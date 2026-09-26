<?php
date_default_timezone_set("Asia/Kolkata"); // India time (GMT+5:30)
$CurrentExaminationGetDate = date('Y-m-d');
$EmployeeID = isset($_SESSION['user_ac']) ? $_SESSION['user_ac'] : 0;
if ($EmployeeID == 0 || $EmployeeID == '') { ?>
    <script type="text/javascript">
        window.location.href = "index.php";
    </script>
<?php
    exit;
}

include "connection/connection.php";

// ============================
// Get  ALL Batchs
// ============================
   $years = range($currentYear,2011);
  
   $semesters = range(1,20);
// ============================
// Get MasterHeadNew
// ============================
$sql = "SELECT * FROM MasterHeadNew WHERE Status = ? ORDER BY Head ASC";
$params = [1];
$stmt = sqlsrv_query($conn, $sql, $params);
$heads = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $heads[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}

// ============================
// Get Default Session
// ============================
$sql = "SELECT * FROM MasterSession WHERE DefaultSession = ?";
$params = [1];
$stmt = sqlsrv_query($conn, $sql, $params);
$accountsessions = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $accountsessions[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}

// ============================
// Get all  Session
// ============================

$sql = "SELECT * FROM MasterSession";

$stmt = sqlsrv_query($conn, $sql);
$accountsessionsall = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $accountsessionsall[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}





// ============================
// Get Academic Session
// ============================
$sql = " SELECT Distinct Session FROM MasterCourseCodes order by Session desc";
$params = [1];
$stmt = sqlsrv_query($conn, $sql);
$academicsessions = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $academicsessions[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}


// ============================
//  Active Fee Categories  
// ============================

$sql = "SELECT * FROM FeeCategoryNew WHERE Status = 1";
$params = [1];
$stmt = sqlsrv_query($conn, $sql);
$masterfeecategory = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $masterfeecategory[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}


// ============================
// Get Faculties for Employee
// ============================
$sql = "SELECT DISTINCT MasterCourseCodes.CollegeName, MasterCourseCodes.CollegeID 
        FROM MasterCourseCodes  
        INNER JOIN UserAccessLevel 
        ON UserAccessLevel.CollegeID = MasterCourseCodes.CollegeID 
        WHERE UserAccessLevel.IDNo = ? 
        ORDER BY CollegeID";
$params = [$EmployeeID];
$stmt = sqlsrv_query($conn, $sql, $params);
$faculities = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $faculities[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}

// ============================
// Get Employees with RoleID = 22
// ============================
$query = "SELECT Name, Designation, IDNo FROM Staff WHERE RoleID = ?";
$params = ['22'];
$get_pending_run = sqlsrv_query($conn, $query, $params);
$accountEmployees = [];
if ($get_pending_run) {
    while ($get_row = sqlsrv_fetch_array($get_pending_run, SQLSRV_FETCH_ASSOC)) {
        $accountEmployees[] = $get_row;
    }
    sqlsrv_free_stmt($get_pending_run);
}
// ============================
// Get Banks 
// ============================
$query = "SELECT * FROM MasterBank order by BankName ASC";
$getAllBanks = sqlsrv_query($conn, $query);
$AllBanks = [];
if ($getAllBanks) {
    while ($get_row = sqlsrv_fetch_array($getAllBanks, SQLSRV_FETCH_ASSOC)) {
        $AllBanks[] = $get_row;
    }
    sqlsrv_free_stmt($getAllBanks);
}
?>
