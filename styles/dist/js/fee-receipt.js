
// function formatDateTime(dateString, format = 'DD-MM-YYYY HH:mm:ss') {

//   const date = new Date(dateString);

//   const pad = (num) => num.toString().padStart(2, '0');

//   const day = pad(date.getDate());
//   const month = pad(date.getMonth() + 1); // Months are 0-based
//   const year = date.getFullYear();
//   const hours = pad(date.getHours());
//   const minutes = pad(date.getMinutes());
//   const seconds = pad(date.getSeconds());

//   if (format === 'DD-MM-YYYY HH:mm:ss') {
//     return `${day}-${month}-${year}`;
//   } else if (format === 'YYYY-MM-DD HH:mm:ss') {
//     return `${year}-${month}-${day}`;
//   } else if (format === 'MM/DD/YYYY') {
//     return `${month}/${day}/${year}`;
//   } else {
//     return date.toISOString();

//   }
// }

function feereceipt() {
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
  let feedebitbutton1 = document.getElementById("feedebitbutton1");
  feedebitbutton1.style.display = 'none';
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
          container.style.display = 'block';
          studentidfiled.value = student.IDNo;
          button.style.display = 'block';
          feediv.style.display = 'block';
          tableContainer.style.display = 'block';
          balance.style.display = 'block';

        } 
        else {
          if (containerno.style.display === "none" || div.style.display === "") {
            containerno.style.display = "block";
            button.style.display === 'none';// Show the div
          }
        }
        let ledger = status.ledgerdata;
        let deaddebit = status.deaddebitsdata;
        let concession = status.pendingConcessionsdata;
        let receiptdata = status.cancelledReceiptData;
          let preceiptdata = status.pendingPReceiptsData;
        let debitcredit = status.debitcreditdata[0];
        // Get first student

        var status = student.Status;
        if (status > 0) {
          feedebitbutton.style.display = 'block';
          feedebitbutton1.style.display = 'block';

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


  function debitheadchnage()
  {
    var selectedOptionText = document.getElementById("debithead").options[document.getElementById("debithead").selectedIndex].text;
    let debitparticulars = document.getElementById("debitparticulars");    
    debitparticulars.value = selectedOptionText;

  }

  function modeofpaymnet(value)
  {
    
    if(value!='Cash')
    {
        let modepaymnetdiv = document.getElementById("modepaymnetdiv");    
        modepaymnetdiv.style.display = 'block';
    }
    else{
        let modepaymnetdiv = document.getElementById("modepaymnetdiv");    
        modepaymnetdiv.style.display = 'none';
    }
    

  }

  function GenerateReceipt()
  {
    showLoader();
    var flag=0;
    //var autodebit = document.getElementById('autodebit').value;

    let checkbox = document.getElementById('autodebit');

      let isChecked = checkbox.checked ? 1 : 0;
// alert(isChecked);
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentIdNo').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var modeofpayment = document.getElementById('modeofpayment').value;
    var transactiondate = document.getElementById('transactiondate').value; 
    var nameofbank = document.getElementById('nameofbank').value;
    var transactionid = document.getElementById('transactionid').value; 

    if(modeofpayment!='Cash')
     {
     if (transactiondate != '' && nameofbank != '' && transactionid != '' && debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '')
    {
      flag=1;
    }
    }
    else
      {
      if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '')
      {
        flag=1;
      }

    }

   if(flag>0)
   {
      fetch('/generatefeereceipt', {
         method: 'POST',
         headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
           body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitfee: debitfee, studentid: studentIdNo,modeofpayment:modeofpayment,nameofbank:nameofbank,transactionid:transactionid,transactiondate:transactiondate,isChecked:isChecked })
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
    else 
    {
        hideLoader();
       alert("Please Select");
    }

  }

  function CreateReceipt()
  {
    showLoader();
    var flag=0;
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentIdNo').value;
    var debitparticulars = document.getElementById('debitparticulars').value;
    var modeofpayment = document.getElementById('modeofpayment').value;
    var transactiondate = document.getElementById('transactiondate').value; 
    var nameofbank = document.getElementById('nameofbank').value;
    var transactionid = document.getElementById('transactionid').value; 

    if(modeofpayment!='Cash')
     {
     if (transactiondate != '' && nameofbank != '' && transactionid != '' && debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '')
    {
      flag=1;
    }
    }
    else
      {
      if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '')
      {
        flag=1;
      }

    }

   if(flag>0)
   {
      fetch('/createfeereceipt', {
         method: 'POST',
         headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
           body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitfee: debitfee, studentid: studentIdNo,modeofpayment:modeofpayment,nameofbank:nameofbank,transactionid:transactionid,transactiondate:transactiondate })
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
    else 
    {
        hideLoader();
       alert("Please Select");
    }


  }


function debitheadchnage() {
  var selectedOptionText = document.getElementById("debithead").options[document.getElementById("debithead").selectedIndex].text;
  let debitparticulars = document.getElementById("debitparticulars");
  debitparticulars.value = selectedOptionText;

}

function modeofpaymnet(value) {

  if (value != 'Cash') {
    let modepaymnetdiv = document.getElementById("modepaymnetdiv");
    modepaymnetdiv.style.display = 'block';
  }
  else {
    let modepaymnetdiv = document.getElementById("modepaymnetdiv");
    modepaymnetdiv.style.display = 'none';
  }


}


function CreateReceipt() {
  showLoader();
  var flag = 0;
  var debithead = document.getElementById('debithead').value;
  var debitsession = document.getElementById('debitsession').value;
  var debitsem = document.getElementById('debitsemester').value;

  var debitfee = document.getElementById('debitfee').value;
  var studentIdNo = document.getElementById('studentIdNo').value;
  var debitparticulars = document.getElementById('debitparticulars').value;
  var modeofpayment = document.getElementById('modeofpayment').value;
  var transactiondate = document.getElementById('transactiondate').value;
  var nameofbank = document.getElementById('nameofbank').value;
  var transactionid = document.getElementById('transactionid').value;

  if (modeofpayment != 'Cash') {
    if (transactiondate != '' && nameofbank != '' && transactionid != '' && debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '') {
      flag = 1;
    }
  }
  else {
    if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '') {
      flag = 1;
    }

  }

  if (flag > 0) {
    fetch('/createfeereceipt', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitfee: debitfee, studentid: studentIdNo, modeofpayment: modeofpayment, nameofbank: nameofbank, transactionid: transactionid, transactiondate: transactiondate })
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
    alert("Please Select");
  }


}

