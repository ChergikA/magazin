<?php
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
?>
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
        ������� ������ �������� <br>
        <?php
        if (isset($_SESSION['txt_user'])) {
            echo "��������: " . $_SESSION['txt_user'];
        }
        else {
            echo "��� ������ <br>";
        }
        echo '� �� ���� ��������';
        ?>
    </body>
</html>
