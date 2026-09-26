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
                            <div class="card-header">
                                <h3 class="card-title">SubMenu</h3>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-label"><b>Role Name</b></div>
                                        <select id="RoleName" name="RoleName" class="form-control">
                                            <option value="">Select Role</option>
                                            <?php
    $query = "SELECT Id, RoleName FROM RoleMaster ORDER BY Id ASC";
    $get_pending_run=sqlsrv_query($conn,$query);
    while($get_row=sqlsrv_fetch_array($get_pending_run))
    {
    ?>
                                            <option value="<?=$get_row['Id'];?>"><?=$get_row['RoleName'];?>(<?=$get_row['Id'];?>)</option>
                                            <?php
}?>

                                        </select>

                                    </div>
                                    <div class="card-footer">

                                        <button class="btn btn-success" style="float:right;"
                                            onclick="SearchRole()">Search</button>
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
function SearchRole() {
    const roleId = document.getElementById('RoleName').value;
    if (!roleId) return;

    ShowLoader();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'action-g.php', true);
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

    xhr.send('code=4&roleId=' + encodeURIComponent(roleId));
}

document.getElementById('savePermissionsBtn').addEventListener('click', function() {
    const roleId = this.dataset.roleid;
    saveRolePermissions(roleId);
});

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
    formData.append('code', 8); // new handler code
    formData.append('roleId', roleId);
    formData.append('permissions', JSON.stringify(permissions));

    fetch('action-g.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        
        if (data == "1") {
            showSuccessMessage("Permissions saved successfully!");
        } else {
            showSuccessMessage("Failed to save permissions.");
        }
    })
    .catch(err => console.error(err));
}

</script>