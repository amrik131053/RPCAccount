<?php 
session_start();
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
include "connection/connection.php";
$todaydate = date('Y-m-d');
$timeStamp = date('Y-m-d H-i');
if (!isset($_SESSION['user_ac'])) {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    exit;
}
$EmployeeID = $_SESSION['user_ac'];
$EmployeeID = is_numeric($EmployeeID) ? (int)$EmployeeID : trim($EmployeeID);

if ($EmployeeID === 0 || $EmployeeID === '' || empty($EmployeeID)) {
    echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    exit;
}
$role_id = '0';
$permissions_array = "";
$r = [];
$p = [];
$id = "0";
$staffSql = "
    SELECT Name, ShiftID, Snap, personalIdentificationMark, Designation, Department, DateOfJoining,
           LeaveSanctionAuthority, CollegeID, AccountRoleId, FatherName, MotherName, DateOfBirth, Gender,
           PANNo, EmailID, OfficialEmailID, MobileNo, WhatsAppNumber, EmergencyContactNo,
           OfficialMobileNo, PostalCode, PermanentAddress, CorrespondanceAddress, Nationality,
           SalaryAtPresent, BankAccountNo, BankName, BankIFSC, State, District, PostOffice,
           Imagepath, ImageStatus, BloodGroup
    FROM Staff
    WHERE IDNo = ? and JobStatus='1'
";
$paramsStaff = array($EmployeeID);
$stmt = sqlsrv_prepare($conn, $staffSql, $paramsStaff);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (!sqlsrv_execute($stmt)) {
    die(print_r(sqlsrv_errors(), true));
}
$Emp_Name = $Emp_Image = $ImagePath = $Emp_Department = $Emp_Designation = $Emp_CollegeID = $DateOfJoining = $LeaveSanctionAuthority = null;
$role_id = '0';
$ShiftID = null;

while ($row_staff = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $Emp_Name = $row_staff['Name'];
    $Emp_Image = $row_staff['Snap'];
    $ImagePath = $row_staff['Imagepath'];
    $Emp_Department = $row_staff['Department'];
    $Emp_Designation = $row_staff['Designation'];
    $Emp_CollegeID = $row_staff['CollegeID'];
    $DateOfJoining = $row_staff['DateOfJoining'];
    $LeaveSanctionAuthority = $row_staff['LeaveSanctionAuthority'];
     $role_id = $row_staff['AccountRoleId'];
    $ShiftID = $row_staff['ShiftID'];
}
sqlsrv_free_stmt($stmt);
$role_get = "SELECT r.Id AS RouteId FROM MainMenuAccounts AS m
LEFT JOIN RouteMaster AS r ON m.Id = r.MainMenuId
LEFT JOIN RolePermissionAccounts AS p  ON r.Id = p.RouteId AND p.RoleId = ?
WHERE  (ISNULL(p.ReadPermission,0) = 1  OR ISNULL(p.WritePermission,0) = 1  OR ISNULL(p.DeletePermission,0) = 1)
ORDER BY m.[OrderMenu] ASC  ";
$paramsRole = array($role_id);
$role_run = sqlsrv_prepare($conn, $role_get, $paramsRole);
if ($role_run === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (!sqlsrv_execute($role_run)) {
    die(print_r(sqlsrv_errors(), true));
}
$r = []; 
while ($row_role_get = sqlsrv_fetch_array($role_run, SQLSRV_FETCH_ASSOC)) {
    if (isset($row_role_get['RouteId'])) {
        $r[] = $row_role_get['RouteId'];
    }
}
sqlsrv_free_stmt($role_run);
$p = [];
$array_aa = array_unique(array_merge($r, $p));

$urls = array('Dashboard.php','not_found.php');
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);
 $file = basename($path);
