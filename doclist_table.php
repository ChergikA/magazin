<?php
require("header.inc.php");
include("milib.inc");

$sum_kassa_prihod=0;
$sum_kassa_rashod=0;

$sum_bank_prihod=0;
$sum_bank_rashod=0;

$sum_drugoe_prihod=0;
$sum_drugoe_rashod=0;

$sum_hoz_prihod=0;
$sum_hoz_rashod=0;

//"&idstr=" + idstr ;
//
$idstr = 'it';
if ( isset($_GET['idstr']) ) {
    $idstr =  $_GET['idstr'] ;
    //echo 'idstr=' . $idstr;
}
//echo 'id=' . $idstr;

/*
//**********************baba-jaga@i.ua**********************
function itogo(){

    global $sum_kassa_prihod;
    global $sum_kassa_rashod;
    global $sum_terminal_prihod;
    global $sum_terminal_rashod;
    global $sum_bank_prihod;
    global $sum_bank_rashod;



    echo '<tr style="color: #0c0;" >';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_kassa_prihod .'</th>';
    echo '<th >'. $sum_kassa_rashod .'</th>';
    $it = $sum_kassa_prihod - $sum_kassa_rashod;
    echo '<th class="Odd">'. $it .'</th>';

    echo '</tr>';

    echo '<tr style="color: #E75480">';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_terminal_prihod .'</th>';
    echo '<th >'. $sum_terminal_rashod .'</th>';
    $it = $sum_terminal_prihod - $sum_terminal_rashod;
    echo '<th class="Odd">'. $it .'</th>';

    echo '</tr>';

    echo '<tr style="color: #00f">';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_bank_prihod .'</th>';
    echo '<th >'. $sum_bank_rashod .'</th>';
    $it = $sum_bank_prihod - $sum_bank_rashod;
    echo '<th class="Odd">'. $it .'</th>';

    echo '</tr>';

    $sum_pr = $sum_kassa_prihod + $sum_bank_prihod + $sum_terminal_prihod;
    $sum_rs = $sum_kassa_rashod + $sum_bank_rashod + $sum_terminal_rashod;
    echo '<tr id="it" >';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_pr .'</th>';
    echo '<th >'. $sum_rs .'</th>';
    $it = $sum_pr - $sum_rs;
    echo '<th class="Odd">'. $it .'</th>';

    echo '</tr>';







}
 * 
 */


//**********************baba-jaga@i.ua**********************
function itogo(){

    global $sum_kassa_prihod;
    global $sum_kassa_rashod;
    global $sum_bank_prihod;
    global $sum_bank_rashod;
    global $sum_drugoe_prihod;
    global $sum_drugoe_rashod;
    global $sum_hoz_prihod;
    global $sum_hoz_rashod;
    


    echo '<tr style="color: #0c0;" >';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_kassa_prihod .'</th>';
    echo '<th >'. $sum_kassa_rashod .'</th>';
    $it1 = $sum_kassa_prihod - $sum_kassa_rashod;
    echo '<th class="Odd">'. $it1 .'</th>';

    echo '</tr>';


    echo '<tr style="color: #00f">';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_bank_prihod .'</th>';
    echo '<th >'. $sum_bank_rashod .'</th>';
    $it2 = $sum_bank_prihod - $sum_bank_rashod;
    echo '<th class="Odd">'. $it2 .'</th>';

    echo '</tr>';

    echo '<tr style="color: #E75480">';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_drugoe_prihod .'</th>';
    echo '<th >'. $sum_drugoe_rashod .'</th>';
    $it3 = $sum_drugoe_prihod - $sum_drugoe_rashod;
    echo '<th class="Odd">'. $it3 .'</th>';

    echo '</tr>';

    echo '</tr>';

    echo '<tr style="color: #964B00">';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_hoz_prihod .'</th>';
    echo '<th >'. $sum_hoz_rashod .'</th>';
    $it4 = $sum_hoz_prihod - $sum_hoz_rashod;
    echo '<th class="Odd">'. $it4.'</th>';

    echo '</tr>';

    $sum_pr = $sum_kassa_prihod + $sum_bank_prihod+$sum_drugoe_prihod+$sum_hoz_prihod;
    $sum_rs = $sum_kassa_rashod + $sum_bank_rashod+$sum_drugoe_rashod+$sum_hoz_rashod;
    echo '<tr id="it" >';
    echo '<th >&nbsp;</th> <!-- состояние документа-->';
    echo '<th class="Odd">'. $sum_pr .'</th>';
    echo '<th >'. $sum_rs .'</th>';
    $it = $sum_pr - $sum_rs;
    echo '<th class="Odd">'. $it .'</th>';

    echo '</tr>';


}


