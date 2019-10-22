<?php
require("header.inc.php");
include("milib.inc");
include("print.inc");
global $db;
global $cnst ;
$cnst = new cls_my_const();
$edit_hkod ="";
$copy='';

$alert_err = ''; // нужна чтобы в яваскрипт крикнуть ошибку при неправильном вводе данных

$id_tov = '';

$id_group_tov='';
$nm_group_tov = '';
$hkod = '';
$newname_tov=''; // полное имя товара
$newname = '';   // краткое  ( до разделителя)
$cena = '';
$sel_ed = 'шт.';
$art = '';
$sostav = '';
$sel_strana = 'Китай' ; // :) по умолчанию
$txt_charakt = '';

$brend  = '';
$vid    = '';
$color  = '';
$razm1  = '';
$razm2  = '';
$rost  = '';

// для группы товара
function add_kod1c($id) {
    global $db;
    global $cnst;
    $kod1c = $id;
    while (strlen($kod1c) < 6) {
      $kod1c = '0' . $kod1c;
     }
    $kod1c = $cnst->kod_sklad . $kod1c;
    echo "$kod1c";     
    $txt_sql = "UPDATE `group_t` SET `Kod1C`= '$kod1c'  WHERE `id` = '$id'";
    echo "sql = $txt_sql";
    $sql = mysql_query($txt_sql, $db);
}

// для товара
function add_kod1c_tovar($id) {
    global $db;
    global $cnst;
    $kod1c = $id;
    while (strlen($kod1c) < 6) {
      $kod1c = '0' . $kod1c;
     }
    $kod1c = $cnst->kod_sklad . $kod1c;
    echo "$kod1c";     
    $txt_sql = "UPDATE `Tovar` SET `Kod1C`= '$kod1c'  WHERE `id_tovar` = '$id'";
   // echo "sql = $txt_sql";
    $sql = mysql_query($txt_sql, $db);
}


if (isset($_GET['id_tov']))  $id_tov = $_GET['id_tov'];

if (isset($_GET['new']))    $flgnew = $_GET['new'];

