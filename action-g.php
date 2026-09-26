<?php session_start();
date_default_timezone_set("Asia/Kolkata");
$timeStamp=date('Y-m-d H-i');
$todaydate=date('Y-m-d');
$currentSmallDate = date('Y-m-d H:i:s');
ini_set('max_execution_time', '0');
if (!(isset($_SESSION['user_ac']))) 
{
?>
<script>
window.location.href = 'index.php';
</script>
<?php
   } 
   else
   {
    function interpolateQuery($query, $params) {
        $values = $params;
    
        foreach ($values as $value) {
            // Convert DateTime to string
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s'); // adjust format if needed
            }
    
            // Quote if not numeric
            $replacement = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
    
            // Replace the first occurrence of ?
            $query = preg_replace('/\?/', $replacement, $query, 1);
        }
    
        return $query;
    }
    
    
    function getSemesterName($semId) {
        $names = [
            1 => 'First', 2 => 'Second', 3 => 'Third', 4 => 'Fourth',
            5 => 'Fifth', 6 => 'Sixth', 7 => 'Seventh', 8 => 'Eighth',
            9 => 'Ninth', 10 => 'Tenth', 11 => 'Eleventh', 12 => 'Twelfth',
            13 => 'Thirteenth', 14 => 'Fourteenth', 15 => 'Fifteenth', 16 => 'Sixteenth',
            17 => 'Seventeenth', 18 => 'Eighteenth', 19 => 'Nineteenth', 20 => 'Twentieth'
        ];
        return $names[$semId] ?? null;
    }
 function generateRefund($receiptid, $conn, $EmployeeID, $currentSmallDate) {
        // 1. Fetch receipt details
        $sqlReceipt = "SELECT * FROM PrintReceipt WHERE ID = ?";
        $stmtReceipt = sqlsrv_query($conn, $sqlReceipt, [$receiptid]);
        if(!$stmtReceipt) return 0;
        $receipt = sqlsrv_fetch_array($stmtReceipt, SQLSRV_FETCH_ASSOC);
    
        $studentID = $receipt['IDNo'];
        $modeOfPayment = $receipt['ModeOfPayment'];
        $autoDebit = $receipt['AutoDebit'];
        $isOld = $receipt['IsOld'];
        $transactionDate = $receipt['TransactionDate'];
        $nameofbank = $receipt['BankName'];
        $transactionno = $receipt['TransactionNo'];
        $creditAmount = $receipt['Credit'];
        $particulars = $receipt['Particulars'];
        $debitHead = $receipt['DebitHead'];
        $SemesterID = $receipt['SemesterID'];
    
        $semesterName = getSemesterName($SemesterID);
        // 2. Fetch student data
        $sqlStudent = "SELECT * FROM Admissions WHERE IDNo = ?";
        $stmtStudent = sqlsrv_query($conn, $sqlStudent, [$studentID]);
        if(!$stmtStudent) return ["status"=>0,"message"=>"Student not found"];
        $student = sqlsrv_fetch_array($stmtStudent, SQLSRV_FETCH_ASSOC);
    
        $IDNo = $student['IDNo'];
        $CollegeName = $student['CollegeName'];
        $StudentName = $student['StudentName'];
        $FatherName = $student['FatherName'];
        $MotherName = $student['MotherName'];
        $Course = $student['Course'];
        $Batch = $student['Batch'];
        $ClassRollNo = $student['ClassRollNo'];
        $UniRollNo = $student['UniRollNo'];
        $FeeCategory = $student['FeeCategory'];
        $Sex = $student['Sex'];
    
        // 3. Determine session and smallDateTime
        if ($isOld != 1) {
            $sqlSession = "SELECT Session FROM MasterSession WHERE DefaultSession = 1";
            $stmtSession = sqlsrv_query($conn, $sqlSession);
            $session = sqlsrv_fetch_array($stmtSession, SQLSRV_FETCH_ASSOC)['Session'];
            $smallDateTime = date('Y-m-d H:i:s'); // current datetime
    
        } else {
            $session = '2024-25';
            $smallDateTime = '2025-03-31 00:30';
        }
    
        // 4. Get new TransactionID
        $sqlMaxTransaction = "SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger";
        $stmtMaxTransaction = sqlsrv_query($conn, $sqlMaxTransaction);
        $newTransactionId = sqlsrv_fetch_array($stmtMaxTransaction, SQLSRV_FETCH_ASSOC)['MaxTransactionID'] + 1;
    
      
     if($modeOfPayment == 'Cash') {

echo $sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session ='2026-27'
      AND ModeOfPayment = 'Cash'      
      AND CAST(ReceiptNo AS INT) BETWEEN 1 AND 100000";


    $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session, $modeOfPayment]);


} 

else if( $modeOfPayment == 'Receipt')
{

$sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session ='?'
      AND ModeOfPayment = '?'
     
      AND CAST(ReceiptNo AS INT) BETWEEN 200000 AND 300000";

 $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session, $modeOfPayment]);

}

else {
    //$sqlMaxReceipt = "SELECT MAX(ReceiptNo) AS MaxReceiptNo 
                    //  FROM Ledger 
                    //  WHERE Session = ? AND ModeOfPayment!='Cash' AND ModeOfPayment!='Receipt'";
  $sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session = ?
      AND ModeOfPayment != 'Cash'
      AND ModeOfPayment != 'Receipt'
      AND CAST(ReceiptNo AS INT) BETWEEN 100000 AND 200000";



    $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session]);
}

       $row = sqlsrv_fetch_array($stmtMaxReceipt, SQLSRV_FETCH_ASSOC);

    if ($modeOfPayment == 'Cash') {

        $newReceiptNo = ($row['MaxReceiptNo'] ?? 0) + 1;
      }
      else if($modeOfPayment == 'Receipt')
      {
 $newReceiptNo = ($row['MaxReceiptNo'] ?? 200000) + 1;
      }
      else
      {
         $newReceiptNo = ($row['MaxReceiptNo'] ?? 1000000) + 1;
      }


    
        // 6. Prepare Ledger insert (Credit)
        if ($modeOfPayment == 'Cash') {
            $sqlInsertLedger = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, ValueDate, ReceiptNo, ModeOfPayment, printReceiptTableID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $paramsLedger = [
                $session, $CollegeName, $newTransactionId, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment Receipt No: $newReceiptNo", $creditAmount, $debitHead,
                'Credit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), $newReceiptNo, $modeOfPayment, $receiptid
            ];
        } else {
            // Bank Payment
            $sqlInsertLedger = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, ValueDate, ReceiptNo, ChequeDraftBank, ChequeDraftNo, DateEntrySubmission, ModeOfPayment, printReceiptTableID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $paramsLedger = [
                $session, $CollegeName, $newTransactionId, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment Receipt No: $newReceiptNo", $creditAmount, $debitHead,
                'Credit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), $newReceiptNo,
                $nameofbank, $transactionno, $transactionDate, $modeOfPayment, $receiptid
            ];

        }
         //echo interpolateQuery($sqlInsertLedger,$paramsLedger);
        $stmtLedger = sqlsrv_query($conn, $sqlInsertLedger, $paramsLedger);
   
        if(!$stmtLedger) return 0;

        // 7. Update PrintReceipt
        $sqlUpdatePR = "UPDATE PrintReceipt SET TransactionID = ?, Status = 1 WHERE ID = ?";
        $stmtUpdatePR = sqlsrv_query($conn, $sqlUpdatePR, [$newTransactionId, $receiptid]);
        if(!$stmtUpdatePR) return 0;
    
        // ------------------- Insert logbook entry ------------------------
        $logbookRemarks = "Ledger refund entry created for Receipt No: $newReceiptNo | Mode: $modeOfPayment | Amount: $creditAmount | Head: $debitHead";
        $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
        $logbookParams = [$IDNo, $logbookRemarks, $EmployeeID, $currentSmallDate];
        sqlsrv_query($conn, $logbookSql, $logbookParams);

        // 8. Handle AutoDebit
        if($autoDebit == 1){
            $sqlMaxTransactionDebit = "SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger";
            $stmtMaxTransactionDebit = sqlsrv_query($conn, $sqlMaxTransactionDebit);
            $newTransactionIdDebit = sqlsrv_fetch_array($stmtMaxTransactionDebit, SQLSRV_FETCH_ASSOC)['MaxTransactionID'] + 1;
    
            $sqlInsertDebit = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, ValueDate, Remarks)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $paramsDebit = [
                $session, $CollegeName, $newTransactionIdDebit, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment", $creditAmount, $debitHead,
                'Debit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), 'Auto Debit'
            ];
            $stmtDebit = sqlsrv_query($conn, $sqlInsertDebit, $paramsDebit);
            if(!$stmtDebit) return 0;
            // return "1";
            echo json_encode([
                "status" => '3',
                "ReceiptNo" => $newReceiptNo,
                "LedgerName" => $debitHead,
                "IDNo" => $IDNo,
                "session" => $session]);
        }
        else{
        echo json_encode([
            "status" => '3',
            "ReceiptNo" => $newReceiptNo,
            "LedgerName" => $debitHead,
            "IDNo" => $IDNo,
            "session" => $session]);
        }
    }
    
    function generateReceipt($receiptid,$conn, $EmployeeID, $currentSmallDate) {
        // 1. Fetch receipt details
        $sqlReceipt = "SELECT * FROM PrintReceipt WHERE ID = ?";
        $stmtReceipt = sqlsrv_query($conn, $sqlReceipt, [$receiptid]);
        if(!$stmtReceipt) return 0;
        $receipt = sqlsrv_fetch_array($stmtReceipt, SQLSRV_FETCH_ASSOC);
    
        $studentID = $receipt['IDNo'];
        $modeOfPayment = $receipt['ModeOfPayment'];
        $autoDebit = $receipt['AutoDebit'];
        $isOld = $receipt['IsOld'];
        $transactionDate = $receipt['TransactionDate'];
        $nameofbank = $receipt['BankName'];
        $transactionno = $receipt['TransactionNo'];
        $creditAmount = $receipt['Credit'];
        $particulars = $receipt['Particulars'];
        $debitHead = $receipt['DebitHead'];
        $HeadID = $receipt['HeadID'];
        $SemesterID = $receipt['SemesterID'];
        $RouteID = $receipt['RouteID'];
        $SpotID = $receipt['SpotID'];
            
        $semesterName = getSemesterName($SemesterID);
        // 2. Fetch student data
        $sqlStudent = "SELECT * FROM Admissions WHERE IDNo = ?";
        $stmtStudent = sqlsrv_query($conn, $sqlStudent, [$studentID]);
        if(!$stmtStudent) return ["status"=>0,"message"=>"Student not found"];
        $student = sqlsrv_fetch_array($stmtStudent, SQLSRV_FETCH_ASSOC);
    
        $IDNo = $student['IDNo'];
        $CollegeName = $student['CollegeName'];
        $StudentName = $student['StudentName'];
        $FatherName = $student['FatherName'];
        $MotherName = $student['MotherName'];
        $Course = $student['Course'];
        $Batch = $student['Batch'];
        $ClassRollNo = $student['ClassRollNo'];
        $UniRollNo = $student['UniRollNo'];
        $FeeCategory = $student['FeeCategory'];
        $Sex = $student['Sex'];
    
        // 3. Determine session and smallDateTime
        if ($isOld != 1) {
            $sqlSession = "SELECT Session FROM MasterSession WHERE Status = 1";
            $stmtSession = sqlsrv_query($conn, $sqlSession);
            $session = sqlsrv_fetch_array($stmtSession, SQLSRV_FETCH_ASSOC)['Session'];
            $smallDateTime = date('Y-m-d H:i:s'); // current datetime
    
        } 
        else 
        {
            $session = '2025-26';
            $smallDateTime = '2026-03-31 00:30';
        }
    
        // 4. Get new TransactionID
        $sqlMaxTransaction = "SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger";
        $stmtMaxTransaction = sqlsrv_query($conn, $sqlMaxTransaction);
        $newTransactionId = sqlsrv_fetch_array($stmtMaxTransaction, SQLSRV_FETCH_ASSOC)['MaxTransactionID'] + 1;
    
        // 5. Get new ReceiptNo for the session
if ($isOld != 1) {
      
 
        // 5. Get new ReceiptNo for the session
      
     if($modeOfPayment == 'Cash') {
    $sqlMaxReceipt = "SELECT MAX(ReceiptNo) AS MaxReceiptNo 
                      FROM Ledger 
                      WHERE Session = ? AND ModeOfPayment=?";

$sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session =?
      AND ModeOfPayment = ?
     
      AND CAST(ReceiptNo AS INT) BETWEEN 1 AND 100000";


    $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session, $modeOfPayment]);

} 

else if( $modeOfPayment == 'Receipt')
{

$sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session =?
      AND ModeOfPayment = ?
     
      AND CAST(ReceiptNo AS INT) BETWEEN 200000 AND 300000";

 $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session, $modeOfPayment]);

//echo interpolateQuery($sqlMaxReceipt,[$session, $modeOfPayment]);


}

else {
    //$sqlMaxReceipt = "SELECT MAX(ReceiptNo) AS MaxReceiptNo 
                    //  FROM Ledger 
                    //  WHERE Session = ? AND ModeOfPayment!='Cash' AND ModeOfPayment!='Receipt'";
  $sqlMaxReceipt = "SELECT MAX(CAST(ReceiptNo AS INT)) AS MaxReceiptNo
    FROM Ledger
    WHERE Session = ?
      AND ModeOfPayment != 'Cash'
      AND ModeOfPayment != 'Receipt'
      AND CAST(ReceiptNo AS INT) BETWEEN 100000 AND 200000";

    $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session]);
}
}
else
{

     
    $sqlMaxReceipt = "SELECT MAX(ReceiptNo) AS MaxReceiptNo 
                      FROM Ledger 
                      WHERE Session = ?";
    $stmtMaxReceipt = sqlsrv_query($conn, $sqlMaxReceipt, [$session, $modeOfPayment]);



  } 

 $row = sqlsrv_fetch_array($stmtMaxReceipt, SQLSRV_FETCH_ASSOC);

    if ($modeOfPayment == 'Cash') {

        $newReceiptNo = ($row['MaxReceiptNo'] ?? 0) + 1;
      }
      else if($modeOfPayment == 'Receipt')
      {
 $newReceiptNo = ($row['MaxReceiptNo'] ?? 200000) + 1;
      }
      else
      {
         $newReceiptNo = ($row['MaxReceiptNo'] ?? 1000000) + 1;
      }

        // 6. Prepare Ledger insert (Credit)
        if ($modeOfPayment == 'Cash') {


             $sqlInsertLedger = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Credit, LedgerName, TransactionType, UserID, ValueDate, ReceiptNo, ModeOfPayment, printReceiptTableID,HeadID,RouteID,SpotID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
            $paramsLedger = [
                $session, $CollegeName, $newTransactionId, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment Receipt No: $newReceiptNo", $creditAmount, $debitHead,
                'Credit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), $newReceiptNo, $modeOfPayment, $receiptid,$HeadID,$RouteID,$SpotID
            ];

     




        } else {
            // Bank Payment
             $sqlInsertLedger = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Credit, LedgerName, TransactionType, UserID, ValueDate, ReceiptNo, ChequeDraftBank, ChequeDraftNo, DateEntrySubmission, ModeOfPayment, printReceiptTableID,HeadID,RouteID,SpotID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
            $paramsLedger = [
                $session, $CollegeName, $newTransactionId, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment Receipt No: $newReceiptNo", $creditAmount, $debitHead,
                'Credit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), $newReceiptNo,
                $nameofbank, $transactionno, $transactionDate, $modeOfPayment, $receiptid,$HeadID,$RouteID,$SpotID];

        }
     // echo interpolateQuery($sqlInsertLedger,$paramsLedger);
     
      $stmtLedger = sqlsrv_query($conn, $sqlInsertLedger, $paramsLedger);
   
        if(!$stmtLedger) return 0;

        // 7. Update PrintReceipt
        $sqlUpdatePR = "UPDATE PrintReceipt SET TransactionID = ?, Status = 1 WHERE ID = ?";
        $stmtUpdatePR = sqlsrv_query($conn, $sqlUpdatePR, [$newTransactionId, $receiptid]);
     if(!$stmtUpdatePR) return 0;
    

