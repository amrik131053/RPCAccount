<?php
include "header.php";
include "provider.php";
?>
<div class="page-body">
    <div class="container-xl">

        <div class="row row-cards">

            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="col-md-4">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create Head</h3>
                            </div>


                            <div class="card-body">


                                <div class="col-12">

                                    <div class="mb-3">
                                        <div class="form-label"><b>Head Name</b></div>
                                        <input type="text" class="form-control" name="headName" id="headName">
                                    </div>
                                    <div class="card-footer">

                                        <button class="btn btn-success" style="float:right;"
                                            onclick="submitNewHeadClick()">Submit</button>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Heads</h3>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive" id="feeCategoryTableDiv">

                                </div>


                                <div class="card-footer">


                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
<script>
window.onload = function() {
    displayAllHeads();
};

function displayAllHeads() {
    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 15,

        },
        success: function(response) {

            HideLoader();
            document.getElementById("feeCategoryTableDiv").innerHTML = response;



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



function editmasterhead(ID, status) {

    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 16,
            ID: ID,
            status: status

        },
        success: function(response) {
            HideLoader();
            if (response == 0) {

                showSuccessMessage("Updated successfully.");
                displayAllHeads();
            } else

            {
                showErrorMessage('No changes made or record not found.');

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

function editmasterheadstudent(ID, status) {
    ShowLoader();
    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 17,
            ID: ID,
            status: status

        },
        success: function(response) {
            HideLoader();
            if (response == 0) {

                showSuccessMessage("Updated successfully.");
                displayAllHeads();
            } else

            {
                showErrorMessage('No changes made or record not found.');

            }



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


function submitNewHeadClick() {
  var headName = document.getElementById('headName').value;
  if(headName!=''){
    ShowLoader();

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: {
            code: 18,
            headName: headName

        },
        success: function(response) {
            HideLoader();
            if (response == 1) {

                showSuccessMessage("Updated successfully.");
                displayAllHeads();
            } else

            {
                showErrorMessage('No changes made or record not found.');
                
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
        else{
          showErrorMessage('Please enter head name');
          
  }

}
</script>
<?php include "footer.php"; ?>