if ($flgnew == 'save') { // запись  карточки читаем данные формы заполнения

    $id_group_tov = $_GET['id_group_tov'];
    $txt_sql      = "SELECT `nm_group` FROM `group_t` WHERE `id` = '$id_group_tov' ";
    $sql2         = mysql_query($txt_sql, $db);
    $s_arr2       = mysql_fetch_array($sql2, MYSQL_BOTH);
    $nm_group_tov = $s_arr2['nm_group'];

    $hkod        = $_GET['hkod'];
    $newname_tov = $_GET['newname_tov'];
    $pos         = strpos($newname_tov, " &#9675;");
    $newname     = substr($newname_tov, 0, $pos);
    $cena        = $_GET['cena'];
    $sel_ed      = $_GET['sel_ed'];
    $art         = $_GET['art'];
    $sel_strana  = $_GET['sel_strana'];
    $sostav      = $_GET['sostav'];
    $txt_charakt = $_GET['txt_charakt'];
    $txt_charakt = str_replace(',', '', $txt_charakt); // без запятых

    if ($id_tov == '') { // новая карточка
        if (trim($hkod) == '') {
            // сформируем штрих код, java не пропустит, если есть штрих код

            $txt_sql = "SELECT ifnull( max( `id_tovar`) ,0) + 1 as maxId FROM `Tovar` ";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $id_t    = $s_arr['maxId'];
            $lenid   = strlen($id_t);
            $nn      = ''; //date('Y');
            for ($index = 0 + $lenid; $index < 5; $index++) {
                $nn   = $nn . "0";
            }
            $hkod = '7' . $nn . $id_t;
        }

        $txt_sql = "INSERT INTO `Tovar` (`id_group`      , `Kod1C`     , `Kod`,
                                             `Tovar`         , `Price`     , `PriceOpt`, `NDS`,
                                             `ed_izm`        , `v_upakovke`, `Sostav`  , `strana`,
                                             `redaktor`      , `magazin`   , `flg_edit` , `charakteristiks`)
                                     VALUES ('$id_group_tov' , '$art'      , '$hkod'    ,
                                              '$newname_tov' , '$cena'     , '0.00'     ,'1'   ,
                                              '$sel_ed'       , NULL        , '$sostav'      , '$sel_strana',
                                              '" . name_user() . "', NULL        ,'1'       , '$txt_charakt'  );";


        //echo $txt_sql;
        $sql = mysql_query($txt_sql, $db);
        if ($sql) {
            $id_tov = mysql_insert_id(); // возвращает id только что записаннной строки
            add_kod1c_tovar($id_tov);
            $flgnew = 'view';
        }
        else {
            echo '<h1>Карточка не записана. ОШИБКА</h1>';
            echo '<br>';
            if ((substr(mysql_error(), 0,9))=='Duplicate') {
                $err_hkod= (substr(mysql_error(), 17,8));
                echo "<h2> Штрихкод  $err_hkod уже есть! </h2>";
                $edit_hkod='';
            }
            $id_tov = '';
            $flgnew = 'edit';
        }

    }
    else { //отредактированная карточка
        //Если штрих код пуст
        if (trim($hkod) == '') {
            //Если стоит галочка Нет Штрих Кода
            if (isset($_GET['not_hk'])){
            // берем id товара ( нужно для генерации штрих-кода)
            if (isset($_GET['id_tov']))  $id_tov = $_GET['id_tov'];
            // сформируем штрих код, java не пропустит, если есть штрих код
            $id_t=$id_tov;
            $lenid   = strlen($id_t);
            $nn      = ''; //date('Y');
            for ($index = 0 + $lenid; $index < 7; $index++) {
                $nn   = $nn . "0";
            }
            $hkod = '7' . $nn . $id_t;
            // штрих код = 7 + нoли для длинны + id товара. Должен быть уникальным, раз id уникально.
            }
            
           
        }
               $txt_sql  = "UPDATE `Tovar` SET `id_group` = '$id_group_tov',
                                                `Tovar` = '$newname_tov',
                                                `Kod` = '$hkod',
                                                `Price` = '$cena',
                                                `ed_izm` = '$sel_ed',
                                                `Sostav` = '$sostav',
                                                `strana` = '$sel_strana',
                                                `flg_edit` = '1',    
                                                `charakteristiks` = '$txt_charakt',
                                                `redaktor` = '" . name_user() . "'
                                                WHERE `Tovar`.`id_tovar` = '$id_tov';";


        $sql = mysql_query($txt_sql, $db);
        if ($sql) {
            $flgnew = 'view';
        }
        else {
            echo '<h1>Не верно введены данные. ОШИБКА.</h1>';
            echo '<br>';
            if ((substr(mysql_error(), 0,9))=='Duplicate') {
                $err_hkod= (substr(mysql_error(), 17,8));
                echo "<h2> Штрихкод  $err_hkod уже есть! </h2>";
                $edit_hkod='';
            }
            $flgnew = 'edit';
        }

    }




}




// открытие и заполнение
// либо новый либо едит, есть ид товара или нет.
// если есть то едит, инае новый


if ($id_tov == '') { // открыта новая
    $flgnew = 'new';
} // конец заполнения новой карточки
else { // открыта для просмотра или редактирования
    $txt_sql      = " SELECT `id_group`, `Kod1C`, `Kod`, `Tovar`, `Price`, `ed_izm`,
                              `Sostav`, `strana`, `charakteristiks`
                     FROM `Tovar` WHERE `id_tovar` = '" . $id_tov . "'";
    $sql          = mysql_query($txt_sql, $db);
    $s_arr        = mysql_fetch_array($sql, MYSQL_BOTH);
    $id_group_tov = $s_arr['id_group'];

    $txt_sql      = "SELECT `nm_group` FROM `group_t` WHERE `id` = '$id_group_tov' ";
    $sql2          = mysql_query($txt_sql, $db);
    $s_arr2        = mysql_fetch_array($sql2, MYSQL_BOTH);
    $nm_group_tov = $s_arr2['nm_group'];

    $hkod        = $s_arr['Kod'];
    $newname_tov = $s_arr['Tovar'];
    // " &#9675;"
    $pos         = strpos($newname_tov, " &#9675;");
    $newname     = trim( substr($newname_tov, 0, $pos)) ; // . " &#9675;" ;
    $newname     = addslashes($newname);
    $newname_tov = addslashes($newname_tov);

    $cena        = $s_arr['Price'];
    $sel_ed      = $s_arr['ed_izm'];
    if($sel_ed==='' or $sel_ed === NULL )$sel_ed='шт.';
    $art         = $s_arr['Kod1C'];
    $sostav      = $s_arr['Sostav'];
    $sel_strana  = $s_arr['strana'];
    if($sel_strana==='' or $sel_strana === NULL )$sel_strana='Украина';
    $txt_charakt = $s_arr['charakteristiks']; // ;;белая,;;M,;170,;шт.;Индия;
    //$txt_charakt = str_replace(',','' ,$txt_charakt); // без запятых
    if($txt_charakt==='')$txt_charakt=NULL;
    if($txt_charakt !== NULL){
        $m_charakt   = explode(';', $txt_charakt); // массив с характеристиками
        $brend  = $m_charakt[0] ;
        $vid    = $m_charakt[1] ;
        $color  = $m_charakt[2] ;
        $razm1  = $m_charakt[3] ;
        $razm2  = $m_charakt[4] ;
        $rost   = $m_charakt[5] ;
    }


} // конец заполнения карточки для редактирования