function displayreceiptdetail() {
  showLoader();
  var startdate = document.getElementById('startdate').value;
  var enddate = document.getElementById('enddate').value;
  {
    fetch('/loadfeereceiptdetail', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ startdate: startdate, enddate: enddate })
    })


      .then(response => response.json())
      .then(data => {
        hideLoader();
        let feedetailData = data.pendingRecords;

        const tableContainer = document.getElementById('annualfeeTableDiv');
        tableContainer.innerHTML = '';


        let table = document.createElement('table');
        table.setAttribute('id', 'annualfeeTable');
        table.classList.add('table', 'table-bordered'); // Add Bootstrap or custom classes

        // Create table header
        let thead = document.createElement('thead');
        thead.innerHTML = `<thead>
                      <tr> <th>Sr No</th>
                      <th>Receipt /Entry  Date </th>
                        <th>Bank Date</th><th>IDNo</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                     
                        <th>Head</th>
                        <th>Particular</th>
                        <th>Trasaction</th>
                        <th>Semester</th>
                       
                        <th>Credit</th>
                        <th>Mode of Payment</th>
                        <th class="w-1" colspan=2>Action</th>
                      </tr>
                    </thead >`;
        table.appendChild(thead);

        // Create table body
        let tbody = document.createElement('tbody');

        // Check if data is available
        let srno = 1;
        if (feedetailData.length > 0) {
          // console.log(feedetailData);
          feedetailData.forEach(item => {

            let row = document.createElement('tr');
            let receiptnumber;
            let receiptdate = formatDate(item.DateEntry);
            // let Debit = item.Debit > 0 ? item.Debit : '';
            let Credit = item.Credit != null ? item.Credit : '';
            let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';

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

            row.innerHTML = `
               <td>${srno}</td> 
              <td>${receiptdate}</td> 
              <td >${bankdate}</td>
            
               <td>${item.IDNo}</td>
               <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.DebitHead}</td><td>${item.Particulars}</td>
                                 <td>${TransactionNo}</td>
                                 
                                  <td>
                                  ${item.SemesterID}
                                  </td> 
                               <td> ${item.Credit}</td>
        
        
                               
                               <td>${item.ModeOfPayment != null ? item.ModeOfPayment : ''}</td>
                                <td>
                                ${Credit > 0 ? `      
           <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-report" data-route='{{ route("viewreceiptentry") }}' target='_blank'  Id='rid' onclick="show_receiptentry('${item.ID}')">Generate</button>` : ''}  
                                
           </td><td><button class="btn btn-danger btn-sm"  onclick="delete_receiptentry('${item.ID}')">Delete</button> </td>`;





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
}

function daybookdata() {
   showLoader();
  var startdate = document.getElementById('startdate').value;
  var enddate = document.getElementById('enddate').value;
  var modeofpayment = document.getElementById('modeofpayment').value;
  var employeeid = document.getElementById('employeeid').value;
  var session = document.getElementById('session').value;
  var orderby = document.getElementById('orderby').value;
  var ledgername = document.getElementById('ledgername').value;
  let balance = document.getElementById("balance");

  balance.innerHTML = '';
  {
    fetch('/daybookreport', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({startdate:startdate,enddate:enddate,modeofpayment:modeofpayment,employeeid:employeeid,session:session,orderby:orderby,ledgername:ledgername})
    })


      .then(response => response.json())
      .then(daybook => {
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
                        <th>Bank Date</th><th>IDNo</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                     
                        <th>Head</th>
                        <th>Particular</th>
                        <th>Trasaction</th>
                        <th>Semester</th>
                       
                        <th>Credit</th>
                        <th>Mode of Payment</th>
                        <th class="w-1">Action</th>
                      </tr>
                    </thead >`;
        table.appendChild(thead);


        let tbody = document.createElement('tbody');
         data = daybook.daybookdata;
        let credittotal = daybook.credittotal[0];
   //console.log(credittotal);


        let srno = 1;
        if (data.length > 0) {
          console.log(data);
          data.forEach(item => {

            let row = document.createElement('tr');
            let receiptnumber;
            let receiptdate = formatDate(item.DateEntry);
            // let Debit = item.Debit > 0 ? item.Debit : '';
            let Credit = item.Credit != null ? item.Credit : '';
            let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';

            let ReceiptNo = item.ReceiptNo;

            if (ReceiptNo > 0) {
              receiptnumber = ReceiptNo;
            }
            else {
              receiptnumber = '';
            }
            if (item.DateEntrySubmission) {
              bankdate = formatDate(item.DateEntrySubmission);
            }
            else {
              bankdate = '';
            }
            balance.innerHTML = ` <button class=" btn btn-info btn-sm" ">Debit : ${credittotal.TotalCredit}</button> `;
            row.innerHTML = `
               <td>${srno}</td> 
              <td>${receiptdate}</td> 
              <td >${bankdate}</td>
            
               <td>${item.IDNo}</td>
               <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.LedgerName}</td><td>${item.Particulars}</td>
                                 <td>${TransactionNo}</td>
                                 
                                  <td>
                                  ${item.SemesterID}
                                  </td> 
                               <td> ${item.Credit}</td>
        
        
                               
                               <td>${item.ModeOfPayment != null ? item.ModeOfPayment : ''}</td>
                                <td>
                                ${Credit > 0 ? `      
                                 <button class="btn btn-primary btn-sm" data-route='{{ route("printreceipt") }}' target='_blank'  Id='rid' onclick="print_receipt('${receiptnumber}','${item.LedgerName}','${item.IDNo}','${item.Session}')"><svg  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                          </svg>Print</button>` : ''} 
                                
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

} 
function allSlipsShow() {
  showLoader();
  var startdate = document.getElementById('startdate').value;
  var enddate = document.getElementById('enddate').value;
  var modeofpayment = document.getElementById('modeofpayment').value;
  {
    fetch('/loadAllSlips', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({startdate:startdate,enddate:enddate,modeofpayment:modeofpayment})
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
                        <th>Bank Date</th><th>IDNo</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                     
                        <th>Head</th>
                        <th>Particular</th>
                        <th>Trasaction</th>
                        <th>Semester</th>
                       
                        <th>Credit</th>
                        <th>Mode of Payment</th>
                        <th class="w-1">Action</th>
                      </tr>
                    </thead >`;
        table.appendChild(thead);


        let tbody = document.createElement('tbody');

        let srno = 1;
        if (data.length > 0) {
          console.log(data);
          data.forEach(item => {

            let row = document.createElement('tr');
            let receiptnumber;
            let receiptdate = formatDate(item.DateEntry);
            // let Debit = item.Debit > 0 ? item.Debit : '';
            let Credit = item.Credit != null ? item.Credit : '';
            let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';

            let ReceiptNo = item.ReceiptNo;

            if (ReceiptNo > 0) {
              receiptnumber = ReceiptNo;
            }
            else {
              receiptnumber = '';
            }
            if (item.DateEntrySubmission) {
              bankdate = formatDate(item.DateEntrySubmission);
            }
            else {
              bankdate = '';
            }

            row.innerHTML = `
               <td>${srno}</td> 
              <td>${receiptdate}</td> 
              <td >${bankdate}</td>
            
               <td>${item.IDNo}</td>
               <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.LedgerName}</td><td>${item.Particulars}</td>
                                 <td>${TransactionNo}</td>
                                 
                                  <td>
                                  ${item.SemesterID}
                                  </td> 
                               <td> ${item.Credit}</td>
        
        
                               
                               <td>${item.ModeOfPayment != null ? item.ModeOfPayment : ''}</td>
                                <td>
                                ${Credit > 0 ? `      
                                
             <button class="btn btn-primary btn-sm" data-route='{{ route("printreceipt") }}' target='_blank'  Id='rid' onclick="print_receipt('${receiptnumber}','${item.LedgerName}','${item.IDNo}','${item.Session}')"><svg  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                          </svg>Print</button>` : ''}
                                
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

}


function delete_receiptentry(id) {
showLoader();


  fetch('/deletefeeslip', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ slipNumber: id })
  })

    .then(response => response.json())
    .then(status => {
hideLoader();
      let message = status[0]['message'];
        showSuccessMessage(message);
      
    });

}




// show modal receipt
function show_receiptentry(id) {
  showLoader();
  const tableContainer = document.getElementById('showSlipDataDiv');

  fetch('/loadSlipModalData', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ slipNumber: id })
  })

    .then(response => response.json())
    .then(status => {
      hideLoader();
      //  console.log(status.receiptDetails);

      let studentDetails = status.studentDetails[0];
      let receiptDetails = status.receiptDetails[0];

      let tableContent = `
           <div class="student-card">
               <div class="col-lg-12">
                   <div class="card">
                       <div class="row row-0">
                           <div class="col-3" style="text-align:center"><br>
                               <img src="http://erp.gku.ac.in:86/Images/Students/${studentDetails.Image}" style="border-radius:50%;height:100px;width:100px;" />
                           </div>
                           <div class="col">
                               <div class="card-body">
                                   <p><b>${receiptDetails.StudentName} (${studentDetails.IDNo})</b><br> 
                                   Uni Roll No : ${studentDetails.UniRollNo}<br>
                                   Class Roll No : ${studentDetails.ClassRollNo}<br>
                                   Batch : ${studentDetails.Batch}<br>
                                   Session : ${receiptDetails.Session}</p>
                               </div>
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
                                           <b>Father Name: ${receiptDetails.FatherName} &nbsp; &nbsp; | &nbsp; &nbsp; 
                                           Mother Name: ${studentDetails.MotherName}</b>
                                       </div>
                                   </div>
                               </div>
                               <div class="list-group-item">
                                   <div class="row align-items-center">
                                       <div class="col text-truncate">
                                           <b>Programme: ${receiptDetails.Course}</b>
                                       </div>
                                   </div>
                               </div>
                           </div>
            <input type="hidden" id="slipNumber" value="${receiptDetails.ID}">
                           <table class='table'>
                               <thead>
                                   <tr>
                                       <th>S. No.</th>
                                       <th>Particulars</th>
                                       <th>Debit Head</th>
                                       <th>Mode</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <tr>
                                       <th>1</th>`;

      if (receiptDetails.ModeOfPayment === 'Bank Transfer') {
        tableContent += `
                                       <th>
                                           Cheque/draft no.: <span style="border-bottom:1.5pt solid black;">${receiptDetails.ChequeDraftNo}</span><br>
                                           Bank Date: <span style="border-bottom:1.5pt solid black;">${receiptDetails.ChequeDraftDate}</span><br>
                                           Bank Name: <span style="border-bottom:1.5pt solid black;">${receiptDetails.ChequeDraftBank}</span>
                                       </th>`;
      } else {
        tableContent += `
               
               <th>${receiptDetails.DebitHead}</th>
               <th>${receiptDetails.Particulars}</th>
               <th>${receiptDetails.ModeOfPayment}</th>
               `;
      }

      tableContent += `
                                   </tr>
                                   <tr>
                                       <th colspan="3"><strong style="float:right;">Total:</strong></th>
                                       <th><strong>Rs. ${receiptDetails.Credit}/-</strong></th>
                                   </tr>
                               </tbody>
                           </table>
                       </div>
                   </div>
               </div>
              
           </div>`;

      tableContainer.innerHTML = tableContent;


    })
    .catch(error => {
      console.error('Error:', error);
      hideLoader();
    });


}

//generateSlip
function generateSlip() {
  let checkbox = document.getElementById('autodebit');

  let isChecked = checkbox.checked ? 1 : 0;
  var slipNumber = document.getElementById('slipNumber').value;
  showLoader();
  fetch('/generateSlip', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ slipNumber: slipNumber,isChecked:isChecked })
  })

    .then(response => response.json())
    .then(status => {
      hideLoader();
       console.log(status);
      showSuccessMessage(status[0].message);
      displayreceiptdetail();
    })
    .catch(error => {
      console.error('Error:', error);
      hideLoader();
    });
  }

  function cancelreceipt() {
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
showcancelreceipt(ledger,deaddebit,concession,tableContainer,status,receiptdata); // Append table to the container
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

function cancel_receipt(TransactionID,SemesterID,LedgerName,IDNo,Session,Credit,Particular)
{

  showLoader();
 
      document.getElementById("annualfeesrnoedit").value = TransactionID;

      document.getElementById("annualfeefacultytedit").value =LedgerName ;

      document.getElementById("annualfeesessionedit").value = Session;

      document.getElementById("annualfeeamountedit").value = Credit;

      document.getElementById("studetnid").innerHTML = IDNo;
      
      document.getElementById("studetnidtext").value = IDNo;

      document.getElementById("annualfeeheadedit").value = SemesterID;
      document.getElementById("particulars").value = Particular;
      
      hideLoader();
}
function CreateDeleteReceipt()
{
    var r = confirm("Do you really want to Cancel Receipt");
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
  fetch('/cancelreceiptdata', {
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
      cancelreceipt();
    })
    .catch(error => console.error('Error:', error));
  }
  else{
    showErrorMessage('Comment is required inputs')
  }
     }

}



// cencel Receipts Functions
function showPendingReceipts() {
  showLoader();
  const tableContainer = document.getElementById('annualfeeTableDiv');
  tableContainer.innerHTML = '';
  tableContainer.style.display = 'block';

    fetch('/showAllPendingCencelReceipts', {
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
                            <th>Receipt No </th>
                            <th>Cancel Receipt Date </th>
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
            let receiptdate = formatDate(item1.CancelReceiptDate);  
            row.innerHTML = `
            <td> ${item1.ReceiptNo}</td>        
            <td> ${receiptdate}</td>        
            <td>${item1.Particulars}</td>
            <td>${item1.StudentName}</td>
            <td>${item1.FatherName}</td>
             <td>${item1.SemesterID}</td> 
             <td>${item1.Credit}</td>
             <td>${item1.Comments}</td>
             <td> <button class="btn btn-secondary btn-sm" onclick="showStudentDetailsForCencelReceipts(${item1.IDNo},${item1.Credit},'${item1.Comments}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.LedgerName}',${item1.IDNo},'${item1.Session}',${item1.Credit});">View</button>    
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



function showStudentDetailsForCencelReceipts(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit)
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

                          <button class="btn btn-danger btn-sm"  onclick="verifyDebitCencelReceiptss(${TransactionID},${SemesterID},'${LedgerName}',${IDNo},'${Session}',${Debit})">Verify</button>
                            <br>
                            <br>
                        </div>
        </div>`;
       
  
      })
      .catch(error => {
        console.error('Error:', error);

      });
}


function verifyDebitCencelReceiptss(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit) {
  showLoader();
    fetch('/verifyDebitCencelReceipts', {
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
        showPendingReceipts();
        let container = document.getElementById("studentdetail");
        container.innerHTML = '';
    
      })
      .catch(error => console.error('Error:', error));
  }


  /////////////////////////Approve Functions//////////////////////////


  function showVerifiedReceipts() {
    showLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = '';
    tableContainer.style.display = 'block';
  
      fetch('/showAllVerifiedCencelReceipts', {
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
                              <th>Receipt No </th>
                              <th>Cancel Receipt Date </th>
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
              let receiptdate = formatDate(item1.CancelReceiptDate);  
              row.innerHTML = `
              <td> ${item1.ReceiptNo}</td>        
              <td> ${receiptdate}</td>        
              <td>${item1.Particulars}</td>
              <td>${item1.StudentName}</td>
              <td>${item1.FatherName}</td>
               <td>${item1.SemesterID}</td> 
               <td>${item1.Credit}</td>
               <td>${item1.Comments}</td>
               <td> <button class="btn btn-secondary btn-sm" onclick="showStudentDetailsForCencelReceiptsApprove(${item1.IDNo},${item1.Credit},'${item1.Comments}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.LedgerName}',${item1.IDNo},'${item1.Session}',${item1.Credit});">View</button>    
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
  
  
  
  function showStudentDetailsForCencelReceiptsApprove(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit)
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
  
                            <button class="btn btn-danger btn-sm"  onclick="approveDebitCencelReceiptss(${TransactionID},${SemesterID},'${LedgerName}',${IDNo},'${Session}',${Debit})">Approve</button>
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
  
  
  function approveDebitCencelReceiptss(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit) {
    showLoader();

      fetch('/approveDebitCencelReceipts', {
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
          showVerifiedReceipts();
          let container = document.getElementById("studentdetail");
          container.innerHTML = '';
      
        })
        .catch(error => console.error('Error:', error));
    }
  

    function allSlipsShowReports() {
      showLoader();
      var startdate = document.getElementById('startdate').value;
      var enddate = document.getElementById('enddate').value;
      var modeofpayment = document.getElementById('modeofpayment').value;
      {
        fetch('/loadAllfeereceiptdetail', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({startdate:startdate,enddate:enddate,modeofpayment:modeofpayment})
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
                            <th>Bank Date</th><th>IDNo</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                         
                            <th>Head</th>
                            <th>Particular</th>
                            <th>Trasaction</th>
                            <th>Semester</th>
                           
                            <th>Credit</th>
                            <th>Mode of Payment</th>
                            <th class="w-1">Created By</th>
                          </tr>
                        </thead >`;
            table.appendChild(thead);
    
    
            let tbody = document.createElement('tbody');
    
            let srno = 1;
            if (data.length > 0) {
              console.log(data);
              data.forEach(item => {
    
                let row = document.createElement('tr');
                let receiptnumber;
                let receiptdate = formatDate(item.DateEntry);
                // let Debit = item.Debit > 0 ? item.Debit : '';
                let Credit = item.Credit != null ? item.Credit : '';
                let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';
    
                let ReceiptNo = item.ReceiptNo;
    
                if (ReceiptNo > 0) {
                  receiptnumber = ReceiptNo;
                }
                else {
                  receiptnumber = '';
                }
                if (item.DateEntrySubmission) {
                  bankdate = formatDate(item.DateEntrySubmission);
                }
                else {
                  bankdate = '';
                }
    
                row.innerHTML = `
                   <td>${srno}</td> 
                  <td>${receiptdate}</td> 
                  <td >${bankdate}</td>
                
                   <td>${item.IDNo}</td>
                   <td>${item.StudentName}</td><td>${item.FatherName}</td><td>${item.LedgerName}</td><td>${item.Particulars}</td>
                                     <td>${TransactionNo}</td>
                                     
                                      <td>
                                      ${item.SemesterID}
                                      </td> 
                                   <td> ${item.Credit}</td>
            
            
                                   
                                   <td>${item.ModeOfPayment != null ? item.ModeOfPayment : ''}</td>
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
    
    }






















 function sync(id)
 {
showLoader();

    
        fetch('/syncfee', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({encryptedId:id})
        })

        .then(response => response.json())
      .then(data => {
        hideLoader();
        
        let message = data['message'];
          let flag = data['status'];
      if(flag!='failure')
      {
       showSuccessMessage("Receipt Created");
      }
      else
      {
   showErrorMessage(message);
      }
      onlinepaymentshow();

      })
      .catch(error => console.error('Error:', error));
 }











function   onlinepaymentshow() {
      showLoader();

      var startdate = document.getElementById('startdate').value;
      var enddate = document.getElementById('enddate').value;
      var rollno = document.getElementById('rollNo').value;
      var paymentstatus = document.getElementById('paymentstatus').value;
      {
        fetch('/loadonlinepaymentdetail', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({startdate:startdate,enddate:enddate,rollno:rollno,paymentstatus:paymentstatus})
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
                            <th>ID</th><th>IDNo</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                         
                            <th>Payment ID</th>
                            <th>Particular</th>
                            <th>Trasaction</th>
                            <th>Semester</th>
                           
                            <th>Credit</th>
                            <th>Mode of Payment</th>
                            <th class="w-1">Created By</th>
                          </tr>
                        </thead >`;
            table.appendChild(thead);
    
    
            let tbody = document.createElement('tbody');
    
            let srno = 1;
            if (data.length > 0) {
              //console.log(data);
              data.forEach(item => {
    
                let row = document.createElement('tr');
                let receiptnumber;
                let receiptdate = formatDate(item.Paymentdate);
                // let Debit = item.Debit > 0 ? item.Debit : '';
                let Credit = item.Credit != null ? item.Credit : '';
                let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';
    
                let ReceiptNo = item.ReceiptNo;
    
                if (ReceiptNo > 0) {
                  receiptnumber = ReceiptNo;
                }
                else {
                  receiptnumber = '';
                }
                if (item.Paymentdate) {
                  bankdate = formatDate(item.Paymentdate);
                }
                else {
                  bankdate = '';
                }
    
                row.innerHTML = `
                   <td>${srno}</td> 
                  <td>${receiptdate}</td> 
                  <td><button  class="btn btn-primary btn-xs" onclick="sync('${item.txnid}')"> ${item.id}</button>
                 </td>
                
                   <td>${item.IDNo}</td>
                   <td>${item.Name}</td><td>${item.Email}</td><td>  ${item.mihpayid} 
</td><td>    ${item.FeeType}</td>
                                     <td>${item.txnid}</td>
                                     
                                      <td>
                                      ${item.sem}
                                      

                                      </td> 
                                   <td> ${item.Amount}</td>
            
            
                                   
                                   <td>${item.Status}</td>
                                    <td>
                                  ${item.Remarks}
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
    
    }

function syncpayments() {
 
   showLoader(); // Show loader

var startdate = document.getElementById('startdate').value;
var enddate = document.getElementById('enddate').value;
var rollno = document.getElementById('rollNo').value;
var paymentstatus = document.getElementById('paymentstatus').value;

fetch('/loadonlinepaymentdetail', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  },
  body: JSON.stringify({
    startdate: startdate,
    enddate: enddate,
    rollno: rollno,
    paymentstatus: paymentstatus
  })
})
.then(response => response.json())
.then(data => {
  // Create array of fetch promises
  const syncRequests = data.map(entry => {
    return fetch('/syncfee', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ encryptedId: entry.txnid })
    });
  });

  // Wait for all to finish
  Promise.all(syncRequests)
    .then(responses => {
      hideLoader(); // Hide loader
     showSuccessMessage('Synchronization Success');
    })
    .catch(error => {
      hideLoader();
      console.error('Error syncing records:', error);
      const messageBox = document.getElementById('message');
      messageBox.innerText = 'An error occurred while syncing records.';
      messageBox.style.display = 'block';
    });
});
    
    }


    
 
 function   onlinepaymentshowkotak() {
      showLoader();

      var startdate = document.getElementById('startdate').value;
      var enddate = document.getElementById('enddate').value;
      var rollno = document.getElementById('rollNo').value;
      var paymentstatus = document.getElementById('paymentstatus').value;
      {
        fetch('/loadonlinepaymentdetailkotak', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({startdate:startdate,enddate:enddate,rollno:rollno,paymentstatus:paymentstatus})
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
                            <th>ID</th><th>IDNo</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                         
                            <th>Payment ID</th>
                            <th>Particular</th>
                            <th>Trasaction</th>
                            <th>Semester</th>
                           
                            <th>Credit</th>
                            <th>Mode of Payment</th>
                            <th class="w-1">Created By</th>
                          </tr>
                        </thead >`;
            table.appendChild(thead);
    
    
            let tbody = document.createElement('tbody');
    
            let srno = 1;
            if (data.length > 0) {
              //console.log(data);
              data.forEach(item => {
    
                let row = document.createElement('tr');
                let receiptnumber;
                let receiptdate = formatDate(item.Paymentdate);
                // let Debit = item.Debit > 0 ? item.Debit : '';
                let Credit = item.Credit != null ? item.Credit : '';
                let TransactionNo = item.TransactionNo > 0 ? item.TransactionNo : '';
    
                let ReceiptNo = item.ReceiptNo;
    
                if (ReceiptNo > 0) {
                  receiptnumber = ReceiptNo;
                }
                else {
                  receiptnumber = '';
                }
                if (item.Paymentdate) {
                  bankdate = formatDate(item.Paymentdate);
                }
                else {
                  bankdate = '';
                }
    
                row.innerHTML = `
                   <td>${srno}</td> 
                  <td>${receiptdate}</td> 
                  <td><button  class="btn btn-primary btn-xs" onclick="syncrazorpay('${item.txnid}')"> ${item.id}</button>
                 </td>
                
                   <td>${item.IDNo}</td>
                   <td>${item.Name}</td><td>${item.Email}</td><td>  ${item.mihpayid} 
</td><td>    ${item.FeeType}</td>
                                     <td>${item.txnid}</td>
                                     
                                      <td>
                                      ${item.sem}
                                      

                                      </td> 
                                   <td> ${item.Amount}</td>
            
            
                                   
                                   <td>${item.Status}</td>
                                    <td>
                                  ${item.Remarks}
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
    
    }

     function syncrazorpay(id)
 {
 // alert(id);
showLoader();

           fetch('/checkPaymentStatusg', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({encryptedId:id})
        })

        .then(response => response.json())
      .then(data => {
        console.log(data);
        //hideLoader();
        
        let message = data['message'];
          let flag = data['status'];
      if(flag!='failure')
      {
       showSuccessMessage("Receipt Created");
      }
      else
      {
   showErrorMessage(message);
      }
     onlinepaymentshowkotak();

      })
      .catch(error => console.error('Error:', error));
 }


//bulk

    function syncpaymentskotak() {
 
showLoader(); // Show loader

var startdate = document.getElementById('startdate').value;
var enddate = document.getElementById('enddate').value;
var rollno = document.getElementById('rollNo').value;
var paymentstatus = document.getElementById('paymentstatus').value;

fetch('/loadonlinepaymentdetailkotak', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  },
  body: JSON.stringify({
    startdate: startdate,
    enddate: enddate,
    rollno: rollno,
    paymentstatus: paymentstatus
  })
})
.then(response => response.json())
.then(data => {
  // Create array of fetch promises
  const syncRequests = data.map(entry => {
    return fetch('/checkPaymentStatusg', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ encryptedId: entry.txnid })
    });
  });

  // Wait for all to finish
  Promise.all(syncRequests)
    .then(responses => {
      hideLoader(); // Hide loader
     showSuccessMessage('Synchronization Success');
    })
    .catch(error => {
      hideLoader();
      console.error('Error syncing records:', error);
      const messageBox = document.getElementById('message');
      messageBox.innerText = 'An error occurred while syncing records.';
      messageBox.style.display = 'block';
    });
});
    
    }