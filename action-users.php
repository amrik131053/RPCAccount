<?php session_start();
date_default_timezone_set("Asia/Kolkata");
include "provider.php";
$timeStamp=date('Y-m-d H-i');
$todaydate=date('Y-m-d');
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
$currentDateTime = date('Y-m-d H:i:s');
function interpolateQuery($query,$params) {
    $keys = [];
    $values = $params;

    // build a regular expression for each parameter
    foreach ($params as $key => $value) {
        // Check if named or positional parameter (for PDO positional is ?)
        // Here we assume positional '?'
        $keys[] = '/\?/';
    }

    // Replace each ? with the corresponding value (quoted if string)
    foreach ($values as $value) {
        $replacement = is_numeric($value) ? $value : "'".addslashes($value)."'";
        $query = preg_replace('/\?/', $replacement, $query, 1);
    }

    return $query;
}
   date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
          $CurrentExaminationGetDate=date('Y-m-d'); 
   $EmployeeID=$_SESSION['user_ac'];
   if ($EmployeeID==0 || $EmployeeID=='') 
      {?>
<script type="text/javascript">
window.location.href = "index.php";
</script>
<?php }
   include "connection/connection.php";

    $employee_details="SELECT AccountRoleId,IDNo,Name,Department,CollegeName,Designation,LeaveRecommendingAuthority,LeaveSanctionAuthority FROM Staff Where IDNo='$EmployeeID'";

      $employee_details_run=sqlsrv_query($conn,$employee_details);

      if ($employee_details_row=sqlsrv_fetch_array($employee_details_run,SQLSRV_FETCH_ASSOC)) {
         $Emp_Name=$employee_details_row['Name'];
         $Emp_Designation=$employee_details_row['Designation'];
         $Emp_CollegeName=$employee_details_row['CollegeName'];
         $Emp_Department=$employee_details_row['Department'];
          $role_id = $employee_details_row['AccountRoleId'];
        
        
      }
      
  $code = $_POST['code'];
    if ($code==1) {
    
    $id = $_POST['id'] ?? null;
if ($id) {
    $sql = "SELECT * FROM Staff 
            WHERE IDNo = ? and JobStatus=?";
    // Prepare statement
    $params = [$id,1];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
       
      $IDNo=$row['IDNo'];
       
    } else {
       
    }

    $sql = "SELECT CollegeID,Name, FatherName, MotherName,IDNo,ImagePath,JobStatus,Designation,AccountRoleId,EmailID,MobileNo
            FROM Staff
            WHERE IDNo = :idno";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idno', $IDNo, PDO::PARAM_STR);
    $stmt->execute();
    $student = $stmt->fetch();
    $AccountRoleId=$student['AccountRoleId'] ?? 0;
    if ($student):
        $imagePath = "$BasURL/Images/Staff/" . htmlspecialchars($student['ImagePath']);
    

        $sql1 = "SELECT * FROM RoleMaster
        WHERE Id = :idno";

$stmt1 = $pdo->prepare($sql1);
$stmt1->bindParam(':idno',$AccountRoleId, PDO::PARAM_STR);
$stmt1->execute();
$student1 = $stmt1->fetch();

$sql2 = "SELECT * FROM Usermaster 
            WHERE UserName = ? ";
    // Prepare statement
    $params1 = [$id];
    $stmt2 = sqlsrv_query($conn, $sql2, $params1) ?? false;

    if ($stmt2 === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
       $Account=$row2['Account'] ?? 0;
?>

<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 ">
                <img src="<?= $imagePath ?>" alt="Staff Photo" style="border-color:red !important; ">
            </div>

            <div class="col">
                <div class="card-body">
                    <input type="hidden" name="useid" id="useid" value="<?=htmlspecialchars($student['IDNo']) ?>">
                    <b><?= htmlspecialchars($student['Name']) ?>
                        (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                    <b>Designation:</b> <?= htmlspecialchars($student['Designation']) ?><br>
                    <?php if($Account==0)
                    {
                        ?>
                    <b>Role:</b> Not Active For Account<br>
                    <?php
                    }
                else{?>
                    <b>Role:</b> <?= htmlspecialchars($student1['RoleName'] ??'') ?><br>
                    <?php }?>
                    <?php if($student['JobStatus']>0 )
                    {
                        echo "<b class='text-success' style='font-size:16px'>(Active)</b>";}
                        else
                            {echo "<b class='text-danger' style='font-size:16px'>(Left)</b>";

                        }
                        ?>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <div class="list-group-item"><b>Email ID:</b> <?= htmlspecialchars($student['EmailID']) ?></div>
            <div class="list-group-item"><b>MobileNo:</b> <?= htmlspecialchars($student['MobileNo']) ?></div>

            <div class="list-group-item">
                <div class="mb-3">
                    <div class="form-label"><b>Role Name</b></div>
                    <select id="RoleName" name="RoleName" class="form-control">
                        <option value="">Select Role</option>
                        <?php
                                            $query = "SELECT Id, RoleName FROM RoleMaster ORDER BY Id ASC";
                                            $get_pending_run=sqlsrv_query($conn,$query);
                                            while($get_row=sqlsrv_fetch_array($get_pending_run))
                                            {
                                            ?>
                        <option value="<?=$get_row['Id'];?>">
                            <?=$get_row['RoleName'];?>(<?=$get_row['Id'];?>)</option>
                        <?php
                                            }?>

                    </select>

                </div>
                <div class="card-footer">

                    <button class="btn btn-success" style="float:right;" onclick="submitRole()">Submit</button>
                </div>



            </div>
        </div>

    </div>

</div>

<?php
    else:
        echo "<div class='alert alert-warning' role='alert'>Uh oh, No record found</div>";
    endif;
} else {
    echo "IDNo not provided.";
}
$pdo = null;

      }

      if($code == '2')
{

    $useid    = trim($_POST['useid']);
    $RoleName = trim($_POST['RoleName']);

    if($RoleName!=''){
    $updateSql = "
    UPDATE Staff SET AccountRoleId = ? WHERE IDNo = ?";
    $stmtUpdate = sqlsrv_query($conn,$updateSql,[$RoleName, $useid]);
    if($stmtUpdate === false)
    {
        print_r(sqlsrv_errors());
        exit;

    }
    }
    $checkUserSql = "
    SELECT TOP 1 UserName
    FROM UserMaster
    WHERE UserName = ?
    ";
    $checkStmt = sqlsrv_query(
        $conn,
        $checkUserSql,
        [$useid]
    );
    if($checkStmt === false)
    {
        print_r(sqlsrv_errors());
        exit;

    }
    if(sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC))
    {
        $updateStatusSql = "UPDATE UserMaster SET Account = 1 WHERE UserName = ?";

        $stmtStatus = sqlsrv_query(
            $conn,
            $updateStatusSql,
            [$useid]
        );
        if($stmtStatus === false)
        {
            print_r(sqlsrv_errors());
            exit;

        }

    }
    else
    {
        $hashPassword = password_hash($useid, PASSWORD_DEFAULT);
        $insertSql = "
        INSERT INTO UserMaster
        (
            UserName,
            [PasswordH],
            LoginType,
            ApplicationType,
            ApplicationName,
            Account
        )
        VALUES
        (
            ?,
            ?,
            'Staff',
            'Web',
            'Campus',
            1
        )
        ";
        $params = [
            $useid,
            $hashPassword
        ];
        $insertStmt = sqlsrv_query(
            $conn,
            $insertSql,
            $params
        );
        
        if($insertStmt === false)
        {
            // echo "<pre>";
            // print_r(sqlsrv_errors());
            // echo "</pre>";
            exit;
        }
    }
    echo 1;
}

