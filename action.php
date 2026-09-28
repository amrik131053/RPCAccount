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

    $employee_details="SELECT RoleID,IDNo,Name,Department,CollegeName,Designation,LeaveRecommendingAuthority,LeaveSanctionAuthority FROM Staff Where IDNo='$EmployeeID'";

      $employee_details_run=sqlsrv_query($conn,$employee_details);

      if ($employee_details_row=sqlsrv_fetch_array($employee_details_run,SQLSRV_FETCH_ASSOC)) {
         $Emp_Name=$employee_details_row['Name'];
         $Emp_Designation=$employee_details_row['Designation'];
         $Emp_CollegeName=$employee_details_row['CollegeName'];
         $Emp_Department=$employee_details_row['Department'];
          $role_id = $employee_details_row['RoleID'];
        
        
      }
      
  $code = $_POST['code'];
//student status  for account status page
if ($code==1) {
    
    $id = $_POST['id'] ?? null;
if ($id) {
	$sql = "SELECT * FROM Admissions 
            WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
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

    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,Category,AdmissionType,
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

$session=$student['Session'];
$CollegeID=$student['CollegeID'];
$CourseID=$student['CourseID'];

    if ($student):
        $imagePath = "$BasURL/Images/Students/" . htmlspecialchars($student['Image']);
   

  $sql1 = "SELECT Duration, ValidUpTo 
         FROM MasterCourseCodes 
         WHERE Session = :session
           AND CollegeID = :college
           AND CourseID = :course";

$stmt1 = $pdo->prepare($sql1);

$stmt1->execute([
    ':session' => $session,
    ':college' => $CollegeID,
    ':course'  => $CourseID
]);

$duration = $stmt1->fetch(PDO::FETCH_ASSOC);



?>

<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 ">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color:red !important; ">
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
                    <?php if($student['Status']>0 )
                    {
                        echo "<b class='text-success' style='font-size:16px'>(Active) </b>"; }
                        else { echo "<b class='text-danger' style='font-size:16px'>(Left)</b>";

                        }    
                        ?> <?php if ($student['AdmissionType'] == '3'): ?>
    <span class="badge bg-warning text-dark">Migration</span>
<?php endif; ?>


 
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div>
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div>
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <div class="list-group-item"><b>Category:</b> <?= htmlspecialchars($student['Category']) ?></div>
            <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div>
            <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div>
           <div class="list-group-item">
    <b>Duration:</b> <?= htmlspecialchars($duration['Duration']) ?> Years </div>
    <div class="list-group-item">
    <?php  
    if (!empty($duration['ValidUpTo'])) {

        // If it's already a DateTime from sqlsrv/PDO, use it
        if ($duration['ValidUpTo'] instanceof DateTime) {
            echo " <b>ValidUpTo</b> : " . $duration['ValidUpTo']->format('d-m-Y');
        } else {
            // If it's a string, convert safely
            echo "   <b>  ValidUpTo</b>  : " . date('d-m-Y', strtotime($duration['ValidUpTo']));
        }
    }
    ?>
</div>
            

        </div>
    </div>

    <div class="card special-comment">
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment">
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
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
      
     else if ($code==1.1) {
    
    $id = $_POST['id'] ?? null;
if ($id) {
    $sql = "SELECT * FROM Admissions 
            WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
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

    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,AdmissionType,
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
    
?>

<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 ">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color:red !important; ">
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
                    <?php if($student['Status']>0 )
                    {
                        echo "<b class='text-success' style='font-size:16px'>(Active)</b>";}
                        else
                            {echo "<b class='text-danger' style='font-size:16px'>(Left)</b>";

                        }
                        ?><?php if ($student['AdmissionType'] == '3'): ?>
    <span class="badge bg-warning text-dark">Migration</span>
<?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!-- <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <!-- <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div> -->
            <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div>
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div>
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment">
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment">
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>
<div class="container">
    <div class="row col-lg-12">
        <div class="col-lg-6">
            <label class="form-label" style="color: brown">Head</label>
            <Select class="form-control " id='debithead' onchange="debitheadchnage()">
                <option value="">Select Head</option>
                <?php foreach ($heads as $head) { 
                  if($head['Head']!='Refund'){
                    ?>
                <option value="<?= $head['Id']; ?>"><?= $head['Head']; ?></option>
                <?php  }} ?>

            </Select>

            <label class="form-label" style="color: brown">Semester</label>
            <Select class="form-control " id="debitsemester">
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php } ?>

            </Select>

            <div id="route_box" style="display:none; margin-top:10px; margin-bottom:10px;">
    
        <label class="form-label" style="color: brown">Route</label>
       <select name="route_id" id="route_id" class="form-control"  onchange="loadSpot(this.value)"> 
                     <option value="">Select Route</option>
                    <?php

$query = "SELECT * FROM TBM_BusRootMaster WHERE IsActive = 1";
$stmt = $pdo->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
    <option value="<?= $row['BusRouteID']; ?>">
        <?= $row['RouteName']; ?>
    </option>
<?php } ?>
                  </select>
</div>


            <label class="form-label" style="color: brown">Credit (Amount)</label>
            <input type="number" class="form-control " Name="feedebit" id='debitfee'>
             <label class="form-label" style="color: brown">Receipt Date</label>
            <input type="date" class="form-control " Name="Receiptdate" id='receiptdate'>



<!-- Spot Select -->







        </div>
        <div class="col-lg-6">
            <div class="form-label" style="color: brown">Session</div>
            <Select class="form-select" id='debitsession'>

                <?php foreach ($accountsessions as $showsession) { ?>
                <option value="<?= $showsession['CurrentSession']; ?>">
                    <?= $showsession['CurrentSession']; ?>
                </option>
                <?php } ?>
            </Select>
            <label class="form-label" style="color: brown">On Account Of</label>
            <textarea class="form-control " rows='1' id='debitparticulars'></textarea>

            <div id="spot_box" style="display:none; margin-top:10px; margin-bottom:10px;">
  <label class="form-label" style="color: brown" >Spot</label>
    <select class="form-control" name="spot" name="spot_id" id="spot_id"  onclick="fetchtransportamount(this.value)">
        <option value="">Select Spot</option>
       
    </select>
</div>



            <label class="form-label" style="color: brown">Mode of Payment</label>
            <Select class="form-select" id='modeofpayment' onchange="modeofpaymnet(this.value)">
                
                <option value="Cash">Cash </option>
                <option value="Cheque">Cheque </option>
                <option value="Bank Transfer">Bank Transfer</option>
                 <option value="Receipt">Receipt</option>
            </Select>
        

           <label class="form-label" style="color: brown">Receipt Number</label>
            <input type="number" class="form-control " Name="receiptno" id='receiptno'>
            </div>
        <div class="container" id="modepaymnetdiv" style="display:none">

            <div class="row col-lg-12">

               

                <div class="col-lg-6">
                    <label class="form-label" style="color: brown">Name of Bank</label>
                    <Select class="form-select" id='nameofbank'>
                        <option value="">Select Bank </option>
                        <?php foreach ($AllBanks as $allBankshow) { ?>
                        <option value="<?= $allBankshow['BankName']; ?>">
                            <?= $allBankshow['BankName']; ?>
                        </option>
                        <?php } ?>
                    </Select>
                </div> <div class="col-lg-6"> <label class="form-label" style="color: brown">Transaction
                        Number</label>
                    <input type="text" class="form-control" id='transactionid'>
                    <label class="form-label" style="color: brown">Transaction Date </label>
                    <input type="date" class="form-control" id='transactiondate'>
                </div>
            </div>
        </div>

    </div>
    <?php if($student['Status']>0 ) 
                    {?>
                    
    <div class="d-flex align-items-center mb-2 mb-lg-0 mt-4" style="gap: 0.5rem;">
                <b style="color:red;">Auto Debit</b>
                <input type="checkbox" id="autodebit" style="width: 20px; height: 20px;">
            </div>
            <?php }?>

    <br>
    <div class="card-footer">

        <?php if($student['Status']>0 )
                    {
                        ?>


        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <!-- <button class="btn btn-primary btn-sm mb-2 mb-lg-0" id="feedebitbutton" onclick="CreateReceipt()">
                Create Entry
            </button> -->

            

            <button class="btn btn-primary btn-sm mb-2 mb-lg-0" id="feedebitbutton1" onclick="GenerateReceipt()">
                Generate Receipt
            </button>
        </div>

        <?php
                             }   
                        else
                            {
                                echo "<b class='text-danger' style='font-size:16px'>This candidate is left contact registration branch </b>";

                        }
                        ?>


    </div>
    <br>

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


    else  if ($code==1.2) {
    


$id = trim($_POST['id']) ?? null;

    include 'student-info.php';

   
    
?>

<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 ">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color:red !important; ">
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
                    <?php if($student['Status']>0 )
                    {
                        echo "<b class='text-success' style='font-size:16px'>(Active)</b>";}
                        else
                            {echo "<b class='text-danger' style='font-size:16px'>(Left)</b>";

                        }
                        ?>
                        <?php if ($student['AdmissionType'] == '3'): ?>
    <span class="badge bg-warning text-dark">Migration</span>
<?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!--   <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div>
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div>
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment"> 
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment">
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>
<div class="container">
    <div class="row gx-2 gy-2">
        <div class="col-6">
            <label class="form-label">Head</label>
            <select class="form-select" id="debithead" onchange="debitheadchnage();">
                <option value="">Select Head</option>
                <?php foreach ($heads as $head) { ?>
                <option value="<?= $head['Id']; ?>"><?= $head['Head']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-3">
            <label class="form-label">Session</label>
            <select class="form-select" id="debitsession">

                <?php foreach ($accountsessions as $showsession) { ?>
                <option value="<?= $showsession['CurrentSession']; ?>">
                    <?= $showsession['CurrentSession']; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-3">
            <label class="form-label">Semester</label>
            <select class="form-select" id="debitsemester">
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php } ?>
            </select>
        </div>


        <div class="col-6">
            <label class="form-label">Particular’s</label>
            <textarea class="form-control" rows="2" id="debitparticulars"></textarea>
        </div>

        <div class="col-6">
            <label class="form-label">Remarks</label>
            <textarea class="form-control" rows="2" id="debitremarks"></textarea>
        </div>

        <div class="col-6">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" id="debitdate">
        </div>
        <div class="col-6">
            <label class="form-label">Debit (Amount)</label>
            <input type="number" class="form-control" id="debitfee">
        </div>


    </div>

<br>
<label>
    <input type="checkbox" id="toggleCheck"  onchange="toggleButtons(this)">
    Concession Entry
  </label>

<br>
<br>

    <div class="card-footer" style="text-align: right;">

        <?php if($student['Status']>0 )
                    {
                        ?>
 


  <button class="btn btn-danger" id='concessionBtn' style="display:none;"  onclick="CreateFeeConcession()">Concession Fee</button> 

         <button class="btn btn-primary" id="debitBtn" onclick="CreateFeedebit()"> Fee Debit </button> 




        <?php
                             }   
                        else
                            {
                                echo "<b class='text-danger' style='font-size:16px'>This candidate is left contact registration branch </b>";

                        }
                        ?>


    </div>


</div>

<?php
 $pdo = null;

      }
         else  if ($code==1.3) {
    
    $id = $_POST['id'] ?? null;
if ($id) {
    $sql = "SELECT * FROM Admissions 
            WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
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

    $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,AdmissionType,
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
    
?>

<div class="student-card">
    <div class="card">
        <div class="row row-0 align-items-center">
            <div class="col-3 ">
                <img src="<?= $imagePath ?>" alt="Student Photo" style="border-color:red !important; ">
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
                    <?php if($student['Status']>0 )
                    {
                        echo "<b class='text-success' style='font-size:16px'>(Active)</b>";}
                        else
                            {echo "<b class='text-danger' style='font-size:16px'>(Left)</b>";

                        }
                        ?><?php if ($student['AdmissionType'] == '3'): ?>
    <span class="badge bg-warning text-dark">Migration</span>
<?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="list-group list-group-flush">
            <!--   <div class="list-group-item"><b>Mobile:</b> <?= htmlspecialchars($student['StudentMobileNo']) ?></div>
            <div class="list-group-item"><b>Email:</b> <?= htmlspecialchars($student['EmailID']) ?></div> -->
            <div class="list-group-item"><b>Father Name:</b> <?= htmlspecialchars($student['FatherName']) ?></div>
            <div class="list-group-item"><b>Mother Name:</b> <?= htmlspecialchars($student['MotherName']) ?></div>
            <div class="list-group-item"><b>Faculty:</b> <?= htmlspecialchars($student['CollegeName']) ?></div>
            <div class="list-group-item"><b>Programme:</b> <?= htmlspecialchars($student['Course']) ?></div>
            <div class="list-group-item"><b>Scholarship:</b> <?= htmlspecialchars($student['ScolarShip']) ?></div>
            <!-- <div class="list-group-item"><b>Location:</b> <?= htmlspecialchars($student['PermanentAddress']) ?></div> -->
        </div>
    </div>

    <div class="card special-comment">
        <label>Special Comment</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentsDetail']) ?></b>
        </div>
    </div>

    <div class="card account-comment">
        <label>Comments From Accounts</label>
        <div class="card-body">
            <b><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
        </div>
    </div>
</div>
<div class="container">
    <div class="row gx-2 gy-2">
        <div class="col-6">
            <label class="form-label">Head</label>
            <select class="form-select" id="debithead" onchange="debitheadchnage();">
                <option value="">Select Head</option>
                <?php foreach ($heads as $head) { ?>
                <option value="<?= $head['Id']; ?>"><?= $head['Head']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-3">
            <label class="form-label">Session</label>
            <select class="form-select" id="debitsession">

                <?php foreach ($accountsessions as $showsession) { ?>
                <option value="<?= $showsession['CurrentSession']; ?>">
                    <?= $showsession['CurrentSession']; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-3">
            <label class="form-label">Semester</label>
            <select class="form-select" id="debitsemester">
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php } ?>
            </select>
        </div>


        <div class="col-6">
            <label class="form-label">Particular’s</label>
            <textarea class="form-control" rows="2" id="debitparticulars"></textarea>
        </div>

        <div class="col-6">
            <label class="form-label">Remarks</label>
            <textarea class="form-control" rows="2" id="debitremarks"></textarea>
        </div>

        <div class="col-6">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" id="debitdate">
        </div>
        <div class="col-6">
            <label class="form-label">Debit (Amount)</label>
            <input type="number" class="form-control" id="debitfee">
        </div>


    </div>


    <br>

    <div class="card-footer">

        <?php if($student['Status']>0 )
                    {
                        ?>





        <button class="btn btn-primary" onclick="CreateFeeConcession()">Concession Fee</button>

         <!-- <button class="btn btn-primary" onclick="CreateFeedebit()"> Fee Debit </button>  -->




        <?php
                             }   
                        else
                            {
                                echo "<b class='text-danger' style='font-size:16px'>This candidate is left contact registration branch </b>";

                        }
                        ?>


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

 else if ($code == 2) 
      {
 $id = $_POST['id'] ?? null;
$IDNo='';
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
       
      $IDNo=$row['IDNo'];
       
    }

if($IDNo>0)
{
    $sql = "SELECT *  FROM Ledger t1
        WHERE t1.IDNo = ? AND NOT EXISTS (SELECT 1 FROM DeadDebits t2   WHERE t1.TransactionID = t2.TransactionID 
              AND t1.Debit = t2.Debit ) ORDER BY t1.DateEntry DESC";

    $params = [$IDNo];
    $stmtledger = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
   
    $ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}




$sql = "
    SELECT 
    SUM(Debit) AS totaldebit, SUM(Credit) AS totalcredit,SUM(Debit) - SUM(Credit) AS balance    FROM Ledger    WHERE IDNo = ?";

$params = [$IDNo];  
$stmt_balance= sqlsrv_query($conn, $sql, $params);

if ($stmt_balance === false) {
    die(print_r(sqlsrv_errors(), true));
}
$balances=[];
$row = sqlsrv_fetch_array($stmt_balance, SQLSRV_FETCH_ASSOC);
{
$balances[]=$row;
}


   

}

?>




<div class="card">
    <div class="card-header"
        style="position: sticky; top: 0; z-index: 10; background-color: #fff; border-bottom: 1px solid #ccc;">

        <h3 class="card-title">
            <div id="download">
                <form action="print_student_ledger.php" method="post" target="_blank">
                    <input type="hidden" name="IDNo" id="studentIdNo" value="<?=$IDNo;?>">
                    <button class="btn btn-primary " type="submit" id="downloadbutton">
                        Download</button>
                </form>
            </div>
        </h3>
        <div class="card-actions"
            style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

            <div id="balance" style="float:right">
                <?php      
     if (!empty($balances) && is_array($balances)) {
                     	
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
                <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
                <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
                <?php 

}
}?>
            </div>
            &nbsp;

        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
            <tr>
                <th>Receipt Date </th>
                <th>Bank Date</th>
                <th>Head</th>
                <th>Receipt No</th>
                <th style="max-width:200px">Particular</th>
                <th>Trasaction</th>
                <th>Semester</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Mode of Payment</th>
                <th>Remarks</th>
                <th class="w-1">Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">

            <?php
  
include 'ledger-status.php';

  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$credit=$ledger['Credit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
            <tr class="table">
                <td><?= htmlspecialchars($dateentry) ?></td>
                <td><?= htmlspecialchars($bankdate) ?></td>
                <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
                <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
                <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
                <td><?= htmlspecialchars($ledger['TransactionType']) ?></td>
                <td><?= htmlspecialchars($ledger['Semester']) . '-' . htmlspecialchars($ledger['SemesterID']) ?></td>
                <td><?= htmlspecialchars($ledger['Debit']) ?></td>
                <td><?= htmlspecialchars($ledger['Credit']) ?></td>
                <td><?= htmlspecialchars($ledger['ModeOfPayment']) ?></td>
                <td><?= htmlspecialchars($ledger['Remarks']) ?></td>
                <td>
                    <?php  if($credit!='')
  {?>
                    <button class="btn btn-primary btn-sm" target='_blank' id='rid'
                        onclick="print_receipt('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                        Print
                    </button>
                    <?php }


                    if($ledger['LedgerName']=='Refund')
                    {?><button class="btn btn-danger btn-sm" target='_blank' id='rid'
                        onclick="print_receipt_refund('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                        Print
                    </button>
                    <?php 

                    } ?>
                </td>
            </tr>
            <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



        </tbody>
    </table>




</div>
<?php



      }
else
	{?>

<div class="card">
    <div class="card-header">

        <h3 class="card-title">
            <div id="download">

            </div>
        </h3>
        <div class="card-actions">

            <div id="balance" style="float:right"></div>
            &nbsp;

        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="scroll-behavior: auto;height:700px">




        </div>
    </div>
</div>
<?php
            


} 
$pdo = null;


 }


 else if ($code == 2.1) 
      {
 $id = $_POST['id'] ?? null;
$IDNo='';
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
       
      $IDNo=$row['IDNo'];
       
    }

if($IDNo>0)
{
    $sql = "SELECT *  FROM Ledger t1
        WHERE t1.IDNo = ? AND NOT EXISTS (SELECT 1 FROM DeadDebits t2   WHERE t1.TransactionID = t2.TransactionID 
              AND t1.Debit = t2.Debit ) ORDER BY t1.TransactionID DESC";

    $params = [$IDNo];
    $stmtledger = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
   
    $ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}



$sql = "
    SELECT 
    SUM(Debit) AS totaldebit, SUM(Credit) AS totalcredit,SUM(Debit) - SUM(Credit) AS balance    FROM Ledger    WHERE IDNo = ?";

$params = [$IDNo];  
$stmt_balance= sqlsrv_query($conn, $sql, $params);

if ($stmt_balance === false) {
    die(print_r(sqlsrv_errors(), true));
}
$balances=[];
$row = sqlsrv_fetch_array($stmt_balance, SQLSRV_FETCH_ASSOC);
{
$balances[]=$row;
}

   

}
?>




<div class="card">
    <div class="card-header"
        style="position: sticky; top: 0; z-index: 10; background-color: #fff; border-bottom: 1px solid #ccc;">

        <h3 class="card-title">
            <div id="download">
                <form action="{{route('printledger')}}" method="post" target="_blank">

                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <input type="hidden" name="IDNo" id="studentIdNo">
                    <button class="btn btn-primary " type="submit" id="downloadbutton">




                        Download</button>
                </form>
            </div>
        </h3>
        <div class="card-actions"
            style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

            <div id="balance" style="float:right">
                <?php      
     if (!empty($balances) && is_array($balances)) {
                        
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
                <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
                <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
                <?php 

}
}?>
            </div>
            &nbsp;

        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
            <tr>
                <th>Receipt Date </th>
                <th>Bank Date</th>
                <th>Head</th>
                <th>Receipt No</th>
                <th style="max-width:200px">Particular</th>
                <th>Trasaction</th>
                <th>Semester</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Remarks</th>
                <th class="w-1">Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">

            <?php
  
include 'ledger-status.php';
  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$credit=$ledger['Credit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
            <tr>
                <td><?= htmlspecialchars($dateentry) ?></td>
                <td><?= htmlspecialchars($bankdate) ?></td>
                <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
                <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
                <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
                <td><?= htmlspecialchars($ledger['TransactionType']) ?></td>
                <td><?= htmlspecialchars($ledger['Semester']) . '-' . htmlspecialchars($ledger['SemesterID']) ?></td>
                <td><?= htmlspecialchars($ledger['Debit']) ?></td>
                <td><?= htmlspecialchars($ledger['Credit']) ?></td>
                <td><?= htmlspecialchars($ledger['Remarks']) ?></td>
                <td>
                    <?php  if($credit!='' )
  {?>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-report" Id='rid'
                        onclick="cancel_receipt('<?= $ledger['TransactionID'] ?>','<?= addslashes($ledger['SemesterID']) ?>','<?=  $ledger['LedgerName'];?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>','<?= $ledger['Credit'] ?>',`<?= $ledger['Particulars'] ;?>`)">

                Cancel Receipt
                    </button>


                    <?php }?>

                </td>
            </tr>
            <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



        </tbody>
    </table>




</div>
<?php



      }
else
    {?>

<div class="card">
    <div class="card-header">

        <h3 class="card-title">
            <div id="download">

            </div>
        </h3>
        <div class="card-actions">

            <div id="balance" style="float:right"></div>
            &nbsp;

        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="scroll-behavior: auto;height:700px">




        </div>
    </div>
</div>
<?php
            
$pdo = null;


} 
 }
 else if ($code == 2.3) 
      {
 $id = $_POST['id'] ?? null;
$IDNo='';
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
       
      $IDNo=$row['IDNo'];
       
    }