include 'activity.php';
$updateactivity = "INSERT INTO UserActivity (IDNo, ActivityType, ActivityDescription, IPAddress, Broswer, DeviceType, CreatedAt) VALUES (?, ?, ?, ?, ?, ?, ?)";
$paramslog = array($EmployeeID, $file, '', $ipAddress ?? '', $browserName ?? '', $deviceType ?? '', $timeStampS ?? '');
$stmt3 = sqlsrv_prepare($conn, $updateactivity, $paramslog);
if ($stmt3 === false) {
} else {
    if (!sqlsrv_execute($stmt3)) {
    }
}
sqlsrv_free_stmt($stmt3);
if (!in_array($file, $urls)) {
    $routeQuery = "SELECT Id FROM RouteMaster WHERE Route = ?   ";
    $paramsRoute = array($file);
     $routeStmt = sqlsrv_prepare($conn, $routeQuery, $paramsRoute);

    if ($routeStmt === false) {
        header('Location: not_found.php');
        exit;
    }
    if (!sqlsrv_execute($routeStmt)) {
        header('Location: not_found.php');
        exit;
    }
    $foundId = null;
    while ($row1 = sqlsrv_fetch_array($routeStmt, SQLSRV_FETCH_ASSOC)) {
        $foundId = $row1['Id'];
    }
    sqlsrv_free_stmt($routeStmt);
    if ($foundId === null || !in_array($foundId, $array_aa)) {
        header('Location:not_found.php');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Accounts</title>
    <!-- CSS files -->
    <link href="styles/dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="styles/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="styles/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="styles/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="styles/dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <link href="mycss.css" rel="stylesheet" />
</head>

<body class="layout-fluid">
    <div id="fullScreenLoader" class="full-screen-loader" style="display:none;">
        <div class="loader"></div>
    </div>
    <script src="styles/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="tablerToastSuccess" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="tablerToastWarning" class="toast align-items-center text-bg-warning border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <header class="navbar-expand-md" >
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar" style="background-color:#223260;color:white;">
                    <div class="container-xl">

                        <ul class="navbar-nav">
                            <ul class="navbar-nav">
                                <?php

// Assuming you already have a connection $conn to SQL Server
$query = "
SELECT 
    m.Id AS MainMenuId,
    m.MainMenuName,
    r.Id AS RouteId,
    r.PageName,
    r.Route,
    p.RoleId,
    ISNULL(p.ReadPermission,0) AS ReadPermission,
    ISNULL(p.WritePermission,0) AS WritePermission,
    ISNULL(p.DeletePermission,0) AS DeletePermission
FROM MainMenuAccounts AS m
LEFT JOIN RouteMaster AS r ON m.Id = r.MainMenuId
LEFT JOIN RolePermissionAccounts AS p 
    ON r.Id = p.RouteId AND p.RoleId = ?
WHERE 
    (ISNULL(p.ReadPermission,0) = 1 
     OR ISNULL(p.WritePermission,0) = 1 
     OR ISNULL(p.DeletePermission,0) = 1)
ORDER BY m.[OrderMenu] ASC;
";

$params = [$role_id];
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$menus = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $menuId = $row['MainMenuId'];
    if (!isset($menus[$menuId])) {
        $menus[$menuId] = [
            'MenuID' => $menuId,
            'MenuName' => $row['MainMenuName'],
            'SubMenus' => []
        ];
    }

    if (!empty($row['RouteId'])) {
        $menus[$menuId]['SubMenus'][] = [
            'SubMenuID' => $row['RouteId'],
            'SubMenuName' => $row['PageName'],
            'RouteName' => $row['Route'],
            'RoleId' => $row['RoleId'],
            'Permissions' => [
                'Create' => (int)$row['ReadPermission'],
                'Write'  => (int)$row['WritePermission'],
                'Delete' => (int)$row['DeletePermission']
            ]
        ];
    }
}

$AllMenuBars = array_values($menus);
foreach ($AllMenuBars as $menu) {
    $visibleSubMenus = [];
    if (!empty($menu['SubMenus'])) {
        foreach ($menu['SubMenus'] as $sub) {
            $permissions = $sub['Permissions'] ?? [];
            $canRead   = !empty($permissions['Create']) && (int)$permissions['Create'] === 1;
            $canWrite  = !empty($permissions['Write'])  && (int)$permissions['Write']  === 1;
            $canDelete = !empty($permissions['Delete']) && (int)$permissions['Delete'] === 1;

            if ($canRead || $canWrite || $canDelete) {
                $visibleSubMenus[] = $sub;
            }
        }
    }

    if (count($visibleSubMenus) > 0) {
        ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                        data-bs-auto-close="outside" role="button" aria-expanded="false" style="color:white;">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block" style="color:white;">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                                <path d="M12 12l8 -4.5" />
                                                <path d="M12 12l0 9" />
                                                <path d="M12 12l-8 -4.5" />
                                                <path d="M16 5.25l-8 4.5" />
                                            </svg>
                                        </span>
                                        <span
                                            class="nav-link-title" style="color:white;"><?php echo htmlspecialchars($menu['MenuName']); ?></span>
                                    </a>

                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-columns">
                                            <div class="dropdown-menu-column">
                                                <?php
                        foreach ($visibleSubMenus as $sub) {
                            $route = $sub['RouteName'] ?? '#'; // Replace with actual route URL
                            $subName = $sub['SubMenuName'] ?? '';
                            echo '<a class="dropdown-item" href="' . htmlspecialchars($route) . '">' . htmlspecialchars($subName) . '</a>';
                        }
                        ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
    }
}
?>
                            </ul>


                        </ul>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                <span class="avatar avatar-sm"
                                    style="background-image:url(<?=$BasURL;?>/Images/Staff/<?=$ImagePath;?>)">

                                </span>
                                <div class="d-none d-xl-block ps-2">
                                    <div style="color:white;"><?=$Emp_Name;?></div>
                                    <div class="mt-1 small " style="color:white;"><?=$Emp_Designation;?></div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </header>
        <div class="page-wrapper">
            <p id="ajax-loader"></p>