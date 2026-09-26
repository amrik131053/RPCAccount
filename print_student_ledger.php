<?php
require('fpdf/fpdf.php');
include 'connection/connection.php'; // <-- SQL Server connection

$id = $_REQUEST['IDNo'] ?? '';
if (empty($id)) {
    die("❌ Student ID missing");
}

// ---------- 1️⃣ Student Info ----------
$studentQuery = "SELECT TOP 1 * FROM Admissions WHERE IDNo = ?";
$stmt = sqlsrv_query($conn, $studentQuery, [$id]);
$student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$student) {
    die("❌ No student found!");
}
$IDNo = $student['IDNo'];

// ---------- 2️⃣ Debit/Credit Summary ----------
$dcQuery = "SELECT 
    ISNULL(SUM(Debit),0) AS totaldebit, 
    ISNULL(SUM(Credit),0) AS totalcredit, 
    ISNULL(SUM(Debit),0) - ISNULL(SUM(Credit),0) AS balance 
FROM Ledger WHERE IDNo = ?";
$stmt = sqlsrv_query($conn, $dcQuery, [$IDNo]);
$debitcredit = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// ---------- 3️⃣ Ledger Entries ----------
$ledgerQuery = "
SELECT * FROM Ledger t1
WHERE t1.IDNo = ?
AND NOT EXISTS (
    SELECT 1 FROM DeadDebits t2 
    WHERE t1.TransactionID = t2.TransactionID AND t1.Debit = t2.Debit
)
ORDER BY t1.TransactionID DESC";
$stmt = sqlsrv_query($conn, $ledgerQuery, [$IDNo]);
$ledgerData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $ledgerData[] = $row;
}

// ---------- 4️⃣ Dead Debits ----------
$deadQuery = "SELECT * FROM DeadDebits WHERE IDNo = ? AND ApprovedDebitStatus BETWEEN '0' AND '1' ORDER BY DateEntry DESC";
$stmt = sqlsrv_query($conn, $deadQuery, [$IDNo]);
$deadDebits = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $deadDebits[] = $row;
}

// ---------- 5️⃣ Pending Concessions ----------
$concQuery = "SELECT * FROM ConcessionEntries WHERE IDNo = ? AND ConcessionStatus BETWEEN '0' AND '1'";
$stmt = sqlsrv_query($conn, $concQuery, [$IDNo]);
$pendingConcessions = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $pendingConcessions[] = $row;
}

// ---------- 6️⃣ Cancelled Receipts ----------
$cancelQuery = "SELECT * FROM CancelledReceipt WHERE IDNo = ? AND ApprovedStatus BETWEEN '0' AND '1' ORDER BY DateEntry DESC";
$stmt = sqlsrv_query($conn, $cancelQuery, [$IDNo]);
$cancelled = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $cancelled[] = $row;
}

// ---------- 7️⃣ Pending Print Receipts ----------
$pprQuery = "SELECT * FROM PrintReceipt WHERE IDNo = ? AND Status = '0' ORDER BY DateEntry DESC";
$stmt = sqlsrv_query($conn, $pprQuery, [$IDNo]);
$pendingPrints = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $pendingPrints[] = $row;
}

// ---------- 8️⃣ FPDF Setup ----------
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',12);
        $this->Cell(0,7,'Account Status Report',0,1,'C');
        $this->Ln(2);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function tableHeader($headers) {
        $this->SetFont('Arial','B',8);
        foreach ($headers as $header => $width) {
            $this->Cell($width, 6, $header, 1, 0, 'C');
        }
        $this->Ln();
    }
}

// ---------- 9️⃣ Generate PDF ----------
$pdf = new PDF('L', 'mm', 'A4'); // Landscape for wide tables
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);

// Student Info
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,"IDNo: {$student['IDNo']}",0,1,'C');
$pdf->Ln(2);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Session / Batch",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(60,6,"{$student['Session']} ({$student['Batch']})",1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Programme",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(140,6,"{$student['Course']}",1);
$pdf->Ln();

