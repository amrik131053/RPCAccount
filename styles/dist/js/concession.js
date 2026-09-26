function CreateFeeConcession() {
    //showLoader();
    var debithead = document.getElementById('debithead').value;
    var debitsession = document.getElementById('debitsession').value;
    var debitsem = document.getElementById('debitsemester').value;
    var debitremarks = document.getElementById('debitremarks').value;
    var debitfee = document.getElementById('debitfee').value;
    var studentIdNo = document.getElementById('studentIdNo').value;
    var debitparticulars = document.getElementById('debitparticulars').value;



    if (debithead != '' && debitsession != '' && debitsem != '' && debitfee >= 0 && studentIdNo != '' &&debitparticulars!='') {
      fetch('/createfeedeconcession', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ debitparticulars: debitparticulars, debithead: debithead, debitsession: debitsession, debitsem: debitsem, debitremarks: debitremarks, debitfee: debitfee, studentid: studentIdNo })
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

  function ConcessionVerify() {
    showLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = '';
    tableContainer.style.display = 'block';
  
      fetch('/concessionforverification', {
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
                               <th>ID </th>
                              <th> Date </th>
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
                <td> ${item1.ConcessionID}</td>  
              <td> ${receiptdate}</td>        
              <td>${item1.Particulars}</td>
              <td>${item1.StudentName}</td>
              <td>${item1.FatherName}</td>
               <td>${item1.SemesterID}</td> 
               <td>${item1.Debit}</td>
               <td>${item1.Remarks}</td>
               <td> <button class="btn btn-secondary btn-sm" onclick="showconcesiontudentDetails(${item1.IDNo},${item1.Debit},'${item1.Remarks}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.DebitHead}',${item1.IDNo},'${item1.Session}',${item1.Debit},${item1.ConcessionID});">View</button>    
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


  function showconcesiontudentDetails(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit,ConcessionID)
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
  <tr><th>ID</th>
  <th>Semester</th>
  <th>Concession </th>
  <th>Comments</th>
  </tr>
  <tr>
  
  <td>${ConcessionID}</td>
  <td>${sedmid}</td>
  <td>${debit}</td>
  <td>${comments}</td>
  </tr>
  </table>
    

          </div>
            <div class="card-footer">

                          <button class="btn btn-danger btn-sm"  onclick="verifyConcession(${ConcessionID})">Verify</button>
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

function verifyConcession(ConcessionID) {
  showLoader();
    fetch('/verifyConcessiondata', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ ConcessionID:ConcessionID})
    })
      .then(response => response.json())
      .then(data => {
        hideLoader();
        // console.log(data);
        let message = data[0]['message'];
        showSuccessMessage(message);
        ConcessionVerify();
        let container = document.getElementById("studentdetail");
        container.innerHTML = '';
    
      })
      .catch(error => console.error('Error:', error));
  }

  function ConcessionApprove() {
    showLoader();
    const tableContainer = document.getElementById('annualfeeTableDiv');
    tableContainer.innerHTML = '';
    tableContainer.style.display = 'block';
  
      fetch('/concessionforapproval', {
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
                               <th>ID </th>
                              <th> Date </th>
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
                <td> ${item1.ConcessionID}</td>  
              <td> ${receiptdate}</td>        
              <td>${item1.Particulars}</td>
              <td>${item1.StudentName}</td>
              <td>${item1.FatherName}</td>
               <td>${item1.SemesterID}</td> 
               <td>${item1.Debit}</td>
               <td>${item1.Remarks}</td>
               <td> <button class="btn btn-secondary btn-sm" onclick="showconcesiontudentverDetails(${item1.IDNo},${item1.Debit},'${item1.Remarks}',${item1.SemesterID},${item1.TransactionID},${item1.SemesterID},'${item1.DebitHead}',${item1.IDNo},'${item1.Session}',${item1.Debit},${item1.ConcessionID});">View</button>    
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

  function showconcesiontudentverDetails(id,debit,comments,sedmid,TransactionID,SemesterID,LedgerName,IDNo,Session,Debit,ConcessionID)
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
    <tr><th>ID</th>
    <th>Semester</th>
    <th>Concession </th>
    <th>Comments</th>
    </tr>
    <tr>
    
    <td>${ConcessionID}</td>
    <td>${sedmid}</td>
    <td>${debit}</td>
    <td>${comments}</td>
    </tr>
    </table>
      
  
            </div>
              <div class="card-footer">
  
                            <button class="btn btn-danger btn-sm"  onclick="approveConcession(${ConcessionID})">Verify</button>
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

  function approveConcession(ConcessionID) {
    showLoader();
      fetch('/approvalConcessiondata', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ConcessionID:ConcessionID})
      })
        .then(response => response.json())
        .then(data => {
          hideLoader();
          // console.log(data);
          let message = data[0]['message'];
          showSuccessMessage(message);
          ConcessionApprove();
          let container = document.getElementById("studentdetail");
          container.innerHTML = '';
      
        })
        .catch(error => console.error('Error:', error));
    }
  

    

    function showallConcessionsWithDetes() {
    
  
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
          fetch('/showAllConcessionsFromAPI', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ startdate: startdate, enddate: enddate ,status:status})
          })
          .then(response => response.json())
          .then(status => {
            const tableContainer = document.getElementById('annualfeeTableDiv');
            tableContainer.innerHTML = '';
    
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
                               
                                <th>Create Date </th>
                                  <th>Particular</th>
                                  <th>Name</th>
                                  <th>Father Name</th>
                                  <th>Semester</th>
                                  <th>Amount</th>
                                  <th>Remarks</th>
                                  <th>Status</th>
                                  <th class="w-1">Action</th>
                                </tr>
                              </thead >`;
            }
            table.appendChild(thead);
            let tbody = document.createElement('tbody');
            if (deaddebit.length > 0) {
              deaddebit.forEach(item1 => {
                  // let clr = item1.ConcessionStatus === '0' ? "#c9e6f8" :
                  //           item1.ConcessionStatus === '1' ? "#eff6ae" : "#a4e5c0";
          
                            if(item1.ConcessionStatus=='0')
                              {
                                 $status='Pending to Verify';
                                 $color="#92f5e9";
                              }
                              else if(item1.ConcessionStatus=='1')
                              {
                                $status='Pending to Approve';
                                $color="#f5f292";
                              }
                              else if(item1.ConcessionStatus=='2')
                                {
                                  $status='Approved';
                                  $color="#bcf592";
                                }else if(item1.ConcessionStatus=='-1')
                                  {
                                    $status='Deleted';
                                    $color="red";
                                  }
                                else
                                {
                                  $status='';
                                }
                               

                                deleteButton='';
                                if (item1.ConcessionStatus<2 &&  item1.ConcessionStatus>=0 ) {
                                  deleteButton = `<button class="btn btn-danger btn-sm" onclick="concessionDeleteWithStatus(${item1.ConcessionID});">Delete</button>`;
                               }


                  let row = document.createElement('tr');
                  row.style.backgroundColor = $color;
          
                  let DateEntry = formatDate(item1.DateEntry);
                  row.innerHTML = `
                      <td>${DateEntry}</td>        
                      <td>${item1.Particulars}</td>
                      <td>${item1.StudentName}</td>
                      <td>${item1.FatherName}</td>
                      <td>${item1.SemesterID}</td> 
                      <td>${item1.Debit}</td>
                      <td>${item1.Remarks}</td>
                      <td>${$status}</td>
                      <td>
                          ${deleteButton}
                      </td>
                  `;
                  tbody.appendChild(row);
              });
          } else {
              let row = document.createElement('tr');
              row.innerHTML = `<td colspan='9' style="text-align:center;">No Record Found</td>`;
              tbody.appendChild(row);
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
          showErrorMessage('start and end date required');
        }

    }

    function concessionDeleteWithStatus(ID){
      // alert(ID);
      showLoader();
      fetch('/deleteConcessionWithID', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ConcessionID:ID})
      })
        .then(response => response.json())
        .then(data => {
          hideLoader();
          console.log(data);
          let message = data[0]['message'];
          showSuccessMessage(message);
          showallConcessionsWithDetes();
      
      
        })
        .catch(error => console.error('Error:', error));
    }
    

    
    function searchAllConcessionsReport() {
    
  
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
        fetch('/showAllConcessionsReportsFromAPI', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ startdate: startdate, enddate: enddate ,status:status})
        })
        .then(response => response.json())
        .then(status => {
          const tableContainer = document.getElementById('annualfeeTableDiv');
          tableContainer.innerHTML = '';
  
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
                             
                              <th>Create Date </th>
                                <th>Particular</th>
                                <th>Name</th>
                                <th>Father Name</th>
                                <th>Semester</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Created By</th>
                               
                              </tr>
                            </thead >`;
          }
          table.appendChild(thead);
          let tbody = document.createElement('tbody');
          if (deaddebit.length > 0) {
            deaddebit.forEach(item1 => {
                // let clr = item1.ConcessionStatus === '0' ? "#c9e6f8" :
                //           item1.ConcessionStatus === '1' ? "#eff6ae" : "#a4e5c0";
        
                $color="";
                          if(item1.ConcessionStatus=='0')
                            {
                               $status='Pending to Verify';
                               $color="#92f5e9";
                            }
                            else if(item1.ConcessionStatus=='1')
                            {
                              $status='Pending to Approve';
                              $color="#f5f292";
                            }
                            else if(item1.ConcessionStatus=='2')
                              {
                                $status='Approved';
                                $color="#bcf592";
                              }else if(item1.ConcessionStatus=='-1')
                                {
                                  $status='Deleted';
                                  $color="red";
                                }
                              else
                              {
                                $status='';
                              }
                             

                              deleteButton='';
                              if (item1.ConcessionStatus<2 &&  item1.ConcessionStatus>=0 ) {
                                deleteButton = `<button class="btn btn-danger btn-sm" onclick="concessionDeleteWithStatus(${item1.ConcessionID});">Delete</button>`;
                             }


                let row = document.createElement('tr');
                row.style.backgroundColor = $color;
        
                let DateEntry = formatDate(item1.DateEntry);
                row.innerHTML = `
                    <td>${DateEntry}</td>        
                    <td>${item1.Particulars}</td>
                    <td>${item1.StudentName}</td>
                    <td>${item1.FatherName}</td>
                    <td>${item1.SemesterID}</td> 
                    <td>${item1.Debit}</td>
                    <td>${item1.Remarks}</td>
                    <td>${$status}</td>
                    <td>${item1.CreatedBy}</td>
                   
                `;
                tbody.appendChild(row);
            });
        } else {
            let row = document.createElement('tr');
            row.innerHTML = `<td colspan='9' style="text-align:center;">No Record Found</td>`;
            tbody.appendChild(row);
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
        showErrorMessage('start and end date required');
      }

  }