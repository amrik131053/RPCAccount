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

/* #feedebitbutton {
    font-size: 13px;
    padding: 4px 12px;
    border-radius: 4px;
} */


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
            code: 1.1,
            id: id
        },
        success: function(response) {
            HideLoader();
           
            if (response != '1') {
                
                document.getElementById("studentdetail").innerHTML = response;
            } else {
                document.getElementById("studentdetail").innerHTML =
                "<div class='alert alert-warning' role='alert'>Uh oh, No record found</div>";
                
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
            //   console.log(response);
            document.getElementById("ledger_debit").innerHTML = response;
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

function CreateFeedebit() {

    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitremarks = document.getElementById('debitremarks').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentid').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var debitdate = document.getElementById('debitdate').value;
      // Field-specific validation
      if (!debithead) return showErrorMessage("Please select Fee Head");
    if (!debitsession) return showErrorMessage("Please select Session");
    if (!debitsem) return showErrorMessage("Please select Semester");
    if (!debitfee || isNaN(debitfee) || debitfee < 0) return showErrorMessage("Please enter a valid Amount");
    if (!studentIdNo) return showErrorMessage("Please enter Student Roll No");
    if (!debitparticulars) return showErrorMessage("Please enter Particulars");
    if (!debitdate) return showErrorMessage("Please select Date");

    ShowLoader(); 
           
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 11,
                particulars: debitparticulars,
                debithead: debithead,
                debitsession: debitsession,
                debitsemester: debitsem,
                debitremarks: debitremarks,
                debitfee: debitfee,
                studentid: studentIdNo,
                debitdate: debitdate
            },
            success: function(response) {
                feedebit();
                HideLoader();
                // console.log(response);
                if (response == 1) {
                    showSuccessMessage('Fee debit successfully added');
                } else {
                    showErrorMessage("Try After some time");
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

// function debitheadchnage() {
//     var selectedOptionText = document.getElementById("debithead").options[document.getElementById("debithead")
//         .selectedIndex].text;
//     let debitremarks = document.getElementById("debitparticulars");
//     debitremarks.value = selectedOptionText;

// }

function debitheadchnage() {

    let headSelect = document.getElementById("debithead");
    let selectedText = headSelect.options[headSelect.selectedIndex].text;

    let routeBox = document.getElementById("route_box");
    let spotBox = document.getElementById("spot_box");

let debitremarks = document.getElementById("debitparticulars");
     debitremarks.value = selectedText;

    if(selectedText === "Bus Fee"){
        routeBox.style.display = "block";
        spotBox.style.display = "block";
    }else{
        routeBox.style.display = "none";
        spotBox.style.display = "none";
    }

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
function loadSpot(route) 
{  
var code='33'; 
$.ajax({
url:'action.php',
data:{route:route,code:code},
type:'POST',
success:function(data){
if(data != "")
{
 $("#spot_id").html("");
$("#spot_id").html(data);
}
}
});
}
function fetchtransportamount(spot) 
{  
var code='33.1'; 
$.ajax({
url:'action.php',
data:{spot:spot,code:code},
type:'POST',
success:function(data){
    console.log(data);
if(data != "")
{
let debitfee = document.getElementById("debitfee");
     debitfee.value = data;
}
}
});
}


function CreateReceipt() {
    var flag = 0;
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentid').value.trim();
    var debitparticulars = document.getElementById('debitparticulars').value;
    var modeofpayment = document.getElementById('modeofpayment').value;
    var transactiondate = document.getElementById('transactiondate').value;
    var nameofbank = document.getElementById('nameofbank').value;
    var transactionid = document.getElementById('transactionid').value;
    var route_id = document.getElementById('route_id').value;
    var spot_id = document.getElementById('spot_id').value;
      // Common validations
      if (!debithead) return showErrorMessage("Please select Fee Head");
    if (!debitsession) return showErrorMessage("Please select Session");
    if (!debitsem) return showErrorMessage("Please select Semester");
    if (!debitfee) return showErrorMessage("Please enter Amount");
    if (!studentIdNo) return showErrorMessage("Please enter Student Roll No");
    if (!debitparticulars) return showErrorMessage("Please enter Particulars");

    // Additional validation for non-cash payments
    if (modeofpayment !== 'Cash') {
        if (!transactiondate) return showErrorMessage("Please enter Transaction Date");
        if (!nameofbank) return showErrorMessage("Please enter Bank Name");
        if (!transactionid) return showErrorMessage("Please enter Transaction ID");
    }


if (debithead=='3'){

        if (!route_id) return showErrorMessage("Please select route name ");
        if (!spot_id) return showErrorMessage("Please select spot name");
        
    }



        ShowLoader();
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 19,
                debitparticulars: debitparticulars,
                debithead: debithead,
                debitsession: debitsession,
                debitsem: debitsem,
                debitfee: debitfee,
                studentid: studentIdNo,
                modeofpayment: modeofpayment,
                nameofbank: nameofbank,
                transactionid: transactionid,
                transactiondate: transactiondate,route_id:route_id,spot_id:spot_id
            },
            success: function(response) {
                console.log(response);
                HideLoader();
                try {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    feedebit();
                     console.log(response);
                    if (response.status == 1) {
                        showSuccessMessage('Successfully Created');
                    document.getElementById('debitfee').value = null;
                    document.getElementById('debithead').value = null;
                } else {
                    showErrorMessage("Try After some time");
                }
            }
            catch (e) {
                console.error("JSON Parse Error:", e, response);
                showErrorMessage("Unexpected response format");
            }
        },
        error: function(xhr, status, error) {
                HideLoader();
                // console.error("AJAX Error:", error);
                // alert("An error occurred while fetching data.");
            },
            complete: function() {
                HideLoader();
                
            }
        });
}

function GenerateReceipt() {
    var IsOld = 0;
    var entryType = 0;
    let checkbox = document.getElementById('autodebit');
    let isChecked = checkbox.checked ? 1 : 0;
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
   var route_id = document.getElementById('route_id').value;
    var spot_id = document.getElementById('spot_id').value;
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

if (debithead=='3'){

        if (!route_id) return showErrorMessage("Please select route name ");
        if (!spot_id) return showErrorMessage("Please select spot name");
        
    }

    

        ShowLoader();
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 19,
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
                IsOld: IsOld,route_id:route_id,spot_id:spot_id
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

</script>