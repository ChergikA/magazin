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
            function open_log_price(date){
                window.open( "log_price.php?date=" + date );
            }
        </script>

    </head>
    <body>

        <table cellspacing='0' class="Design5" >
                <col width="180px">
                <col width="180px">
                <col width="280px">
                <col width="90px">

            <tbody>

                <?php
                
                $time_now=date("Y-m-d",strtotime( date("Y-m-d"))) ;
                
                if (isset($_SESSION['obmen_time'])){
                    $time_now = date("Y-m-d H:i:s", $_SESSION['obmen_time']);
                }
                
                if(isset($_GET['show'])){
                    $time_now=date("Y-m-d",strtotime( date("Y-m-d") )-864000*5) ; // минус 10 дней
                }
                
               // echo date("Y-m-d", $time_now);
                 
                $txt_sql =  " SELECT * FROM `log_obmen` 
                    WHERE `datetime` > '$time_now'
                    ORDER BY `datetime` DESC ";
                

                $sql     = mysql_query($txt_sql, $db);
                while ($srt_arr = mysql_fetch_assoc($sql)) {

                    $sobitie = $srt_arr['sobitie'];
                    $info      =  $srt_arr['info'];
                    $date_info = $srt_arr['datetime'];

                    $stile = '';
                    if(strpos($sobitie, 'шибка') > 0 ) $stile = "style='color: #FF0000'";
                    if(strpos($sobitie, 'ачало') > 0 ) $stile = "style='color: #00f'";
                    if(strpos($sobitie, 'зменение цен') > 0 ){
                          //  substr ( string $string , int $start [, int $length ] )
                            $date_i = '"' . substr ( $date_info,0,10 ) . '"';
                            $stile = "style='color: #FF0000'";
                            $info = " <a style='color: #0c0'  href='javascript:open_log_price($date_i)'> Показать список товара с измененными ценами </a>";
   
                    }
                    
                    echo "<tr " . $stile . " >";
                    echo " <td class='Odd'>     $date_info </td>";
                    echo " <th>                 $sobitie   </th>";
                    echo " <th class='Odd'>     $info      </th>";
                    echo " <td> "             . $srt_arr['user']     ."</td>";
                    echo " <th> &nbsp; </th>";

                    echo "</tr>";
                }
                ?>


            </tbody>
        </table>
    </body>
</html>