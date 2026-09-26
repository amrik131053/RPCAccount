function formatDate(dateString, format = 'DD-MM-YYYY HH:mm:ss') {
  const date = new Date(dateString);

  const pad = (num) => num.toString().padStart(2, '0');

  const day = pad(date.getDate());
  const month = pad(date.getMonth() + 1); // Months are 0-based
  const year = date.getFullYear();
  const hours = pad(date.getHours());
  const minutes = pad(date.getMinutes());
  const seconds = pad(date.getSeconds());

  if (format === 'DD-MM-YYYY HH:mm:ss') {
    return `${day}-${month}-${year}`;
  } else if (format === 'YYYY-MM-DD') {
    return `${year}-${month}-${day}`;
  } else if (format === 'MM/DD/YYYY') {
    return `${month}/${day}/${year}`;
  } else {
    return date.toISOString();

  }
}

function formatDatetime(dateString, format = 'DD-MM-YYYY') {
  const date = new Date(dateString);

  const pad = (num) => num.toString().padStart(2, '0');

  const day = pad(date.getDate());
  const month = pad(date.getMonth() + 1); // Months are 0-based
  const year = date.getFullYear();
  const hours = pad(date.getHours());
  const minutes = pad(date.getMinutes());
  const seconds = pad(date.getSeconds());

  if (format === 'DD-MM-YYYY HH:mm:ss') {
    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
  } else if (format === 'YYYY-MM-DD') {
    return `${year}-${month}-${day}`;
  } else if (format === 'MM/DD/YYYY') {
    return `${month}/${day}/${year}`;
  } else {
    return date.toISOString();
  }
}
// Example Usage


function loadPrograms(id) {
  
  var session = document.getElementById('session').value;

  if (session!='' && id!='') {
   
    const ProgramDropdown = document.getElementById('ProgramDropdown');
    ProgramDropdown.innerHTML = '<option value="">Select Programs</option>';

    fetch('/programs', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ id: id, session: session })
    })

      .then(response => response.json())
      .then(programs => {
       
        hideLoader();


        programs.forEach(program => {
          let option = document.createElement('option');
          option.value = program.CourseID;
          option.text = program.Course + '- ' + program.Duration + ' Years '+ ' (' + program.CourseID + ')';
          ProgramDropdown.add(option);
        });
      })
      .catch(error => console.error('Error:', error));
  }
  

