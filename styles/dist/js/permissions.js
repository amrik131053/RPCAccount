function showErrorMessage(message) {
  const toastEl = document.getElementById('tablerToastWarning');
  const toastBody = toastEl.querySelector('.toast-body');
  toastBody.textContent = message;
  const toast = new bootstrap.Toast(toastEl);

  toast.show();

}
function showSuccessMessage(message) {
  const toastEl = document.getElementById('tablerToastSuccess');
  const toastBody = toastEl.querySelector('.toast-body');
  toastBody.textContent = message;
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
  setTimeout(function () {
  }, 2000); 
}

function showLoader() {
  document.getElementById('fullScreenLoader').style.display = 'flex';
}

function hideLoader() {
  document.getElementById('fullScreenLoader').style.display = 'none';
}

function displayAllRoles() {

}
function loadAllMenuRecord() {
  showLoader();
  fetch('/loadMenu', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(annual => {
      hideLoader();
      const feedetailData = annual;
      const tableContainer = document.getElementById('annualfeeTableDiv');
      tableContainer.innerHTML = '';

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
      tableContainer.appendChild(table);
    })
    .catch(error => {
      console.error('Error:', error);
      hideLoader();
    });
}

// Function to save updated menu name
// Open modal with values
function openEditModal(id, name) {
  // alert(id);
  document.getElementById('editMenuId').value = id;
  document.getElementById('editMenuName').value = name;
}

// Save changes
function saveMenuUpdate() {
  let id = document.getElementById('editMenuId').value;
  let newName = document.getElementById('editMenuName').value;
  fetch('/updateMenu', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ id: id, name: newName })
  })
  .then(response => response.json())
  .then(data => {
      showSuccessMessage("Menu updated successfully!");
      loadAllMenuRecord(); // refresh table
      bootstrap.Modal.getInstance(document.getElementById('modal-edit-menu')).hide(); // close modal
   
  })
  .catch(error => console.error("Error:", error));
}
  function displayAllRoles() {
 showLoader();
      fetch('/LoadAllRoles', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
      })
        .then(response => response.json())
        .then(roledata => {
          hideLoader();
        //   console.log(feecategory);
          const roledetaildata = roledata;
          const tableContainer = document.getElementById('feeCategoryTableDiv');
          tableContainer.innerHTML = '';
          let table = document.createElement('table');
          table.setAttribute('id', 'feecategoryfeeTable');
          table.classList.add('table', 'table-bordered');
          let thead = document.createElement('thead');
          thead.innerHTML = `<thead>
                              <tr>
                               <th>ID</th>
                              <th>Name</th>
                              <th>Action</th>
                              </tr>
                            </thead >`;
          table.appendChild(thead);
          let tbody = document.createElement('tbody');
          if (roledata.length > 0) {
            roledata.forEach(item => {
              let row = document.createElement('tr');
              var checkBox="";
              value="1";
              if(item.Status==1)
              {
                checkBox="checked";
                value="0";
              }
              row.innerHTML = `
              <td>${item.Id}</td>
              <td>${item.RoleName}</td>
              <td>
                <button class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#modal-edit-Role" 
                  onclick="openEditModalRole(${item.Id}, '${item.RoleName}')">Edit</button>
              </td>`;
              tbody.appendChild(row);
            });
          } else {
            let row = document.createElement('tr');
            row.innerHTML = `<td colspan="8" class="text-center">No records found</td>`;
            tbody.appendChild(row);
          }
  
          table.appendChild(tbody);
          tableContainer.appendChild(table); // Append table to the container
        })
        .catch(error => {
          console.error('Error:', error);
          hideLoader();
        });

      }
  

function submitNewMenu() {
  showLoader();
  var MenuName = document.getElementById('MenuName').value;
  if (MenuName != '') {
    fetch('/createMainMenu', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ MenuName: MenuName})
    })
      .then(response => response.json())
      .then(data => {
      
        hideLoader();
        let message = data['message'];
        showSuccessMessage(message);
        loadAllMenuRecord();
      })
      .catch(error => console.error('Error:', error));
  }
  else {
    hideLoader();
    alert("Please enter comments ");

  }
}
function createRole() {
  showLoader();
  var RoleName = document.getElementById('RoleName').value;
  if (RoleName != '') {
    fetch('/createRole', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ RoleName: RoleName})
    })
      .then(response => response.json())
      .then(data => {
      
        hideLoader();
        let message = data['message'];
        showSuccessMessage(message);
        displayAllRoles();
      })
      .catch(error => console.error('Error:', error));
  }
  else {
    hideLoader();
    alert("Please enter comments ");

  }
}