if ($stmtLedger) {

    // 7. Update PrintReceipt
   $sqlUpdatePR = "UPDATE PrintReceipt SET TransactionID = ?, Status = 1 WHERE ID = ?";
   $stmtUpdatePR = sqlsrv_query($conn, $sqlUpdatePR, [$newTransactionId, $receiptid]);

    if (!$stmtUpdatePR) return 0;

} else {
    return 0;
}
    
        // ------------------- Insert logbook entry ------------------------
        $logbookRemarks = "Ledger credit entry created for Receipt No: $newReceiptNo | Mode: $modeOfPayment | Amount: $creditAmount | Head: $debitHead";
        $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
        $logbookParams = [$IDNo, $logbookRemarks, $EmployeeID, $currentSmallDate];
        sqlsrv_query($conn, $logbookSql, $logbookParams);

//Bus Pass code

if($HeadID == '3')
{
$stmt = sqlsrv_query(
    $conn,
    "{CALL ApplyBusPassStudent(?,?,?,?)}",
    [$IDNo,$RouteID,$SpotID,'6']
);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($stmt === false) {
 
    die(print_r(sqlsrv_errors(), true));
}


}

        // 8. Handle AutoDebit
        if($autoDebit == 1){
            $sqlMaxTransactionDebit = "SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger";
            $stmtMaxTransactionDebit = sqlsrv_query($conn, $sqlMaxTransactionDebit);
            $newTransactionIdDebit = sqlsrv_fetch_array($stmtMaxTransactionDebit, SQLSRV_FETCH_ASSOC)['MaxTransactionID'] + 1;
    
            $sqlInsertDebit = "INSERT INTO Ledger
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, ValueDate, Remarks,HeadID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $paramsDebit = [
                $session, $CollegeName, $newTransactionIdDebit, $smallDateTime, $IDNo, $UniRollNo, $StudentName, $FatherName, $MotherName,
                $Course, $Batch, $ClassRollNo, $semesterName, $receipt['SemesterID'], $FeeCategory, $Sex,
                $particulars, $particulars." by $modeOfPayment", $creditAmount, $debitHead,
                'Debit', $receipt['CreatedBy'], date('Y-m-d H:i:s'), 'Auto Debit',$HeadID
            ];
            $stmtDebit = sqlsrv_query($conn, $sqlInsertDebit, $paramsDebit);
            if(!$stmtDebit) return 0;
            // return "1";
            echo json_encode([
                "status" => '3',
                "ReceiptNo" => $newReceiptNo,
                "LedgerName" => $debitHead,
                "IDNo" => $IDNo,
                "session" => $session]);
        }
        else{
        echo json_encode([
            "status" => '3',
            "ReceiptNo" => $newReceiptNo,
            "LedgerName" => $debitHead,
            "IDNo" => $IDNo,
            "session" => $session]);
        }
    }
    
   date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
          $CurrentExaminationGetDate=date('Y-m-d'); 
   $EmployeeID=$_SESSION['user_ac'];
   $UserID=$_SESSION['user_ac'];
   if ($EmployeeID==0 || $EmployeeID=='') 
      {?>
<script type="text/javascript">
window.location.href = "index.php";
</script>
<?php }
   include "connection/connection.php";

    $employee_details="SELECT RoleID,IDNo,Name,Department,CollegeName,Designation,LeaveRecommendingAuthority,LeaveSanctionAuthority FROM Staff Where IDNo='$EmployeeID'";

      $employee_details_run=sqlsrv_query($conn,$employee_details);

      if ($employee_details_row=sqlsrv_fetch_array($employee_details_run,SQLSRV_FETCH_ASSOC)) {
         $Emp_Name=$employee_details_row['Name'];
         $Emp_Designation=$employee_details_row['Designation'];
         $Emp_CollegeName=$employee_details_row['CollegeName'];
         $Emp_Department=$employee_details_row['Department'];
          $role_id = $employee_details_row['RoleID'];
        
        
      }
      
  $code = $_REQUEST['code'];

if ($code==1) {
    try {
        $newMenu = trim($_POST['newMenu'] ?? '');
        if ($newMenu == '') {
            echo 0;
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO MainMenuAccounts (MainMenuName) VALUES (:menu)");
        $stmt->bindParam(':menu', $newMenu);
        if ($stmt->execute()) {
            echo 1;
        } else {
            echo 0;
        }
    } catch (PDOException $e) {
        echo 0;
    }
}
else if($code==2)
{
    try {
        $stmt = $pdo->prepare("SELECT Id, MainMenuName FROM MainMenuAccounts ORDER BY Id ASC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            echo json_encode($rows);
        } else {
            echo json_encode([]);
        }

    } catch (PDOException $e) {
        echo json_encode([]);
    }
}

else if ($code == 3) {
    try {
        $menuId = trim($_POST['menuId'] ?? '');
        $menuName = trim($_POST['menuName'] ?? '');
        
        if ($menuId == '' || $menuName == '') {
            echo 0;
            exit;
        }
        $stmt = $pdo->prepare("UPDATE MainMenuAccounts SET MainMenuName = :name WHERE Id = :id");
        $stmt->bindParam(':name', $menuName);
        $stmt->bindParam(':id', $menuId, PDO::PARAM_INT);
        echo $stmt->execute() ? 1 : 0;
    } catch (PDOException $e) {
        echo 0;
    }
}
elseif ($code == 4) {
    $roleId = isset($_POST['roleId']) ? intval($_POST['roleId']) : 0;

    $query = "
        SELECT 
            m.Id AS MainMenuId,
            m.MainMenuName,
            r.Id AS RouteId,
            r.PageName,
            r.Route,
            ISNULL(p.ReadPermission, 0) AS ReadPermission,
            ISNULL(p.WritePermission, 0) AS WritePermission,
            ISNULL(p.DeletePermission, 0) AS DeletePermission
        FROM MainMenuAccounts m
        LEFT JOIN RouteMaster r ON m.Id = r.MainMenuId
        LEFT JOIN RolePermissionAccounts p ON r.Id = p.RouteId AND p.RoleId = ?
        ORDER BY m.Id, r.Id
    ";

    $params = [$roleId];
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        echo "<tr><td colspan='4' class='text-center text-muted'>No data found</td></tr>";
        exit;
    }?>
<div id="permissionsContainer">
    <?php
    $currentMenu = null;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if ($currentMenu !== $row['MainMenuId']) {
            if ($currentMenu !== null) echo "</tbody></table></div></div>";
            $currentMenu = $row['MainMenuId'];
            echo "<div class='card mb-2'>";
            echo "<div class='card-header d-flex justify-content-between' style='cursor:pointer' data-bs-toggle='collapse' data-bs-target='#menu-{$currentMenu}'>";
            echo "<strong>{$row['MainMenuName']} ({$currentMenu})</strong> <span>+</span></div>";
            echo "<div class='collapse' id='menu-{$currentMenu}'><table class='table table-bordered mt-2'><thead><tr>
                  <th>Route</th><th>Read</th><th>Write</th><th>Delete</th></tr></thead><tbody>";
        }
    
        if (!empty($row['RouteId'])) {
            $checkedRead  = $row['ReadPermission']  ? "checked" : "";
            $checkedWrite = $row['WritePermission'] ? "checked" : "";
            $checkedDelete= $row['DeletePermission']? "checked" : "";
    
            echo "<tr data-route-id='{$row['RouteId']}'>
                  <td>{$row['PageName']} ({$row['Route']})-{$row['RouteId']}</td>
                  <td><input type='checkbox' $checkedRead></td>
                  <td><input type='checkbox' $checkedWrite></td>
                  <td><input type='checkbox' $checkedDelete></td>
                  </tr>";
        }
    }
    if ($currentMenu !== null) echo "</tbody></table></div></div>";
    ?>
</div>

<button class='btn btn-primary mt-3' onclick='saveRolePermissions(<?= $roleId ?>)'>Save Permissions</button>
<?php
           
}


elseif ($code == 5) {
    $MainMenuId = isset($_POST['MainMenuId']) ? intval($_POST['MainMenuId']) : 0;
    $query = "
        SELECT r.Id,r.PageName,r.Route,r.MainMenuId,m.MainMenuName FROM RouteMaster r INNER JOIN MainMenuAccounts m ON m.Id = r.MainMenuId
    ";

    if ($MainMenuId) {
        $query .= " WHERE r.MainMenuId = $MainMenuId";
    }

    $query .= " ORDER BY r.Id";

    $stmt = sqlsrv_query($conn, $query);

    if ($stmt === false) {
        echo "<tr><td colspan='5'>Error in query execution.</td></tr>";
        exit;
    }

    $hasRows = false;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $hasRows = true;
        echo "<tr>
                <td>{$row['Id']}</td>
                <td>{$row['MainMenuName']}</td>
                <td>{$row['PageName']}</td>
                <td>{$row['Route']}</td>
                <td>
                    <button class='btn btn-primary btn-sm'
                        onclick=\"openEditModalSubMenu({$row['Id']},'{$row['MainMenuId']}','{$row['PageName']}','{$row['MainMenuName']}','{$row['Route']}')\">
                        Edit
                    </button>
                </td>
              </tr>";
    }

    if (!$hasRows) {
        echo "<tr><td colspan='5' class='text-center text-muted'>No records found</td></tr>";
    }

    exit;
}

// ---------------------------
// Add/Edit Menu (code = 6)
// ---------------------------
elseif ($code == 6) {
    // Get POST data
    $MainMenuId = isset($_POST['MainMenuId']) ? intval($_POST['MainMenuId']) : 0;
    $PageName   = isset($_POST['PageName']) ? $_POST['PageName'] : '';
    $Route      = isset($_POST['routeName']) ? $_POST['routeName'] : '';
    $Id         = isset($_POST['routeId']) ? intval($_POST['routeId']) : 0;


        $sql = "UPDATE RouteMaster SET MainMenuId = ?, PageName = ?, Route = ? WHERE Id = ?";
        $params = array($MainMenuId, $PageName, $Route, $Id);
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if ($stmt && sqlsrv_execute($stmt)) {
            echo "1"; // success
        } else {
            echo "0"; // fail
        }


    exit;
}

// INSERT SUB MENU

elseif ($code == 7) {
    $MainMenuId = isset($_POST['MainMenuId']) ? intval($_POST['MainMenuId']) : 0;
    $PageName   = isset($_POST['PageName']) ? trim($_POST['PageName']) : '';
    $Route      = isset($_POST['Route']) ? trim($_POST['Route']) : '';

    if (!$MainMenuId || !$PageName || !$Route) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required!']);
        exit;
    }

    try {
        $query = "INSERT INTO RouteMaster (MainMenuId, PageName, Route) VALUES (?, ?, ?)";
        $params = [$MainMenuId, $PageName, $Route];

        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_rows_affected($stmt) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'New Route Added']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Not added']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Unable to add route', 'details' => $e->getMessage()]);
    }

    exit;
}
// ASSIGN PERMISSIONS ROLE 

elseif ($code == 8) {
    $roleId = isset($_POST['roleId']) ? intval($_POST['roleId']) : 0;
    $permissions = isset($_POST['permissions']) ? json_decode($_POST['permissions'], true) : [];

    if (!$roleId || empty($permissions)) {
        echo "0"; exit;
    }

    foreach ($permissions as $perm) {
        $routeId = intval($perm['routeId']);
        $read = $perm['read'] ? 1 : 0;
        $write = $perm['write'] ? 1 : 0;
        $delete = $perm['delete'] ? 1 : 0;
        $check = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM RolePermissionAccounts WHERE RoleId=? AND RouteId=?", [$roleId, $routeId]);
        $row = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
        if ($row['cnt'] > 0) {
            $stmt = sqlsrv_prepare($conn, "UPDATE RolePermissionAccounts SET ReadPermission=?, WritePermission=?, DeletePermission=? WHERE RoleId=? AND RouteId=?", [$read, $write, $delete, $roleId, $routeId]);
        } else {
            $stmt = sqlsrv_prepare($conn, "INSERT INTO RolePermissionAccounts (RoleId, RouteId, ReadPermission, WritePermission, DeletePermission) VALUES (?, ?, ?, ?, ?)", [$roleId, $routeId, $read, $write, $delete]);
        }
        sqlsrv_execute($stmt);
    }
    echo "1";
    exit;
}
elseif ($code == 9) {
    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $session = $_POST['session'] ?? '';
    $ledgername = $_POST['ledgername'] ?? '';
    $modeofpayment = $_POST['modeofpayment'] ?? '';
    $empid = $_POST['empid'] ?? '';
    $orderby = strtoupper($_POST['orderby'] ?? 'ASC');

    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    $head = '';
    if(!empty($ledgername)) {
        $stmt = sqlsrv_query($conn, "SELECT Head FROM MasterHeadNew WHERE Status='1' AND Id = ?", array($ledgername));
        if($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $head = $row['Head'];
        }
    }
    $params = array($startdate . " 00:01", $enddate . " 23:59");
    $sql = "SELECT * FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";

    if(!empty($session)) {
        $sql .= " AND Session = ?";
        $params[] = $session;
    }
    if(!empty($ledgername) && !empty($head)) {
        $sql .= " AND LedgerName = ?";
        $params[] = $head;
    }
    if(!empty($modeofpayment)) {
        $sql .= " AND ModeOfPayment = ?";
        $params[] = $modeofpayment;
    }
    if(!empty($empid)) {
        $sql .= " AND UserID = ?";
        $params[] = $empid;
    }
    $sql .= " And  ModeOfPayment!='Receipt' ORDER BY DateEntry $orderby";

    $stmt = sqlsrv_query($conn, $sql, $params);
    $daybookdata = [];
    if($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $daybookdata[] = $row;
        }
    }
    $sumSql = "SELECT SUM(Credit) as TotalCredit FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";
    $sumParams = array($startdate . " 00:01", $enddate . " 23:59");
    if(!empty($session)) $sumSql .= " AND Session = ?";
    if(!empty($ledgername) && !empty($head)) $sumSql .= " AND LedgerName = ?";
    if(!empty($modeofpayment)) $sumSql .= " AND ModeOfPayment = ?";
    if(!empty($empid)) $sumSql .= " AND UserID = ?";
    $sumParams = array_merge(array($startdate . " 00:01", $enddate . " 23:59"), array_filter([$session, $head, $modeofpayment, $empid]));

    $sumStmt = sqlsrv_query($conn, $sumSql, $sumParams);
    $TotalCredit = 0;
    if($sumStmt && $sumRow = sqlsrv_fetch_array($sumStmt, SQLSRV_FETCH_ASSOC)) {
        $TotalCredit = $sumRow['TotalCredit'] ?? 0;
    }
