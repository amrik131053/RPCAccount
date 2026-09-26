<?php
include "header.php";
?>
<div class="page-body">
    <div class="container-xl">
        

        <div class="row row-cards">
            

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                       <h4 class="card-title">Account Status</h4> 
      </div>
            <br>                  
                          <div class="card-body p-0">
                         <div class="row">
  <div class="col-1"></div>
  <div class="col">
    <input type="text" class="form-control" placeholder="Search Student here" value="" id="studentid" onkeydown="if(event.key === 'Enter') account_comment()">
  </div>
  <div class="col-4">
    <button class="btn btn-primary" onclick="account_comment()">
      <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
        <path d="M21 21l-6 -6" />
      </svg>
      Search
    </button>
  </div>
</div>
                        <br>
              
                    <div class="card-body p-0" id="studentdetail">


                    </div>
                   
                </div>
            </div></div>
            <div class="col-md-8">
                <div class="card-body p-0">
                        <div class="table-responsive" id="annualfeeTableDiv" style="scroll-behavior: auto;height:800px">
                <div id="ledger">
                <div class="card">
                    <div class="card-header">

                        <h3 class="card-title">
                            <div id="download">
                                
                            </div>
                        </h3>
                        <div class="card-actions">

                            <div id="balance" style="float:right">
     </div>
                            &nbsp;

                        </div>
                    </div></div>
                  </div>
                    </div>
            </div>

        </div>



    </div>
</div>
</div>

<?php include "footer.php"; ?>