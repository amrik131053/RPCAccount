 <?php
session_start();
ini_set('max_execution_time','0');
ob_start();
header("Content-Type: application/xls");
header("Pragma: no-cache");
header("Expires: 0");
include 'connection/connection.php';
$exportCode ='';
$role_id ='';
$fileName = 'My File';

if (isset($_REQUEST['role_id']))
{
    $role_id = $_REQUEST['role_id'];
}
if (isset($_REQUEST['exportCode']))
{
    $exportCode = $_REQUEST['exportCode'];
}
//Day Book


if ($_GET['exportCode'] == 1) {
   

    // Collect filters
$startDate = $_REQUEST['StartDate'] ?? '';
 $endDate = $_REQUEST['EndDate'] ?? '';
    $headId = $_REQUEST['Head'] ?? '';
    $session = $_REQUEST['session'] ?? '';
    $ledgername = $_REQUEST['ledgername'] ?? '';
    $modeofpayment = $_REQUEST['modeofpayment'] ?? '';
    $empid = $_REQUEST['empid'] ?? '';
    $orderby = $_REQUEST['orderby'] ?? 'ASC';

    // Get Ledger Head (if ID provided)
    if (!empty($ledgername)) {
        $stmtHead = $pdo->prepare("SELECT Head FROM MasterHeadNew WHERE Status = 1 AND Id = ?");
        $stmtHead->execute([$ledgername]);
        $headRow = $stmtHead->fetch(PDO::FETCH_ASSOC);
        if ($headRow) {
            $ledgername = $headRow['Head'];
        }
    }

    // Start building the query
    $query = "SELECT * FROM Ledger WHERE  ModeOfPayment!='Other Cash' AND  DateEntry BETWEEN ? AND ? AND Credit > 0";
    $params = [$startDate . ' 00:01', $endDate . ' 23:59'];

    if (!empty($session)) {
        $query .= " AND Session = ?";
        $params[] = $session;
    }
    if (!empty($ledgername)) {
        $query .= " AND LedgerName = ?";
        $params[] = $ledgername;
    }
    if (!empty($modeofpayment)) {
        $query .= " AND ModeOfPayment = ?";
        $params[] = $modeofpayment;
    }
    if (!empty($empid)) {
        $query .= " AND UserID = ?";
        $params[] = $empid;
    }

    $query .= "And  ModeOfPayment!='Receipt' ORDER BY ReceiptNo $orderby";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get Total Credit
    $sumQuery = "SELECT SUM(Credit) AS TotalCredit FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";
    $sumParams = [$startDate . ' 00:01', $endDate . ' 23:59'];

    if (!empty($session)) {
        $sumQuery .= " AND Session = ?";
        $sumParams[] = $session;
    }
    if (!empty($ledgername)) {
        $sumQuery .= " AND LedgerName = ?";
        $sumParams[] = $ledgername;
    }
    if (!empty($modeofpayment)) {
        $sumQuery .= " AND ModeOfPayment = ?";
        $sumParams[] = $modeofpayment;
    }
    if (!empty($empid)) {
        $sumQuery .= " AND UserID = ?";
        $sumParams[] = $empid;
    }

  $stmtSum = $pdo->prepare($sumQuery);
    $stmtSum->execute($sumParams);
    $creditTotal = $stmtSum->fetch(PDO::FETCH_ASSOC)['TotalCredit'] ?? 0;

    // Set headers for Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Daybook_Export_" . date("Ymd_His") . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Start table
    echo "<table border='1'>";
    echo "<thead>
        <tr>
            <th>Sr. No</th>
            <th>Date Entry</th>
            <th>Ledger Name</th>
            <th>Session</th>
            <th>IDNo</th>
              <th>Class Roll No</th>
               <th>Uni Roll No</th>
                <th>Name</th>
                 <th>Father Name</th>
                  <th>Mother Name</th>
                   <th>Faculty</th>
                    <th>Program</th>
                      <th>Batch</th>
                        <th>Semester</th>

            <th>Amount</th>
            <th>Receipt No</th>
            <th>Mode of Payment</th>
            <th>User ID</th>
            <th>Payment ID</th>
            <th>Payment Reference No</th>
             <th>UTR</th>
              <th>Payment Date</th>
              <th>Bank Name</th>
            
        </tr>
    </thead><tbody>";

    $count = 1;
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            $dateEntry = is_object($row['DateEntry']) ? $row['DateEntry']->format('Y-m-d H:i:s') : $row['DateEntry'];

             $ChequeDraftDate1 = $row['ChequeDraftDate'] ?? $row['DateEntrySubmission'];


            echo "<tr>
                <td>{$count}</td>
                <td>{$dateEntry}</td>
                <td>{$row['LedgerName']}</td>
                <td>{$row['Session']}</td>
                 <td>{$row['IDNo']}</td>
              <td>{$row['ClassRollNo']}</td>
               <td>{$row['UniRollNo']}</td>
                <td>{$row['StudentName']}</td>
                 <td>{$row['FatherName']}</td>
                  <td>{$row['MotherName']}</td>
                    <td>{$row['CollegeName']}</td>
                     <td>{$row['Course']}</td>
                      <td>{$row['Batch']}</td>
                        <td>{$row['Semester']}</td>



                <td>{$row['Credit']}</td>
                 <td>{$row['ReceiptNo']}</td>
                <td>{$row['ModeOfPayment']}</td>
                <td>{$row['UserID']}</td>
                 <td>{$row['OnlineTransactionID']}</td>
     <td>{$row['ReferenceNumber']}</td>
      <td>{$row['ChequeDraftNo']}</td>
    
    <td>{$ChequeDraftDate1}</td>





         <td>{$row['ChequeDraftBank']}</td>
               
            </tr>";
            $count++;
        }

        echo "<tr>
            <td colspan='13'><strong>Total Credit</strong></td>
            <td><strong>{$creditTotal}</strong></td>
            <td colspan='3'></td>
        </tr>";
    } else {
        echo "<tr><td colspan='8'>No records found.</td></tr>";
    }

    echo "</tbody></table>";

    $fileName="Day Book";
}
else if ($_GET['exportCode'] == 1.1) {
   

    // Collect filters
$startDate = $_REQUEST['StartDate'] ?? '';
 $endDate = $_REQUEST['EndDate'] ?? '';
    $headId = $_REQUEST['Head'] ?? '';
    $session = $_REQUEST['session'] ?? '';
    $ledgername = $_REQUEST['ledgername'] ?? '';
    $modeofpayment = $_REQUEST['modeofpayment'] ?? '';
    $empid = $_REQUEST['empid'] ?? '';
    $orderby = $_REQUEST['orderby'] ?? 'ASC';

    // Get Ledger Head (if ID provided)
    if (!empty($ledgername)) {
        $stmtHead = $pdo->prepare("SELECT Head FROM MasterHeadNew WHERE Status = 1 AND Id = ?");
        $stmtHead->execute([$ledgername]);
        $headRow = $stmtHead->fetch(PDO::FETCH_ASSOC);
        if ($headRow) {
            $ledgername = $headRow['Head'];
        }
    }

    // Start building the query
    $query = "SELECT * FROM Ledger WHERE  ModeOfPayment!='Other Cash' AND  DateEntry BETWEEN ? AND ? AND Credit > 0";
    $params = [$startDate . ' 00:01', $endDate . ' 23:59'];

    if (!empty($session)) {
        $query .= " AND Session = ?";
        $params[] = $session;
    }
    if (!empty($ledgername)) {
        $query .= " AND LedgerName = ?";
        $params[] = $ledgername;
    }
    if (!empty($modeofpayment)) {
        $query .= " AND ModeOfPayment = ?";
        $params[] = $modeofpayment;
    }
    if (!empty($empid)) {
        $query .= " AND UserID = ?";
        $params[] = $empid;
    }

    $query .= " ORDER BY ReceiptNo $orderby";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get Total Credit
    $sumQuery = "SELECT SUM(Credit) AS TotalCredit FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";
    $sumParams = [$startDate . ' 00:01', $endDate . ' 23:59'];

    if (!empty($session)) {
        $sumQuery .= " AND Session = ?";
        $sumParams[] = $session;
    }
    if (!empty($ledgername)) {
        $sumQuery .= " AND LedgerName = ?";
        $sumParams[] = $ledgername;
    }
    if (!empty($modeofpayment)) {
        $sumQuery .= " AND ModeOfPayment = ?";
        $sumParams[] = $modeofpayment;
    }
    if (!empty($empid)) {
        $sumQuery .= " AND UserID = ?";
        $sumParams[] = $empid;
    }

  $stmtSum = $pdo->prepare($sumQuery);
    $stmtSum->execute($sumParams);
    $creditTotal = $stmtSum->fetch(PDO::FETCH_ASSOC)['TotalCredit'] ?? 0;

    // Set headers for Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Daybook_Export_" . date("Ymd_His") . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Start table
    echo "<table border='1'>";
    echo "<thead>
        <tr>
            <th>Sr. No</th>
            <th>Date Entry</th>
            <th>Ledger Name</th>
            <th>Session</th>
            <th>IDNo</th>
              <th>Class Roll No</th>
               <th>Uni Roll No</th>
                <th>Name</th>
                 <th>Father Name</th>
                  <th>Mother Name</th>
                   <th>Faculty</th>
                    <th>Program</th>
                      <th>Batch</th>
                        <th>Semester</th>

            <th>Amount</th>
            <th>Receipt No</th>
            <th>Mode of Payment</th>
            <th>User ID</th>
            <th>Payment ID</th>
            <th>Payment Reference No</th>
             <th>UTR</th>
              <th>Payment Date</th>
              <th>Bank Name</th>
            
        </tr>
    </thead><tbody>";

    $count = 1;
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            $dateEntry = is_object($row['DateEntry']) ? $row['DateEntry']->format('Y-m-d H:i:s') : $row['DateEntry'];

             $ChequeDraftDate1 = $row['ChequeDraftDate'] ?? $row['DateEntrySubmission'];


            echo "<tr>
                <td>{$count}</td>
                <td>{$dateEntry}</td>
                <td>{$row['LedgerName']}</td>
                <td>{$row['Session']}</td>
                 <td>{$row['IDNo']}</td>
              <td>{$row['ClassRollNo']}</td>
               <td>{$row['UniRollNo']}</td>
                <td>{$row['StudentName']}</td>
                 <td>{$row['FatherName']}</td>
                  <td>{$row['MotherName']}</td>
                    <td>{$row['CollegeName']}</td>
                     <td>{$row['Course']}</td>
                      <td>{$row['Batch']}</td>
                        <td>{$row['Semester']}</td>



                <td>{$row['Credit']}</td>
                 <td>{$row['ReceiptNo']}</td>
                <td>{$row['ModeOfPayment']}</td>
                <td>{$row['UserID']}</td>
                 <td>{$row['OnlineTransactionID']}</td>
     <td>{$row['ReferenceNumber']}</td>
      <td>{$row['ChequeDraftNo']}</td>
    
    <td>{$ChequeDraftDate1}</td>





         <td>{$row['ChequeDraftBank']}</td>
               
            </tr>";
            $count++;
        }

        echo "<tr>
            <td colspan='13'><strong>Total Credit</strong></td>
            <td><strong>{$creditTotal}</strong></td>
            <td colspan='3'></td>
        </tr>";
    } else {
        echo "<tr><td colspan='8'>No records found.</td></tr>";
    }

    echo "</tbody></table>";

    $fileName="Day Book";
}