//**********************baba-jaga@i.ua**********************
function viewtovar($iddoc){

    global $db;
    if($iddoc==-1) return FALSE;


    echo '  <div style=" padding: 0px 0px 20px 40px; font-size: 0.8em; " >';
    echo '  <!-- табличная часть документа -->';
    echo '  <table cellspacing="0" border="1" class="Design5" style=" font-size: 14px; " >';
    echo '    <col width="35px">';
    echo '    <col width="110px">';
    echo '    <col width="240px">'; // наименование
    echo '    <col width="55px">';
    echo '    <col width="55px">';  // к-во
    echo '    <col width="55px">';
    echo '    <col width="55px">';
    echo '    <col width="55px">';
    echo '<thead>';
    echo '  <tr>';
    echo '     <td>п/н</td>';
    echo '     <th class="Odd"> Код</th>';
    echo '     <th>             Наименование</th>';
    echo '     <th  class="Odd">К-во</th>';
    echo '     <td>             Цена</td>';
    echo '     <th class="Odd">Сумма</th>';
    echo '     <td>             Ск. %</td>';
    echo '     <th  class="Odd">Итог</th>';
    echo '     <th>&nbsp;</th>';
    echo '   </tr>';
    echo '</thead>';


    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`, `DocTab`.`Pometka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$iddoc.")\n"
    . "ORDER BY `DocTab`.`id` DESC ";

    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {


        $st =  'style="text-decoration: none; "';
        if($s_arr['Kvo']==0)$st =  'style="text-decoration: line-through; "';

        echo '<tr>';
        echo '<td  '. $st .'  >'. $s_arr['nomstr'] .'</td>';
        echo '<th class="Odd"  '. $st .'  >'. $s_arr['Kod'] .'</th>';
        echo '<th '. $st .' >'. $s_arr['Tovar'] .'</th>';
        echo '<th  class="Odd"  '. $st .'  >'. $s_arr['Kvo'] .'</th>';
        echo '<td  '. $st .'  >'. $s_arr['Cena'] .'</td>';
        $sum = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo']) ; //sprintf("%.2f", $sumdoc);
        echo '<th class="Odd"  '. $st .'  >'. $sum .'</th>'; //event, idstr, oldskidka
        echo '<td  '. $st .'  > '. $s_arr['Skidka'] .' </td>';
        $it = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo'] * ( 1 - $s_arr['Skidka']/100 ) ) ;
        echo '<th  class="Odd"  '. $st .'  >'.$it.'</th>';
        echo '<th  '. $st .'  >' . $s_arr['Pometka'] . '</th>';
        echo '</tr>';


    }


    echo '                    </table>';
    echo '                  </div>';


}