$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Student Name",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(60,6,"{$student['StudentName']}",1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Father Name",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(140,6,"{$student['FatherName']}",1);
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Mother Name",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(60,6,"{$student['MotherName']}",1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Sex",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(140,6,"{$student['Sex']}",1);
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Uni / Class Roll No",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(60,6,"{$student['UniRollNo']} / {$student['ClassRollNo']}",1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(40,6,"Address",1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(140,6,"{$student['PermanentAddress']}",1);
$pdf->Ln(10);

// ---------- Ledger Table ----------
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0,6,"Ledger Transactions",0,1,'L');

$headers = [
    "Receipt Date" => 25,
    "Bank Date" => 25,
    "Receipt No" => 25,
    "Particulars" => 100,
    "Semester" => 20,
    "Debit" => 25,
    "Credit" => 25,
    "Remarks" => 35
];
$pdf->tableHeader($headers);

$pdf->SetFont('Arial','',8);
foreach ($ledgerData as $row) {
    // Handle DateEntry
    if (!empty($row['DateEntry'])) {
        $dateEntry = ($row['DateEntry'] instanceof DateTime)
            ? $row['DateEntry']->format('d-m-Y')
            : date('d-m-Y', strtotime($row['DateEntry']));
    } else {
        $dateEntry = '';
    }

    // Handle ValueDate
    if (!empty($row['ValueDate'])) {
        $valueDate = ($row['ValueDate'] instanceof DateTime)
            ? $row['ValueDate']->format('d-m-Y')
            : date('d-m-Y', strtotime($row['ValueDate']));
    } else {
        $valueDate = '';
    }

    // Now safe to print
    $pdf->Cell(25,5,$dateEntry,1);
    $pdf->Cell(25,5,$valueDate,1);
    $pdf->Cell(25,5,$row['ReceiptNo'],1);
    $pdf->Cell(100,5,substr($row['Particulars'],0,50),1);
    $pdf->Cell(20,5,$row['Semester']."/".$row['SemesterID'],1);
    $pdf->Cell(25,5,number_format($row['Debit'],2),1,0,'R');
    $pdf->Cell(25,5,number_format($row['Credit'],2),1,0,'R');
    $pdf->Cell(35,5,$row['Remarks'] ?? '',1);
    $pdf->Ln();
}

// foreach ($ledgerData as $row) {
//     $pdf->Cell(25,5,isset($row['DateEntry']) ? date('d-m-Y', strtotime($row['DateEntry'])) : '',1);
//     $pdf->Cell(25,5,isset($row['ValueDate']) ? date('d-m-Y', strtotime($row['ValueDate'])) : '',1);
//     $pdf->Cell(25,5,$row['ReceiptNo'],1);
//     $pdf->Cell(75,5,substr($row['Particulars'],0,50),1);
//     $pdf->Cell(20,5,$row['Semester']."/".$row['SemesterID'],1);
//     $pdf->Cell(25,5,number_format($row['Debit'],2),1,0,'R');
//     $pdf->Cell(25,5,number_format($row['Credit'],2),1,0,'R');
//     $pdf->Cell(35,5,$row['Remarks'] ?? '',1);
//     $pdf->Ln();
// }
$pdf->Ln(5);

// Totals
$pdf->SetFont('Arial','B',9);
$pdf->Cell(60,7,"Total Debit: ".number_format($debitcredit['totaldebit'],2),1,0,'C');
$pdf->Cell(60,7,"Total Credit: ".number_format($debitcredit['totalcredit'],2),1,0,'C');
$pdf->Cell(60,7,"Balance: ".number_format($debitcredit['balance'],2),1,0,'C');
$pdf->Ln(10);



// ---------- Final Output ----------
$pdf->Output("I", "Account_Status_{$student['IDNo']}.pdf");
?>
