<?php
include "header.php";
include "provider.php";
?>
<div class="page-body">
    <div class="container-xl">
    <div class="card">
        <div class="card-header">
                    <h4 class="card-title">Online Payments Razor Pay</h4>
                </div>
</div>
<br>
        <div class="row row-cards">
           
         
            <div class="col-md-2">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search here</h3>
                            </div>


                            <div class="card-body">

                                <div class="mb-3">
                                    <div class="form-label"><b>Roll No/IDNo/Reference No</b></div>
                                   <input type='text'  class="form-control" id='rollNo'>
                                </div>

                                <div class="mb-3">
                                    <div class="form-label"><b>Start Date</b></div>
                                   <input type='date' value="<?= date('Y-m-d'); ?>"  class="form-control" id='startdate'>
                                </div>
                                <div class="mb-3">
                                <div class="form-label"><b>End Date</b></div>
                                   <input type='date'  value="<?= date('Y-m-d'); ?>" class="form-control" id='enddate'>
                                </div>

                                <label class="form-label" style="color: brown">Fee Head</label>

                             <select class="form-select" id="paymenthead">
    <option value="">Select Head</option>

<?php
$qry  = "SELECT DISTINCT FeeType FROM PAYGKU_Response";
$stmt = sqlsrv_query($conn, $qry);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
?>
    <option value="<?= htmlspecialchars($row['FeeType']) ?>">
        <?= htmlspecialchars($row['FeeType']) ?>
    </option>
<?php
}
?>
</select>



 <label class="form-label" style="color: brown">Payment Status</label>
                                <Select class="form-select" id='paymentstatus' >
                                <option value="">Select </option>
                                     <option value="success">Success </option>
                                      <option value="failure">Failed </option>
                                       
                                    </Select>
                               
                             </div>
                               
 <div class="card-footer">
       <button class="btn btn-success" style="float:right;" onclick="onlinepaymentshow()">Display</button>
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
                                <h3 class="card-title">Receipt Detail</h3>

                                <div class="card-actions">

<div id="balance" style="float:right">
<a href="#" aria-label="Button">
       
       <button type="submit" class="btn btn-warning" onclick="syncfailedpayments()">
          
          Failed Synch
       </button>


         <button type="submit" class="btn btn-primary" onclick="syncpayments()">
          
           Deep Synch
       </button>

   </a>
</div>

&nbsp;

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

    </div>

<?php include 'footer.php';?>

    <script>

function  onlinepaymentshow() {
     
ShowLoader();
      var startdate = document.getElementById('startdate').value;
      var enddate = document.getElementById('enddate').value;
      var rollno = document.getElementById('rollNo').value;
      var paymentstatus = document.getElementById('paymentstatus').value;
      var paymenthead= document.getElementById('paymenthead').value;
$.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: '27.2',startdate:startdate,enddate:enddate,rollno:rollno,paymentstatus:paymentstatus,paymenthead:paymenthead
            
        },
        success: function(response) {
               HideLoader(); 
             document.getElementById("annualfeeTableDiv").innerHTML = response;

         
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            // alert("An error occurred while fetching data.");
        },
        complete: function() {

        }
    });


     
     } 

   
 

    function syncfee(id) {
    ShowLoader();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: '28.1',
            encryptedId: id
        },
        dataType: 'json',  // <-- tells jQuery to parse response as JSON
        success: function(data) {
              // works fine

            if (data.status === 'success') {
                showSuccessMessage(data.message);
            } else if (data.status === 'failure') {
                showErrorMessage(data.message);
            } else {
                console.log(data.status);
                alert('Unexpected response from server.');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            // Optionally alert user here
        },
        complete: function() {
             HideLoader(); 
        }
    });
}


 function  syncpayments()

        {   
    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;

    ShowLoader();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: '29.3',
            startdate: startdate,
            enddate: enddate
        },
        dataType: 'json', // jQuery automatically parses JSON
        success: function(data) {
            console.log(data); // View full response in console

            if (data.status === 'success') {
                showSuccessMessage(data.message);
            } else if (data.status === 'failure') {
                showErrorMessage(data.message);
            } else {
                console.log(data.status);
                alert('Unexpected response from server.');
            }
        },
       error: function(xhr, status, error) {
        console.error("AJAX Error:", error);           // logs error message
        console.log("Response text:", xhr.responseText); // logs raw server response
        alert("An error occurred while fetching data.");  // alert user
    },
        complete: function() {
            HideLoader();
        }
    });
        }


 function  syncfailedpayments()
        {   
    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;

    ShowLoader();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: '29.3',
            startdate: startdate,
            enddate: enddate
        },
        dataType: 'json', // jQuery automatically parses JSON
        success: function(data) {
            console.log(data); // View full response in console

            if (data.status === 'success') {
                showSuccessMessage(data.message);
            } else if (data.status === 'failure') {
                showErrorMessage(data.message);
            } else {
                console.log(data.status);
                alert('Unexpected response from server.');
            }
        },
       error: function(xhr, status, error) {
        console.error("AJAX Error:", error);           // logs error message
        console.log("Response text:", xhr.responseText); // logs raw server response
        alert("An error occurred while fetching data.");  // alert user
    },
        complete: function() {
            HideLoader();
        }
    });
        }


    </script>