?>

<div style="max-height: 600px; overflow-y: auto;">
    <table class="table table-bordered table-striped">
        <thead style="position: sticky; top: 0; background: #f9f9f9; z-index: 40;">
            <tr>
                <th>Sr No</th>
                <th style="max-width:200px">Receipt / Entry Date</th>
                <th>Bank Date</th>
                <th>ID No</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Head</th>
                <th>Particular</th>
               <th>Receipt No</th>
                <th>Semester</th>
                <th>Credit</th>
                <th>Mode of Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">
            <?php
if(count($daybookdata) > 0){
    $srno = 1;
    foreach($daybookdata as $item){
        $receiptdate = '';
        $bankdate  = '';
        if(!empty($item['DateEntry']) && $item['DateEntry'] instanceof DateTime){
            $receiptdate = $item['DateEntry']->format('d-m-Y');
        }
        if(!empty($item['DateEntrySubmission']) && $item['DateEntrySubmission'] instanceof DateTime){
            $bankdate = $item['DateEntrySubmission']->format('d-m-Y');
        }

        $Credit = $item['Credit'] ?? '';
        $TransactionNo = $item['TransactionNo'] ?? '';
        $ReceiptNo = $item['ReceiptNo'] ?? '';
        // <td>{$TransactionNo}</td>
        echo "<tr>
            <td>{$srno}</td>
            <td>{$receiptdate}</td>
            <td>{$bankdate}</td>
            <td>{$item['IDNo']}</td>
            <td>{$item['StudentName']}</td>
            <td>{$item['FatherName']}</td>
            <td>{$item['LedgerName']}</td>
            <td>{$item['Particulars']}</td>
              <td>{$item['ReceiptNo']}</td>
            <td>{$item['SemesterID']}</td>
            <td>{$Credit}</td>
            <td>{$item['ModeOfPayment']}</td>
            <td>";
        if($Credit > 0){
            echo "<button class='btn btn-primary btn-sm' onclick=\"print_receipt('{$ReceiptNo}','{$item['LedgerName']}','{$item['IDNo']}','{$item['Session']}')\">Print</button>";
        }
        echo "</td></tr>";
        $srno++;
    }
}else{
    echo "<tr><td colspan='13' class='text-center text-danger'>No records found</td></tr>";
}
?>
        </tbody>

    </table>
    <div
        style="position: sticky; top: 0; z-index: 50; background-color: white; padding: 10px; display: flex; justify-content: flex-end; border-bottom: 1px solid #ccc;">
        <button class="btn btn-info btn-sm">Total Credit: <?= number_format($TotalCredit, 2) ?></button>
    </div>
</div>
<?php
exit;
}
elseif ($code == 9.1) {
    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $session = $_POST['session'] ?? '';
    $ledgername = $_POST['ledgername'] ?? '';
    $modeofpayment = $_POST['modeofpayment'] ?? '';
    $empid = $_POST['empid'] ?? '';
    $orderby = strtoupper($_POST['orderby'] ?? 'ASC');

    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    $head = '';
    if(!empty($ledgername)) {
        $stmt = sqlsrv_query($conn, "SELECT Head FROM MasterHeadNew WHERE Status='1' AND Id = ?", array($ledgername));
        if($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $head = $row['Head'];
        }
    } 
    $params = array($startdate . " 00:01", $enddate . " 23:59");
    $sql = "SELECT * FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";

    if(!empty($session)) {
        $sql .= " AND Session = ?";
        $params[] = $session;
    }
    if(!empty($ledgername) && !empty($head)) {
        $sql .= " AND LedgerName = ?";
        $params[] = $head;
    }
    if(!empty($modeofpayment)) {
        $sql .= " AND ModeOfPayment = ?";
        $params[] = $modeofpayment;
    }
    if(!empty($empid)) {
        $sql .= " AND UserID = ?";
        $params[] = $empid;
    }
    $sql .= " ORDER BY DateEntry $orderby";

    $stmt = sqlsrv_query($conn, $sql, $params);
    $daybookdata = [];
    if($stmt) {
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $daybookdata[] = $row;
        }
    }
    $sumSql = "SELECT SUM(Credit) as TotalCredit FROM Ledger WHERE DateEntry BETWEEN ? AND ? AND Credit > 0";
    $sumParams = array($startdate . " 00:01", $enddate . " 23:59");
    if(!empty($session)) $sumSql .= " AND Session = ?";
    if(!empty($ledgername) && !empty($head)) $sumSql .= " AND LedgerName = ?";
    if(!empty($modeofpayment)) $sumSql .= " AND ModeOfPayment = ?";
    if(!empty($empid)) $sumSql .= " AND UserID = ?";
    $sumParams = array_merge(array($startdate . " 00:01", $enddate . " 23:59"), array_filter([$session, $head, $modeofpayment, $empid]));

    $sumStmt = sqlsrv_query($conn, $sumSql, $sumParams);
    $TotalCredit = 0;
    if($sumStmt && $sumRow = sqlsrv_fetch_array($sumStmt, SQLSRV_FETCH_ASSOC)) {
        $TotalCredit = $sumRow['TotalCredit'] ?? 0;
    }
?>

<div style="max-height: 600px; overflow-y: auto;">
    <table class="table table-bordered table-striped">
        <thead style="position: sticky; top: 0; background: #f9f9f9; z-index: 40;">
            <tr>
                <th>Sr No</th>
                <th style="max-width:200px">Receipt / Entry Date</th>
                <th>Bank Date</th>
                <th>ID No</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Head</th>
                <th>Particular</th>
               <th>Receipt No</th>
                <th>Semester</th>
                <th>Credit</th>
                <th>Mode of Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">
            <?php
if(count($daybookdata) > 0){
    $srno = 1;
    foreach($daybookdata as $item){
        $receiptdate = '';
        $bankdate  = '';
        if(!empty($item['DateEntry']) && $item['DateEntry'] instanceof DateTime){
            $receiptdate = $item['DateEntry']->format('d-m-Y');
        }
        if(!empty($item['DateEntrySubmission']) && $item['DateEntrySubmission'] instanceof DateTime){
            $bankdate = $item['DateEntrySubmission']->format('d-m-Y');
        }

        $Credit = $item['Credit'] ?? '';
        $TransactionNo = $item['TransactionNo'] ?? '';
        $ReceiptNo = $item['ReceiptNo'] ?? '';
        // <td>{$TransactionNo}</td>
        echo "<tr>
            <td>{$srno}</td>
            <td>{$receiptdate}</td>
            <td>{$bankdate}</td>
            <td>{$item['IDNo']}</td>
            <td>{$item['StudentName']}</td>
            <td>{$item['FatherName']}</td>
            <td>{$item['LedgerName']}</td>
            <td>{$item['Particulars']}</td>
              <td>{$item['ReceiptNo']}</td>
            <td>{$item['SemesterID']}</td>
            <td>{$Credit}</td>
            <td>{$item['ModeOfPayment']}</td>
            <td>";
        if($Credit > 0){
            echo "<button class='btn btn-primary btn-sm' onclick=\"print_receipt('{$ReceiptNo}','{$item['LedgerName']}','{$item['IDNo']}','{$item['Session']}')\">Print</button>";
        }
        echo "</td></tr>";
        $srno++;
    }
}else{
    echo "<tr><td colspan='13' class='text-center text-danger'>No records found</td></tr>";
}
?>
        </tbody>

    </table>
    <div
        style="position: sticky; top: 0; z-index: 50; background-color: white; padding: 10px; display: flex; justify-content: flex-end; border-bottom: 1px solid #ccc;">
        <button class="btn btn-info btn-sm">Total Credit: <?= number_format($TotalCredit, 2) ?></button>
    </div>
