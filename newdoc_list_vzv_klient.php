<?php
require("header.inc.php");
include("milib.inc");


//**********************baba-jaga@i.ua**********************
function list_str(){

global $db;

//$dt2 = date('Y-m-d') ;
//$d = date('d')- 14; // возврат возможен в течении 14-ти дней
//$dt1 =  date('Y-m-') . $d  ;

$add_where = '';

// принят и продан
$add_where=$add_where. " AND `StatusDoc`.`nameStatus` LIKE 'пр%'  ";

if ( isset($_GET['h_kod']) ) {
    $add_where=$add_where. " AND `Klient`.`diskont` LIKE '%".$_GET['h_kod']."%' ";

}



// касса и банк приход только чек и только продан
// касса и банк расход только возврат клиента и только принят
// TO_DAYS(NOW()) - TO_DAYS(date_col) <= 14

//$str_where = "WHERE (`DocHd`.`DataDoc` >= '" . $dt1 . "' AND `DocHd`.`DataDoc` <= '" . $dt2 . "' " . $add_where . " ) ";
$str_where = "WHERE (TO_DAYS(NOW()) - TO_DAYS(`DocHd`.`DataDoc`) <= 14 " . $add_where . " ) ";

$txt_sql = "SELECT `DocHd`.`id`, `DocHd`.`firms_id`,   `StatusDoc`.`images`,`StatusDoc`.`nameStatus`, `DocHd`.`flg_otpravlen`,
      `DocHd`.`DataDoc`, `DocHd`.`nomDoc`, `DocHd`.`oplataBank`, "
    . " `VidDoc`.`name_doc` as VidDoc ,`VidDoc`.`Kod` as KodVidDoc , `VidDoc`.`php_file` , `Klient`.`name_klient`,
        `DocHd`.`SkidkaProcent`, `DocHd`.`Pometka`\n"
    . "FROM `StatusDoc`\n"
    . " LEFT JOIN `DocHd` ON `StatusDoc`.`idStatus` = `DocHd`.`statusDoc` \n"
    . " LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_` \n"
    . " LEFT JOIN `VidDoc` ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` \n"
    . $str_where
    . "ORDER BY `DocHd`.`id` ASC\n"
    . " ";



    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr   = mysql_fetch_array($sql, MYSQL_BOTH)) {

         $stile ='';
         if(  $s_arr['oplataBank'] == 1 ) $stile = " style='color: #00f' ";

         $styleotpralen='';
         if(  $s_arr['flg_otpravlen'] == 1 )  $styleotpralen = "style='color: #0c0'";

         $del = ''; $delend='';
         if(  $s_arr['nameStatus'] == 'Удален' ){$del = '<DEL >'; $delend='</DEL>';}//style="color: #F93D00"

         //$a = "<a " .$styleotpralen. " href='javascript:set_doc(". $s_arr['id'] ."," . '"'.   trim($s_arr['php_file']) . '"' .  ")'>" ;

         //iddoc, idfirm, name_doc, kodviddoc
        // $a = "<a " .$styleotpralen. " href='javascript:set_doc(". $s_arr['id'] ."," . '"'.   trim($s_arr['php_file']) . '"' .  ")'>" ;

         $id_doc = '"'. $s_arr['id'] . '"';
         $strdoc = $s_arr['VidDoc'] . ' №_' . $s_arr['nomDoc'] . ' от:' . $s_arr['DataDoc']  ;
         $a = "<a href='javascript:set_doc(". $s_arr['id'] .","
                 . '"'.   $strdoc . '"'  .","
                 . '"'. $s_arr['KodVidDoc'] . '"'  .","
                 . '"'. $s_arr['firms_id']  . '"' .  ")'>" ;

        echo '<tr>';
        echo '<td ><img src="'. $s_arr['images'] .'" border=0></td> ';
        echo '<td class="Odd">'. datesql_to_str( $s_arr['DataDoc']) .'</td>';
        echo '<th >'. $s_arr['nomDoc'].'</th>';
        echo '<td class="Odd" style="text-align: left" >'. $a . $del .  $s_arr['VidDoc'] . $delend . '</a> </td>';
        echo '<td style="font-size: 0.9em; text-align: left" >'. $s_arr['name_klient'].'</td>';
        echo '<td class="Odd">'. $s_arr['SkidkaProcent'].'</td>';
        $sumdoc = sumdoc($s_arr['id']);
        echo '<td '.$stile.' > ' . $sumdoc . '</td>';
        echo '<td class="Odd" style="font-size: 0.9em; text-align: left" >'. $s_arr['Pometka'].'</td>';
        echo '</tr>';




    }



}

?>
<!--
вывод документов
-->

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript">
            //**********************baba-jaga@i.ua**********************
            function on_load(){
                if(!check_user() )  return;

            }

            //**********************baba-jaga@i.ua**********************
            function set_doc(iddoc, name_doc, kodviddoc, idfirm ){
                //alert('=' +iddoc + "  vid = " + viddoc );
                //window.open( phpfile + "?id_doc=" + iddoc );

               top.top_menu.location  = "newdoc_menu_vzv_klient.php?iddoc="+iddoc
                +"&idfirm="+idfirm
                +"&name_doc="+name_doc
                +"&viddocparent="+kodviddoc
                +"&viddoc=Возврат" ;

            }

        </script>

    </head>
    <body onload="javascript:on_load()"  >
       <div style=" padding: 0px 0px 0px 0px " >
            <table cellspacing='0' class="Design5"  >
                <col width="30px">
                <col width="75px">
                <col width="75px">
                <col width="150px">
                <col width="210px">
                <col width="39px">
                <col width="69px">
                <col >

                <thead>
                    <?php list_str(); // выводим список отобранных документов  ?>

                </thead>

            </table>
        </div>




    </body>
</html>
