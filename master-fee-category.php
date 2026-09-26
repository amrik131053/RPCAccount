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
                                <h3 class="card-title">Create Category</h3>
                            </div>


                            <div class="card-body">


                                <div class="col-12">

                                    <div class="mb-3">
                                        <div class="form-label"><b>Category Name</b></div>
                                        <input type="text" class="form-control" name="categoryName" id="categoryName">
                                    </div>
                                    <div class="card-footer">

                                        <button class="btn btn-success" style="float:right;"
                                            onclick="submitNewCategoryClick()">Submit</button>
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
                                <h3 class="card-title">All Categories</h3>
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
<?php include "footer.php"; ?>

<script>
    window.onload = function() {
        displayAllCategory();
    };


    function displayAllCategory() {
        ShowLoader();
       $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 12,
      
    },
    success: function (response) {

    HideLoader();
    document.getElementById("feeCategoryTableDiv").innerHTML = response;
    
    
    
},
error: function (xhr, status, error) {
    console.error("AJAX Error:", error);
    alert("An error occurred while fetching data.");
},
complete: function () {
        HideLoader();
      
    }
  });
   }

function editfeecategory(ID,status) {
    ShowLoader();
       $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 13,ID:ID,Status:status
      
    },
    success: function (response) {
HideLoader();
if (response == 0) {
    
    showSuccessMessage("Updated successfully.");
    displayAllCategory();
} else 

{
    showErrorMessage('No changes made or record not found.');
    
}



},
error: function (xhr, status, error) {
    HideLoader();
    console.error("AJAX Error:", error);
    alert("An error occurred while fetching data.");
},
complete: function () {
    
    HideLoader();
    }
  });
  }




  function submitNewCategoryClick(){
    var categoryName = document.getElementById('categoryName').value;
    
    if(categoryName!=''){
     $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 14,newfeecategory:categoryName
      
    },
    success: function (response) {

     if (response == 1) {

        showSuccessMessage("Fee Category Adde successfully.");
        displayAllCategory();
      } else 
        
        {
    showErrorMessage('No changes made or record not found.');

        }
            
 
         
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      alert("An error occurred while fetching data.");
    },
    complete: function () {
      
    }
  });
    }
    else{
        showErrorMessage('Please enter category name');

    }
  }


</script>
