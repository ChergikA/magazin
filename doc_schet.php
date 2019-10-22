<?php
require("header.inc.php");
include("milib.inc");
//include ("print.inc");
global $db;

$id_doc = -1;
$dwnl = '';
$my_const = new cls_my_const();
$kvo_def = $my_const->kvo_def;

$h_kod =''; $info_tovar=""; $kvo   = $kvo_def ; $play='';

 if (isset($_GET['id_doc'])) { // 
    $id_doc = $_GET['id_doc'];
    if (write_doc($id_doc)) {
        $info_tovar = "<a style=' color: #1864fc; text-decoration: underline; font-size: 11px; ' 
                        href='javascript: podbor_tovar();'> Подобрать из прайс-листа </a>";
    }
}

// если введен штрих код
if (isset($_GET['h_kod']) ) {

    $h_kod = $_GET['h_kod'];
    $kvo   = $_GET['kvo'];
    if ($kvo == '' or $kvo == '0') {                    //штрих код без количества
        //сформируем инфо о товаре
        $txt_sql = "SELECT `Tovar`, `Price`,`Ostatok` FROM `Tovar` WHERE `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Tovar'] == NULL) {
            $info_tovar = "Нет товара с кодом:" . $h_kod;
            $h_kod      = '';
        }else $info_tovar = $s_arr['Tovar'] . "  Цена:" . $s_arr['Price'] . "  Ост.:" . $s_arr['Ostatok'];


    }else {                                             // введено и количество запишем строку в БД
        //получим цену товара
        $txt_sql = "SELECT `Price` FROM `Tovar` WHERE `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Price'] == NULL) {
            $info_tovar = "Нет товара с кодом:" . $h_kod;
            $h_kod      = '';
        }else {
            $id_doc = $_GET['id_doc'];
            $h_kod  = $_GET['h_kod'];
            $kvo    = $_GET['kvo'];
            $skidka = $_GET['skidka'];
            $err = upd_tabdoc($id_doc , $h_kod , $s_arr['Price'] , $kvo , $skidka, 0 , 0 );
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

        $txt_sql = "UPDATE `DocHd` SET `SkidkaProcent` = '" . $newsk . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql     = mysql_query($txt_sql, $db);

        $txt_sql = " UPDATE `DocTab` SET `Skidka` = '" . $newsk . "' WHERE `DocHd_id` = '" . $id_doc . "' ;";
        $sql     = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
}

//"doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
//перезапишем скидку в одной строке и пересчитаем док
if (isset($_GET['idstr'])) {

    $newsk  = $_GET['skidka'];
    $id_str = $_GET['idstr'];
    $id_doc = $_GET['id_doc'];
    //$txt_sql =  "UPDATE `DocHd` SET `SkidkaProcent` = '". $newsk ."' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    //$sql     = mysql_query($txt_sql, $db);
    if (write_doc($id_doc)) {
        $txt_sql = " UPDATE `DocTab` SET `Skidka` = '" . $newsk . "' WHERE `id` = '" . $id_str . "' ;";
        $sql     = mysql_query($txt_sql, $db);

        savesumdoc($id_doc);
    }

}

//Перезапишем примечание документа при его изменении
if (isset($_GET['pometka'])) {
    $pometka = $_GET['pometka'];
    $id_doc = $_GET['id_doc'];
    $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    $sql     = mysql_query($txt_sql, $db);

}

//Перезапишем номер счета
if (isset($_GET['newnomdoc'])) {
    $newnomdoc = $_GET['newnomdoc'];
    $id_doc = $_GET['id_doc'];
    $txt_sql = "UPDATE `DocHd` SET `nomDoc` = '" . $newnomdoc . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    $sql     = mysql_query($txt_sql, $db);

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
    $id_doc  = $_GET['id_doc'];
   // $sum_v_kassu = 0;
   // $oplata      = 0;
    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('Продан');
     //       $sum_v_kassu = $_GET['sum_v_kassu'];
     //       $oplata      = $_GET['oplata'];//

        }
        else {
            $idstatus = id_status('Удален');
        }

        $txt_sql  = "UPDATE `DocHd` SET `statusDoc`   = '" . $idstatus . "'
                                    WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql      = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }


    $closedoc = 'close';
}

