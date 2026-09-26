  
  <?php 
if($id) 
{
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

       echo "<div class='alert alert-warning' role='alert'> No record found</div>";
    exit;
    }









     $sql = "SELECT Session, CollegeID, CollegeName, CourseID, Batch,AdmissionType,Category,
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
       endif;
}
    ?>