function openEditModalRole(id, name) {
  // alert(id);
  document.getElementById('editRoleId').value = id;
  document.getElementById('editRoleName').value = name;
}

// Save changes
function saveRoleUpdate() {
  let id = document.getElementById('editRoleId').value;
  let newName = document.getElementById('editRoleName').value;
  fetch('/updateRole', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ id: id, name: newName })
  })
  .then(response => response.json())
  .then(data => {
      showSuccessMessage("Role updated successfully!");
      displayAllRoles(); // refresh table
      bootstrap.Modal.getInstance(document.getElementById('modal-edit-Role')).hide(); // close modal
   
  })
  .catch(error => console.error("Error:", error));
}


function SearchMenu() {

  let MenuName=document.getElementById('MenuName').value;
  showLoader();
  fetch('/SearchMenu', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({id:MenuName})
  })
    .then(response => response.json())
    .then(annual => {
      hideLoader();
      const feedetailData = annual;
      const tableContainer = document.getElementById('annualfeeTableDiv');
      tableContainer.innerHTML = '';

      let table = document.createElement('table');
      table.setAttribute('id', 'annualfeeTable');
      table.classList.add('table', 'table-bordered');

      let thead = document.createElement('thead');
      thead.innerHTML = `
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>SubMenu Name</th>
          <th>Route Name</th>
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
            <td>${item.PageName}</td>
            <td>${item.Route}</td>
            <td>
              <button class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#modal-edit-menu" 
                onclick="openEditModalSubMenu(${item.Id},'${item.MainMenuId}', '${item.PageName}','${item.MainMenuName}','${item.Route}')">Edit</button>
            </td>`;
          tbody.appendChild(row);
        });
      }
      let addRow = document.createElement('tr');
      addRow.innerHTML = `
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
      `;
      tbody.appendChild(addRow);
      
      

      table.appendChild(tbody);
      tableContainer.appendChild(table);
    })
    .catch(error => {
      console.error('Error:', error);
      hideLoader();
    });
}


function openEditModalSubMenu(id, MainMenuId, PageName, MainMenuName, Route) {
  document.getElementById('editSubMenuId').value = id;
  document.getElementById('editMainMenu').value = MainMenuId;
  document.getElementById('editSubMenu').value = PageName;
  document.getElementById('editRouteName').value = Route;
}

// Save changes
function saveSubMenuUpdate() {
  let editMenuId= document.getElementById('editMainMenu').value;
let editSubMenuId=document.getElementById('editSubMenuId').value;
let editSubMenu=document.getElementById('editSubMenu').value;
let editRouteName= document.getElementById('editRouteName').value;

  fetch('/updateSubMenu', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ editMenuId: editMenuId, editSubMenuId: editSubMenuId,editSubMenu:editSubMenu,editRouteName:editRouteName })
  })
  .then(response => response.json())
  .then(data => {
      showSuccessMessage("updated successfully!");
      SearchMenu(); // refresh table
      bootstrap.Modal.getInstance(document.getElementById('modal-edit-menu')).hide(); // close modal
   
  })
  .catch(error => console.error("Error:", error));
}
function saveNewMenu() {
  let menuId = document.getElementById('newMainMenu').value;
  let subMenu = document.getElementById('newSubMenu').value;
  let route   = document.getElementById('newRoute').value;

  if (!menuId || !subMenu || !route) {
    alert("All fields are required!");
    return;
  }

  // send to backend
  fetch('/SaveMenu', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
      MainMenuId: menuId,
      PageName: subMenu,
      Route: route
    })
  })
  .then(res => res.json())
  .then(data => {
    showSuccessMessage("Menu saved successfully!");
    SearchMenu(); // reload table
  })
  .catch(err => console.error(err));
}