</div>
<?php
exit;
}
elseif ($code == 10) {

 $id = trim($_POST['id']) ?? null;


 include 'student-info.php';


            $color = "#007bff";
?>


<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 text-center py-2">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color: <?= $color ?>;">
            </div>

            <div class="col">
                <div class="card-body">
                    <b><?= htmlspecialchars($student['StudentName']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    Uni Roll No: <?= htmlspecialchars($student['UniRollNo']) ?><br>
                    Class Roll No: <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                    Batch: <?= htmlspecialchars($student['Batch']) ?> &nbsp;&nbsp; LEET:
                    <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                    Session: <?= htmlspecialchars($student['Session']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <!-- <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div> -->
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <!-- <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div> -->
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment" style='font-size:10px;'>
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment" style='font-size:10px;'>
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>

<?php
        
    } 

elseif ($code==11) {
    $debithead     = $_POST['debithead'] ?? '';
    $debitsession  = $_POST['debitsession'] ?? '';
    $debitsemester = $_POST['debitsemester'] ?? '';
    $debitremarks  = $_POST['debitremarks'] ?? '';
    $debitfee      = $_POST['debitfee'] ?? 0;
    $id            = $_POST['studentid'] ?? '';
    $particulars   = $_POST['particulars'] ?? '';
     $debitdate     = $_POST['debitdate'] ?? '';
     $UserID        = $_SESSION['user_id'] ?? 0;

  
  include 'student-info.php';



        $stmt = $pdo->prepare("SELECT Head FROM MasterHeadNew WHERE Status='1' AND Id = :id");
        $stmt->execute([':id' => $debithead]);
        $headRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $headName = $headRow['Head'] ?? '';
        $stmt = $pdo->query("SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newTransactionId = ($row['MaxTransactionID'] ?? 0) + 1;
        $datetime = date('Y-m-d H:i:s', time() + 5.5 * 3600);
        $semesterName = getSemesterName($debitsemester);
       
        $sql = "INSERT INTO Ledger
    ([Session], CollegeName, TransactionID, [DateEntry], IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, [ValueDate], Remarks)
    VALUES
    (:session, :college, :transactionId, :dateEntry, :idNo, :uniRollNo, :studentName, :fatherName, :motherName, :course, :batch, :classRollNo, :semester, :semesterID, :feeCategory, :sex, :onAccountOf, :particulars, :debit, :ledgerName, :transactionType, :userID, :valueDate, :remarks)";




try {
 $stmt = $pdo->prepare($sql);
$stmt->execute([
    ':session'         => $debitsession,
    ':college'         => $student['CollegeName'],
    ':transactionId'   => $newTransactionId,
    ':dateEntry'       => $debitdate,
    ':idNo'            => $student['IDNo'],
    ':uniRollNo'       => $student['UniRollNo'],
    ':studentName'     => $student['StudentName'],
    ':fatherName'      => $student['FatherName'],
    ':motherName'      => $student['MotherName'],
    ':course'          => $student['Course'],
    ':batch'           => $student['Batch'],
    ':classRollNo'     => $student['ClassRollNo'],
    ':semester'        => $semesterName,
    ':semesterID'      => $debitsemester,
    ':feeCategory'     => $student['FeeCategory'],
    ':sex'             => $student['Sex'],
    ':onAccountOf'     => $debitremarks,
    ':particulars'     => $particulars,
    ':debit'           => $debitfee,
    ':ledgerName'      => $headName,
    ':transactionType' => 'Debit',   // always pass as placeholder
    ':userID'          => $UserID,
    ':valueDate'       => $datetime,
    ':remarks'         => $debitremarks
]);

   echo 1;
} catch (PDOException $e) {
    // Print error details on screen
    echo "<pre>";
    echo "❌ SQL ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";

    // Optionally, also log the query and parameters
    error_log("SQL ERROR: " . $e->getMessage());
    error_log("QUERY: " . $sql);
    error_log("PARAMS: " . print_r($params, true));
}






        

        // -------------------LogBOok--------------------
        $logSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (:userid, :remarks, :updatedby, :date)";
        $logStmt = $pdo->prepare($logSql);
        $logRemarks = "Added Ledger entry: StudentID {$student['IDNo']}, TransactionID $newTransactionId, Amount $debitfee, Head $headName";
        $logStmt->execute([
        ':userid'    => $student['IDNo'],
        ':remarks'   => $logRemarks,
        ':updatedby' => $UserID,
        ':date'      => $datetime
        ]);


        // ------------------------------------------------
}

elseif ($code==11.1) {
    $debithead     = $_POST['debithead'] ?? '';
    $debitsession  = $_POST['debitsession'] ?? '';
    $debitsemester = $_POST['debitsemester'] ?? '';
    $debitremarks  = $_POST['debitremarks'] ?? '';
    $debitfee      = $_POST['debitfee'] ?? 0;
    $id            = $_POST['studentid'] ?? '';
    $particulars   = $_POST['particulars'] ?? '';
     $debitdate     = $_POST['debitdate'] ?? '';
     $UserID        = $_SESSION['user_id'] ?? 0;


$idBigInt = is_numeric($id) ? (int)$id : 0;

  
     $stmt = $pdo->prepare("SELECT * FROM Admissions WHERE UniRollNo = :search1 OR ClassRollNo = :search2 OR IDNo = :search3");
     $stmt->execute([
         ':search1' => $id,
         ':search2' => $id,
         ':search3' => $idBigInt,
     ]);
     $student = $stmt->fetch(PDO::FETCH_ASSOC);
     
        if (!$student) {
            echo json_encode([["message" => "Student not found"]]);
            exit;
        }


        $stmt = $pdo->prepare("SELECT Head FROM MasterHeadNew WHERE Status='1' AND Id = :id");
        $stmt->execute([':id' => $debithead]);
        $headRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $headName = $headRow['Head'] ?? '';
        $stmt = $pdo->query("SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newTransactionId = ($row['MaxTransactionID'] ?? 0) + 1;
        $datetime = date('Y-m-d H:i:s', time() + 5.5 * 3600);
        $semesterName = getSemesterName($debitsemester);

 $sql = "INSERT INTO ConcessionEntries 
  (Session, CollegeName, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, UserID, Remarks, ConcessionStatus, CreatedBy, CreatedDate)
  VALUES (:session, :college, :dateEntry, :idNo, :uniRollNo, :studentName, :fatherName, :motherName, :course, :batch, :classRollNo, :semester, :semesterID, :feeCategory, :sex, :onAccountOf, :particulars, :debit, :ledgerName, :userID, :remarks, :concessionStatus, :createdBy, :createdDate)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':session' => $debitsession,
  ':college' => $student['CollegeName'],
  ':dateEntry' => $debitdate,
  ':idNo' => $student['IDNo'],
  ':uniRollNo' => $student['UniRollNo'],
  ':studentName' => $student['StudentName'],
  ':fatherName' => $student['FatherName'],
  ':motherName' => $student['MotherName'],
  ':course' => $student['Course'],
  ':batch' => $student['Batch'],
  ':classRollNo' => $student['ClassRollNo'],
  ':semester' => $semesterName,
  ':semesterID' => $debitsemester,
  ':feeCategory' => $student['FeeCategory'],
  ':sex' => $student['Sex'],
  ':onAccountOf' => $debitremarks,
  ':particulars' => $particulars,
  ':debit' => $debitfee,
  ':ledgerName' => $headName,
  ':userID' => $UserID,
  ':remarks' => $debitremarks,
  ':concessionStatus' => 0,
  ':createdBy' => $UserID,
  ':createdDate' => $datetime
]);


 
   



        echo "1";



        // -------------------LogBOok--------------------
        $logSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (:userid, :remarks, :updatedby, :date)";
        $logStmt = $pdo->prepare($logSql);
        $logRemarks = "Added Concession entry: StudentID {$student['IDNo']}, TransactionID $newTransactionId, Amount $debitfee, Head $headName";
        $logStmt->execute([
        ':userid'    => $student['IDNo'],
        ':remarks'   => $logRemarks,
        ':updatedby' => $UserID,
        ':date'      => $datetime
        ]);
       
}



else if($code==12){
   
$CollegeID = $_POST['CollegeID'];
$feesession = $_POST['feesession'];
$ProgramDropdown = $_POST['ProgramDropdown'];
$batch = $_POST['batch'];
$lateralentry = $_POST['lateralentry'];
$annualfeecategory = $_POST['annualfeecategory'];
$semester = $_POST['semester'];

$params = [];
$qry = "SELECT ad.Session, ad.CollegeID, ad.CollegeName, ad.CourseID, ad.Batch,
        ad.StudentName, ad.FatherName, ad.MotherName, ad.ClassRollNo, ad.UniRollNo, ad.IDNo,
        ad.StudentMobileNo, ad.Country, ad.FeeCategory, ad.CommentFromAcc, ad.CommentsDetail,
        ad.EmailID, ad.Nationality, ad.PermanentAddress, ad.Sex, ad.Course, ad.DOB, ad.AddressLine1,
        ad.PIN, ad.Image, ad.SignaturePath, ad.LateralEntry, ad.Eligibility, ad.EligibilityRemarks,
        ad.BloodGroup, ad.ABCID, ad.Status, ad.OTR, fcn.FeeCategory as FeeCategoryName
        FROM Admissions ad
        INNER JOIN FeeCategoryNew fcn ON ad.FeeCategory = fcn.ID
        WHERE ad.CourseID = :courseId 
          AND ad.Session = :session 
          AND ad.CollegeID = :collegeId 
          AND ad.Status > 0";

$params[':courseId'] = $ProgramDropdown;
$params[':session'] = $feesession;
$params[':collegeId'] = $CollegeID;

if ($annualfeecategory > 0) {
    $qry .= " AND ad.FeeCategory = :feeCategory";
    $params[':feeCategory'] = $annualfeecategory;
}

if (!empty($lateralentry)) {
    $qry .= " AND ad.LateralEntry = :lateral";
    $params[':lateral'] = $lateralentry;
}

$qry .= " ORDER BY ad.ClassRollNo ASC";
$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($students) > 0) {
    // Collect all IDNos for ledger queries
    $IDNos = implode(',', array_map(fn($s) => "'" . $s['IDNo'] . "'", $students));

    // Ledger totals (Fee, Tuition Fee)

    $ledgerQry = "
    SELECT 
        IDNo,
        SUM(CASE WHEN LedgerName IN ('Fee', 'Tuition Fee', 'Registration Fee', 'Tution Fee') THEN Debit ELSE 0 END) AS totaldebit,
        SUM(CASE WHEN LedgerName IN ('Fee', 'Tuition Fee', 'Registration Fee', 'Tution Fee') THEN Credit ELSE 0 END) AS totalcredit,
        SUM(CASE WHEN LedgerName = 'One Time Charges' THEN Debit ELSE 0 END) AS OTC,
        SUM(CASE WHEN LedgerName = 'Examination Fee' THEN Debit ELSE 0 END) AS examinationfee
    FROM ledger
    WHERE IDNo IN ($IDNos)
      AND SemesterID = '$semester'
    GROUP BY IDNo
";

    $stmtLedger = sqlsrv_query($conn, $ledgerQry);

    $ledgerData = [];
    while ($row = sqlsrv_fetch_array($stmtLedger, SQLSRV_FETCH_ASSOC)) {
        $ledgerData[] = $row;
    }

    $ledgerMap = [];
    foreach ($ledgerData as $l) {
        $ledgerMap[$l['IDNo']] = $l;
    }
    echo '<div class="table-responsive table-fixed-header">';
    echo '<table class="table table-bordered">';
    echo '<thead>
            <tr>
                <th><input type="checkbox" id="select_all" onclick="selectAll(this)"></th>
                <th>#</th>
                <th>Debit</th>
                <th>OTC</th>
                  <th>Examination Fee</th>
                <th>Special Comment</th>
                <th>Account Comment</th>
                <th>IDNo</th>
                <th>ClassRollNo</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Session</th>
                <th>Fee Category</th>
                <th>View</th>
            </tr>
          </thead><tbody>';

    $count = 1;
    foreach ($students as $s) {
        $id = $s['IDNo'];
        $totalDebit = isset($ledgerMap[$id]) ? $ledgerMap[$id]['totaldebit'] : 0;
        $totalCredit = isset($ledgerMap[$id]) ? $ledgerMap[$id]['totalcredit'] : 0;
       $otc = isset($ledgerMap[$id]) ? $ledgerMap[$id]['OTC'] : 0;
       $examinationfee = isset($ledgerMap[$id]) ? $ledgerMap[$id]['examinationfee'] : 0;

        echo "<tr data-bs-toggle='modal' data-bs-target='#modal-ledger' onclick='viewmodalaccountstatus(\"{$id}\")'>
                <td><input type='checkbox' class='studentid' value='{$id}'></td>
                <td>{$count}</td>
                <td>{$totalDebit}</td>
                <td>{$otc}</td>
                 <td>{$examinationfee}</td>
                <td>{$s['CommentsDetail']}</td>
                <td>{$s['CommentFromAcc']}</td>
                <td>{$id}</td>
                <td>{$s['ClassRollNo']}</td>
                <td>{$s['StudentName']}</td>
                <td>{$s['FatherName']}</td>
                <td>{$s['Session']}</td>
                <td>{$s['FeeCategoryName']}</td>
                
              </tr>";
        $count++;
    }

    echo '</tbody></table></div>';
} else {
    echo '<div class="alert alert-warning text-center">No records found</div>';

}


}

else if($code==12.1){
   
$CollegeID = $_POST['CollegeID'];
$feesession = $_POST['feesession'];
$ProgramDropdown = $_POST['ProgramDropdown'];
$batch = $_POST['batch'];
$lateralentry = $_POST['lateralentry'];
$annualfeecategory = $_POST['annualfeecategory'];
$semester = $_POST['semester'];
$debitheadsearch = $_POST['debitheadsearch'];


$params = [];
$qry = "SELECT ad.Session, ad.CollegeID, ad.CollegeName, ad.CourseID, ad.Batch,
        ad.StudentName, ad.FatherName, ad.MotherName, ad.ClassRollNo, ad.UniRollNo, ad.IDNo,
        ad.StudentMobileNo, ad.Country, ad.FeeCategory, ad.CommentFromAcc, ad.CommentsDetail,
        ad.EmailID, ad.Nationality, ad.PermanentAddress, ad.Sex, ad.Course, ad.DOB, ad.AddressLine1,
        ad.PIN, ad.Image, ad.SignaturePath, ad.LateralEntry, ad.Eligibility, ad.EligibilityRemarks,
        ad.BloodGroup, ad.ABCID, ad.Status, ad.OTR, fcn.FeeCategory as FeeCategoryName
        FROM Admissions ad
        INNER JOIN FeeCategoryNew fcn ON ad.FeeCategory = fcn.ID
        WHERE ad.CourseID = :courseId 
          AND ad.Session = :session 
          AND ad.CollegeID = :collegeId 
          AND ad.Status > 0 ";

$params[':courseId'] = $ProgramDropdown;
$params[':session'] = $feesession;
$params[':collegeId'] = $CollegeID;

if ($annualfeecategory > 0) {
    $qry .= " AND ad.FeeCategory = :feeCategory";
    $params[':feeCategory'] = $annualfeecategory;
}

if (!empty($lateralentry)) {
    $qry .= " AND ad.LateralEntry = :lateral";
    $params[':lateral'] = $lateralentry;
}

$qry .= " ORDER BY ad.ClassRollNo ASC";
$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($students) > 0) {
    // Collect all IDNos for ledger queries
    $IDNos = implode(',', array_map(fn($s) => "'" . $s['IDNo'] . "'", $students));

    // Ledger totals (Fee, Tuition Fee)

     $ledgerQry = "
    SELECT 
        IDNo,
        SUM(CASE WHEN LedgerName IN ('$debitheadsearch') THEN Debit ELSE 0 END) AS totaldebit,
        SUM(CASE WHEN LedgerName IN ('Fee', 'Tuition Fee', 'Registration Fee', 'Tution Fee') THEN Credit ELSE 0 END) AS totalcredit,
        SUM(CASE WHEN LedgerName = 'One Time Charges' THEN Debit ELSE 0 END) AS OTC,
        SUM(CASE WHEN LedgerName = 'Examination Fee' THEN Debit ELSE 0 END) AS examinationfee
    FROM ledger
    WHERE IDNo IN ($IDNos)
      AND SemesterID = '$semester' ANd LedgerName='$debitheadsearch'  GROUP BY IDNo
";

    $stmtLedger = sqlsrv_query($conn, $ledgerQry);

    $ledgerData = [];
    while ($row = sqlsrv_fetch_array($stmtLedger, SQLSRV_FETCH_ASSOC)) {
        $ledgerData[] = $row;
    }

    $ledgerMap = [];
    foreach ($ledgerData as $l) {
        $ledgerMap[$l['IDNo']] = $l;
    }
    echo '<div class="table-responsive table-fixed-header">';
    echo '<table class="table table-bordered">';
    echo '<thead>
            <tr>
                <th><input type="checkbox" id="select_all" onclick="selectAll(this)"></th>
                <th>#</th>
                <th>Debit</th>
                <th>OTC</th>
                  <th>Examination Fee</th>
                <th>Special Comment</th>
                <th>Account Comment</th>
                <th>IDNo</th>
                <th>ClassRollNo</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Session</th>
                <th>Fee Category</th>
                <th>View</th>
            </tr>
          </thead><tbody>';

    $count = 1;
    foreach ($students as $s) {
        $id = $s['IDNo'];
        $totalDebit = isset($ledgerMap[$id]) ? $ledgerMap[$id]['totaldebit'] : 0;
        $totalCredit =  0;
       $otc =  0;
       $examinationfee = 0;

        echo "<tr data-bs-toggle='modal' data-bs-target='#modal-ledger' onclick='viewmodalaccountstatus(\"{$id}\")'>
                <td><input type='checkbox' class='studentid' value='{$id}'></td>
                <td>{$count}</td>
                <td>{$totalDebit}</td>
                <td>{$otc}</td>
                 <td>{$examinationfee}</td>
                <td>{$s['CommentsDetail']}</td>
                <td>{$s['CommentFromAcc']}</td>
                <td>{$id}</td>
                <td>{$s['ClassRollNo']}</td>
                <td>{$s['StudentName']}</td>
                <td>{$s['FatherName']}</td>
                <td>{$s['Session']}</td>
                <td>{$s['FeeCategoryName']}</td>
                
              </tr>";
        $count++;
    }

    echo '</tbody></table></div>';
} else {
    echo '<div class="alert alert-warning text-center">No records found</div>';

}


}

else if ($code == 13) {
    $students = $_POST['students']; 
    $UserID = $_SESSION['user_id']; 
    $debithead = $_POST['debithead'];
    $debitsession = $_POST['debitsession'];
    $debitsemester = $_POST['debitsemester'];
    $debitremarks = $_POST['debitremarks'];
    $debitfee = $_POST['debitfee'];
    $debitparticulars = $_POST['debitparticulars'];
    $debitdate = $_POST['debitdate'];

    $semesterName = getSemesterName($debitsemester);
  
        $headQry = "SELECT Head FROM MasterHeadNew WHERE Status='1' AND Id = ?";
        $params = [$debithead];
        $stmt = sqlsrv_query($conn, $headQry, $params);
        $headRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $headName = $headRow['Head'];

        foreach ($students as $idNo) {
            $studentQry = "SELECT * FROM Admissions WHERE IDNo = ?";
            $stmtStudent = sqlsrv_query($conn, $studentQry, [$idNo]);
            $student = sqlsrv_fetch_array($stmtStudent, SQLSRV_FETCH_ASSOC);

            if (!$student) continue;
            $maxQry = "SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger";
            $stmtMax = sqlsrv_query($conn, $maxQry);
            $maxRow = sqlsrv_fetch_array($stmtMax, SQLSRV_FETCH_ASSOC);
            $newTransactionID = $maxRow['MaxTransactionID'] + 1;

        // -------------------Logbook Entery-----------------
                    
                    $queryRemarks = "Added Ledger entry for Student IDNo: {$student['IDNo']}, TransactionID: $newTransactionID, Amount: $debitfee, Head: $headName";
                    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
                    $logbookParams = [$student['IDNo'], $queryRemarks, $EmployeeID, $currentSmallDate];
                    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
        // ------------------------------------

            $insertQry = "INSERT INTO Ledger 
                (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, 
                Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, 
                LedgerName, TransactionType, UserID, ValueDate, Remarks)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $paramsInsert = [
                $debitsession,
                $student['CollegeName'],
                $newTransactionID,
                $debitdate,
                $student['IDNo'],
                $student['UniRollNo'],
                $student['StudentName'],
                $student['FatherName'],
                $student['MotherName'],
                $student['Course'],
                $student['Batch'],
                $student['ClassRollNo'],
                $semesterName, 
                $debitsemester, 
                $student['FeeCategory'],
                $student['Sex'],
                $debitremarks,
                $debitparticulars,
                $debitfee,
                $headName,
                'Debit',
                $UserID,
                date('Y-m-d H:i:s'),
                $debitremarks
            ];
            $insertStmt = sqlsrv_query($conn, $insertQry, $paramsInsert);
            if ($insertStmt === false) {
               echo  "0";
            }
        }
        echo "1";
}