else {
  showErrorMessage("Select Session and College");
}
}
function displayannualfee() {
  showLoader();
  var feesession = document.getElementById('session').value;
  var CollegeID = document.getElementById('CollegeID').value;
  var ProgramDropdown = document.getElementById('ProgramDropdown').value;
  const batch = document.getElementById('batch').value;
  var lateralentry = document.getElementById('lateralentry').value;


  if (session1 = !'' && CollegeID != '' && ProgramDropdown != '' && lateralentry != '') {
    fetch('/loadfee', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ CollegeID: CollegeID, feesession: feesession, ProgramDropdown: ProgramDropdown, batch: batch, lateralentry: lateralentry })
    })


      .then(response => response.json())
      .then(annual => {
        hideLoader();
        const feedetailData = annual;
        const tableContainer = document.getElementById('annualfeeTableDiv');
        tableContainer.innerHTML = '';


        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes

        // Create table header
        let thead = document.createElement('thead');
        thead.innerHTML = `<thead>
                            <tr>
                            <th>Semester</th>
                              <th>Session</th>
                              <th>Batch</th>
                              <th>Head</th>
                              <th>Fee Category</th>
                               <th>Amount</th>
                              <th>LEET</th>
                              <th colspan='2'>Action</th>
                            </tr>
                          </thead >`;
        table.appendChild(thead);

        // Create table body
        let tbody = document.createElement('tbody');

        // Check if data is available

        if (feedetailData.length > 0) {
          feedetailData.forEach(item => {
            let row = document.createElement('tr');
            row.innerHTML = `<td>${item.Semester}-${item.SemesterID}</td><td>${item.Session}</td>
                               <td>${item.Batch}</td>
                              <td>${item.Head}</td>
                                <td>${item.FeeCategoryName}(${item.FeeCategory})</td>
                               
                               <td>${item.Amount}</td>
                             <td>${item.LateralEntry}</td>
                              <td>  <button class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#modal-report" onclick="editannualfee(${item.SrNo})">Edit  
                                 </td>
                                <td>    <button onclick="deleteannualfee(${item.SrNo})" class='btn btn-danger btn-sm'>
                              <i class="fa fa-trash">Delete</i>
                               </button>    </td>`;
            tbody.appendChild(row);
          });
        } else {
          // Show message if no data is found
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
  else {
    // alert('Select Data');  
    hideLoader();
    showErrorMessage('Please select required inputs')
  }
}





function viewmodalaccountstatus(id) {
  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv_ledger');
  tableContainer.innerHTML = '';
 // tableContainer.style.display = 'none';
  // let button = document.getElementById("downloadbutton");
  // button.style.display = 'none';
  // let container = document.getElementById("studentdetail");
  // container.style.display = 'none';
  // let containerno = document.getElementById("Nostudentdetail");
  // containerno.style.display = 'none';
  // let balance = document.getElementById("balance");
  // balance.style.display = 'none';
  if (id != '') {
    fetch('/loadaccountstatus', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ studentid: id })
    })
      .then(response => response.json())
      .then(status => {
        hideLoader();
      
        let student = status.studentdatas[0];
     
        let ledger = status.ledgerdata;
        let deaddebit = status.deaddebitsdata;
        let concession = status.pendingConcessionsdata;
        let receiptdata = status.cancelledReceiptData;
        let debitcredit = status.debitcreditdata[0];
        var status = student.Status;
        console.log(ledger);
        hideLoader();
      showledger(ledger,deaddebit,concession,tableContainer,receiptdata,preceiptdata);




        // tableContainer.innerHTML = ledger;

        // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
  else {
    hideLoader();
    alert('Select Data');
  }
}


function accountstatus() {
  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv');
  tableContainer.innerHTML = '';
  tableContainer.style.display = 'none';
  let button = document.getElementById("downloadbutton");
  button.style.display = 'none';
  let container = document.getElementById("studentdetail");
  container.style.display = 'none';
  let containerno = document.getElementById("Nostudentdetail");
  containerno.style.display = 'none';
  let balance = document.getElementById("balance");
  balance.style.display = 'none';
  var id = document.getElementById('studentid').value;
  var dataurl = "{{ $dataurl }}";
  if (id != '') {

    fetch('/loadaccountstatus', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ studentid: id })
    })
      .then(response => response.json())
      .then(status => {
        hideLoader();
        let student = status.studentdatas[0];
        if (student.IDNo > 0) {
          let studentidfiled = document.getElementById("studentIdNo");
          studentidfiled.value = student.IDNo;
          button.style.display = 'block';
          container.style.display = 'block';
          balance.style.display = 'block';
          tableContainer.style.display = 'block';
        }
        else {
          if (containerno.style.display === "none" || div.style.display === "") {
            containerno.style.display = "block"; // Show the div
          }
        }
        let ledger = status.ledgerdata;
        let deaddebit = status.deaddebitsdata;
        let concession = status.pendingConcessionsdata;
        let receiptdata = status.cancelledReceiptData;
        let preceiptdata = status.pendingPReceiptsData;
        let debitcredit = status.debitcreditdata[0];
        var status = student.Status;
        if (status > 0) {
          var color = 'Green';
        }
        else { var color = 'Red'; }
        balance.innerHTML = `

                             <button class=" btn btn-info btn-sm" ">Debit : ${debitcredit.totaldebit}</button> <button class=" btn btn-success btn-sm">Credit : ${debitcredit.totalcredit}</button> <button class="btn btn-danger btn-sm">Balance : ${debitcredit.balance}</button>
                       </div>`;
        // Create dynamic HTML for the student data
        container.innerHTML = `
            <div class="student-card">
            <div class="col-lg-12">
                <div class="card">
                  <div class="row row-0">
                    <div class="col-3" style="text-align:center"><br>
                    <img  src="http://erp.gku.ac.in:86/Images/Students/${student.Image}"  style="border-radius:50%;height:100px;width:100px;border:5px ${color} solid" />
                    </div>
                    <div class="col">
                      <div class="card-body">
                         <p class=""><b>${student.StudentName} (${student.IDNo})</b><br> Uni Roll No : ${student.UniRollNo}<br>
                        Class Roll No : ${student.ClassRollNo}
                        <br>
                        Batch : ${student.Batch}  &nbsp; &nbsp;LEET : <b>${student.LateralEntry} </b><br>
                        Session : ${student.Session}</p>
                      </div>
                    </div>
                  </div>
                  
                </div>
               <div class="col-12">
                    <div class="card">
                    <div class="list-group-item">
                         

                     <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col text-truncate">
                            <b class="text-reset d-block">Mobile  :  ${student.StudentMobileNo}</b>
                              </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col text-truncate">
                            <b class="text-reset d-block">Mobile  :  ${student.EmailID}</b>
                              </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col text-truncate">
                            <b class="text-reset d-block">Father Name  :  ${student.FatherName}</b>
                              </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col text-truncate">
                            <b class="text-reset d-block">Mother Name  :  ${student.MotherName}</b>
                              </div>
                          </div>
                        </div>
                          <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col text-truncate">
                            <b class="text-reset d-block">Faculty  :  ${student.CollegeName}</b>
                              </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col">
                            <b class="text-reset d-block">Programme  : ${student.Course}</b>
                              </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col">
                            <b class="text-reset d-block">Scholarship  : ${student.ScolarShip}</b>  
                              </div>
                          </div>
                        </div>
                           <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col">
                            <p  class="text-reset d-block">Location  : <b> ${student.PermanentAddress}</b></p>
                              </div>
                          </div>
                        </div>
                        

                        </div>
                                                
                      </div>
                      <div class="card">
                  <div class="row row-0">
                    <div class="col"> <label style="background-color:red;color:white;border-radious:10%"><b>&nbsp;&nbsp;Special Comment &nbsp;&nbsp;</b></label>
                      <div class="card-body">
                       <b class="col-12" style="color:blue">${student.CommentsDetail}</b>
                      </div>
                    </div>
                      </div>
                  </div>
                   <div class="row row-0">
                   <div class="col"> <label style="background-color:red;color:white"><b> &nbsp;&nbsp;Comments From Accounts&nbsp;&nbsp;</b></label>
                      <div class="card-body">
                   <b class="col-12" style="color:blue">${student.CommentFromAcc}</b>
                      </div>
                    </div>
                  </div>
            </div>
                    </div>
                  </div>`;

       

        hideLoader();
        showledger(ledger,deaddebit,concession,tableContainer,receiptdata,preceiptdata);
        // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);

      });


  }
  else {
    hideLoader();
    alert('Select Data');
  }
}
function print_receipt(receiptnumber, LedgerName, IDNo, Session) {

  let routeUrl = document.getElementById('rid').getAttribute('data-route');


  let form = document.createElement('form');
  form.method = 'POST';
  form.action = 'PrintReceipt';
  form.target = 'blank';

  // Add CSRF token
  let csrfToken = document.createElement('input');
  csrfToken.type = 'hidden';
  csrfToken.name = '_token';
  csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  form.appendChild(csrfToken);

  // Add input field
  let receiptNoinput = document.createElement('input');
  receiptNoinput.type = 'hidden';
  receiptNoinput.name = 'ReceiptNo';
  receiptNoinput.value = receiptnumber;
  form.appendChild(receiptNoinput);

  let LedgerNameinput = document.createElement('input');
  LedgerNameinput.type = 'hidden';
  LedgerNameinput.name = 'LedgerName';
  LedgerNameinput.value = LedgerName;
  form.appendChild(LedgerNameinput);

  let IDNoinput = document.createElement('input');
  IDNoinput.type = 'hidden';
  IDNoinput.name = 'IDNo';
  IDNoinput.value = IDNo;
  form.appendChild(IDNoinput);

  let Sessioninput = document.createElement('input');
  Sessioninput.type = 'hidden';
  Sessioninput.name = 'Session';
  Sessioninput.value = Session;
  form.appendChild(Sessioninput);

  // You can set it dynamically


  // Append form to body and submit
  document.body.appendChild(form);
  form.submit();

}
function accountcomment() {

  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv');
  tableContainer.innerHTML = '';
 
  let button = document.getElementById("downloadbutton");

  button.style.display = 'none';

  let container = document.getElementById("studentdetail");

  container.style.display = 'none';

  let containerno = document.getElementById("Nostudentdetail");
  containerno.style.display = 'none';

  
  let balance = document.getElementById("balance");
  balance.style.display = 'none';
  var id = document.getElementById('studentid').value;


  var id = document.getElementById('studentid').value;
  var dataurl = "{{ $dataurl }}";
  if (id != '') {

    fetch('/loadaccountstatus', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ studentid: id })
    })

      .then(response => response.json())
      .then(status => {
        hideLoader();
        let student = status.studentdatas[0];
        let debitcredit = status.debitcreditdata[0];

        if (student.IDNo > 0) {
          let studentidfiled = document.getElementById("studentIdNo");
          //  let feediv= document.getElementById("feedebitdiv");
          studentidfiled.value = student.IDNo;
          button.style.display = 'block';
          container.style.display = 'block';
          balance.style.display = 'block';
          containerno.style.display = 'none';
          tableContainer.style.display = 'block';
        }
        else {
          containerno.style.display = 'block';
        }
       
        let ledger = status.ledgerdata;
        let deaddebit = status.deaddebitsdata;
        let concession = status.pendingConcessionsdata;
          let preceiptdata = status.pendingPReceiptsData;
        let receiptdata = status.cancelledReceiptData;

        var status = student.Status;
        if (status > 0) {
          var color = 'Green';
        }
        else { var color = 'Red'; }
        balance.innerHTML = `<button class=" btn btn-info btn-sm" ">Debit : ${debitcredit.totaldebit}</button> <button class=" btn btn-success btn-sm">Credit : ${debitcredit.totalcredit}</button> <button class="btn btn-danger btn-sm">Balance : ${debitcredit.balance}</button>
                     </div>`;
        // Create dynamic HTML for the student data
        container.innerHTML = `
          <div class="student-card">
          <div class="col-lg-12">
       
        
              <div class="card">
                <div class="row row-0">
                  <div class="col-3" style="text-align:center"><br>
                  <img  src="http://erp.gku.ac.in:86/Images/Students/${student.Image}"  style="border-radius:50%;height:100px;width:100px;border:5px ${color} solid" />
                  </div>
                  <div class="col">
                    <div class="card-body">
                       <p><b>${student.StudentName} (${student.IDNo})</b><br> Uni Roll No : ${student.UniRollNo}<br>
                      Class Roll No : ${student.ClassRollNo}
                      <br>
                      Batch : ${student.Batch}  &nbsp; &nbsp;LEET : <b>${student.LateralEntry} </b><br>
                      Session : ${student.Session}</p>
                    </div>
                  </div>
                </div>
                
              </div>
             <div class="col-12">
                  <div class="card">
                  <div class="list-group-item">
                 <div class="list-group list-group-flush list-group-hoverable">
                 <div class="list-group-item">
                        <div class="row align-items-center">
                         <div class="col text-truncate">
                         <b> Father Name  :  ${student.FatherName}    &nbsp; &nbsp; | &nbsp; &nbsp; Mother Name  :  ${student.MotherName}</b>
                            </div>
                        </div>
                      </div>
                      <div class="list-group-item">
                        <div class="row align-items-center">
                         <div class="col text-truncate">
                          <b>Programme  : ${student.Course} </b>
                            </div>
                        </div>
                      </div>
                      <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col">
                            <b class="text-reset d-block">Scholarship  : ${student.ScolarShip}</b>
                              </div>
                          </div>
                          </div>

                       <div class="card">
                  <div class="row row-0">
                    <div class="col"> <label style="background-color:red;color:white;border-radious:10%"><b>&nbsp;&nbsp;Special Comment &nbsp;&nbsp;</b></label>
                      <div class="card-body">
                       <b class="col-12" style="color:blue">${student.CommentsDetail}</b>
                      </div>
                    </div>
                      </div>
                  </div>
                   <div class="row row-0">
                   <div class="col"> <label style="background-color:red;color:white"><b> &nbsp;&nbsp;Comments From Accounts&nbsp;&nbsp;</b></label>
                      <div class="card-body">
                   <b class="col-12" style="color:blue">${student.CommentFromAcc}</b>
                      </div>
                    </div>
                  </div>
                 
                         <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="card m-2">
                                <div class="card-body">
                                    <label class="bg-danger text-white p-1"><strong>New Comments</strong></label>
                                    <textarea class="form-control mt-2" name="accountComments" id="accountComments" rows="3"></textarea>
                                </div>
                            </div>


                            <div class="card-footer text-end">
                            ${status > 0 ? `<button type="submit" class="btn btn-success" onclick="submitaccountComents(${student.IDNo});">Submit</button>`:'<button  class="btn btn-danger"">Left</button>'}
                            </div>
                       

                     </div>
                      </div>                    
                    
                      </div>                   
                    </div>
                  
                    </div>
                  </div>
                </div>`;

               showledger(ledger,deaddebit,concession,tableContainer,receiptdata,preceiptdata);
                
     })
      .catch(error => {
        console.error('Error:', error);
        hideLoader();
      });
  }
  else {
    hideLoader();
    alert('Select Data');
  }
}

