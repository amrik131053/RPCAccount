function ShowLoader() {
  const spinner = document.getElementById("ajax-loader");
  if (spinner) spinner.style.display = "flex";
}

// ✅ Hide loader
function HideLoader() {
  const spinner = document.getElementById("ajax-loader");
  if (spinner) spinner.style.display = "none";
}
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
function account_status() {
 const id = document.getElementById('studentid').value.trim();
  if (!id) {
   showErrorMessage("Enter Detail");
    return;
  }
  ShowLoader();
  Ledger_Status(id); 
  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 1,
      id: id
    },
    success: function (response) {
      HideLoader();
      document.getElementById("studentdetail").innerHTML = response;
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      alert("An error occurred while fetching data.");
    },
    complete: function () {
      
    }
  });
}


function deletedebit() {
 const id = document.getElementById('studentid').value.trim();
  if (!id) {
   showErrorMessage("Enter Detail");
    return;
  }
  ShowLoader();
  delete_Ledger_Status(id); 
  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 1,
      id: id
    },
    success: function (response) {
      HideLoader();
      document.getElementById("studentdetail").innerHTML = response;
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      HideLoader();
      alert("An error occurred while fetching data.");
    },
    complete: function () {
      
      HideLoader();
    }
  });
}





function  delete_Ledger_Status(id)
 {

  if (!id) {
   showErrorMessage("Enter Detail");
   
    return;
  }

  ShowLoader();



  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 2.3,
      id: id
    },
    success: function (response) {
      HideLoader();
      document.getElementById("ledger_delete").innerHTML = response;
      const table = document.querySelector('#ledger_delete table');
      enableTableSorting(table);
    },
    error: function (xhr, status, error) {
      HideLoader();
      console.error("AJAX Error:", error);
      alert("An error occurred while fetching data.");
    },
    complete: function () {
      HideLoader();
     
    }
  });
 }

 function delete_debit(TransactionID,SemesterID,LedgerName,IDNo,Session,Debit,Particular,LedgerID)
{
 
      document.getElementById("annualfeesrnoedit").value = TransactionID;
      document.getElementById("ledgerid").value = LedgerID;

      document.getElementById("annualfeefacultytedit").value =LedgerName ;

      document.getElementById("annualfeesessionedit").value = Session;

      document.getElementById("annualfeeamountedit").value = Debit;

      document.getElementById("studetnid").innerHTML = IDNo;
      
      document.getElementById("studetnidtext").value = IDNo;

      document.getElementById("annualfeeheadedit").value = SemesterID;
      document.getElementById("particulars").value = Particular;
      
  
}



 function  Ledger_Status(id)
 {

  if (!id) {
   showErrorMessage("Enter Detail");
   
    return;
  }

 

  ShowLoader();
  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 2,
      id: id
    },
    success: function (response) {
      HideLoader();
      document.getElementById("ledger").innerHTML = response;

       const table = document.querySelector('#ledger table');
                  enableTableSorting(table);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", error);
      alert("An error occurred while fetching data.");
    },
    complete: function () {
     
    }
  });
 }

function account_comment() {




  const id = document.getElementById('studentid').value.trim();

  if (!id) {
   showErrorMessage("Enter Detail");

    return;
  }


  ShowLoader();
  Ledger_Status(id); // Make sure this is defined

  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 3,
      id: id
    },
    success: function (response) {
      HideLoader();
      document.getElementById("studentdetail").innerHTML = response;
    },
    error: function (xhr, status, error) {
      HideLoader();
      console.error("AJAX Error:", error);
      alert("An error occurred while fetching data.");
    },
    complete: function () {
      
      HideLoader();
    }
  });
}


function submitaccountComents(id)

{

  var accountComments = document.getElementById('accountComments').value;
  ShowLoader();
  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 5,
      id: id,accountComments:accountComments
    },
    success: function (response) {
HideLoader();
if(response==1)
  {
    account_comment();
    showSuccessMessage("Updated");
    
  }
  else if (response==0){
    showErrorMessage('Unable To update');
  }
  
  else
  {
    showErrorMessage('Invalid Comment kindly check');
  }
  
},
error: function (xhr, status, error) {
  HideLoader();
  console.error("AJAX Error:", error);
  alert("An error occurred while fetching data.");
},
complete: function () {
      HideLoader();
      
    }
  })

}

function submitaccountComentsadmin(id)
{
  ShowLoader();
  var accountComments = document.getElementById('accountCommentsadmin').value;
var accountCommentsold = document.getElementById('accountCommentsadminold').value;



  $.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 6,
      id: id,accountComments:accountComments,accountCommentsold:accountCommentsold
    },
    success: function (response) {
      HideLoader();
      // console.log(response);
      if(response==1)
        {
          showSuccessMessage("Updated");
          account_comment();
        }
        else if (response==0){
          showErrorMessage('Unable To update');
        }
        else
        {
          showErrorMessage('Invalid Comment kindly check');
        }
        
      },
      error: function (xhr, status, error) {
        HideLoader();
        console.error("AJAX Error:", error);
        alert("An error occurred while fetching data.");
      },
      complete: function () {
        
        HideLoader();
    }
  })

}


  // load Progrms with session 

function loadPrograms(id) {
  ShowLoader();
$.ajax({
    url: 'action.php',
    type: 'POST',
    data: {
      code: 4,
      id: id
    },
    success: function (response) {
HideLoader();
$("#ProgramDropdown").html("");
$("#ProgramDropdown").html(response);

},
error: function (xhr, status, error) {
  HideLoader();
  console.error("AJAX Error:", error);
  alert("An error occurred while fetching data.");
},
complete: function () {
      HideLoader();
      
    }
  });
    
  }





function  print_receipt(ReceiptNo,LedgerName,IDNo,session)
{

	window.open(
  'print_receipt.php?SlipID=' + encodeURIComponent(ReceiptNo) +
  '&ledgerName=' + encodeURIComponent(LedgerName) +
  '&session=' + encodeURIComponent(session) +
  '&IDNo=' + encodeURIComponent(IDNo),
  '_blank'
);

}

function  print_receipt_refund(ReceiptNo,LedgerName,IDNo,session)
{ 

  window.open(
  'print_receipt_refund.php?SlipID=' + encodeURIComponent(ReceiptNo) +
  '&ledgerName=' + encodeURIComponent(LedgerName) +
  '&session=' + encodeURIComponent(session) +
  '&IDNo=' + encodeURIComponent(IDNo),
  '_blank'
);

}