function SearchRole() {
  let RoleName = document.getElementById('RoleName').value;
  showLoader();

  fetch('/SearchRole', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ id: RoleName })
  })
    .then(response => response.json())
    .then(data => {
      hideLoader();
      const tableContainer = document.getElementById('annualfeeTableDiv');
      tableContainer.innerHTML = '';
      let form = document.createElement('form');
      form.method = 'POST';

      form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="id" value="${RoleName}">
      `;

      if (data.length > 0) {
        data.forEach((menu, index) => {
          let menuCard = document.createElement('div');
          menuCard.classList.add('card', 'mb-2');
          let header = document.createElement('div');
          header.classList.add('card-header', 'd-flex', 'justify-content-between', 'align-items-center');
          header.style.cursor = 'pointer';
          header.setAttribute('data-bs-toggle', 'collapse');
          header.setAttribute('data-bs-target', `#menu-${index}`);
          header.innerHTML = `<strong>${menu.MenuName} (${menu.MenuID})</strong> <span class="toggle-icon">+</span>`;
          header.addEventListener('click', function () {
            const icon = this.querySelector('.toggle-icon');
            setTimeout(() => {
              icon.innerText = document.querySelector(`#menu-${index}`).classList.contains('show') ? '+' : '−';
            }, 300);
          });

          let collapseDiv = document.createElement('div');
          collapseDiv.classList.add('collapse');
          collapseDiv.id = `menu-${index}`;

          let table = document.createElement('table');
          table.classList.add('table', 'table-bordered', 'mt-2');
          let thead = document.createElement('thead');
          thead.innerHTML = `
            <tr>
              <th>Route / SubMenu</th>
              <th>Read</th>
              <th>Write</th>
              <th>Delete</th>
            </tr>`;
          table.appendChild(thead);

          let tbody = document.createElement('tbody');

          if (menu.SubMenus && menu.SubMenus.length > 0) {
            menu.SubMenus.forEach(sub => {
              let p = sub.Permissions || {};
              let readChecked = (p.Create && p.Create.toString().trim() === "1") ? "checked" : "";
              let writeChecked = (p.Write && p.Write.toString().trim() === "1") ? "checked" : "";
              let deleteChecked = (p.Delete && p.Delete.toString().trim() === "1") ? "checked" : "";

              let subRow = document.createElement('tr');
              subRow.innerHTML = `
                <td>${sub.SubMenuName ?? ''} (${sub.RouteName})</td>
                <td>
                  <input class="permission-checkbox" type="hidden" name="permissions[${sub.SubMenuID}][read]" value="0">
                  <input class="permission-checkbox" type="checkbox" name="permissions[${sub.SubMenuID}][read]" value="1" ${readChecked}>
                </td>
                <td>
                  <input class="permission-checkbox" type="hidden" name="permissions[${sub.SubMenuID}][write]" value="0">
                  <input class="permission-checkbox" type="checkbox" name="permissions[${sub.SubMenuID}][write]" value="1" ${writeChecked}>
                </td>
                <td>
                  <input class="permission-checkbox" type="hidden" name="permissions[${sub.SubMenuID}][delete]" value="0">
                  <input class="permission-checkbox" type="checkbox" name="permissions[${sub.SubMenuID}][delete]" value="1" ${deleteChecked}>
                </td>
              `;
              tbody.appendChild(subRow);
            });
          } else {
            let subRow = document.createElement('tr');
            subRow.innerHTML = `<td colspan="4" class="text-muted">No routes found</td>`;
            tbody.appendChild(subRow);
          }

          table.appendChild(tbody);
          collapseDiv.appendChild(table);

          menuCard.appendChild(header);
          menuCard.appendChild(collapseDiv);
          form.appendChild(menuCard);
        });
      }

      let saveBtn = document.createElement('button');
      saveBtn.type = 'button'; 
      saveBtn.classList.add('btn', 'btn-primary', 'mt-3');
      saveBtn.innerText = 'Save Permissions';
      saveBtn.addEventListener('click', function () {
        savePermissions(form); 
      });

      form.appendChild(saveBtn);
      tableContainer.appendChild(form);
    })
    .catch(error => {
      // console.error('Error:', error);
      hideLoader();
    });
}

function savePermissions(form) {
  showLoader();
  let formData = new FormData(form);
  fetch('/SaveRolePermission', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    // console.log(result);
    hideLoader();
    showSuccessMessage("Permissions saved successfully!");
      SearchRole(); 
   
  })
  .catch(error => {
    hideLoader();
    console.error('Error:', error);
    alert('Something went wrong.');
  });
}


