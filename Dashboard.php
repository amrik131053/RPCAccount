<?php
include "header.php";
date_default_timezone_set("Asia/Kolkata"); // India time
$todaydate = date('Y-m-d');
$UserID = $_SESSION['user_ac'];



function getTotalCredit($conn, $role_id, $UserID, $mode = null) {
    $todaydate = date('Y-m-d');
    if ($role_id == 1 || $role_id == 2) {
        $sql = "SELECT SUM(Credit) AS TotalCredit FROM Ledger WHERE CAST(DateEntry AS DATE) = ?";
        $params = array($todaydate);
        if ($mode) {
            $sql .= " AND ModeOfPayment = ?";
            $params[] = $mode;
        }
    } else {
        $sql = "SELECT SUM(Credit) AS TotalCredit FROM Ledger WHERE CAST(DateEntry AS DATE) = ? AND UserID = ?";
        $params = array($todaydate, $UserID);
        if ($mode) {
            $sql .= " AND ModeOfPayment = ?";
            $params[] = $mode;
        }
    }


    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if (!$stmt) { die(print_r(sqlsrv_errors(), true)); }
    if (!sqlsrv_execute($stmt)) { die(print_r(sqlsrv_errors(), true)); }
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    return $result['TotalCredit'] ?? 0;
}

// Function to get count from table
function getCount($conn, $role_id, $UserID, $table, $condition = '', $sessionColumn = null, $session = null) {
    $sql = "";
    $params = array();

    if ($role_id == 1 || $role_id == 2) {
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE 1=1";
        if ($sessionColumn && $session) {
            $sql .= " AND $sessionColumn = ?";
            $params[] = $session;
        }
        if ($condition) { $sql .= " AND $condition"; }
    } else {
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE CreatedBy = ?";
        $params[] = $UserID;
        if ($sessionColumn && $session) {
            $sql .= " AND $sessionColumn = ?";
            $params[] = $session;
        }
        if ($condition) { $sql .= " AND $condition"; }
    }

    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if (!$stmt) { die(print_r(sqlsrv_errors(), true)); }
    if (!sqlsrv_execute($stmt)) { die(print_r(sqlsrv_errors(), true)); }
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    return $result['count'] ?? 0;
}
// Dashboard calculations
$TotalCash = getTotalCredit($conn, $role_id, $UserID, 'Cash');
$TotalBankTransfer = getTotalCredit($conn, $role_id, $UserID, 'Bank Transfer');
$TotalGateway = getTotalCredit($conn, $role_id, $UserID, 'Payment Gateway');

$TotalCredit = $TotalCash + $TotalBankTransfer + $TotalGateway;
$cashPercentage = $TotalCredit > 0 ? round(($TotalCash / $TotalCredit) * 100, 2) : 0;
$bankPercentage = $TotalCredit > 0 ? round(($TotalBankTransfer / $TotalCredit) * 100, 2) : 0;
$gatewayPercentage = $TotalCredit > 0 ? round(($TotalGateway / $TotalCredit) * 100, 2) : 0;

// Fetch current session
$sessionStmt = sqlsrv_query($conn, "SELECT CurrentSession FROM MasterSession WHERE DefaultSession = 1");
$currentSession = sqlsrv_fetch_array($sessionStmt, SQLSRV_FETCH_ASSOC);
 $presentSession = $currentSession['CurrentSession'] ?? '';
if ($role_id == 1 || $role_id == 2) {
    $sql = "SELECT COUNT(*) AS ReceiptsCount 
            FROM Ledger 
            WHERE DateEntry BETWEEN ? AND ? 
            AND Credit > 0";
    $params = array($todaydate . ' 00:01', $todaydate . ' 23:59');
} else {
    $sql = "SELECT COUNT(*) AS ReceiptsCount 
            FROM Ledger 
            WHERE CAST(DateEntry AS DATE) = ? 
            AND UserID = ?";
    $params = array($todaydate, $UserID);
}

$stmt = sqlsrv_prepare($conn, $sql, $params);
if (!$stmt) { die(print_r(sqlsrv_errors(), true)); }
if (!sqlsrv_execute($stmt)) { die(print_r(sqlsrv_errors(), true)); }
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$ReceiptsCount = $row['ReceiptsCount'] ?? 0;
sqlsrv_free_stmt($stmt);

// ---------------------- Pending Receipts ----------------------
if ($role_id == 1 || $role_id == 2) {
    $sql = "SELECT COUNT(*) AS PendingReceiptsCount 
            FROM PrintReceipt 
            WHERE Session = ? 
            AND Status = '0'";
    $params = array($presentSession);
} else {
    $sql = "SELECT COUNT(*) AS PendingReceiptsCount 
            FROM PrintReceipt 
            WHERE CreatedBy = ? 
            AND Status = '0'";
    $params = array($UserID);
}

$stmt = sqlsrv_prepare($conn, $sql, $params);
if (!$stmt) { die(print_r(sqlsrv_errors(), true)); }
if (!sqlsrv_execute($stmt)) { die(print_r(sqlsrv_errors(), true)); }
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$PendingReceiptsCount = $row['PendingReceiptsCount'] ?? 0;
sqlsrv_free_stmt($stmt);


$sql = "SELECT COUNT(*) AS ActiveStudents 
        FROM Admissions 
        WHERE Admissions.Status = '1' 
        AND (PassOut != 1 OR PassOut IS NULL)";

