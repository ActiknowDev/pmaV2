<h1>Edit User</h1>
<?php
    echo $this->Form->create($user);
    echo $this->Form->control('id', ['type' => 'hidden']);
    echo $this->Form->control('email');
    echo $this->Form->control('password');
    echo $this->Form->button(__('Update User'));
    echo $this->Form->end();
?>