else if ($code == 14) {

    $UserID = $_SESSION['user_id'];  
    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $modeofpayment = $_POST['modeofpayment'] ?? '';

    if (empty($startdate) || empty($enddate)) {
        echo "<div class='alert alert-danger'>Please select start and end date</div>";
        exit;
    }

    $start = date('Y-m-d 00:01:00', strtotime($startdate));
    $end = date('Y-m-d 23:59:59', strtotime($enddate));

    $sql = "SELECT * FROM Ledger 
            WHERE UserID = ? 
            AND DateEntry BETWEEN ? AND ?
            AND Credit > 0";

    $params = [$UserID, $start, $end];

    if (!empty($modeofpayment)) {
        $sql .= " AND ModeOfPayment = ?";
        $params[] = $modeofpayment;
    }

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo "<div class='alert alert-danger'>Error loading records.</div>";
        exit;
    }
    echo '<div class="table-responsive table-fixed-header">';
    echo '<table class="table table-bordered" id="annualfeeTable">';
    $output = "
      <thead>
        <tr>
          <th>Sr No</th>
          <th>Receipt / Entry Date</th>
          <th>Bank Date</th>
          <th>IDNo</th>
          <th>Student Name</th>
          <th>Father Name</th>
          <th>Head</th>
          <th>Particular</th>
          <th>Transaction</th>
          <th>Semester</th>
          <th>Credit</th>
          <th>Mode of Payment</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
    ";

    $srno = 1;
    $hasData = false;

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $hasData = true;

        $DateEntry = isset($row['DateEntry']) && $row['DateEntry'] instanceof DateTime
            ? $row['DateEntry']->format('d-m-Y')
            : '';
        $BankDate = isset($row['DateEntrySubmission']) && $row['DateEntrySubmission'] instanceof DateTime
            ? $row['DateEntrySubmission']->format('d-m-Y')
            : '';

        $Credit = !empty($row['Credit']) ? $row['Credit'] : '';
        $TransactionNo = !empty($row['TransactionNo']) ? $row['TransactionNo'] : '';
        $ReceiptNo = !empty($row['ReceiptNo']) ? $row['ReceiptNo'] : '';

        $printButton = $Credit > 0 ? "
            <button class='btn btn-primary btn-sm' onclick=\"print_receipt('{$ReceiptNo}','{$row['LedgerName']}','{$row['IDNo']}','{$row['Session']}')\">
                <svg width='20' height='20' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icon-tabler-printer'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2' />
                    <path d='M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4' />
                    <path d='M7 13a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z' />
                </svg> Print
            </button>" : '';

        $output .= "
        <tr>
            <td>{$srno}</td>
            <td>{$DateEntry}</td>
            <td>{$BankDate}</td>
            <td>{$row['IDNo']}</td>
            <td>{$row['StudentName']}</td>
            <td>{$row['FatherName']}</td>
            <td>{$row['LedgerName']}</td>
            <td>{$row['Particulars']}</td>
            <td>{$TransactionNo}</td>
            <td>{$row['SemesterID']}</td>
            <td>{$Credit}</td>
            <td>{$row['ModeOfPayment']}</td>
            <td>{$printButton}</td>
        </tr>
        ";
        $srno++;
    }

    if (!$hasData) {
        $output .= "<tr><td colspan='13' class='text-center'>No records found</td></tr>";
    }

    $output .= "</tbody></table></div>";

    echo $output;
}
else if($code==15){
    $sql = "SELECT t1.*
            FROM PrintReceipt t1
            WHERE NOT EXISTS (
                SELECT 1
                FROM Ledger t2
                WHERE t1.ID = t2.PrintReceiptTableID
            )
            AND t1.CreatedBy = ?
            AND (t1.Status = '0' OR t1.Status IS NULL)
            ORDER BY t1.ID DESC";

    $params = [$EmployeeID];
    //    echo  interpolateQuery($sql,$params);
    $stmt = sqlsrv_query($conn, $sql, $params);
    echo "<table class='table table-bordered'>
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Receipt / Entry Date</th>
                <th>Bank Date</th>
                <th>ID No</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Head</th>
                <th>Particular</th>
                <th>Transaction</th>
                <th>Semester</th>
                <th>Credit</th>
                <th>Mode of Payment</th>
                <th colspan='2'>Action</th>
              </tr>
            </thead>
            <tbody>";

    if ($stmt) {
        $sr = 1;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $receiptDate = $row['DateEntry'] ? $row['DateEntry']->format('d-m-Y') : '';
            $bankDate = $row['TransactionDate'] ? $row['TransactionDate']->format('d-m-Y') : '';
            $credit = !empty($row['Credit']) ? $row['Credit'] : '';
            $receiptNo = !empty($row['ReceiptNo']) ? $row['ReceiptNo'] : '';
            $transactionNo = !empty($row['TransactionNo']) ? $row['TransactionNo'] : '';
            $mode = !empty($row['ModeOfPayment']) ? $row['ModeOfPayment'] : '';
             $debithead=$row['DebitHead'];

            echo "<tr>
                    <td>{$sr}</td>
                    <td>{$receiptDate}</td>
                    <td>{$bankDate}</td>
                    <td>{$row['IDNo']}</td>
                    <td>{$row['StudentName']}</td>
                    <td>{$row['FatherName']}</td>
                    <td>{$row['DebitHead']}</td>
                    <td>{$row['Particulars']}</td>
                    <td>{$transactionNo}</td>
                    <td>{$row['SemesterID']}</td>
                    <td>{$credit}</td>
                    <td>{$mode}</td>
                    <td>";

            if ($credit > 0 && $debithead!='Refund') {
                echo "<button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#modal-report' 
                      onclick=\"show_receiptentry('{$row['ID']}')\">Generate</button>";
            }
            else
            {

            }

            echo "</td>
                  <td><button class='btn btn-danger btn-sm' onclick=\"delete_receiptentry('{$row['ID']}')\">Delete</button></td>
                </tr>";

            $sr++;
        }

        if ($sr == 1) {
            echo "<tr><td colspan='14' class='text-center'>No records found</td></tr>";
        }
        sqlsrv_free_stmt($stmt);
    } else {
        echo "<tr><td colspan='14' class='text-center'>Error fetching data</td></tr>";
    }

    echo "</tbody></table>";
}
else if($code==16)
{
    $receiptid = $_POST['receiptid'];
    $sqlReceipt = "SELECT * FROM PrintReceipt WHERE ID = ?";
    $stmtReceipt = sqlsrv_query($conn, $sqlReceipt, [$receiptid]);
    $receipt = sqlsrv_fetch_array($stmtReceipt, SQLSRV_FETCH_ASSOC);
    // print_r($receipt);
    sqlsrv_free_stmt($stmtReceipt);

    if (!$receipt) {
        echo "<p>Receipt not found.</p>";
        exit;
    }
    $studentID = $receipt['IDNo'];
    $sqlStudent = "SELECT * FROM Admissions WHERE IDNo = ?";
    $stmtStudent = sqlsrv_query($conn, $sqlStudent, [$studentID]);
    $student = sqlsrv_fetch_array($stmtStudent, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmtStudent);

    if (!$student) {
        echo "<p>Student not found.</p>";
        exit;
    }
    ?>
<div class="col-lg-12">
    <div class="card">
        <div class="row row-0">
            <div class="col-3 text-center">
                <br>
                <img src="<?=$BasURL;?>/Images/Students/<?php echo $student['Image']; ?>"
                    style="border-radius:50%;height:100px;width:100px;" />
            </div>
            <div class="col">
                <div class="card-body">
                    <p><b><?php echo $receipt['StudentName']; ?> (<?php echo $student['IDNo']; ?>)</b><br>
                        Uni Roll No: <?php echo $student['UniRollNo']; ?><br>
                        Class Roll No: <?php echo $student['ClassRollNo']; ?><br>
                        Batch: <?php echo $student['Batch']; ?><br>
                        Session: <?php echo $receipt['Session']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="list-group-item">
            <div class="list-group list-group-flush list-group-hoverable">
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col text-truncate">
                            <b>Father Name: <?php echo $receipt['FatherName']; ?> &nbsp; | &nbsp;
                                Mother Name: <?php echo $student['MotherName']; ?></b>
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col text-truncate">
                            <b>Programme: <?php echo $receipt['Course']; ?></b>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="slipNumber" value="<?php echo $receipt['ID']; ?>">

            <table class="table">
                <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>Particulars</th>
                        <th>Debit Head</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>1</th>
                        <?php if ($receipt['ModeOfPayment'] === 'Bank Transfer') { ?>
                        <th>
                            Cheque/draft no.: <span
                                style="border-bottom:1.5pt solid black;"><?php echo $receipt['TransactionNo']; ?></span><br>
                            Bank Date: <span
                                style="border-bottom:1.5pt solid black;"><?php echo $receipt['TransactionDate']->format('d-m-Y'); ?></span><br>
                            Bank Name: <span
                                style="border-bottom:1.5pt solid black;"><?php echo $receipt['BankName']; ?></span>
                        </th>
                        <?php } else { ?>
                        <th><?php echo $receipt['DebitHead']; ?></th>
                        <th><?php echo $receipt['Particulars']; ?></th>
                        <th><?php echo $receipt['ModeOfPayment']; ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end"><strong>Total:</strong></th>
                        <th><strong>Rs. <?php echo $receipt['Credit']; ?>/-</strong></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}
else if ($code == 17) {
    $receiptid = $_POST['slipNumber'];
    $autodebit = isset($_POST['isChecked']) ? intval($_POST['isChecked']) : 0;

    $updateSql = "UPDATE PrintReceipt SET AutoDebit = ? WHERE ID = ?";
    $stmtUpdate = sqlsrv_query($conn, $updateSql, [$autodebit, $receiptid]);
    if ($stmtUpdate === false) {
        echo "2"; // AutoDebit update failed
        exit;
    }


    


 echo  generateReceipt($receiptid, $conn, $EmployeeID, $currentSmallDate);



    $sqlReceipt = "SELECT * FROM PrintReceipt WHERE ID = ?";
    $stmtReceipt = sqlsrv_query($conn, $sqlReceipt, [$receiptid]);
    $receipt = sqlsrv_fetch_array($stmtReceipt, SQLSRV_FETCH_ASSOC);
       sqlsrv_free_stmt($stmtReceipt);
    if (!$receipt) {
       


        exit;
    }

    $updateStatusSql = "UPDATE PrintReceipt SET Status = 1 WHERE ID = ?";
    $stmtStatus = sqlsrv_query($conn, $updateStatusSql, [$receiptid]);
    if ($stmtStatus === false) {
        echo "0"; // Status update failed
        exit;
    }




    $studentID = $receipt['IDNo'];

    
    $queryRemarks = "Generated slip StudentID $studentID, Slip ID $receiptid, AutoDebit: $autodebit";
    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
    $logbookParams = [$studentID, $queryRemarks, $EmployeeID, $currentSmallDate];
    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
    if ($logbookStmt === false) {
       // echo "3"; // Logbook insert failed
        exit;
    }   

    // 4. Success
   // echo "1";
}


else if ($code == 18) {
    $receiptid = $_POST['slipNumber'];
    // 1. Update Status to -1 (Deleted)
    $updateSql = "UPDATE PrintReceipt SET Status = '-1' WHERE ID = ?";
    $stmtUpdate = sqlsrv_query($conn, $updateSql, [$receiptid]);
    if ($stmtUpdate === false) {
        echo "0"; // Update failed
        exit;
    }
    // 2. Insert logbook entry
    $sqlReceipt = "SELECT * FROM PrintReceipt WHERE ID = ?";
    $stmtReceipt = sqlsrv_query($conn, $sqlReceipt, [$receiptid]);
    $receipt = sqlsrv_fetch_array($stmtReceipt, SQLSRV_FETCH_ASSOC);
    // print_r($receipt);
    sqlsrv_free_stmt($stmtReceipt);
    if (!$receipt) {
        echo "<p>Receipt not found.</p>";
        exit;
    }
    $studentID = $receipt['IDNo'];
    
    $queryRemarks = "Deleted slip StudentID $studentID, Slip ID $receiptid";
    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
    $logbookParams = [$studentID, $queryRemarks, $EmployeeID, $currentSmallDate];
    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
    if ($logbookStmt === false) {
        echo "2"; // Logbook insert failed
        exit;
    }
    // 3. Success
    echo "1";
}

else if ($code == 19) {

    
    $debithead = $_POST['debithead'];
    $debitsession = $_POST['debitsession'];
    $debitparticulars = $_POST['debitparticulars'];
    $debitfee = $_POST['debitfee'];
    $debitsem = $_POST['debitsem'];
    $studentid = $_POST['studentid'];
    $modeofpayment = $_POST['modeofpayment'];
    $nameofbank = $_POST['nameofbank'];
    $transactionid = $_POST['transactionid'];
    $transactiondate = $_POST['transactiondate'];

        $routeId = $_POST['route_id'];
        $spotid = $_POST['spot_id'];

    if (isset($_POST['IsOld'])) {
        $IsOld = $_POST['IsOld'];
    } else {
        $IsOld = 0;
    }
    if (isset($_POST['entryType'])) {
        $entryType = $_POST['entryType'];
    } else {
        $entryType = 1; 
    }
    if (isset($_POST['isChecked'])) {
        $isChecked = $_POST['isChecked'];
    } else {
        $isChecked = 0; // default fallback
    }

    if ($IsOld != '1') {
        $smallDateTime = date('Y-m-d H:i:s');
        $isOldStatus = 0;
    } else {
        $isOldStatus = 1;
        $smallDateTime = '2025-03-31 00:30:00';
    }
    $dateTime = date('Y-m-d H:i:s');

    $sql = "SELECT TOP 1 * FROM Admissions 
    WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
    $idBigInt = is_numeric($studentid) ? (int)$studentid : 0;
    $params = [$studentid, $studentid, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $IDNo = $student['IDNo'];
    $CollegeName = $student['CollegeName'];
    $StudentName = $student['StudentName'];
    $FatherName = $student['FatherName'];
    $Course = $student['Course'];
    $headQuery = "SELECT TOP 1 * FROM MasterHeadNew WHERE Status = '1' AND Id = ?";
    $headStmt = sqlsrv_query($conn, $headQuery, [$debithead]);
    $headData = sqlsrv_fetch_array($headStmt, SQLSRV_FETCH_ASSOC);
    $head = $headData['Head'];
    $bankTransactionDate = date('Y-m-d H:i:s', strtotime($transactiondate));
    if ($modeofpayment == 'Cash') {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, AutoDebit, Status, IsOld,HeadID,RouteID,SpotID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, $debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $isChecked, 0, $isOldStatus,$debithead,$routeId,$spotid];
    } else {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, BankName, TransactionNo, TransactionDate, AutoDebit, Status, IsOld,HeadID,RouteID,SpotID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, $debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $nameofbank, $transactionid, $bankTransactionDate, $isChecked, 0, $isOldStatus,$debithead,$routeId,$spotid];
    }

    $queryRemarks = "New PrintReceipt created  Mode: $modeofpayment, Amount: $debitfee, Head: $head";
    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
    $logbookParams = [$IDNo, $queryRemarks, $EmployeeID, $currentSmallDate];
    sqlsrv_query($conn, $logbookSql, $logbookParams);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt) {
        if ($entryType == 0) {
            $idQuery = "SELECT TOP 1 ID FROM PrintReceipt ORDER BY ID DESC";
            $idStmt = sqlsrv_query($conn, $idQuery);
            $idRow = sqlsrv_fetch_array($idStmt, SQLSRV_FETCH_ASSOC);
            $maxid = $idRow['ID'];
           echo  generateReceipt($maxid, $conn, $EmployeeID, $currentSmallDate);
         } else {
            // echo "1";
            echo json_encode(["status" => '1', "message" => "Receipt Generated"]);
        }
    } else {
        // echo "0";
        echo json_encode(["status" => '0', "message" => "Receipt Generated"]);
    }
}