if($flgnew == 'copy'){ // новая путем копирования
   $flgnew = 'new';
   $id_tov = '';
   $hkod   = '';
   $copy="yes";
}




$save_close = '';
if(isset($_GET['bt_ok'])) $save_close = $_GET['bt_ok'];

if (isset($_GET['prncennik'])) {
//prncennik=1&k_tov=" + ktov
    $kvo = $_GET['prncennik'];
    if (strlen($_GET['hkod']) > 3) {

        //echo 'печать ценник для :' . $_GET['k_tov'] ;
        $prn = new prn();
        for ($i = 0; $i < $kvo; $i++) {
            $prn->prn_cennik($_GET['hkod']);
        }
    }
}






$flgedit = '';
if($flgnew === 'view'){
    $flgedit =  "readonly = 'true'";
    $edit_hkod="readonly='true'";
} 

if ($flgnew== 'edit') {
   $edit_hkod ="";
}


//воод нового значения для доп справочников
if (isset($_GET['tip'])) {
    $tip   = $_GET['tip'];
    $newzn = $_GET['newzn'];

    if (strlen($newzn) < 1) {
        $alert_err = 'Не верный ввод значения';
    }
    else {

        if ($tip == 'group') {
         $txt_sql = "INSERT INTO `group_t` (`nm_group`) VALUES ( '" . $newzn . "');";
         $sql = mysql_query($txt_sql, $db);
         $id_from_mysql = mysql_insert_id($db); // возвращает id только что записаннной строки
        // echo "id = $id_from_mysql  ";

         add_kod1c($id_from_mysql);
        } else {
            //  'brend'
            //  'vid'
            //  'color'
            //  'razm1'
            //  'razm2'
            //  'rost'
            $txt_sql = " INSERT INTO `tovar_hr` ( `name_hr` , `tip_hr` )
                        VALUES (  '".$newzn."', '".$tip."' );";
                        $sql = mysql_query($txt_sql, $db);
        }
        
    }
}

//выводим группы товара
function list_group(){

    global $db;
    echo '<ul class="From_ul" >';
    //echo ' <option selected="selected" value=""> Все фирмы</option>'; // и эта строка выбрана

   // $sel_firma = 2; //$sel_firma = id_firm();

    $txt_sql = "SELECT `id`,`nm_group` FROM `group_t`
                    ORDER BY `nm_group` ASC  ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {


        echo '<li > <a href="#" onclick="javascript:set_group(' . "'" . $srt_arr[0]. "'" .','. "'" .  $srt_arr[1] . "'" .')" >'.$srt_arr[1].'</a> </li>';
         echo "<hr>";
    }

    echo '</ul>';

}

//выводим характеристики товара
function  list_hr($tip){
  //  'brend'
  //  'vid'
  //  'color'
  //  'razm1'
  //  'razm2'
  //  'rost'
  global $db;
  echo '<ul class="From_ul" >';
   $txt_sql = "SELECT `id`,`name_hr` FROM `tovar_hr`
                   WHERE `tip_hr`='".$tip."'
                   ORDER BY `name_hr` ASC  ";

     $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {
        echo '<li > <a href="#" onclick="javascript:set_hr(' . "'" . $srt_arr[1]  . "'".','. "'" .  $tip . "'" . ')" >'.$srt_arr[1].'</a> </li>';
        echo "<hr>";
    }

    echo '</ul>';


}

