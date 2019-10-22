<?php
require("header.inc.php");
include("milib.inc");
include ("print.inc");
global $db;

$id_doc = -1;
//$flgopt = '' ; // далее переназначается в функции

$my_const = new cls_my_const();
$kvo_def = $my_const->kvo_def;

$cls_sql = new cls_my_sql;

$h_kod =''; $info_tovar=""; $kvo   = $kvo_def ; $play='';

//$tip_prn = $cls_sql->const_sql("SELECT `name` FROM `const` WHERE `kod`='tip_prn_chek'");
$tip_prn = cls_set::get_parametr('docChek','tip_prn'); // для javascript prn()

if (isset($_GET['id_doc'])) { // 
    $id_doc = $_GET['id_doc'];

    $sel_oplata = '';
    $sel_oplata_ks = '';
    $sel_oplata_trm = '';
    $sel_oplata_chek = '';
    $sel_oplata_hoz = '';
    $sel_oplata_schet1 = '';
    $sel_oplata_schet2 = '';

    $set_sel_oplata = cls_set::get_parametr('docChek', 'f_oplata');


    if (write_doc($id_doc)) {
        $info_tovar = "<a style=' color: #1864fc; text-decoration: underline; font-size: 11px; ' 
                        href='javascript: podbor_tovar();'> Подобрать из прайс-листа </a>";

    }  else {
        // док только для чтения оплату прочитаем которую выставили при продаже
        
        $txt_sql = "SELECT `oplataBank` FROM `DocHd` WHERE `id` = '$id_doc'";
        $s_arr = cls_my_sql::const_sql($txt_sql);
        $set_sel_oplata = $s_arr['oplataBank'];
        
        //echo " = " . $set_sel_oplata;
        
    }

    switch ($set_sel_oplata) {
        case -1: $sel_oplata = "selected='selected'";
            break;
        case 0: $sel_oplata_ks = "selected='selected'";
            break;
        case 1: $sel_oplata_trm = "selected='selected'";
            break;
        case 2: $sel_oplata_chek = "selected='selected'";
            break;
        case 3: $sel_oplata_hoz = "selected='selected'";
            break;
        case 4: $sel_oplata_schet1 = "selected='selected'";
            break;
        case 5: $sel_oplata_schet2 = "selected='selected'";
            break;
    }

// форма оплаты выбор
    $f_oplata = " <h4 style='float: left; /* Обтекание справа чтоб все одной строкой*/' >Оплата: </h4>
                    <select id ='sel_oplata' style='width: 100px' >
                    <option $sel_oplata         value=''>                </option>
                    <option $sel_oplata_ks      value='0'>   Касса       </option>
                    <option $sel_oplata_trm     value='1'>   Терминал    </option>
                    <option $sel_oplata_chek    value='2'>        Чек    </option>
                    <option $sel_oplata_hoz     value='3'> Хоз. нужды    </option>
                    <option $sel_oplata_schet1  value='4'> Счет на оплату</option>
                    <option $sel_oplata_schet2  value='5'>Счет - товар выдан!</option>
                                        </select>";


    //document.location = "doc_chek.php?id_doc=" + iddoc + "&prn=yes"+ "&opt=yes";
    if (isset($_GET['prn'])) {
        $prn = $_GET['prn'];
        if ($prn == 'yes') {
            if (isset($_GET['opt'])) { // чеки на лазерный файл fods
                $opt = $_GET['opt'];
                if($opt==='yes'){
                   include("prn/prn_checkopt.php");
                   prnxml($id_doc);
                }  else {
                    include("prn/prn_check.php");
                    prnxml($id_doc);    
                }
            } else {                    // чековый принтер
                $prn = new prn();
                $prn->prn_doc($id_doc);
            }
        }
    }
}

