<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
<!-- FONT AWESOME 5 -->

<!-- Favicon Start -->
<link rel="apple-touch-icon" sizes="180x180" href="image/fevicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="image/fevicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/fevicon/favicon-16x16.png">
    <link rel="manifest" href="image/fevicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Favicon End -->
    
<?php echo $this->Html->css(array('all.css', 'bootstrap.min.css', 'dataTables.bootstrap4.min.css', 'buttons.bootstrap4.min.css','jquery-ui.css','jquery.multiselect.css','main.css')); ?>

    <title>Project Management App</title>
    <style type="text/css">
    
    .error{
        
        color: red;
    }
</style>
</head>
<body data-theme="">
   <!--main start-->
       <?php echo $this->fetch('content'); ?>
        <!--main end-->
   <?php echo $this->Html->script(array('jquery-3.4.1.js', 'popper.min.js', 'bootstrap.min.js', 'jquery-ui.js','jquery.dataTables.min.js','dataTables.bootstrap4.min.js','jquery.multiselect.js','custom.js')); ?> 
</body>
</html>