<?php
include "header.php";
include "provider.php";
?>

<style>
#feedebitdiv {
    background: #f8f9fa;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 10px 14px;
    margin-top: 10px;
    font-family: "Segoe UI", Tahoma, sans-serif;
    font-size: 13px;
}

#feedebitdiv label {
    font-weight: 600;
    font-size: 12px;
    color: #222;
    margin-bottom: 3px;
}

#feedebitdiv .form-control,
#feedebitdiv .form-select,
#feedebitdiv textarea {
    font-size: 13px;
    padding: 4px 8px;
    height: 30px;
    border-radius: 4px;
}

#feedebitdiv textarea {
    height: 60px !important;
    resize: none;
}

#feedebitdiv .row {
    margin-bottom: 6px;
}

#feedebitdiv .col-lg-6 {
    padding-right: 8px;
    padding-left: 8px;
}

#feedebitdiv .card-footer {
    padding-top: 6px;
    text-align: right;
}

#feedebitbutton {
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 4px;
}


.student-card {
    font-family: "Segoe UI", Tahoma, sans-serif;
    font-size: 13px;
    color: #222;
}

.student-card .card {
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 8px;
    box-shadow: none;
}

.student-card .card-header {
    background-color: #eaeaea;
    border-bottom: 1px solid #ccc;
    padding: 6px 10px;
    font-weight: 600;
    font-size: 13px;
}

.student-card .card-body {
    padding: 6px 10px;
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

.student-card b {
    color: #000;
    font-weight: 600;
}

.student-card .list-group-item {
    border: none;
    padding: 4px 8px;
    font-size: 13px;
    background: transparent;
}

.student-card .list-group-item:nth-child(odd) {
    background-color: #f9f9f9;
}

.student-card .special-comment label,
.student-card .account-comment label {
    background-color: #d9534f;
    color: #fff;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 3px;
}

.student-card .special-comment b,
.student-card .account-comment b {
    color: #0056b3;
}

.student-card .text-center {
    text-align: center !important;
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

/* Buttons */
.btn-primary {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-primary:hover {
    background-color: #004080;
}

/* Input field */
#studentid {
    font-size: 13px;
}

/* Student details */
#studentdetail {
    background: #fff;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    min-height: 140px;
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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="col">
                                <input type="text" class="form-control" 
                                    placeholder="Search Student..." id='studentid' onkeydown="if(event.key === 'Enter') feedebit()">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" onclick="feedebit()">
                                    <svg class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                        <path d="M21 21l-6 -6" />
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </div>

                        <div class="card-body" id="studentdetail"></div>

                        <div class="card-body" id="ledger" style="display: none">
                            <div class="alert alert-warning text-center">
                                Uh oh, No Record Found
                            </div>
                        </div>
                        <div class="container" id="feedebitdiv" style="display:none">
                            <div class="row col-lg-12">
                                <div class="col-lg-6">
                                    <label class="form-label" style="color: brown">Head</label>
                                    <Select class="form-control " id='debithead' onchange="debitheadchnage()">
                                        <option value="">Select Head</option>
                                        <?php foreach ($heads as $head) { 

                                            if($head['Head']=='Refund')
                                                {
                                                    ?>
                                        <option value="<?= $head['Id']; ?>"><?= $head['Head']; ?></option>
                                        <?php }
                                        else
                                        {

                                        }
                                        } ?>

                                    </Select>
                                    <label class="form-label" style="color: brown">Semester</label>
                                    <Select class="form-control " id="debitsemester">
                                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php } ?>

                                    </Select>
                                    <label class="form-label" style="color: brown">Credit (Amount)</label>
                                    <input type="number" class="form-control " Name="feedebit" id='debitfee'>
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
                                    <label class="form-label" style="color: brown">Mode of Payment</label>
                                    <Select class="form-select" id='modeofpayment' onchange="modeofpaymnet(this.value)">
                                        <option value="Cash">Cash </option>
                                        <option value="Cheque">Cheque </option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </Select>
                                </div>

                                <div class="container" id="modepaymnetdiv" style="display:none">

                                    <div class="row col-lg-12">

                                        <div class="col-lg-6"> <label class="form-label"
                                                style="color: brown">Transaction
                                                Number</label>
                                            <input type="text" class="form-control" id='transactionid'>
                                            <label class="form-label" style="color: brown">Transaction Date </label>
                                            <input type="date" class="form-control" id='transactiondate'>
                                        </div>

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
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <br>
                            <div class="card-footer">

                                <div class="row col-lg-12">
                                    <div class="col-lg-8" style='text-align:right'>
                                      
                                       <!--  <button class="btn btn-primary btn-sm" id='feedebitbutton'
                                            
                                            >Create Entry</button> -->
                                    </div>
                                    <div class="col-lg-4"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <br>
                                     
                                        <button class="btn  btn-danger btn-sm" id='feedebitbutton1'
                                            onclick="GenerateReceipt()" style='text-align:right'>Create Refund
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>

                        </div>
                    </div>

                </div>

            </div>
            <!-- Right Section -->
            <div class="col-md-9">
                <div class="card-body">
                    <div class="card" id="ledger_debit">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
function feedebit() {
    const id = document.getElementById('studentid').value.trim();
    if (!id) {
        showErrorMessage("Enter Detail");
        return;
    }
    ShowLoader();
    Ledger_Status_debit(id);
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 1,
            id: id
        },
        success: function(response) {
            HideLoader();
            let feediv = document.getElementById("feedebitdiv");
        
            if (response != '1') {
                feediv.style.display = 'block';
             
                document.getElementById("studentdetail").innerHTML = response;
            } else {
                document.getElementById("studentdetail").innerHTML =
                "<div class='alert alert-warning' role='alert'>Uh oh, No record found</div>";
                feediv.style.display = 'none';
              
            }
        },
        error: function(xhr, status, error) {
            HideLoader();
            console.error("AJAX Error:", error);
            // alert("An error occurred while fetching data.");
        },
        complete: function() {
            HideLoader();

        }
    });
}

