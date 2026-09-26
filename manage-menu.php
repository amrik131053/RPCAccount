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
                                <h3 class="card-title">Manage Menu</h3>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-label"><b>Menu Name</b></div>
                                        <input type="text" class="form-control" name="MenuName" id="MenuName">
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-success" style="float:right;"
                                            onclick="submitNewMenu()">1Submit</button>
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
loadAllMenuRecord();

function submitNewMenu() {
    var MenuName = document.getElementById('MenuName').value;
    var code = 1;
    if (MenuName != '') {
        ShowLoader();

        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: code,
                newMenu: MenuName
            },
            success: function(response) {
                HideLoader();
                if (response.trim() == '1') {
                    showSuccessMessage('New Main Menu Added');
                    loadAllMenuRecord();
                } else {
                    showErrorMessage('Failed to add menu');
                }
            },
            error: function() {
                HideLoader();
                showErrorMessage('Server error occurred');
            }
        });
    } else {
        HideLoader();
        showErrorMessage('Please enter menu name');
    }
}


function loadAllMenuRecord() {
    var code = 2;
     ShowLoader();

    $.ajax({
        url: 'action-g.php',
        type: 'POST',
        data: {
            code: code
        },
        success: function(response) {
            HideLoader();
            document.getElementById('annualfeeTableDiv').innerHTML = '';

            try {
                var feedetailData = JSON.parse(response);
            } catch (e) {
                document.getElementById('annualfeeTableDiv').innerHTML =
                    '<p class="text-center text-danger">Invalid response from server.</p>';
                return;
            }

            let table = document.createElement('table');
            table.setAttribute('id', 'annualfeeTable');
            table.classList.add('table', 'table-bordered');

            let thead = document.createElement('thead');
            thead.innerHTML = `
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th colspan='2'>Action</th>
        </tr>`;
            table.appendChild(thead);

            let tbody = document.createElement('tbody');

            if (feedetailData.length > 0) {
                feedetailData.forEach(item => {
                    let row = document.createElement('tr');
                    row.innerHTML = `
            <td>${item.Id}</td>
            <td>${item.MainMenuName}</td>
            <td>
              <button class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#modal-edit-menu"
                onclick="openEditModal(${item.Id}, '${item.MainMenuName}')">Edit</button>
            </td>`;
                    tbody.appendChild(row);
                });
            } else {
                let row = document.createElement('tr');
                row.innerHTML = `<td colspan="3" class="text-center">No records found</td>`;
                tbody.appendChild(row);
            }

            table.appendChild(tbody);
            document.getElementById('annualfeeTableDiv').appendChild(table);
        },
        error: function() {
            HideLoader();
            showErrorMessage('Server error occurred');
        }
    });
}

function openEditModal(id, name) {
    document.getElementById('editMenuId').value = id;
    document.getElementById('editMenuName').value = name;
}


function updateMenu() {
    var menuId = document.getElementById('editMenuId').value;
    var menuName = document.getElementById('editMenuName').value;
    var code = 3;

    if (menuName !== '') {
         ShowLoader();
        $.ajax({
            url: 'action-g.php',
            type: 'POST',
            data: {
                code: code,
                menuId: menuId,
                menuName: menuName
            },
            success: function(response) {
                HideLoader();
                if (response == '1') {
                    showSuccessMessage('Menu Updated Successfully');
                    $('#modal-edit-menu').modal('hide');
                    loadAllMenuRecord();
                } else {
                    showErrorMessage('Failed to update menu');
                }
            },
            error: function() {
                HideLoader();
                showErrorMessage('Server error occurred');
            }
        });
    } else {
        HideLoader();
        showErrorMessage('Please enter menu name');
    }
}
</script>
<div class="modal fade" id="modal-edit-menu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editMenuId">
                <div class="form-group">
                    <label for="editMenuName">Menu Name</label>
                    <input type="text" id="editMenuName" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateMenu()">Update</button>
            </div>
        </div>
    </div>
</div>