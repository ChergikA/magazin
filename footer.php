<?php
require("header.inc.php");

function txt_info(){

    global $db;
    $ver = '';
    $dt_time='';


   $txt_sql =  "SELECT `name` FROM `const` WHERE `kod` LIKE 'nameFilePriem' ";
   $sql     = mysql_query($txt_sql, $db);
   $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
   $dt_time .= $s_arr['name'];

   $dt_time = str_replace('.xml', '' , $dt_time);
   $pos = strrpos($dt_time, '_') ;
   $dt_time = substr($dt_time, $pos+1);

   //20130102211543
   $dt_time = substr($dt_time, 6,2) . '-' .
              substr($dt_time, 4,2) . '-' .
              substr($dt_time, 0,4) . ' ' .
              substr($dt_time, 8,2) . ':' .
              substr($dt_time, 10,2) . ':' .
              substr($dt_time, 12,2)    ;

   $txt_sql =  "SELECT `name` FROM `const` WHERE `kod` LIKE 'Version' ";
   $sql     = mysql_query($txt_sql, $db);
   $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
   $ver .= $s_arr['name'];

   $ver = str_replace('ver', '', $ver);
   $ver = str_replace('.zip', '', $ver);


   $info = 'Версия: ' . $ver . '  Обновление данных: ' . $dt_time;

   return $info ;


}

?>

<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title>Верхняя страница</title>

        <script type="text/javascript">

            var name_user =         <?php
                                        if (isset($_SESSION['txt_user'])) {
                                            echo '"' . $_SESSION['txt_user'] . '"' ;
                                        }else                                            echo '""';
                                        ?> ;
        </script>
    </head>
    <body >


        <div style="position:relative; font-size: 10px ">
            <div id="name" style="position:absolute; z-index:1; top:0px; left:0px; width:100%; text-align:left;">
                <?php echo txt_info() ; ?>
            </div>
            <div id="data" style="position:absolute; z-index:2; top:0px; left:0px; width:100%; text-align:right;">
                Copyright  © 2010 -
                <script language="javascript" type="text/javascript"><!--
                    var d = new Date();
                    document.write(d.getFullYear());
                    //--></script>
                <strong>Александр Чергик</strong> e-mail: odinc@mail.ua
            </div>
        </div>


</body>
</html>