//**********************baba-jaga@i.ua**********************
function list_str(){

global $db;
    global $sum_kassa_prihod;
    global $sum_kassa_rashod;
    global $sum_bank_prihod;
    global $sum_bank_rashod;
    global $sum_drugoe_prihod;
    global $sum_drugoe_rashod;
    global $sum_hoz_prihod;
    global $sum_hoz_rashod;


$dt1 =  str_to_datesql($_GET['dt1']);
$dt2 =  str_to_datesql($_GET['dt2']);

$add_where = '';

if ( isset($_GET['firm']) )
    $add_where=$add_where. " AND `DocHd`.`firms_id` = '" . $_GET['firm'] . "' ";

if ( isset($_GET['vid_doc']) )
    $add_where=$add_where. " AND `DocHd`.`VidDoc_id` = '" . $_GET['vid_doc'] . "' ";

if ( isset($_GET['status_doc']) )
    $add_where=$add_where. " AND `DocHd`.`statusDoc` = '" . $_GET['status_doc'] . "' ";

if ( isset($_GET['avtor_doc']) )
    $add_where=$add_where. " AND `DocHd`.`users_id` = '" . $_GET['avtor_doc'] . "' ";

if ( isset($_GET['h_kod']) ) {
    $add_where=$add_where. " AND `Klient`.`diskont` LIKE '%".$_GET['h_kod']."%' ";

}elseif ( isset($_GET['fragment']) ) {
     $add_where=$add_where. " AND `Klient`.`name_klient` LIKE '%".$_GET['fragment']."%' ";

}



// касса и банк приход только чек и только продан
// касса и банк расход только возврат клиента и только принят

$str_where = "WHERE (`DocHd`.`DataDoc` >= '" . $dt1 . "' AND `DocHd`.`DataDoc` <= '" . $dt2 . "' " . $add_where . " ) ";

$txt_sql = "SELECT `DocHd`.`id`, `StatusDoc`.`images`,`StatusDoc`.`nameStatus`, `DocHd`.`flg_otpravlen`,
      `DocHd`.`DataDoc`,`DocHd`.`timedoc`, `DocHd`.`nomDoc`, `DocHd`.`oplataBank`, `DocHd`.`sum_v_kassu` , `DocHd`.`flg_optPrice`,"
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
         switch ($s_arr['oplataBank']){
             case 0 : $stile = " style='color: #0c0'    "; break; //rfssa
             case 1 : $stile = " style='color: #00f'    "; break; //bank
             case 2 : $stile = " style='color: #E75480' "; break; // drugoe
             case 3 : $stile = " style='color: #964B00' "; break; // hoz
             
         }
         
         $styleotpralen='';
         if(  $s_arr['flg_otpravlen'] == 1 )  $styleotpralen = "style='color: #0c0'";

         $del = ''; $delend='';
         if(  $s_arr['nameStatus'] == 'Удален' ){$del = '<DEL >'; $delend='</DEL>';}//style="color: #F93D00"

         $opt ='';
         if(  $s_arr['flg_optPrice'] == 1 ) $opt = " опт";



         $a = "<a " .$styleotpralen. " href='javascript:set_doc(". $s_arr['id'] ."," . '"'.   trim($s_arr['php_file']) . '"' .  ")'>" ;


         echo '      <div style=" padding: 0px 0px 0px 0px " >
            <table cellspacing="0" class="Design5"  >
                <col width="30px">
                <col width="75px">
                <col width="75px">
                <col width="160px">
                <col width="210px">
                <col width="39px">
                <col width="69px">
                <col >

                <thead>';

         $timedoc = $s_arr['timedoc'];
         $dtdoc = datesql_to_str( $s_arr['DataDoc']);
         $nomdoc = $s_arr['nomDoc'];
         $stylenom = ' style= "font-size: 12px; " ';


         $img = $s_arr['images'];
         if($s_arr['KodVidDoc'] == 'Счет'){
             if($s_arr['nameStatus'] == 'новый'){
                 if($s_arr['sum_v_kassu']>0) $img ='images/oplata.png';
             }
         }


         $dtdoc .= '<br><span style= "font-size: 11px; "> '.$timedoc.' </span>';
         if(strlen($nomdoc) < 4 )$stylenom='' ;

        echo '<tr id="'.$s_arr['id'].'" >';
        echo '<td ><img src="'. $img .'" border=0></td> ';
        echo '<td class="Odd" style= "font-size: 14px; text-align: center" >'. $dtdoc .'</td>';
        echo '<th'.$stylenom.' >'. $nomdoc .'</th>';
        echo '<td class="Odd" style="text-align: left" >'. $a . $del .  $s_arr['VidDoc'] . $opt . $delend . '</a> </td>';
        echo '<td style="font-size: 0.9em; text-align: left" >'. $s_arr['name_klient'].'</td>';
        echo '<td class="Odd">'. $s_arr['SkidkaProcent'].'</td>';
        $sumdoc = sumdoc($s_arr['id']);
        echo '<td '.$stile.' > ' . $sumdoc . '</td>';
        echo '<td class="Odd" style="font-size: 0.9em; text-align: left" >'. $s_arr['Pometka'].'</td>';
        echo '</tr>';

        echo '                </thead>

            </table>
        </div>';


        // подсчет итогов
        if( $s_arr['KodVidDoc'] == 'Чек' ){
            if($s_arr['nameStatus'] == 'Продан'){
                if($s_arr['oplataBank'] == 1 ){
                    $sum_bank_prihod = $sum_bank_prihod + $sumdoc ;
                }
                                if ($s_arr['oplataBank'] == 0) {
                    $sum_kassa_prihod =$sum_kassa_prihod + $sumdoc ;
                }               if ($s_arr['oplataBank'] == 2){
                    $sum_drugoe_prihod =$sum_drugoe_prihod + $sumdoc;
                }               if ($s_arr['oplataBank']==3) {
                    $sum_hoz_prihod = $sum_hoz_prihod + $sumdoc;
                }
            }
        }

       if( $s_arr['KodVidDoc'] == 'Счет' ){
            if($s_arr['nameStatus'] == 'Продан'){
                    $sum_bank_prihod = $sum_bank_prihod + $sumdoc ;
            }
        }

        //возвраты
        if( $s_arr['KodVidDoc'] == 'Возврат' ){
            if($s_arr['nameStatus'] == 'Принят'){
                if($s_arr['oplataBank'] == 1 ){
                    $sum_bank_rashod = $sum_bank_rashod + $sumdoc ;
                }
                                else {
                    $sum_kassa_rashod =$sum_kassa_rashod + $sumdoc ;
                }
            }
        }

        //выводим таблицу с товаром по требованию
        if(isset($_GET['viewtovar'])) viewtovar ($s_arr['id']);

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

        <script type="text/javascript">

            // при активации окна обновляем данные
            var active = true;
            var idstr = <?php echo '"' . $idstr . '"' ; // выводим список отобранных документов  ?> ;
            var idstriz = 'it';

            //**********************baba-jaga@i.ua**********************
            function set_doc(iddoc, phpfile){
                //alert('=' +iddoc + "  vid = " + viddoc );
                 idstriz =  iddoc ;
                if(phpfile==''){
                    alert('Служебный документ');
                    return;
                }
                window.open( phpfile + "?id_doc=" + iddoc );

            }

            function onfocus(){
                if(active == false) {
                    var loc = new String( document.location );
                    loc = loc.substr(0,loc.indexOf('&idstr=')); // часть строки до #
                    //loc = loc.substr(0,loc.indexOf('#')); // часть строки до #
                    if(loc=='')loc = document.location;
                    top.content.location = loc + "&idstr=" + idstriz ;

                    active = true;

                }
                active = true;
            }

            function offfocus(){
                active = false;
            }

            function onload(){
                    // скрипт прокрутки в самом низу

            }


        </script>

    </head>
    <body onblur="javascript:offfocus()" onfocus="javascript:onfocus()" onload="javascript:onload()"  >

                    <?php list_str(); // выводим список отобранных документов  ?>






       <div style=" padding: 25px 0px 0px 0px; margin-left: 25px; " >
           <table cellspacing='0' class="Design5" style=" width: 400px ;" >
                <col >
                <col width="130px">
                <col width="130px">
                <col width="130px">
                <col >


                <thead>
                    <tr>
                        <th >&nbsp;</th> <!-- состояние документа-->
                        <th class="Odd">Оплата</th>
                        <th >Возврат</th>
                        <th class="Odd">Итого</th>

                    </tr>

                    <?php itogo();   ?>

                </thead>

            </table>
        </div>


    </body>
    <script type="text/javascript">
        //Чтобы прокрутить страницу при помощи JavaScript, её DOM должен быть полностью загружен.
           var el = document.getElementById(idstr);
           el.scrollIntoView(true);
    </script>
</html>