// function CreateFeedebit() {
//   showLoader();
//   var debithead = document.getElementById('debithead').value;
//   var debitsession = document.getElementById('debitsession').value;
//   var debitsem = document.getElementById('debitsemester').value;
//   var debitremarks = document.getElementById('debitremarks').value;
//   var debitfee = document.getElementById('debitfee').value;
//   var studentIdNo = document.getElementById('studentIdNo').value;
//   var debitparticulars = document.getElementById('debitparticulars').value;
//   var debitdate = document.getElementById('debitdate').value;
  

//   if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '') {
//     fetch('/createfeedebitc', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//       },
//       body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitremarks: debitremarks, debitfee: debitfee, studentid: studentIdNo,debitdate:debitdate })
//     })
//       .then(response => response.json())
//       .then(data => {
//         hideLoader();
//         let message = data[0]['message'];
//         showSuccessMessage(message);
//         feedebit();
    
//       })
//       .catch(error => console.error('Error:', error));
//   }
//   else {
//     hideLoader();
//     alert("Please Select   ");
//   }
// }

//function CreateFeedebit() {
   // alert("sdfsf");
 // showLoader();

//   var debithead = document.getElementById('debithead').value;
//   var debitsession = document.getElementById('debitsession').value;
//   var debitsem = document.getElementById('debitsemester').value;
//   var debitremarks = document.getElementById('debitremarks').value;
//   var debitfee = document.getElementById('debitfee').value;
//   var studentIdNo = document.getElementById('studentIdNo').value;
//   var debitparticulars = document.getElementById('debitparticulars').value;
//  var debitdate = document.getElementById('debitdate').value;

