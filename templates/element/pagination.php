<div class="row">
    <div class="col-md-6">
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
    <div class="col-md-6">
        <ul class="pagination">
            <?php echo $this->Paginator->prev(
                        '<< ' . __('Previous'),
                        array(),
                        null,
                        array('class' => 'prev btn')
                    );
            ?>
            <?= $this->Paginator->numbers(array('separator' => null,'modulus' => '4', 'class' => 'page-item')) ?>

            <?php echo $this->Paginator->next(
                        ('Next').' >>',
                        array(),
                        null,
                        array('class' => 'next btn')
                    );
            ?>
        </ul>
    </div>
</div>
