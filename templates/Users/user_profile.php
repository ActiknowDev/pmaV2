<div class="row mt-5">
    <div class="col-md-6 offset-3">
        <div class="card border-info mb-3">
            <div class="card-header border-info h3 p-3 text-secondary">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        if (isset($_GET['reports-profile']))
                            echo "Manager Profile";
                        else if (isset($_GET['reportees-profile']))
                            echo "Reportee Profile";
                        else
                            echo "My Profile";
                        ?>
                    </div>
                    <div class="col-md-6 text-info text-right h6 mt-2">
                        <?php
                        if (isset($_GET['reports-profile']) || isset($_GET['reportees-profile'])) {
                        ?>
                        <a href="<?= $this->Url->build('/profile') ?>">My Profile</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- text-info -->
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item mb-2">
                        <span class="font-weight-bold text-secondary">Name</span> : <?= $userData->name ?>
                    </li>
                    <li class="list-group-item mb-2">
                        <span class="font-weight-bold text-secondary">Designation</span> : <?= $userData->designation ?>
                    </li>
                    <li class="list-group-item mb-2">
                        <span class="font-weight-bold text-secondary">Reports To</span> :
                        <a href="<?= $this->Url->build("/profile?reports-profile=") . base64_encode($manager->id) ?>"
                            class='text-info'><?= $manager->name ?></a>
                    </li>
                    <li class="list-group-item mb-2">
                        <span class="font-weight-bold text-secondary">Work Anniversary</span> :
                        <?= date('M d', strtotime($userData->doj)) ?>
                    </li>
                    <li class="list-group-item mb-2">
                        <span class="font-weight-bold text-secondary">Reportees</span> :
                        <?php
                        $i = 1;
                        foreach ($subordinates as $val) {
                            $url = $this->Url->build('/profile?reportees-profile=') . base64_encode($val->id);
                            if (count((array)$subordinates) === $i) {
                                echo  "<a href='$url' class='text-info'>" . $val->name . "</a>";
                            } else {
                                echo  "<a href='$url' class='text-info'>" . $val->name . "</a>" . ', ';
                                $i++;
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>