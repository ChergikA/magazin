<?php
require("header.inc.php");
include("milib.inc");
global $db;

$id_doc = -1;

$my_const = new cls_my_const();
$kvo_def = $my_const->kvo_def;

$h_kod =''; $info_tovar=""; $kvo   = $kvo_def ;


if (isset($_GET['id_doc']) ) { // 
    $id_doc = $_GET['id_doc'];
    if (write_doc($id_doc)) {
        $info_tovar = "<a style=' color: #1864fc; text-decoration: underline; font-size: 11px; ' 
                        href='javascript: podbor_tovar();'> Подобрать из прайс-листа </a>";
    }
}

$idstr = '0';//ид к которому прокрутим страницу

// печать этикетки &kvoprn=" + kvoprihod
if (isset($_GET['kvoprn'])) {
    // если установлен принтер штрих-кода, проверяем в константах
    $txt_sql = "SELECT `name` FROM `const` WHERE `kod` LIKE 'PrintHKod' ";
    $s_arr   = cls_my_sql::const_sql($txt_sql); 
    if ($s_arr['name'] == 1) {
        $kvoprn = $_GET['kvoprn'];
        $kod1c  = $_GET['kod1c'];
        $cn     = $_GET['cena'];
        $idtov  = id_tovar('', $kod1c);
        $idstr = trim($kod1c);

        $txt_sql = 'TRUNCATE cenniki'; // очистим табл ценников
        $sql     = mysql_query($txt_sql, $db);


        $txt_sql = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `tip_cn`,`cena`)
                VALUES ('" . $idtov . "', '" . $kvoprn . "', '0' , '0', '".$cn."');";
        $sql     = mysql_query($txt_sql, $db);
        require("prn_hkod.php");
        prnxml();
    }
    else {
        echo '!!! не установлен принтер штрих-кода';
    }
    //kod1c
}

// если введен штрих код
if (isset($_GET['h_kod'])) {

    $h_kod = $_GET['h_kod'];
    $kvo = $_GET['kvo'];



    $txt_sql = "SELECT `id_tovar`, `Tovar`, `Price`,`Ostatok` FROM `Tovar` WHERE  `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
    if ($kvo == '' or $kvo == '0') {                    //штрих код без количества
        //сформируем инфо о товаре
        $sql = mysql_query($txt_sql, $db);
        $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Tovar'] == NULL) {
            $info_tovar = "<a style=' color: red; text-decoration: underline; font-size: 14px; ' 
                        href='javascript: open_tovar_new();'> Нет товара с кодом h_kod </a>";
            $h_kod = '';
        } else
            $info_tovar = $s_arr['Tovar'] . "  Цена:" . $s_arr['Price'] . "  Ост.:" . $s_arr['Ostatok'];
    }else {                                             // введено и количество запишем строку в БД
        //получим цену товара
        $s_arr = cls_my_sql::const_sql($txt_sql);
        if ($s_arr['Price'] == NULL) {
            $info_tovar = "<a style=' color: red; text-decoration: underline; font-size: 14px; ' 
                        href='javascript: open_tovar_new();'> Нет товара с кодом $h_kod. Нажмите, чтобы создать новый товар</a>";
            $h_kod = '';
        } else {
            $id_doc = $_GET['id_doc'];
            $h_kod = $_GET['h_kod'];
            $kvo = $_GET['kvo'];
            $price = $s_arr['Price'];
            $id_tov = $s_arr['id_tovar'];


            // для золотого пера показываем розницу канцлера И НЕ МЕНЯЕМ
            if (cls_set::get_parametr('docPriemka', 'ZolotePero') == 1) {
                $txt_sql = "SELECT `Cena` FROM `DocTab` WHERE `Tovar_id`='$id_tov' and `DocHd_id`= '$id_doc'";
                $s_arr = cls_my_sql::const_sql($txt_sql);
                $price_doc = $s_arr['Cena'];
                $err = upd_tabdoc($id_doc, $h_kod, $price_doc, $kvo, 0, $price, 0);
            } else {
                $err = upd_tabdoc($id_doc, $h_kod, $s_arr['Price'], $kvo, 0, $s_arr['Price'], 0);
            }

            if ($err != '')
                $info_tovar = $err;
            //обнулим чтоб снова фокус на штрих коде
            $h_kod = '';
            $kvo = $kvo_def;
        }
    }
}

