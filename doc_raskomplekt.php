<?php
require("header.inc.php");
include("milib.inc");
global $db;

$id_doc = -1;
if (isset($_GET['id_doc'])) $id_doc = $_GET['id_doc'];

$my_const = new cls_my_const();
$kvo_def = 0;// $my_const->kvo_def;

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
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    if ($s_arr['name'] == 1) {


        $kvoprn = $_GET['kvoprn'];
        $kod1c  = $_GET['kod1c'];
        $idtov  = id_tovar('', $kod1c);
        $idstr = $kod1c;

        $txt_sql = 'TRUNCATE cenniki'; // очистим табл ценников
        $sql     = mysql_query($txt_sql, $db);


        $txt_sql = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `tip_cn`)
                VALUES ('" . $idtov . "', '" . $kvoprn . "', '0' , '0' );";
        $sql     = mysql_query($txt_sql, $db);
        require("prn_hkod.php");
        prnxml();
    }
    else {
        echo '!!! не установлен принтер штрих-кода';
    }
    //kod1c
}

// получим штр код первой строки чтоб правильно внести к-во при редактировании кво
$txt_sql = "SELECT `Tovar`.`Kod1C` FROM `DocTab`
        LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar`
        WHERE ((`DocTab`.`DocHd_id` ='$id_doc') AND (`DocTab`.`nomstr` =1))";
$sql     = mysql_query($txt_sql, $db);
$s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
if ($s_arr['Kod1C'] == NULL  ) { //
    $pervaja_stroka = 0;
}else {
    $pervaja_stroka = $s_arr['Kod1C'] ;
}


//}

//echo $txt_sql . '<br>='.$pervaja_stroka . '<br>='. $s_arr['nomstr']  ;

// если введен штрих код
if (isset($_GET['h_kod']) ) {

    $h_kod = $_GET['h_kod'];
    $kvo   = $_GET['kvo'];
    $txt_sql = "SELECT `Tovar`, `Price`,`Ostatok` FROM `Tovar` WHERE  `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
    if ($kvo == '' or $kvo == '0') {                    //штрих код без количества
        //сформируем инфо о товаре

        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Tovar'] == NULL) {
            $info_tovar = "Нет товара с кодом:" . $h_kod;
            $h_kod      = '';
        }else $info_tovar = $s_arr['Tovar'] . "  Цена:" . $s_arr['Price'] . "  Ост.:" . $s_arr['Ostatok'];


    }else {                                             // введено и количество запишем строку в БД
        //получим цену товара

        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Price'] == NULL) {
            $info_tovar = "Нет товара с кодом:" . $h_kod;
            $h_kod      = '';
        }else {
            //$id_doc = $_GET['id_doc'];
            $h_kod  = $_GET['h_kod'];
            $kvo    = $_GET['kvo'];

            if($pervaja_stroka==0){ // первая запись в документ
                $err = upd_tabdoc($id_doc , $h_kod , $s_arr['Price'] , $kvo , 0 , $s_arr['Price'] , 0 );
            }else{
                if($pervaja_stroka == $h_kod){ // добавление к-ва в первую запись
                    $err = upd_tabdoc($id_doc , $h_kod , $s_arr['Price'] , $kvo , 0 , $s_arr['Price'] , 0 );
                } else {
                       $err = upd_tabdoc($id_doc , $h_kod , $s_arr['Price'] , 0 , 0 , $s_arr['Price'] , $kvo );
                }

            }
            if($err != '') $info_tovar = $err;   
            //обнулим чтоб снова фокус на штрих коде
            $h_kod ='';  $kvo   = $kvo_def ;
        }

    }
}



//"doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
//перезапишем рекомендуемую цену одной строке и пересчитаем док
if (isset($_GET['newcena'])) {

    $newsk  = $_GET['newcena'];
    $id_str = $_GET['idstr'];
    //$id_doc = $_GET['id_doc'];
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

    $id_str = '';
    if (isset($_GET['idstr'])) $id_str = $_GET['idstr'];

    $pometka = $_GET['pometka'];
    //$id_doc = $_GET['id_doc'];



    if(trim($id_str) == ''){
        $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    }
        else {
        $txt_sql = " UPDATE `DocTab` SET `pometka` = '" . $pometka . "' WHERE `id` = '" . $id_str . "' ;";
    }


    $sql     = mysql_query($txt_sql, $db);

}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
// удаление позиции документа
if (isset($_GET['delstr'])) {

    $id_str = $_GET['delstr'];
    delstrdoc( $id_doc , $id_str ) ;

}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";
// закончили работу с документом, либо продано, либо отмена
$closedoc = '';
if (isset($_GET['prodano'])) {
    $prodano = $_GET['prodano'];
  //  $id_doc  = $_GET['id_doc'];

    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('Принят');
        }
        else {
            $idstatus = id_status('Удален');
        }

        $txt_sql  = "UPDATE `DocHd` SET `statusDoc` = '" . $idstatus . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql      = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
    $closedoc = 'close';
}

