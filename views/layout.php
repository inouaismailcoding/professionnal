<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= SCRIPTS.'css'.DIRECTORY_SEPARATOR.'app.css' ;?>">
    <link rel="stylesheet" href="<?= SCRIPTS.'fontawesome'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'all.min.css' ;?>">
    
    
    
    
    <title>Document</title>
</head>
<body>

<!-- NAVRBAR -->
<div class="container">
    <?php include ASSETS.DIRECTORY_SEPARATOR."navbar.php"; ?>
    <div class="container">
    <?= isset($contents) ? $contents: "<h1> Welcome To My Site </h1>" ; ?>
    </div>
    <div>
        <span class="fa fa-plus text-info"></span>
        <span class="fa fa-user text-info"></span>
        <span class="fa fa-umbrella text-danger"></span>
        <span class="fa fa-ambulance text-info"></span>
        <span class="fa fa-app-store-ios text-dark"></span>
        <span class="fa fa-accessible-icon"></span>
        <span class="fa fa-pause"></span>
        <span class="fa fa-ambulance text-info"></span>
        <span class="fa fa-android text-info"></span>
    </div>
    </div>
    <script src="<?= SCRIPTS.'js'.DIRECTORY_SEPARATOR.'jquery.js' ;?>"></script>

    <script src="<?= SCRIPTS.'js'.DIRECTORY_SEPARATOR.'datatables.min.js' ;?>"></script>
    <script src="<?= SCRIPTS.'js/script.js' ;?>"></script>
    <link rel="stylesheet" href="<?= SCRIPTS.'css'.DIRECTORY_SEPARATOR.'dataTables.min.css' ;?>">

    <!-- ON CREE UN SCRIPT JS -->
    <script>
        let table=$('#table-Admin-Post')
       if($('#table-Admin-Post'))
       {
        $(function(){
            table.dataTable({
                dom:'fltrip',
                "order":[[0,"desc"]],
                "paging": true,
                "scrolly":200,
                "pageLength":3,
                "lengthMenu":[5,10,15,20,25,30],
                "pagingType":"simple_numbers"
            });
        });
       }
    </script>
</body>
</html>