// если введен штрих код
if (isset($_GET['h_kod']) ) {

    $h_kod = $_GET['h_kod'];
    $kvo   = $_GET['kvo'];
    $flgopt= $_GET['flgopt']; 	
    $id_doc = $_GET['id_doc'];
    $skidka = $_GET['skidka'];
    
    $sql_query = "SELECT `Tovar`, `Price`,`PriceOpt`,`Ostatok` FROM `Tovar` WHERE `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
    $s_arr     = $cls_sql->const_sql($sql_query);
	
    $price = $s_arr['Price']; 	
    if ($flgopt == 'опт') $price = $s_arr['PriceOpt'];	

    if ($kvo == '' or $kvo == '0') {                    //штрих код без количества
        //сформируем инфо о товаре
        if ($s_arr['Tovar'] == NULL) {
            $info_tovar = "Нет товара с кодом:" . $h_kod;
            $h_kod      = '';
        }else $info_tovar = $s_arr['Tovar'] . "  Цена:" . $price . "  Ост.:" . $s_arr['Ostatok'];
    }else {                                             // введено и количество запишем строку в БД
        //получим цену товара
        if ( $s_arr['Price'] == NULL or $s_arr['Price'] == 0 or $s_arr['Price'] == '' or $s_arr['Price'] == '0'  ) {
            $info_tovar = "не верная цена для: " . $s_arr['Tovar'] . "     =" . $s_arr['Price'];
            $h_kod      = '';
        }else {

            $err = upd_tabdoc($id_doc , $h_kod , $price , $kvo , $skidka, 0 , 0 );
            if($err != '') $info_tovar = $err;
            //обнулим чтоб снова фокус на штрих коде
            $h_kod ='';  $kvo   = $kvo_def ; $play='play';
        }

    }
}

//перезапишем скидку дока и пересчитаем его
if (isset($_GET['newskidka'])) {

    $newsk  = $_GET['newskidka'];
    $id_doc = $_GET['id_doc'];

    if (write_doc($id_doc)) {

        reskidkadoc($id_doc, $newsk);

        savesumdoc($id_doc);
    }
}

//изменим тип скидки
if (isset($_GET['tip_skidki'])) {

    $tip_skidki  = $_GET['tip_skidki'];
    $id_doc = $_GET['id_doc'];
    if (write_doc($id_doc)) {
        
        $txt_sql = "UPDATE `DocHd` SET `tip_skidki` = '$tip_skidki' WHERE `DocHd`.`id` ='$id_doc';" ;
        cls_my_sql::run_sql($txt_sql);
      
       // reskidkadoc($id_doc, $newsk);

       savesumdoc($id_doc);
    }
}

//"doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
//перезапишем скидку в одной строке и пересчитаем док
if (isset($_GET['idstr'])) {
    
    $newsk  = '-';
    if(isset($_GET['skidka'])) $newsk  = $_GET['skidka'];
    $newcena = '-';
    if(isset($_GET['cena'])) $newcena  = $_GET['cena'];
     
    
    $id_str = $_GET['idstr'];
    $id_doc = $_GET['id_doc'];
   
    $txt_sql =  "SELECT `tip_skidki`,`SkidkaProcent` FROM `DocHd` WHERE `id`='$id_doc'";
    $s_arr = cls_my_sql::const_sql( $txt_sql );
    If( $s_arr['tip_skidki'] != 'Накопительная' ){
    //$txt_sql =  "UPDATE `DocHd` SET `SkidkaProcent` = '". $newsk ."' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    //$sql     = mysql_query($txt_sql, $db);
        if (write_doc($id_doc)) {
            if($newsk != '-'){
                $txt_sql = " UPDATE `DocTab` SET `Skidka` = '" . $newsk . "' WHERE `id` = '" . $id_str . "' ;";

            }
            if($newcena !='-'){
              //  $txt_sql = " UPDATE `DocTab` SET `Cena` = '" . $newcena . "' WHERE `id` = '" . $id_str . "' ;";

                // надо пересчитывать скидку относительно цены
            }
            cls_my_sql::run_sql($txt_sql);
            savesumdoc($id_doc);
        }
    }

}