elseif ($code==20) {
    $TransactionID = $_POST['TransactionID'];
    $LedgerName = $_POST['LedgerName'];
    $Session = $_POST['Session'];
    $Debit = $_POST['Debit'];
    $IDNo = $_POST['IDNo'];
    $SemesterID = $_POST['SemesterID'];
    $Remarks = $_POST['Remarks'];
    $Comments = $_POST['Comments'];

    if(empty($Comments)) {
        echo json_encode(["status"=>0,"message"=>"Comments Required"]);
        exit;
    }
        // Fetch ledger entry
        $sqlLedger = "SELECT * FROM Ledger WHERE TransactionID=? AND LedgerName=? AND Session=? 
                      AND SemesterID=? AND Credit=? AND IDNo=?";
        $paramsLedger = [$TransactionID, $LedgerName, $Session, $SemesterID, $Debit, $IDNo];
        $stmtLedger = sqlsrv_query($conn, $sqlLedger, $paramsLedger);
        if(!$stmtLedger) throw new Exception(print_r(sqlsrv_errors(), true));

        $ledger = sqlsrv_fetch_array($stmtLedger, SQLSRV_FETCH_ASSOC);
        if(!$ledger) {
            echo  "2";
            exit;
        }

        $currentDate = date('Y-m-d H:i:s');
        $currentDateSmall = date('Y-m-d H:i:s');
        $IDNoLedger = $ledger['IDNo'];
        $CollegeName = $ledger['CollegeName'];
        $StudentName = $ledger['StudentName'];
        $FatherName = $ledger['FatherName'];
        $ModeOfPayment = $ledger['ModeOfPayment'];
        $Credit = $ledger['Credit'];
        $DateEntry = $ledger['DateEntry'];
        $LedgerUserID = $ledger['UserID'];
        $ClassRollNo = $ledger['ClassRollNo'];
        $ReceiptNo = $ledger['ReceiptNo'];
        $ChequeDraftNo = $ledger['ChequeDraftNo'] ?? '';
        $ChequeDraftBank = $ledger['ChequeDraftBank'] ?? '';
        $DateEntrySubmission = $ledger['DateEntrySubmission'] ?? null;

        // Determine insert fields based on payment mode
        if($ModeOfPayment == 'Cash' || $ModeOfPayment == 'Bank Transfer') {
            $sqlInsert = "INSERT INTO CancelledReceipt
                (DateEntry, CollegeName, IDNo, StudentName, FatherName, Particulars, ModeOfPayment, Credit,DateEntrySubmission, 
                TransactionID, UserID, CancelReceiptDate, Comments, ReceiptStatus, LedgerName, Session, 
                ClassRollNo, SemesterID, ReceiptNo, CreatedBy, CreatedDate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $paramsInsert = [
                $DateEntry, $CollegeName, $IDNoLedger, $StudentName, $FatherName, $Remarks, $ModeOfPayment, 
                $Credit,$DateEntrySubmission,$TransactionID, 0, $currentDate, $Comments, 0, $LedgerName, $Session, 
                $ClassRollNo, $SemesterID, $ReceiptNo, $LedgerUserID, $currentDateSmall
            ];
        } else {
            // Non-cash, include cheque/bank info
            $sqlInsert = "INSERT INTO CancelledReceipt
                (DateEntry, CollegeName, IDNo, StudentName, FatherName, Particulars, ModeOfPayment, Credit, 
                DateEntrySubmission, ChequeDraftNo, ChequeDraftBank, TransactionID, CancelReceiptDate, Comments, 
                ReceiptStatus, LedgerName, Session, ClassRollNo, SemesterID, ReceiptNo, CreatedBy, CreatedDate)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $paramsInsert = [
                $DateEntry, $CollegeName, $IDNoLedger, $StudentName, $FatherName, $Remarks, $ModeOfPayment, 
                $Credit, $DateEntrySubmission, $ChequeDraftNo, $ChequeDraftBank, $TransactionID, 
                $currentDate, $Comments, 0, $LedgerName, $Session, $ClassRollNo, $SemesterID, $ReceiptNo, 
                $LedgerUserID, $currentDateSmall
            ];
        }

        $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);
        if(!$stmtInsert) throw new Exception(print_r(sqlsrv_errors(), true));

        echo "1";

   
}
else if($code == 21) {
   
        $sql = "SELECT * FROM CancelledReceipt WHERE ReceiptStatus = 0 ORDER BY CancelReceiptDate DESC";
        $stmt = sqlsrv_query($conn, $sql);

        if(!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
        $html .= '<thead>
                    <tr>
                     <th>ReceiptNo</th>
                <th>Create Date</th>
               <th>IDNO</th>
                <th>ClassRollNo</th>
                <th>Name</th>
                <th>Father Name</th>
              
                <th>Semester</th>
                <th>Amount</th>
                <th>Remarks</th>
             
                 <th>Particular</th>
                <th>Action</th>
            </tr>
                  </thead>';
        $html .= '<tbody>';

        $rowCount = 0;
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $cancelDate = $row['CancelReceiptDate'] ? $row['CancelReceiptDate']->format('Y-m-d H:i:s') : '';
            $html .= '<tr>
                        <td>'.$row['ReceiptNo'].'</td>
                        <td>'.$cancelDate.'</td>
                      
                        <td>'.$row['IDNo'].'</td>
                        <td>'.$row['ClassRollNo'].'</td>
                        
                        <td>'.$row['StudentName'].'</td>
                        <td>'.$row['FatherName'].'</td>
                        <td>'.$row['SemesterID'].'</td>
                        <td>'.$row['Credit'].'</td>
                        <td>'.$row['Comments'].'</td>
                          <td>'.$row['Particulars'].'</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                onclick="showStudentDetailsForCencelReceipts(
                                    '.$row['IDNo'].',
                                    '.$row['Credit'].',
                                    \''.addslashes($row['Comments']).'\',
                                    '.$row['SemesterID'].',
                                    '.$row['TransactionID'].',
                                    \''.addslashes($row['LedgerName']).'\',
                                    \''.$row['Session'].'\'
                                );">
                                View
                            </button>
                        </td>
                      </tr>';
            $rowCount++;
        }

        if($rowCount == 0) {
            $html .= '<tr><td colspan="9">No Record Found</td></tr>';
        }

        $html .= '</tbody></table>';

        echo $html;
}

elseif ($code == 22) {

    $id = trim($_POST['id']) ?? null;
  
    if ($id) {
        $sql = "SELECT * FROM Admissions 
        WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
        $idBigInt = is_numeric($id) ? (int)$id : 0;
        $params = [$id, $id, $idBigInt];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $IDNo = $row['IDNo'];
        }

        $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                       StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, IDNo,
                       StudentMobileNo, Country, FeeCategory, CommentFromAcc, CommentsDetail,
                       EmailID, Nationality, PermanentAddress, Sex, Course, DOB,
                       AddressLine1, PIN, Image, SignaturePath, LateralEntry,
                       Eligibility, EligibilityRemarks, BloodGroup, ABCID, Status,
                       OTR, ScolarShip
                FROM Admissions
                WHERE IDNo = :idno";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch();

        if ($student):
            $imagePath = "$BasURL/Images/Students/" . htmlspecialchars($student['Image']);
            $color = "#007bff";
?>


<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 text-center py-2">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color: <?= $color ?>;">
            </div>

            <div class="col">
                <div class="card-body">
                    <b><?= htmlspecialchars($student['StudentName']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    Uni Roll No: <?= htmlspecialchars($student['UniRollNo']) ?><br>
                    Class Roll No: <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                    Batch: <?= htmlspecialchars($student['Batch']) ?> &nbsp;&nbsp; LEET:
                    <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                    Session: <?= htmlspecialchars($student['Session']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <!-- <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div> -->
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <!-- <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div> -->
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment" style='font-size:10px;'>
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment" style='font-size:10px;'>
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>

<?php
$sql = "SELECT * FROM CancelledReceipt WHERE ReceiptStatus = 0 AND IDNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($IDNo));

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo '<table class="table">';
echo '<tr>
        <th>Sem</th>
        <th>Amount</th>
        <th>Comments</th>
        <th>Receipt No</th>
        <th colspan=2>Action</th>
      </tr>';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $TransactionID = $row['TransactionID'];
    $sedmid = $row['SemesterID'];
    $LedgerName = $row['LedgerName'];
    $Debit = $row['Credit'];
    $comments = $row['Comments'];
    $Session = $row['Session'];
     $ReceiptNo = $row['ReceiptNo'];
    
    // Escape single quotes for JS
    $LedgerNameJS = addslashes($LedgerName);
    $SessionJS = addslashes($Session);
    
    echo "<tr>
            <td>{$sedmid}</td>
            <td>{$Debit}</td>
            <td>{$comments}</td>
             <td>{$ReceiptNo}</td>
            <td>
                <button class='btn btn-danger btn-sm' 
                    onclick=\"verifyDebitCencelReceiptss('{$TransactionID}','{$sedmid}','{$LedgerNameJS}','{$IDNo}','{$SessionJS}','{$Debit}')\">
                    Verify
                </button></td><td>


                <button class='btn btn-danger btn-sm' 
                    onclick=\"deleteDebitCencelReceiptss('{$TransactionID}','{$sedmid}','{$LedgerNameJS}','{$IDNo}','{$SessionJS}','{$Debit}')\">
                    Delete
                </button>


            </td>
          </tr>";
}

echo '</table>';
?>

<?php
        else:
            echo "1";
        endif;
    } else {
        echo "1";
    }
}

elseif ($code==23) {
    $transactionId = $_POST['transactionId'];
$debitHead = $_POST['debitHead'];
$session = $_POST['session'];
$debitAmount = $_POST['debitAmount'];
$IDNo = $_POST['IDNo'];
 // Or pass from JS

$sql = "UPDATE CancelledReceipt 
        SET ReceiptStatus = 1, VerifiedBy = ?, VerifiedDate = ? 
        WHERE TransactionID = ? 
          AND LedgerName = ? 
          AND Session = ? 
          AND Credit = ? 
          AND IDNo = ?";

$params = [$EmployeeID, $currentSmallDate, $transactionId, $debitHead, $session, $debitAmount, $IDNo];

$stmt = sqlsrv_query($conn, $sql, $params);


$logDescription = "Receipt verified for TransactionID: $transactionId, Head: $debitHead, IDNo: $IDNo";
$logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
$logbookParams = [$IDNo, $logDescription, $EmployeeID, $currentSmallDate];
$stmtLog = sqlsrv_query($conn, $logbookSql, $logbookParams);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo "2";
    exit;
}

if (sqlsrv_rows_affected($stmt) > 0) {
    echo "1";
} else {
    echo "0";
}
}

elseif ($code==23.1) {
    $transactionId = $_POST['transactionId'];
$debitHead = $_POST['debitHead'];
$session = $_POST['session'];
$debitAmount = $_POST['debitAmount'];
$IDNo = $_POST['IDNo'];
 // Or pass from JS

$sql = "Delete From CancelledReceipt 
              WHERE TransactionID = ? 
          AND LedgerName = ? 
          AND Session = ? 
          AND Credit = ? 
          AND IDNo = ?";

$params = [$transactionId, $debitHead, $session, $debitAmount, $IDNo];

$stmt = sqlsrv_query($conn, $sql, $params);


$logDescription = " Cancel Receipt entry Deleted for TransactionID: $transactionId, Head: $debitHead, IDNo: $IDNo";
$logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
$logbookParams = [$IDNo, $logDescription, $EmployeeID, $currentSmallDate];
$stmtLog = sqlsrv_query($conn, $logbookSql, $logbookParams);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo "2";
    exit;
}

if (sqlsrv_rows_affected($stmt) > 0) {
    echo "1";
} else {
    echo "0";
}
}

else if($code == 24) {
   
    $sql = "SELECT * FROM CancelledReceipt WHERE ReceiptStatus = 1 ORDER BY CancelReceiptDate DESC";
    $stmt = sqlsrv_query($conn, $sql);

    if(!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }
    $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
    $html .= '<thead>
                <tr>
                    <th>Receipt No</th>
                    <th>Cancel Receipt Date</th>
                    <th>Particular</th>
                    <th>Name</th>
                    <th>Father Name</th>
                    <th>Semester</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th class="w-1">Action</th>
                </tr>
              </thead>';
    $html .= '<tbody>';

    $rowCount = 0;
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $cancelDate = $row['CancelReceiptDate'] ? $row['CancelReceiptDate']->format('Y-m-d H:i:s') : '';
        $html .= '<tr>
                    <td>'.$row['ReceiptNo'].'</td>
                    <td>'.$cancelDate.'</td>
                    <td>'.$row['Particulars'].'</td>
                    <td>'.$row['StudentName'].'</td>
                    <td>'.$row['FatherName'].'</td>
                    <td>'.$row['SemesterID'].'</td>
                    <td>'.$row['Credit'].'</td>
                    <td>'.$row['Comments'].'</td>
                    <td>
                        <button class="btn btn-secondary btn-sm" 
                            onclick="showStudentDetailsForCencelReceipts(
                                '.$row['IDNo'].',
                                '.$row['Credit'].',
                                \''.addslashes($row['Comments']).'\',
                                '.$row['SemesterID'].',
                                '.$row['TransactionID'].',
                                \''.addslashes($row['LedgerName']).'\',
                                \''.$row['Session'].'\'
                            );">
                            View
                        </button>
                    </td>
                  </tr>';
        $rowCount++;
    }

    if($rowCount == 0) {
        $html .= '<tr><td colspan="9">No Record Found</td></tr>';
    }

    $html .= '</tbody></table>';

    echo $html;
}

