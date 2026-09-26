function showcancelreceipt(ledger,deaddebit,concession,tableContainer,statusstudent,caceledreceipt)
{
   
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
                        <th>Head</th>
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

  if (caceledreceipt.length > 0) {
    caceledreceipt.forEach(item3 => {
      let row = document.createElement('tr');
      row.style.backgroundColor = 'rgb(201 184 101)';
      let receiptdate = formatDate(item3.DateEntry);  
      let bankdate = formatDate(item3.ChequeDraftDate);  
      let status;


      if (item3.ApprovedStatus==0) {
        status='Pending to Verify';
      }
      else if(item3.ApprovedStatus==1)
      
    {
      status='Pending For Approval';
    }
    else
      {
        status='Approved';

      }

      row.innerHTML = `
      <td> ${receiptdate}</td> 
      <td>${item3.ChequeDraftDate}</td>
      <td>${item3.LedgerName}</td>      <td>${item3.ReceiptNo}</td> <td>${item3.Particulars}</td>
                         <td>${item3.Particulars} Cancel Receipt</td>
                       
                          <td>
                          ${item3.SemesterID}
                          </td> 
                      

                       <td></td>
 <td>${item3.Credit}</td>

                       
                       <td>${item3.Comments}</td>
                        <td>  
                          <button class="btn btn-info btn-sm"> ${status}</button>
                        
                        
    </td>`;
    tbody.appendChild(row);
  });
}

  // Check if data is available
  if (concession.length > 0) {
    concession.forEach(item2 => {
      let row = document.createElement('tr');
      row.style.backgroundColor = '#64cf64';
      let receiptdate = formatDate(item2.DateEntry);  
      let status;


      if (item2.ConcessionStatus==0) {
        status='Pending to Verify';
      }
      else if(item2.ConcessionStatus==1)
      
    {
      status='Pending For Approval';
    }
    else
      {
        status='Approved';

      }

      row.innerHTML = `
      <td> ${receiptdate}</td> 
      <td ></td>
       <td>${item2.LedgerName}</td> <td></td>  <td>${item2.Particulars}</td>
                         <td>Concession Added</td>
                       
                          <td>
                          ${item2.SemesterID}
                          </td> 
                       <td>${item2.Debit}</td>

                       <td></td>


                       
                       <td>${item2.Remarks}</td>
                        <td>  
                          <button class="btn btn-info btn-sm"> ${status}</button>
                        
                        
    </td>`;
    tbody.appendChild(row);
  });
}


  if (deaddebit.length > 0) {
    deaddebit.forEach(item1 => {
      let row = document.createElement('tr');
      row.style.backgroundColor = '#eb4040bd'
      let receiptdate = formatDate(item1.DateEntry);  
      let status;


      if (item1.ApprovedDebitStatus==0) {
        status='Pending to Verify';
      }
      else{
        status='Pending For Approval';

      }

      row.innerHTML = `
      <td> ${receiptdate}</td> 
      <td ></td>
         <td>${item1.DebitHead}</td>   <td></td>  <td>${item1.Particulars}</td>
                         <td>Added For Delete </td>
                       
                          <td>
                          ${item1.SemesterID}
                          </td> 
                       <td>${item1.Debit}</td>

                       <td></td>


                       
                       <td>${item1.Comments}</td>
                        <td>  
                          <button class="btn btn-info btn-sm"> ${status}</button>
                        
                        
    </td>`;
    tbody.appendChild(row);
  });
}
if (ledger.length > 0) {
    ledger.forEach(item => {
      let row = document.createElement('tr');
      let receiptnumber;
      let receiptdate = formatDate(item.DateEntry);
      // let Debit = item.Debit > 0 ? item.Debit : '';
      let Debit = item.Debit!=null ? item.Debit : '';
      let Credit = item.Credit > 0 ? item.Credit : '';

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
      <td>${receiptdate}</td> 
      <td >${bankdate}</td>      <td>${item.LedgerName}</td>
      <td>${receiptnumber}</td><td>${item.Particulars}</td>
                         <td>${item.TransactionType}</td>
                       
                          <td>
                          ${item.Semester}-${item.SemesterID}
                          </td> <td>${Debit}</td>
                       <td>${Credit}</td>


                       
                       <td>${item.Remarks != null ? item.Remarks : ''}</td>
                       ${(statusstudent>0)?`<td> 
                        ${(item.Credit!=null)? `

                         <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-report"   Id='rid' onclick="cancel_receipt('${item.TransactionID}','${item.SemesterID}','${item.LedgerName}','${item.IDNo}','${item.Session}','${item.Credit}','${item.Particulars}')">Cancel Receipt</button>` : ''

        }  
                        
    </td>`:''}`
      tbody.appendChild(row);
    });
  } else {
    hideLoader();
    // Show message if no data is found
    let row = document.createElement('tr');
    row.innerHTML = `<td colspan="8" class="text-center">No records found</td>`;
    tbody.appendChild(row);
  }
  table.appendChild(tbody);
  tableContainer.appendChild(table);
}