if (isset($_GET['kvoIn'])){
    $new_kvoIn=$_GET['kvoIn'];
    $id_str=$_GET['idstr'];
    $id_doc=$_GET['id_doc'];
    $cenap=$_GET['cenaP'];
    $cenar=$_GET['cenaR'];

    if (write_doc($id_doc)) {
        $txt_sql = " UPDATE `DocTab` SET `Kvo` = '" . $new_kvoIn . "',`Cena` = '" . $cenar . "'  WHERE `id` = '" . $id_str . "' ;";
        $sql     = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
}

if (isset($_GET['priceIn'])){
    if(isset($_GET['id_tovar'])){
    $new_priceIn=$_GET['priceIn'];
    $id_str=$_GET['idstr'];
    $id_doc=$_GET['id_doc'];
    $id_tovar=$_GET['id_tovar'];

    if (write_doc($id_doc)) {
        $txt_sql = " UPDATE `Tovar` SET `PriceIn` = '" . $new_priceIn . "' WHERE `id_tovar` = '" . $id_tovar . "' ;";
        $sql     = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
}
}

if (isset($_GET['priceRec'])){
    

    if(isset($_GET['id_tovar'])){
    $new_priceRec=$_GET['priceRec'];
    $id_str=$_GET['idstr'];
    $id_doc=$_GET['id_doc'];
    $id_tovar=$_GET['id_tovar'];

        if (write_doc($id_doc)) {
            $txt_sql = " UPDATE `Tovar` SET `Price` = '" . $new_priceRec . "' WHERE `id_tovar` = '" . $id_tovar . "' ;";
            $sql     = mysql_query($txt_sql, $db);
            $txt_sql = " UPDATE `DocTab` SET `Cena` = '" . $new_priceRec . "' WHERE `id` = '" . $id_str . "' ;";
            $sql     = mysql_query($txt_sql, $db);
            savesumdoc($id_doc);
        }
    }
}

//"doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
//перезапишем рекомендуемую цену одной строке и пересчитаем док
if (isset($_GET['newcena'])) {
    

    $newsk  = $_GET['newcena'];
    $id_str = $_GET['idstr'];
    $id_doc = $_GET['id_doc'];
    //$txt_sql =  "UPDATE `DocHd` SET `SkidkaProcent` = '". $newsk ."' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    //$sql     = mysql_query($txt_sql, $db);
    if (write_doc($id_doc)) {
        $txt_sql = " UPDATE `DocTab` SET `Cena` = '" . $newsk . "' WHERE `id` = '" . $id_str . "' ;";
        $sql     = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);

    }
}

//Перезапишем примечание документа или одной его строки при его изменении
if (isset($_GET['pometka'])) {

  //document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr + "&pometka=" + newpometka + "&p_in=" +  p_in + "&idtov=" + idtov ;
    
    $id_str = '';
    if (isset($_GET['idstr'])) $id_str = $_GET['idstr'];

    $pometka = $_GET['pometka'];
    $id_doc = $_GET['id_doc'];
    if (isset($_GET['p_in']))  $p_in = $_GET['p_in'];
    if (isset($_GET['idtov'])) $idtov = $_GET['idtov'];



    if(trim($id_str) == ''){
        $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    }else {
        // ввод процента наценки
        // пересчет розницы от входной
        $procent  =  (real)$pometka;
        $cn_rec = (( $procent + 100)*$p_in)/100; 
        //перезапишем рекомендуемую цену одной строке и пересчитаем док
            if (write_doc($id_doc)) {
             $txt_sql = " UPDATE `Tovar` SET `Price` = '" . $cn_rec . "' WHERE `id_tovar` = '" . $idtov . "' ;";
             $sql     = mysql_query($txt_sql, $db);
             $txt_sql = " UPDATE `DocTab` SET `Cena` = '" . $cn_rec . "' WHERE `id` = '" . $id_str . "' ;";
             $sql     = mysql_query($txt_sql, $db);
             savesumdoc($id_doc);
            }

    }


    $sql     = mysql_query($txt_sql, $db);

}


//"doc_priemka.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&newkod=" + kod  + "&idstr_=" + idstr_
// смена штрихкода в товаре или просто смена товара
if (isset($_GET['idstr_'])) {
    $id_str = $_GET['idstr_'];
    $id_doc = $_GET['id_doc'];
    $kod1c = $_GET['kod1c'];
    $newhkod = $_GET['newkod'];
    $cn_in   = $_GET['cn_in_'];

    //echo 'newhkod = ' . $newhkod ;
    if (write_doc($id_doc)) {
        //ищем товар по штркоду, если не находим, меняем штрих код
        $idt = id_tovar('', $newhkod);
        if ($idt == '') {
            $idt = id_tovar($newhkod);
        }
        

        if ($idt == '') { // меняем штрихкод товара UPDATE `nk`.`Tovar` SET `Kod` = '45454556789' WHERE `Tovar`.`id_tovar` =1;
            $txt_sql = "UPDATE `Tovar` SET `Kod` = '$newhkod' WHERE `Kod1C` ='$kod1c';";
        } else { // нашли товар, меняем его в строке и цену входную ему присвоим
            $txt_sql = " UPDATE `DocTab` SET `Tovar_id` = '" . $idt . "' WHERE `id` = '" . $id_str . "' ;";
            $sql = mysql_query($txt_sql, $db);
            $txt_sql = "UPDATE `Tovar` SET `PriceIn` = '$cn_in' WHERE `id_tovar` ='$idt';";
            //echo '='.$txt_sql;
            
        }
        $sql = mysql_query($txt_sql, $db);
    }
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
// удаление позиции документа
if (isset($_GET['delstr'])) {
    $id_str = $_GET['delstr'];
    $id_doc = $_GET['id_doc'];

    delstrdoc( $id_doc , $id_str ) ;
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";
// закончили работу с документом, либо продано, либо отмена
$closedoc = '';
if (isset($_GET['prodano'])) {
    $prodano = $_GET['prodano'];
    $id_doc  = $_GET['id_doc'];

    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('Принят');
            // перезапишем всю рекомендуемую в товар
            $txt_sql =  "SELECT `Tovar_id`,`Cena` FROM `DocTab` WHERE `DocHd_id`='$id_doc'";
            $sql = cls_my_sql::tbl_sql($txt_sql);
            while ($srt_arr = mysql_fetch_array($sql)){
                $price_rec = $srt_arr['Cena'];
                if($price_rec != 0){
                $txt_sql2 = " UPDATE `Tovar` SET `Price` = '$price_rec' WHERE `id_tovar` = '" . $srt_arr['Tovar_id'] . "' ;"; 
                cls_my_sql::run_sql($txt_sql2);
                }
                
            }

            
        }
        else {
            $idstatus = id_status('Удален');
        }

        $txt_sql  = "UPDATE `DocHd` SET `statusDoc` = '" . $idstatus . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        cls_my_sql::run_sql($txt_sql);
            
        //$sql      = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
    $closedoc = 'close';
}

// если открываем форму нужного, уже со всеми записями документа
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    // получим данные для шапки
    $txt_sql = "SELECT `firms`.`name_firm`, `Klient`.`name_klient`,`DocHd`.`id`,`DocHd`.`nomDoc`,"
    .         " `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `StatusDoc`.`nameStatus`,"
    .         "`DocHd`.`SkidkaProcent`, `DocHd`.`Pometka`, `VidDoc`.`name_doc`, `users`.`full_name`\n"
    . "FROM `firms`\n"
    . " LEFT JOIN `DocHd`     ON `firms`.`id` = `DocHd`.`firms_id` \n"
    . " LEFT JOIN `Klient`    ON `DocHd`.`Klient_id` = `Klient`.`id_` \n"
    . " LEFT JOIN `VidDoc`    ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` \n"
    . " LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus` \n"
    . " LEFT JOIN `users`     ON `DocHd`.`users_id` = `users`.`id` \n"
    . "WHERE (`DocHd`.`id` = " . $id_doc . " ) ";

//echo ' = ' . $txt_sql;

    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    $nm_firm = $s_arr['name_firm'];
    $nm_klient = $s_arr['name_klient'];
    //$skidka_doc =  $s_arr['SkidkaProcent'];
    $nm_doc =  $s_arr['name_doc'] . ' №_' . $s_arr['nomDoc'] . ' от:' . datesql_to_str( $s_arr['DataDoc']) ; //Товарный чек №_32 от 28.11.2012
    $avtor  =  "автор: " . $s_arr['full_name'];
    $pometka = addslashes( $s_arr['Pometka']) ; // экранируем символы

    if( ! write_doc($id_doc)) $nm_doc = $nm_doc . ' (ПРОСМОТР)';




}

//***********************************  baba-jaga@i.ua  ********************************************************
//выводим строки документа
function str_view(){
    global $id_doc;
    global $db;
    if($id_doc==-1) return FALSE;
   //                                 <td>п/н</td>
   //                                 <th class="Odd"> Код</th>
   //                                 <th>             Наименование</th>
   //                                 <th  class="Odd"> К-во <br> приход </th>
   //                                 <th>             К-во <br> принято </th>
   //                                 <th  class="Odd">нехватка</th>
   //                                 <td>             излишек</td>
   //                                 <th class="Odd">&nbsp;</th>
   //                                 <td>            Цена</td>
   //                                 <th  class="Odd">Цена <br> рекоменд. </th>
   //                                 <th>Пометка</th>
   //                                 <th>&nbsp;</th>

    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Cena2`, `DocTab`.`Kvo2`, `DocTab`.`pometka`, "
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`, `Tovar`.`PriceIn`, `Tovar`.`id_tovar`, `Tovar`.`fix_prc_rozn`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`timeShtamp` DESC ";

     //   color: #F93D00 ; /* красный  */
     //   color: #00f  /* синий  */

    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        $st = '';
        $razn =    $s_arr['Kvo'] - $s_arr['Kvo2']   ; //  принято -приход
        if($razn < 0){
            $st = ' style=" color: #F93D00 ;"';
        }elseif($razn > 0) {
            $st = ' style=" color: #00f ;"';
        }
        
        $fix_prc = $s_arr['fix_prc_rozn'];
        
        $tek_cena  = $s_arr['Cena2'];
        $new_cena  = $s_arr['Cena'];
        $vhod_cena = $s_arr['PriceIn'];
        

        $prnkod = trim($s_arr['Kod']);
        if(strlen($prnkod) < 10 ){// печатаем только свои
            $prnkod = '<a href = "javascript:;"  onclick="javascript:prnhkod('.'event' .','. "'" . $s_arr['Kod1C'] . "'" .','.  "'".  $s_arr['Kvo2'] .  "'". ','.  "'" . $s_arr['Kvo'] . "'" . ','.  "'" . $s_arr['Cena'] . "'" .  ' )" > <img src="images/b_print.png" border=0>' . $s_arr['Kod'] .' </a>';
        }
        
        $nomstr = '<a href = "javascript:;"  onclick="javascript:rehkod('.'event' .','. "'" . $s_arr['Kod1C'] . "'" .','.  "'".  $s_arr['Kod'] .  "'" .','.  "'".  $s_arr['idstr'] .  "'" .  ' )" > <img src="images/s_reload.png" border=0>' .  $s_arr['nomstr'] .' </a>';

        $pricein = $s_arr['PriceIn'];
        if($pricein == 0) $pricein='';
        
        echo '<tr  id="'.$s_arr['Kod'].'" >';
        echo '<td>'. $nomstr .'</td>';
        echo '<th class="Odd">'.$prnkod.'</th>';
        echo '<th '. $st .' >'. $s_arr['Tovar'] .'</th>';
        echo '<td  class="Odd" '. $st .' >'. $s_arr['Kvo2'] .'</td>';  //приход
        echo '<td '. $st .' ><input type="text" id="kvoIn'.$s_arr['idstr'].'" style="width:47px" onchange="javascript:upd_kvoIn(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['Kvo'] . "'" . ',' . "'" . $s_arr['id_tovar']. "'" . ',' . "'" . $s_arr['Cena2'] . "'" . ' )" value="'.$s_arr['Kvo'].'"></td>';           // принято
        echo '<td class="Odd" '. $st .' ><input type="text" id="priceIn'.$s_arr['idstr'].'" style="width:47px" onchange="javascript:upd_priceIn(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['Kvo'] . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )" value="'. $pricein .'"></td>';

        echo '<td class="Odd">&nbsp</td>';

        
        
        // разница цен
        $st = '';
        $razn =    $new_cena - $tek_cena   ; //
        if($razn < 0){
            $st = ' style=" color: #F93D00 ;"';
        }elseif($razn > 0) {
            $st = ' style=" color: #00f ;"';
        }
        
        $pometka = '___';
        //if(  trim($s_arr['pometka']) != '') $pometka = $s_arr['pometka'];
        if($s_arr['PriceIn']>0){
            $procent = ($new_cena*100/$s_arr['PriceIn']) - 100;
            $procent = sprintf("%.2f", $procent);
            $pometka = $procent . ' %';
        }
       
        if($fix_prc != 0 ){
            $procent = $fix_prc;
            $procent = sprintf("%.2f", $procent);
            $pometka = $procent . ' %';     
            $new_cena = (( $procent + 100)*$vhod_cena)/100; 
            
        }
        
        
        $idstr = $s_arr['idstr'];
        
        echo '<td            '. $st .'>'. sprintf("%.2f", $tek_cena) .'</td>';
       // echo '<td class="Odd"  ><a href = "javascript:;" '. $st .' onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. sprintf("%.2f", $s_arr['Cena']) .' )" > '. sprintf("%.2f", $s_arr['Cena']) .' </a>   </td>';
        echo '<td class="Odd"  ><input type="text" id="priceRec'.$idstr.'" style="width:47px" onchange="javascript:upd_priceRec(event,'. $s_arr['idstr'] .',' . "'" . $new_cena . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ' )"  value="'.$new_cena.'" ></td>';
        //echo '<td            >'. $s_arr['pometka'] .'</td>';
        echo '<td >            <a href = "javascript:;"           onclick="javascript:show_editpometka(event,'. $idstr .',' . "'" .  $pometka . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ',' . "'" . $vhod_cena . "'" . ' )" > '. $pometka .' </a>   </td>';

        if  ($fix_prc == 0 ){ // не зафиксированный процент наценки
            echo '<td >            <a href = "javascript:;"   onclick="javascript:fix_prc(event,'. $idstr .',' . "'" .  $procent . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ',' . "'fix'" . ' )" >    <img  id="img'.$idstr.'"  src="images/s_process.png" border=0></a>   </td>';
        }else{
            echo '<td >            <a href = "javascript:;"   onclick="javascript:fix_prc(event,'. $idstr .',' . "'" .  $procent . "'" . ',' . "'" . $s_arr['id_tovar'] . "'" . ',' . "'unfix'" . ' )" >  <img  id="img'.$idstr.'" src="images/s_passwd.png" border=0></a>   </td>';
        }
            
        echo '<td><a href = "javascript:;"  onclick="javascript:delstr('. $s_arr['idstr'] .' )" > <img src="images/b_drop.png" border=0>  </a> </td>';
        echo '</tr>';


    }

}

