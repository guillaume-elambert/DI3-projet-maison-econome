<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Projet des PDG</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet/less" type="text/css" href="<?php echo HOME; ?>style/style.less"/>
    <link rel="icon" href="<?php echo HOME ?>assets/logo.png">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/less.js/4.0.0/less.min.js"></script>
</head>

<body>
    
<?php 
    include("vues/v_navbar.php");
    include("vues/v_loading.html");
?>
    <div id="content">

    <?php
    if(isset($_SESSION["success"]) || isset($_SESSION["erreurs"]) || isset($_SESSION["messages"])){
        
        echo "<div id='msgErrSucc'>";

        if(isset($_SESSION["success"])){ 
            foreach ($_SESSION["success"] as $unMsg){     
        ?>
            <div class="alert alert-success" role="alert">
                <?php echo $unMsg; ?>
            </div>
        <?php }
            unset($_SESSION["success"]);
        }
        
        if(isset($_SESSION["erreurs"])){ 
            foreach ($_SESSION["erreurs"] as $unMsg){     
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $unMsg; ?>
            </div>
        <?php }
            unset($_SESSION["erreurs"]);
        }
        
        if(isset($_SESSION["messages"])){ 
            foreach ($_SESSION["messages"] as $unMsg){     
        ?>
            <div class="alert alert-warning" role="alert">
                <?php echo $unMsg; ?>
            </div>
        <?php }
            unset($_SESSION["messages"]);
        }
        echo "</div>";
    }?>