//выводим список единиц
function list_ed(){

    global $db;
    global $sel_ed;
    global $flgedit ;
    echo '<select id ="sel_ed" name ="sel_ed" style="width: 100px "  onchange="javascript:set_ed()"  >';
    //echo ' <option selected="selected" value=""> Все фирмы</option>'; // и эта строка выбрана



    $txt_sql = "SELECT `id`,`name_hr` FROM `tovar_hr`
                   WHERE `tip_hr`='ed_izm'
                   ORDER BY `name_hr` ASC  ";

    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

        if($srt_arr[1] == $sel_ed ){
            echo '<option selected="selected" value="'.$srt_arr[1].'">'.$srt_arr[1].'</option>';
        }else {
            if($flgedit == '')
                echo '<option                     value="'.$srt_arr[1].'">'.$srt_arr[1].'</option>';
        }
    }

    echo '</select>';

}


//выводим список стран
function list_strana(){

    global $db;
    global $sel_strana;
    global $flgedit ;

    echo '<select id ="sel_strana" name ="sel_strana" style="width: 100px " onchange="javascript:set_strana()"  >';
    //echo ' <option selected="selected" value=""> Все фирмы</option>'; // и эта строка выбрана

    //$sel_ed = 14 ; // id в бд для 'Украина'

    $txt_sql = "SELECT `id`,`name_hr` FROM `tovar_hr`
                   WHERE `tip_hr`='strana'
                   ORDER BY `name_hr` ASC  ";

    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {

        if($srt_arr[1] == $sel_strana ){
            echo '<option selected="selected" value="'.$srt_arr[1].'">'.$srt_arr[1].'</option>';
        }else {
            if($flgedit == '')
                echo '<option                     value="'.$srt_arr[1].'">'.$srt_arr[1].'</option>';
        }
    }

    echo '</select>';

}

