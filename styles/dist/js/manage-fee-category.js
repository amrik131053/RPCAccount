function submitNewCategoryClick(){
    var categoryName = document.getElementById('categoryName').value;
    showLoader();
    fetch('/MangeFeeCategoryPost', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ CategoryName:categoryName})
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        let message = data['message'];
        showSuccessMessage(message);
        displayAllCategory();
    
    
      })
      .catch(error => console.error('Error:', error));
  }
  

  function displayAllCategory() {
    showLoader();
      fetch('/LoadAllCategory', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
      })
        .then(response => response.json())
        .then(feecategory => {
          hideLoader();
        //   console.log(feecategory);
          const feedetailData = feecategory;
          const tableContainer = document.getElementById('feeCategoryTableDiv');
          tableContainer.innerHTML = '';
          let table = document.createElement('table');
          table.setAttribute('id', 'feecategoryfeeTable');
          table.classList.add('table', 'table-bordered');
          let thead = document.createElement('thead');
          thead.innerHTML = `<thead>
                              <tr>
                              <th>Name</th>
                              <th>Action</th>
                              </tr>
                            </thead >`;
          table.appendChild(thead);
          let tbody = document.createElement('tbody');
          if (feedetailData.length > 0) {
            feedetailData.forEach(item => {
              let row = document.createElement('tr');
              var checkBox="";
              value="1";
              if(item.Status==1)
              {
                checkBox="checked";
                value="0";
              }
              row.innerHTML = `
              
              <td>${item.FeeCategory}(${item.ID})</td>
                <td> 
                        <div>
                                <label class="row">
                                  <span class="col-auto">
                                    <label class="form-check form-check-single form-switch">
                                      <input class="form-check-input" type="checkbox" value="${value}"  ${checkBox}  onclick="editfeecategory(${item.ID},${value})"> 
                                    </label>
                                  </span>
                                </label>
                              </div>        
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

  function editfeecategory(ID,status) {
    showLoader();
    fetch('/editfeecategorydata', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ ID: ID,status:status })
    })
  
      .then(response => response.json())
      .then(data => {
          hideLoader();
            console.log(data);
            let message = data['message'];
            showSuccessMessage(message);
            message="";
    
      })
      .catch(error => console.error('Error:', error));
  
  }

  // head functions

function submitNewHeadClick(){
    var headName = document.getElementById('headName').value;
    alert(headName);
    showLoader();
    fetch('/MangeMasterHeadPost', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ MasterHeadName:headName})
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        console.log(data);
        let message = data['message'];
        showSuccessMessage(message);
        displayAllHeads();
    
    
      })
      .catch(error => console.error('Error:', error));
  }
  

  function displayAllHeads() {
    showLoader();
      fetch('/LoadAllHead', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
      })
        .then(response => response.json())
        .then(feecategory => {
          hideLoader();
          console.log(feecategory);
          const feedetailData = feecategory;
          const tableContainer = document.getElementById('feeCategoryTableDiv');
          tableContainer.innerHTML = '';
          let table = document.createElement('table');
          table.setAttribute('id', 'feecategoryfeeTable');
          table.classList.add('table', 'table-bordered');
          let thead = document.createElement('thead');
          thead.innerHTML = `<thead>
                              <tr>
                              <th>Name</th>
                              <th>Action</th>
                              </tr>
                            </thead >`;
          table.appendChild(thead);
          let tbody = document.createElement('tbody');
          if (feedetailData.length > 0) {
            feedetailData.forEach(item => {
              let row = document.createElement('tr');
              var checkBox="";
              value="1";
              if(item.Status==1)
              {
                checkBox="checked";
                value="0";
              }
              row.innerHTML = `
              
              <td>${item.Head}</td>
                <td> 
                        <div>
                                <label class="row">
                                  <span class="col-auto">
                                    <label class="form-check form-check-single form-switch">
                                      <input class="form-check-input" type="checkbox" value="${value}"  ${checkBox}  onclick="editmasterhead(${item.Id},${value})"> 
                                    </label>
                                  </span>
                                </label>
                              </div>        
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

  function editmasterhead(ID,status) {
    showLoader();
    fetch('/editmasterheaddata', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ ID: ID,status:status })
    })
  
      .then(response => response.json())
      .then(data => {
          hideLoader();
            console.log(data);
            let message = data['message'];
            showSuccessMessage(message);
            message="";
    
      })
      .catch(error => console.error('Error:', error));
  
  }