else if ($_GET['exportCode'] == 2) {  

  
    $sql = "SELECT * FROM PrintReceipt as pr  inner join  Admissions  as A  on  pr.IDNo=a.IDNo where pr.Status='0'";

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $records = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $records[] = $row;
    }
    if (count($records) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }

    // ----------------- Export to Excel -----------------
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Pendingreceipt_" . date('d-m-Y') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<thead>
            <tr>
                 <th>IDNo</th>
                 <th>Uni RollNo</th>
                  <th>Class RollNo</th>

                <th>Name</th>
                <th>Father Name</th>
                <th>Semester</th>
                <th>Amount</th>
                
                <th>Create Date</th>
                <th>Particular</th>
                 <th>Ledger</th>  <th>Mode Of Payment</th>
                <th>Remarks</th>
              
                <th>Status</th>
                <th>Created By</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($records as $item) {
      
        

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';

        echo "<tr>
                
                    
                    <td>{$item['IDNo']}</td>
                    <td>{$item['UniRollNo']}</td>
                    <td>{$item['ClassRollNo']}</td>
                <td>{$item['StudentName']}</td>
                <td>{$item['FatherName']}</td>
                <td>{$item['SemesterID']}</td>
                <td>{$item['Credit']}</td>
                <td>$dateEntry</td>
                <td>{$item['Particulars']}</td>
                  <td>{$item['DebitHead']}</td>
                    <td>{$item['ModeOfPayment']}</td>
                <td>{$item['Comments']}</td>
                <td>Pending</td>
                <td>{$item['CreatedBy']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    exit; // Important: stop further HTML output
 



}
elseif ($exportCode == 3) {

    $collegeid = $_REQUEST['collegeid'] ?? '';
    $courseid = $_REQUEST['courseid'] ?? '';
    $session = $_REQUEST['feesession'] ?? '';
    $batch = $_REQUEST['batch'] ?? '';
    $semester = $_REQUEST['semester'] ?? 0;
    $feeCategory = $_REQUEST['annualfeecategory'] ?? '';
    $passout = $_REQUEST['passout'] ?? 0;
    $status = $_REQUEST['enrollment'] ?? '1';


    $where = "WHERE 1=1";

    if (!empty($status)) $where .= " AND Status='$status'";
    if ($passout == 0) $where .= " AND (PassOut != '1' OR PassOut IS NULL)";
    else if ($passout == 1) $where .= " AND PassOut='1'";
    if (!empty($collegeid)) $where .= " AND CollegeID='$collegeid'";
    if (!empty($batch)) $where .= " AND Batch='$batch'";
    if (!empty($session)) $where .= " AND Session='$session'";
    if (!empty($courseid)) $where .= " AND CourseID='$courseid'";
    if (!empty($feeCategory)) $where .= " AND FeeCategory='$feeCategory'";

    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                   StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, 
                   IDNo, StudentMobileNo, FeeCategory, CommentFromAcc, CommentsDetail,
                   Course, Status
            FROM Admissions $where";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $students = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $students[] = $row;
    }

    if (count($students) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }

    $idList = implode(",", array_map(function ($s) {
        return "'" . $s['IDNo'] . "'";
    }, $students));

    $ledgerSql = "SELECT IDNo, SUM(Debit) AS totaldebit, SUM(Credit) AS totalcredit 
                  FROM Ledger 
                  WHERE IDNo IN ($idList)";
    if (!empty($semester) && $semester != 0) {
        $ledgerSql .= " AND SemesterID <= '$semester'";
    }
    $ledgerSql .= " GROUP BY IDNo";

    $ledgerStmt = sqlsrv_query($conn, $ledgerSql);
    $ledgerData = [];
    while ($row = sqlsrv_fetch_array($ledgerStmt, SQLSRV_FETCH_ASSOC)) {
        $ledgerData[$row['IDNo']] = [
            'totaldebit' => $row['totaldebit'] ?? 0,
            'totalcredit' => $row['totalcredit'] ?? 0
        ];
    }

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Student_Fee_Report_" . date('Ymd_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "<table border='1'>";
    echo "<tr style='background-color:#e1e1e1; font-weight:bold;'>
        <th>Sr No</th>
        <th>Session</th>
        <th>Class RollNo</th>
        <th>Uni RollNo</th>
        <th>IDNo</th>
        <th>Student Name</th>
        <th>Father Name</th>
        <th>College</th>
        <th>Course</th>
        <th>Account Comment</th>
        <th>Comment</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Balance</th>
        <th>Status</th>
    </tr>";

    $sr = 1;
    foreach ($students as $stu) {
        $ledger = $ledgerData[$stu['IDNo']] ?? ['totaldebit' => 0, 'totalcredit' => 0];
        $debit = $ledger['totaldebit'];
        $credit = $ledger['totalcredit'];
        $balance = $debit - $credit;

        echo "<tr>
        <td>{$sr}</td>
        <td>{$stu['Session']}</td>
        <td>{$stu['ClassRollNo']}</td>
        <td>{$stu['UniRollNo']}</td>
        <td>{$stu['IDNo']}</td>
        <td>{$stu['StudentName']}</td>
        <td>{$stu['FatherName']}</td>
        <td>{$stu['CollegeName']}</td>
        <td>{$stu['Course']}</td>
         <td>{$stu['CommentFromAcc']}</td>
          <td>{$stu['CommentsDetail']}</td>

        <td>{$debit}</td>
        <td>{$credit}</td>
        <td>{$balance}</td>
        <td>" . ($stu['Status'] == 1 ? "Active" : "Left") . "</td>
      </tr>";

        $sr++;
    }

    echo "</table>";
}
elseif ($exportCode == 4) {

    $startdate = $_REQUEST['startdate'] ?? '';
    $enddate = $_REQUEST['enddate'] ?? '';
    $status = $_REQUEST['status'] ?? null;

   
    $givenStartDate = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $givenEndDate   = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $where = "WHERE DateEntry BETWEEN '$givenStartDate' AND '$givenEndDate'";
    if (!is_null($status) && $status !== '') {
        $where .= " AND ConcessionStatus = '$status'";
    }

    $sql = "SELECT * FROM ConcessionEntries $where ORDER BY DateEntry DESC";

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $records = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $records[] = $row;
    }
    if (count($records) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }

    // ----------------- Export to Excel -----------------
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=ConcessionReport_" . date('d-m-Y') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<thead> 
            <tr>
                <th>Create Date</th>
                <th>Particular</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Semester</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Created By</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($records as $item) {
        $statusText = '';
        if ($item['ConcessionStatus'] == '0') {
            $statusText = 'Pending to Verify';
        } elseif ($item['ConcessionStatus'] == '1') {
            $statusText = 'Pending to Approve';
        } elseif ($item['ConcessionStatus'] == '2') {
            $statusText = 'Approved';
        } elseif ($item['ConcessionStatus'] == '-1') {
            $statusText = 'Deleted';
        }

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';

        echo "<tr>
                <td>$dateEntry</td>
                <td>{$item['Particulars']}</td>
                <td>{$item['StudentName']}</td>
                <td>{$item['FatherName']}</td>
                <td>{$item['SemesterID']}</td>
                <td>{$item['Debit']}</td>
                <td>{$item['Remarks']}</td>
                <td>$statusText</td>
                <td>{$item['CreatedBy']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    exit; // Important: stop further HTML output
}
elseif ($exportCode == 5) {

    $startdate = $_REQUEST['startdate'] ?? '';
    $enddate = $_REQUEST['enddate'] ?? '';
    $status = $_REQUEST['status'] ?? null;

   
    $givenStartDate = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $givenEndDate   = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $where = "WHERE CreatedDate BETWEEN '$givenStartDate' AND '$givenEndDate'";
    if (!is_null($status) && $status !== '') {
        $where .= " AND ReceiptStatus = '$status'";
    }

    $sql = "SELECT * FROM CancelledReceipt $where ORDER BY DateEntry DESC";

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $records = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $records[] = $row;
    }
    if (count($records) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }

    // ----------------- Export to Excel -----------------
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=CancelReport_" . date('d-m-Y') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<thead>
            <tr>
                <th>Date Entry</th>
                 <th>ClassRollNo</th>
                 <th>IDNo</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Receipt No</th>
                <th>Semester</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Particular</th>
                 <th>Mode Of Payment</th>
                <th>Bank Date</th>
                <th>Status</th>
                <th>Create Date</th>
                <th>Created By</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($records as $item) {
        $statusText = '';
        if ($item['ReceiptStatus'] == '0') {
            $statusText = 'Pending to Verify';
        } elseif ($item['ReceiptStatus'] == '1') {
            $statusText = 'Pending to Approve';
        } elseif ($item['ReceiptStatus'] == '2') {
            $statusText = 'Approved';
        } elseif ($item['ReceiptStatus'] == '-1') {
            $statusText = 'Deleted';
        }

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';

        $dateEntryc = isset($item['DateEntrySubmission']) ? $item['DateEntrySubmission']->format('d-m-Y') : '';

         $CreatedDate = isset($item['CreatedDate']) ? $item['CreatedDate']->format('d-m-Y') : '';

        echo "<tr>
                <td>$dateEntry</td>
                <td>{$item['IDNo']}</td>
                 <td>{$item['ClassRollNo']}</td>
                <td>{$item['StudentName']}</td>
                <td>{$item['FatherName']}</td>
                  <td>{$item['ReceiptNo']}</td>
                <td>{$item['SemesterID']}</td>
                <td>{$item['Credit']}</td>
                <td>{$item['Comments']}</td>
                 <td>{$item['Particulars']}</td>
                  <td>{$item['ModeOfPayment']}</td>

                            <td>$dateEntryc</td>
                  
                 <td>$statusText</td>
                <td>$CreatedDate</td>
               
                <td>{$item['CreatedBy']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    exit; // Important: stop further HTML output
}
elseif ($exportCode == 6) {

    $startdate = $_REQUEST['startdate'] ?? '';
    $enddate = $_REQUEST['enddate'] ?? '';
    $status = $_REQUEST['status'] ?? null;

   
    $givenStartDate = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $givenEndDate   = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $where = "WHERE DateEntry BETWEEN '$givenStartDate' AND '$givenEndDate'";
    if (!is_null($status) && $status !== '') {
        $where .= " AND DebitStatus = '$status'";
    }

    $sql = "SELECT * FROM DeadDebits $where ORDER BY DateEntry DESC";

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $records = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $records[] = $row;
    }
    if (count($records) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }
    // ----------------- Export to Excel -----------------
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Delete_Debit_Reports_" . date('d-m-Y') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "<table border='1'>";
    echo "<thead>
            <tr>
                <th>Create Date</th>
                <th>Particular</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Semester</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Created By</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($records as $item) {
        $statusText = '';
        if ($item['DebitStatus'] == '0') {
            $statusText = 'Pending to Verify';
        } elseif ($item['DebitStatus'] == '1') {
            $statusText = 'Pending to Approve';
        } elseif ($item['DebitStatus'] == '2') {
            $statusText = 'Approved';
        } elseif ($item['DebitStatus'] == '-1') {
            $statusText = 'Deleted';
        }
        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';
        echo "<tr>
                <td>$dateEntry</td>
                <td>{$item['Particulars']}</td>
                <td>{$item['StudentName']}</td>
                <td>{$item['FatherName']}</td>
                <td>{$item['SemesterID']}</td>
                <td>{$item['Debit']}</td>
                <td>{$item['Comments']}</td>
                <td>$statusText</td>
                <td>{$item['CreatedBy']}</td>
              </tr>";
    }

    echo "</tbody></table>";
    exit; // Important: stop further HTML output
}

header("Content-Disposition: attachment; filename=" . $fileName . ".xls");
ob_end_flush();