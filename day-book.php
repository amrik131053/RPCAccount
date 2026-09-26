<?php
include "header.php";
include "provider.php";
?>

<style>
    /* --- Compact .NET-style UI adjustments --- */
    body, .card, .form-control, .form-select, .btn {
        font-size: 13px !important;
    }

    .card-title {
        font-size: 15px;
        font-weight: 600;
    }

    .form-label {
        margin-bottom: 2px;
        font-weight: 500;
    }

    .card-body {
        padding: 10px 12px !important;
    }

    .card-footer {
        padding: 8px 10px !important;
        text-align: center;
    }

    .form-control, .form-select {
        padding: 4px 6px !important;
        height: 30px !important;
    }

    .btn {
        font-size: 13px !important;
        padding: 4px 10px !important;
        border-radius: 4px;
    }

    /* Table scroll area */
    #annualfeeTableDiv {
        max-height: 600px;
        overflow-y: auto;
        font-size: 13px;
    }

    /* Full-screen loader */
    #loader {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    #loader img {
        width: 80px;
        height: 80px;
    }
</style>

<!-- Full-screen loader -->
<div id="loader">
    <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading...">
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">

            <!-- Filter Column -->
            <div class="col-md-2">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Day Book Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label"><b>Start Date</b></label>
                                    <input type='date' class="form-control" value="<?= date('Y-m-d'); ?>" name='startdate' id='startdate'>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label"><b>End Date</b></label>
                                    <input type='date' class="form-control" value="<?= date('Y-m-d'); ?>" name='enddate' id='enddate'>
                                </div>

                                <label class="form-label text-danger">Mode of Payment</label>
                                <select class="form-select" name='modeofpayment' id='modeofpayment'>
                                    <option value="">Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Payment Gateway">Payment Gateway</option>
                                </select>

                                <label class="form-label text-danger mt-1">EmployeeID</label>
                                <select class="form-select" id='employeeid' name='employeeid'>
                                    <option value="">Employee ID</option>
                                    <?php foreach ($accountEmployees as $acEmployee) { ?>
                                        <option value="<?=$acEmployee['IDNo'];?>">
                                            <?=$acEmployee['Name'];?> (<?=$acEmployee['IDNo'];?>)
                                        </option>
                                    <?php } ?>
                                </select>

                                <label class="form-label text-danger mt-1">Session</label>
                                <select class="form-select" id='session' name='session'>
                                    <option value="">Session</option>
                                    <?php foreach ($accountsessions as $showsession) { ?>
                                        <option value="<?=$showsession['CurrentSession'];?>">
                                            <?=$showsession['CurrentSession'];?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <label class="form-label text-danger mt-1">Order by</label>
                                <select class="form-select" id='orderby' name='orderby'>
                                    <option value="ASC">Date Ascending</option>
                                    <option value="DESC">Date Descending</option>
                                </select>

                                <label class="form-label text-danger mt-1">Head</label>
                                <select class="form-select" id="ledgername" name='ledgername'>
                                    <option value="">Select Head</option>
                                    <?php foreach ($heads as $head) { ?>
                                        <option value="<?=$head['Id'];?>"><?=$head['Head'];?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                <button type="submit" class="btn btn-danger" onclick="daybookdata()">Export</button>
                                <button type="button" class="btn btn-success" onclick="daybookdataDisplay()">Display</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Column -->
            <div class="col-md-10">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Receipt Detail</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="annualfeeTableDiv"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="showSlipDataDiv"></div>
            <div class="modal-footer">
                <div class="col-lg-6 d-flex justify-content-end align-items-center">
                    <b style="margin-right:10px;color:red;">Auto Debit</b>
                    <input type="checkbox" id="autodebit" style="width:20px;height:20px;margin-right:20px;">
                    <a href="#" class="btn btn-primary" data-bs-dismiss="modal" onclick="generateSlip()">Generate</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";

?>
<script>
function daybookdata() {
    const startdate = document.getElementById('startdate').value.trim();
    const enddate = document.getElementById('enddate').value.trim();
    const modeofpayment = document.getElementById('modeofpayment').value.trim();
    const employeeid = document.getElementById('employeeid').value.trim();
    const session = document.getElementById('session').value.trim();
    const orderby = document.getElementById('orderby').value.trim();
    const ledgername = document.getElementById('ledgername').value.trim();
    if (!startdate || !enddate) {
        alert("Please select both Start Date and End Date.");
        return;
    }

    const params = new URLSearchParams({
        StartDate: startdate,
        EndDate: enddate,
        modeofpayment: modeofpayment,
        employeeid: employeeid,
        session: session,
        orderby: orderby,
        ledgername: ledgername,
        exportCode: 1
    });
    window.location.href = 'export.php?' + params.toString();
}

function daybookdataDisplay() {
    // showLoader(); // optional

    const startdate = document.getElementById('startdate').value;
    const enddate = document.getElementById('enddate').value;
    const modeofpayment = document.getElementById('modeofpayment').value;
    const employeeid = document.getElementById('employeeid').value;
    const session = document.getElementById('session').value;
    const orderby = document.getElementById('orderby').value;
    const ledgername = document.getElementById('ledgername').value;

    ShowLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = ''; // clear previous data
    // document.getElementById("balance").innerHTML = '';

    fetch('action-g.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            code: 9, // code for display
            startdate,
            enddate,
            modeofpayment,
            empid: employeeid,
            session,
            orderby,
            ledgername
        })
    })
    .then(response => response.text())
    .then(html => {
        HideLoader();
        tableContainer.innerHTML = html;
       
    })
    .catch(err => {
        HideLoader();
        console.error('Error fetching daybook:', err);
       
    });
}

function formatDate(dateString) {
  const d = new Date(dateString);
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  return `${day}/${month}/${year}`;
}
</script>
