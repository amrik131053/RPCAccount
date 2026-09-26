function feedebit() {
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
   let feedebitdiv = document.getElementById("feedebitdiv");
   feedebitdiv.style.display = 'none';
   let feedebitbutton = document.getElementById("feedebitbutton");
   feedebitbutton.style.display = 'none';
    var id = document.getElementById('studentid').value;
    var dataurl = "{{ $dataurl }}";
    //alert(dataurl);
    // alert("batch :"+ id)
    if (id != '') {
      // alert("ghjghj"+id); 
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
          let container = document.getElementById("studentdetail");
          let balance = document.getElementById("balance");
          let student = status.studentdatas[0];
         
          
  
          if (student.IDNo > 0) {
            let studentidfiled = document.getElementById("studentIdNo");
            let button = document.getElementById("downloadbutton");
            let feediv = document.getElementById("feedebitdiv");
            container.style.display='block';
            studentidfiled.value = student.IDNo;
          
          

            button.style.display = 'block';
            feediv.style.display = 'block';
            tableContainer.style.display = 'block';
            balance.style.display = 'block';
  
          }else
          {
          if (containerno.style.display === "none" || div.style.display === "") {
            containerno.style.display = "block"; 
            button.style.display === 'none';// Show the div
          }
        }
          let ledger = status.ledgerdata;
          let deaddebit = status.deaddebitsdata;
        let concession = status.pendingConcessionsdata;
          let preceiptdata = status.pendingPReceiptsData;
        let receiptdata = status.cancelledReceiptData;

          let debitcredit = status.debitcreditdata[0];
          // Get first student
          
          var status = student.Status;
          if (status > 0) {
            feedebitbutton.style.display = 'block';

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
  
  function deletedebit() {
       showLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
   tableContainer.innerHTML = '';
   tableContainer.style.display = 'none';
    var id = document.getElementById('studentid').value;
    var dataurl = "{{ $dataurl }}";
    //alert(dataurl);
    // alert("batch :"+ id)
    if (id != '') {
      // alert("ghjghj"+id); 
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
          let container = document.getElementById("studentdetail");
          let balance = document.getElementById("balance");
          let student = status.studentdatas[0];
                
  
          if (student.IDNo > 0) {
            let studentidfiled = document.getElementById("studentIdNo");
            let button = document.getElementById("downloadbutton");
            let feediv = document.getElementById("feedebitdiv");
  
            studentidfiled.value = student.IDNo;
            button.style.display = 'block';
            feediv.style.display = 'block';
            tableContainer.style.display = 'block';
            balance.style.display = 'block';
          }
  
          let ledger = status.ledgerdata;
          let deaddebit = status.deaddebitsdata;
          let concession = status.pendingConcessionsdata;
          let receiptdata = status.cancelledReceiptData;
          
          let debitcredit = status.debitcreditdata[0];
          // Get first student
          var status = student.Status;
          if (status > 0) {
            var color = 'Green';
          }
          else { var color = 'Red'; }
  
          balance.innerHTML = `
       
                             <button class=" btn btn-info btn-sm" ">Debit : ${debitcredit.totaldebit}</button> <button class=" btn btn-success btn-sm">Credit : ${debitcredit.totalcredit}</button> <button class="btn btn-danger btn-sm">Balance : ${debitcredit.balance}</button>
                       </div>`;
          // Create dynamic HTML for the student data
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
         <p  class="text-reset d-block">Location  : <b> ${student.PermanentAddress}</b></p>
           </div>
       </div>
     </div>
       <div class="list-group-item">
                          <div class="row align-items-center">
                           <div class="col">
                            <b class="text-reset d-block">Scholarship  : ${student.ScolarShip}</b>
                              </div>
                          </div>
                          </div></div></div>
  
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
  
  let table = document.createElement('table');
  table.setAttribute('id', 'annualfeeTable');
  table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes
  table.id = "annualfeeTableForFixedHeader";
  // Create table header
  let thead = document.createElement('thead');
  thead.innerHTML = `<thead>
                      <tr>
                      <th>Receipt Date </th>
                        <th>Bank Date</th>
                        <th>Receipt  No</th>
                        <th>Particular</th>
                        <th>Trasaction</th>
                        <th>Semester</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Remarks</th>
                        <th class="w-1">Action</th>
                      </tr>
                    </thead >`;
  table.appendChild(thead);

  // Create table body
  let tbody = document.createElement('tbody');
  let bankdate;
  // Check if data is available

    hideLoader();
    
  showledgerdelete(ledger,deaddebit,concession,tableContainer,status,receiptdata); // Append table to the container
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


  function CreateFeedebit() {
    showLoader();
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitremarks = document.getElementById('debitremarks').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentIdNo').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var debitdate = document.getElementById('debitdate').value;
  
    if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '' &&debitdate!='') {
      fetch('/createfeedebitc', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitremarks: debitremarks, debitfee: debitfee, studentid: studentIdNo,debitdate:debitdate })
      })
        .then(response => response.json())
        .then(data => {
          hideLoader();
  
          let message = data[0]['message'];
          showSuccessMessage(message);
          feedebit();
      
        })
        .catch(error => console.error('Error:', error));
    }
    else {
      hideLoader();
      alert("Please Select   ");
    }
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
    var r = confirm("Do you really want to Delete Debit");
    if(r == true) 
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
}

function alldebitdelete() {
  
  var startdate = document.getElementById('startdate').value;
  var enddate = document.getElementById('enddate').value;
  var status = document.getElementById('status').value;
    var newstatus='0'
  if(status!='')
    {
   if(startdate!='' && enddate!='' )
   {
    newstatus='1'
   }
    }
    else{
      newstatus='1';
    }

  if( newstatus>0){
    showLoader();
    fetch('/loadAllDebitdelete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ startdate: startdate, enddate: enddate ,status:status})
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        const tableContainer = document.getElementById('annualfeeTableDiv');
        tableContainer.innerHTML = '';
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered');
        let thead = document.createElement('thead');
        thead.innerHTML = `<thead>
                      <tr> <th>Sr No</th>
                      <th>Receipt /Entry  Date </th>
                        <th>TransactionID</th><th>IDNo</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                     
                        <th>Head</th>
                        <th>Particular</th>
                        <th>Comments</th>
                        <th>Semester</th>
                       
                        <th>Credit</th>
                        <th>Mode of Payment</th>
                        <th class="w-1">Action</th>
                      </tr>
                    </thead >`;
        table.appendChild(thead);
        let tbody = document.createElement('tbody');
        let srno = 1;
        $color='';
        if (data.length > 0) {
          console.log(data);
          data.forEach(item => {
            if(item.ApprovedDebitStatus=='0')
              {
                 $status='Pending to Verify';
                 $color="#92f5e9";
              }
              else if(item.ApprovedDebitStatus=='1')
              {
                $status='Pending to Approve';
                $color="#f5f292";
              }
              else if(item.ApprovedDebitStatus=='2')
                {
                  $status='Approved';
                  $color="#bcf592";
                }
                else if(item.ApprovedDebitStatus=='-1')
                  {
                    $status='Deleted';
                    $color="red";
                  }
                else
                {
                  $status='';
                }
            let row = document.createElement('tr');
            row.style.backgroundColor = $color;
            let receiptnumber;
            let receiptdate = formatDate(item.DateEntry);
            // let Debit = item.Debit > 0 ? item.Debit : '';
            let Debit = item.Debit != null ? item.Debit : '';
          

            let ReceiptNo = item.ReceiptNo;

            if (ReceiptNo > 0) {
              receiptnumber = ReceiptNo;
            }
            else {
              receiptnumber = '';
            }
            if (item.TransactionDate) {
              bankdate = formatDate(item.TransactionDate);
            }
            else {
              bankdate = '';
            }
            deleteButton='';
            if (item.ApprovedDebitStatus<2 &&  item.ApprovedDebitStatus>=0 ) {
              deleteButton = `<button class="btn btn-danger btn-sm" 
                                   onclick="delete_debitentry('${item.TransactionID}', 
                                                              '${item.SemesterID}', 
                                                              '${item.DebitHead}', 
                                                              ${item.IDNo}, 
                                                              '${item.Session}', 
                                                              '${Debit}')">
                                   Delete
                               </button>`;
           }
            row.innerHTML = `
               <td>${srno}</td> 
              <td>${receiptdate}</td> 
              <td >${item.TransactionID}</td>
            
               <td>${item.IDNo}</td>
               <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.DebitHead}</td><td>${item.Particulars}</td>
                                 <td>${item.Comments}</td>
                                 
                                  <td>
                                  ${item.SemesterID}
                                  </td> 
                               <td> ${Debit}</td>
        
        
                               
                               <td>${$status}</td>
                                <td>
                                 ${deleteButton}
                                
               </td>`;
            srno++;
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
  else
  {
    showErrorMessage('start and end date required');
  }

}




  function delete_debitentry(TransactionID,SemesterID,DebitHead,IDNo,Session,Debit)
{
  var r = confirm("Do you really want to Delete by Mistake Entry");
  if(r == true) 
   {
  showLoader();
 if (TransactionID != '' && SemesterID != '' && DebitHead != '' && IDNo >= 0 && Session != '' && Debit != '') {
        fetch('/canceldeaddebit', {
           method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({TransactionID:TransactionID,SemesterID:SemesterID,DebitHead:DebitHead,IDNo:IDNo,Session:Session,Debit:Debit })
        })
          .then(response => response.json())
          .then(data => {
            hideLoader();
    
            let message = data[0]['message'];
            showSuccessMessage(message);
            alldebitdelete();
        
          })
          .catch(error => console.error('Error:', error));
      }
      else {
        hideLoader();
        alert("Please Select");
      }
    }
}



function alldebitshowReports() {
  
  var startdate = document.getElementById('startdate').value;
  var enddate = document.getElementById('enddate').value;
  var status = document.getElementById('status').value;
    var newstatus='0'
  if(status!='')
    {
   if(startdate!='' && enddate!='' )
   {
    newstatus='1'
   }
    }
    else{
      newstatus='1';
    }

  if( newstatus>0){
    showLoader();
    fetch('/showAllDebitReportsFromAPI', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ startdate: startdate, enddate: enddate ,status:status})
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        const tableContainer = document.getElementById('annualfeeTableDiv');
        tableContainer.innerHTML = '';
        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered');
        let thead = document.createElement('thead');
        thead.innerHTML = `<thead>
                      <tr> <th>Sr No</th>
                      <th>Receipt /Entry  Date </th>
                        <th>TransactionID</th><th>IDNo</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                     
                        <th>Head</th>
                        <th>Particular</th>
                        <th>Comments</th>
                        <th>Semester</th>
                       
                        <th>Credit</th>
                        <th>Mode of Payment</th>
                        <th class="w-1">Created by</th>
                      </tr>
                    </thead >`;
        table.appendChild(thead);
        let tbody = document.createElement('tbody');
        let srno = 1;
        $color='';
        if (data.length > 0) {
          console.log(data);
          data.forEach(item => {
            if(item.ApprovedDebitStatus=='0')
              {
                 $status='Pending to Verify';
                 $color="#92f5e9";
              }
              else if(item.ApprovedDebitStatus=='1')
              {
                $status='Pending to Approve';
                $color="#f5f292";
              }
              else if(item.ApprovedDebitStatus=='2')
                {
                  $status='Approved';
                  $color="#bcf592";
                }
                else if(item.ApprovedDebitStatus=='-1')
                  {
                    $status='Deleted';
                    $color="red";
                  }
                else
                {
                  $status='';
                }
            let row = document.createElement('tr');
            row.style.backgroundColor = $color;
            let receiptnumber;
            let receiptdate = formatDate(item.DateEntry);
            // let Debit = item.Debit > 0 ? item.Debit : '';
            let Debit = item.Debit != null ? item.Debit : '';
          

            let ReceiptNo = item.ReceiptNo;

            if (ReceiptNo > 0) {
              receiptnumber = ReceiptNo;
            }
            else {
              receiptnumber = '';
            }
            if (item.TransactionDate) {
              bankdate = formatDate(item.TransactionDate);
            }
            else {
              bankdate = '';
            }
            deleteButton='';
            if (item.ApprovedDebitStatus<2 &&  item.ApprovedDebitStatus>=0 ) {
              deleteButton = `<button class="btn btn-danger btn-sm" 
                                   onclick="delete_debitentry('${item.TransactionID}', 
                                                              '${item.SemesterID}', 
                                                              '${item.DebitHead}', 
                                                              ${item.IDNo}, 
                                                              '${item.Session}', 
                                                              '${Debit}')">
                                   Delete
                               </button>`;
           }
            row.innerHTML = `
               <td>${srno}</td> 
              <td>${receiptdate}</td> 
              <td >${item.TransactionID}</td>
            
               <td>${item.IDNo}</td>
               <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.DebitHead}</td><td>${item.Particulars}</td>
                                 <td>${item.Comments}</td>
                                 
                                  <td>
                                  ${item.SemesterID}
                                  </td> 
                               <td> ${Debit}</td>
        
        
                               
                               <td>${$status}</td>
                                <td>
          ${item.CreatedBy}
                                
               </td>`;
            srno++;
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
  else
  {
    showErrorMessage('start and end date required');
  }

}
