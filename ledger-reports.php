<?php
include "header.php";
include "provider.php";
?>

<style>
#annualfeeTableDiv {
    margin-top: 10px;
    font-size: 13px;
}

#annualfeeTableDiv label {
    font-weight: 600;
    font-size: 12px;
    color: #222;
    margin-bottom: 3px;
}

#annualfeeTableDiv .row {
    margin-bottom: 6px;
}

/* General card body */
.card-body {
    padding: 10px 12px;
    background: #fff;
}

/* Tables */
.table {
    font-size: 13px;
    margin-bottom: 0;
}

.table th,
.table td {
    padding: 6px 10px;
    vertical-align: middle;
}

.table thead th {
    background: #f1f5f9;
    color: #222;
    border-bottom: 2px solid #dee2e6;
}

/* Header button */
.card-header button {
    font-size: 12px;
    padding: 3px 8px;
}

/* Sticky left panel */
/* Sticky left panel with internal scroll */
.col-md-3 {
    position: sticky;
    top: 10px;
    align-self: flex-start;
    overflow-y: auto;
    /* enable internal scroll */
    z-index: 10;
    background: #fff;
    /* avoid overlap transparency */
    border-right: 1px solid #e0e0e0;
    padding-right: 8px;
}
</style>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-2">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header">
                                <h4 >Student Fee Ledger Filters</h4>
                            </div>

                            <div class="card-body">
                                <div class="row ">
                                    <!-- Session -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold">Session</label>
                                        <select class="form-select" id="session" name="session">
                                            <option value="">Select Session</option>
                                            <?php foreach ($academicsessions as $showsession) { ?>
                                            <option value="<?= $showsession['Session']; ?>">
                                                <?= $showsession['Session']; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Faculty Name -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold">Faculty Name</label>
                                        <select class="form-select" onchange="loadPrograms(this.value)" id="CollegeID"
                                            name="CollegeID">
                                            <option value="">Select College</option>
                                            <?php foreach ($faculities as $showfaculities) { ?>
                                            <option value="<?= $showfaculities['CollegeID']; ?>">
                                                <?= $showfaculities['CollegeName']; ?>
                                                (<?= $showfaculities['CollegeID']; ?>)
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Program -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold">Program Name</label>
                                        <select class="form-select" id="ProgramDropdown" name="ProgramDropdown">
                                            <option value="">Select Program</option>
                                        </select>
                                    </div>

                                    <!-- Batch -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold text-primary">Batch <small
                                                class="text-muted">(Optional)</small></label>
                                        <select class="form-select" id="batch" name="batch">
                                            <option value="0">Select Batch</option>
                                            <?php foreach ($years as $batches) { ?>
                                            <option value="<?= $batches; ?>"><?= $batches; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Semester -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold text-primary">Semester <small
                                                class="text-muted">(Optional)</small></label>
                                        <select class="form-select" id="annualfeesem" name="annualfeesem">
                                            <option value="">Select Semester</option>
                                            <?php foreach ($semesters as $semester) { ?>
                                            <option value="<?= $semester; ?>"><?= $semester; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Fee Category -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold text-primary">Fee Category</label>
                                        <select class="form-select" id="annualfeecategory" name="annualfeecategory">
                                            <option value="">Select Fee Category</option>
                                            <?php foreach ($masterfeecategory as $feeCategories) { ?>
                                            <option value="<?= $feeCategories['ID']; ?>">
                                                <?= $feeCategories['FeeCategory']; ?> (<?= $feeCategories['ID']; ?>)
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Enrollment Status -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold text-primary">Enrollment Status</label>
                                        <select class="form-select" id="enrollment" name="enrollment">
                                            <option value="1">Active</option>
                                            <option value="0">Left</option>
                                        </select>
                                    </div>

                                    <!-- Academic Status -->
                                    <div class="col-lg-12">
                                        <label class="form-label fw-bold text-primary">Academic Status</label>
                                        <select class="form-select" id="passout" name="passout">
                                            <option value="0">Pursuing</option>
                                            <option value="1">Passout</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-danger me-2" onclick="daybookdata()">
                                    <i class="bi bi-file-earmark-excel"></i> Export
                                </button>
                                <button type="button" class="btn btn-success" onclick="displayledgerdata()">
                                    <i class="bi bi-eye"></i> Display
                                </button>
                            </div>
                            <!-- </div> -->

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="card-body">
                    <div class="card" id="annualfeeTableDiv">

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?php
include "footer.php";
?>

<script>
function displayledgerdata() {
 

    var passout = document.getElementById('passout').value;
    var feesession = document.getElementById('session').value;
    var collegeid = document.getElementById('CollegeID').value;
    var courseid = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    const semester = document.getElementById('annualfeesem').value;
    var annualfeecategory = document.getElementById('annualfeecategory').value;
    var enrollment = document.getElementById('enrollment').value;

    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = ''; // clear previous data
    // document.getElementById("balance").innerHTML = '';

 if (passout === '1' && feesession === '') 
    {
        showErrorMessage("Session is mandatory for Passout");
    
    }
    else
    {
   ShowLoader();
    fetch('action-g.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                code: 27, // code for display
                passout,
                feesession,
                collegeid,
                courseid,
                batch,
                semester,
                annualfeecategory,
                enrollment
            })
        })
        .then(response => response.text())
        .then(html => {

            tableContainer.innerHTML = html;
            const table =document.querySelector('#annualfeeTableDiv table');
            if (table) enableTableSorting(table);
            HideLoader(); // optional
        })
        .catch(err => {
            console.error('Error fetching daybook:', err);
            HideLoader();
        });
    }
}

function daybookdata() {
    var passout = document.getElementById('passout').value;
    var feesession = document.getElementById('session').value;
    // alert(feesession);
    var collegeid = document.getElementById('CollegeID').value;
    var courseid = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    const semester = document.getElementById('annualfeesem').value;
    var annualfeecategory = document.getElementById('annualfeecategory').value;
    var enrollment = document.getElementById('enrollment').value;
    var exportCode = 3;
    const params = new URLSearchParams({
        passout: passout,
        feesession: feesession,
        collegeid: collegeid,
        courseid: courseid,
        batch: batch,
        semester: semester,
        annualfeecategory: annualfeecategory,
        enrollment: enrollment,
        exportCode: exportCode
    });
    window.location.href = 'export.php?' + params.toString();
}
</script>