<?php
require("header.inc.php");
include("milib.inc");


// проверка на ввод штрих кода, если введен заносим в сессию
if ( isset($_GET['h_kod']) ) {
    $h_kod = $_GET['h_kod'];
    if(trim($h_kod) == '' ) $h_kod = 'дисконт';
    $_SESSION['diskont_klienta'] = $h_kod;
}

// проверка на ввод фрагмента, если введен заносим в сессию
if ( isset($_GET['fragment']) ) {

    $frgm = $_GET['fragment'];
    if(trim($frgm) == '' ) $_SESSION['list_klient_searh'] = 'фрагмент';
    else $_SESSION['list_klient_searh'] = $frgm;
}

// был ли ввежен сейчас или ранее штрихкод
$h_kod = diskont_klienta () ;


// был ли ввежен сейчас или ранее фрагмент клиента
if( isset( $_SESSION['list_klient_searh']) ) {
        $frgm = trim( $_SESSION['list_klient_searh']) ;
    }else {
        $frgm = 'фрагмент' ;
}



$nm_klient = 'Наименование клиента';

if($h_kod != 'дисконт') $nm_klient = nm_klient ($h_kod);

$_SESSION['imenin'] = 'no';
if ( isset($_GET['imenin']) ) {
    //echo 'покажем всех имениннников';
    $_SESSION['list_klient_searh'] = 'фрагмент';
    $h_kod = 'дисконт';
    $_SESSION['diskont_klienta'] = $h_kod;
    $nm_klient = 'Наименование клиента';
    $_SESSION['imenin'] = 'ok';
}

// если в нижней таблице выбрали товар , то нижнюю табл не обновляем, чтоб не прыгало
$refresh_pricelist = 'y';
if ( isset($_GET['kod_tov']) ) {

    $d_kl = $_GET['kod_tov'];
    $_SESSION['diskont_klienta'] = $d_kl ;
    $nm_klient = nm_klient($d_kl) ;

    $h_kod = 'дисконт';

    $refresh_pricelist = 'n';

}


?>
<!--
менюшка для прайс-листа
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript">

            // при активации окна обновляем данные
            var active = true;

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                 if(!check_user() )  return;

                document.frm_searh.h_kod.value = <?php echo '"' . $h_kod . '"' ; ?> ;
                document.frm_searh.fragment.value =<?php echo '"' . $frgm . '"' ; ?>;
                document.getElementById('h_kod').focus();

                // обновляем нижнюю таблицу
                <?php
                if ($refresh_pricelist == 'y')
                    echo 'top.content.location  = "klients_table.php";';
                ?>

            }

            //**********************baba-jaga@i.ua**********************
            // для body
            function onfocus(){
                if(active == false) {
                    //var loc = new String( document.location );
                    //loc = loc.substr(0,loc.indexOf('&idstr=')); // часть строки до #
                    //loc = loc.substr(0,loc.indexOf('#')); // часть строки до #
                    //if(loc=='')loc = document.location;
                    //top.content.location = loc + "&idstr=" + idstriz ;
                    top.content.location = "klients_table.php";
                    active = true;

                }
                active = true;
            }

                       //**********************baba-jaga@i.ua**********************
                       function on_focus(element){

                           if(element == 'h_kod'){
                               // alert(element);
                               //alert("te-" +  document.getElementById('h_kod').value );
                               var hkod = document.frm_searh.h_kod.value;
                               if(hkod == "дисконт") {
                                   document.frm_searh.h_kod.value = "";
                               }
                               // document.frm_searh.h_kod.value = "test";
                               document.frm_searh.h_kod.style = "color: #000000";
                               // document.getElementById('h_kod').style.color = "#fc0;";
                               document.getElementById('h_kod').select();
                               //this.style.color='green';
                               //this.style = "color: #000000";

                               //style="/*color: #8f8888;"
                           }else if( element == 'fragment' ){
                               var frgm = document.frm_searh.fragment.value;
                               if( frgm  == "фрагмент") {
                                   document.frm_searh.fragment.value = "";
                               }
                               // document.frm_searh.h_kod.value = "test";
                               document.frm_searh.fragment.style = "color: #000000";
                               // document.getElementById('h_kod').style.color = "#fc0;";
                               document.getElementById('fragment').select();

                           }else alert("element=" + element);
                       }


                       //**********************baba-jaga@i.ua**********************
                       function off_focus(element){
                           if(element == 'h_kod'){
                               // alert(element);
                               //alert("te-" +  document.getElementById('h_kod').value );
                               var hkod = document.frm_searh.h_kod.value;
                               if(hkod == "") {
                                   document.frm_searh.h_kod.value = "дисконт";
                               }
                           }else if( element == 'fragment' ){
                               var frgm = document.frm_searh.fragment.value;
                               if( frgm  == "") {
                                   document.frm_searh.fragment.value = "фрагмент";
                               }
                           }else alert("element=" + element);
                       }


                       //**********************baba-jaga@i.ua**********************
                       // submit
                       function go_searh(){
                           return true ;
                       }

                       //**********************baba-jaga@i.ua**********************
                       function open_klient(kak){
                           if(kak=='edit'){
                               var diskont = <?php echo '"' . diskont_klienta() . '"' ; ?> ;
                               if(diskont == 'дисконт') {
                                   alert('ВыбериТЕ клиента');
                               }else{
                                active=false;
                                window.open("klients_new.php?new=edit");
                               }
                           }else if(kak=='именины'){
                               //top.top_menu.location = "klients_menu.php?imenin=1";

                           }else{
                               active = false;
                               window.open("klients_new.php?new=new");
                           }//
                       }
        </script>
    </head>
    <body onload="javascript:on_load()" onfocus="javascript:onfocus()" >
        <div id="menu"  style=" padding: 13px 0px 0px 0px;  ">
            <ul>
                <li> <a href="javascript:open_klient('new')">новый клиент</a></li>
                <li> <a href="javascript:open_klient('edit')"> карточка клиента </a> </li>
                <li><a href="javascript:open_klient('именины')">история клиента</a></li>
            </ul>
            <br>
        </div>

        <div style=" padding: 0px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                <?php echo $nm_klient ?>

        </div>

        <div style=" padding: 9px 0px 0px 0px;  " >

            <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                Поиск: &nbsp; <input type="text" name="h_kod" id="h_kod" size="9" maxlength="13"
                                     tabindex="0" style="color: #8f8888;" onfocus="javascript:on_focus('h_kod')"
                                                                           onblur="javascript:off_focus('h_kod')" >
                &nbsp; &nbsp; &nbsp;
                <input type="text" name="fragment" id ="fragment"  size="19" maxlength="13"
                       tabindex="0" style="color: #969696 " onfocus="javascript:on_focus('fragment')"
                                                            onblur="javascript:off_focus('fragment')" >
                <input type="hidden"   name="txt_only" >
                <input type="submit" name="submit" id="submit"  value="  Найти  "    >
            </form>
        </div>



        <div style=" padding: 10px 0px 0px 0px " >
            <table cellspacing='0' border='0' class="Design5" >
                <col width="120px">
                <col width="310px">
                <col width="65px">
                <col width="90px">
                <col width="100px">



                <thead>
                    <tr>
                        <td class="Odd">дисконт</td>
                        <th>наименование</th>
                        <td  class="Odd">скидка</td>
                        <th >посещал</th>
                        <th>пометка</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
