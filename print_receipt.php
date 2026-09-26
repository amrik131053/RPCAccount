<?php
require('fpdf/fpdf.php');
$SlipID=$_GET['SlipID'];
$lagerName=$_GET['ledgerName'];
$session=$_GET['session'];
$IDNo=$_GET['IDNo'];
date_default_timezone_set("Asia/Kolkata");  
   include "connection/connection.php";
    $getStatus="SELECT DateEntrySubmission,Ledger.Remarks,Ledger.credit,Ledger.CollegeName,Ledger.UserID,Ledger.ClassRollNo,Ledger.UniRollNo,Ledger.StudentName,Ledger.FatherName,Ledger.MotherName,Ledger.Sex,Ledger.ReceiptNo,Ledger.DateEntry,Ledger.IDNo, Ledger.Course,Ledger.Batch,Ledger.Semester,Ledger.OnAccountof,Ledger.ChequeDraftBank,Ledger.ChequeDraftNo, CONVERT(VARCHAR(11),Ledger.ChequeDraftDate,106) as ChequeDraftDate ,Ledger.ModeOfPayment as ModeOfPayment, Ledger.Credit as Credit1,Ledger.CashAmount,Ledger.OtherAmount,Ledger.ReferenceNumber,Ledger.SemesterID  from Ledger  Where   Ledger.LedgerName='$lagerName' And  Ledger.ReceiptNo='$SlipID' And   Ledger.Session='$session' and IDNo='$IDNo'";
   $getStatusRun=sqlsrv_query($conn,$getStatus);
   if($getStatusRow=sqlsrv_fetch_array($getStatusRun))
   {  
        $Remarks=$getStatusRow['Remarks'];
        $credit=$getStatusRow['credit'];
        $CollegeName=$getStatusRow['CollegeName'];
        $UserID=$getStatusRow['UserID'];
        $ClassRollNo=$getStatusRow['ClassRollNo'];
        $UniRollNo=$getStatusRow['UniRollNo'];
        $StudentName=$getStatusRow['StudentName'];
        $FatherName=$getStatusRow['FatherName'];
        $MotherName=$getStatusRow['MotherName'];
        $Sex=$getStatusRow['Sex'];
        if($Sex=='Male')
        {
            $gender="S/o";
        }
        else{
            $gender="D/o";
        }
        $ReceiptNo=$getStatusRow['ReceiptNo'];
        $DateEntry=$getStatusRow['DateEntry']->format('d-m-Y');
        $Course=$getStatusRow['Course'];
        $Batch=$getStatusRow['Batch'];
        $Semester=$getStatusRow['Semester'];
        $SemesterID=$getStatusRow['SemesterID'];
        $OnAccountof=$getStatusRow['OnAccountof'];
        $ChequeDraftBank=$getStatusRow['ChequeDraftBank'];
        $ChequeDraftNo=$getStatusRow['ChequeDraftNo']; 
        $ChequeDraftDate1 = $getStatusRow['ChequeDraftDate'] ?? $getStatusRow['DateEntrySubmission'];
        $ChequeDraftDate = ''; 
        if (!empty($ChequeDraftDate1)) {
            if ($ChequeDraftDate1 instanceof DateTime) {
                $ChequeDraftDate = $ChequeDraftDate1->format('d-m-Y');
            } else {
                $possibleFormats = ['d/m/Y', 'Y-m-d', 'Y/m/d'];
                $date1 = false;
        
                foreach ($possibleFormats as $format) {
                    $date1 = DateTime::createFromFormat($format, $ChequeDraftDate1);
                    if ($date1 !== false) {
                        break;
                    }
                }
        
                if ($date1 !== false) {
                    $ChequeDraftDate = $date1->format('d-m-Y');
                } else {
                    $ChequeDraftDate = $ChequeDraftDate1;
                }
            }
        }
        

        $Credit1=$getStatusRow['Credit1'];
        $CashAmount=$getStatusRow['CashAmount'];
        $OtherAmount=$getStatusRow['OtherAmount'];
        $ModeOfPayment=$getStatusRow['ModeOfPayment'];
        $ReferenceNumber=$getStatusRow['ReferenceNumber'];
       
      $result1 = "SELECT  * FROM Admissions where IDNo='$IDNo'";
      $stmt1 = sqlsrv_query($conn,$result1);
      if($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC) )
      {
       $UniRollNo= $row['UniRollNo'];
       $img= $row['Image'];
       $name = $row['StudentName'];
       $father_name = $row['FatherName'];
       $mother_name = $row['MotherName'];
       $course = $row['Course'];
       $email = $row['EmailID'];
       $batch = $row['Batch'];
       $college = $row['CollegeName'];
      }
  
      $number = $Credit1;
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'One', '2' => 'Two',
     '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'six',
     '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
     '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
     '13' => 'Thirteen', '14' => 'Fourteen',
     '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
     '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
     '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
     '60' => 'Sixty', '70' => 'Seventy',
     '80' => 'Eighty', '90' => 'Ninety');
    $digits = array('', 'hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
      $divider = ($i == 2) ? 10 : 100;
      $number = floor($no % $divider);
      $no = floor($no / $divider);
      $i += ($divider == 10) ? 1 : 2;
      if ($number) {
         $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
         $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
         $str [] = ($number < 21) ? $words[$number] .
             " " . $digits[$counter] . $plural . " " . $hundred
             :
             $words[floor($number / 10) * 10]
             . " " . $words[$number % 10] . " "
             . $digits[$counter] . $plural . " " . $hundred;
      } else $str[] = null;
   }
   $str = array_reverse($str);
   $result = implode('', $str);
   $points = ($point) ?
     "." . $words[$point / 10] . " " . 
           $words[$point = $point % 10] : '';
   $ammountInwords= $result . "Rupees " . $points;
      $receiptData = [
        'ReceiptNo' => $ReceiptNo,
        'DateEntry' => $DateEntry,
        'StudentName' => $StudentName,
        'FatherName' => $FatherName,
        'Course' => $Course,
        'Batch' => $Batch,
        'ClassRollNo' => $ClassRollNo,
        'IDNo' => $IDNo,
        'gender' => $gender,
        'OnAccountof' => $OnAccountof,
        'Semester' => $Semester,
        'SemesterID' => $SemesterID,
        'UniRollNo' => $UniRollNo,
    ];
    $paymentData = [
        'ModeOfPayment' => $ModeOfPayment,
        'ChequeDraftNo' => $ChequeDraftNo,
        'ChequeDraftBank' => $ChequeDraftBank,
        'ReferenceNumber' => $ReferenceNumber,
        'ChequeDraftDate' => $ChequeDraftDate,
        'Credit' => $Credit1,
    ];
    $footerData = [
        'AmountInWords' => $ammountInwords,
        'ModeOfPayment' => $ModeOfPayment,
    ];
   }