// если открываем форму нужного, уже со всеми записями документа
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    // получим данные для шапки
    $txt_sql = "SELECT `firms`.`name_firm`, `firms`.`from_chek`,  `Klient`.`name_klient`,`Klient`.`name_full`,`DocHd`.`id`,`DocHd`.`nomDoc`,"
    .         " `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`sum_v_kassu`, `StatusDoc`.`nameStatus`,"
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

    $no_nds = $s_arr['from_chek'];

    $nm_klient = $s_arr['name_klient'];
    if(trim($s_arr['name_full']) != '' ) $nm_klient =$s_arr['name_full'];
    $sumoplata = $s_arr['sum_v_kassu'];
    $skidka_doc =  $s_arr['SkidkaProcent'];
    $nomerdoc = $s_arr['nomDoc'];
    $nm_doc =  $s_arr['name_doc'] . ' №_' . $s_arr['nomDoc'] . ' от:' . datesql_to_str( $s_arr['DataDoc']) ; //Товарный чек №_32 от 28.11.2012
    $avtor  =  "автор: " . $s_arr['full_name'];
    $pometka = addslashes( $s_arr['Pometka']) ; // экранируем символы

    $writedoc = write_doc($id_doc);
    if( ! $writedoc ) $nm_doc = $nm_doc . ' (ПРОСМОТР)';
    
    
    $from_nkl = '';
    if($no_nds != 0){
       $from_nkl =' <li><a href="#" onclick="javascript:prn(\'prn_nkl\')"  >Напечатать накладную</a></li>
                   <li><a href="#" onclick="javascript:prn(\'save_nkl\')" >Записать Накладную</a></li>';
    }                                           

    if (isset($_GET['prn'])) {
        $prn = $_GET['prn']; //prn_chet save_chet prn_nkl save_nkl
        prn($id_doc, $prn, $no_nds, $nomerdoc  );
    }


}