if($IDNo>0)
{
    $sql = "SELECT *  FROM Ledger t1
        WHERE t1.IDNo = ? AND NOT EXISTS (SELECT 1 FROM DeadDebits t2   WHERE t1.TransactionID = t2.TransactionID 
              AND t1.Debit = t2.Debit ) ORDER BY t1.TransactionID DESC";

    $params = [$IDNo];
    $stmtledger = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
   
    $ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}



$sql = "
    SELECT 
    SUM(Debit) AS totaldebit, SUM(Credit) AS totalcredit,SUM(Debit) - SUM(Credit) AS balance    FROM Ledger    WHERE IDNo = ?";

$params = [$IDNo];  
$stmt_balance= sqlsrv_query($conn, $sql, $params);

if ($stmt_balance === false) {
    die(print_r(sqlsrv_errors(), true));
}
$balances=[];
$row = sqlsrv_fetch_array($stmt_balance, SQLSRV_FETCH_ASSOC);
{
$balances[]=$row;
}


 
}
?>




<div class="card">
    <div class="card-header"
        style="position: sticky; top: 0; z-index: 10; background-color: #fff; border-bottom: 1px solid #ccc;">

        <h3 class="card-title">
            <div id="download">
                <form action="{{route('printledger')}}" method="post" target="_blank">

                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <input type="hidden" name="IDNo" id="studentIdNo">
                    <button class="btn btn-primary " type="submit" id="downloadbutton">




                        Download</button>
                </form>
            </div>
        </h3>
        <div class="card-actions"
            style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

            <div id="balance" style="float:right">
                <?php      
     if (!empty($balances) && is_array($balances)) {
                        
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
                <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
                <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
                <?php 

}
}?>
            </div>
            &nbsp;

        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
            <tr>
                <th>Receipt Date </th>
                <th>Bank Date</th>
                <th>Head</th>
                <th>Receipt No</th>
                <th style="max-width:200px">Particular</th>
                <th>Trasaction</th>
                <th>Semester</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Remarks</th>
                <th class="w-1">Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">

            <?php
  
