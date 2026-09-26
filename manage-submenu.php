<?php
include "header.php";
?>

<div class="page-body">
    <div class="container-xl">

        <div class="row row-cards">

            <div class="col-md-4">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Menu</h3>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-label"><b>Menu Name</b></div>

                                        <Select class="form-select" id='MenuName'>
                                            <option value="">Select Menu</option>

                                            <?php
    $query = "SELECT Id, MainMenuName FROM MainMenuAccounts ORDER BY Id ASC";
    $get_pending_run=sqlsrv_query($conn,$query);
    while($get_row=sqlsrv_fetch_array($get_pending_run))
    {
    ?>
                                            <option value="<?=$get_row['Id'];?>"><?=$get_row['MainMenuName'];?></option>
                                            <?php
}?>


                                        </Select>
                                    </div>
                                    <div class="card-footer">

                                        <button class="btn btn-success" style="float:right;"
                                            onclick="SearchMenu()">Search</button>
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

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="annualfeeTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Main Menu</th>
                                                <th>Sub Menu</th>
                                                <th>Route</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="annualfeeTableDiv">
                                            <!-- rows will be inserted here by PHP -->
                                        </tbody>
                                    </table>

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
function SearchMenu() {
    let MenuName = document.getElementById('MenuName').value;
    ShowLoader();
    let formData = new FormData();
    formData.append('code', 5);
    formData.append('MainMenuId', MenuName);

    // Get all menu options
    const allMenuOptions = document.getElementById('MenuName').innerHTML;

    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            HideLoader();
            const tableContainer = document.getElementById('annualfeeTableDiv');

            let table = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>SubMenu Name</th>
                                <th>Route Name</th>
                                <th colspan='2'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${html} <!-- rows returned from PHP -->
                            <tr>
                                <td>New</td>
                                <td>
                                    <select class="form-select" id="newMainMenu">
                                        <option value="">Select Menu</option>
                                        ${allMenuOptions}
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" id="newSubMenu" placeholder="Sub Menu"></td>
                                <td><input type="text" class="form-control" id="newRoute" placeholder="Route"></td>
                                <td><button class="btn btn-success btn-sm" onclick="saveNewMenu()">Save</button></td>
                            </tr>
                        </tbody>
                    </table>`;

            tableContainer.innerHTML = table;
        })
        .catch(err => {
            console.error('Error:', err);
            HideLoader();
        });
}



function openEditModalSubMenu(id, MainMenuId, PageName, MainMenuName, Route) {
    document.getElementById('editSubMenuId').value = id;
    document.getElementById('editMainMenu').value = MainMenuId;
    document.getElementById('editSubMenu').value = PageName;
    document.getElementById('editRouteName').value = Route;

    // Open Bootstrap modal programmatically (if not using data-bs-toggle)
    const modal = new bootstrap.Modal(document.getElementById('modal-edit-menu'));
    modal.show();
}

// Save changes to backend
function saveSubMenuUpdate() {
    let editMenuId = document.getElementById('editMainMenu').value;
    let editSubMenuId = document.getElementById('editSubMenuId').value;
    let editSubMenu = document.getElementById('editSubMenu').value;
    let editRouteName = document.getElementById('editRouteName').value;

    if (!editMenuId || !editSubMenu || !editRouteName) {
        showSuccessMessage("All fields are required!");
        return;
    }

    ShowLoader();
    let formData = new FormData();
    formData.append('code', 6); // code=6 for edit
    formData.append('routeId', editSubMenuId);
    formData.append('MainMenuId', editMenuId);
    formData.append('PageName', editSubMenu);
    formData.append('routeName', editRouteName);

    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text()) // use text() instead of json()
        .then(response => {
            HideLoader();
            if (response.trim() === "1") {
                showSuccessMessage("Menu updated successfully!");
            } else {
                showSuccessMessage("Failed to update menu!");
            }
            SearchMenu(); // refresh table
            bootstrap.Modal.getInstance(document.getElementById('modal-edit-menu')).hide();
        })
        .catch(err => console.error(err));
}


function saveNewMenu() {
    let menuId = document.getElementById('newMainMenu').value;
    let subMenu = document.getElementById('newSubMenu').value;
    let route = document.getElementById('newRoute').value;

    if (!menuId || !subMenu || !route) {
        showSuccessMessage("All fields are required!");
        return;
    }
    ShowLoader();

    // Prepare form data
    let formData = new FormData();
    formData.append('code', 7); // code=7 for adding new menu
    formData.append('MainMenuId', menuId);
    formData.append('PageName', subMenu);
    formData.append('Route', route);

    fetch('action-g.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // expecting JSON from PHP
        .then(data => {
            HideLoader();
            if (data.status === 'success') {
                showSuccessMessage(data.message);
                SearchMenu(); // reload the table after adding
                // Clear input fields
                document.getElementById('newMainMenu').value = '';
                document.getElementById('newSubMenu').value = '';
                document.getElementById('newRoute').value = '';
            } else {
                showSuccessMessage(data.message || 'Error adding menu');
            }
        })
        .catch(err => {
            HideLoader();

            console.error('Error:', err);
            showSuccessMessage('Unable to add menu');
        });
}
</script>
<!-- Edit Sub Menu Modal -->
<div class="modal fade" id="modal-edit-menu" tabindex="-1" aria-labelledby="modalEditMenuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditMenuLabel">Edit Sub Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSubMenuForm">
                    <input type="hidden" id="editSubMenuId">

                    <div class="mb-3">
                        <label for="editMainMenu" class="form-label">Main Menu</label>
                        <select class="form-select" id="editMainMenu">
                            <option value="">Select Main Menu</option>
                            <!-- You can dynamically fill main menu options using JS/PHP -->
                            <?php
              $query = "SELECT Id, MainMenuName FROM MainMenuAccounts ORDER BY Id ASC";
              $stmt = sqlsrv_query($conn, $query);
              while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  echo "<option value='{$row['Id']}'>{$row['MainMenuName']}</option>";
              }
              ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editSubMenu" class="form-label">Sub Menu Name</label>
                        <input type="text" class="form-control" id="editSubMenu" placeholder="Sub Menu Name">
                    </div>

                    <div class="mb-3">
                        <label for="editRouteName" class="form-label">Route Name</label>
                        <input type="text" class="form-control" id="editRouteName" placeholder="Route Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSubMenuUpdate()">Save Changes</button>
            </div>
        </div>
    </div>
</div>