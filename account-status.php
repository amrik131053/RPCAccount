<?php
include "header.php";
?>
<style>
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
.col-md-3 {
    position: sticky;
    top: 10px;
    align-self: flex-start;
    height: fit-content;
    z-index: 10;
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
                                    placeholder="Search Student..." id='studentid' onkeydown="if(event.key === 'Enter') account_status()">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" onclick="account_status()">
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
                    </div>
                </div>
            </div>
           
            <div class="col-md-9">
                <div class="card-body">  
                        <div id="ledger">
                        </div>
                 </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>