elseif ($code == 25) {

$id = trim($_POST['id']) ?? null;

if ($id) {
    $sql = "SELECT * FROM Admissions 
    WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
    $idBigInt = is_numeric($id) ? (int)$id : 0;
    $params = [$id, $id, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $IDNo = $row['IDNo'];
    }

    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                   StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, IDNo,
                   StudentMobileNo, Country, FeeCategory, CommentFromAcc, CommentsDetail,
                   EmailID, Nationality, PermanentAddress, Sex, Course, DOB,
                   AddressLine1, PIN, Image, SignaturePath, LateralEntry,
                   Eligibility, EligibilityRemarks, BloodGroup, ABCID, Status,
                   OTR, ScolarShip
            FROM Admissions
            WHERE IDNo = :idno";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
    $stmt->execute();
    $student = $stmt->fetch();

    if ($student):
        $imagePath = "$BasURL/Images/Students/" . htmlspecialchars($student['Image']);
        $color = "#007bff";
?>


<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 text-center py-2">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color: <?= $color ?>;">
            </div>

            <div class="col">
                <div class="card-body">
                    <b><?= htmlspecialchars($student['StudentName']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    Uni Roll No: <?= htmlspecialchars($student['UniRollNo']) ?><br>
                    Class Roll No: <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                    Batch: <?= htmlspecialchars($student['Batch']) ?> &nbsp;&nbsp; LEET:
                    <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                    Session: <?= htmlspecialchars($student['Session']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
        <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <!-- <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div> -->
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <!-- <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div> -->
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment" style='font-size:10px;'>
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment" style='font-size:10px;'>
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>

<?php
$sql = "SELECT * FROM CancelledReceipt WHERE ReceiptStatus = 1 AND IDNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($IDNo));

if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}

echo '<table class="table">';
echo '<tr>
    <th>Semester</th>
    <th>Amount</th>
    <th>Comments</th>
    <th>Action</th>
  </tr>';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
$TransactionID = $row['TransactionID'];
$sedmid = $row['SemesterID'];
$LedgerName = $row['LedgerName'];
$Debit = $row['Credit'];
$comments = $row['Comments'];
$Session = $row['Session'];

// Escape single quotes for JS
$LedgerNameJS = addslashes($LedgerName);
$SessionJS = addslashes($Session);

echo "<tr>
        <td>{$sedmid}</td>
        <td>{$Debit}</td>
        <td>{$comments}</td>
        <td>
            <button class='btn btn-danger btn-sm' 
                onclick=\"verifyDebitCencelReceiptss('{$TransactionID}','{$sedmid}','{$LedgerNameJS}','{$IDNo}','{$SessionJS}','{$Debit}')\">
                Verify
            </button>
        </td>
      </tr>";
}

echo '</table>';
?>

<?php
    else:
        echo "1";
    endif;
} else {
    echo "1";
}
}

elseif ($code == 26) {

    $transactionId = $_POST['transactionId'];
    $debitHead     = $_POST['debitHead'];
    $session       = $_POST['session'];
    $semesterId    = $_POST['SemesterID'];  // make sure this is sent from frontend
    $debitAmount   = $_POST['debitAmount'];
    $IDNo          = $_POST['IDNo'];
    $currentDate   = date('Y-m-d H:i:s');  // For approved date/time

    $sqlDeleteLedger = "DELETE FROM Ledger 
                        WHERE TransactionID = ? 
                          AND LedgerName = ? 
                          AND Session = ? 
                          AND SemesterID = ? 
                          AND Credit = ? 
                          AND IDNo = ?";

    $paramsLedger = [$transactionId, $debitHead, $session, $semesterId, $debitAmount, $IDNo];
    $stmtDelete = sqlsrv_query($conn, $sqlDeleteLedger, $paramsLedger);


    $logDescription = "Receipt Approve for TransactionID: $transactionId, Head: $debitHead, IDNo: $IDNo";
$logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
$logbookParams = [$IDNo, $logDescription, $EmployeeID, $currentSmallDate];
$stmtLog = sqlsrv_query($conn, $logbookSql, $logbookParams);
    if ($stmtDelete === false) {
        echo "0";
        exit;
    }
    if (sqlsrv_rows_affected($stmtDelete) > 0) {
        $sqlUpdate = "UPDATE CancelledReceipt 
                      SET ReceiptStatus = 2, 
                          ApprovedUserID = ?, 
                          ApprovedReceiptDate = ? 
                      WHERE TransactionID = ? 
                        AND LedgerName = ? 
                        AND Session = ? 
                        AND Credit = ? 
                        AND IDNo = ?";

        $paramsUpdate = [$EmployeeID, $currentDate, $transactionId, $debitHead, $session, $debitAmount, $IDNo];
        $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $paramsUpdate);

        if ($stmtUpdate === false) {
            echo "0";
            exit;
        }

        if (sqlsrv_rows_affected($stmtUpdate) > 0) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "0";
    }
}
elseif ($code == 27) {

    $collegeid   = $_POST['collegeid'];
    $courseid    = $_POST['courseid'];
    $session     = $_POST['feesession'];
    $batch       = $_POST['batch'];
    $semester    = $_POST['semester'];
    $feeCategory = $_POST['annualfeecategory'];
    $passout     = $_POST['passout'];
    $status      = $_POST['enrollment'];

    $where = "WHERE 1=1";

    if (!empty($status)) {
        $where .= " AND Status='$status'";
    }

    // passout filter: 0 = pursuing, 1 = passout
    if ($passout == 0) {
        $where .= " AND (PassOut != '1' OR PassOut IS NULL)";
    } else {
        $where .= " AND PassOut = '1'";
    }

    if (!empty($collegeid)) {
        $where .= " AND CollegeID='$collegeid'";
    }
    if (!empty($batch)) {
        $where .= " AND Batch='$batch'";
    }
    if (!empty($session)) {
        $where .= " AND Session='$session'";
    }
    if (!empty($courseid)) {
        $where .= " AND CourseID='$courseid'";
    }
    if (!empty($feeCategory)) {
        $where .= " AND FeeCategory='$feeCategory'";
    }

    // 🔹 Step 1: Fetch student data
    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                   StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, 
                   IDNo, StudentMobileNo, FeeCategory, CommentFromAcc, CommentsDetail,
                   Course, Status
            FROM Admissions $where";

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        echo "Error fetching Admissions: " . print_r(sqlsrv_errors(), true);
        exit;
    }

    $students = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $students[] = $row;
    }

    if (count($students) == 0) {
        echo "<div class='alert alert-warning'>No Record Found</div>";
        exit;
    }

    // 🔹 Step 2: Prepare ID list for Ledger
    $idList = implode(",", array_map(function ($s) {
        return "'" . $s['IDNo'] . "'";
    }, $students));

    // 🔹 Step 3: Fetch debit/credit sums
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
            'debit'  => $row['totaldebit'] ?? 0,
            'credit' => $row['totalcredit'] ?? 0
        ];
    }
   echo " <div style='max-height: 600px; overflow-y: auto;'>";
    echo "<table class='table table-bordered table-striped'>";
       echo " <thead style='position: sticky; top: 0; background: #f9f9f9; z-index: 40;'>
            <tr>
                <th>Sr No</th>
                <th>Session</th>
                <th>Class RollNo</th>
                <th>Uni RollNo</th>
                <th>IDNo</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>College</th>
                <th>Course</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
          </thead>
          <tbody>";

    $sr = 1;
    foreach ($students as $st) {
        $id = $st['IDNo'];
        $debit  = isset($ledgerData[$id]['debit']) ? $ledgerData[$id]['debit'] : 0;
        $credit = isset($ledgerData[$id]['credit']) ? $ledgerData[$id]['credit'] : 0;
        $balance = $debit - $credit;

        echo "<tr>
                <td>{$sr}</td>
                <td>{$st['Session']}</td>
                <td>{$st['ClassRollNo']}</td>
                <td>{$st['UniRollNo']}</td>
                <td>{$st['IDNo']}</td>
                <td>{$st['StudentName']}</td>
                <td>{$st['FatherName']}</td>
                <td>{$st['CollegeName']}</td>
                <td>{$st['Course']}</td>
                <td>{$debit}</td>
                <td>{$credit}</td>
                <td>{$balance}</td>
              </tr>";
        $sr++;
    }

    echo "</tbody></table></div>";
}
elseif ($code == 28) {

    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $status = $_POST['status'] ?? null;

    if (empty($startdate) || empty($enddate)) {
        echo "<div class='alert alert-warning'>Start Date and End Date are required</div>";
        exit;
    }

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
    // Generate HTML table
    echo " <div style='max-height: 600px; overflow-y: auto;'>";
    echo "<table class='table '>";
       echo " <thead style='position: sticky; top: 0;z-index: 40;'>

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
        $color = '';
        $statusText = '';
        if ($item['ConcessionStatus'] == '0') {
            $statusText = 'Pending to Verify';
            $color = '#F4BB44';
        } elseif ($item['ConcessionStatus'] == '1') {
            $statusText = 'Pending to Approve';
            $color = '#FFA500';
        } elseif ($item['ConcessionStatus'] == '2') {
            $statusText = 'Approved';
            $color = '#AFE1AF';
        } elseif ($item['ConcessionStatus'] == '-1') {
            $statusText = 'Deleted';
            $color = '#F88379';
        }

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';
        echo "<tr style='background-color: $color;'>
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

    echo "</tbody></table></div>";
}
elseif ($code == 29) {

    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $status = $_POST['status'] ?? null;

    if (empty($startdate) || empty($enddate)) {
        echo "<div class='alert alert-warning'>Start Date and End Date are required</div>";
        exit;
    }

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
    // Generate HTML table
    echo " <div style='max-height: 600px; overflow-y: auto;'>";
    echo "<table class='table '>";
       echo " <thead style='position: sticky; top: 0;z-index: 40;'>

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
        $color = '';
        $statusText = '';
        if ($item['DebitStatus'] == '0') {
            $statusText = 'Pending to Verify';
            $color = '#F4BB44';
        } elseif ($item['DebitStatus'] == '1') {
            $statusText = 'Pending to Approve';
            $color = '#FFA500';
        } elseif ($item['DebitStatus'] == '2') {
            $statusText = 'Approved';
            $color = '#AFE1AF';
        } elseif ($item['DebitStatus'] == '-1') {
            $statusText = 'Deleted';
            $color = '#F88379';
        }

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';
        echo "<tr style='background-color: $color;'>
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

    echo "</tbody></table></div>";
}
elseif ($code == 30) {

    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $status = $_POST['status'] ?? null;

    if (empty($startdate) || empty($enddate)) {
        echo "<div class='alert alert-warning'>Start Date and End Date are required</div>";
        exit;
    }

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
    // Generate HTML table
    echo " <div style='max-height: 600px; overflow-y: auto;'>";
    echo "<table class='table '>";
       echo " <thead style='position: sticky; top: 0;z-index: 40;'>

            <tr>
                <th>Create Date</th>
               <th>IDNO</th>
                <th>ClassRollNo</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>ReceiptNo</th>
                <th>Semester</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Status</th>
                 <th>Particular</th>
                <th>Created By</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($records as $item) {
        $color = '';
        $statusText = '';
        if ($item['ReceiptStatus'] == '0') {
            $statusText = 'Pending to Verify';
            $color = '#F4BB44';
        } elseif ($item['ReceiptStatus'] == '1') {
            $statusText = 'Pending to Approve';
            $color = '#FFA500';
        } elseif ($item['ReceiptStatus'] == '2') {
            $statusText = 'Approved';
            $color = '#AFE1AF';
        } elseif ($item['ReceiptStatus'] == '-1') {
            $statusText = 'Deleted';
            $color = '#F88379';
        }

        $dateEntry = isset($item['DateEntry']) ? $item['DateEntry']->format('d-m-Y') : '';
        echo "<tr style='background-color: $color;'>
                <td>$dateEntry</td>
              <td>{$item['IDNo']}</td>
              <td>{$item['ClassRollNo']}</td>
                <td>{$item['StudentName']}</td>
                <td>{$item['FatherName']}</td>
                <td>{$item['ReceiptNo']}</td>
                <td>{$item['SemesterID']}</td>
                <td>{$item['Debit']}</td>
                <td>{$item['Comments']}</td>
                <td>$statusText</td>
                  <td>{$item['Particulars']}</td>
                <td>{$item['CreatedBy']}</td>

              </tr>";
    }

    echo "</tbody></table></div>";
}


else if ($code == 31) {

    
     $debithead = $_POST['debithead'];
    $debitsession = $_POST['debitsession'];
    $debitparticulars = $_POST['debitparticulars'];
    $debitfee = $_POST['debitfee'];
    $debitsem = $_POST['debitsem'];
    $studentid = $_POST['studentid'];
    $modeofpayment = $_POST['modeofpayment'];
    $nameofbank = $_POST['nameofbank'];
    $transactionid = $_POST['transactionid'];
    $transactiondate = $_POST['transactiondate'];
    if (isset($_POST['IsOld'])) {
        $IsOld = $_POST['IsOld'];
    } else {
        $IsOld = 0;
    }
    if (isset($_POST['entryType'])) {
        $entryType = $_POST['entryType'];
    } else {
        $entryType = 1; 
    }
    if (isset($_POST['isChecked'])) {
        $isChecked = $_POST['isChecked'];
    } else {
        $isChecked = 0; // default fallback
    }

    if ($IsOld != '1') {
        $smallDateTime = date('Y-m-d H:i:s');
        $isOldStatus = 0;
    } else {
        $isOldStatus = 1;
        $smallDateTime = '2025-03-31 00:30:00';
    }
    $dateTime = date('Y-m-d H:i:s');
    $sql = "SELECT TOP 1 * FROM Admissions 
    WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
    $idBigInt = is_numeric($studentid) ? (int)$studentid : 0;
    $params = [$studentid, $studentid, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $student = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $IDNo = $student['IDNo'];
    $CollegeName = $student['CollegeName'];
    $StudentName = $student['StudentName'];
    $FatherName = $student['FatherName'];
    $Course = $student['Course'];
    $headQuery = "SELECT TOP 1 * FROM MasterHeadNew WHERE  Id = ?";
    $headStmt = sqlsrv_query($conn, $headQuery, [$debithead]);
    $headData = sqlsrv_fetch_array($headStmt, SQLSRV_FETCH_ASSOC);
    $head = $headData['Head'];
    $bankTransactionDate = date('Y-m-d H:i:s', strtotime($transactiondate));
    if ($modeofpayment == 'Cash') {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, AutoDebit, Status, IsOld)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, $debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $isChecked, 0, $isOldStatus];
    } 
    else {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, BankName, TransactionNo, TransactionDate, AutoDebit, Status, IsOld)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, $debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $nameofbank, $transactionid, $bankTransactionDate, $isChecked, 0, $isOldStatus];
    }

    $queryRemarks = "New Refund created  Mode: $modeofpayment, Amount: $debitfee, Head: $head";
    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
    $logbookParams = [$IDNo, $queryRemarks, $EmployeeID, $currentSmallDate];
    sqlsrv_query($conn, $logbookSql, $logbookParams);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt) {
        if ($entryType == 0) {
            $idQuery = "SELECT TOP 1 ID FROM PrintReceipt ORDER BY ID DESC";
            $idStmt = sqlsrv_query($conn, $idQuery);
            $idRow = sqlsrv_fetch_array($idStmt, SQLSRV_FETCH_ASSOC);
            $maxid = $idRow['ID'];
           echo  generateRefund($maxid, $conn, $EmployeeID, $currentSmallDate);
        } else {
            // echo "1";
            echo json_encode(["status" => '1', "message" => "Receipt Generated"]);
        }
    } else {
        // echo "0";
        echo json_encode(["status" => '0', "message" => "Receipt Generated"]);
    }
}
   


   else if($code == 32) {
   
         $sql = "SELECT * FROM ConcessionEntries WHERE ConcessionStatus = 0 ";
        $stmt = sqlsrv_query($conn, $sql);

        if(!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
        $html .= '<thead>
                    <tr>
                        
                        <th>Cancel Receipt Date</th>
                        <th>Particular</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Semester</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th class="w-1">Action</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';

        $rowCount = 0;
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $cancelDate = $row['DateEntry'] ? $row['DateEntry']->format('Y-m-d H:i:s') : '';
            $html .= '<tr>
                    
                        <td>'.$cancelDate.'</td>
                        <td>'.$row['Particulars'].'</td>
                        <td>'.$row['StudentName'].'</td>
                        <td>'.$row['FatherName'].'</td>
                        <td>'.$row['SemesterID'].'</td>
                        <td>'.$row['Debit'].'</td>
                        <td>'.$row['Remarks'].'</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                onclick="showStudentDetailsForCencelReceipts(
                                    '.$row['IDNo'].',
                                    '.$row['Debit'].',
                                    \''.addslashes($row['Remarks']).'\',
                                    '.$row['SemesterID'].',
                                    
                                    \''.addslashes($row['LedgerName']).'\',
                                    \''.$row['Session'].'\'
                                );">
                                View
                            </button>
                        </td>
                      </tr>';
            $rowCount++;
        }

        if($rowCount == 0) {
            $html .= '<tr><td colspan="9">No Record Found</td></tr>';
        }

        $html .= '</tbody></table>';

        echo $html;
}