class CustomPDF extends FPDF {
    function Footer() {   
    }   
} 
class PDF extends FPDF
{
    // Optional: If you need to set transparency for all content
    public function SetAlpha($alpha = 1) {
        $this->_out('q ' . $alpha . ' 0 0 ' . $alpha . ' 0 0 cm'); // Apply alpha
    }
    public function AddWatermark($imagePath, $width = 100, $height = 50,$opacity=0.5) {
        // Center the watermark on the page
        // $this->SetAlpha($opacity);
        $pageWidth = $this->GetPageWidth();
        $pageHeight = $this->GetPageHeight();

        $x = ($pageWidth - $width) / 2;  // Center horizontally
        $y = ($pageHeight - $height) / 7; // Center vertically

        // Add the watermark image
        $this->Image($imagePath, $x, $y, $width, $height);
    }
    function Header()
    {
        $X=10;
        $Y=13;
        $this->AddWatermark('styles/images/logo-watermakr.png',90,80);
                $this->SetXY($X, $Y);
        $this->Image('styles/images/logo-login.png', 15, 13, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Guru Kashi University', 0, 1, 'C');
        $this->SetXY($X, $Y+6);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, $this->collegeName, 0, 1, 'C');
        $this->SetXY($X, $Y+11);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, 'Sardulgarh Road, Talwandi Sabo ', 0, 1, 'C');
    }
    function Footer()
    {
        // $this->SetY(-15);
        // $this->SetFont('Arial', 'I', 8);
        // $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
    function AddReceiptDetails($details)
    {
        $X=15;
        $Y=35;
        $this->SetXY($X, $Y);
        $this->SetFont('Arial', 'B', 9.5);
        $this->Cell(0, 4, 'Receipt No: ' . $details['ReceiptNo'], 0, 1,'L');
        $this->SetXY($X, $Y);
        $this->Cell(179, 4, 'Date: ' . $details['DateEntry'], 0, 1,'R');


        $this->SetXY($X, $Y+6);
        $this->Cell(30, 4, 'Received From: ', 0, 0);
        $this->SetXY($X+30, $Y+5.5);
        $this->Cell(149, 5, $details['StudentName'] .' '. $details['gender'].' ' . $details['FatherName'],'B', 0, 1);

        $this->SetXY($X, $Y+12);
        $this->Cell(30, 4, 'Programme: ', 0, 0);
        $this->SetXY($X+30, $Y+11.5);
    
        $this->Cell(125, 5, $details['Course'],'B', 0, 1);
        $this->SetXY($X+148, $Y+12);
        $this->Cell(20, 4, "Batch:",'', 1,'R');
        $this->SetXY($X+167, $Y+11.5);
        $this->Cell(12, 5,$details['Batch'],'B', 1,'R');

        $this->SetXY($X, $Y+18);
        $this->Cell(30, 4, 'Class Roll No: ', 0, 0);
        $this->SetXY($X+30, $Y+17.5);
        $this->Cell(20, 5, $details['ClassRollNo'],'B', 1, 1);

        $this->SetXY($X+50, $Y+18);
        $this->Cell(22, 4, "Uni Roll No:", 0, 1,'L');
        $this->SetXY($X+71, $Y+17.5);
        $this->Cell(31, 5, $details['UniRollNo'], 'B', 1,'L');

        $this->SetXY($X+102, $Y+18);
        $this->Cell(25, 4, "ID/Reg. No: ", 0, 1,'L');

        $this->SetXY($X+121, $Y+17.5);
        $this->Cell(22, 5, $details['IDNo'], 'B', 1,'L');
                 
        $this->SetXY($X+143 , $Y+18);
        $this->Cell(25, 4, "Semester: ", 0, 1,'L');

        $this->SetXY($X+160.5, $Y+17.5);
        $this->Cell(18.5, 5, $details['Semester'].' ('.$details['SemesterID'].')', 'B', 1,'L');
                 
        $this->SetXY($X, $Y+24);
        $this->Cell(30, 4, 'On Account of:', 0, 0);
        $this->SetXY($X+30, $Y+23.5);
        $this->Cell(149, 5,$details['OnAccountof'],'B', 0, 1);
        $this->SetXY($X+118, $Y+23);
    }
    function AddPaymentDetails($payment)
    {
 
        $X=16;
        $Y=28;
        $this->SetXY($X, $Y+40);
        $this->SetFont('Arial', 'B', 9.5);
        $this->Cell(10, 7, 'S.No.', 1, 0, 'C');
        $this->Cell(128, 7, 'Particulars', 1, 0, 'C');
        $this->Cell(40, 7, 'Amount', 1, 1, 'C');

        $this->SetFont('Arial', 'B', 9.5);
        if ($payment['ModeOfPayment'] == 'Bank Transfer') {
            $this->SetXY($X, $Y+47);
            $this->Cell(10, 19, '1', 1, 0, 'C');
            $this->Cell(128.5, 7, 'Cheque/Draft No: ' . $payment['ChequeDraftNo'], 0, 0);
            $this->SetXY($X+10, $Y+52);
            $this->Cell(128.5, 8, 'Bank Date: ' . $payment['ChequeDraftDate'], 0, 0);
            $this->SetXY($X+10, $Y+57);
            $this->Cell(128.5, 8, 'Bank: ' . $payment['ChequeDraftBank'], 0, 0);
            $this->SetXY($X+138, $Y+47);
            $this->Cell(40, 19, $payment['Credit'], 1, 1,'C');
        }
        else if ($payment['ModeOfPayment'] == 'Payment Gateway') {
            $this->SetXY($X, $Y+47);
            $this->Cell(10, 19, '1', 1, 0, 'C');
            $this->Cell(128.5, 7, 'Reference No: ' . $payment['ReferenceNumber'], 0, 0);
            $this->SetXY($X+10, $Y+52);
            $this->Cell(128.5, 8, 'Date: ' . $payment['ChequeDraftDate'], 0, 0);
          
            $this->SetXY($X+138, $Y+47);
            $this->Cell(40, 19,$payment['Credit'], 1, 1,'C');
        }
         else {
            $this->SetXY($X, $Y+47);
            $this->Cell(10, 19, '1', 1, 0, 'C');
            $this->Cell(128, 19, 'Receipt', 1, 0);
            $this->Cell(40, 19, 'Rs. '.$payment['Credit'].'/-', 1, 1,'C');
        }

        $this->SetXY($X, $Y+66);
        $this->SetFont('Arial', 'B', 9.5);
        $this->Cell(138, 7, 'Total', 1, 0, 'R');
        $this->Cell(40, 7, 'Rs. '.$payment['Credit'].'/-', 1, 1, 'C');
    }
    function AddFooterDetails($footer)
    {
        $X=$this->GETX();
        $Y=$this->GETY();
        $this->SetXY($X+5, $Y+2);
        $this->SetFont('Arial', 'B', 9.5);
        $this->Cell(0, 4, 'Received Rs. ' , 0, 1);
        $this->SetFont('Arial', '', 9.5);
        $this->SetXY($X+29, $Y+2);
        $this->Cell(126, 4, $footer['AmountInWords'] . ' by (' . $footer['ModeOfPayment'].')', 0, 1);
        $this->SetFont('Arial', 'B', 9.5);
       
        $X=$this->GETX();
        $Y=$this->GETY();
        $this->SetXY($X+6, $Y+3);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(178, 8, 'This is computer generated receipt, no signature required ', '', 1, 'L');
        $this->SetXY($X+6, $Y+3);
        setlocale(LC_TIME, "");
        date_default_timezone_set('Europe/Paris');
        $date_du_jour = utf8_encode(strftime('%A %d %B %Y'));
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(178, 8, 'Print Date:'.$date_du_jour, '', 1, 'R');
    }
}
$pdf = new PDF();
$pdf->collegeName = $CollegeName;
$pdf->AddPage();
$pdf->AddReceiptDetails($receiptData);
$pdf->AddPaymentDetails($paymentData);
$pdf->AddFooterDetails($footerData);
$pdf->Output('I','ReceiptNo_'.$ReceiptNo.'.pdf'); 