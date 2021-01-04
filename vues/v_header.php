<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Projet des PDG</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"/>
    <link rel="stylesheet/less" type="text/css" href="<?php echo $base; ?>util/style.less"/>
</head>

<body>
    
    <?php include("vues/v_navbar.php"); ?>
    <div id="content">

    <?php if(isset($_GET["message"])){ ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $_GET["message"]; ?>
        </div>
    <?php } ?>