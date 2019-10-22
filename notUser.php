<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
    </head>
    <body>
        <?php
            if(isset($_SESSION['txt_user'])) unset ( $_SESSION['txt_user'] );
            echo 'не верно введены данные о пользователе';
        ?>
    </body>
</html>