include 'ledger-status.php';
  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$debit=$ledger['Debit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
            <tr>
                <td><?= htmlspecialchars($dateentry) ?></td>
                <td><?= htmlspecialchars($bankdate) ?></td>
                <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
                <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
                <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
                <td><?= htmlspecialchars($ledger['TransactionType']) ?></td>
                <td><?= htmlspecialchars($ledger['Semester']) . '-' . htmlspecialchars($ledger['SemesterID']) ?></td>
                <td><?= htmlspecialchars($ledger['Debit']) ?></td>
                <td><?= htmlspecialchars($ledger['Credit']) ?></td>
               
                <td><?= htmlspecialchars($ledger['Remarks']) ?></td>
                <td>
                    <?php  if($debit!='')
  {?>


                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-report" id="rid"
                        onclick="delete_debit('<?= $ledger['TransactionID'] ?>','<?= addslashes($ledger['SemesterID']) ?>','<?= $ledger['LedgerName'];?>','<?= $ledger['IDNo']; ?>','<?= $ledger['Session']; ?>','<?= $ledger['Debit']; ?>','<?= $ledger['Particulars']; ?>','<?= $ledger['LedgerID']; ?>')">
                        Delete Debit
                    </button>





                    <?php }?>
                </td>
            </tr>
            <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



        </tbody>
    </table>




</div>
<?php



      }
else
    {?>

<div class="card">
    <div class="card-header">

        <h3 class="card-title">
            <div id="download">

            </div>
        </h3>
        <div class="card-actions">

            <div id="balance" style="float:right"></div>
            &nbsp;

        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="scroll-behavior: auto;height:700px">




        </div>
    </div>
</div>
<?php
            


}
 }

else if ($code==3) {
    
    $id = $_POST['id'] ?? null;
if ($id) {
	$sql = "SELECT * FROM Admissions 
            WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
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
       
$status= htmlspecialchars($student['Status']) ;
if($status>0)
{
        $color = "#007bff"; 
}
else
{
	 $color = "#ff0000";
}
          
?>

<div class="student-card">
    <div class="col-lg-12">
        <div class="card">
            <div class="row row-0">
                <div class="col-3" style="text-align:center"><br>
                    <img src="<?= $imagePath ?>"
                        style="border-radius:50%;height:100px;width:100px;border:5px <?= $color ?> solid" />
                </div>
                <div class="col">
                    <div class="card-body">
                        <p>
                            <b><?= htmlspecialchars($student['StudentName']) ?>
                                (<?= htmlspecialchars($student['IDNo']) ?>)</b><br>
                            Uni Roll No : <?= htmlspecialchars($student['UniRollNo']) ?><br>
                            Class Roll No : <?= htmlspecialchars($student['ClassRollNo']) ?><br>
                            Batch : <?= htmlspecialchars($student['Batch']) ?>&nbsp;&nbsp;
                            LEET : <b><?= htmlspecialchars($student['LateralEntry']) ?></b><br>
                            Session : <?= htmlspecialchars($student['Session']) ?>
                        </p>
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
                                    <b class="text-reset d-block">Father Name:
                                        <?= htmlspecialchars($student['FatherName']) ?></b>
                                </div> | <div class="col text-truncate">
                                    <b class="text-reset d-block">Mother Name:
                                        <?= htmlspecialchars($student['MotherName']) ?></b>
                                </div>
                            </div>
                        </div>


                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <b class="text-reset d-block">Programme:
                                        <?= htmlspecialchars($student['Course']) ?></b>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <b class="text-reset d-block">Scholarship:
                                        <?= htmlspecialchars($student['ScolarShip']) ?></b>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="row row-0">
                            <div class="col">
                                <label style="background-color:red;color:white;border-radius:10%"><b>&nbsp;&nbsp;Special
                                        Comment&nbsp;&nbsp;</b></label>
                                <div class="card-body">
                                    <b class="col-12"
                                        style="color:blue"><?= htmlspecialchars($student['CommentsDetail']) ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-0">
                        <div class="col">
                            <label style="background-color:red;color:white"><b>&nbsp;&nbsp;Comments From
                                    Accounts&nbsp;&nbsp;</b></label>
                            <div class="card-body">
                                <b class="col-12"
                                    style="color:blue"><?= htmlspecialchars($student['CommentFromAcc']) ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="card m-2">
                        <div class="card-body">
                            <label class="bg-danger text-white p-1"><strong>New Comments</strong></label>
                            <textarea class="form-control mt-2" name="accountComments" id="accountComments"
                                rows="3"></textarea>
                        </div>
                    </div>


                    <div class="card-footer text-end">
                        <?php if($status>0){?>
                        <button type="submit" class="btn btn-success"
                            onclick="submitaccountComents(<?=$student['IDNo'];?>);">Add
                            Comment </button><?php } else{ ?><button class="btn btn-danger">Left</button><?php }?>
                    </div>
                    <?php if($EmployeeID=='131053')
 {?>
                    <div class="card m-2">
                        <div class="card-body">
                            <textarea class="form-control mt-2" name="accountCommentsadminold"
                                id="accountCommentsadminold" rows="3"
                                hidden><?= htmlspecialchars($student['CommentFromAcc']) ?></textarea>
                            <label class="bg-primary text-white p-1"><strong>Update Comments </strong></label>
                            <textarea class="form-control mt-2" name="accountCommentsadmin" id="accountCommentsadmin"
                                rows="3"><?= htmlspecialchars($student['CommentFromAcc']) ?></textarea>
                        </div>
                    </div>


                    <div class="card-footer text-end">

                        <button type="submit" class="btn btn-success"
                            onclick="submitaccountComentsadmin(<?=$student['IDNo'];?>);">Update Comment</button>
                    </div>
                    <?php }?>
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


      }

      else if ($code==4) {



$faculityid = $_POST['id'] ?? null;
$session    = $_POST['session'] ?? null;

// Base query
$sql = "SELECT DISTINCT Course, CourseID, Duration 
        FROM MasterCourseCodes
        WHERE 1=1"; // Always true — makes appending optional filters easy

$params = [];

// Add optional filters dynamically
if (!empty($faculityid)) {
    $sql .= " AND CollegeID = ?";
    $params[] = $faculityid;
}

if (!empty($session)) {
    $sql .= " AND Session = ?";
    $params[] = $session;
}

$sql .= " ORDER BY Course ASC";

// Run the query safely
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Generate dropdown options
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    ?>
<option value="<?= htmlspecialchars($row['CourseID']); ?>">
    <?= htmlspecialchars($row['Course']); ?>(<?= htmlspecialchars($row['CourseID']); ?>) -
    <?= htmlspecialchars($row['Duration']); ?>Years
</option>
<?php
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);


}
//Account Comment 

    else if ($code==5) {

$newComment = $_POST['accountComments'] ?? '';
$idno       = $_POST['id'] ?? '';


$updateSql = "
    UPDATE Admissions
    SET CommentFromAcc = 
        CASE 
            WHEN CommentFromAcc IS NULL THEN ?
            ELSE CommentFromAcc + ' , ' + ?
        END
    WHERE IDNo = ?";

$updateParams = [$newComment,$newComment,$idno];
$updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

if ($updateStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_rows_affected($updateStmt) > 0) {

   
    // Insert into logbook
    $logbookSql = "
        INSERT INTO logbook (userid, remarks, updatedby, date)
        VALUES (?, ?, ?, ?)";
    $logbookParams = [$idno, $newComment, $EmployeeID, $currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);

    if ($logbookStmt && sqlsrv_rows_affected($logbookStmt) > 0) {
        echo '1';
    } else {
        echo '0';
    }

} else {
  echo  '2';
}
sqlsrv_free_stmt($updateStmt);
if (isset($logbookStmt)) sqlsrv_free_stmt($logbookStmt);
sqlsrv_close($conn);

}
//Account Comment Admin 

 else if ($code==6) {

$newComment = $_POST['accountComments'] ?? '';

$accountCommentsold = $_POST['accountCommentsold'] ?? '';
$idno       = $_POST['id'] ?? '';
$currentDateTime = date('Y-m-d H:i:s');

$updateSql = "
    UPDATE Admissions
    SET CommentFromAcc =  ?
        
    WHERE IDNo = ?";

$updateParams = [$newComment,$idno];
$updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

if ($updateStmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


$commentlof='Old Comment-'.$accountCommentsold.'-New Comment-'.$newComment;
if (sqlsrv_rows_affected($updateStmt) > 0) {

   
    // Insert into logbook
    $logbookSql = "
        INSERT INTO logbook (userid, remarks, updatedby, date)
        VALUES (?, ?, ?, ?)";
    $logbookParams = [$idno, $commentlof, $EmployeeID, $currentDateTime];



    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);

    if ($logbookStmt && sqlsrv_rows_affected($logbookStmt) > 0) {
        echo '1';
    } else {
        echo '0';
    }

} else {
  echo  '2';
}
sqlsrv_free_stmt($updateStmt);
if (isset($logbookStmt)) sqlsrv_free_stmt($logbookStmt);
sqlsrv_close($conn);

}
   


 else if ($code==7) {


// --- Inputs ---
$session     = $_POST['feesession'] ?? null;
$faculityid  = $_POST['CollegeID'] ?? null;
$programid   = $_POST['ProgramDropdown'] ?? null;
$lateral     = $_POST['lateralentry']?? null;

      


// --- Build SQL query ---
$sql = "
    SELECT maf.*, fcn.FeeCategory AS FeeCategoryName
    FROM MasterAnnualFee AS maf
    INNER JOIN FeeCategoryNew AS fcn ON maf.FeeCategory = fcn.ID
    WHERE maf.Status = '1'
      AND maf.Session = ?
      AND maf.CollegeID = ?
      AND maf.CourseID = ?
";

$params = [$session, $faculityid, $programid];


if (!empty($lateral)){
     $sql .= " AND maf.LateralEntry = ?";
    $params[] = $lateral;  
}
else
{

}


$sql .= " ORDER BY maf.SemesterID ASC";



// --- Execute query ---
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feedetailData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Render HTML table ---
echo '<table class="table table-bordered">';
echo '<thead>
        <tr>
            <th>Semester</th>
            <th>Session</th>
            <th>Batch</th>
            <th>Head</th>
            <th>Fee Category</th>
            <th>Amount</th>
            <th>LEET</th>
            <th colspan="2">Action</th>
        </tr>
      </thead>';

echo '<tbody>';

if (!empty($feedetailData)) {
    foreach ($feedetailData as $item) {
        $semester     = htmlspecialchars($item['Semester']);
        $semesterID   = htmlspecialchars($item['SemesterID']);
        $session      = htmlspecialchars($item['Session']);
        $batch        = htmlspecialchars($item['Batch']);
        $head         = htmlspecialchars($item['Head']);
        $feeCatName   = htmlspecialchars($item['FeeCategoryName']);
        $feeCategory  = htmlspecialchars($item['FeeCategory']);
        $amount       = htmlspecialchars($item['Amount']);
        $lateralEntry = htmlspecialchars($item['LateralEntry']);
        $srNo         = htmlspecialchars($item['SrNo']);

        echo "<tr>
                <td>{$semester}-{$semesterID}</td>
                <td>{$session}</td>
                <td>{$batch}</td>
                <td>{$head}</td>
                <td>{$feeCatName} ({$feeCategory})</td>
                <td>{$amount}</td>
                <td>{$lateralEntry}</td>
                <td>
                    <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modal-report' onclick='editannualfee({$srNo})'>
                        Edit
                    </button>
                </td>
                <td>
                    <button onclick='deleteannualfee({$srNo})' class='btn btn-danger btn-sm'>
                        <i class='fa fa-trash'></i> Delete
                    </button>
                </td>
              </tr>";
    }
} else {
    echo '<tr><td colspan="9" class="text-center">No records found</td></tr>';
}

echo '</tbody>';
echo '</table>';



}
else if($code==8)
{

$srno = $_POST['srno'] ?? null;
if (!$srno) {
    echo "<div class='alert alert-danger'>SrNo not provided.</div>";
    exit;
}

 $sql = "SELECT maf.*, fcn.FeeCategory AS FeeCategoryName
        FROM MasterAnnualFee AS maf
        INNER JOIN FeeCategoryNew AS fcn ON maf.FeeCategory = fcn.ID
        WHERE maf.SrNo = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$srno]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<div class='alert alert-warning'>Record not found.</div>";
    exit;
}

