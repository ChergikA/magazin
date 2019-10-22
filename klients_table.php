<?php
require("header.inc.php");
include("milib.inc");

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
            function set_tovar(kod_tov){



               var str = new String( top.top_menu.location)   ;

              //   alert('=' + str.indexOf("klients_menu.php")  );

                if( str.indexOf("klients_menu.php") != -1 ) {
                    top.top_menu.location  = "klients_menu.php?kod_tov=" + kod_tov ;
                }else{
                    // вызвана с меню нового документа
                    var   vid_doc = top.top_menu.vid_doc;
                    if( vid_doc == 'Чек' || vid_doc == 'Счет' ){
                        top.top_menu.location  = "newdoc_menu.php?kod_tov=" + kod_tov +"&viddoc="+ top.top_menu.vid_doc;
                    }else{ // Возвратклиента
                        // продолжаем сбор данных для возврата
                        top.top_menu.location  = "newdoc_menu_vzv_klient.php?kod_tov=" + kod_tov +"&viddoc="+ top.top_menu.vid_doc;
                    }

                    //alert('='+f);



                    // продолжаем сбор данных для возврата


                }
            }
        </script>

    </head>
    <body  >

        <table cellspacing='0' class="Design5" >
                <col width="120px">
                <col width="310px">
                <col width="65px">
                <col width="90px">
                <col width="100px">

            <tbody>

                <?php
                    global $db;


                    // был ли ввежен сейчас или ранее штрихкод
                    if( isset( $_SESSION['diskont_klienta']) ) {
                            $h_kod = $_SESSION['diskont_klienta'] ;
                    }else $h_kod = 'дисконт' ;


                    // был ли ввежен сейчас или ранее фрагмент клиента
                    if( isset( $_SESSION['list_klient_searh']) ) {
                            $frgm = trim( $_SESSION['list_klient_searh']) ;
                            if($frgm=='')$frgm='фрагмент';
                    }else $frgm = 'фрагмент' ;

                    $txt_sql = "SELECT * FROM `Klient` ";

                    $where = ''; $limit = '';

                    if ($h_kod != 'дисконт') {
                        //$where = " WHERE `diskont` = " . $h_kod;
                       $where = " WHERE `diskont` LIKE '%".$h_kod."%'";
                    }
                    elseif ($frgm != 'фрагмент') {
                        $where = " WHERE `name_klient` LIKE '%$frgm%' ";
                    }
                    else {
                        $limit   = ' LIMIT 0 , 300 ';
                    }



                     $txt_sql = $txt_sql . $where . " ORDER BY `name_klient` ASC" . $limit;
                    //echo  ( 'txtsql=' . $txt_sql  );
                    $sql     = mysql_query($txt_sql, $db);
                    while ($srt_arr = mysql_fetch_assoc($sql)) {
                        $id_kl = '"'. $srt_arr['diskont'] . '"';
                        $nm_kl = $srt_arr['name_klient'];

                        $st = '';
                        //if($srt_arr['heppy_diskont'] > 0 ) $st = "style='color: #FF0000'";

                        echo "<tr ".$st." >";
                        echo " <td class='Odd'> ". $srt_arr['diskont'] ."</td>";
                        echo "<th> <a ".$st." href='javascript:set_tovar( $id_kl )'> $nm_kl </a> </th>";
                        echo " <td class='Odd'> ". $srt_arr['Skidka'] ."</td>";
                        echo " <td> "            . $srt_arr['data_zakupki'] ."</td>";
                        echo " <th colspan='2'> "            . $srt_arr['pometka'] ."</th>";


                    echo "
                        <td >
                                &nbsp;
                        </td>
                        <td>
                            &nbsp;
                        </td>";

                    echo "</tr>";




                    }
                ?>
            </tbody>
        </table>
    </body>
</html>