function sumIn($id_doc){
// Вычисляем входную сумму для документа Приемка Товара
    global $db;

    $sumIn=0;
    //$txt_sql = "SELECT sum( `Kvo` * `Cena` * (1-`Skidka` / 100 ) ) as sum FROM `DocTab` WHERE `DocHd_id` = " . $id_doc ;
    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Cena2`, `DocTab`.`Kvo2`, `DocTab`.`pometka`, "
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`, `Tovar`.`PriceIn`, `Tovar`.`id_tovar`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`timeShtamp` DESC ";

    $sql     = mysql_query($txt_sql, $db);
    
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ){
    $sumIn  = $sumIn+($s_arr['PriceIn']*$s_arr['Kvo']);
   }

   if($sumIn == NULL ) $sumIn = 0;
    $sumIn=sprintf("%.2f", $sumIn);
   return $sumIn ;
}


?>
<!--

-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title> <?php echo  $nm_doc ; ?> </title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="jquery/js/jquery-2.0.3.min.js"></script> 
        <script type="text/javascript" src="jquery/js/jquery-ui-1.8.2.custom.min.js"></script> 
        <link rel="stylesheet" href="jquery/css/smoothness/jquery-ui-1.8.2.custom.css" /> 

        <script type="text/javascript">
            //для прокрутки к нужному месту страницы
            var idstr_ =  <?php echo '"'. $idstr . '"' ; ?> ;

            function open_tovar_new () {
                window.open("tovar_new.php?new=new" ); 
            }

            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){
                return true ;
            }

            //**********************baba-jaga@i.ua**********************
            //печать этикеток штрих-кода
            function prnhkod( event,kod1c,kvoprihod,kvo,cena ){
                if(kvoprihod<kvo)kvoprihod=kvo;
                if(kvoprihod<1) return;
                
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("prnbar");

                obj.style.width =  '300px' ;
                obj.style.top = MouseY +120 + 'px' ;
                obj.style.left = MouseX -60 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_kod_1c').value  = kod1c;
                document.getElementById('_cena').value  = cena;
                document.getElementById('_kolvo').value  = kvo;
                document.getElementById('_kolvo').focus();

                var iddoc = <?php echo $id_doc; ?> ;
                //document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;

            }
            
            
            //**********************baba-jaga@i.ua**********************
            //смена штрих кода или товара на товар
            function rehkod( event,kod1c,kod,idstr_ ){

                //alert("="+idstr_);
                    
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("rehkod");

                obj.style.width =  '300px' ;
                obj.style.top = MouseY +120 + 'px' ;
                obj.style.left = MouseX -60 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_kod_1c').value  = kod1c;
                document.getElementById('_kod').value  = kod;
                document.getElementById('idstr_').value  = idstr_;
                document.getElementById('_kod').focus();
                document.getElementById('_kod').select();

                var iddoc = <?php echo $id_doc; ?> ;
                //document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;

            }
            
          
            // Эта функция вызываеться для смены штрих кода или товара в строке
             function re_rehkod() {
                var kod1c =   document.getElementById('_kod_1c').value ; 
                var kod   =   document.getElementById('_kod').value ;
                var iddoc = <?php echo $id_doc; ?> ;
                var idstr_= document.getElementById('idstr_').value;
                var id_cn =  'priceIn' +  idstr_ ; 
                //alert(id_cn);
                var cn_in_ = document.getElementById(id_cn ).value;
                //alert('cn='+cn_in_);
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&newkod=" + kod  + "&idstr_=" + idstr_ + "&cn_in_=" + cn_in_  ;
            }
            
            
            // Эта функция вызываеться с всплывающего окна для печати этикеток в заданном кол-ве
             function prn_et () {
                var kod1c =   document.getElementById('_kod_1c').value ; 
                var cena   =  document.getElementById('_cena').value ;
                var iddoc = <?php echo $id_doc; ?> ;
                var kvoprihod= document.getElementById('_kolvo').value;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;
            }




            //**********************baba-jaga@i.ua**********************
            //откр форму печати ценников
            function prn(){
                var iddoc = <?php echo $id_doc; ?> ;
                window.open("doc_cennik.php?iddoc=" +  iddoc + "&add=1");
            }

            //**********************baba-jaga@i.ua**********************
            //смена штрих кода в поле
            function re_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo  ;
            }

            //**********************baba-jaga@i.ua**********************
            //нажата кнопка отмена во вводе штрих кода
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //открываем всплывающее окошко для редактирования рекомендуемой цены
            function show_bar(event, idstr, oldskidka) {

                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("win");

                obj.style.top = MouseY - 40 + 'px' ;
                obj.style.left = MouseX -160 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_edit').value  = oldskidka;
                document.getElementById('_idstr').value  = idstr;

                document.getElementById('_edit').focus();


            }

            //**********************baba-jaga@i.ua**********************
            //просто закрываем окно редактирования скидки цены или пометки
            function hide_bar() {
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("winpometka").style.visibility="hidden";
                document.getElementById("prnbar").style.visibility="hidden";
                document.getElementById("rehkod").style.visibility="hidden";
            }

            //*********************baba-jaga@i.ua**********************
            // окно редактирования примечания
            // теперь редактим процент наценки
            function show_editpometka(event, idstr ,txtpometka,idtov, p_in){
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("winpometka");

                var pox = -620;
                if(idstr == '' ) pox=20;

                obj.style.width =  '200px' ;
                obj.style.top = MouseY -60 + 'px' ;
                obj.style.left = MouseX + pox + 'px' ;
                obj.style.visibility = "visible";

                document.getElementById('_idstrp').value  = idstr;
                document.getElementById('_p_in').value  = p_in;
                document.getElementById('_idtov').value  = idtov;

                document.getElementById('_editpometka').style.width =  '100px' ;
                document.getElementById('_editpometka').value  = txtpometka ;
                document.getElementById('_editpometka').focus();
                document.getElementById('_editpometka').select();
            }

            //**********************baba-jaga@i.ua**********************
            //смена рекомендуемой цены в строке
            function re_skidka_str(){
                ///alert('reskidka_str');
                var iddoc = <?php echo $id_doc; ?> ;
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden"

                var newskidka = document.getElementById('_edit').value ;
                var idstr     = document.getElementById('_idstr').value;
                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr + "&newcena=" + newskidka ;
            }

            //*********************baba-jaga@i.ua**********************
            //записываем пометку и закрываем окно
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                var idstr      = document.getElementById('_idstrp').value;
                var p_in       = document.getElementById('_p_in').value;
                var idtov      = document.getElementById('_idtov').value;

                //document.getElementById('h_kod').focus();
                //document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );
                if(idstr == '') {
                   document.location = "doc_priemka.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
                }else{ // меняем рекомендуемую согласно выбранной наценке
                   document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr + "&pometka=" + newpometka + "&p_in=" +  p_in + "&idtov=" + idtov ;
                }

            }

            //**********************baba-jaga@i.ua**********************
            //кнопка удаления строки
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }


            //**********************baba-jaga@i.ua**********************
            //продали товар закрываем док с пометкой продано
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&prodano=ok";

            }

            //**********************baba-jaga@i.ua**********************
            // отмена продажи закрываем док с пометкой удален
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&prodano=no";

            }

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                var h_kod =   <?php echo '"' . $h_kod . '"'; ?>;

                var closedoc =   <?php echo '"' . $closedoc . '"'; ?>;
                //alert('=='+closedoc);
                if(closedoc != '') window.close();

                document.frm_searh.h_kod.value =  h_kod  ;
                document.frm_searh.kvo.value  = <?php echo '"' . $kvo . '"'; ?>;

                if (idstr_  != 0 ) return;

                if( h_kod == '' ) document.getElementById('h_kod').focus();
                else {
                    document.getElementById('kvo').focus() ;
                    document.getElementById('kvo').select() ;
                }

                plays();
               // hide_bar(); // не показывать вспл окно

            }
            
            //**********************baba-jaga@i.ua**********************
            // открываем форму подбора из прайс-листа
            function podbor_tovar(){
                //alert('=' +iddoc + "  vid = " + viddoc );
                var iddoc = <?php echo $id_doc; ?> ;
                window.open("podbor_tovar.php?id_doc=" + iddoc );
                window.close();

            }

            //**********************baba-jaga@i.ua**********************
            // нажата кнопка закрыть
            function closeWin(){
               window.close();
            }

            //**********************baba-jaga@i.ua**********************
            // по идее дзинькает при нажатии
            function plays() {
              var snd = new Audio("images/ok.wav");

               snd.preload = "auto";

                 snd.load();
                 snd.play();

            }

             function upd_kvoIn(event, idstr ,new_kvo, id_tovar, cena){
                var new_kvoIn=document.getElementById('kvoIn'+idstr).value;
                var cenaR    =document.getElementById('priceRec'+idstr).value; 
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr + "&kvoIn=" + new_kvoIn + "&cenaP=" + cena + "&cenaR=" + cenaR;
            }

              function upd_priceRec(event, idstr ,new_kvo, id_tovar){
                var new_priceRec =document.getElementById('priceRec'+idstr).value;
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr +"&id_tovar=" + id_tovar + "&priceRec=" + new_priceRec ;
            }

             function upd_priceIn(event, idstr ,new_kvo, id_tovar){
                var new_priceIn =document.getElementById('priceIn'+idstr).value;
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_priemka.php?id_doc=" + iddoc + "&idstr=" + idstr  +"&id_tovar=" + id_tovar + "&priceIn=" + new_priceIn ;
            }
            
            function fix_prc( event, idstr ,prc, id_tovar, flg  ){
               //alert(id_tovar);
                var query = 'js/fix_prc.php?idtov='+id_tovar + "&fix=" + flg + "&prc=" + prc;
                        $.ajax({
                          type: 'GET',
                          url: query,
                          dataType: 'text',
                          async: false,
                          success: function(data)
                          {
                           // alert(data);
                            if(flg === 'fix'){
                                $("#img" + idstr ).attr("src","images/s_passwd.png");
                            }else{ // unfix
                                $("#img" + idstr ).attr("src","images/s_process.png");
                            }
                            
                          },
                          error:function (xhr, ajaxOptions, thrownError){
                            alert(xhr.status);
                            alert(thrownError);
                          }
                        });
            }


        </script>
    </head>
    <body onload="javascript:on_load()" >

        <!-- таблица делящая страницу -->
        <table cellspacing='3' border='0' style=" width: 100%;  margin: 0px " >
            <col >
            <col width="800px">
            <col >
            <tr>
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- левая колонка основной таблицы -->
                <td> <!-- средняя колонка основной таблицы -->


                    <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                            <!-- таблица упорядочивания данных формы ввода -->
                            <table cellspacing='0' border='0' style=" width: 100%;  " >
                                <col width="170px">
                                <col width="200px">
                                <col width="190px">
                                <col width="100px">
                                <col width="80px">
                                <col >
                                <tr> <!-- первая строка формы -->
                                    <td> <h3 style="font-size: 1.3em; text-align: left " > <?php echo  $nm_firm ; ?>  </h3> </td>
                                    <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" > <?php echo  $nm_doc ; ?>   </h3> </td>
                                    <td colspan="3" > <h3 style="font-size: 1.1em; text-align: right " > <?php echo  $avtor ; ?>   </h3> </td>
                                </tr>
                                <tr> <!-- вторая строка формы -->
                                    <td colspan="2" > <h4> <?php echo  $nm_klient ; ?>  </h4> </td>
                                    <td> &nbsp;</td>

                                    <td> <h4> &nbsp;</h4> </td>
                                    <td>  &nbsp; </td>
                                    <td> <a href="#" onclick="javascript:prodano()" > <img src="images/btnPriem.jpg" border=0> </a> </td>
                                </tr>
                                <tr> <!-- третья строка формы ИНФО О Товаре -->
                                    <td colspan="3" >
                                        <h3 style="font-size: 1.2em; font-style: italic; font-weight: normal; " >
                                             <?php echo  $info_tovar ; ?>
                                        </h3>
                                    </td>
                                    <td> <h4>Всего приход.</h4> </td>
                                    <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php echo '"' . sumIn($id_doc) . '"' ; ?>  > </td>
                                    <td> <a href="#" onclick="javascript:prn()" > <img src="images/btnPrint.jpg" border=0> </a> </td>
                                </tr >
                                <tr  > <!-- четвертая строка формы -->
                                    <td colspan="3" >

                                        Код товара: &nbsp; <input type="text" name="h_kod" id="h_kod" size="7" maxlength="19"
                                                                  tabindex="0" onchange="javascript:re_h_kod()" >
                                        &nbsp; &nbsp; &nbsp; к-во:
                                        <input type="text" name="kvo" id ="kvo"  size="4" maxlength="13"
                                               tabindex="1" onchange="javascript:re_h_kod()" >
                                        <input type="button" name="button_ok" id="button_ok"  onclick="javascript:re_h_kod()" value="  Ok  "    >
                                        <input type="button" name="button_re" id="button_re"  onclick="javascript:otmena_h_kod()" value="  Отмена  "    >

                                    </td>
                                    <td> <h4>ВСЕГО: </h4> </td>
                                    <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php echo '"' . sumdoc($id_doc) . '"' ; ?>  > </td>
                                    <td> <a href="#" onclick="javascript:ne_prodano()" > <img src="images/btnDel.jpg" border=0> </a>  </td>

                                </tr>
                            </table>


                   </form>



                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- табличная часть документа -->
                        <table cellspacing='0' border='0' class="Design5" >
                            <col width="55px">
                            <col width="160px">
                            <col width="260px">  <!-- наименование -->
                            <col width="60px">
                            <col width="60px">
                            <col width="60px">
                            <col width="5px">
                            <col width="60px"> <!-- цена -->
                            <col width="65px">
                            <col width="85px"><!-- pometka -->
                            <col width="35px"><!-- del -->
                            <col width="25px"><!-- fix -->


                            <thead>
                                <tr>
                                    <td>п/н</td>
                                    <th class="Odd"> Код</th>
                                    <th>             Наименование</th>
                                    <th  class="Odd"> К-во <br> приход </th>
                                    <th>             К-во <br> принято </th>
                                    <th  class="Odd">Цена<br>входная</th>
                                    <th class="Odd">&nbsp;</th>
                                    <td>Цена <br>магазина</td>
                                    <th  class="Odd">Цена <br>рекоменд.</th>
                                    <th  class="Odd">Наценка<br>%</th>
                                    <th>&nbsp;</th>
                                    
                                </tr>
                            </thead>

                            <?php  str_view() ; ?>


                        </table>
                    </div>

                    <div>
                        <br>
                        <a href="#" onclick="javascript:show_editpometka(event, '' ,<?php echo "'" . $pometka . "'" ; ?> )" > Примечание:</a>  <?php echo $pometka ; ?>

                    </div>

                </td> <!-- конец средней колонки основной таблицы -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- правая колонка основной таблицы -->
            </tr></table> <!--конец таблицы делящая страницу -->

            <!-- всплывающее окно для редактирования данных в таблице -->
            <div id=win class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Цена: <input type="text" name="_edit" id ="_edit" size="5" onchange="javascript:re_skidka_str()" >
                <input type="hidden" id ="_idstr">
            </div>

            <!-- всплывающее окно для редактирования примечания -->
            <div id=winpometka class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                наценка в %: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >
                <input type="hidden" id ="_idstrp">
                <input type="hidden" id ="_p_in">
                <input type="hidden" id ="_idtov">
                
            </div>
            
           <!-- всплывающее окно для печати этикеток = -->
            <div id=prnbar class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Кол-во этикеток: <input type="text" name="_kolvo" id ="_kolvo" size="5" >
                <input type="button" name="btn_prn" id="btn_prn"  onclick="javascript:prn_et()" value="  Ok  "    >
                
                <input type="hidden" id ="_kod_1c">
                <input type="hidden" id ="_cena">
                
            </div>
           
            <!-- всплывающее окно для смены штрихкода -->
            <div id=rehkod class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                штрихкод: <input type="text" name="_kod" id ="_kod" size="12" >
                <input type="button" name="btn_prn" id="btn_prn"  onclick="javascript:re_rehkod()" value="  Ok  "    >
                <input type="hidden" id ="idstr_">
                <input type="hidden" id ="_kod_1c">
                
                
            </div>


    </body>

     <script type="text/javascript">
        //Чтобы прокрутить страницу при помощи JavaScript, её DOM должен быть полностью загружен.
           var el = document.getElementById(idstr_);
           el.scrollIntoView(true);
    </script>

</html>
