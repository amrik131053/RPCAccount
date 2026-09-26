  
  <?php
   $sql = "SELECT * FROM ConcessionEntries WHERE IDNo = ? AND ConcessionStatus BETWEEN 0 AND 1";
    $stmtconcession = sqlsrv_query($conn, $sql, [$IDNo]);

    if ($stmtconcession === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $concessions = [];
    while ($row = sqlsrv_fetch_array($stmtconcession, SQLSRV_FETCH_ASSOC)) {
        $concessions[] = $row;
    }

  
    $sql = "SELECT * FROM DeadDebits WHERE IDNo = ? AND (DebitStatus IS NULL OR DebitStatus BETWEEN 0 AND 1)
ORDER BY DateEntry DESC;";
    $stmtdebits = sqlsrv_query($conn, $sql, [$IDNo]);

    if ($stmtdebits === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $deaddebits = [];
    while ($rowdebits = sqlsrv_fetch_array($stmtdebits, SQLSRV_FETCH_ASSOC)) {
        $deaddebits[] = $rowdebits;

    }

    

    $sql = "SELECT * FROM CancelledReceipt WHERE IDNo = ? AND ReceiptStatus BETWEEN 0 AND 1 ORDER BY DateEntry DESC";
    $stmtcancelreceipt = sqlsrv_query($conn, $sql, [$IDNo]);



    if ($stmtcancelreceipt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $cancelreceipts = [];
    while ($rowcancelreceipts = sqlsrv_fetch_array($stmtcancelreceipt, SQLSRV_FETCH_ASSOC)) {
        $cancelreceipts[] = $rowcancelreceipts;
    }


    $sql = "SELECT * FROM PrintReceipt WHERE IDNo = ? AND Status = 0 ORDER BY ID DESC";
    $stmtpendingreceipt = sqlsrv_query($conn, $sql, [$IDNo]);

    if ($stmtpendingreceipt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $pendingreceipts = [];
    while ($rowpendingreceipts = sqlsrv_fetch_array($stmtpendingreceipt, SQLSRV_FETCH_ASSOC)) {
        $pendingreceipts[] = $rowpendingreceipts;
    }
    
   if (!empty($pendingreceipts) && is_array($pendingreceipts)) {
foreach ($pendingreceipts as $pendingreceipt) {

    $dateentry = '';
        $bankdate  = '';

      if (!empty($pendingreceipt['DateEntry']) && $pendingreceipt['DateEntry'] instanceof DateTime) {
        $dateentry = $pendingreceipt['DateEntry']->format('d-m-Y');
    }
       if (!empty($pendingreceipt['TransactionDate']) && $pendingreceipt['TransactionDate'] instanceof DateTime) {
    $bankdate = $pendingreceipt['TransactionDate']->format('d-m-Y');
}


?>
            <tr class="table-danger">
                <td><?= $dateentry;?></td>
                <td><?= $bankdate;?></td>
                <td><?= $pendingreceipt['DebitHead'];?></td>
                <td><?= $pendingreceipt['ID'];?></td>
                <td><?= $pendingreceipt['DebitHead'];?></td>
                <td>Credit</td>

                <td>
                    <?= $pendingreceipt['SemesterID']?>
                </td>
                <td></td>
                <td><?= $pendingreceipt['Credit']?></td>

<td>--</td>

                <td><?= $pendingreceipt['Particulars'];?></td>
                <td>

<p class="text-warning"><b>Pending to Generate</b></p>


                </td>
            </tr>
            <?php
}
}


  if (!empty($cancelreceipts) && is_array($cancelreceipts)) {


foreach ($cancelreceipts as $cancelreceipt) {

     $dateentry = '';
        $bankdate  = '';

      if (!empty($cancelreceipt['DateEntry']) && $cancelreceipt['DateEntry'] instanceof DateTime) {
        $dateentry = $cancelreceipt['DateEntry']->format('d-m-Y');
    }
       if (!empty($cancelreceipt['TransactionDate']) && $cancelreceipt['TransactionDate'] instanceof DateTime) {
    $bankdate = $cancelreceipt['TransactionDate']->format('d-m-Y');
}

$receiptstatus=$cancelreceipt['ReceiptStatus'];
?>
             <tr class="table-danger">
                <td><?=$dateentry;?></td>
                <td><?=$bankdate?></td>
                <td><?= $cancelreceipt['LedgerName'];?></td>
                <td><?= $cancelreceipt['ReceiptNo'];?></td>
                <td style="max-width:200px"><?= $cancelreceipt['Particulars'];?></td>
                <td>Credit</td>

                <td>
                    <?= $cancelreceipt['SemesterID']?>
                </td>
                <td>-</td>
                <td><?= $cancelreceipt['Credit']?></td>



                <td> Receipt Cancel :<?= $cancelreceipt['Comments']?></td>
                <td>

                    <?php if($receiptstatus==0)
                    {
                        echo "<p class='text-warning'><b>Pending to verify</b></p>";

                    }else if($receiptstatus==1)
                    {
                        echo "<p class='text-warning'><b>Pending to Approve</b></p>";

                    }
                    else
                    {
                         echo "<p class='text-warning'><b>No Status</b></p>";
                    }

                    ?>

                </td>
            </tr>
            <?php
}
}


  if (!empty($deaddebits) && is_array($deaddebits)) {
foreach ($deaddebits as $deaddebit) {

             $dateentry = '';
        $bankdate  = '';

      if (!empty($deaddebit['DateEntry']) && $deaddebit['DateEntry'] instanceof DateTime) {
        $dateentry = $deaddebit['DateEntry']->format('d-m-Y');
    }
       if (!empty($deaddebit['TransactionDate']) && $deaddebit['TransactionDate'] instanceof DateTime) {
    $bankdate = $deaddebit['TransactionDate']->format('d-m-Y');
}

$receiptstatus=$deaddebit['DebitStatus'];
?>
             <tr class="table-info">
                <td><?=$dateentry;?></td>
                <td><?=$bankdate?></td>
                <td><?= $deaddebit['Particulars'];?></td>
                <td>-</td>
                <td style="max-width:200px"><?= $deaddebit['Particulars'];?></td>
                <td>Debit</td>

                <td>
                    <?= $deaddebit['SemesterID']?>
                </td>
               
                <td><?= $deaddebit['Debit']?></td>
                 <td>-</td>
                   <td>-</td>



                <td> Debit Delete : <?= $deaddebit['Comments']?></td>
                <td>

                    <?php if($receiptstatus==0)
                    {
                        echo "<p class='text-warning'><b>Pending to verify</b></p>";

                    }else if($receiptstatus==1)
                    {
                        echo "<p class='text-warning'><b>Pending to Approve</b></p>";

                    }
                    else
                    {
                         echo "<p class='text-warning'><b>No Status</b></p>";
                    }

                    ?>

                </td>
            </tr>
            <?php
}
}






if (!empty($concessions) && is_array($concessions)) {
foreach ($concessions as $concession) {

        $dateentry = '';
        $bankdate  = '';

      if (!empty($concession['DateEntry']) && $concession['DateEntry'] instanceof DateTime) {
        $dateentry = $concession['DateEntry']->format('d-m-Y');
    }
       if (!empty($concession['TransactionDate']) && $concession['TransactionDate'] instanceof DateTime) {
    $bankdate = $concession['TransactionDate']->format('d-m-Y');
}
$receiptstatus=$concession['ConcessionStatus'];


?>
         

            <tr class="table-success">
               <td><?=$dateentry;?></td>

               <!-- // (Session, CollegeName, DateEntry, IDNo, UniRollNo, StudentName, FatherName, MotherName, Course, Batch, ClassRollNo, Semester, SemesterID, FeeCategory, Sex, OnAccountOf, Particulars, Debit, LedgerName, UserID, Remarks, ConcessionStatus, CreatedBy, CreatedDate) -->




                <td><?=$bankdate?></td>
                <td><?= $concession['LedgerName'];?></td>
                <td>--</td>
                <td style="max-width:200px"><?= $concession['Particulars'];?></td>
                <td>Concession</td>

                <td>
                    <?= $concession['Semester']?>-<?= $concession['SemesterID']?>
                </td>
                <td><?= $concession['Debit']?></td>
                <td></td>

<td>--</td>

                <td><?= $concession['Remarks']?></td>
                 <td>

                    <?php if($receiptstatus==0)
                    {
                        echo "<p class='text-warning'><b>Pending to verify</b></p>";

                    }else if($receiptstatus==1)
                    {
                        echo "<p class='text-warning'><b>Pending to Approve</b></p>";

                    }
                    else
                    {
                         echo "<p class='text-warning'><b>No Status</b></p>";
                    }

                    ?>

                </td>
            </tr>
            <?php
}

}