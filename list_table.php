<?php
require("header.inc.php");
include("milib.inc");

$k_tov = kod_tov();

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
            var is_selected_tovar='no';
            
                        
            //**********************baba-jaga@i.ua**********************
            function on_load(){

                
                var kod_tov = <?php  echo '"' . $k_tov . '"' ; ?>; 
                
                
                 set_tovar(kod_tov);


            }
            
            //**********************baba-jaga@i.ua**********************
            function set_tovar(kod_tov) {
                // проще получить код и досать имя из базы, чем закрывать и откр спец символы
                //alert('=' +kod_tov);

                // обновляем лист меню
                top.top_menu.location = "list_menu.php?kod_tov=" + kod_tov;
                id_kl=kod_tov;
                
                        if (is_selected_tovar==='no'){
                   
                   document.getElementById('a'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('b'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('c'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('d'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('e'+id_kl).style.backgroundColor="#fcc623";
                   is_selected_tovar=id_kl;
               }else{
                   document.getElementById('a'+is_selected_tovar).style.backgroundColor="#ffffff";
                   document.getElementById('b'+is_selected_tovar).style.backgroundColor="#F1F8FE";
                   document.getElementById('c'+is_selected_tovar).style.backgroundColor="#ffffff";
                   document.getElementById('d'+is_selected_tovar).style.backgroundColor="#F1F8FE";
                   document.getElementById('e'+is_selected_tovar).style.backgroundColor="#ffffff";

                   document.getElementById('a'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('b'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('c'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('d'+id_kl).style.backgroundColor="#fcc623";
                   document.getElementById('e'+id_kl).style.backgroundColor="#fcc623";
                                     
                   is_selected_tovar=id_kl;
                   
               }
                }


        </script>

    </head>
    <body>

        <table cellspacing='0' class="Design5" >
                <col width="120px">
                <col width="170px">
                <col width="280px">
                <col width="60px">
                <col width="60px">
                <col width="55px">




            <tbody>

                <?php
                global $db;

                $txt_sql = "SELECT `Kod1C`,`Kod`,`Tovar`,`Price`,`PriceOpt`,`Ostatok` FROM `Tovar` ";

                $where    = ' `flg_del` = 0 ';  $limit   = ' LIMIT 0 , 300 ';

                if (only_searh() != '') {
                    $where    = $where . ' AND `Ostatok` >0 ';
                    $limit = '';
                }

                $kod_t    = kod_tov_searh();
                if ($kod_t == 'штрихкод')  $kod_t    = '';
                $fragment = frgm_tov_searh();
                if ($fragment == 'фрагмент') $fragment = '';

                //"SELECT * FROM `Tovar` WHERE ((`Kod1C` LIKE '%483%') or (`Kod` LIKE '%48%'))";

                if ($kod_t != '') {
                    //$where = $where . ' `Kod` = ' . $kod_t;
                    $where .=  " AND ((`Kod1C` LIKE '%".$kod_t."%') or (`Kod` LIKE '%".$kod_t."%'))";
                     $limit = '';
                }elseif ($fragment != '') {
                    if ($where !== '') $where = $where . ' AND ';
                    $where = $where . " `Tovar` LIKE '%$fragment%' ";
                     $limit = '';
                }

                if ($where !== '') {
                    $where = ' WHERE ' . $where;
                }
                else {
                    $limit   = ' LIMIT 0 , 300 ';
                }
                $txt_sql = $txt_sql . $where . " ORDER BY `Tovar` ASC" . $limit;
                // echo  ( '<br>txtsql=' . $txt_sql . '<br>'  );
                $sql     = mysql_query($txt_sql, $db);
                while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {

                    $kod_tov = '"' . $srt_arr['Kod'] . '"';
                    $id3=$srt_arr['Kod'];

                    echo "<tr>
                        <td id=".'a'.$id3." style='text-align: left' ><a href='javascript:set_tovar(  $kod_tov  )'> ".$srt_arr['Kod1C']." </td>
                        <td id=".'b'.$id3." class='Odd' style='text-align: left' ><a href='javascript:set_tovar(  $kod_tov  )'>  ".$srt_arr['Kod']."  </td>
                        <th id=".'c'.$id3." > <a href='javascript:set_tovar(  $kod_tov  )'>  ".$srt_arr['Tovar']."  </a> </th>
                        <td id=".'d'.$id3." class='Odd'><a href='javascript:set_tovar(  $kod_tov  )'> &nbsp;   ".$srt_arr['Price']."  </td>
                        <td id=".'e'.$id3." ><a href='javascript:set_tovar(  $kod_tov  )'>&nbsp;  ".$srt_arr['PriceOpt']."   </td>"; // opt
                    $ost = "&nbsp;";
                    if (0 + $srt_arr['Ostatok'] !== 0)  $ost = $srt_arr['Ostatok'];
                    echo "<td class='Odd'> $ost </td>"; # остаток

                    echo "
                        <td >
                                &nbsp;
                        </td>
                        <td>
                            &nbsp;
                        </td>";

                    echo "</tr>";
                }
                // echo "</table>";
                // //<a href='list.php?tov=$srt_arr[0]&func=buy'  title='поступление товара'><img width='7' height='7' border='0' src='images/buy.png'></a>
                //закрытие соединение (рекомендуется)
                ?>
            </tbody>
        </table>
    </body>
</html>
