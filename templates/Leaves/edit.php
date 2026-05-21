<?= $this->Form->create($leave) ?>


<?= $this->Form->control('id', ['type' => 'hidden']); ?>


<div class="modal-header">
    <h5 class="modal-title">Edit Leave</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="content">
        <div class="form-group row">
            <div class="col-md-12">
                <label for="">Subject</label>
                <?= $this->Form->control('subject', ['id' => 'edit_leave_subject', 'required' => true, 'label' => false, 'class' => 'form-control', 'type' => 'text']); ?>

            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label for="">Leave Type</label>
                <?= $this->Form->control('leave_type', ['id' => 'edit_leave_type', 'required' => true, 'label' => false, 'class' => 'form-control', 'options' => ['Casual Leave' => 'Casual Leave', 'Sick Leave' => 'Sick Leave', 'Paid Leave' => 'Paid Leave', 'Half Day' => 'Half Day', 'WFH' => 'Work From Home']]); ?>
                <!-- 
                            <select name="" class="form-control" id="">
                                <option value="">Casual Leave (5)</option>
                                <option value="">Sick Leave(4)</option>
                                <option value="">Paid Leave(2)</option>
                            </select> -->
            </div>
            <div class="col-md-4">
                <label for="">From Date</label>
                <div class="adon-group">
                    <!-- <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span> -->
                    <?= $this->Form->control('from_date', [
                        'id' => 'edit_from_date',
                        'required' => true,
                        'label' => false,
                        'type' => 'date',
                        'required' => true,
                        'class' => 'form-control datepicker2'
                    ]); ?>
                </div>
            </div>
            <div class="col-md-4">
                <label for="">To Date</label>
                <div class="adon-group">
                    <!-- <span class="icon ft-primary"><i class="fa fa-calendar-alt"></i></span> -->
                    <?= $this->Form->control('to_date', [
                        'id' => 'edit_to_date',
                        'required' => true,
                        'label' => false,
                        'type' => 'date',
                        'required' => true,
                        'class' => 'form-control datepicker2'
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label for="">Message</label>
                <?= $this->Form->control('message', ['id' => 'edit_message', 'required' => false, 'label' => false, 'required' => true, 'class' => 'form-control']); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="v-btn v-btn-base" data-dismiss="modal">Close</button>
    <button class="v-btn v-btn-primary" type="submit">Edit Leave</a>
</div>

<?= $this->Form->end() ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
    // $(document).ready(function() {
    //     $(".datepicker2").datepicker({
    //         minDate: new Date()
    //     });
    // });
</script>