// Sanitize values for output
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES);
}
?>

<!-- Modal HTML with data filled in -->
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Update Annual Fee (<label id="annualfeesessionedit"><?= e($data['Session']) ?></label>)
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Faculty Name</label>
            <input type="hidden" class="form-control" id="annualfeesrnoedit" value="<?= e($data['SrNo']) ?>" readonly>
            <input type="text" class="form-control" id="annualfeefacultytedit" value="<?= e($data['CollegeName']) ?>"
                readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Programme Name</label>
            <input type="text" class="form-control" id="annualfeeprogrammeedit" value="<?= e($data['Course']) ?>"
                readonly>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label">Head</label>
                    <input type="text" class="form-control" id="annualfeeheadedit" value="<?= e($data['Head']) ?>"
                        readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label">Fee Category</label>
                    <input type="text" class="form-control" id="annualfeenationalityedit"
                        value="<?= e($data['FeeCategoryName']) ?>" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label">Lateral Entry</label>
                    <input type="text" class="form-control" id="annualfeelateraledit"
                        value="<?= e($data['LateralEntry']) ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Actual Fee</label>
                    <input type="text" class="form-control" id="annualfeeactualamountedit"
                        value="<?= e($data['ActualFee'] ?? $data['Amount']) ?>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">Payable Fee</label>
                    <input type="text" class="form-control" id="annualfeeamountedit" value="<?= e($data['Amount']) ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
        <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal"
            onclick="UpdateAnnualFee(<?= e($data['SrNo']) ?>);">Update</a>
    </div>
</div>

<?php 
}

else if($code=='9')
{

$srno      = $_POST['srno'] ?? null;
$amount    = $_POST['amount'] ?? null;
$actualFee = $_POST['actualFee'] ?? null;


if (!$srno || !$amount || !$actualFee) {
    echo "0";
    exit;
}

try {
    $sql = "UPDATE MasterAnnualFee SET Amount = ?, ActualFee = ? WHERE SrNo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $actualFee, $srno]);

    if ($stmt->rowCount() > 0) {
        // Log update
        $remarks = "Update MasterAnnualFee SET Amount='$amount', ActualFee='$actualFee' WHERE SrNo='$srno'";
        
        $sql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$srno, $remarks, $EmployeeID,$currentDateTime]);

        echo "1";
     
    } else {
        echo "2";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}





}

else if($code==10)
{

$srno = $_POST['srno'] ?? null;

if (!$srno) {
    echo "0";
    exit;
}

try {
    // Soft delete by setting Status=0
    $sql = "UPDATE MasterAnnualFee SET Status = '0' WHERE SrNo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$srno]);

    if ($stmt->rowCount() > 0) {
        // Log the delete action
        $remarks = "UPDATE MasterAnnualFee SET Status = '0' WHERE SrNo = '$srno'";
       $sql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$srno, $remarks, $EmployeeID,$currentDateTime]);
        echo "1";
        // Or redirect: header("Location: somepage.php?msg=deleted"); exit;
    } else {
        echo "2";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

}
else if($code==11)
{
$headid      = $_POST['annualfeehead'] ?? null;
$amount      = $_POST['annualfeeamount'] ?? null;
$feecategory = $_POST['annualfeecategory'] ?? null;
$session     = $_POST['feesession'] ?? null;
$collegeid   = $_POST['CollegeID'] ?? null;
$courseid    = $_POST['ProgramDropdown'] ?? null;
$lateral     = $_POST['lateralentry'] ?? null;
$semesterid  = $_POST['annualfeesem'] ?? null;
$actualfees  = $_POST['annualfeeapplicableamount'] ?? null;


if ($lateral == 0 || $lateral === null) {

    echo "0";
    exit;
}

try {
    // Fetch college course data
    $stmt = $pdo->prepare("SELECT CollegeName, Course, Batch FROM MasterCourseCodes WHERE CollegeID = ? AND CourseID = ? AND Session = ? ANd LateralEntry='No'");

    $stmt->execute([$collegeid, $courseid, $session]);
    $collegeCourseName = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$collegeCourseName) {
        echo "1";
        exit;
    }
    $course = $collegeCourseName['Course'];
    $CollegeName = $collegeCourseName['CollegeName'];

    // Fetch head name
    $stmt = $pdo->prepare("SELECT Head FROM MasterHeadNew WHERE Status = '1' AND Id = ?");

    $stmt->execute([$headid]);

    $headName = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$headName) {
        echo "2";
        exit;
    }
    $headName=$headName['Head'];

    // Calculate batch
    if ($lateral === "Yes") {
        $batch = intval($collegeCourseName['Batch']) - 1;
    } else {
        $batch = intval($collegeCourseName['Batch']);
    }

    

    // Check if record already exists
    $stmt = $pdo->prepare("SELECT 1 FROM MasterAnnualFee WHERE Batch = ? AND Session = ? AND CollegeID = ? AND CourseID = ? AND LateralEntry = ? AND SemesterID = ? AND HeadID = ? AND FeeCategory = ? AND Status = '1'");
    $stmt->execute([$batch, $session, $collegeid, $courseid, $lateral, $semesterid, $headid, $feecategory]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        echo "3";
        exit;
    }

    // Insert new record
   $sql = "INSERT INTO MasterAnnualFee 
    (CollegeName, Course, Batch, Semester, HeadID, Head, Amount, FeeCategory, Session, CollegeID, CourseID, LateralEntry, SemesterID, Createdby, CreatedDate, ActualFee, Status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $CollegeName,
    $course,
    $batch,
    $semesterid,
    $headid,
    $headName,
    $amount,
    $feecategory,
    $session,
    $collegeid,
    $courseid,
    $lateral,
    $semesterid,
    $EmployeeID,
    $currentDateTime,
    $actualfees,
    1
];

$stmt = $pdo->prepare($sql);
$success = $stmt->execute($params);

if ($success) {
    echo "4";
} 
else {
    echo "5";
}


} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}


}

else if ($code==12) {

$sql = "SELECT * FROM FeeCategoryNew";
$stmt = sqlsrv_query($conn, $sql);
$masterFeeCategory = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $masterFeeCategory[] = $row;
    }
    sqlsrv_free_stmt($stmt);
}

echo '<table id="feecategoryfeeTable" class="table table-bordered">';
echo '<thead>
        <tr>
            <th>Name</th>
            <th>Action</th>
        </tr>
      </thead>';
echo '<tbody>';

if (!empty($masterFeeCategory)) 
{
    foreach ($masterFeeCategory as $item) {
        $checked = ($item['Status'] == 1) ? 'checked' : '';
        // When checked (Status=1), value is 0 (to toggle off), else 1 (to toggle on)
        $value = ($item['Status'] == 1) ? 0 : 1;

        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['FeeCategory']) . ' (' . intval($item['ID']) . ')</td>';
        echo '<td>
                <div>
                  <label class="row">
                    <span class="col-auto">
                      <label class="form-check form-check-single form-switch">
                        <input class="form-check-input" type="checkbox" value="' . $value . '" ' . $checked . ' onclick="editfeecategory(' . intval($item['ID']) . ', ' . $value . ')">
                      </label>
                    </span>
                  </label>
                </div>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="2" class="text-center">No records found</td></tr>';
}

echo '</tbody></table>';


   
}

else if ($code==13) {

$newFeeCategoryId = $_POST['ID'];
$status = $_POST['Status'];

try {

  $sql = "UPDATE FeeCategoryNew SET Status = :status WHERE ID = :id";

    $sql = "UPDATE FeeCategoryNew SET Status = :status WHERE ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $newFeeCategoryId, PDO::PARAM_INT);
    $stmt->execute();



    if ($stmt->rowCount() > 0) {

         $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date)
        VALUES (?, ?, ?, ?)";
    $logbookParams = [$newFeeCategoryId,"Fee Category Status Changed", $EmployeeID, $currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);

    

        echo "0";
    } else {
        echo "1";
    }
} 
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}
else if($code==14)
{

$newFeeCategory = $_POST['newfeecategory'];  // or however you get this input

try {
    $sql = "INSERT INTO FeeCategoryNew (FeeCategory,Status) VALUES (:feeCategory, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':feeCategory', $newFeeCategory, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

         $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby,pagename, date)
        VALUES (?, ?, ?, ?,?)";
    $logbookParams = [$EmployeeID,"New Fee Category Added $newFeeCategory", $EmployeeID,"master-fee-category.php" ,$currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);

        echo "1";
    } else {
        echo "0";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


}

