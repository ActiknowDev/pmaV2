<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<!-- <div class="message success" onclick="this.classList.add('hidden')"><?= $message ?></div> -->
 <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
    <i class="fas fa-check-circle mr-2"></i>
    <?= $message ?>

    <button type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
