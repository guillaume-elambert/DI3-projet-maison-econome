    </div>
    
    <?php include("vues/v_popUp.html"); ?>
</body>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo HOME; ?>script/script.js"></script>
<?php 
if(isset($javascript) && !empty($javascript)){
    foreach($javascript as $script){
        echo "<script type='text/javascript' src='$script'></script>";
    }
}
?>

</html>