function prn($id_doc, $prn, $no_nds, $nomdoc) {
    global $dwnl;
//$prn  = prn_chet save_chet prn_nkl save_nkl
    if ($prn == 'prn_chet') {
        if($no_nds == 0){
            include("prn/prn_schetnds.php");
        }  else {
            include("prn/prn_schet.php");
        }
        prnxml($id_doc,'');
    } elseif ($prn == 'prn_nkl') {
       include("prn/prn_nakladna.php"); 
       prnxml($id_doc,'');
    }elseif ($prn == 'save_nkl') {
       include("prn/prn_nakladna.php"); 
       prnxml($id_doc,'nakladna');
       $dwnl='nakl';
    }elseif ($prn == 'save_chet') {
         if($no_nds == 0){
            include("prn/prn_schetnds.php");
        }  else {
            include("prn/prn_schet.php");
        }
        prnxml($id_doc,'schet');
        $dwnl='schet';
    }
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
    . "ORDER BY `DocTab`.`id` DESC ";



    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {

        $st =  'style="text-decoration: none; "';
        if($s_arr['Kvo']==0)$st =  'style="text-decoration: line-through; "';

        echo '<tr>';
        echo '<td>'. $s_arr['nomstr'] .'</td>';
        echo '<th class="Odd">'. $s_arr['Kod1C'] .'</th>';
        echo '<th ' . $st . ' >'. $s_arr['Tovar'] .'</th>';
        echo '<th  class="Odd">'. $s_arr['Kvo'] .'</th>';
        echo '<td>'. $s_arr['Cena'] .'</td>';
        $sum = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo']) ; //sprintf("%.2f", $sumdoc);
        echo '<th class="Odd">'. $sum .'</th>'; //event, idstr, oldskidka
        echo '<td><a href="#" onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. $s_arr['Skidka'] .' )" > '. $s_arr['Skidka'] .' </a>   </td>';
        $it = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo'] * ( 1 - $s_arr['Skidka']/100 ) ) ;
        echo '<th  class="Odd">'.$it.'</th>';
        echo '<th><a href="#"  onclick="javascript:delstr('. $s_arr['idstr'] .' )" > <img src="images/b_drop.png" border=0> </a> </th>';
        echo '</tr>';


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

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">


            // низспадающий список для печати на jQuery
            $(document).ready(function () {
                
                $("ul.menu_body li:even").addClass("alt");
                
                $('img.menu_head').click(function () {
                   $('ul.menu_body').slideToggle('medium');
                });
                    
                $('ul.menu_body li a').mouseover(function () {
                    $(this).animate({ fontSize: "20px", paddingLeft: "20px" }, 50 );
                });
                
                $('ul.menu_body li a').mouseout(function () {
                    $(this).animate({ fontSize: "18px", paddingLeft: "10px" }, 50 );
                });
            });

            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){
                return true ;
            }

            //**********************baba-jaga@i.ua**********************
            //смена скидки в документе
            function re_skidka(){
                //alert('reskidka');

                var iddoc = <?php echo $id_doc; ?> ;
                var newskidka = document.frm_searh.skidka_doc.value;

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&newskidka=" + newskidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //смена скидки в строке
            function re_skidka_str(){
                ///alert('reskidka_str');
                var iddoc = <?php echo $id_doc; ?> ;
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden"

                var newskidka = document.getElementById('_edit').value ;
                var idstr     = document.getElementById('_idstr').value;
                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //смена штрих кода в поле
            function re_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                var skidka= document.frm_searh.skidka_doc.value;
                document.location = "doc_schet.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo + "&skidka=" + skidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //нажата кнопка отмена во вводе штрих кода
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_schet.php?id_doc=" + iddoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //открываем всплывающее окошко для редактирования скидки
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
            //просто закрываем окно редактирования скидки
            function hide_bar() {
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("winpometka").style.visibility="hidden";
                document.getElementById("winnomdoc").style.visibility="hidden";
            }

            //*********************baba-jaga@i.ua**********************
            //окно редактирования примечания
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
            //окно редактирования номера
            function show_editnomdoc(event,txtpometka){
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("winnomdoc");

                obj.style.width =  '160px' ;
                obj.style.top = MouseY -2 + 'px' ;
                obj.style.left = MouseX +20 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_editnomdoc').style.width =  '60px' ;
                document.getElementById('_editnomdoc').value  = txtpometka ;
                document.getElementById('_editnomdoc').focus();
            }



            //*********************baba-jaga@i.ua**********************
            //записываем пометку и закрываем окно
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                document.getElementById('h_kod').focus();
                document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
            }

            //*********************baba-jaga@i.ua**********************
            //изменяем номер док-та и закрываем окно
            function re_nomdoc(){
                var iddoc      = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;
                var writedoc   = <?php echo "'". $writedoc . "'" ; ?> ;

                document.getElementById('h_kod').focus();
                document.getElementById("winnomdoc").style.visibility="hidden";

                if(writedoc != '1') {
                    alert("Нелзя изменить номер этого документа. Отгружено.");
                    return;
                }
                if(sum_oplata > 0){
                    alert("Есть оплата по счету. Нелзя изменить номер этого документа");
                    return;
                }

                var newnomdoc = document.getElementById('_editnomdoc').value ;

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&newnomdoc=" + newnomdoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //кнопка удаления строки
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_schet.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }


            //**********************baba-jaga@i.ua**********************
            //продали товар закрываем док с пометкой продано
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;

                var sumdoc = document.getElementById("sum_vsego").value ;

               if(sumdoc == 0){ alert('Счет пустой.'); return; }
               if(sumdoc > sum_oplata){ alert('Сумма документа превышает сумму оплаты.'); return; }

                document.location = "doc_schet.php?id_doc=" + iddoc + "&prodano=ok" ;

            }

            //**********************baba-jaga@i.ua**********************
            // отмена продажи закрываем док с пометкой удален
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;

                if(sum_oplata > 0){ alert('Счет оплачен, его нельзя удалить.'); return; }

                if(confirm('Удалить счет?')) {
                    document.location = "doc_schet.php?id_doc=" + iddoc + "&prodano=no";
                }
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
                
                var dwnl = <?php echo '"' . $dwnl . '"'; ?>;
                if(dwnl=='nakl'){
                    var link = document.getElementById("dwnl_nakl");
                    link.click();
                }
                 if(dwnl=='schet'){
                    var link = document.getElementById("dwnl_schet");
                    link.click();
                }
 
                    
                


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
            // печать счетов накладных
            function prn(chto){
                var iddoc = <?php echo '"' . $id_doc . '"' ; ?> ;
//                var nonds   = <?php //echo $no_nds; ?> ;
//                if(nonds==0){
//                    window.open("prn_schet_nds.php?iddoc=" + iddoc );
//                }else{
//                    window.open("prn_schet.php?iddoc=" + iddoc);
//                    window.open("prn_nakladna.php?iddoc=" + iddoc);
//                    //window.open("prn_nakladna.php?iddoc=" + iddoc , " target='_blank' ");
//                }
                document.location = "doc_schet.php?id_doc=" + iddoc + "&prn="+chto ;

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
                                <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" >
                                    <a href="#" onclick="javascript:show_editnomdoc(event, <?php echo "'" . $nomerdoc . "'" ; ?> )" >   <?php echo $nm_doc; ?>  </a>
                                    </h3>
                                </td>
                                <td colspan="3" > <h3 style="font-size: 1.1em; text-align: right " > <?php echo $avtor; ?>   </h3> </td>
                            </tr>
                            <tr> <!-- вторая строка формы -->
                                <td colspan="3" > <h4> <?php echo $nm_klient; ?>  </h4> </td>

                                <td> <h4> ОПЛАТА: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_oplata" id ="sum_oplata" size="5" value= <?php echo '"' . $sumoplata . '"'; ?>  > </td>
                                <td> <a href="#" onclick="javascript:prodano()" > <img src="images/btnOk.jpg" border=0> </a> </td>
                            </tr>
                            <tr> <!-- третья строка формы ИНФО О Товаре -->
                                <td colspan="3" >
                                    <h3 style="font-size: 1.2em; font-style: italic; font-weight: normal; " >
                                        <?php echo $info_tovar; ?>
                                    </h3>
                                </td>
                                <td colspan="2"> <h4>&nbsp;</h4> </td>

                                <td> 
                                    <!--<a href="#" onclick="javascript:prn()" > <img src="images/btnPrint.jpg" border=0> </a>--> 
                                   <div class="container" style="position:absolute ;  top: 65px;" >
                                            <img src="images/btnPrint.jpg" class="menu_head" />
                                            <ul class="menu_body">
                                                <li><a href="#" onclick="javascript:prn('prn_chet')" >Напечатать счет</a></li>
                                                <li><a href="#" onclick="javascript:prn('save_chet')">Записать счет</a></li>
                                                <?php echo $from_nkl ; ?> 

                                            </ul> 
                                            <a id="dwnl_nakl"  href='data/nakladna.fods' download></a>
                                            <a id="dwnl_schet" href='data/schet.fods'    download></a>

                                            
                                    </div>
                                </td>
                            </tr>
                            <tr> <!-- четвертая строка формы -->
                                <td colspan="2" >

                                    Код товара: &nbsp; <input type="text" name="h_kod" id="h_kod" size="11" maxlength="19"
                                                              tabindex="0" onchange="javascript:re_h_kod()" >
                                    &nbsp; &nbsp; &nbsp; к-во:
                                    <input type="text" name="kvo" id ="kvo"  size="3" maxlength="13"
                                           tabindex="1" onchange="javascript:re_h_kod()" >


                                </td>

                                <td> <h4 style="float: left; /* Обтекание справа чтоб все одной строкой*/" > Скидка: </h4>
                                    <input type="text" name="skidka_doc" id ="skidka_doc" size="8" onchange="javascript:re_skidka()" style="float: left;"  value= <?php echo '"' . $skidka_doc . '"'; ?> >
                                <h4> % </h4></td>

                                <td> <h4> ВСЕГО: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php echo '"' . sumdoc($id_doc) . '"'; ?>  > </td>
                                <td> <a href="#" onclick="javascript:ne_prodano()" > <img src="images/btnClear.jpg" border=0> </a>  </td>

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
                Скидка: <input type="text" name="_edit" id ="_edit" size="5" onchange="javascript:re_skidka_str()" > %
                <input type="hidden" id ="_idstr">
            </div>

            <!-- всплывающее окно для редактирования примечания -->
            <div id=winpometka class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Примечание: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >

            </div>

                        <!-- редактируем номер док-та -->
            <div id=winnomdoc class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Номер счета: <input type="text" name="_editnomdoc" id ="_editnomdoc"  onchange="javascript:re_nomdoc()" >

            </div>


    </body>
</html>