elseif ($code == 33) {

    $id = trim($_POST['id']) ?? null;
  
    if ($id) {
        $sql = "SELECT * FROM Admissions 
        WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
        $idBigInt = is_numeric($id) ? (int)$id : 0;
        $params = [$id, $id, $idBigInt];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $IDNo = $row['IDNo'];
        }

        $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                       StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, IDNo,
                       StudentMobileNo, Country, FeeCategory, CommentFromAcc, CommentsDetail,
                       EmailID, Nationality, PermanentAddress, Sex, Course, DOB,
                       AddressLine1, PIN, Image, SignaturePath, LateralEntry,
                       Eligibility, EligibilityRemarks, BloodGroup, ABCID, Status,
                       OTR, ScolarShip
                FROM Admissions
                WHERE IDNo = :idno";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch();

        if ($student):
            $imagePath = "$BasURL/Images/Students/" . htmlspecialchars($student['Image']);
            $color = "#007bff";
?>


<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 text-center py-2">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color: <?= $color ?>;">
            </div>

            <div class="col">
                <div class="card-body">
                    <b><?= htmlspecialchars($student['StudentName']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    Uni Roll No: <?= htmlspecialchars($student['UniRollNo']) ?><br>
                    Class Roll No: <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                    Batch: <?= htmlspecialchars($student['Batch']) ?> &nbsp;&nbsp; LEET:
                    <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                    Session: <?= htmlspecialchars($student['Session']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <!-- <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div> -->
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <!-- <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div> -->
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment" style='font-size:10px;'>
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment" style='font-size:10px;'>
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>

<?php
 $sql = "SELECT * FROM ConcessionEntries WHERE ConcessionStatus = 0 AND IDNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($IDNo));

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo '<table class="table">';
echo '<tr>
        <th>Semester</th>
        <th>Amount</th>
        <th>Comments</th>
        <th>Action</th>
      </tr>';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $TransactionID = $row['TransactionID'];
    $sedmid = $row['SemesterID'];
        $ConcessionID = $row['ConcessionID'];
    $LedgerName = $row['LedgerName'];
    $Debit = $row['Debit'];
        $IDNo = $row['IDNo'];
    $comments = $row['Remarks'];
    $Session = $row['Session'];
    
    // Escape single quotes for JS
    $LedgerNameJS = addslashes($LedgerName);
    $SessionJS = addslashes($Session);
    
    echo "<tr>
            <td>{$sedmid}</td>
            <td>{$Debit}</td>
            <td>{$comments}</td>
            <td>
                <button class='btn btn-danger btn-sm' 
                    onclick=\"verifyDebitCencelReceiptss('{$ConcessionID}',{$IDNo})\">
                    Verify
                </button>
            </td>
          </tr>";
}

echo '</table>';
?>

<?php
        else:
            echo "1";
        endif;
    } else {
        echo "1";
    }
}

      elseif ($code==34) {

$id = $_POST['id'];

$currentSmallDate = date('Y-m-d H:i:s'); // Or pass from JS

 $sql = "UPDATE ConcessionEntries set ConcessionStatus='1' where  ConcessionID=?";

 
$params = [$id];  
 
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    echo "2";
    exit;
}

if (sqlsrv_rows_affected($stmt) > 0) {
    echo "1";
} else {
    echo "0";
}
}



   else if($code == 35) {
   
         $sql = "SELECT * FROM ConcessionEntries WHERE ConcessionStatus = 1 ";
        $stmt = sqlsrv_query($conn, $sql);

        if(!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
        $html .= '<thead>
                    <tr>
                        
                        <th>Cancel Receipt Date</th>
                        <th>Particular</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Semester</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th class="w-1">Action</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';

        $rowCount = 0;
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $cancelDate = $row['DateEntry'] ? $row['DateEntry']->format('Y-m-d H:i:s') : '';
            $html .= '<tr>
                    
                        <td>'.$cancelDate.'</td>
                        <td>'.$row['Particulars'].'</td>
                        <td>'.$row['StudentName'].'</td>
                        <td>'.$row['FatherName'].'</td>
                        <td>'.$row['SemesterID'].'</td>
                        <td>'.$row['Debit'].'</td>
                        <td>'.$row['Remarks'].'</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                onclick="showStudentDetailsForCencelReceipts(
                                    '.$row['IDNo'].',
                                    '.$row['Debit'].',
                                    \''.addslashes($row['Remarks']).'\',
                                    '.$row['SemesterID'].',
                                    
                                    \''.addslashes($row['LedgerName']).'\',
                                    \''.$row['Session'].'\'
                                );">
                                View
                            </button>
                        </td>
                      </tr>';
            $rowCount++;
        }

        if($rowCount == 0) {
            $html .= '<tr><td colspan="9">No Record Found</td></tr>';
        }

        $html .= '</tbody></table>';

        echo $html;
}

elseif ($code == 36) {

    $id = trim($_POST['id']) ?? null;
  
    if ($id) {
        $sql = "SELECT * FROM Admissions 
        WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
        $idBigInt = is_numeric($id) ? (int)$id : 0;
        $params = [$id, $id, $idBigInt];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $IDNo = $row['IDNo'];
        }

        $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                       StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, IDNo,
                       StudentMobileNo, Country, FeeCategory, CommentFromAcc, CommentsDetail,
                       EmailID, Nationality, PermanentAddress, Sex, Course, DOB,
                       AddressLine1, PIN, Image, SignaturePath, LateralEntry,
                       Eligibility, EligibilityRemarks, BloodGroup, ABCID, Status,
                       OTR, ScolarShip
                FROM Admissions
                WHERE IDNo = :idno";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch();

        if ($student):
            $imagePath = "$BasURL/Images/Students/" . htmlspecialchars($student['Image']);
            $color = "#007bff";
?>


<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 text-center py-2">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color: <?= $color ?>;">
            </div>

            <div class="col">
                <div class="card-body">
                    <b><?= htmlspecialchars($student['StudentName']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    Uni Roll No: <?= htmlspecialchars($student['UniRollNo']) ?><br>
                    Class Roll No: <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                    Batch: <?= htmlspecialchars($student['Batch']) ?> &nbsp;&nbsp; LEET:
                    <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                    Session: <?= htmlspecialchars($student['Session']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <!-- <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div> -->
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <!-- <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div> -->
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment" style='font-size:10px;'>
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment" style='font-size:10px;'>
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>

<?php
 $sql = "SELECT * FROM ConcessionEntries WHERE ConcessionStatus = 1 AND IDNo = ?";
//  $sql = "UPDATE ConcessionEntries set ConcessionStatus='1',VerifiedBy='$EmployeeID',VerifiedDate='$currentSmallDate' where  ConcessionID=?";
 $stmt = sqlsrv_query($conn, $sql, array($IDNo));

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo '<table class="table">';
echo '<tr>
        <th>Semester</th>
        <th>Amount</th>
        <th>Comments</th>
        <th>Action</th>
      </tr>';

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $TransactionID = $row['TransactionID'];
    $sedmid = $row['SemesterID'];
        $ConcessionID = $row['ConcessionID'];
    $LedgerName = $row['LedgerName'];
    $Debit = $row['Debit'];
        $IDNo = $row['IDNo'];
    $comments = $row['Remarks'];
    $Session = $row['Session'];
    
    // Escape single quotes for JS
    $LedgerNameJS = addslashes($LedgerName);
    $SessionJS = addslashes($Session);
    
    echo "<tr>
            <td>{$sedmid}</td>
            <td>{$Debit}</td>
            <td>{$comments}</td>
            <td>
                <button class='btn btn-danger btn-sm' 
                    onclick=\"verifyDebitCencelReceiptss('{$ConcessionID}',{$IDNo})\">
                    Verify
                </button>
            </td>
          </tr>";
}

echo '</table>';
?>

<?php
        else:
            echo "1";
        endif;
    } else {
        echo "1";
    }
}

      elseif ($code==37) {


    $concessionId = $_POST['id']; 
    $dateTime = date('Y-m-d H:i:s');

    // 1. Update concession status
    $updateSql = "UPDATE ConcessionEntries
                  SET ConcessionStatus =2, ApprovedBy = :approvedBy, ApprovedDate = :approvedDate
                  WHERE ConcessionID = :concessionId";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([
        ':approvedBy' => $EmployeeID,
        ':approvedDate' => $dateTime,
        ':concessionId' => $concessionId
    ]);

    if ($stmt->rowCount() > 0) {
        // 2. Fetch the updated record
        $selectSql = "SELECT * FROM ConcessionEntries WHERE ConcessionID = :concessionId";
        $stmt = $pdo->prepare($selectSql);
        $stmt->execute([':concessionId' => $concessionId]);
        $concession = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($concession && $concession['ConcessionStatus'] == 2) {
            // Extract variables for ledger entry
            $debitHead = $concession['LedgerName'];
            $debitSession = $concession['Session'];
            $debitSemester = $concession['SemesterID'];
            $debitRemarks = $concession['Remarks'];
            $debitFee = $concession['Debit'];
            $idNo = $concession['IDNo'];
            $id = $concession['IDNo'];
            $particulars = $concession['Particulars'];
            $creatorId = $concession['CreatedBy'];
            $concessioninLedger = "ConcessionId-" . $concessionId;



        $sql = "SELECT * FROM Admissions 
        WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";
        $idBigInt = is_numeric($id) ? (int)$id : 0;
        $params = [$id, $id, $idBigInt];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            $IDNo = $row['IDNo'];
        }

        $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,
                       StudentName, FatherName, MotherName, ClassRollNo, UniRollNo, IDNo,
                       StudentMobileNo, Country, FeeCategory, CommentFromAcc, CommentsDetail,
                       EmailID, Nationality, PermanentAddress, Sex, Course, DOB,
                       AddressLine1, PIN, Image, SignaturePath, LateralEntry,
                       Eligibility, EligibilityRemarks, BloodGroup, ABCID, Status,
                       OTR, ScolarShip
                FROM Admissions
                WHERE IDNo = :idno";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
        $stmt->execute();
        $student = $stmt->fetch();

       
        $debitsemestertext = getSemesterName($debitSemester);
        
              $dateTime = date('Y-m-d H:i:s');         
        $smallDateTime = date('Ymd');           
        
        $stmt = $pdo->query("SELECT MAX(TransactionID) AS MaxTransactionID FROM Ledger");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newTransactionId = ($row['MaxTransactionID'] ?? 0) + 1;
        

        $insertSql = "INSERT INTO Ledger
            (Session, CollegeName, TransactionID, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, TransactionType, UserID, ValueDate, Remarks)
            VALUES
            (:Session, :CollegeName, :TransactionID, :DateEntry, :IDNo, :UniRollNo, :StudentName, :FatherName, :MotherName, :Course, :Batch, :ClassRollNo, :Semester, :SemesterID, :FeeCategory, :Sex, :OnAccountOf, :Particulars, :Credit, :LedgerName, :TransactionType, :UserID, :ValueDate, :Remarks)";
        
        $stmt = $pdo->prepare($insertSql);
        
        $params = [
            ':Session'       => $debitSession,
            ':CollegeName'   => $student['CollegeName'],
            ':TransactionID' => $newTransactionId,
            ':DateEntry'     => $smallDateTime,
            ':IDNo'          => $id,
            ':UniRollNo'     => $student['UniRollNo'],
            ':StudentName'   => $student['StudentName'],
            ':FatherName'    => $student['FatherName'],
            ':MotherName'    => $student['MotherName'],
            ':Course'        => $student['Course'],
            ':Batch'         => $student['Batch'],
            ':ClassRollNo'   => $student['ClassRollNo'],
            ':Semester'      => $debitsemestertext,
            ':SemesterID'    => $debitSemester,
            ':FeeCategory'   => $student['FeeCategory'],
            ':Sex'           => $student['Sex'],
            ':OnAccountOf'   => $particulars,
            ':Particulars'   => $concessioninLedger,
            ':Credit'        => -$debitFee,
            ':LedgerName'    => $debitHead,
            ':TransactionType' => 'Debit',
            ':UserID'        => $creatorId,
            ':ValueDate'     => $dateTime,
            ':Remarks'       => $debitRemarks,






        ];
        
        $stmt->execute($params);
        
        if ($stmt->rowCount() > 0) {


                $updateTransactionSql = "UPDATE ConcessionEntries
                                         SET TransactionID = :transactionId
                                         WHERE ConcessionID = :concessionId";
                $stmt2 = $pdo->prepare($updateTransactionSql);
                $stmt2->execute([
                    ':transactionId' => $newTransactionId,
                    ':concessionId' => $concessionId
                ]);

   if ($stmt2->rowCount() > 0) {



                   try { // Insert into logbook
                    $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
                    $logStmt = $pdo->prepare($logbookSql);

        $queryRemarks = "Approved concession entry ID $concessionId and created ledger entry TX: " . $newTransactionId;
                    $currentDate = date('Y-m-d H:i:s');

                    $logParams = [(int)$id,$queryRemarks,$EmployeeID,$currentDate];

                    $logStmt->execute($logParams);

                   
                    if ($logStmt->rowCount() > 0) {

                    echo 1;
                }


                else
                {
                }

                } catch (PDOException $e) {
    echo "Logbook insert error: " . $e->getMessage();
}



                } 


                else {
                    echo "Failed to update transaction ID";
                }


            
              } 

              else {
            
        
           }
    }
       
   else {
                echo "Ledger entry creation failed";
            }


}




}

}