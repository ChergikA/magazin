<?php
require("header.inc.php");
include("milib.inc");

// **********************************baba-jaga@i.ua*******************************
// Создание приемки товара
// список необработанных приходников
// используется под new_doc_menu_2


?>


<!--
вывод прайс-листа
-->

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

        <script type="text/javascript">
            //**********************baba-jaga@i.ua**********************
            function set_doc(iddoc, namedoc){
                //$_GET['viddoc']$_GET['id_firm']
                top.top_menu.location  = "newdoc_menu_2.php?kod_tov=" + iddoc + "&namedoc=" +namedoc + "&viddoc=Приемка"  ;

            }
        </script>

    </head>
    <body>

        <table cellspacing='0' class="Design5" >
                <col width="120px">
                <col width="120px">
                <col width="120px">
                <col width="200px">
                <col >

            <tbody>

                <?php
                    global $db;

                    // поступления которые еще не приняты
                $txt_sql =  "SELECT `DocHd`.`id`, `DocHd`.`nomDoc`, `DocHd`.`DataDoc`, `DocHd`.`Pometka`\n"
                             . "FROM `StatusDoc`\n"
                             . " LEFT JOIN `DocHd` ON `StatusDoc`.`idStatus` = `DocHd`.`statusDoc` \n"
                             . " LEFT JOIN `VidDoc` ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` \n"
                        . "WHERE ((`VidDoc`.`Kod` = 'Поступление') AND (`StatusDoc`.`nameStatus` = 'на прием'))\n"
                        . "ORDER BY `DocHd`.`DataDoc` DESC , `DocHd`.`nomDoc` ASC";



               $sql     = mysql_query($txt_sql, $db);
                while ($srt_arr = mysql_fetch_assoc($sql)) {
                        $id_doc = '"'. $srt_arr['id'] . '"';
                        $strdoc = 'Приходный документ № ' . $srt_arr['nomDoc'] . ' от:' . $srt_arr['DataDoc']  ;

                        $a = "<a href='javascript:set_doc(". $srt_arr['id'] ."," . '"'.   $strdoc . '"' .  ")'>" ;

                        echo "<tr>";
                        echo " <td class='Odd'>&nbsp;</td>";
                        echo " <td > ". datesql_to_str( $srt_arr['DataDoc']) ."</td>";
                        echo "<th class='Odd'> " . $a  . $srt_arr['nomDoc'] . " </a> </th>";
                        echo " <th> "            . $srt_arr['Pometka'] ."</th>";
                        echo " <th> &nbsp; </th>";
                        echo "</tr>";

                    }
                ?>
            </tbody>
        </table>
    </body>
</html>
