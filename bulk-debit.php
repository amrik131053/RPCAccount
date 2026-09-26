<?php
include "header.php";
include "provider.php";
?>
<style>
/* Tables */
.table {
    font-size: 13px;
    margin-bottom: 0;
}

.table-fixed-header {
    max-height: 600px;
    /* set height for scrolling */
    overflow-y: auto;
}

.table-fixed-header table {
    width: 100%;
    border-collapse: collapse;
}

.table-fixed-header thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    /* background color for header */
    z-index: 10;
}
</style>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-2">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Bulk Debit</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-label"><b>Session</b><span class="text-danger">*</span></div>
                                    <Select class="form-select" id='session'>
                                        <option value="">Select Session</option>
                                        <?php  foreach  ($academicsessions as $showsession) 
                                      {?>
                                        <option value="<?=$showsession['Session'];?>">
                                            <?=$showsession['Session'];?></option>
                                        <?php }?>

                                    </Select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-label"><b>Faculty Name</b><span class="text-danger">*</span></div>
                                    <Select class="form-select" onchange="loadPrograms(this.value)" id='CollegeID'>
                                        <option value="">Select College</option>
                                        <?php foreach ($faculities as $showfaculities) { ?>
                                        <option value="<?= $showfaculities['CollegeID']; ?>">
                                            <?= $showfaculities['CollegeName']; ?>(<?= $showfaculities['CollegeID']; ?>)
                                        </option>
                                        <?php } ?>

                                    </Select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-label"><b>Program Name</b><span class="text-danger">*</span></div>
                                    <Select class="form-select" id='ProgramDropdown'>
                                        <option value="">Select Programs</option>
                                    </Select>
                                </div>



                                <Select class="form-select" id='batch' hidden>
                                    <option value="0">Select Batch</option>


                                </Select>



                                </Select>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-label"><b>Lateral Entry</b><span class="text-danger">*</span>
                                        </div>
                                        <Select class="form-select" id='lateralentry' required>
                                            <option value=" ">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>


                                        </Select>
                                    </div>



                                    <div class="mb-3">
                                        <label class="form-label" style="color: brown"><b>Fee Category</b></label>
                                        <Select class="form-select" id="annualfeecategory" name='annualfeecategory'>
                                            <option value="">Fee Category</option>

                                            <?php  foreach ($masterfeecategory as $feeCategories)

                                            {?><option value="<?= $feeCategories['ID'];?>">
                                                <?= $feeCategories['FeeCategory'];?>(<?= $feeCategories['ID'];?>)
                                            </option>
                                            <?php }?>

                                        </Select>
                                    </div>


                                    <div class="mb-3">
                                        <div class="form-label"><b>Semester</b></div>
                                        <Select class="form-select" id='searchsemester' required>
                                            <?php  foreach ($semesters as $semester)
                                                {?><option value="<?= $semester;?>">
                                                <?= $semester;?></option>
                                            <?php }?>
                                        </Select>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-success" style="float:right;"
                                            onclick="displayStudentForBulkDebit()">Display</button>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Students</h3>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive" id="annualfeeTableDiv">

                                </div>

                                <hr>
                                <div class="row col-lg-12">

                                    <div class="col-lg-6">

                                        <label class="form-label" style="color: brown">Head</label>
                                        <Select class="form-select" id='debithead'>
                                            <option value="7">Tution Fee</option>
                                            <option value="">Select Head</option>
                                            <?php  foreach ($heads as $head) {?>
                                            <option value="<?=$head['Id'];?>"><?= $head['Head'];?></option>

                                            <?php } ?>

                                        </Select>






                                        <div class="form-label" style="color: brown"><b>Session</b></div>
                                        <Select class="form-select" id='debitsession'>

                                          
                                            <?php foreach ($accountsessions as $showsession) { ?>
                                            <option value="<?= $showsession['CurrentSession']; ?>">
                                                <?= $showsession['CurrentSession']; ?>
                                            </option>
                                            <?php } ?>

                                        </Select>

                                        <label class="form-label" style="color: brown"><b>Semester</b></label>
                                        <Select class="form-select" id="debitsemester">
                                            <?php  foreach ($semesters as $semester)
                                                {?><option value="<?= $semester;?>">
                                                <?= $semester;?></option>
                                            <?php }?>

                                        </Select>

                                        <div class="mb-3">
                                            <div class="form-label"><b>Debit Date</b></div>
                                            <input type="date" id='debitdate' class="form-control">
                                        </div>

                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label" style="color: brown">Debit</label>
                                        <input type="number" class="form-control" Name="feedebit" id='debitfee'>
                                        <label class="form-label" style="color: brown">Particular`s</label>

                                        <textarea class="form-control" rows='3' id='debitparticulars'></textarea>
                                        <label class="form-label" style="color: brown">Remarks</label>
                                        <textarea class="form-control" rows="3" id='debitremarks'></textarea>
                                    </div>




                                </div>
                                <br>
                                <div class="card-footer">

                                    <button class="btn  btn-primary btn-xs" onclick="CreateFeedebitBulk()"
                                        style="float:right">Debit
                                        Fee</button>
                                    <br>
                                    <br>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-ledger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Account Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="modalannualfeeTableDiv" style="scroll-behavior: auto;height:700px">




                </div>



                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "footer.php";
?>
<script>
function displayStudentForBulkDebit() {

    const feesession = document.getElementById('session').value;
    const CollegeID = document.getElementById('CollegeID').value;
    const ProgramDropdown = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    const lateralentry = document.getElementById('lateralentry').value;
    const annualfeecategory = document.getElementById('annualfeecategory').value;
    const semester = document.getElementById('searchsemester').value;

    if (!feesession) {

        return showErrorMessage("Please select session");
    }
    if (!CollegeID) {

        return showErrorMessage("Please select College");
    }
    if (!ProgramDropdown) {

        return showErrorMessage("Please select Course");
    }
    if (!lateralentry) {

        return showErrorMessage("Please select lateral entry");
    }
    ShowLoader();
    $.ajax({
        url: 'action-g.php',
        type: 'POST',
        data: {
            code: 12,
            CollegeID: CollegeID,
            feesession: feesession,
            ProgramDropdown: ProgramDropdown,
            batch: batch,
            lateralentry: lateralentry,
            annualfeecategory: annualfeecategory,
            semester: semester
        },
        success: function(response) {
            HideLoader();
            document.getElementById('annualfeeTableDiv').innerHTML = response;


            setTimeout(() => {
                const table = document.querySelector('#annualfeeTableDiv table');
                if (table) enableTableSorting(table);
            }, 50);


        },
        error: function(err) {
            HideLoader();
            console.error(err);
            alert('Something went wrong.');
        }
    });

}


function CreateFeedebitBulk() {
    ShowLoader();
    const studentCheckboxes = document.getElementsByClassName('studentid');
    let selectedStudents = [];
    for (let i = 0; i < studentCheckboxes.length; i++) {
        if (studentCheckboxes[i].checked) {
            selectedStudents.push(studentCheckboxes[i].value);
        }
    }

    const debithead = document.getElementById('debithead').value;
    const debitsession = document.getElementById('debitsession').value;
    const debitsemester = document.getElementById('debitsemester').value;
    const debitremarks = document.getElementById('debitremarks').value;
    const debitfee = document.getElementById('debitfee').value;
    const debitparticulars = document.getElementById('debitparticulars').value;
    const debitdate = document.getElementById('debitdate').value;

    if (!debithead) {
        HideLoader();
        return showErrorMessage("Please select Fee Head");
    }
    if (!debitsession) {
        HideLoader();
        return showErrorMessage("Please select Session");
    }
    if (!debitsemester) {
        HideLoader();
        return showErrorMessage("Please select Semester");
    }
    if (!debitfee || isNaN(debitfee) || debitfee < 0) {
        HideLoader();
        return showErrorMessage("Enter valid Fee Amount");
    }
    if (!debitparticulars) {
        HideLoader();
        return showErrorMessage("Enter Particulars");
    }
    if (!debitremarks) {
        HideLoader();
        return showErrorMessage("Enter Remarks");
    }
    if (!debitdate) {
        HideLoader();
        return showErrorMessage("Select Debit Date");
    }
    if (selectedStudents.length === 0) {
        HideLoader();
        return showErrorMessage("Select at least one student");
    }


    if (selectedStudents.length === 0) {
        HideLoader();
        showErrorMessage('Please select at least one student');
        return;
    }

    const formData = new FormData();
    formData.append('code', '13'); // action code
    formData.append('debithead', debithead);
    formData.append('debitsession', debitsession);
    formData.append('debitsemester', debitsemester);
    formData.append('debitremarks', debitremarks);
    formData.append('debitfee', debitfee);
    formData.append('debitparticulars', debitparticulars);
    formData.append('debitdate', debitdate);

    selectedStudents.forEach((id, index) => {
        formData.append(`students[${index}]`, id);
    });

    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            HideLoader();
            console.log(data);
            showSuccessMessage('Bulk debit created successfully');
            displayStudentForBulkDebit();
        })
        .catch(error => {
            HideLoader();
            console.error('Error:', error);
            showErrorMessage('Error connecting to server');
        });
}


function selectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.studentid');
    checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
}

function viewmodalaccountstatus(id) {


    $.ajax({
            url: 'action.php',
            type: 'POST',
            data: {
                code: 2,
                id: id

            },
            success: function(response) {
                console.log(response);
                const container = document.getElementById('modalannualfeeTableDiv');
                container.innerHTML = response;


                setTimeout(() => {
                    const table = container.querySelector('table');
                    if (table) {
                        enableTableSorting(table);
                    } else {
                        console.warn('No table found in response');
                    }
                }, 100);
            },

            error: function(err) {
                HideLoader();
                console.error(err);
                alert('Something went wrong.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

}
</script>