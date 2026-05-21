<style>
    #example {
        height: 45vh;
    }
</style>
<section class="page page-dashboard">
    <!-- PAGE-TITLE -->
    <div class="page-title skin-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="heading ft-secondary">
                        <span class="icon"><i class="fa fa-user-tie"></i></span>Comp-Off Management
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="v-tab">

                        <li>
                            <?= $this->Html->link('My Leaves', ['controller' => 'Leaves', 'action' => 'index']); ?>
                        </li>

                        <li>
                            <?= $this->Html->link('Requested Leaves', [
                'controller' => 'Leaves',
                "action" => 'requestleave'
              ]); ?>
                        </li>

                        <?php
            if (($userSession['role'] != 3) || ($userSession['role'] == 3 &&
              array_intersect($userSession['role_name'], array(12)))) {
            ?>
                        <li>
                            <?= $this->Html->link('All Leaves', [
                  'controller' => 'Leaves',
                  'action' => 'allLeaves'
                ]) ?>
                        </li>
                        <?php } ?>

                        <li>
                            <?= $this->Html->link('Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'addCompOff'
              ]); ?>
                        </li>

                        <li class="active">
                            <?= $this->Html->link('Requested Comp-Off', [
                'controller' => 'Leaves',
                'action' => 'requestCompOff'
              ]); ?>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- PAGE-CONTENT -->
    <div class="page-content">
        <div class="container">
            <?= $this->Flash->render() ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <div class="header">
                            <h4 class="title">Comp-Off Management</h4>

                            <div class="row">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" onkeyup="filterCompOffData(this)"
                                        placeholder="Filter Conp-Off...">
                                </div>
                            </div>
                        </div>
                        <div class="content ">

                        <table id="example" style="width:100%;" class="table table-default table-striped block table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <!-- <th>Applied On</th> -->
                                        <th>Requested Date</th>
                                        <!-- <th>Approved By</th> -->
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="filterData">
                                    <?php
                  foreach ($comp_data as $value) :
                  ?>
                                    <tr>
                                        <td><?= $this->CompOffName->compOffName($value['employee_id']) ?></td>
                                        <td><?= $value['description'] ?></td>
                                        <!-- <td><?= $value['applied_on'] ?></td> -->
                                        <td><?= date('Y-m-d', strtotime($value['request_date'])) ?></td>
                                        <!-- <td><?= $value['approved_by'] ?></td> -->
                                        <td>
                                            <?php
                        if ($value['status'] == 'Pending') :
                        ?>
                                            <span class="badge badge-info">
                                                <?= $value['status'] ?>
                                            </span>
                                            <?php
                        endif;
                        ?>
                                            <?php
                        if ($value['status'] == 'Cancelled') :
                        ?>
                                            <span class="badge badge-danger">
                                                <?= $value['status'] ?>
                                            </span>
                                            <?php
                        endif;
                        ?>
                                            <?php
                        if ($value['status'] == 'Approved') :
                        ?>
                                            <span class="badge badge-success">
                                                <?= $value['status'] ?>
                                            </span>
                                            <?php
                        endif;
                        ?>
                                        </td>
                                        <?php
                      if (!in_array($value['status'], ['Approved', 'Cancelled'])) :
                      ?>
                                        <td>
                                            <a class="v-btn v-btn-success btn-sm" title="Approved"
                                                onclick="approveCompOff(<?= $value['id'] ?>,'Approved')"><i
                                                    class="fa fa-check"></i></a>
                                            <a class="v-btn v-btn-danger btn-sm" title="Cancelled"
                                                onclick="approveCompOff(<?= $value['id'] ?>,'Cancelled')"><i
                                                    class="fa fa-times"></i></a>
                                        </td>
                                        <?php
                      else :
                        echo "<td>--</td>";
                      endif;
                      ?>
                                    </tr>
                                    <?php
                  endforeach;
                  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src=" https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
function approveCompOff(id, status) {
    let csrf = '<?= $this->request->getAttribute('csrfToken') ?>';
    // console.log(id);
    // console.log(status);
    // console.log(csrf);

    let url = '<?= $this->Url->build([
                  'controller' => 'Leaves',
                  'action' => 'approveCompOff'
                ]) ?>'

    $.post(url, {
            'id': id,
            'status': status,
            '_csrfToken': csrf,
        },
        function(data, status) {
            if (data == 'Yes') {
                location.reload();
            }
        });

}

function filterCompOffData(data) {
    let value = data.value;
    let check = ['Approved', 'Cancelled'];

    $.ajax({
        url: "<?= $this->Url->build("/Leaves/filterCompOff") ?>",
        method: "GET",
        data: {
            value
        },
        success: (res) => {
            let data = JSON.parse(res);
            // console.log(data);
            $("#filterData").html("");
            let row = "";

            data.forEach(element => {
                row += `<tr>
                <td>
                  ${element.emp_name}
                </td>
                <td>
                  ${element.descr}
                </td>
                <td>  
                  ${element.req_date}
                </td>
                <td>
                  ${element.comp_status === "Pending" 
                    ? `<span class="badge badge-info">
                          ${element.comp_status}
                        </span>` 
                    : element.comp_status === "Approved" 
                    ? `<span class="badge badge-success">
                          ${element.comp_status}
                        </span>`
                    : `<span class="badge badge-danger">
                          ${element.comp_status}
                        </span>`}
                </td>
                <td>
                  ${!check.includes(element.comp_status) 
                    ?
                    `<a class="v-btn v-btn-success btn-sm" title="Approved"
                        onclick="approveCompOff(${element.id},'Approved')"><i
                        class="fa fa-check"></i></a>
                      <a class="v-btn v-btn-danger btn-sm" title="Cancelled"
                        onclick="approveCompOff(${element.id},'Cancelled')"><i
                        class="fa fa-times"></i></a>`
                    : "--"}
                </td>
              </tr>`;
            });

            $("#filterData").html(row);

        }
    })

}
</script>