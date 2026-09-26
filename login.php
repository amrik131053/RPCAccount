<?php
ob_clean();
session_start();
date_default_timezone_set("Asia/Kolkata");
include 'connection/connection.php';
include 'activity.php';
$status = 0;
$user = trim($_POST["user"] ?? '');
$pass = $_POST["pass"] ?? '';
 $sql1 = "SELECT UserMaster.PasswordH,Staff.ProfileLock FROM UserMaster INNER JOIN Staff ON UserMaster.UserName = Staff.IDNO
WHERE UserMaster.UserName = ? AND UserMaster.ApplicationType = 'Web' AND Staff.JobStatus = 1  AND Account='1'";
$params1 = array($user);
$stmt2 = sqlsrv_prepare($conn,$sql1,$params1);
if (!$stmt2 || !sqlsrv_execute($stmt2)) {
}
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    if(password_verify($pass, trim($row['PasswordH'])))
    {
        $status = 1;
        $lockProfile = $row['ProfileLock'];
    }
}
if ($status == 1 && $lockProfile != 1 ) {
    session_regenerate_id(true);
    $_SESSION['user_ac'] = $user;
    $_SESSION['user_ac_id']=$user;
        $updateactivity = "INSERT INTO UserActivity(IDNo,ActivityType,ActivityDescription,IPAddress,Broswer,DeviceType,CreatedAt)
          Values (?,?,?,?,?,?,?)";
    $paramslog = array($user,'Logged in','New Login',$ipAddress,$browserName,$deviceType,$timeStampS);
    sqlsrv_query($conn, $updateactivity,$paramslog);
    $updateLoggedIn = "UPDATE UserMaster SET LoggedIn = '0', ForceLogout='0' WHERE UserName = ?  AND ApplicationType = 'Web' AND ApplicationName = 'Campus'";
    $params2 = array($user);
    sqlsrv_query($conn, $updateLoggedIn, $params2);
        header('Location: Dashboard.php');
    }
    elseif ($lockProfile == 1 && $status == 1) {
        $_SESSION['incorrect'] = "<p style='color:red;'>Your profile has been temporarily blocked. Please contact IT Department.</p>";
        header('Location: index.php');
        exit();
    }
    else
    {
        $_SESSION['incorrect'] = "<p style='color:red;'>Incorrect Password. Try ERP Password.</p>";
        header('Location: index.php');
        exit();
    }
?>