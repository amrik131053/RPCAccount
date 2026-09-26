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
                        <input type="date"  value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm" id="startdate">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-1"><b>To</b></label>
                        <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm" id="enddate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">Mode of Payment</label>
                        <Select class="form-select form-select-sm" id='modeofpayment'>
                            <option value="">Select </option>
                            <option value="Cash">Cash </option>
                            <option value="Cheque">Cheque </option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Payment Gateway">Payment Gateway</option>
                        </Select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-success btn-sm mt-3" onclick="allSlipsShow()">Display</button>
                    </div>
                </div>
                <div class="card-actions">

                    <div style="float:right">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="displayreceiptdetail()">
                            Pending
                        </button>
                    </div>
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
                                <h3 class="card-title">My Receipt Detail</h3>
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
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="showSlipDataDiv">

            </div>
            <div class="modal-footer">
                <div class="col-lg-6" style="display: flex; justify-content: flex-end; align-items: center;">
                    <b style="margin-right: 10px; color: red;">Auto Debit</b>
                    <input type="checkbox" id="autodebit" style="width: 30px; height: 30px; margin-right: 20px;">
                    <a href="#" class="btn btn-primary" style="margin-left: auto;" data-bs-dismiss="modal"
                        onclick="generateSlip()">Generate</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "footer.php";
?>
<script>
function allSlipsShow() {
    ShowLoader();
    const startdate = document.getElementById('startdate').value;
    const enddate = document.getElementById('enddate').value;
    const modeofpayment = document.getElementById('modeofpayment').value;

    const formData = new FormData();
    formData.append('code', '14');
    formData.append('startdate', startdate);
    formData.append('enddate', enddate);
    formData.append('modeofpayment', modeofpayment);

    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            HideLoader();
            document.getElementById('annualfeeTableDiv').innerHTML = html;
        })
        .catch(error => {
            HideLoader();
            console.error('Error:', error);
        });
}

function displayreceiptdetail() {
    ShowLoader();
    const startdate = document.getElementById('startdate').value;
    const enddate = document.getElementById('enddate').value;

    const formData = new FormData();
    formData.append('code', '15');
    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            HideLoader();
            document.getElementById('annualfeeTableDiv').innerHTML = html;
        })
        .catch(error => {
            HideLoader();
            console.error('Error:', error);
            showErrorMessage('Error connecting to server');
        });
}
 

function show_receiptentry(id) {
    ShowLoader();
    fetch('action-g.php', {
        method: 'POST',
        body: new URLSearchParams({ code: 16, receiptid: id })
    })
    .then(res => res.text())
    .then(html => {
        HideLoader();
        document.getElementById('showSlipDataDiv').innerHTML = html;
    })
    .catch(err => {
        HideLoader();
        console.error(err);
    });
}


function generateSlip() {
    let checkbox = document.getElementById('autodebit');
    let isChecked = checkbox.checked ? 1 : 0;
    let slipNumber = document.getElementById('slipNumber').value;
    ShowLoader();
    $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 17,
            slipNumber: slipNumber,
            isChecked: isChecked
            },
         success: function(response) {
                HideLoader();
                try {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    displayreceiptdetail();
                    console.log(response);
                    if (response.status == '1') {
                        showSuccessMessage('Successfully Generated');
                       // $('#debitfee').val('');
                       // $('#debithead').val('');
                    } 
                    else if (response.status == '3') {
                        showSuccessMessage('Successfully Generated');
                      //  $('#debitfee').val('');
                       // $('#debithead').val('');
                        window.open(
                            'print_receipt.php?SlipID=' + encodeURIComponent(response.ReceiptNo) +
                            '&ledgerName=' + encodeURIComponent(response.LedgerName) +
                            '&session=' + encodeURIComponent(response.session) +
                            '&IDNo=' + encodeURIComponent(response.IDNo),
                            '_blank'
                        );
                    } 
                    else {
                        showErrorMessage("Try After some time");
                    }
                } catch (e) {
                    console.error("JSON Parse Error:", e, response);
                    showErrorMessage("Unexpected response format");
                }
            },
            error: function(xhr, status, error) {
                HideLoader();
                console.error("AJAX Error:", error);
                showErrorMessage("An error occurred while processing your request.");
            },
            complete: function() {
                HideLoader();
                // Any post-processing after success/error
            }
        });
}

function delete_receiptentry(id) {
    ShowLoader();
    fetch('action-g.php', {
        method: 'POST',
        body: new URLSearchParams({ code: 18, slipNumber: id })
    })
    .then(res => res.text())
    .then(status => {
        HideLoader();
        if(status === "1") {
            showSuccessMessage("Slip deleted successfully");
            displayreceiptdetail(); // Refresh table
        } else if(status === "0") {
            showErrorMessage("Failed to update record status");
        } else if(status === "2") {
            showErrorMessage("Failed to insert logbook entry");
        } else {
            showErrorMessage("Unknown error");
        }
    })
    .catch(err => {
        HideLoader();
        console.error(err);
        showErrorMessage("Server error!");
    });
}


</script>