elseif($code=='15')
{

try {
    $sql = "SELECT * FROM MasterHeadNew ORDER BY Head ASC";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Start building the table
    echo '<table id="feecategoryfeeTable" class="table table-bordered">';
    echo '<thead>
            <tr>
              <th>Name</th>
              <th>Action</th>
              <th>For Student</th>
            </tr>
          </thead>';

    echo '<tbody>';
    if (!empty($rows)) {
        foreach ($rows as $item) {
            // Determine checkbox state and value
            $checkBox = ($item['Status'] == 1) ? 'checked' : '';
            $value = ($item['Status'] == 1) ? 0 : 1;

            $checkBox1 = ($item['StudentStatus'] == 1) ? 'checked' : '';
            $value1 = ($item['StudentStatus'] == 1) ? 0 : 1;

            echo '<tr>';
     echo "<td>" . htmlspecialchars($item['Head']) . "({$item['Id']})</td>";


            echo '<td>
                    <div>
                      <label class="row">
                        <span class="col-auto">
                          <label class="form-check form-check-single form-switch">
                            <input class="form-check-input" type="checkbox" value="' . $value . '" ' . $checkBox . ' onclick="editmasterhead(' . intval($item['Id']) . ', ' . $value . ')">
                          </label>
                        </span>
                      </label>
                    </div>
                  </td>
                  <td>
                    <div>
                      <label class="row">
                        <span class="col-auto">
                          <label class="form-check form-check-single form-switch">
                            <input class="form-check-input" type="checkbox" value="' . $value1 . '" ' . $checkBox1 . ' onclick="editmasterheadstudent(' . intval($item['Id']) . ', ' . $value1 . ')">
                          </label>
                        </span>
                      </label>
                    </div>
                  </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="2" class="text-center">No records found</td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
} catch (PDOException $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}



}

elseif($code==16)
{
try {
    $status = $_POST['status'];  // or wherever you get it from
    $headid = $_POST['ID'];

    $sql = "UPDATE MasterHeadNew SET Status = :status WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $headid, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

           $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby,pagename, date)
        VALUES (?, ?, ?, ?,?)";
    $logbookParams = [$headid," Fee Head  Updated ", $EmployeeID,"master-head.php" ,$currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
        echo "0";
    } else {
        echo "No rows updated";
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}

}
elseif($code==17)
{
try {
    $status = $_POST['status'];  // or wherever you get it from
    $headid = $_POST['ID'];

    $sql = "UPDATE MasterHeadNew SET StudentStatus = :status WHERE Id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $headid, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
         $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby,pagename, date)
        VALUES (?, ?, ?, ?,?)";
    $logbookParams = [$headid," Fee Head  Updated for student ", $EmployeeID,"master-head.php" ,$currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
        echo "0";
    } else {
        echo "No rows updated";
    }
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}

}
else if($code==18)
{


try {

    $newhead = $_POST['headName'] ?? '';

    if (empty($newhead)) {
        echo "0";
        exit;
    }

    $sql = "INSERT INTO MasterHeadNew (Head, Status,StudentStatus) VALUES (:head, 1,0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':head', $newhead, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

         $logbookSql = "INSERT INTO logbook (userid, remarks, updatedby,pagename, date)
        VALUES (?, ?, ?, ?,?)";
    $logbookParams = [$EmployeeID," Fee Head Added $newhead", $EmployeeID,"master-head.php" ,$currentDateTime];

    $logbookStmt = sqlsrv_query($conn, $logbookSql, $logbookParams);
        echo "1";
    } else {
        echo "2";
    }
}
 catch (PDOException $e) {
    echo "Unable to load: " . htmlspecialchars($e->getMessage());
}

}
else if($code==19)
{

try {
   
     $startdate = $_POST['startdate'] ?? null;
    $enddate = $_POST['enddate'] ?? null;
    $status = $_POST['newstatus'] ?? null;

 
        $givenStartDate = date('Y-m-d 00:01:00', strtotime($startdate));
        $givenEndDate = date('Y-m-d 23:59:00', strtotime($enddate));

        // // Build SQL query
        $sql = "SELECT * FROM DeadDebits 
                WHERE  CreatedBy = :userid  AND  DateEntry BETWEEN  :startdate AND :enddate";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userID, PDO::PARAM_STR);
        $stmt->bindParam(':startdate', $givenStartDate);
        $stmt->bindParam(':enddate', $givenEndDate);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



    echo '<table id="annualfeeTable" class="table table-bordered">';
    echo '<thead>
            <tr>
              <th>Sr No</th>
              <th>Receipt / Entry Date</th>
              <th>TransactionID</th>
              <th>IDNo</th>
              <th>Student Name</th>
              <th>Father Name</th>
              <th>Head</th>
              <th>Particular</th>
              <th>Comments</th>
              <th>Semester</th>
              <th>Credit</th>
              <th>Status</th>
              <th class="w-1">Action</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (!empty($rows)) {
        $srno = 1;

        foreach ($rows as $item) {
            // Determine row color and status label
            $color = '';
            $statusLabel = '';

            switch ($item['ApprovedDebitStatus']) {
                case '0':
                    $statusLabel = 'Pending to Verify';
                    $color = '#92f5e9';
                    break;
                case '1':
                    $statusLabel = 'Pending to Approve';
                    $color = '#f5f292';
                    break;
                case '2':
                    $statusLabel = 'Approved';
                    $color = '#bcf592';
                    break;
                case '-1':
                    $statusLabel = 'Deleted';
                    $color = 'red';
                    break;
                default:
                    $statusLabel = '';
            }

            // Format dates safely
            $receiptDate = !empty($item['DateEntry']) ? date('d-m-Y', strtotime($item['DateEntry'])) : '';
            $transactionID = htmlspecialchars($item['TransactionID']);
            $idNo = htmlspecialchars($item['IDNo']);
            $studentName = htmlspecialchars($item['StudentName']);
            $fatherName = htmlspecialchars($item['FatherName']);
            $debitHead = htmlspecialchars($item['DebitHead']);
            $particulars = htmlspecialchars($item['Particulars']);
            $comments = htmlspecialchars($item['Comments']);
            $semester = htmlspecialchars($item['SemesterID']);
            $debit = htmlspecialchars($item['Debit']);
            $session = htmlspecialchars($item['Session']);

            // Only allow delete for specific statuses
            $deleteButton = '';
            if ($item['DebitStatus'] < 2 && $item['DebitStatus'] >= 0) {
                $deleteButton = '<button class="btn btn-danger btn-sm" 
                                    onclick="delete_debitentry(\'' . $transactionID . '\', 
                                                                \'' . $semester . '\', 
                                                                \'' . $debitHead . '\', 
                                                                ' . intval($idNo) . ', 
                                                                \'' . $session . '\', 
                                                                \'' . $debit . '\')">
                                    Delete
                                 </button>';
            }

            echo '<tr style="background-color:' . $color . '">';
            echo '<td>' . $srno++ . '</td>';
            echo '<td>' . $receiptDate . '</td>';
            echo '<td>' . $transactionID . '</td>';
            echo '<td>' . $idNo . '</td>';
            echo '<td>' . $studentName . '</td>';
            echo '<td>' . $fatherName . '</td>';
            echo '<td>' . $debitHead . '</td>';
            echo '<td>' . $particulars . '</td>';
            echo '<td>' . $comments . '</td>';
            echo '<td>' . $semester . '</td>';
            echo '<td>' . $debit . '</td>';
            echo '<td>' . $statusLabel . '</td>';
            echo '<td>' . $deleteButton . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="13" class="text-center">No records found</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
} catch (PDOException $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}





}
else if($code==20)
    {
 $transactionId = $_POST['TransactionID'] ?? null;
$debitHead = $_POST['LedgerName'] ?? null;
$session = $_POST['Session'] ?? null;
$semesterId = $_POST['SemesterID'] ?? null;
$debitAmount = $_POST['Debit'] ?? null;
$id = $_POST['IDNo'] ?? null;
$particulars = $_POST['Remarks'] ?? null;
$comments = $_POST['Comment'] ?? null;
$LedgerID = $_POST['LedgerID'] ?? null;


try {
    
    
    $stmt = $pdo->prepare("SELECT IDNo, CollegeName, StudentName, FatherName, Course FROM Admissions WHERE IDNo = ?");
    $stmt->execute([$id]);
    $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$studentData) {
        echo "Student data not found";
        exit;
    }

    $smallDateTime = date('Y-m-d H:i:s');

    // Insert into DeadDebits table
     $sql = "INSERT INTO DeadDebits 
            (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Debit, TransactionID, Comments, DebitStatus, DebitHead, Session, SemesterID, CreatedBy, CreatedDate,LedgerID)
            VALUES 
            (:DateEntry, :CollegeName, :IDNo, :StudentName, :Course, :FatherName, :Particulars, :Debit, :TransactionID, :Comments, '0', :DebitHead, :Session, :SemesterID, :CreatedBy, :CreatedDate,:LedgerID)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':DateEntry' => $smallDateTime,
        ':CollegeName' => $studentData['CollegeName'],
        ':IDNo' => $studentData['IDNo'],
        ':StudentName' => $studentData['StudentName'],
        ':Course' => $studentData['Course'],
        ':FatherName' => $studentData['FatherName'],
        ':Particulars' => $particulars,
        ':Debit' => $debitAmount,
        ':TransactionID' => $transactionId,
        ':Comments' => $comments,
        ':DebitHead' => $debitHead,
        ':Session' => $session,
        ':SemesterID' => $semesterId,
        ':CreatedBy' => $EmployeeID,
        ':CreatedDate' => $smallDateTime,
        ':LedgerID' => $LedgerID,
    ]);

    if ($stmt->rowCount() > 0) {
        echo "0";
    } else {
        echo "0";
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}

    }

    else if($code == 21) {
   
        $sql = "SELECT * FROM DeadDebits WHERE DebitStatus = 0 ORDER BY DateEntry DESC";
       // $sql = "SELECT * FROM DeadDebits WHERE DebitStatus = 0   OR DebitStatus IS NULL ORDER BY DateEntry DESC";

        $stmt = sqlsrv_query($conn, $sql);

        if(!$stmt) {
            throw new Exception(print_r(sqlsrv_errors(), true));
        }
        $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
        $html .= '<thead>
                    <tr>
                      
                        <th>Cancel Receipt Date</th>
                        <th>Particular</th>
                        <th>IDNo</th>
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
                        <td>'.$row['IDNo'].'</td>
                        <td>'.$row['StudentName'].'</td>
                        <td>'.$row['FatherName'].'</td>
                        <td>'.$row['SemesterID'].'</td>
                        <td>'.$row['Debit'].'</td>
                        <td>'.$row['Comments'].'</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" 
                                onclick="showStudentDetailsForCencelReceipts(
                                    '.$row['IDNo'].',
                                    '.$row['Debit'].',
                                    \''.addslashes($row['Comments']).'\',
                                    '.$row['SemesterID'].',
                                    '.$row['TransactionID'].',
                                    \''.addslashes($row['Particulars']).'\',
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
$sql = "SELECT *
FROM DeadDebits
WHERE (DebitStatus = 0 OR DebitStatus IS NULL)
  AND IDNo = ?;
";
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
    $LedgerName = $row['Particulars'];
    $Debit = $row['Debit'];
    $comments = $row['Comments'];
    $Session = $row['Session'];
    $DeadDebitID = $row['DeadDebitID'];
    
    // Escape single quotes for JS
    $LedgerNameJS = addslashes($LedgerName);
    $SessionJS = addslashes($Session);
    
    echo "<tr>
            <td>{$sedmid}</td>
            <td>{$Debit}</td>
            <td>{$comments}</td>
            <td>
                <button class='btn btn-danger btn-sm' 
                    onclick=\"verifyDebitCencelReceiptss('{$TransactionID}','{$sedmid}','{$LedgerNameJS}','{$IDNo}','{$SessionJS}','{$Debit}','{$DeadDebitID}')\">
                    Verify ({$DeadDebitID})
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
$DeadDebitID = $_POST['DeadDebitID'];
$currentSmallDate = date('Y-m-d H:i:s'); // Or pass from JS

//  $sql = "UPDATE DeadDebits 
//         SET DebitStatus = 1, VerifiedBy = ?, VerifiedDate = ? 
//         WHERE TransactionID = ? 
//           AND Particulars = ? 
//           AND Session = ? 
//           AND Debit = ? 
//           AND IDNo = ?";

// $params = [$EmployeeID, $currentSmallDate, $transactionId, $debitHead, $session, $debitAmount, $IDNo];

 $sql = "UPDATE DeadDebits 
        SET DebitStatus = 1, VerifiedBy = ?, VerifiedDate = ? 
        WHERE DeadDebitID = ? 
                   AND IDNo = ?";

$params = [$EmployeeID, $currentSmallDate,$DeadDebitID,$IDNo];


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

else if($code == 24) {
   
    $sql = "SELECT * FROM DeadDebits WHERE DebitStatus = 1 ORDER BY DateEntry DESC";
    $stmt = sqlsrv_query($conn, $sql);

    if(!$stmt) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }
    $html = '<table class="table table-bordered" id="annualfeeTableForFixedHeader">';
    $html .= '<thead>
                <tr>
                    <th>Receipt No</th>
                  
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
                    <td>'.$row['Comments'].'</td>
                    <td>
                        <button class="btn btn-secondary btn-sm" 
                            onclick="showStudentDetailsForCencelReceipts(
                                '.$row['IDNo'].',
                                '.$row['Debit'].',
                                \''.addslashes($row['Comments']).'\',
                                '.$row['SemesterID'].',
                                '.$row['TransactionID'].',
                                \''.addslashes($row['Particulars']).'\',
                                \''.$row['Session'].'\'
                            );">
                            View
                        </button>
                    </td>
                  </tr>';
        $rowCount++;
    }

    if($rowCount == 0) {
        $html .= '<tr><td colspan="9">No ddRecord Found</td></tr>';
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
$sql = "SELECT * FROM DeadDebits WHERE DebitStatus = 1 AND IDNo = ?";
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
$LedgerName = $row['Particulars'];
$Debit = $row['Debit'];
$comments = $row['Comments'];
$Session = $row['Session'];
$LedgerID = $row['LedgerID'];
$DeadDebitID = $row['DeadDebitID'];
// Escape single quotes for JS
$LedgerNameJS = addslashes($LedgerName);
$SessionJS = addslashes($Session);

echo "<tr>
        <td>{$sedmid}</td>
        <td>{$Debit}</td>
        <td>{$comments}</td>
        <td>
            <button class='btn btn-danger btn-sm' 
                onclick=\"verifyDebitCencelReceiptss('{$TransactionID}','{$sedmid}','{$LedgerNameJS}','{$IDNo}','{$SessionJS}','{$Debit}','$LedgerID','$DeadDebitID')\">
                Verify {$LedgerID}
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
    $LedgerID      = $_POST['LedgerID'];
    $DeadDebitID   = $_POST['DeadDebitID'];

    $currentDate   = date('Y-m-d H:i:s');  // For approved date/time
$currentSmallDate = date('Y-m-d H:i:s'); 
    $sqlDeleteLedger = "DELETE FROM Ledger 
                        WHERE LedgerID = ?  AND IDNo = ?";

    $paramsLedger = [$LedgerID, $IDNo];

    $stmtDelete = sqlsrv_query($conn, $sqlDeleteLedger, $paramsLedger);



    $logDescription = "Debit Approve for TransactionID: $transactionId, Head: $debitHead, IDNo: $IDNo";
$logbookSql = "INSERT INTO logbook (userid, remarks, updatedby, date) VALUES (?, ?, ?, ?)";
$logbookParams = [$IDNo, $logDescription, $EmployeeID, $currentSmallDate];
$stmtLog = sqlsrv_query($conn, $logbookSql, $logbookParams);

    if ($stmtDelete === false) {
        echo "0";
        exit;
    }
    // if (sqlsrv_rows_affected($stmtDelete) > 0) {
        $sqlUpdate = "UPDATE DeadDebits 
                      SET DebitStatus = 2, 
                          ApprovedDebitId = ?, 
                          ApprovedDebitDate = ? 
                      WHERE DeadDebitID = ? 
                        AND IDNo = ?";




$params = [$EmployeeID, $currentSmallDate,$DeadDebitID,$IDNo];


 $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, $params);



        if ($stmtUpdate === false) {
            echo "0";
            exit;
        }

        if (sqlsrv_rows_affected($stmtUpdate) > 0) {
            echo "1";
        } else {
            echo "0";
        }
    // } else {
    //     echo "0";
    // }
}