//Перезапишем примечание документа при его изменении
if (isset($_GET['pometka'])) {
    
    if (write_doc($id_doc)) {
    
    $pometka = $_GET['pometka'];
    $id_doc = $_GET['id_doc'];
    $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    cls_my_sql::run_sql($txt_sql);
    }
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
// удаление позиции документа
if (isset($_GET['delstr'])) {
    $id_str = $_GET['delstr'];
    $id_doc = $_GET['id_doc'];

    delstrdoc( $id_doc , $id_str ) ;
    $play='play';
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";
// закончили работу с документом, либо продано, либо отмена
$closedoc = '';
if (isset($_GET['prodano'])) {
    $prodano = $_GET['prodano'];
    $id_doc = $_GET['id_doc'];
    $sum_v_kassu = 0;
    $oplata = 0;
    if(isset($_GET['oplata']))    $oplata = $_GET['oplata']; //
            //<option  value="4">    Счет на оплату</option>
            //<option  value="5">Счет - товар выдан!</option>



    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('Продан');
            $sum_v_kassu = $_GET['sum_v_kassu'];

            if ($oplata == '4') { // Счет выписан, но товар еще не выдан
                $idstatus = id_status('новый');
            }
        } else {
            $idstatus = id_status('Удален');
        }

        $txt_sql = "UPDATE `DocHd` SET `statusDoc`   = '" . $idstatus . "',
                                        `sum_v_kassu` = " . $sum_v_kassu . ",
                                        `oplataBank`  = " . $oplata . "  WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
       //echo '='.$txt_sql;
        cls_my_sql::run_sql($txt_sql);
        savesumdoc($id_doc);
    } else {// редактить нельзя но есл это был счет на оплату
        // и оплата стала равна 5 то значит по нему выдали товар
        // счет на оплату = 4 -  можно удалить
        $txt_sql = "SELECT `oplataBank`,`statusDoc` FROM `DocHd` WHERE `id`= '" . $id_doc . "'";
        $s_arr = cls_my_sql::const_sql($txt_sql); 

        $oplata_old = $s_arr['oplataBank'];
        $idstatus = $s_arr['statusDoc'];
        if ($oplata_old == '4') {
            if ($prodano == 'ok') {
                if ($oplata == '5') {
                    $idstatus = id_status('Продан');
                    $txt_sql = "UPDATE `DocHd` SET `statusDoc`   = '" . $idstatus. "', "
                    . "`oplataBank` = '".$oplata."' WHERE `DocHd`.`id` = '" . $id_doc . "'";
                    $sql = mysql_query($txt_sql, $db);
                }
            } else {
                $idstatus = id_status('Удален');
                $txt_sql = "UPDATE `DocHd` SET `statusDoc` = '" . $idstatus. "' WHERE `DocHd`.`id` = '" . $id_doc . "'";
                $sql = mysql_query($txt_sql, $db);              
            }

            
        }
    }


    $closedoc = 'close';
}

// если открываем форму нужного, уже со всеми записями документа
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    // получим данные для шапки
    $txt_sql = "SELECT `firms`.`name_firm`, `Klient`.`name_klient`,`DocHd`.`id`,`DocHd`.`nomDoc`,"
    .         " `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`flg_optPrice` , `StatusDoc`.`nameStatus`,"
    .         "`DocHd`.`SkidkaProcent`,`tip_skidki`, `DocHd`.`Pometka`, `VidDoc`.`name_doc`, `users`.`full_name`\n"
    . "FROM `firms`\n"
    . " LEFT JOIN `DocHd`     ON `firms`.`id` = `DocHd`.`firms_id` \n"
    . " LEFT JOIN `Klient`    ON `DocHd`.`Klient_id` = `Klient`.`id_` \n"
    . " LEFT JOIN `VidDoc`    ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` \n"
    . " LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus` \n"
    . " LEFT JOIN `users`     ON `DocHd`.`users_id` = `users`.`id` \n"
    . "WHERE (`DocHd`.`id` = " . $id_doc . " ) ";

//echo ' = ' . $txt_sql;

    $s_arr   = cls_my_sql::const_sql( $txt_sql );

    $nm_firm = $s_arr['name_firm'];
    $nm_klient = $s_arr['name_klient'];
    
    $flgopt = 'розница' ;
    if( $s_arr['flg_optPrice'] == 1) $flgopt = 'опт' ; 
   
        
    $skidka_doc =  $s_arr['SkidkaProcent'];
    $nm_doc =  $s_arr['name_doc']  . ' №_' . $s_arr['nomDoc'] . ' от:' . datesql_to_str( $s_arr['DataDoc']) ; //Товарный чек №_32 от 28.11.2012
    //$nm_doc.= $flgopt;
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
    //                               <tr>
  //                                  <td>п/н</td>
  //                                  <th class="Odd"> Код</th>
  //                                  <th>             Наименование</th>
  //                                  <th  class="Odd">К-во</th>
  //                                  <td>             Цена</td>
  //                                  <th class="Odd">Сумма</th>
  //                                  <td>             Ск. %</td>
 //                                   <th  class="Odd">Итог</th>
 //                                   <th>д</th>
 //                                </tr>

    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`,`Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`timeShtamp` DESC ";



    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        echo '<tr>';
        echo '<td>'. $s_arr['nomstr'] .'</td>';
        echo '<th class="Odd">'. $s_arr['Kod1C'] .'</th>';

        $st =  'style="text-decoration: none; "';
        if($s_arr['Kvo']==0)$st =  'style="text-decoration: line-through; "';

        echo '<th '.$st.' >'. $s_arr['Tovar'] .'</th>';
        echo '<th  class="Odd">'. $s_arr['Kvo'] .'</th>';
        echo '<td><a href="#" onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. $s_arr['Cena'] .',1 )" > '. $s_arr['Cena'] .' </a>   </td>';
        //echo '<td>'. $s_arr['Cena'] .'</td>';
        $sum = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo']) ; //sprintf("%.2f", $sumdoc);
        echo '<th class="Odd">'. $sum .'</th>'; //event, idstr, oldskidka
        echo '<td><a href="#" onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. $s_arr['Skidka'] .' )" > '. $s_arr['Skidka'] .' </a>   </td>';
        $it = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo'] * ( 1 - $s_arr['Skidka']/100 ) ) ;
        echo '<th  class="Odd">'.$it.'</th>';

        $img = 'images/b_drop2.png';
        if($s_arr['Kvo']==0) $img = 'images/b_drop.png';
        echo '<th><a href="#"  onclick="javascript:delstr('. $s_arr['idstr'] .' )" > <img src="'.$img.'" border=0> </a> </th>';

        echo '</tr>';


    }

}