$stmt = sqlsrv_query($conn, $sql);
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$TotalStudents = $row['ActiveStudents'] ?? 0;
$wholeDebitStudentsData = 0; // default
if ($role_id == 1 || $role_id == 2) {
    $sql = "SELECT SUM(CAST(ledger.Debit AS BIGINT)) AS totaldebit, 
                   SUM(CAST(ledger.Credit AS BIGINT)) AS totalcredit
            FROM ledger
            INNER JOIN Admissions ON ledger.IDNo = Admissions.IDNO
            WHERE Admissions.Status='1' AND (Admissions.PassOut != '1' OR Admissions.PassOut IS NULL)";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $wholeDebitStudentsData = ($row['totaldebit'] ?? 0) - ($row['totalcredit'] ?? 0);
}

function formatRupeesIndian($number) {
    $decimal = '';
    if(strpos($number, '.') !== false){
        $parts = explode('.', $number);
        $number = $parts[0];
        $decimal = '.' . substr($parts[1], 0, 2); // keep 2 decimal places
    }
    $lastThree = substr($number, -3);
    $rest = substr($number, 0, -3);
    if ($rest != '') {
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest) . ",";
    }
    return '₹ ' . $rest . $lastThree . $decimal;
}
?>

<div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <!-- Cash Card -->
        <div class="col-sm-6 col-lg-3">
        <div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="subheader">Cash</div>

        </div>  
        <div class="h1 mb-3"><?= formatRupeesIndian($TotalCash) ?></div>
        <div class="d-flex mb-2">
            <div>Cash Percentage</div>
            <div class="ms-auto">
                <span class="text-green"><?= $cashPercentage ?>%</span>
            </div>
        </div>
        <div class="progress progress-sm">
            <div class="progress-bar bg-primary" style="width: <?= $cashPercentage ?>%" role="progressbar" aria-valuenow="<?= $cashPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

        </div>
        <!-- Bank Card -->
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Bank Transfer</div>
              </div>
              <div class="h1 mb-3"><?= formatRupeesIndian($TotalBankTransfer) ?></div>
              <div class="d-flex mb-2">
                <div>Bank Transfer Percentage</div>
                <div class="ms-auto">
                  <span class="text-green"><?= $bankPercentage ?>%</span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: <?= $bankPercentage ?>%" role="progressbar" aria-valuenow="<?= $bankPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- Payment Gateway Card -->
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Payment Gateway</div>
              </div>
              <div class="h1 mb-3"><?= formatRupeesIndian($TotalGateway) ?></div>
              <div class="d-flex mb-2">
                <div>Online Percentage</div>
                <div class="ms-auto">
                  <span class="text-green"><?= $gatewayPercentage ?>%</span>
                </div>
              </div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: <?= $gatewayPercentage ?>%" role="progressbar" aria-valuenow="<?= $gatewayPercentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- Total Amount Card -->
        <div class="col-sm-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Total Amount</div>
              </div>
              <div class="h1 mb-3"><?= formatRupeesIndian($TotalCredit) ?></div>
              <div>Total Amount</div>
              <div class="progress progress-sm">
                <div class="progress-bar bg-primary" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Receipts & Debits Row -->
      <div class="row row-cards mt-3">
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto"><span class="bg-primary text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-stack"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path><path d="M5 21h14"></path><path d="M5 18h14"></path><path d="M5 15h14"></path></svg></span></div>
                <div class="col">
                    <div class="font-weight-medium"><?= $ReceiptsCount ?></div>
                    <div class="text-muted">Total Receipts</div>
                </div>
                <div class="col-auto" onclick="exportTotalReceipts();"><span class="bg-success text-white avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto"><span class="bg-green text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-currency-rupee"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 5h-11h3a4 4 0 0 1 0 8h-3l6 6"></path><path d="M7 9l11 0"></path></svg></span></div>
                <div class="col">
                  <div class="font-weight-medium"><?= $PendingReceiptsCount ?></div>
                  <div class="text-muted">Pending Receipts</div>
                </div>
                <div class="col-auto" onclick="exportPendingReceipts();"><span class="bg-success text-white avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto"><span class="bg-twitter text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-currency-rupee"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 5h-11h3a4 4 0 0 1 0 8h-3l6 6"></path><path d="M7 9l11 0"></path></svg></span></div>
                <div class="col">
                  <div class="font-weight-medium"><?= formatRupeesIndian($wholeDebitStudentsData) ?></div>
                  <div class="text-muted">Pending Collection <span style="color:red">(Active Student)</span></div>
                </div>
                <div class="col-auto" onclick="exportPendingCollection();"><span class="bg-success text-white avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto"><span class="bg-facebook text-white avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M17 10h2a2 2 0 0 1 2 2v1"></path><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path></svg></span></div>
                <div class="col">
                  <div class="font-weight-medium"><?= $TotalStudents ?></div>
                  <div class="text-muted">Total Students</div>
                </div>
                <div class="col-auto" onclick="exportPendingCollection();"><span class="bg-success text-white avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg></span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
    function exportTotalReceipts()
    {
         var startDate = '<?= $todaydate ?>';
  
        
        window.location.href = 'export.php?StartDate='+startDate + '&EndDate=' +startDate+ '&exportCode=' +1;
    }
    function exportPendingReceipts()
    {
        window.location.href = 'export.php?exportCode=' +2;
    }
    function exportPendingCollection()
    {
       
         window.location.href = 'export.php?exportCode=' +3;
    }
    function exportTotalStudents()
    {
         window.location.href = 'export.php?StartDate='+startDate + '&EndDate=' +startDate+ '&exportCode=' +3;
    }
</script>