//   if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '') {
//     fetch('/createfeedebitc', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//       },
//       body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitremarks: debitremarks, debitfee: debitfee, studentid: studentIdNo,debitdate:debitdate})
//     })
//       .then(response => response.json())
//       .then(data => {
//         hideLoader();

//         let message = data[0]['message'];
//         showSuccessMessage(message);
//         feedebit();
    
//       })
//       .catch(error => console.error('Error:', error));
//   }
//   else {
//     hideLoader();
//     alert("Please Select   ");
//   }
//}
function submitaccountComents(studentIdNo) {
  showLoader();
  var accountComments = document.getElementById('accountComments').value;
  // var studentIdNo =document.getElementById('studentIdNo').value;

  if (accountComments != '' && studentIdNo != '') {
    fetch('/createAcountComment', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ accountComments: accountComments, studentid: studentIdNo })
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        let message = data[0]['message'];
        showSuccessMessage(message);
        accountcomment();
        //    alert(message);
      })
      .catch(error => console.error('Error:', error));
  }
  else {
    hideLoader();
    alert("Please enter comments ");

  }
}


// loader for all screens//////////////////
function showLoader() {
  document.getElementById('fullScreenLoader').style.display = 'flex';
}

function hideLoader() {
  document.getElementById('fullScreenLoader').style.display = 'none';
}

// Toast

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
    // location.reload();
  }, 2000); // 2000ms = 2 seconds

}


function CreateAnnualFee() {
  showLoader();
  var feesession = document.getElementById('session').value;
  var CollegeID = document.getElementById('CollegeID').value;
  var ProgramDropdown = document.getElementById('ProgramDropdown').value;
  const batch = document.getElementById('batch').value;
  var lateralentry = document.getElementById('lateralentry').value;

  var annualfeehead = document.getElementById('annualfeehead').value;
  var annualfeecategory = document.getElementById('annualfeecategory').value;
  var annualfeesem = document.getElementById('annualfeesem').value;
 
  var annualfeeamount = document.getElementById('annualfeeamount').value;
  var annualfeeapplicableamount = document.getElementById('annualfeeapplicableamount').value;


  if (session1 = !'' && CollegeID != '' && ProgramDropdown != '' && lateralentry != '' && annualfeehead != '' && annualfeecategory != '' && annualfeesem != '' &&  annualfeeamount != '' &&annualfeeapplicableamount!='') {
    fetch('/addannualfee', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ CollegeID: CollegeID, feesession: feesession, ProgramDropdown: ProgramDropdown, batch: batch, lateralentry: lateralentry, annualfeehead: annualfeehead, annualfeecategory: annualfeecategory, annualfeesem: annualfeesem,annualfeeamount: annualfeeamount,annualfeeapplicableamount:annualfeeapplicableamount })
    })

      .then(response => response.json())
      .then(data => {
        hideLoader();
        displayannualfee();
        let message = data[0]['message'];
        showSuccessMessage(message);
      })
      .catch(error => console.error('Error:', error));
  }
  else {
    hideLoader();
    showErrorMessage('Please select required inputs');
  }
}


//Bulk Debit 