//***********************************  baba-jaga@i.ua  ********************************************************
function view_tip_skidki(){
    global $flgopt;
    global $s_arr;
    global $id_doc;


    if( cls_set::get_parametr('priceList', 'cenaOpt') == 1 ){ // если авто переход на оптовые цены
        //$flgopt = 'розница' ;
        //if( $s_arr['flg_optPrice'] == 1) $flgopt = 'опт' ; 
        
        echo $flgopt;
    }
    
    if( cls_set::get_parametr('priceList', 'skidkaOtSum') == 1 ){// если градация скидок
      //тип скидкидки накопительная или произвольная
      $tip_skidki = $s_arr['tip_skidki'];
      if ( $tip_skidki == ''  )  $tip_skidki = 'Накопительная';  
    
      if($tip_skidki == 'Накопительная'){
          $sel_nak = "selected='selected'";
          $sel_pr  = "";
      }else{ // Произвольная скидка
          $sel_nak = "";
          $sel_pr  = "selected='selected'"; 
      }
        //&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      echo " <h4 style='float: left; ' >Тип ск.: </h4>
            <select id ='sel_skidka' style='width: 100px' onchange='javascript:re_tip_skidki()'  >
                    <option $sel_nak         value='Накопительная'>Накопительная</option>
                    <option $sel_pr          value='Произвольная' >Произвольная</option>
                                        </select>";
      
        
    }   
    
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


        <script type="text/javascript">



            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){
                return true ;
            }

            //**********************baba-jaga@i.ua**********************
            function re_tip_skidki(){
                var iddoc       = <?php echo $id_doc; ?> ;

                var gr = document.getElementById("sel_skidka");
                var tip_skidki = gr.options[gr.selectedIndex].value;

                //alert("=" + tip_skidki );
                document.location = "doc_chek.php?id_doc=" + iddoc + "&tip_skidki=" + tip_skidki ;
            }

            //**********************baba-jaga@i.ua**********************
            //смена скидки в документе
            function re_skidka(){
                //alert('reskidka');

                var iddoc = <?php echo $id_doc; ?> ;
                var newskidka = document.frm_searh.skidka_doc.value;

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_chek.php?id_doc=" + iddoc + "&newskidka=" + newskidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //смена скидки в строке
            function re_skidka_str(){
                ///alert('reskidka_str');
                var iddoc = <?php echo $id_doc; ?> ;
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden"
                
                var tipedit   = document.getElementById('_tipedit').value ;
                var newskidka = document.getElementById('_edit').value ;
                var idstr     = document.getElementById('_idstr').value;
                if(tipedit == 1){
                   document.location = "doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&cena=" + newskidka ; 
                }else{
                   document.location = "doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ; 
                }
                
                //
            }

            //**********************baba-jaga@i.ua**********************
            //смена штрих кода в поле
            function re_h_kod(){
                var iddoc  = <?php echo $id_doc; ?> ;
		var flgopt = <?php echo "'" . $flgopt . "'" ; ?> ;	
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                var skidka= document.frm_searh.skidka_doc.value;
                document.location = "doc_chek.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo 
					+ "&skidka=" + skidka + "&flgopt=" + flgopt ;
            }

            //**********************baba-jaga@i.ua**********************
            //нажата кнопка отмена во вводе штрих кода
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_chek.php?id_doc=" + iddoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //открываем всплывающее окошко для редактирования скидки
            function show_bar(event, idstr, oldskidka, tipedit) {

                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("win");

                obj.style.top = MouseY - 40 + 'px' ;
                obj.style.left = MouseX -160 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_edit').value     = oldskidka;
                document.getElementById('_idstr').value    = idstr;
                document.getElementById('_tipedit').value  = tipedit;
                

                document.getElementById('_edit').focus();


            }

            //**********************baba-jaga@i.ua**********************
            //просто закрываем окно редактирования скидки
            function hide_bar() {
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("winpometka").style.visibility="hidden";
            }

            //*********************baba-jaga@i.ua**********************
            //просто закрываем окно редактирования примечания
            function show_editpometka(event,txtpometka){
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("winpometka");

                obj.style.width =  '600px' ;
                obj.style.top = MouseY -60 + 'px' ;
                obj.style.left = MouseX +20 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_editpometka').style.width =  '500px' ;
                document.getElementById('_editpometka').value  = txtpometka ;
                document.getElementById('_editpometka').focus();
            }

            //*********************baba-jaga@i.ua**********************
            //записываем пометку и закрываем окно
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                document.getElementById('h_kod').focus();
                document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_chek.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
            }

            //**********************baba-jaga@i.ua**********************
            //кнопка удаления строки
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }

            //**********************baba-jaga@i.ua**********************
            //внесли сумму в кассу
            function vkassu(){
               var sumvkassu = document.frm_searh.sum_vkassu.value ;
               var sumdoc    = document.frm_searh.sum_vsego.value ;
               var zd = sumvkassu - sumdoc;
                   zd = zd.toFixed(2);
              document.frm_searh.sum_zdacha.value = zd;

            }

            //**********************baba-jaga@i.ua**********************
            //продали товар закрываем док с пометкой продано
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                var sum_v_kassu = document.frm_searh.sum_vkassu.value ;

                //sel_oplata

               var f = document.getElementById("sel_oplata");
               var oplata = f.options[f.selectedIndex].value;

               if(oplata == ''){ alert('Не выбрана форма оплаты'); return; }

                document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=ok&sum_v_kassu="+sum_v_kassu + "&oplata="+oplata ;

            }

            //**********************baba-jaga@i.ua**********************
            // отмена продажи закрываем док с пометкой удален
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";

            }

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                var h_kod =   <?php echo '"' . $h_kod . '"'; ?>;
                var play  =   <?php echo '"' . $play . '"'; ?> ;
                var closedoc =   <?php echo '"' . $closedoc . '"'; ?>;
                //alert('=='+closedoc);
                if(closedoc != '') window.close();

                document.frm_searh.h_kod.value =  h_kod  ;
                document.frm_searh.kvo.value  = <?php echo '"' . $kvo . '"'; ?>;

                if( h_kod == '' ) document.getElementById('h_kod').focus();
                else {
                    document.getElementById('kvo').focus() ;
                    document.getElementById('kvo').select() ;

                }
                if(play == 'play')  plays();

               // hide_bar(); // не показывать вспл окно
                //alert('c:  '+ document.referrer); // с какой сраницы пришел

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
            // по идее дзинькает при нажатии
            function plays() {
              var snd = new Audio("images/ok.wav");

               snd.preload = "auto";

                 snd.load();
                 snd.play();

            }

       //**********************baba-jaga@i.ua**********************
      // печать чека
      function prn(){
         var iddoc   = <?php echo $id_doc; ?> ;
         var tip_prn = <?php echo '"' . $tip_prn . '"' ; ?> ;     
               // для лазерного принтера
         if(tip_prn == 'Laser'){ 
                var flgopt = <?php echo "'" . $flgopt . "'"; ?> ;
                if(flgopt=='опт'){
                   document.location = "doc_chek.php?id_doc=" + iddoc + "&prn=yes"+ "&opt=yes";
                }else{
                   document.location = "doc_chek.php?id_doc=" + iddoc + "&prn=yes"+ "&opt=no";
                }
            }else{  // для UNS-tp51    
                document.location = "doc_chek.php?id_doc=" + iddoc + "&prn=yes";
            }  
      }


        </script>
    </head>
    <body onload="javascript:on_load()" >

        <!-- таблица делящая страницу -->
        <table cellspacing='3' border='0' style=" width: 100%;   " >
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
                                <td> <h3 style="font-size: 1.3em; text-align: left " > <?php echo $nm_firm; ?>  </h3> </td>
                                <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" > <?php echo $nm_doc; ?>   </h3> </td>
                                <td colspan="3" > <h3 style="font-size: 1.1em; text-align: right " > <?php echo $avtor; ?>   </h3> </td>
                            </tr>
                            <tr> <!-- вторая строка формы -->
                                <td colspan="2" > <h4> <?php echo $nm_klient; ?>  </h4> </td>
                                <td>
                                    
                                    <?php echo $f_oplata ; ?>
                                </td>

                                <td> <h4> ВСЕГО: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" style="width: 75px" value= <?php echo '"' . sumdoc($id_doc) . '"'; ?>  > </td>
                                <td> <a href="#" onclick="javascript:prodano()" > <img src="images/btnOk.jpg" border=0> </a> </td>
                            </tr>
                            <tr> <!-- третья строка формы ИНФО О Товаре -->
                                <td colspan="2" >
                                    <h3 style="font-size: 1.1em; font-style: italic; font-weight: normal; " >
                                        <?php echo $info_tovar; ?>
                                    </h3>
                                </td>
                                <td> <h2 style="font-size: 1.2em; text-align: center " > <?php  view_tip_skidki() ; ?>  </h2> </td>
                                <td> <h4> В КАССУ: </h4> </td>
                                <td> <input type="text" name="sum_vkassu" id ="sum_vkassu" onchange="javascript:vkassu()"  style="width: 75px" value="0" > </td>
                                <td> <a href="#" onclick="javascript:prn()" > <img src="images/btnPrint.jpg" border=0> </a> </td>
                            </tr>
                            <tr> <!-- четвертая строка формы -->
                                <td colspan="2" >

                                    Код товара: <input type="text" name="h_kod" id="h_kod" style="width: 120px" maxlength="19"
                                                              tabindex="0" onchange="javascript:re_h_kod()" >
                                    &nbsp; &nbsp; &nbsp; к-во:
                                    <input type="text" name="kvo" id ="kvo" style="width: 35px" maxlength="13"
                                           tabindex="1" onchange="javascript:re_h_kod()" >


                                </td>

                                <td> <h4 style="float: left; /* Обтекание справа чтоб все одной строкой*/" >Скидка:</h4>
                                    <input type="text" name="skidka_doc" id ="skidka_doc" style="float: left; width: 45px" onchange="javascript:re_skidka()"   value= <?php echo '"' . $skidka_doc . '"'; ?> >
                                <h4> % </h4></td>

                                <td> <h4> СДАЧА: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_zdacha" id ="sum_zdacha" style="width: 75px" value="0" > </td>
                                <td> <a href="#" onclick="javascript:ne_prodano()" > <img src="images/btnDel.jpg" border=0> </a>  </td>

                            </tr>
                        </table>


                    </form>



                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- табличная часть документа -->
                        <table cellspacing='0' border='0' class="Design5" >
                            <col width="30px">
                            <col width="80px">
                            <col width="340px">
                            <col width="50px">
                            <col width="60px">
                            <col width="70px">
                            <col width="55px">
                            <col width="70px">



                            <thead>
                                <tr>
                                    <td>п/н</td>
                                    <th class="Odd"> Код</th>
                                    <th>             Наименование</th>
                                    <th  class="Odd">К-во</th>
                                    <td>             Цена</td>
                                    <th class="Odd">Сумма</th>
                                    <td>             Ск. %</td>
                                    <th  class="Odd">Итог</th>
                                    <th>&nbsp;</th>

                                </tr>
                            </thead>

                            <?php str_view() ; ?>


                        </table>
                    </div>

                    <div>
                        <br>
                        <a href="#" onclick="javascript:show_editpometka(event, <?php echo "'" . $pometka . "'" ; ?> )" > Примечание:</a>  <?php echo $pometka ; ?>

                    </div>

                </td> <!-- конец средней колонки основной таблицы -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- правая колонка основной таблицы -->
            </tr></table> <!--конец таблицы делящая страницу -->

            <!-- всплывающее окно для редактирования данных в таблице -->
            <div id=win class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Значение: <input type="text" name="_edit" id ="_edit" size="5" onchange="javascript:re_skidka_str()" > 
                <input type="hidden" id ="_idstr">
                <input type="hidden" id ="_tipedit">
            </div>

            <!-- всплывающее окно для редактирования примечания -->
            <div id=winpometka class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Примечание: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >

            </div>


    </body>
</html>