?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title>карточка товара</title>

        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />


         <style type="text/css"> /* для DIV списков */
            .layer {
             overflow: auto; /* Добавляем полосы прокрутки */
             background: none repeat scroll 0 0 #F7F7F7;
            /* border-color: -moz-use-text-color #FFFFFF #FFFFFF; */
             border-image: none;
             border-radius: 0 0 6px 6px;
             border-right: 1px solid #FFFFFF;
             border-style: none solid solid;
             border-width: medium 1px 1px;
             font-size: 0.92em;
             padding: 0px;
             margin: 3px;

            }
            .From_ul{
                /* list-style-type: circle; */
                padding: 1px;
                margin: 3px;

            }
            

         </style>




        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript" src="jquery/js/jquery-1.4.2.min.js"></script> 
        <script type="text/javascript" src="jquery/js/jquery-ui-1.8.2.custom.min.js"></script> 
        <script type="text/javascript"> 
 
    jQuery(document).ready(function(){
      $('#name_tov').autocomplete({
        source:'suggest_zip.php',
        minLength:2,
        select:function(evt, ui){
                    edit_name();
                                    }
    });
    });
 
  
            var _newname = '';
            var _brend   = '';
            var _vid     = '';
            var _color   = '';
            var _razm1   = '';
            var _razm2   = '';
            var _rost    = '';
            var _ed      = '';
            var _strana  = '';

            //**********************baba-jaga@i.ua**********************
            // дел пробелы в начале и в конце
            function trim()
            {
                return this.replace(/^\s+|\s+$/g, '');
            }

            //**********************baba-jaga@i.ua**********************
            function check_it(){

               var n = document.forms['new_tovar'].ok_save.checked;

               if(n==true){
                   var h    = document.forms['new_tovar'].not_hk.checked;
                   var hkod = document.forms['new_tovar'].hkod.value;
                   hkod =  hkod.trim() ;

                   if(h==false){
                       if(hkod=='') {
                           alert('Не верно введен штрихкод');
                           n=false;
                       }
                   }

                   if( _newname.trim() == '') {
                       alert('Введите наименование товара');
                       n=false;
                   }



                   var grtov = document.getElementById("nm_group_tov").value ;
                   if( grtov.trim() == '') {
                       alert('Не выбрана группа для товара');
                       n=false;
                   }



                   var cenatov = document.getElementById("cena").value ;
                   if( cenatov.trim() == '') {
                       alert('Нужно указать цену продажи товара');
                       n=false;

                   }

                   //alert(''+cenatov.trim() + ' =' +n);

               }

               if(n==true) {
                   document.getElementById("new").value = 'save' ;
               }

               document.forms['new_tovar'].ok_save.checked = '' ;
               return n;

            }

            //**********************baba-jaga@i.ua**********************
            function on_load(){
                var msg =  <?php echo '"' . $alert_err . '"' ; ?> ;
                if(msg=='Данные сохранены'){
                    closeWin();
                    return ;
                }

                if(msg != ''){
                    alert(msg);
                }

                var save_close = <?php echo '"' . $save_close  . '"' ; ?> ;
                if(save_close != ''){
                    closeWin();
                    return ;
                }

                _brend   = <?php echo '"' . $brend  . '"' ; ?> ;
                _vid     = <?php echo '"' . $vid    . '"' ; ?> ;
                _color   = <?php echo '"' . $color  . '"' ; ?> ;
                _razm1   = <?php echo '"' . $razm1  . '"' ; ?> ;
                _razm2   = <?php echo '"' . $razm2  . '"' ; ?> ;
                _rost    = <?php echo '"' . $rost   . '"' ; ?> ;
                _ed      = <?php echo '"' . $sel_ed  . '"' ; ?> ;
                _strana  = <?php echo '"' . $sel_strana  . '"' ; ?> ;

                var txt_charakt = <?php echo '"' . $txt_charakt  . '"' ; ?> ;
                document.getElementById("txt_charakt").value = txt_charakt;

                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                document.getElementById("new").value = flgnew;

                if(flgnew == 'new'){
                    document.getElementById("tip_open").innerHTML = 'Новая карточка';
                }else if(flgnew == 'edit'){
                    document.getElementById("tip_open").innerHTML = 'Редактирование';
                }else{
                    document.getElementById("tip_open").innerHTML = 'Просмотр';
                }

                var idtov = <?php echo '"' . $id_tov  . '"' ; ?> ;
                document.getElementById("id_tov").value = idtov;

                var nm_group_tov = <?php echo '"' . $nm_group_tov  . '"' ; ?> ;
                document.getElementById("nm_group_tov").value = nm_group_tov;

                var id_group_tov = <?php echo '"' . $id_group_tov  . '"' ; ?> ;
                document.getElementById("id_group_tov").value = id_group_tov;

                var newname_tov = <?php echo '"' . $newname_tov  . '"' ; ?> ;
                document.getElementById("newname_tov").value = newname_tov;
                if(newname_tov==''){
                    newname_tov = 'Новая карточка товара';
                } else{
                    document.getElementById("name_tov").value = newname_tov;
                }
                document.getElementById("newname").innerHTML = newname_tov;

                var newname = <?php echo '"' . $newname  . '"' ; ?> ;
                document.getElementById("name_tov").value = newname;
                _newname = newname + " &#9675;" ;


                var hkod = <?php echo '"' . $hkod  . '"' ; ?> ;
                document.getElementById("hkod").value = hkod;


                var cena = <?php echo '"' . $cena  . '"' ; ?> ;
                document.getElementById("cena").value = cena;

                var sostav = <?php echo '"' . $sostav  . '"' ; ?> ;
                document.getElementById("sostav").value = sostav;
            if(flgnew == 'edit'){
                    var newname_tov = <?php echo '"' . $newname_tov  . '"' ; ?> ;
                    document.getElementById("name_tov").value =newname_tov;
                    
                }
               if(flgnew == 'view'){
                    var newname_tov = <?php echo '"' . $newname_tov  . '"' ; ?> ;
                    document.getElementById("name_tov").value =newname_tov;
                }
               var copy = <?php echo '"' . $copy  . '"' ; ?> ;
              
               if(copy == 'yes'){
                    var newname_tov = <?php echo '"' . $newname_tov  . '"' ; ?> ;
                    document.getElementById("name_tov").value =newname_tov;
                }

            }

           //**********************baba-jaga@i.ua**********************
            //открываем всплывающее окошко для ввода нового значения
            function add_zn(event,tip) {
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("win");

                obj.style.width =  '400px' ;
                obj.style.top = MouseY -60 + 'px' ;
                obj.style.left = MouseX +20 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_newzn').style.width =  '300px' ;
                //document.getElementById('_editpometka').value  = txtpometka ;
                document.getElementById('_newzn').focus();
                document.getElementById('_tip').value  = tip;

            }

            //**********************baba-jaga@i.ua**********************
            //добавляем новое значение в справочник базы
            function  add_new_zn(){
                ///alert('reskidka_str');
                //var iddoc = <?php //echo $id_doc; ?> ;
                //document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                var idtov = <?php echo '"' . $id_tov  . '"' ; ?> ;

                var newzn = document.getElementById('_newzn').value ;
                var tip   = document.getElementById('_tip').value;
                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                var flgnew =  document.getElementById("new").value ;

                document.location = "tovar_new.php?tip=" + tip + "&newzn=" + newzn  + "&new=" + flgnew + "&id_tov=" + idtov ;
            }



            //**********************baba-jaga@i.ua**********************
            //просто закрываем окно ввода нового значения
            function hide_bar() {
                document.getElementById("win").style.visibility="hidden";
            }

            //**********************baba-jaga@i.ua**********************
            // выбрали группу для нового товара
            function set_group(idgroup, nmgroup){
                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                if(flgnew == 'view') return;

               document.getElementById("nm_group_tov").value=nmgroup;
               document.getElementById("id_group_tov").value=idgroup;

            }

            //**********************baba-jaga@i.ua**********************
            // изменили наименование товара
            function edit_name(){

                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                if(flgnew == 'view') return;

                _newname = document.getElementById("name_tov").value + " &#9675;" ;

                //alert(_newname);

                var nametov = _newname + " " + _brend + " " +  _vid + " "    + _color + " " + _razm1 + " "  + _razm2 + " "  + _rost   ;
                document.getElementById("newname").innerHTML = nametov;
                document.getElementById("newname_tov").value = nametov;

            }

            //**********************baba-jaga@i.ua**********************
            // выбрали характеристику товара
            function set_hr(namehr, tiphr){

                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                if(flgnew == 'view') return;

                 if(tiphr == 'brend')  _brend = namehr + ',';
                 if(tiphr == 'vid')    _vid   = namehr + ',';
                 if(tiphr == 'color')  _color = namehr + ',';
                 if(tiphr == 'razm1')  _razm1 = namehr + ',';
                 if(tiphr == 'razm2')  _razm2 = namehr + ',';
                 if(tiphr == 'rost')   _rost  = namehr + ',';

                 var txt_charakt = _brend + ";" +  _vid + ";"    + _color + ";" + _razm1 + ";"  + _razm2 + ";"  + _rost +  ";"  + _ed+  ";"  + _strana +  ";" ;
                 document.getElementById("txt_charakt").value = txt_charakt;

                 var nametov = _newname + " " + _brend + " " +  _vid + " "    + _color + " " + _razm1 + " "  + _razm2 + " "  + _rost   ;
                 nametov = nametov.trim();
                 var dl = nametov.length;
                 var endsimvol = nametov.charAt(dl-1);
                 if (endsimvol==',') nametov = nametov.substring(0, dl-1);
                document.getElementById("newname").innerHTML = nametov;
                document.getElementById("newname_tov").value = nametov;



            }

            //**********************baba-jaga@i.ua**********************
            // выбрали единицу измерения
            function set_ed(){

                _ed = document.getElementById("sel_ed").value  ;
                 var txt_charakt = _brend + ";" +  _vid + ";"    + _color + ";" + _razm1 + ";"  + _razm2 + ";"  + _rost +  ";"  + _ed+  ";"  + _strana +  ";" ;
                 document.getElementById("txt_charakt").value = txt_charakt;
            }

            //**********************baba-jaga@i.ua**********************
            // выбрали страну
            function set_strana(){
                _strana = document.getElementById("sel_strana").value  ;
                var txt_charakt = _brend + ";" +  _vid + ";"    + _color + ";" + _razm1 + ";"  + _razm2 + ";"  + _rost +  ";"  + _ed+  ";"  + _strana +  ";" ;
                document.getElementById("txt_charakt").value = txt_charakt;
            }

            //**********************baba-jaga@i.ua**********************
            // печатаем ценник
            function prn_cennik(){

                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                if(flgnew != 'view') {
                    alert("Запишите изменения");
                    return;
                 }

                var kvo    =    document.getElementById("kvo_print").value  ;
                var hkod   = document.getElementById("hkod").value   ;
                var flgnew =  document.getElementById("new").value ;
                var id_tov = document.getElementById("id_tov").value ;

                document.location = "tovar_new.php?prncennik="+kvo+"&hkod=" + hkod + "&new=" + flgnew + "&id_tov=" + id_tov ;


            }

            //**********************baba-jaga@i.ua**********************
            // Создаем еще одну карточку , путем копирования
            function copy_tovar(){

                var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
                if(flgnew != 'view') {
                    alert("Запишите изменения");
                    return;
                 }
                var id_tov = document.getElementById("id_tov").value ;
                window.open("tovar_new.php?new=copy&id_tov=" +id_tov);
                closeWin();
            }

            //**********************baba-jaga@i.ua**********************
            // переход в режим редактирования
            function edit_tovar(){

               var flgnew = <?php echo '"' . $flgnew  . '"' ; ?> ;
               if(flgnew != 'view') {
                   return;
                }
                var id_tov = document.getElementById("id_tov").value ;
               document.location = "tovar_new.php?new=edit&id_tov=" + id_tov ;

            }


        </script>
        <link rel="stylesheet" href="jquery/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
  <style type="text/css"><!--
  
          /* style the auto-complete response */
          li.ui-menu-item { font-size:12px !important; } 
  
  --></style> 
    </head>
    <body onload="javascript:on_load()" >

        <form name='new_tovar' onsubmit = "return check_it()">

            <!--// таблица делим экран  //-->
            <table width=100% border=0 cellpadding=2 bordercolor='#FBF0DB' bgcolor ='#FBF0DB' >
                <!--//  //-->

                <col>
                <col width="150" > <!--// groups tovar //-->
                <col width="800" ><!--// osnova tovarф //-->
                <col>
                <tr>  <td>&nbsp;</td><td><h4 id="tip_open" style="color: #00f" >Новый товар</h4></td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp; </td>
                    <td>
                        <a href="#" onclick="javascript:add_zn(event,'group')" title="Добавить группу"> Группы товара <img src="images/add.png" border=0> </a>

                        <div class="layer" style=" width: 240px;  height: 485px; " >
                            <?php list_group(); // выводим спискок групп товара ?>
                        </div>

                    </td>
                    <td>

                        <!--// Таблица основных свойств товара //-->
                        <table border=0 cellpadding=2 bordercolor='#FBF0DB' bgcolor = '#FEF7DB' >
                            <col width="120" >
                            <col width="120" >
                            <col width="100">
                            <col width="100">
                            <col width="100" >
                            <col>
                            <tr>
                                <td colspan="5" style="text-align: center" > <h3 id="newname" >Новая карточка товара </h3>  </td>
                                <td>  <input type="hidden" size="3" id="new" name="new"  >
                                      <input type="hidden" size="3" id="id_tov" name="id_tov"  >
                                      <input type="hidden" size="3" id="id_group_tov" name="id_group_tov"  >
                                      <input type="hidden" size="3" id="newname_tov" name="newname_tov"  >
                                      <input type="hidden" size="3" id="txt_charakt" name="txt_charakt" >
                                </td>

                            </tr>
                            <tr >
                                <td colspan="1" > Группа: </td>
                                <td colspan="4" > <input type="text" size="48" id="nm_group_tov" name="nm_group_tov" readonly="true" >  </td>
                                <td             > &nbsp; </td>
                            </tr>
                            <tr >
                                <td colspan="1" > Наименование: </td>
                                <td colspan="4" > <input type="text" size="48"  name="name_tov" id="name_tov"  onchange="javascript:edit_name()" <?php echo  $flgedit ; ?> >  </td>
                                <td             > &nbsp; </td>
                            </tr>
                            <tr >
                                <td> штрихкод: </td>
                                <td> <input type="text" size="12" id="hkod" name="hkod" <?php echo  $edit_hkod ; ?> >  </td>
                                <td style="text-align: right" > артикул: </td>
                                <td> <input type="text" name="art" style="width: 100px " <?php echo  $flgedit ; ?> >  </td>
                                <td style="text-align: right"  > страна: </td>
                                <td> <?php list_strana(); // выводим список единиц ?> </td>
                            </tr>
                            <tr >
                                <td style="text-align: right"> <input type="checkbox" name="not_hk" id="not_hk"  > нет</td>
                                <td style="text-align: left">  штрихкода </td>
                                <td style="text-align: right" > ед.: </td>
                                <td> <?php list_ed(); // выводим список единиц ?> </td>
                                <td style="text-align: right" > цена: </td>
                                <td> <input type="text"  style="width: 100px "  name="cena" id="cena" <?php echo  $flgedit ; ?> >  </td>
                            </tr>

                            <tr >
                                <td colspan="2" > &nbsp; </td>
                                <td style="text-align: right" > состав: </td>
                                <td colspan="3"> <input type="text" style="width: 330px "  name="sostav" id="sostav" <?php echo  $flgedit ; ?> >  </td>
                            </tr>

                           <tr>
                               <td colspan="2" style="text-align: left" >
                                   Печать этикеток. к-во:  <input type="text" name="kvo_print" id="kvo_print" value="1" size="3"  >
                                  <a href="#" onclick="javascript:prn_cennik()" title="Печать этикетки штрих кода" > <img src="images/b_print.png" border=0> </a>
                               </td>
                               <td colspan="4" style="text-align: right" >
                                <input type="checkbox" name="ok_save"  > все верно!
                                <button name="bt_ok"    value="save_close" type="submit" >OK</button>
                                <button name="bt_new"   value="new_tovar"  type="submit" >записать</button>
                                <button name="bt_clear" value="clear"      type="button"  onclick="javascript:closeWin()"  >закрыть</button>
                                <a href="#" onclick="javascript:edit_tovar()" title="Редактировать товар" > &nbsp; <img src="images/b_edit.png" border=0> </a>
                                &nbsp;
                                <a href="#" onclick="javascript:copy_tovar()" > <img src="images/b_import.png" border=0> &nbsp;</a>
                               </td>
                            </tr>

                            <tr >
                                <td colspan="6" > &nbsp; </td>
                            </tr>
                            <tr style="text-align: center " >
                                <td > <a href="#" onclick="javascript:add_zn(event,'brend')" > Бренд  <img src="images/add.png" border=0> </a></td>
                                <td> <a href="#" onclick="javascript:add_zn(event,'Vid')" > Вид <img src="images/add.png" border=0> </a> </td>
                                <td> <a href="#" onclick="javascript:add_zn(event,'color')" > Цвет <img src="images/add.png" border=0> </a></td>
                                <td> <a href="#" onclick="javascript:add_zn(event,'razm1')" > Размер1 <img src="images/add.png" border=0> </a> </td>
                                <td> <a href="#" onclick="javascript:add_zn(event,'razm2')" > Размер2 <img src="images/add.png" border=0> </a> </td>
                                <td> <a href="#" onclick="javascript:add_zn(event,'rost')" > Рост <img src="images/add.png" border=0> </a>  </td>
                            </tr>
                           <tr >
                                <td> <div class="layer" style=" width: 120px;  height: 250px; " >  <?php list_hr('brend'); ?>  </div></td>
                                <td> <div class="layer" style=" width: 120px;  height: 250px; " >  <?php list_hr('vid');   ?>  </div></td>
                                <td> <div class="layer" style=" width: 100px;  height: 250px; " >  <?php list_hr('color'); ?>  </div></td>
                                <td> <div class="layer" style=" width: 100px;  height: 250px; " >  <?php list_hr('razm1'); ?>  </div></td>
                                <td> <div class="layer" style=" width: 100px;  height: 250px; " >  <?php list_hr('razm2'); ?>  </div></td>
                                <td> <div class="layer" style=" width: 100px;  height: 250px; " >  <?php list_hr('rost');  ?>  </div></td>
                            </tr>


                        </table>

                    </td>
                    <td>&nbsp; </td>
                </tr>


            </table>

            <hr><hr>

        </form>


     <!-- всплывающее окно для ввода новых значений, группы, характеристик -->
            <div id=win class=bar>
                <div align=right>
                <span style='cursor: pointer' title='Закрыть' onclick='hide_bar()'>x</span>
                </div>
                Значение: <input type="text" name="_newzn" id ="_newzn"  onchange="javascript:add_new_zn()" >
                <input type="hidden" id ="_tip">
            </div>


    </body>
</html>


