<?php
include "header.php";
include "provider.php";
?>
<div class="page-body">
    <div class="container-xl">

        <div class="row row-cards">


            <div class="col-md-4">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Anual Fee</h3>
                            </div>


                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-label"><b>Session</b></div>
                                    <Select class="form-control" id='session'>
                                        <option value="">Select Session</option>
                                        <?php  foreach  ($academicsessions as $showsession) 
                                      {?>
                                        <option value="<?=$showsession['Session'];?>">
                                            <?=$showsession['Session'];?></option>
                                        <?php }?>

                                    </Select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-label"><b>Faculty Name</b></div>
                                    <Select class="form-control" onchange="loadPrograms(this.value)" id='CollegeID'>
                                        <option value="">Select College</option>
                                        <?php foreach ($faculities as $showfaculities) { ?>
                                        <option value="<?= $showfaculities['CollegeID']; ?>">
                                            <?= $showfaculities['CollegeName']; ?>(<?= $showfaculities['CollegeID']; ?>)
                                        </option>
                                        <?php } ?>



                                    </Select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-label"><b>Program Name</b></div>
                                    <Select class="form-control" id='ProgramDropdown'>
                                        <option value="">Select Programs</option>



                                    </Select>
                                </div>


                                <div class="form-label"><b>Batch <i style="color: brown">(Optional)</i></b></div>
                                <select class="form-control" id='batch'>
                                    <option value="0">Select Batch</option>
                                    <?php  foreach  ($years as $year) 
                                      {?>
                                    <option value="<?= $year;?>">
                                        <?= $year;?></option>
                                    <?php }?>
                                </select>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-label"><b>Lateral Entry</b></div>
                                        <Select class="form-control" id='lateralentry' required>
                                            <option value="0">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>


                                        </Select>
                                    </div>
                                    <div class="card-footer">

                                        <button class="btn btn-success" style="float:right;"
                                            onclick="displayannualfee()">Display</button>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Fee Detail</h3>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive" id="annualfeeTableDiv">

                                </div>

                                <!-- <div class="card-footer"> -->
                                <hr>
                                <div class="row g-3">
                                    <div class="col-2">


                                        <label class="form-label" style="color: brown">Head</label>
                                        <Select class="form-control" id="annualfeehead">
                                            <option value="7">Tution Fee</option>
                                            <option value="">Select Head</option>
                                            <?php  foreach ($heads as $head) {?>
                                            <option value="<?=$head['Id'];?>"><?= $head['Head'];?></option>

                                            <?php } ?>

                                        </Select>


                                    </div>

                                    <div class="col-2">
                                        <label class="form-label" style="color: brown"><b>Fee Category</b></label>
                                        <Select class="form-control" id="annualfeecategory">

                                            <option value="">Fee Category</option>

                                            <?php  foreach ($masterfeecategory as $feeCategories)

                                            {?><option value="<?= $feeCategories['ID'];?>">
                                                <?= $feeCategories['FeeCategory'];?>(<?= $feeCategories['ID'];?>)
                                            </option>
                                            <?php }?>

                                        </Select>
                                    </div>

                                    <div class="col-1">
                                        <label class="form-label" style="color: brown"><b>Semester</b></label>
                                        <Select class="form-control" id="annualfeesem">


                                            <?php  foreach ($semesters as $semester)

                                            {?><option value="<?= $semester;?>">
                                                <?= $semester;?></option>
                                            <?php }?>

                                        </Select>
                                    </div>

                                    <div class="col-2"> <label class="form-label" style="color: brown">Actual
                                            FEE</label>
                                        <input type="text" value="" class="form-control" id="annualfeeamount">
                                    </div>


                                    <div class="col-2"> <label class="form-label" style="color: brown">Applicable
                                            FEE</label>
                                        <input type="text" value="" class="form-control" id="annualfeeapplicableamount">
                                    </div>

                                    <div class="col-1">
                                        <label class="form-label" style="color: brown">Action</label>
                                        <button class="btn btn-success" onclick="CreateAnnualFee()">Add</button>
                                    </div>
                                </div>

                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" id='editannualfeemodal'>

    </div>
</div>

<script>
function displayannualfee() {
    var feesession = document.getElementById('session').value;
    var CollegeID = document.getElementById('CollegeID').value;
    var ProgramDropdown = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    var lateralentry = document.getElementById('lateralentry').value;


    if (session1 = !'' && CollegeID != '' && ProgramDropdown != '' && lateralentry != '') {

      ShowLoader();
        $.ajax({
            url: 'action.php',
            type: 'POST',
            data: {
                code: 7,
                CollegeID: CollegeID,
                feesession: feesession,
                ProgramDropdown: ProgramDropdown,
                batch: batch,
                lateralentry: lateralentry
            },
            success: function(response) {
              HideLoader();
              
              document.getElementById("annualfeeTableDiv").innerHTML = response;
              
              
              
            },
            error: function(xhr, status, error) {
              HideLoader();
              console.error("AJAX Error:", error);
              alert("An error occurred while fetching data.");
            },
            complete: function() {
              HideLoader();
              
            }
          });
        } else {
      HideLoader();
        showErrorMessage('Invalid Selection');
    }

}

