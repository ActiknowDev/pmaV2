<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="shortcut icon" type="image/png" href="https://actiknow.com/wp-content/uploads/2018/05/favicon.png" /> -->
   <!-- Favicon Start -->
   <link rel="apple-touch-icon" sizes="180x180" href="<?= WEBURL ?>image/fevicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= WEBURL ?>image/fevicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= WEBURL ?>image/fevicon/favicon-16x16.png">
    <link rel="manifest" href="image/fevicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Favicon End -->

    <!-- GOOGLE FONTS -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- FONT AWESOME 5 -->
    <?php echo $this->Html->css(array('all.css', 'bootstrap.min.css', 'dataTables.bootstrap4.min.css', 'buttons.bootstrap4.min.css', 'jquery-ui.css', 'jquery.multiselect.css', 'main.css')); ?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <title>Project Management App</title>
    <?= $this->Html->css('style') ?>
</head>

<body data-theme="">
    <!--top nav bar start-->
    <?php echo $this->element('appHeader'); ?>
    <!--top nav bar end-->
    <!--side nav bar start-->
    <?php echo $this->element('appSidebar'); ?>
    <!--side nav bar end-->
    <!--main start-->
    <div class="app-content">

        <?php echo $this->fetch('content'); ?>
    </div>
    <!--main end-->
    <script>
        var baseUrl = '<?= $this->Url->build('/', ['fullBase' => true]); ?>';
        var TOKEN = '<?= $this->request->getAttribute("csrfToken"); ?>'
    </script>
    <!-- <?php echo $this->Html->script(array('jquery-3.4.1.js', 'popper.min.js', 'bootstrap.min.js', 'jquery-ui.js', 'jquery.dataTables.min.js', 'dataTables.bootstrap4.min.js', 'jquery.multiselect.js', 'custom.js')); ?> -->

    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>


    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->


    <!-- loadingoverlay -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/src/loadingoverlay.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/extras/loadingoverlay_progress/loadingoverlay_progress.min.js">
    </script>

<!-- <script src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        lengthMenu: [[100, "All", 50, 25], [100, "All", 50, 25]],
        dom: 'Bfrtip',
        buttons: [
            // {
            //     extend: 'copy',
            //     exportOptions: {
            //         rows: function (idx, data, node) {
            //             // Check if the row is visible (block display)
            //             return $(node).css('display') !== 'none';
            //         }
            //     }
            // },
            {
                extend: 'csv',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    rows: function (idx, data, node) {
                        // Check if the row is visible (block display)
                        return $(node).css('display') !== 'none';
                    }
                }
            }
        ]
    });
});
</script>

</body>

</html>