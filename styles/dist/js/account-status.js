function showledger(ledger,deaddebit,concession,tableContainer,caceledreceipt,preceiptdata)
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
  // Check if data is available



 if (preceiptdata.length > 0) {
    preceiptdata.forEach(item3 => {
      let row = document.createElement('tr');
      row.style.backgroundColor = 'rgb(201 184 101)';
      let receiptdate = formatDate(item3.DateEntry);  
      let status;


      if (item3.Status==0) {
        status='Pending to Generate';
      }
      else if(item3.Status==1)
      
    {
      status='Pending For Approval';
    }
    else
      {
        status='Approved';

      }

      row.innerHTML = `
      <td> ${receiptdate}</td>  <td></td> 
      <td>${item3.Particulars}</td>
          <td></td> <td>  New Receipt</td>
                         <td> Credit</td>
                       
                          <td>
                          ${item3.SemesterID}
                          </td> 
                      

                       <td></td>
 <td>${item3.Credit}</td>

                       
                       <td>${item3.ModeOfPayment}</td>
                        <td>  
                          <button class="btn btn-info btn-sm"> ${status}</button>
                        
                        
    </td>`;
    tbody.appendChild(row);
  });
}





















  if (caceledreceipt.length > 0) {
    caceledreceipt.forEach(item3 => {
      let row = document.createElement('tr');
      row.style.backgroundColor = 'rgb(201 184 101)';
      let receiptdate = formatDate(item3.DateEntry);  
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
      <td></td>
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
      <td>${item2.LedgerName}</td>      <td ></td> <td>${item2.Particulars}</td>
                         <td>${item2.Particulars} Added</td>
                       
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
        <td>${item1.DebitHead}</td> <td></td> <td>${item1.Particulars}</td>
                         <td>Debit For Delete </td>
                       
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
      <td >${bankdate}</td>
      <td>${item.LedgerName}</td>
       <td>${receiptnumber}</td><td>${item.Particulars}</td>
                         <td>${item.TransactionType}</td>
                       
                          <td>
                          ${item.Semester}-${item.SemesterID}
                          </td> <td>${Debit}</td>
                       <td>${Credit}</td>


                       
                       <td>${item.Remarks != null ? item.Remarks : ''}</td>
                        <td>
                        ${Credit > 0 ? `

                          <button class="btn btn-primary btn-sm" data-route='{{ route("printreceipt") }}' target='_blank'  Id='rid' onclick="print_receipt('${receiptnumber}','${item.LedgerName}','${item.IDNo}','${item.Session}')"><svg  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                          </svg>Print</button>` : ''


        }  
                        
    </td>`;
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