else if($code==27)
{


    $startdate = $_POST['startdate'] ?? null;
    $enddate = $_POST['enddate'] ?? null;
    $id = $_POST['rollno'] ?? null;
    $paymentstatus = $_POST['paymentstatus'] ?? null;
    $paymenthead = $_POST['paymenthead'] ?? null;


    $IDNo = null;


    if (!empty($id)) {


            $sql = "SELECT * FROM Admissions  WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
       
      $IDNo=$row['IDNo'];
       
    }


     else {

  $qry = "SELECT * FROM PAYGKU_Response WHERE txnid='$id' ";
 $stmt = sqlsrv_query($conn, $qry);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $rowds = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($rowds) {
       
  $IDNo=$rowds['IDNo'];
       
    }

       
    } 
      
       
    }


    $qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
    $params = [];

    if ($startdate && $enddate) {
        $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
        $end = date('Y-m-d', strtotime($enddate)) . " 23:59";
        $qry .= "AND PaymentDate BETWEEN :start AND :end ";
        $params[':start'] = $start;
        $params[':end'] = $end;
    }

    if ($IDNo) {
        $qry .= "AND IDNo = :idno ";
        $params[':idno'] = $IDNo;
    }


    if ($paymentstatus) {
        $qry .= "AND Status = :status ";
        $params[':status'] = $paymentstatus;
    }

 if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }


    $qry .= "AND Amount > 0 AND Gateway = 'payu' ORDER BY PaymentDate DESC, Status DESC";

    // Execute query
    $stmt = $pdo->prepare($qry);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo '<table class="table table-bordered" id="annualfeeTable">';
    echo '<thead>
            <tr>
              <th>Sr No</th>
              <th>Receipt / Entry Date</th>
              <th>ID</th>
              <th>IDNo</th>
              <th>Student Name</th>
              <th>Email</th>
              <th>Payment ID</th>
              <th>Particular</th>
              <th>Transaction</th>
              <th>Semester</th>
              <th>Credit</th>
              <th>Status</th>
              <th>Remarks</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (count($records) > 0) {
        $srno = 1;
        foreach ($records as $item) {
            $receiptDate = !empty($item['Paymentdate']) ? date('d-m-Y', strtotime($item['Paymentdate'])) : '';
            $credit = $item['Credit'] ?? '';
            $transactionNo = $item['txnid'] ?? '';
            $mihpayid = $item['mihpayid'] ?? '';
            $remarks = $item['Remarks'] ?? '';
            $name = $item['Name'] ?? '';
            $email = $item['Email'] ?? '';
            $sem = $item['sem'] ?? '';
            $feeType = $item['FeeType'] ?? '';
            $status = $item['Status'] ?? '';
            $amount = $item['Amount'] ?? '';
            $id = $item['id'] ?? '';

          echo "<tr>
        <td>{$srno}</td>
        <td>{$receiptDate}</td>
        <td><button class='btn btn-primary btn-xs' onclick='syncfee(\"{$transactionNo}\")'>{$id}</button></td>
        <td>{$item['IDNo']}</td>
        <td>{$name}</td>
        <td>{$email}</td>
        <td>{$mihpayid}</td>
        <td>{$feeType}</td>
        <td>{$transactionNo}</td>
        <td>{$sem}</td>
        <td>{$amount}</td>
        <td>{$status}</td>
        <td>{$remarks}</td>
      </tr>";

            $srno++;
        }
    } else {
        echo "<tr><td colspan='13' class='text-center'>No records found</td></tr>";
    }

    echo '</tbody></table>';





}



else if($code==27.1)
{


    $startdate = $_POST['startdate'] ?? null;
    $enddate = $_POST['enddate'] ?? null;
    $id = $_POST['rollno'] ?? null;
    $paymentstatus = $_POST['paymentstatus'] ?? null;
    $paymenthead = $_POST['paymenthead'] ?? null;


    $IDNo = null;


    if (!empty($id)) {


            $sql = "SELECT * FROM Admissions  WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
       
      $IDNo=$row['IDNo'];
       
    }


     else {

  $qry = "SELECT * FROM PAYGKU_Response WHERE txnid='$id' ";
 $stmt = sqlsrv_query($conn, $qry);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $rowds = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($rowds) {
       
  $IDNo=$rowds['IDNo'];
       
    }

       
    } 
      
       
    }


    $qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
    $params = [];

    if ($startdate && $enddate) {
        $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
        $end = date('Y-m-d', strtotime($enddate)) . " 23:59";
        $qry .= "AND PaymentDate BETWEEN :start AND :end ";
        $params[':start'] = $start;
        $params[':end'] = $end;
    }

    if ($IDNo) {
        $qry .= "AND IDNo = :idno ";
        $params[':idno'] = $IDNo;
    }


    if ($paymentstatus) {
        $qry .= "AND Status = :status ";
        $params[':status'] = $paymentstatus;
    }

 if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }


    $qry .= "AND Amount > 0 AND Gateway = 'eazypay' ORDER BY PaymentDate DESC, Status DESC";
//echo $qry;
    // Execute query
    $stmt = $pdo->prepare($qry);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo '<table class="table table-bordered" id="annualfeeTable">';
    echo '<thead>
            <tr>
              <th>Sr No</th>
              <th>Receipt / Entry Date</th>
              <th>ID</th>
              <th>IDNo</th>
              <th>Student Name</th>
              <th>Email</th>
              <th>Payment ID</th>
              <th>Particular</th>
              <th>Transaction</th>
              <th>Semester</th>
              <th>Credit</th>
              <th>Status</th>
              <th>Remarks</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (count($records) > 0) {
        $srno = 1;
        foreach ($records as $item) {
            $receiptDate = !empty($item['Paymentdate']) ? date('d-m-Y', strtotime($item['Paymentdate'])) : '';
            $credit = $item['Credit'] ?? '';
            $transactionNo = $item['txnid'] ?? '';
            $mihpayid = $item['mihpayid'] ?? '';
            $remarks = $item['Remarks'] ?? '';
            $name = $item['Name'] ?? '';
            $email = $item['Email'] ?? '';
            $sem = $item['sem'] ?? '';
            $feeType = $item['FeeType'] ?? '';
            $status = $item['Status'] ?? '';
            $amount = $item['Amount'] ?? '';
            $id = $item['id'] ?? '';

          echo "<tr>
        <td>{$srno}</td>
        <td>{$receiptDate}</td>
        <td><button class='btn btn-primary btn-xs' onclick='syncfee(\"{$transactionNo}\")'>{$id}</button></td>
        <td>{$item['IDNo']}</td>
        <td>{$name}</td>
        <td>{$email}</td>
        <td>{$mihpayid}</td>
        <td>{$feeType}</td>
        <td>{$transactionNo}</td>
        <td>{$sem}</td>
        <td>{$amount}</td>
        <td>{$status}</td>
        <td>{$remarks}</td>
      </tr>";

            $srno++;
        }
    } else {
        echo "<tr><td colspan='13' class='text-center'>No records found</td></tr>";
    }

    echo '</tbody></table>';





}

else if($code==27.2)
{


    $startdate = $_POST['startdate'] ?? null;
    $enddate = $_POST['enddate'] ?? null;
    $id = $_POST['rollno'] ?? null;
    $paymentstatus = $_POST['paymentstatus'] ?? null;
    $paymenthead = $_POST['paymenthead'] ?? null;


    $IDNo = null;


    if (!empty($id)) {


            $sql = "SELECT * FROM Admissions  WHERE ClassRollNo = ? OR UniRollNo = ? OR IDNo = ?";

    
    $idBigInt = is_numeric($id) ? (int)$id : 0;

    // Prepare statement
    $params = [$id, $id, $idBigInt];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
       
      $IDNo=$row['IDNo'];
       
    }


     else {

  $qry = "SELECT * FROM PAYGKU_Response WHERE txnid='$id' ";
 $stmt = sqlsrv_query($conn, $qry);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch result
    $rowds = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($rowds) {
       
  $IDNo=$rowds['IDNo'];
       
    }

       
    } 
      
       
    }


    $qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
    $params = [];

    if ($startdate && $enddate) {
        $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
        $end = date('Y-m-d', strtotime($enddate)) . " 23:59";
        $qry .= "AND PaymentDate BETWEEN :start AND :end ";
        $params[':start'] = $start;
        $params[':end'] = $end;
    }

    if ($IDNo) {
        $qry .= "AND IDNo = :idno ";
        $params[':idno'] = $IDNo;
    }


    if ($paymentstatus) {
        $qry .= "AND Status = :status ";
        $params[':status'] = $paymentstatus;
    }

 if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }


    $qry .= "AND Amount > 0 AND Gateway = 'razorpay' ORDER BY PaymentDate DESC, Status DESC";