function editannualfee(srno) {
    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 8,
            srno: srno
        },
        success: function(response) {

            HideLoader();
            document.getElementById("editannualfeemodal").innerHTML = response;



        },
        error: function(xhr, status, error) {
            HideLoader();
            console.error("AJAX Error:", error);
            alert("An error occurred while fetching data.");
        },
        complete: function() {
            HideLoader();

        }
    });

}

function UpdateAnnualFee(srno) {


    var amount = document.getElementById("annualfeeamountedit").value;
    var ActualFee = document.getElementById("annualfeeactualamountedit").value;
    ShowLoader();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 9,
            srno: srno,
            amount: amount,
            actualFee: ActualFee,
            srno: srno
        },
        success: function(response) {
            HideLoader();
            // console.log(response);
            if (response == 1) {
                showSuccessMessage("Annual Fee updated successfully.");
                displayannualfee();
            } else if (response == 0) {
                showErrorMessage('Missing required parameters.');
            } else if (response == 2) {
                showErrorMessage('No changes made or record not found.');
            } else {
                showErrorMessage('Invalid data kindly check');
            }

        },
        error: function(xhr, status, error) {
            HideLoader();
            console.error("AJAX Error:", error);
            alert("An error occurred while fetching data.");
        },
        complete: function() {

            HideLoader();
        }
    })

}



function deleteannualfee(srno) {
    if (!confirm("Are you sure you want to delete this Annual Fee? This action cannot be undone.")) {
        return; // User cancelled
    }

    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 10,
            srno: srno
        },
        success: function(response) {
            HideLoader();
            if (response == 1) {
                showSuccessMessage("Annual Fee Deleted successfully.");
                displayannualfee();
            } else if (response == 0) {
                showErrorMessage('Missing required parameters.');
            } else if (response == 2) {
                showErrorMessage('No changes made or record not found.');
            } else {
                showErrorMessage('Invalid data kindly check');
            }
        },
        error: function(xhr, status, error) {
            HideLoader();
            console.error("AJAX Error:", error);
            alert("An error occurred while fetching data.");
        }
    });
}



function CreateAnnualFee() {

    var feesession = document.getElementById('session').value;
    var CollegeID = document.getElementById('CollegeID').value;
    var ProgramDropdown = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    var lateralentry = document.getElementById('lateralentry').value;

    var annualfeehead = document.getElementById('annualfeehead').value;
    var annualfeecategory = document.getElementById('annualfeecategory').value;
    var annualfeesem = document.getElementById('annualfeesem').value;

    var annualfeeamount = document.getElementById('annualfeeamount').value;
    var annualfeeapplicableamount = document.getElementById('annualfeeapplicableamount').value;


    if (session1 = !'' && CollegeID != '' && ProgramDropdown != '' && lateralentry != '' && annualfeehead != '' &&
        annualfeecategory != '' && annualfeesem != '' && annualfeeamount != '' && annualfeeapplicableamount != '') {

        $.ajax({
            url: 'action.php',
            type: 'POST',
            data: {
                code: 11,
                CollegeID: CollegeID,
                feesession: feesession,
                ProgramDropdown: ProgramDropdown,
                batch: batch,
                lateralentry: lateralentry,
                annualfeehead: annualfeehead,
                annualfeecategory: annualfeecategory,
                annualfeesem: annualfeesem,
                annualfeeamount: annualfeeamount,
                annualfeeapplicableamount: annualfeeapplicableamount
            },
            success: function(response) {
                console.log(response);

                if (response == 0) {
                    showErrorMessage('Lateral Entry Not Selected');
                } else if (response == 1) {
                    showErrorMessage('College or Course data not found');
                } else if (response == 2) {
                    showErrorMessage('Head data not found');
                } else if (response == 3) {
                    showErrorMessage('Record already exists');
                } else if (response == 4) {

                    showSuccessMessage("Annual Fee Deleted successfully.");

                    displayannualfee();
                } else {
                    showErrorMessage('try after some time ');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                alert("An error occurred while fetching data.");
            }
        });
    } else {
        showErrorMessage('Invalid data kindly check');

    }

}
</script>
}

<?php include "footer.php"; ?>