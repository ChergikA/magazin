<?php
require("header.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title>Верхняя страница</title>

    </head>
    <body style="background: rgb(255, 255, 255) url(images/img02.gif) repeat-x scroll left top;">
        <div id="name" style="position:absolute; z-index:1; top:0px; left:0px; width:100%; text-align:left;">

    <img  style="width: 1600px; height: 75px;" alt="" src="images/logo.jpg" align="left">
</div>
    <div style="position:absolute; z-index:2; top:42px; left:200px; width:100%; text-align:center;">

        <?php
        if (isset($_SESSION['txt_user'])) {
            echo ( date('d-m-Y') . " продавец: " . $_SESSION['txt_user'] );
        }
        ?>
        &nbsp;  &nbsp; &nbsp;    <br>
    </div>

</body>
</html>