function Ledger_Status_debit(id) {
    if (!id) {
        showErrorMessage("Enter Detail");
        return;
    }
    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: '2',
            id: id
        },
        success: function(response) {
            HideLoader();
           document.getElementById("ledger_debit").innerHTML = response;

       const table = document.querySelector('#ledger_debit table');
                  enableTableSorting(table);
        },
        error: function(xhr, status, error) {
            HideLoader();
            console.error("AJAX Error:", error);
            // alert("An error occurred while fetching data.");
        },
        complete: function() {
            HideLoader();

        }
    });
}



function debitheadchnage() {
    var selectedOptionText = document.getElementById("debithead").options[document.getElementById("debithead")
        .selectedIndex].text;
    let debitremarks = document.getElementById("debitparticulars");
    debitremarks.value = selectedOptionText;

}

function modeofpaymnet(value) {

    if (value != 'Cash') {
        let modepaymnetdiv = document.getElementById("modepaymnetdiv");
        modepaymnetdiv.style.display = 'block';
    } else {
        let modepaymnetdiv = document.getElementById("modepaymnetdiv");
        modepaymnetdiv.style.display = 'none';
    }


}



function GenerateReceipt() {
    var IsOld = 0;
    var entryType = 0;
   
    let isChecked = 0;
    var flag = 0;

    var debithead = document.getElementById('debithead').value.trim();
    var debitsession = document.getElementById('debitsession').value.trim();
    var debitsem = document.getElementById('debitsemester').value.trim();
    var debitfee = document.getElementById('debitfee').value.trim();
    var studentIdNo = document.getElementById('studentid').value.trim();
    var debitparticulars = document.getElementById('debitparticulars').value.trim();
    var modeofpayment = document.getElementById('modeofpayment').value.trim();
    var transactiondate = document.getElementById('transactiondate').value.trim();
    var nameofbank = document.getElementById('nameofbank').value.trim();
    var transactionid = document.getElementById('transactionid').value.trim();

// Field-specific validation
if (!debithead) return HideLoader() & showErrorMessage("Please select Fee Head");
if (!debitsession) return HideLoader() & showErrorMessage("Please select Session");
if (!debitsem) return HideLoader() & showErrorMessage("Please select Semester");
if (!debitfee || isNaN(debitfee) || debitfee < 0) return HideLoader() & showErrorMessage("Please enter valid Amount");
if (!studentIdNo) return HideLoader() & showErrorMessage("Please enter Student Roll No");
if (!debitparticulars) return HideLoader() & showErrorMessage("Please enter Particulars");

if (modeofpayment !== 'Cash') {
    if (!transactiondate) return HideLoader() & showErrorMessage("Please enter Transaction Date");
    if (!nameofbank) return HideLoader() & showErrorMessage("Please enter Bank Name");
    if (!transactionid) return HideLoader() & showErrorMessage("Please enter Transaction ID");
}

   

        ShowLoader();
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 31,
                debitparticulars: debitparticulars,
                debithead: debithead,
                debitsession: debitsession,
                debitsem: debitsem,
                debitfee: debitfee,
                studentid: studentIdNo,
                modeofpayment: modeofpayment,
                nameofbank: nameofbank,
                transactionid: transactionid,
                transactiondate: transactiondate,
                isChecked: isChecked,
                entryType: entryType,
                IsOld: IsOld
            },
            success: function(response) {
                HideLoader();
                try {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    feedebit();
                    console.log(response);
                    if (response.status == '1') {
                        showSuccessMessage('Successfully Generated');
                        $('#debitfee').val('');
                        $('#debithead').val('');
                    } 
                    else if (response.status == '3') {
                        showSuccessMessage('Successfully Generated');
                        $('#debitfee').val('');
                        $('#debithead').val('');
                        window.open(
                            'print_receipt_refund.php?SlipID=' + encodeURIComponent(response.ReceiptNo) +
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

</script>