function displayStudentForBulkDebit() {
  showLoader();
  var feesession = document.getElementById('session').value;
  var CollegeID = document.getElementById('CollegeID').value;
  var ProgramDropdown = document.getElementById('ProgramDropdown').value;
  var batch = document.getElementById('batch').value;
  var lateralentry = document.getElementById('lateralentry').value;
  var annualfeecategory = document.getElementById('annualfeecategory').value;
    var semester = document.getElementById('searchsemester').value;
  // CollegeID = 64;
  // ProgramDropdown = 326;
  // batch = 0;
  // feesession = '2023-24-J';
  // lateralentry = 'No';

  if (session1 = !'' && CollegeID != '' && ProgramDropdown != '' && lateralentry != '' && annualfeecategory!='') {
    fetch('/loadStudents', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ CollegeID: CollegeID, feesession: feesession, ProgramDropdown: ProgramDropdown, batch: batch, lateralentry: lateralentry,annualfeecategory:annualfeecategory,semester:semester })
    })
      .then(response => response.json())
      .then(annual => {
       
        hideLoader();
        const feedetailData = annual;
        const tableContainer = document.getElementById('annualfeeTableDiv');
        tableContainer.innerHTML = '';


        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes

        // Create table header
        let thead = document.createElement('thead');
        thead.innerHTML = `<thead>
                            <tr>
                            <th><input type='checkbox' class="checkbox" style=" width: 20px;
  height: 20px; "  id="select_all" onclick="selectAll();"></th>
  
                             <th>#</th>       <th>Debit</th>  <th>Special Comment </th>
                                  <th>Account Comment</th>
                            <th>IDNo</th>
                           
                            <th>ClassRollNo</th>
                            <th>Name</th>
                              <th>Father Name</th>
                              <th>Session</th>
                              <th>Fee Category</th>
                              
                                     <th>View</th>
                           
                              
                            </tr>
                          </thead>`;
        table.appendChild(thead);
 let Count=1;
        // Create table body
        let tbody = document.createElement('tbody');

        // Check if data is available

        if (feedetailData.length > 0) {
          feedetailData.forEach(item => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td><input type='checkbox'  class='studentid' style=" width: 20px;
  height: 20px; "  value='${item.IDNo}'></td><td>${Count} </td>
        <td>${item.TotalDebit}</td>  <td>${item.CommentsDetail}</td>
                                      <td>${item.CommentFromAcc}</td> 
                                    
            <td>${item.IDNo}</td>
            <td>${item.ClassRollNo}</td>
            <td>${item.StudentName}</td>
            <td>${item.FatherName}</td>
                               <td>${item.Session}</td>
                             
                             
                                <td>${item.FeeCategory}</td>
                               
            <td><button class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#modal-ledger" 
            onclick="viewmodalaccountstatus(${item.IDNo})">View</td></td>
                            
                           
                              `;
            tbody.appendChild(row);
         
         Count++; });
        } else {
          // Show message if no data is found
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
  else {
    // alert('Select Data');  
    hideLoader();
    showErrorMessage('Please select required inputs')
  }
}

function CreateFeedebitBulk() {
   showLoader();
  var studentid = document.getElementsByClassName('studentid');
  var len_student = studentid.length;
  var student_str = [];

  for (i = 0; i < len_student; i++) {
    if (studentid[i].checked === true) {
      student_str.push(studentid[i].value);
    }
  }
  var debithead= document.getElementById('debithead').value;
  var debitsession= document.getElementById('debitsession').value;
  var debitsem= document.getElementById('debitsemester').value;
  var debitremarks= document.getElementById('debitremarks').value;
  var debitfee= document.getElementById('debitfee').value;
  var debitparticulars= document.getElementById('debitparticulars').value;
    var debitdate= document.getElementById('debitdate').value;
  const basicInfo = [{
    debithead: document.getElementById('debithead').value,
    debitsession: document.getElementById('debitsession').value,
    debitsem: document.getElementById('debitsemester').value,
    debitremarks: document.getElementById('debitremarks').value,
    debitfee: document.getElementById('debitfee').value,
    debitparticulars: document.getElementById('debitparticulars').value,
   debitdate: document.getElementById('debitdate').value

  }];
  const arraydetails = [{
    'basic': basicInfo,
    'students': student_str
  }];

  if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && debitparticulars!='' && debitremarks!=''&& debitdate!='' ) {
    if(student_str != '')
 {

    fetch('/createbulkdebit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ arraydetails })

    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        let message = data[0]['message']; 
        // console.log(message);
        showSuccessMessage(message);
        document.getElementById('debithead').value='';
        document.getElementById('debitfee').value='';
        displayStudentForBulkDebit();
      })
      .catch(error => console.error('Error:', error));
        
}
else{
  showErrorMessage('Please Select Atleast one student');
}
  }
  else {
    hideLoader();
    showErrorMessage('All inputs required');
  }

}

function deleteannualfee(srno)
{
  
   showLoader();
  fetch('/deleteannualfee', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ srno: srno})
  })

    .then(response => response.json())
    .then(data => {
      hideLoader();
   
      let message = data[0]['message'];
      showSuccessMessage(message);
      displayannualfee();
    })
    .catch(error => console.error('Error:', error));
}




function editannualfee(srno) {

  showLoader();
  fetch('/editannualfeedata', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ srno: srno })
  })

    .then(response => response.json())
    .then(data => {

      hideLoader();

      document.getElementById("annualfeesessionedit").innerHTML = data.Session;
      document.getElementById("annualfeesrnoedit").value = data.SrNo;
      document.getElementById("annualfeefacultytedit").value = data.CollegeName;
      document.getElementById("annualfeeprogrammeedit").value = data.Course;
      document.getElementById("annualfeeamountedit").value = data.Amount;
            document.getElementById("annualfeeactualamountedit").value = data.ActualFee;
      document.getElementById("annualfeenationalityedit").value = data.FeeCategoryName;
      document.getElementById("annualfeeheadedit").value = data.Head;
      document.getElementById("annualfeelateraledit").value = data.LateralEntry;

    })
    .catch(error => console.error('Error:', error));

}



function UpdateAnnualFee() {

  var srno = document.getElementById("annualfeesrnoedit").value;
  var amount = document.getElementById("annualfeeamountedit").value;
  var ActualFee = document.getElementById("annualfeeactualamountedit").value;
  showLoader();
  fetch('/updateannualfeedata', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ srno: srno, amount: amount,ActualFee:ActualFee })
  })

    .then(response => response.json())
    .then(data => {
      hideLoader();
      console.log(data);
      let message = data[0]['message'];
      showSuccessMessage(message);
      displayannualfee();
    })
    .catch(error => console.error('Error:', error));

}


function delte_debit(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit,Particular)
{

  showLoader();
 
      document.getElementById("annualfeesrnoedit").value = TransactionID;

      document.getElementById("annualfeefacultytedit").value =LedgerName ;

      document.getElementById("annualfeesessionedit").value = Session;

      document.getElementById("annualfeeamountedit").value = Debit;

      document.getElementById("studetnid").innerHTML = IDNo;
      
      document.getElementById("studetnidtext").value = IDNo;

      document.getElementById("annualfeeheadedit").value = SemesterID;
      document.getElementById("particulars").value = Particular;
      
      hideLoader();
}
function CreateDeleteDebit()
{

   var TransactionID=document.getElementById("annualfeesrnoedit").value;

  var LedgerName=document.getElementById("annualfeefacultytedit").value;

   var Session=document.getElementById("annualfeesessionedit").value;

  var Debit=document.getElementById("annualfeeamountedit").value;

  var IDNo=document.getElementById("studetnidtext").value;

  var SemesterID= document.getElementById("annualfeeheadedit").value;

  var Remarks= document.getElementById("particulars").value;

 var  Comments= document.getElementById("comments").value;
 if(Comments!='')
  {
  //showLoader();
  fetch('/deletedebitdata', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ TransactionID:TransactionID,LedgerName:LedgerName,Session:Session,Debit:Debit,IDNo:IDNo,SemesterID:SemesterID,Comments:Comments,Remarks:Remarks })
  })
 .then(response => response.json())
    .then(data => {
      //hideLoader();
      console.log(data);
      let message = data[0]['message'];
      showSuccessMessage(message);
      hideLoader();
      deletedebit();
    })
    .catch(error => console.error('Error:', error));
  }
  else{
    showErrorMessage('Comment is required inputs')
  }

}




function deadDebitVerify() {
  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv');
  tableContainer.innerHTML = '';
  tableContainer.style.display = 'block';

    fetch('/alldeaddebits', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify()
    })
      .then(response => response.json())
      .then(status => {
        hideLoader();
        console.log(status);
        let deaddebit = status;
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes
        table.id = "annualfeeTableForFixedHeader";
        let thead = document.createElement('thead');
        if (deaddebit.length > 0) {
        thead.innerHTML = `<thead>
                            <tr>
                            <th>Receipt Date </th>
                              <th>Particular</th>
                              <th>Name</th>
                              <th>Father Name</th>
                              <th>Semester</th>
                              <th>Amount</th>
                              <th>Remarks</th>
                              <th class="w-1">Action</th>
                            </tr>
                          </thead >`;
        }
        table.appendChild(thead);
        let tbody = document.createElement('tbody');
        if (deaddebit.length > 0) {
          deaddebit.forEach(item1 => {
            let row = document.createElement('tr');
            let receiptdate = formatDate(item1.DateEntry);  
            row.innerHTML = `
            <td> ${receiptdate}</td>        
            <td>${item1.Particulars}</td>
            <td>${item1.StudentName}</td>
            <td>${item1.FatherName}</td>
             <td>${item1.SemesterID}</td> 
             <td>${item1.Debit}</td>
             <td>${item1.Comments}</td>
             <td> <button class="btn btn-secondary btn-sm" onclick="showStudentDetails(${item1.IDNo},${item1.Debit},'${item1.Comments}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.DebitHead}',${item1.IDNo},'${item1.Session}',${item1.Debit});">View</button>    
          </td>`;
          tbody.appendChild(row);
        });
      }
      else{
           let row = document.createElement('tr');
        row.innerHTML = `<td colspan='8'>No Record Fund</td>`;
      }
        hideLoader();
        table.appendChild(tbody);
        tableContainer.appendChild(table); // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);

      });

}
function showStudentDetails(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit)
{

  showLoader();
  let container = document.getElementById("studentdetail");
  container.style.display = 'none';
  
    fetch('/getStudentData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({studentid:id})
    })
      .then(response => response.json())
      .then(studentdatas => {
      
        hideLoader();
        container.style.display = 'block';
        studentdatas=studentdatas[0];
        var status = studentdatas.Status;
        if (status > 0) { var color = 'Green';}
        else { var color = 'Red'; }
  container.innerHTML = `
  <div class="student-card">
  <div class="col-lg-12">
      <div class="card">
        <div class="row row-0">
          <div class="col-3" style="text-align:center"><br>
          <img  src="http://erp.gku.ac.in:86/Images/Students/${studentdatas.Image}"  style="border-radius:50%;height:100px;width:100px;border:5px ${color} solid" />
          </div>
          <div class="col">
            <div class="card-body">
               <p><b>${studentdatas.StudentName} (${studentdatas.IDNo})</b><br> Uni Roll No : ${studentdatas.UniRollNo}<br>
              Class Roll No : ${studentdatas.ClassRollNo}
              <br>
              Batch : ${studentdatas.Batch}  &nbsp; &nbsp;LEET : <b>${studentdatas.LateralEntry} </b><br>
              Session : ${studentdatas.Session}</p>
            </div>
          </div>
        </div>
      </div>
     <div class="col-12">
          <div class="card">
          <div class="list-group-item">
               

           <div class="list-group list-group-flush list-group-hoverable">
             
            
            
              <div class="list-group-item">
                <div class="row align-items-center">
                 <div class="col text-truncate">
                 <b> Father Name  :  ${studentdatas.FatherName}    &nbsp; &nbsp; | &nbsp; &nbsp; Mother Name  :  ${studentdatas.MotherName}</b>
                    </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                 <div class="col text-truncate">
                  <b>Programme  : ${studentdatas.Course} </b>
                    </div>
                </div>
              </div>
            
              </div>                    
             
              </div>
                                      
            </div>
           
         <div class="card">
                  <div class="row row-0">
                    <div class="col"> <label style="background-color:red;color:white;border-radious:10%"><b>&nbsp;&nbsp;Special Comment &nbsp;&nbsp;</b></label>
                      <div class="card-body">
                       <b class="col-12" style="color:blue">${studentdatas.CommentsDetail}</b>
                      </div>
                    </div>
                      </div>
                  </div>
                   
            </div>
                              
  </div>
    
  <table class="table">
  <tr>
  <th>Semester</th>
  <th>Debit</th>
  <th>Comments</th>
  </tr>
  <tr>
  <td>${sedmid}</td>
  <td>${debit}</td>
  <td>${comments}</td>
  </tr>
  </table>
    

          </div>
            <div class="card-footer">

                          <button class="btn btn-danger btn-sm"  onclick="verifyDebit(${TransactionID},${SemesterID},'${LedgerName}',${IDNo},'${Session}',${Debit})">Verify</button>
                            <br>
                            <br>
                        </div>
        </div>`;
        hideLoader();
       
  
      })
      .catch(error => {
        console.error('Error:', error);

      });
}

function verifyDebit(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit) {
  showLoader();
    fetch('/verifyDebit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ TransactionID: TransactionID, SemesterID: SemesterID, LedgerName: LedgerName, IDNo: IDNo, Session: Session, Debit: Debit })
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        let message = data[0]['message'];
        showSuccessMessage(message);
        deadDebitVerify();
        let container = document.getElementById("studentdetail");
        container.innerHTML = '';
    
      })
      .catch(error => console.error('Error:', error));
  }



  // approve debit

  
function deadDebitApprove() {
  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv');
  tableContainer.innerHTML = '';
  tableContainer.style.display = 'block';

    fetch('/alldeaddebitsApprove', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify()
    })
      .then(response => response.json())
      .then(status => {
        hideLoader();
        // console.log(status);
        let deaddebit = status;
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes
        table.id = "annualfeeTableForFixedHeader";
        let thead = document.createElement('thead');
        if (deaddebit.length > 0) {
        thead.innerHTML = `<thead>
                            <tr>
                            <th>Receipt Date </th>
                              <th>Particular</th>
                              <th>Name</th>
                              <th>Father Name</th>
                              <th>Semester</th>
                              <th>Debit</th>
                              <th>Remarks</th>
                              <th class="w-1">Action</th>
                            </tr>
                          </thead >`;
        }
        table.appendChild(thead);
        let tbody = document.createElement('tbody');
        if (deaddebit.length > 0) {
          deaddebit.forEach(item1 => {
            let row = document.createElement('tr');
            let receiptdate = formatDate(item1.DateEntry);  
            row.innerHTML = `
            <td> ${receiptdate}</td>        
            <td>${item1.Particulars}</td>
            <td>${item1.StudentName}</td>
            <td>${item1.FatherName}</td>
             <td>${item1.SemesterID}</td> 
             <td>${item1.Debit}</td>
             <td>${item1.Comments}</td>
             <td> <button class="btn btn-secondary btn-sm" onclick="showStudentDetailsApprove(${item1.IDNo},${item1.Debit},'${item1.Comments}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.DebitHead}',${item1.IDNo},'${item1.Session}',${item1.Debit});">View</button>    
          </td>`;
          tbody.appendChild(row);
        });
      }
      else{
           let row = document.createElement('tr');
        row.innerHTML = `<td colspan='8'>No Record Fund</td>`;
      }
        hideLoader();
        table.appendChild(tbody);
        tableContainer.appendChild(table); // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);

      });

}


function showStudentDetailsApprove(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit)
{

  showLoader();
  let container = document.getElementById("studentdetail");
  container.style.display = 'none';
  
    fetch('/getStudentData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({studentid:id})
    })
      .then(response => response.json())
      .then(studentdatas => {
      
        hideLoader();
        container.style.display = 'block';
        studentdatas=studentdatas[0];
        var status = studentdatas.Status;
        if (status > 0) { var color = 'Green';}
        else { var color = 'Red'; }
  container.innerHTML = `
  <div class="student-card">
  <div class="col-lg-12">
      <div class="card">
        <div class="row row-0">
          <div class="col-3" style="text-align:center"><br>
          <img  src="http://erp.gku.ac.in:86/Images/Students/${studentdatas.Image}"  style="border-radius:50%;height:100px;width:100px;border:5px ${color} solid" />
          </div>
          <div class="col">
            <div class="card-body">
               <p><b>${studentdatas.StudentName} (${studentdatas.IDNo})</b><br> Uni Roll No : ${studentdatas.UniRollNo}<br>
              Class Roll No : ${studentdatas.ClassRollNo}
              <br>
              Batch : ${studentdatas.Batch}  &nbsp; &nbsp;LEET : <b>${studentdatas.LateralEntry} </b><br>
              Session : ${studentdatas.Session}</p>
            </div>
          </div>
        </div>
      </div>
     <div class="col-12">
          <div class="card">
          <div class="list-group-item">
               

           <div class="list-group list-group-flush list-group-hoverable">
             
            
            
              <div class="list-group-item">
                <div class="row align-items-center">
                 <div class="col text-truncate">
                 <b> Father Name  :  ${studentdatas.FatherName}    &nbsp; &nbsp; | &nbsp; &nbsp; Mother Name  :  ${studentdatas.MotherName}</b>
                    </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                 <div class="col text-truncate">
                  <b>Programme  : ${studentdatas.Course} </b>
                    </div>
                </div>
              </div>
            
              </div>                    
             
              </div>
                                      
            </div>
           
         <div class="card">
                  <div class="row row-0">
                    <div class="col"> <label style="background-color:red;color:white;border-radious:10%"><b>&nbsp;&nbsp;Special Comment &nbsp;&nbsp;</b></label>
                      <div class="card-body">
                       <b class="col-12" style="color:blue">${studentdatas.CommentsDetail}</b>
                      </div>
                    </div>
                      </div>
                  </div>
                   
            </div>
                              
  </div>
    
  <table class="table">
  <tr>
  <th>Semester</th>
  <th>Debit</th>
  <th>Comments</th>
  </tr>
  <tr>
  <td>${sedmid}</td>
  <td>${debit}</td>
  <td>${TransactionID}</td>
  </tr>
  </table>
    

          </div>
            <div class="card-footer">

                          <button class="btn btn-danger btn-sm"  onclick="approveDebit(${TransactionID},${SemesterID},'${LedgerName}',${IDNo},'${Session}',${Debit})">Verify</button>
                            <br>
                            <br>
                        </div>
        </div>`;
        hideLoader();
       
  
      })
      .catch(error => {
        console.error('Error:', error);

      });
}


function approveDebit(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit) {
  // alert(TransactionID);
  showLoader();
    fetch('/approveDebit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ TransactionID: TransactionID, SemesterID: SemesterID, LedgerName: LedgerName, IDNo: IDNo, Session: Session, Debit: Debit })
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        console.log(data);
        let message = data[0]['message'];
        showSuccessMessage(message);
        deadDebitApprove();
        let container = document.getElementById("studentdetail");
        container.innerHTML = '';
    
      })
      .catch(error => console.error('Error:', error));
  }

  
  function displayledgerdata() {
    // alert(TransactionID);
  
  const tableContainer = document.getElementById('annualfeeTableDiv');
   tableContainer.innerHTML = '';
   tableContainer.style.display = 'none';


    var passout = document.getElementById('passout').value;
    var feesession = document.getElementById('session').value;
    var collegeid = document.getElementById('CollegeID').value;
    var courseid = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    const semester = document.getElementById('annualfeesem').value;
    var annualfeecategory = document.getElementById('annualfeecategory').value;
     var enrollment = document.getElementById('enrollment').value;
 showLoader();
        fetch('/ledgerreportdata', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({feesession:feesession,collegeid:collegeid,courseid:courseid,batch:batch,semester:semester,annualfeecategory:annualfeecategory,passout:passout,enrollment:enrollment})
      })
        
     .then(response => response.json())
      .then(myledgerdata => {
        hideLoader();
        tableContainer.style.display = 'block';
        // console.log(status);
        let ledgerdatastudent = myledgerdata.classData;
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes
        table.id = "annualfeeTableForFixedHeader";
        let thead = document.createElement('thead');
        if (ledgerdatastudent.length > 0) {
        thead.innerHTML = `<thead>
                            <tr>
                             <th>Sr No</th>
                            <th>Session</th>
                             <th>Class RollNo</th>
                               <th>Uni RollNo</th>
                              <th>IDNo</th>
                              <th>Name</th>
                              <th>Father Name</th>
                              <th>College</th>
                              <th>Course</th>
                           
                               <th>Debit</th>
                               <th>Credit</th>
                               
                              <th class="w-1">Balance</th>
                            </tr>
                          </thead >`;
        }
        table.appendChild(thead);
        let srno=1;
        let tbody = document.createElement('tbody');
        if (ledgerdatastudent.length > 0) {
          ledgerdatastudent.forEach(item1 => {
            let row = document.createElement('tr');
           
           let Balance= item1.TotalDebit-item1.TotalCredit;
            row.innerHTML = `<td>${srno}</td> <td>${item1.Session}</td>
            <td>${item1.ClassRollNo} </td>    
             <td>${item1.UniRollNo} </td>        
            <td>${item1.IDNo}</td>
            <td>${item1.StudentName}</td>
            <td>${item1.FatherName}</td>
            <td>${item1.CollegeName}</td> 
            <td>${item1.Course}</td>
            <td>${item1.TotalDebit}</td>
            <td>${item1.TotalCredit}</td>
            <td>${Balance}</td>
           `;
           srno++;
          tbody.appendChild(row);
        });
       }
      else{
           let row = document.createElement('tr');
        row.innerHTML = `<td colspan='8'>No Record Fund</td>`;
      }
        hideLoader();
        table.appendChild(tbody);
        tableContainer.appendChild(table); // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);

      });
       

  
        
    }
  function displayInvalidDisplay() {
  
    const tableContainer = document.getElementById('annualfeeTableDiv');
   tableContainer.innerHTML = '';
   tableContainer.style.display = 'none';

    var feesession = document.getElementById('session').value;
    var collegeid = document.getElementById('CollegeID').value;
    var courseid = document.getElementById('ProgramDropdown').value;
    const batch = document.getElementById('batch').value;
    const semester = document.getElementById('annualfeesem').value;
    var annualfeecategory = document.getElementById('annualfeecategory').value;
if(batch>0)
{  showLoader();
        fetch('/invalidreportdata', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({feesession:feesession,collegeid:collegeid,courseid:courseid,batch:batch,semester:semester,annualfeecategory:annualfeecategory})
      })
        
     .then(response => response.json())
      .then(myledgerdata => {
        hideLoader();
        tableContainer.style.display = 'block';
        // console.log(status);
        let ledgerdatastudent = myledgerdata.classData;
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes
        table.id = "annualfeeTableForFixedHeader";
        let thead = document.createElement('thead');
        if (ledgerdatastudent.length > 0) {
        thead.innerHTML = `<thead>
                            <tr>
                             <th>Sr No</th>
                            <th>Session</th>
                             <th>Class RollNo</th>
                              <th>IDNo</th>
                              <th>Name</th>
                              <th>Father Name</th>
                              <th>College</th>
                              <th>Course</th>
                               <th>Debit</th>
                               <th>Credit</th>
                               
                              <th class="w-1">Balance</th>
                            </tr>
                          </thead >`;
        }
        table.appendChild(thead);
        let srno=1;
        let tbody = document.createElement('tbody');
        if (ledgerdatastudent.length > 0) {
          ledgerdatastudent.forEach(item1 => {
            let row = document.createElement('tr');
           
           let Balance= item1.TotalDebit-item1.TotalCredit;
            row.innerHTML = `<td>${srno}</td> <td>${item1.Session}</td>
            <td>${item1.ClassRollNo} </td>        
            <td>${item1.IDNo}</td>
            <td>${item1.StudentName}</td>
            <td>${item1.FatherName}</td>
            <td>${item1.CollegeName}</td> 
            <td>${item1.Course}</td>
            <td>${item1.TotalDebit}</td>
            <td>${item1.TotalCredit}</td>
            <td>${Balance}</td>
           `;
           srno++;
          tbody.appendChild(row);
        });
       }
      else{
           let row = document.createElement('tr');
        row.innerHTML = `<td colspan='8'>No Record Fund</td>`;
      }
        hideLoader();
        table.appendChild(tbody);
        tableContainer.appendChild(table); // Append table to the container
      })
      .catch(error => {
        console.error('Error:', error);

      });
       

    }
    else
    {
      showErrorMessage("Batch is Mandatory");
    }
        
    }
  