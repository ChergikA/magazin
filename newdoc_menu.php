<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// создание новых документов, которые требуют ввода имени клиента
//на данном этапе это чек и возврат от клиента

//global $db;

// какой документ выбрали
$vid_doc = "Чек";
if ( isset($_GET['viddoc']) ) {
    $vid_doc = $_GET['viddoc'];

}

if ($vid_doc == "Возврат"){
    // надо выбрать клиента по новой
    $h_kod = 'дисконт';
    $_SESSION['diskont_klienta'] = $h_kod;

}

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


// если в нижней таблице выбрали товар , то нижнюю табл не обновляем, чтоб не прыгало
$refresh_pricelist = 'y';
if ( isset($_GET['kod_tov']) ) {

    $d_kl = $_GET['kod_tov'];
    $_SESSION['diskont_klienta'] = $d_kl ;
    $nm_klient = nm_klient($d_kl) ;

    $h_kod = 'дисконт';

    $refresh_pricelist = 'n';

}



function opennewdoc() {
// создаем новый документ и открываем его окно ч/з скрипт onload
    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        $id_firm = $_GET['id_firm'];
        $vid_doc = $_GET['viddoc'];

        if ($vid_doc == "Чек"){
            $id_doc  =  add_newdoc($id_firm, $vid_doc) ;
            echo ' window.open("doc_chek.php?id_doc= ' . $id_doc . ' " ); ';
        }
        if ($vid_doc == "Счет"){
            $id_doc  =  add_newdoc($id_firm, $vid_doc) ;
            echo ' window.open("doc_schet.php?id_doc= ' . $id_doc . ' " ); ';
        }
        if ($vid_doc == "Возврат"){
            //echo ' window.open("doc_vzv_klient.php?id_doc= ' . $id_doc . ' " ); ';
            //"newdoc_menu_vzv_klient.php?kod_tov=" + kod_tov +"&viddoc="+ top.top_menu.vid_doc;
            //echo 'top.top_menu.location = "newdoc_menu_vzv_klient.php?&viddoc='.$vid_doc.';';
        }

    }

    //$h_kod = 'дисконт';
    //$_SESSION['diskont_klienta'] = $h_kod ;
    //$nm_klient = nm_klient($h_kod) ;


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

            var vid_doc = <?php echo '"' . $vid_doc . '"' ; ?> ;

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                if(!check_user() )  return;

                <?php opennewdoc(); ?>

                document.frm_searh.h_kod.value = <?php echo '"' . $h_kod . '"'; ?> ;
                document.frm_searh.fragment.value =<?php echo '"' . $frgm . '"'; ?>;
                document.getElementById('h_kod').focus();


                // обновляем нижнюю таблицу при необходимости
                var repricelist = <?php echo '"' . $refresh_pricelist . '"'; ?> ;
                if( repricelist == "y" ) top.content.location  = "klients_table.php";



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




        </script>
    </head>
    <body onload="javascript:on_load()"  >

        <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >

            <div style=" padding: 3px 0px 0px 0px;"">
                &nbsp; &nbsp;&nbsp;&nbsp;
                <?php list_firm(); // выводим списко фирм ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <?php list_viddoc(); // выводим виды документов ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  создать  " onclick="open_doc()"    >
                <br>
            </div>

            <div style=" padding: 2px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                <?php echo $nm_klient ?>
                <input type="hidden"   id="nm_klient"  value=  <?php echo '"' . $nm_klient . '"' ?> >
                <input type="hidden"   id="viddoc" name="viddoc"  value=  <?php echo '"' . $vid_doc . '"' ; ?>  >

            </div>

            <div style=" padding: 9px 0px 0px 0px;  " >


                Поиск: &nbsp; <input type="text" name="h_kod" id="h_kod" size="9" maxlength="13"
                                     tabindex="0" style="color: #8f8888;" onfocus="javascript:on_focus('h_kod')"
                                     onblur="javascript:off_focus('h_kod')" >
                &nbsp; &nbsp; &nbsp;
                <input type="text" name="fragment" id ="fragment"  size="19" maxlength="13"
                       tabindex="0" style="color: #969696 " onfocus="javascript:on_focus('fragment')"
                       onblur="javascript:off_focus('fragment')" >
                <input type="hidden"   name="txt_only" >
                <input type="submit" name="kn_searh" id="kn_searh"  value="  Найти  "    >

            </div>
        </form>


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