//echo $qry;
    // Execute query
    $stmt = $pdo->prepare($qry);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo '<table class="table table-bordered" id="annualfeeTable">';
    echo '<thead>
            <tr>
              <th>Sr No</th>
              <th>Receipt / Entry Date</th>
              <th>ID</th>
              <th>IDNo</th>
              <th>Student Name</th>
              <th>Email</th>
              <th>Payment ID</th>
              <th>Particular</th>
              <th>Transaction</th>
              <th>Semester</th>
              <th>Credit</th>
              <th>Status</th>
              <th>Remarks</th>
            </tr>
          </thead>';
    echo '<tbody>';

    if (count($records) > 0) {
        $srno = 1;
        foreach ($records as $item) {
            $receiptDate = !empty($item['Paymentdate']) ? date('d-m-Y', strtotime($item['Paymentdate'])) : '';
            $credit = $item['Credit'] ?? '';
            $transactionNo = $item['txnid'] ?? '';
            $mihpayid = $item['mihpayid'] ?? '';
            $remarks = $item['Remarks'] ?? '';
            $name = $item['Name'] ?? '';
            $email = $item['Email'] ?? '';
            $sem = $item['sem'] ?? '';
            $feeType = $item['FeeType'] ?? '';
            $status = $item['Status'] ?? '';
            $amount = $item['Amount'] ?? '';
            $id = $item['id'] ?? '';

          echo "<tr>
        <td>{$srno}</td>
        <td>{$receiptDate}</td>
        <td><button class='btn btn-primary btn-xs' onclick='syncfee(\"{$transactionNo}\")'>{$id}</button></td>
        <td>{$item['IDNo']}</td>
        <td>{$name}</td>
        <td>{$email}</td>
        <td>{$mihpayid}</td>
        <td>{$feeType}</td>
        <td>{$transactionNo}</td>
        <td>{$sem}</td>
        <td>{$amount}</td>
        <td>{$status}</td>
        <td>{$remarks}</td>
      </tr>";

            $srno++;
        }
    } else {
        echo "<tr><td colspan='13' class='text-center'>No records found</td></tr>";
    }

    echo '</tbody></table>';





}

else if($code==28)
{



    $encryptedId = $_POST['encryptedId'] ?? null;

    if ($encryptedId) {
        $postData = [
            'transaction_id' => $encryptedId
        ];

      $ch = curl_init('https://payment.gku.ac.in/api/payu/payment-sync');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(['error' => curl_error($ch)]);
        } else {
            $submit_details = json_decode($response, true);
            echo json_encode($submit_details);
        }

        curl_close($ch);
    } else {
        echo json_encode(['error' => 'Missing encryptedId']);
    }
} 

else if($code==28.1)
{
 $encryptedId = $_POST['encryptedId'] ?? null;

    if ($encryptedId) {
        $postData = [
            'transaction_id' => $encryptedId
        ];

      $ch = curl_init('https://payment.gku.ac.in/api/razorpay/payment-sync');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(['error' => curl_error($ch)]);
        } else {
            $submit_details = json_decode($response, true);
            echo json_encode($submit_details);
        }

        curl_close($ch);
    } else {
        echo json_encode(['error' => 'Missing encryptedId']);
    }
}

else if($code==28.2)
{
 $encryptedId = $_POST['encryptedId'] ?? null;

    if ($encryptedId) {
        $postData = [
            'transaction_id' => $encryptedId
        ];

      $ch = curl_init('http://117.250.20.109:89/Student/resyncEasyPay');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(['error' => curl_error($ch)]);
        } else {
            $submit_details = json_decode($response, true);
            echo json_encode($submit_details);
        }

        curl_close($ch);
    } else {
        echo json_encode(['error' => 'Missing encryptedId']);
    }
}


else if($code==29)
{

$startdate = $_POST['startdate'] ?? null;
$enddate = $_POST['enddate'] ?? null;
$paymentstatus = 'failure';
$paymenthead = $_POST['paymenthead'] ?? null;


$qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
$params = [];

if ($startdate && $enddate) {
    $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $end = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $qry .= " AND PaymentDate BETWEEN :start AND :end ";
    $params[':start'] = $start;
    $params[':end'] = $end;
}
if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }

$qry .= " AND Amount > 0 AND Gateway = 'payu' ORDER BY PaymentDate DESC, Status DESC";

$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$responses = []; // To store responses or errors if needed

if (count($records) > 0) {
    foreach ($records as $item) {
        $encryptedId = $item['txnid'] ?? '';

        if ($encryptedId) {
            $postData = [
                'transaction_id' => $encryptedId
            ];

            $ch = curl_init('https://payment.gku.ac.in/api/payu/payment-sync');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                // Log error or store in responses array
                $responses[] = ['txnid' => $encryptedId, 'error' => $error_msg];
            } else {
                // Optionally decode JSON response and store or log
                $responses[] = ['txnid' => $encryptedId, 'response' => $response];
            }

            curl_close($ch);
        }
    }

    // Send a clean JSON response back to client (you can customize this)
    echo json_encode([
        'status' => 'success',
        'message' => 'Sync completed',
        'details' => $responses // optional, or remove if you want no details sent
    ]);
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'No records found'
    ]);
}

exit;

}

else if($code==29.1)
{

$startdate = $_POST['startdate'] ?? null;
$enddate = $_POST['enddate'] ?? null;
$paymentstatus = 'failure';
$paymenthead = $_POST['paymenthead'] ?? null;

$qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
$params = [];

if ($startdate && $enddate) {
    $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $end = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $qry .= " AND PaymentDate BETWEEN :start AND :end ";
    $params[':start'] = $start;
    $params[':end'] = $end;
}

if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }



$qry .= " AND Amount > 0 AND Gateway = 'payu'  ANd Status='$paymentstatus' ORDER BY PaymentDate DESC, Status DESC";

$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$responses = []; // To store responses or errors if needed

if (count($records) > 0) {
    foreach ($records as $item) {
        $encryptedId = $item['txnid'] ?? '';

        if ($encryptedId) {
            $postData = [
                'transaction_id' => $encryptedId
            ];

            $ch = curl_init('https://payment.gku.ac.in/api/payu/payment-sync');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                // Log error or store in responses array
                $responses[] = ['txnid' => $encryptedId, 'error' => $error_msg];
            } else {
                // Optionally decode JSON response and store or log
                $responses[] = ['txnid' => $encryptedId, 'response' => $response];
            }

            curl_close($ch);
        }
    }

    // Send a clean JSON response back to client (you can customize this)
    echo json_encode([
        'status' => 'success',
        'message' => 'Sync completed',
        'details' => $responses // optional, or remove if you want no details sent
    ]);
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'No records found'
    ]);
}

exit;

}



else if($code==29.1)
{

$startdate = $_POST['startdate'] ?? null;
$enddate = $_POST['enddate'] ?? null;
$paymentstatus = 'failure';
$paymenthead = $_POST['paymenthead'] ?? null;

$qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
$params = [];

if ($startdate && $enddate) {
    $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $end = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $qry .= " AND PaymentDate BETWEEN :start AND :end ";
    $params[':start'] = $start;
    $params[':end'] = $end;
}

if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }



$qry .= " AND Amount > 0 AND Gateway = 'payu'  ANd Status='$paymentstatus' ORDER BY PaymentDate DESC, Status DESC";

$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$responses = []; // To store responses or errors if needed

if (count($records) > 0) {
    foreach ($records as $item) {
        $encryptedId = $item['txnid'] ?? '';

        if ($encryptedId) {
            $postData = [
                'transaction_id' => $encryptedId
            ];

            $ch = curl_init('https://payment.gku.ac.in/api/payu/payment-sync');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                // Log error or store in responses array
                $responses[] = ['txnid' => $encryptedId, 'error' => $error_msg];
            } else {
                // Optionally decode JSON response and store or log
                $responses[] = ['txnid' => $encryptedId, 'response' => $response];
            }

            curl_close($ch);
        }
    }

    // Send a clean JSON response back to client (you can customize this)
    echo json_encode([
        'status' => 'success',
        'message' => 'Sync completed',
        'details' => $responses // optional, or remove if you want no details sent
    ]);
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'No records found'
    ]);
}

exit;

}



else if($code==29.3)
{

$startdate = $_POST['startdate'] ?? null;
$enddate = $_POST['enddate'] ?? null;
$paymentstatus = 'failure';
$paymenthead = $_POST['paymenthead'] ?? null;

$qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
$params = [];

if ($startdate && $enddate) {
    $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $end = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $qry .= " AND PaymentDate BETWEEN :start AND :end ";
    $params[':start'] = $start;
    $params[':end'] = $end;
}

if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }



$qry .= " AND Amount > 0 AND Gateway = 'razorpay'  ANd Status='$paymentstatus' ORDER BY PaymentDate DESC, Status DESC";

$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$responses = []; // To store responses or errors if needed

if (count($records) > 0) {
    foreach ($records as $item) {
        $encryptedId = $item['txnid'] ?? '';

        if ($encryptedId) {
            $postData = [
                'transaction_id' => $encryptedId
            ];

            $ch = curl_init('https://payment.gku.ac.in/api/razorpay/payment-sync');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                // Log error or store in responses array
                $responses[] = ['txnid' => $encryptedId, 'error' => $error_msg];
            } else {
                // Optionally decode JSON response and store or log
                $responses[] = ['txnid' => $encryptedId, 'response' => $response];
            }

            curl_close($ch);
        }
    }

    // Send a clean JSON response back to client (you can customize this)
    echo json_encode([
        'status' => 'success',
        'message' => 'Sync completed',
        'details' => $responses // optional, or remove if you want no details sent
    ]);
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'No records found'
    ]);
}

exit;

}


else if($code==29.2)
{

$startdate = $_POST['startdate'] ?? null;
$enddate = $_POST['enddate'] ?? null;
$paymentstatus = 'failure';
$paymenthead = $_POST['paymenthead'] ?? null;


$qry = "SELECT * FROM PAYGKU_Response WHERE 1=1 ";
$params = [];

if ($startdate && $enddate) {
    $start = date('Y-m-d', strtotime($startdate)) . " 00:01";
    $end = date('Y-m-d', strtotime($enddate)) . " 23:59";

    $qry .= " AND PaymentDate BETWEEN :start AND :end ";
    $params[':start'] = $start;
    $params[':end'] = $end;
}
if ($paymenthead) {
        $qry .= "AND FeeType = :feetype ";
        $params[':feetype'] = $paymenthead;
    }

$qry .= " AND Amount > 0 AND Gateway = 'eazypay' ORDER BY PaymentDate DESC, Status DESC";

$stmt = $pdo->prepare($qry);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$responses = []; // To store responses or errors if needed

if (count($records) > 0) {
    foreach ($records as $item) {
        $encryptedId = $item['txnid'] ?? '';

        if ($encryptedId) {
            $postData = [
                'transaction_id' => $encryptedId
            ];

            $ch = curl_init('http://117.250.20.109:89/Student/resyncEasyPay');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                // Log error or store in responses array
                $responses[] = ['txnid' => $encryptedId, 'error' => $error_msg];
            } else {
                // Optionally decode JSON response and store or log
                $responses[] = ['txnid' => $encryptedId, 'response' => $response];
            }

            curl_close($ch);
        }
    }

    // Send a clean JSON response back to client (you can customize this)
    echo json_encode([
        'status' => 'success',
        'message' => 'Sync completed',
        'details' => $responses // optional, or remove if you want no details sent
    ]);
} else {
    echo json_encode([
        'status' => 'failure',
        'message' => 'No records found'
    ]);
}

exit;

}


else if ($code == 30) {

    
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
    $headQuery = "SELECT TOP 1 * FROM MasterHeadNew WHERE Status = '1' AND Id = ?";
    $headStmt = sqlsrv_query($conn, $headQuery, [$debithead]);
    $headData = sqlsrv_fetch_array($headStmt, SQLSRV_FETCH_ASSOC);
    $head = $headData['Head'];
    $bankTransactionDate = date('Y-m-d H:i:s', strtotime($transactiondate));
    if ($modeofpayment == 'Cash') {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, AutoDebit, Status, IsOld)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, -$debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $isChecked, 0, $isOldStatus];
    } 
    else {
        $sql = "INSERT INTO PrintReceipt 
        (DateEntry, CollegeName, IDNo, StudentName, Course, FatherName, Particulars, Credit, DebitHead, Session, SemesterID, CreatedBy, CreatedDate, ModeOfPayment, BankName, TransactionNo, TransactionDate, AutoDebit, Status, IsOld)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$smallDateTime, $CollegeName, $IDNo, $StudentName, $Course, $FatherName, $debitparticulars, -$debitfee, $head, $debitsession, $debitsem, $EmployeeID, $dateTime, $modeofpayment, $nameofbank, $transactionid, $bankTransactionDate, $isChecked, 0, $isOldStatus];
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