elseif ($code == 3) {
    $roleId = isset($_POST['roleId']) ? intval($_POST['roleId']) : 0;
    $query = "
    SELECT 
        *,R.Id as RouteId
    FROM RouteMaster R

    LEFT JOIN SpecialPermission S
    ON S.PageId = R.Id
    AND S.EmpId = ?

    LEFT JOIN MainMenuAccounts M
    ON M.Id = R.MainMenuId

    ORDER BY M.OrderMenu, R.PageName
    ";
    $params = [$roleId];
    $stmt = sqlsrv_query($conn, $query, $params);
    if ($stmt === false) {
        echo "<tr><td colspan='4' class='text-center text-muted'>No data found</td></tr>";
        exit;
    }
    ?>
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
            $checkedRead  = $row['U']  ? "checked" : "";
            $checkedWrite = $row['I'] ? "checked" : "";
            $checkedDelete= $row['D']? "checked" : "";
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
elseif ($code == 4) {
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
        $check = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM SpecialPermission WHERE EmpId=? AND PageId=?", [$roleId, $routeId]);
        $row = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC);
        if ($row['cnt'] > 0) {
            $stmt = sqlsrv_prepare($conn, "UPDATE SpecialPermission SET U=?, I=?, D=? WHERE EmpId=? AND PageId=?", [$read, $write, $delete, $roleId, $routeId]);
        } else {
            $stmt = sqlsrv_prepare($conn, "INSERT INTO SpecialPermission (EmpId, PageId, U, I, D) VALUES (?, ?, ?, ?, ?)", [$roleId, $routeId, $read, $write, $delete]);
        }
        sqlsrv_execute($stmt);
    $updateSql = "
    UPDATE Staff SET AccountRoleId = ? WHERE IDNo = ?";
    $stmtUpdate = sqlsrv_query($conn,$updateSql,[0, $roleId]);
    if($stmtUpdate === false)
    {
        print_r(sqlsrv_errors());
        exit;

    }
    $checkUserSql = "
    SELECT TOP 1 UserName
    FROM UserMaster
    WHERE UserName = ?
    ";
    $checkStmt = sqlsrv_query(
        $conn,
        $checkUserSql,
        [$roleId]
    );
    if($checkStmt === false)
    {
        print_r(sqlsrv_errors());
        exit;

    }
    if(sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC))
    {
        $updateStatusSql = "UPDATE UserMaster SET Account = 1 WHERE UserName = ?";

        $stmtStatus = sqlsrv_query(
            $conn,
            $updateStatusSql,
            [$roleId]
        );
        if($stmtStatus === false)
        {
            print_r(sqlsrv_errors());
            exit;

        }

    }
    else
    {
        $hashPassword = password_hash($roleId, PASSWORD_DEFAULT);
        $insertSql = "
        INSERT INTO UserMaster
        (
            UserName,
            [PasswordH],
            LoginType,
            ApplicationType,
            ApplicationName,
            Account
        )
        VALUES
        (
            ?,
            ?,
            'Staff',
            'Web',
            'Campus',
            1
        )
        ";
        $params = [
            $roleId,
            $hashPassword
        ];
        $insertStmt = sqlsrv_query(
            $conn,
            $insertSql,
            $params
        );
        
        if($insertStmt === false)
        {
            // echo "<pre>";
            // print_r(sqlsrv_errors());
            // echo "</pre>";
            exit;
        }
    }
    }
    echo "1";
    exit;
}

    }

    ?>