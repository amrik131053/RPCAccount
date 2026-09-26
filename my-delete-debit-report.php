<?php
include "header.php";
?>
<style>
/* Tables */
.table {
    font-size: 13px;
    margin-bottom: 0;
}

.table-fixed-header {
    max-height: 500px;
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
        <div class="card">
            <div class="card-header">
                <div class="row align-items-end g-3">
                    <div class="col-md-3">
                        <label class="form-label mb-1"><b>From</b></label>
                        <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm"
                            id="startdate">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-1"><b>To</b></label>
                        <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm"
                            id="enddate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">Status (Optional)</label>
                        <select id='status' class="form-control form-control-sm">
                            <option value=''>All</option>
                            <option value='0'>Pending</option>
                            <option value='1'>Verified</option>
                            <option value='2'>Approved</option>
                            <option value='-1'>Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-success btn-sm mt-3" onclick="alldebitdelete()">Display</button>
                    </div>
                </div>
                <div class="card-actions">


                    &nbsp;
                </div>
            </div>
        </div>
        &nbsp;
        <div class="row row-cards">
            <div class="col-md-12">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Delete Debit Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="annualfeeTableDiv">
                                </div>
                            </div>
                        </div>
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
function alldebitdelete() {

    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;
    var status = document.getElementById('status').value;
    var newstatus = '0'
    if (status != '') {
        if (startdate != '' && enddate != '') {
            newstatus = '1'
        }
    } else {
        newstatus = '1';
    }

    if (startdate != '' && enddate != '') {
        ShowLoader();

        if (newstatus > 0) {

            $.ajax({
                url: 'action.php',
                type: 'POST',
                data: {
                    code: 19,
                    startdate: startdate,
                    enddate: enddate,
                    newstatus: newstatus

                },
                success: function(response) {
                    HideLoader();
                    document.getElementById("annualfeeTableDiv").innerHTML = response;



                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    HideLoader();
                    alert("An error occurred while fetching data.");
                },
                complete: function() {
                    HideLoader();
                }
            });
        }
    } else {
        HideLoader();
        showErrorMessage('No changes made or record not found.');
    }


}
</script>