// если открываем форму нужного, уже со всеми записями документа
if (isset($_GET['id_doc'])) {
    //$id_doc = $_GET['id_doc'];
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
   //                                 <td>            Цена</td>
   //                                 <th  class="Odd">Цена <br> рекоменд. </th>
   //                                 <th>Пометка</th>
   //                                 <th>&nbsp;</th>

    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Cena2`, `DocTab`.`Kvo2`, `DocTab`.`pometka`, "
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`id`  ";

     //   color: #F93D00 ; /* красный  */
     //   color: #00f  /* синий  */

    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        $st = '';
        $razn =    $s_arr['Kvo'] - $s_arr['Kvo2']   ; //  принято -приход
        if($razn < 0){
            $st = ' style=" color: #00f ;"';
        }elseif($razn > 0) {
            $st = ' style=" color: #F93D00 ;"';
        }

        $prnkod = trim($s_arr['Kod']);
        if(strlen($prnkod) < 8 ){// печатаем только свои
            $prnkod = '<a href = "javascript:;"  onclick="javascript:prnhkod('. "'" . $s_arr['Kod1C'] . "'" .','.  "'".  $s_arr['Kvo2'] .  "'". ','.  "'" . $s_arr['Kvo'] . "'" .  ' )" > <img src="images/b_print.png" border=0>' . $s_arr['Kod'] .' </a>';
        }

        $kvo = $s_arr['Kvo'];
        if($kvo==0)$kvo='';

        $kvo2 = $s_arr['Kvo2'];
        if($kvo2==0)$kvo2='';

        echo '<tr  id="'.$s_arr['Kod'].'" >';
        echo '<td>'. $s_arr['nomstr'] .'</td>';
        echo '<th class="Odd">'.$prnkod.'</th>';
        echo '<th '. $st .' >'. $s_arr['Tovar'] .'</th>';
        echo '<td  class="Odd" '. $st .' >'. $kvo2 .'</td>';  //приход
        echo '<td '. $st .' >'. $kvo .'</td>';           // принято
  //      echo '<td class="Odd" '. $st .' >'. $razn .'</td>';

  //      echo '<td class="Odd">&nbsp</td>';

        // разница цен
        //$st = '';
        //$razn =    $s_arr['Cena'] - $s_arr['Cena2']   ; //
        //if($razn < 0){
        //    $st = ' style=" color: #F93D00 ;"';
        //}elseif($razn > 0) {
        //    $st = ' style=" color: #00f ;"';
        //}
        $pometka = '___';
        if(  trim($s_arr['pometka']) != '') $pometka = $s_arr['pometka'];
        echo '<td            '. $st .'>'. sprintf("%.2f", $s_arr['Cena2']) .'</td>';
        echo '<td class="Odd"  ><a href = "javascript:;" '. $st .' onclick="javascript:show_bar(event,'. $s_arr['idstr'] .','. sprintf("%.2f", $s_arr['Cena']) .' )" > '. sprintf("%.2f", $s_arr['Cena']) .' </a>   </td>';
        //echo '<td            >'. $s_arr['pometka'] .'</td>';
        echo '<td >            <a href = "javascript:;"           onclick="javascript:show_editpometka(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['pometka'] . "'" . ' )" > '. $pometka .' </a>   </td>';

        echo '<th><a href = "javascript:;"  onclick="javascript:delstr('. $s_arr['idstr'] .' )" > <img src="images/b_drop.png" border=0> </a> </th>';
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

        <script type="text/javascript">
            //для прокрутки к нужному месту страницы
            var idstr_ =  <?php echo '"'. $idstr . '"' ; ?> ;

            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){
                return true ;
            }

            //**********************baba-jaga@i.ua**********************
            //печать этикеток штрих-кода
            function prnhkod( kod1c,kvoprihod,kvo ){
                if(kvoprihod<kvo)kvoprihod=kvo;
                if(kvoprihod<1) return;

                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  ;

            }

            //**********************baba-jaga@i.ua**********************
            //откр форму печати ценников
            function prn(){
                var iddoc = <?php echo $id_doc; ?> ;
                window.open("doc_cennik.php?iddoc=" +  iddoc + "&add=1" );
            }

            //**********************baba-jaga@i.ua**********************
            //смена штрих кода в поле
            function re_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo  ;
            }

            //**********************baba-jaga@i.ua**********************
            //нажата кнопка отмена во вводе штрих кода
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc ;
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
            }

            //*********************baba-jaga@i.ua**********************
            // окно редактирования примечания
            function show_editpometka(event, idstr ,txtpometka){
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("winpometka");

                var pox = -620;
                if(idstr == '' ) pox=20;

                obj.style.width =  '600px' ;
                obj.style.top = MouseY -60 + 'px' ;
                obj.style.left = MouseX + pox + 'px' ;
                obj.style.visibility = "visible";

                document.getElementById('_idstrp').value  = idstr;

                document.getElementById('_editpometka').style.width =  '500px' ;
                document.getElementById('_editpometka').value  = txtpometka ;
                document.getElementById('_editpometka').focus();
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

                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&idstr=" + idstr + "&newcena=" + newskidka ;
            }

            //*********************baba-jaga@i.ua**********************
            //записываем пометку и закрываем окно
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                var idstr     = document.getElementById('_idstrp').value;
                //document.getElementById('h_kod').focus();
                //document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );
                if(idstr == '') {
                   document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
                }else{
                   document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&idstr=" + idstr + "&pometka=" + newpometka  ;
                }

            }

            //**********************baba-jaga@i.ua**********************
            //кнопка удаления строки
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }


            //**********************baba-jaga@i.ua**********************
            //продали товар закрываем док с пометкой продано
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&prodano=ok";

            }

            //**********************baba-jaga@i.ua**********************
            // отмена продажи закрываем док с пометкой удален
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_raskomplekt.php?id_doc=" + iddoc + "&prodano=no";

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
                                    <td> <h4>&nbsp;</h4> </td>
                                    <td> &nbsp;</td>
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
                            <col width="35px">
                            <col width="170px">
                            <col width="260px">  <!-- наименование -->
                            <col width="60px">
                            <col width="60px">
                            <col width="60px">
                            <col width="60px"> <!-- цена -->
                            <col width="85px"><!-- pometka -->


                            <thead>
                                <tr>
                                    <td>п/н</td>
                                    <th class="Odd">  Код</th>
                                    <th>              Наименование</th>
                                    <th  class="Odd"> К-во <br> <span style="font-size: 19px;" > + </span>  </th>
                                    <th>              К-во <br> <span style="font-size: 19px;" > - </span> </th>
                                    <td class="Odd" > Цена</td>
                                    <th             > Цена <br> <span style="font-size: 10px;" > рекоменд. </span>  </th>
                                    <th class="Odd" > Пометка</th>
                                    <th>&nbsp;</th>

                                </tr>
                            </thead>

                            <?php  str_view() ; ?>


                        </table>
                    </div>

                    <div>
                        <br>
                        <a href="#" onclick="javascript:show_editpometka(event, '' ,<?php echo "'" . $pometka . "'" ; ?> )" > Примечание:</a>  <?php echo $pometka ; ?>
                        <br>
                        <br>
                    </div>
                    <div>
                        <h3 style="text-align: center" >Внимание !</h3>
                        <p> &#9675; Первым набираем код и количество товара, который расформировывем.</p>
                        <p> &#9675; Далее набираем коды и количество товара, которые должны получить в результате
                            расформирования. </p>
                        <p> &#9675; В одном документе можно расформировать только один товар, <br>
                            результатом расформирования может быть несколько различных товаров. </p>

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
                Примечание: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >
                <input type="hidden" id ="_idstrp">
            </div>


    </body>

     <script type="text/javascript">
        //Чтобы прокрутить страницу при помощи JavaScript, её DOM должен быть полностью загружен.
           var el = document.getElementById(idstr_);
           el.scrollIntoView(true);
    </script>

</html>
