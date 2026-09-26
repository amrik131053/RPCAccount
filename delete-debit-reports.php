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

.student-card img {
    height: 90px;
    width: 90px;
    padding-left:10px;
    box-shadow: 5 0 5px rgba(0, 0, 255, 0.15);
    background-color: #fff;
    display: block;
    margin: 0 auto;
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Delete Debit Reports</h3>
                            </div>


                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-label"><b>Start Date</b></div>
                                    <input type='date' value="<?= date('Y-m-d'); ?>" class="form-control" id='startdate'>
                                </div>
                                <div class="mb-3">
                                    <div class="form-label"><b>End Date</b></div>
                                    <input type='date' value="<?= date('Y-m-d'); ?>" class="form-control" id='enddate'>
                                </div>
                                <div class="mb-3">
                                <div class="form-label"><b>Status </b>(Optional)</div>
                                <select id='status' class="form-control">
                                <option value=''>All</option>
                                  <option value='0'>Pending</option>
                                  <option value='1'>Verified</option>
                                  <option value='2'>Approved</option>
                                  <option value='-1'>Deleted</option>
                                   </select>   
                                
                        
                                </div>

                            </div>

                            <div class="card-footer">
                                <button class="btn btn-danger" style="float:left;"
                                    onclick="daybookdata()">Export</button>

                                <button class="btn btn-success" style="float:right;"
                                    onclick="searchAllConcessionsReport()">Display</button>
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
                                <h3 class="card-title">Details</h3>

                                <div class="card-actions">


                                </div>
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
function searchAllConcessionsReport() {
    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;
    var status = document.getElementById('status').value;
    var newstatus = '0';

    if (!startdate) {
        showErrorMessage("Enter start date");
        return;
    }
    if (!enddate) {
        showErrorMessage("Enter end date");
        return;
    }
    ShowLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = ''; // clear previous data

    fetch('action-g.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            code: 29,        // indicates "all concessions report"
            startdate,
            enddate,
            status,
            newstatus
        })
    })
    .then(response => response.text())
    .then(html => {
        HideLoader();
        tableContainer.innerHTML = html; // inject HTML table
        const table =document.querySelector('#annualfeeTableDiv table');
        if (table) enableTableSorting(table);
    })
    .catch(err => {
        HideLoader();
        console.error('Error fetching daybook:', err);
    });
}


function daybookdata() {
    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;
    var status = document.getElementById('status').value;
    var newstatus = '0';
    if (!startdate) {
        showErrorMessage("Enter start date");
        return;
    }
    if (!enddate) {
        showErrorMessage("Enter end date");
        return;
    }
    var exportCode = 6;
    const params = new URLSearchParams({
        startdate:startdate,
        enddate:enddate,
        status:status,
        newstatus:newstatus,
        exportCode: exportCode
    });
    window.location.href = 'export.php?' + params.toString();
}

</script>