else if($code==31)
{

 $id = trim($_POST['receiptno']) ?? null;


if($id>0)
{
    $sql = "SELECT *  FROM Ledger t1
        WHERE t1.ReceiptNo = ? AND NOT EXISTS (SELECT 1 FROM DeadDebits t2   WHERE t1.TransactionID = t2.TransactionID 
              AND t1.Debit = t2.Debit ) ORDER BY t1.TransactionID DESC";

    $params = [$id];
    $stmtledger = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
   
    $ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}

 

}

?>




<div class="card">
    <div class="card-header"
        style="position: sticky; top: 0; z-index: 10; background-color: #fff; border-bottom: 1px solid #ccc;">

        <div class="card-actions"
            style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

            <div id="balance" style="float:right">
                <?php      
     if (!empty($balances) && is_array($balances)) {
                        
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
                <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
                <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
                <?php 

}
}?>
            </div>
            &nbsp;

        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
            <tr>
                <th>Session </th>
                <th>IDNo </th>
                <th>Class RollNo </th>
                <th>Uni RollNo </th>
                <th>Name </th>
                <th>Course </th>
                <th>Receipt Date </th>
                <th>Bank Date</th>
                <th>Head</th>
                <th>Receipt No</th>
                <th style="max-width:200px">Particular</th>
                <th>Trasaction</th>
                <th>Semester</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Remarks</th>
                <th class="w-1">Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">

            <?php
  


  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$credit=$ledger['Credit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
            <tr class="table">
                <td><?= htmlspecialchars($ledger['Session']) ?></td>
                <td><?= htmlspecialchars($ledger['IDNo']) ?></td>
                <td><?= htmlspecialchars($ledger['ClassRollNo']) ?></td>
                <td><?= htmlspecialchars($ledger['UniRollNo']) ?></td>
                <td><?= htmlspecialchars($ledger['StudentName']) ?></td>
                <td><?= htmlspecialchars($ledger['Course']) ?></td>
                <td><?= htmlspecialchars($dateentry) ?></td>
                <td><?= htmlspecialchars($bankdate) ?></td>
                <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
                <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
                <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
                <td><?= htmlspecialchars($ledger['TransactionType']) ?></td>
                <td><?= htmlspecialchars($ledger['Semester']) . '-' . htmlspecialchars($ledger['SemesterID']) ?></td>
                <td><?= htmlspecialchars($ledger['Debit']) ?></td>
                <td><?= htmlspecialchars($ledger['Credit']) ?></td>
                <td><?= htmlspecialchars($ledger['Remarks']) ?></td>
                <td>
                    <?php  if($credit!='')
  {?>
                    <button class="btn btn-primary btn-sm" target='_blank' id='rid'
                        onclick="print_receipt('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                        Print
                    </button>
                    <?php }


                    if($ledger['LedgerName']=='Refund')
                    {?><button class="btn btn-danger btn-sm" target='_blank' id='rid'
                        onclick="print_receipt_refund('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                        Print
                    </button>
                    <?php 

                    } ?>
                </td>
            </tr>
            <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



        </tbody>
    </table>




</div>
<?php

            

}


else if($code==31.1)
{

 $id = trim($_POST['receiptno']) ?? null;


if($id>0)
{
    $sql = "SELECT *  FROM CancelledReceipt t1
        WHERE t1.ReceiptNo = ? AND NOT EXISTS (SELECT 1 FROM DeadDebits t2   WHERE t1.TransactionID = t2.TransactionID 
              AND t1.Debit = t2.Debit ) ORDER BY t1.TransactionID DESC";

    $params = [$id];
    $stmtledger = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
   
    $ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}

 

}

?>




<div class="card">
    <div class="card-header"
        style="position: sticky; top: 0; z-index: 10; background-color: #fff; border-bottom: 1px solid #ccc;">

        <div class="card-actions"
            style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

            <div id="balance" style="float:right">
                <?php      
     if (!empty($balances) && is_array($balances)) {
                        
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
                <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
                <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
                <?php 

}
}?>
            </div>
            &nbsp;

        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
            <tr>
                <th>Session </th>
                <th>IDNo </th>
                <th>Class RollNo </th>
               
                <th>Name </th>
               
                <th>Receipt Date </th>
                <th>Bank Date</th>
                <th>Head</th>
                <th>Receipt No</th>
                <th style="max-width:200px">Particular</th>
                <th>Trasaction</th>
                <th>Semester</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Remarks</th>
                <th class="w-1">Action</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;">

            <?php
  


  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$credit=$ledger['Credit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
            <tr class="table">
                <td><?= htmlspecialchars($ledger['Session']) ?></td>
                <td><?= htmlspecialchars($ledger['IDNo']) ?></td>
                <td><?= htmlspecialchars($ledger['ClassRollNo']) ?></td>
              
                <td><?= htmlspecialchars($ledger['StudentName']) ?></td>
                <!-- <td><?= htmlspecialchars($ledger['Course']) ?></td> -->
                <td><?= htmlspecialchars($dateentry) ?></td>
                <td><?= htmlspecialchars($bankdate) ?></td>
                <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
                <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
                <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
                <td>Credit</td>
                <td><?= htmlspecialchars($ledger['SemesterID']) ?></td>
                <td><?= htmlspecialchars($ledger['Debit']) ?></td>
                <td><?= htmlspecialchars($ledger['Credit']) ?></td>
                <td><?= htmlspecialchars($ledger['Comments']) ?></td>
                <td>
                    --
                </td>
            </tr>
            <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



        </tbody>
    </table>




</div>
<?php
        
$pdo = null;
}

else if($code==32)
{

 $id = trim($_POST['receiptno']) ?? null;
 $session = trim($_POST['session']) ?? null;

if($id>0)
{

// Build SQL
$sql = "
SELECT *  
FROM Ledger t1
WHERE t1.ChequeDraftNo LIKE ? 
  AND t1.Session = ? 
  AND NOT EXISTS (
        SELECT 1 
        FROM DeadDebits t2
        WHERE t1.TransactionID = t2.TransactionID
          AND t1.Debit = t2.Debit
  )
ORDER BY t1.TransactionID DESC
";



$params = ['%' . $id . '%', $session];

// Run query
$stmtledger = sqlsrv_query($conn, $sql, $params);

if ($stmtledger === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch results
$ledgers = [];
while ($rowledger = sqlsrv_fetch_array($stmtledger, SQLSRV_FETCH_ASSOC)) {
    $ledgers[] = $rowledger;
}




 

}

?>




<div class="card">


    <div class="card-actions" style="position: sticky; top: 0; z-index: 20; background-color: white; padding: 10px;">

        <div id="balance" style="float:right">
            <?php      
     if (!empty($balances) && is_array($balances)) {
                        
      foreach ($balances as $balance) {
   ?><button class="btn btn-info btn-xs"><b> Debit : </b> <?=$balance['totaldebit'] ?? 0?></button>
            <button class="btn btn-success btn-xs"><b> Credit : </b> <?=$balance['totalcredit'] ?? 0?></button>
            <button class="btn btn-danger btn-xs"><b>Balance : </b> <?=$balance['balance'] ?? 0?></button>
            <?php 

}
}?>
        </div>
        &nbsp;

    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-info" style="position: sticky; top: 59px; z-index: 1000;">
        <tr>
            <th>Session </th>
            <th>IDNo </th>
            <th>Class RollNo </th>
            <th>Uni RollNo </th>
            <th>Name </th>
            <th>Course </th>
            <th>Receipt Date </th>
            <th>Bank Date</th>
            <th>Head</th>
            <th>Receipt No</th>
            <th style="max-width:200px">Particular</th>
            <th>Trasaction</th>
            <th>Semester</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Remarks</th>
            <th class="w-1">Action</th>
        </tr>
    </thead>
    <tbody style="font-size:10px;">

        <?php
  


  
   if (!empty($ledgers) && is_array($ledgers) ) {
    foreach ($ledgers as $ledger) {
        if (!is_array($ledger)) continue;
        $dateentry = '';
        $bankdate  = '';
$credit=$ledger['Credit'];
        if (!empty($ledger['DateEntry']) && $ledger['DateEntry'] instanceof DateTime) {
            $dateentry = $ledger['DateEntry']->format('d-m-Y');
        }
        if (!empty($ledger['DateEntrySubmission']) && $ledger['DateEntrySubmission'] instanceof DateTime) {
            $bankdate = $ledger['DateEntrySubmission']->format('d-m-Y');
        }
?>
        <tr class="table">
            <td><?= htmlspecialchars($ledger['Session']) ?></td>
            <td><?= htmlspecialchars($ledger['IDNo']) ?></td>
            <td><?= htmlspecialchars($ledger['ClassRollNo']) ?></td>
            <td><?= htmlspecialchars($ledger['UniRollNo']) ?></td>
            <td><?= htmlspecialchars($ledger['StudentName']) ?></td>
            <td><?= htmlspecialchars($ledger['Course']) ?></td>
            <td><?= htmlspecialchars($dateentry) ?></td>
            <td><?= htmlspecialchars($bankdate) ?></td>
            <td><?= htmlspecialchars($ledger['LedgerName']) ?></td>
            <td><?= htmlspecialchars($ledger['ReceiptNo']) ?></td>
            <td style="max-width:200px"><?= htmlspecialchars($ledger['Particulars']) ?></td>
            <td><?= htmlspecialchars($ledger['TransactionType']) ?></td>
            <td><?= htmlspecialchars($ledger['Semester']) . '-' . htmlspecialchars($ledger['SemesterID']) ?></td>
            <td><?= htmlspecialchars($ledger['Debit']) ?></td>
            <td><?= htmlspecialchars($ledger['Credit']) ?></td>
            <td><?= htmlspecialchars($ledger['Remarks']) ?></td>
            <td>
                <?php  if($credit!='')
  {?>
                <button class="btn btn-primary btn-sm" target='_blank' id='rid'
                    onclick="print_receipt('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                    Print
                </button>
                <?php }


                    if($ledger['LedgerName']=='Refund')
                    {?><button class="btn btn-danger btn-sm" target='_blank' id='rid'
                    onclick="print_receipt_refund('<?= $ledger['ReceiptNo'] ?>','<?= addslashes($ledger['LedgerName']) ?>','<?= $ledger['IDNo'] ?>','<?= $ledger['Session'] ?>')">
                    Print
                </button>
                <?php 

                    } ?>
            </td>
        </tr>
        <?php
    }
}
 else {
    echo "<tr><td colspan='10'><div class='alert alert-warning' role='alert'>Uh oh, No record found</div></td></tr>";
}
?>



    </tbody>
</table>




</div>
<?php
$pdo = null;


 }
   
//Spot 
else if($code==33)
{
$RouteID=$_POST['route'];
$query = "SELECT * FROM TBM_BusStopageMaster WHERE IsActive = 1 ANd  BusRouteID='$RouteID'";
$stmt = $pdo->prepare($query);
$stmt->execute();
 ?><option value="">
       Select Spot
    </option>

<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
    <option value="<?= $row['StopageID']; ?>">
        <?= $row['Spot']; ?>
    </option>
<?php } 

$pdo = null;

}
else if($code==33.1)
{
$spot=$_POST['spot'];
 $query = "SELECT * FROM TBM_BusStopageMaster WHERE IsActive = 1 ANd  StopageID='$spot'";
$stmt = $pdo->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

     echo (int)$row['BusFee'];
 } 
$pdo = null;
}

}