<?php
include "header.php";
?>
<style>
.permission-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
}
</style>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Search Student..."
                                        id='studentid' onkeydown="if(event.key === 'Enter') searchUsers()">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" onclick="searchUsers()">
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
            </div>
            <div class="col-md-8">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Menu</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" id="annualfeeTableDiv">
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
<?php
include "footer.php";
?>
<script>
function searchUsers() {
    const id = document.getElementById('studentid').value;
    if (!id) {
        showErrorMessage("Enter Detail");
        return;
    }
    ShowLoader();
    $.ajax({
        url: 'action-users.php',
        type: 'POST',
        data: {
            code: 1,
            id: id
        },
        success: function(response) {
            console.log(response);
            HideLoader();

            if (response != '1') {
                document.getElementById("studentdetail").innerHTML = response;
                // SearchRole();
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

function submitRole() {
    var useid = document.getElementById('useid').value;
    var RoleName = document.getElementById('RoleName').value;
  
        ShowLoader();
        $.ajax({
            url: 'action-users.php',
            type: 'POST',
            data: {
                code: 2,
                useid: useid,
                RoleName: RoleName    
            },
            success: function(response) {
                // console.log(response);
                HideLoader();
                if (response == 1) {
                    searchUsers();
                    showSuccessMessage('Successfully');
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
function SearchRole() {
    const roleId = document.getElementById('useid').value;
    if (!roleId) return;

    ShowLoader();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'action-users.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            HideLoader();
            if (xhr.status === 200) {
                // Insert the PHP-generated HTML directly
                document.getElementById('annualfeeTableDiv').innerHTML = xhr.responseText;
            } else {
                showSuccessMessage('Error loading data.');
            }
        }
    };

    xhr.send('code=3&roleId=' + encodeURIComponent(roleId));
}

function saveRolePermissions(roleId) {
    const container = document.getElementById('permissionsContainer');
    if (!container) {
        console.error("Permissions container not found!");
        return;
    }

    const permissions = [];

    container.querySelectorAll('tbody tr').forEach(row => {
        const routeId = row.dataset.routeId;
        if (!routeId) return;

        const checkboxes = row.querySelectorAll('input[type="checkbox"]');
        const read = checkboxes[0]?.checked ? 1 : 0;
        const write = checkboxes[1]?.checked ? 1 : 0;
        const del = checkboxes[2]?.checked ? 1 : 0;

        permissions.push({
            routeId,
            read,
            write,
            delete: del
        });
    });

    const formData = new FormData();
    formData.append('code', 4); // new handler code
    formData.append('roleId', roleId);
    formData.append('permissions', JSON.stringify(permissions));
    ShowLoader();
    fetch('action-users.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        HideLoader();
        if (data == "1") {
            showSuccessMessage("Permissions saved successfully!");
        } else {
            showSuccessMessage("Failed to save permissions.");
        }
    })
    .catch(err => console.error(err));
}
</script>