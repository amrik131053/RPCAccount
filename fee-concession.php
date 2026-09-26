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
                                <input type="text" class="form-control" placeholder="Search Student..." id='studentid' onkeydown="if(event.key === 'Enter') feedebit()">
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
    const id = document.getElementById('studentid').value;
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
            code: 1.3,
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
            alert("An error occurred while fetching data.");
        },
        complete: function() {
            HideLoader();

        }
    });
}


  function toggleButtons(checkbox) {
    // When checkbox is checked → show Concession button, hide Debit button
    if (checkbox.checked) {
      document.getElementById('debitBtn').style.display = 'none';
      document.getElementById('concessionBtn').style.display = 'inline-block';
    } else {
      // When unchecked → show Debit button, hide Concession button
      document.getElementById('debitBtn').style.display = 'inline-block';
      document.getElementById('concessionBtn').style.display = 'none';
    }
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

       const table = document.querySelector('#ledger_debit table');
                  enableTableSorting(table);
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

function CreateFeedebit() {
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitremarks = document.getElementById('debitremarks').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentid').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var debitdate = document.getElementById('debitdate').value;
  
    if (studentIdNo === '') {
        showErrorMessage("⚠️ Student ID is required");
        return;
    }
    if (debithead === '') {
        showErrorMessage("⚠️ Please select a fee head");
        return;
    }
    if (debitsession === '') {
        showErrorMessage("⚠️ Please select a session");
        return;
    }
    if (debitsem === '') {
        showErrorMessage("⚠️ Please select a semester");
        return;
    }
    if (debitparticulars === '') {
        showErrorMessage("⚠️ Please enter particulars");
        return;
    }
    if (debitdate === '') {
        showErrorMessage("⚠️ Please select a debit date");
        return;
    }
    if (debitfee == '') {
        showErrorMessage("⚠️ Enter a fee amount");
        return;
    }

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
                HideLoader();
                feedebit();
                // console.log(response);
                if (response == 1) {
                    showSuccessMessage('Fee debit successfully added');
                } else {
                    showErrorMessage("Try After some time");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                HideLoader();
                showErrorMessage("An error occurred while fetching data.");
            },
            complete: function() {
                HideLoader();
                
            }
        });
    
}

function debitheadchnage() {
    var selectedOptionText = document.getElementById("debithead").options[document.getElementById("debithead")
        .selectedIndex].text;
    let debitremarks = document.getElementById("debitremarks");
    let debitparticulars = document.getElementById("debitparticulars");
    debitremarks.value = selectedOptionText;
    debitparticulars.value = selectedOptionText;

}



function CreateFeeConcession() {
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitremarks = document.getElementById('debitremarks').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentid').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var debitdate = document.getElementById('debitdate').value;
  
    if (studentIdNo === '') {
        showErrorMessage("⚠️ Student ID is required");
        return;
    }
    if (debithead === '') {
        showErrorMessage("⚠️ Please select a fee head");
        return;
    }
    if (debitsession === '') {
        showErrorMessage("⚠️ Please select a session");
        return;
    }
    if (debitsem === '') {
        showErrorMessage("⚠️ Please select a semester");
        return;
    }
    if (debitparticulars === '') {
        showErrorMessage("⚠️ Please enter particulars");
        return;
    }
    if (debitdate === '') {
        showErrorMessage("⚠️ Please select a debit date");
        return;
    }
    if (debitfee == '') {
        showErrorMessage("⚠️ Enter a fee amount");
        return;
    }

        ShowLoader();
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: 11.1,
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
                HideLoader();
                feedebit();
                 console.log(response);
                if (response == 1) {
                    showSuccessMessage('Fee debit successfully added');
                } else {
                    showErrorMessage("Try After some time");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                HideLoader();
                showErrorMessage("An error occurred while fetching data.");
            },
            complete: function() {
                HideLoader();
                
            }
        });
    
}

</script>