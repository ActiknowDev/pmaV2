<table class="table table-default" id="table_data">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Asset Name</th>
            <th>Serial Number</th>
            <th>Expenses Type</th>
            <th>Expenses Date</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // dd($assetExpenses);
        $x = 1;
        $totalExpense = 0;
        foreach ($assetExpenses as $expense) :
            $totalExpense += $expense->expenses_amount;
        ?>
        <tr>
            <td><?= $x++; ?></td>
            <td><?= $productName ?></td>
            <td><?= $expense->serial_number ?></td>
            <td><?= $expense->expense_type ?></td>
            <td><?= date("Y-m-d", strtotime($expense->expense_date)) ?></td>
            <td>₹<?= $expense->expenses_amount ?></td>
            <td>
                <a href="javascript:void(0)" class="icon" onclick="editExpense(<?= $expense->id ?>)">
                    <i class="fa fa-pencil-alt"></i>
                </a>
                <a href="javascript:void(0)" class="icon" onclick="deleteExpense(<?= $expense->id ?>)">
                    <i class="fa fa-trash-alt"></i>
                </a>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </tbody>
</table>

<input type="hidden" value="<?= $totalExpense ?>" id="totalExpense">