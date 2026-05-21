<div class="modal-dialog" role="document">
    <div class="modal-content">

        <?= $this->Form->create(null, [
            'url' => [
                'controller' => 'AssetAssignedEntries',
                'action' => 'editExpense'
            ]
        ]) ?>
        <div class="modal-header">
            <h5 class="modal-title">Edit Expenses</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input type="hidden" name="asset_id" value="<?= $assetExpenses->asset_id ?>">
                <input type="hidden" name="id" value="<?= $assetExpenses->id ?>">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="">Expense Type</label>
                        <div class="adon-group">
                            <input type="text" class="form-control" name="expense_type"
                                value="<?= $assetExpenses->expense_type ?>" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="">Expenses Amount</label>
                        <div class="adon-group">
                            <input type="number" class="form-control" name="expenses_amount"
                                value="<?= $assetExpenses->expenses_amount ?>" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="">Expense Date</label>
                        <div class="adon-group">
                            <input type="text" class="datepicker form-control" name="expense_date"
                                value="<?= date("Y-m-d", strtotime($assetExpenses->expense_date)) ?>" autocomplete="off"
                                required>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
            <button class="v-btn v-btn-primary" type="submit">Edit Expense</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>