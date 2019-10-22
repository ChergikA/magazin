<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// Создание возврата клиента
// вызывается по клику из klients_table, если мы в режиме создания нового документа

//global $db;



//$refresh_pricelist = 'y';
if ( isset($_GET['kod_tov']) ) {

    $d_kl = $_GET['kod_tov'];
    $_SESSION['diskont_klienta'] = $d_kl ;
    $nm_klient = nm_klient($d_kl) ;

    //$refresh_pricelist = 'n';

}


// был ли ввежен сейчас или ранее штрихкод
$h_kod = diskont_klienta () ;

$nm_klient = 'Наименование клиента';

if($h_kod != 'дисконт') $nm_klient = nm_klient ($h_kod);

// какой документ выбрали
$vid_doc = "Возврат";
if ( isset($_GET['viddoc']) ) {
    $vid_doc = $_GET['viddoc'];

}


//(iddoc, idfirm, name_doc, viddocparent, viddoc)

$name_doc = "Товарный чек";
$id_doc_parent ='';
$vid_doc_parent ='';
if ( isset($_GET['name_doc']) ) {
    $name_doc = $_GET['name_doc'];
    $id_doc_parent = $_GET['iddoc'];
    $vid_doc_parent = $_GET['viddocparent'];
}


function opennewdoc() {
// создаем новый документ и открываем его окно ч/з скрипт onload
    global $db;
    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        //newdoc=newdoc&id_doc_parent=" + id_doc_parent

        $id_doc_parent = $_GET['id_doc_parent'];
        if ($id_doc_parent == '') { // не выбран чек для возврата
            $id_firm = id_firm();
        }
        else { // указан конкретный чек
            $txt_sql = "SELECT `firms_id` FROM `DocHd` WHERE `id` = '" . $id_doc_parent . "' ";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $id_firm = $s_arr['firms_id'];
        }

        $vid_doc = 'Возврат';

        $id_doc = add_newdoc($id_firm, $vid_doc);

        if ($id_doc_parent == '') { // не выбран чек для возврата
        }
        else {// указан конкретный чек
            // сформируем пометку для возврата
            $txt_sql    = "SELECT `nomDoc` , `DataDoc` , `oplataBank` ,`Pometka` FROM `DocHd` WHERE `id` = '" . $id_doc_parent . "' ";
            $sql        = mysql_query($txt_sql, $db);
            $s_arr      = mysql_fetch_array($sql, MYSQL_BOTH);
            $p_vozvrata = 'Чек № ' . $s_arr['nomDoc'] . ' от:' . $s_arr['DataDoc'];
            $p_chek     = $s_arr['Pometka'];
            $form_oplata = $s_arr['oplataBank'];

            // сформируем пометку для чека
            $txt_sql = "SELECT `nomDoc` , `DataDoc` FROM `DocHd` WHERE `id` = '" . $id_doc . "' ";
            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $p_chek  = $p_chek . ' Возврат № ' . $s_arr['nomDoc'] . ' от:' . $s_arr['DataDoc'];


            $txt_sql = "UPDATE `DocHd` SET  `Pometka` = '" . $p_chek . "' WHERE `DocHd`.`id` = '" . $id_doc_parent . "' ; ";
            $sql     = mysql_query($txt_sql, $db);

            $txt_sql = "UPDATE `DocHd` SET   `Pometka` = '" . $p_vozvrata . "',
                       `oplataBank` = '" . $form_oplata . "'  WHERE `DocHd`.`id` = '" . $id_doc . "' ; ";
            $sql     = mysql_query($txt_sql, $db);

            // заполним таб часть приемки
            //$txt_sql = "SELECT `Tovar_id`,`Kvo`,`nomstr` FROM `DocTab` WHERE `DocHd_id` = '" .  $idprihodnik .  "' ORDER BY `DocTab`.`nomstr`";

            $txt_sql = "SELECT `Tovar`.`Kod1C`, `DocTab`.`Kvo` , `DocTab`.`Cena` , `DocTab`.`Skidka`  "
                    . "FROM `DocTab`\n"
                    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
                    . "WHERE (`DocTab`.`DocHd_id` ='" . $id_doc_parent . "')\n"
                    . "ORDER BY `DocTab`.`nomstr` ASC\n"
                    . " ";

            $sql     = mysql_query($txt_sql, $db);
            while ($srt_arr = mysql_fetch_array($sql)) {

                $err = upd_tabdoc($id_doc, $srt_arr['Kod1C'], $srt_arr['Cena'], 0, $srt_arr['Skidka'], $srt_arr['Cena'], $srt_arr['Kvo']);
                //echo "Alert($err);"   ;
            }
        }

        echo ' window.open("doc_vzv_klient.php?id_doc= ' . $id_doc . ' " ); ';
    }

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

                //document.frm_searh.h_kod.value = <?php //echo '"' . $h_kod . '"'; ?> ;

                var h_kod = <?php echo '"' . $h_kod . '"'; ?> ;
                top.content.location  = "newdoc_list_vzv_klient.php?h_kod=" +h_kod ;


            }

            //**********************baba-jaga@i.ua**********************
            function open_vzvdoc(){

                 var id_doc_parent =  <?php echo '"' . $id_doc_parent . '"' ?> ;
                 var vid_doc_parent=  <?php echo '"' . $vid_doc_parent . '"' ?> ;

                 if(vid_doc_parent != ''){
                 if(vid_doc_parent != 'Чек' ){
                     alert('Возврат можно создать только по товарному чеку');
                     return;
                 }}

                 top.top_menu.location = "newdoc_menu_vzv_klient.php?newdoc=newdoc&id_doc_parent=" + id_doc_parent
                                + "&viddoc=Возврат" ;

            }

        </script>
    </head>
    <body onload="javascript:on_load()"  >

        <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >

            <div >
                &nbsp; &nbsp;&nbsp;&nbsp;
                <?php list_firm(); // выводим списко фирм ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <?php list_viddoc(); // выводим виды документов ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  создать  " onclick="open_vzvdoc()"    >
                <br>
            </div>

            <div style=" padding: 7px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                <?php echo $nm_klient ?>
                <input type="hidden"   id="nm_klient"  value=  <?php echo '"' . $nm_klient . '"' ?> >
                <input type="hidden"   id="viddoc" name="viddoc"  value=  <?php echo '"' . $vid_doc . '"' ; ?>  >

            </div>

            <div style=" padding: 8px 0px 0px 0px; font-size: 18px; color: #AA0000; " >

                <?php echo $name_doc ?>



            </div>
        </form>


        <div style=" padding: 10px 0px 0px 0px " >
            <table cellspacing='0' class="Design5" >
                <col width="30px">
                <col width="75px">
                <col width="75px">
                <col width="150px">
                <col width="210px">
                <col width="39px">
                <col width="69px">
                <col >

                <thead>
                    <tr>
                        <th >&nbsp;</th> <!-- состояние документа-->
                        <th class="Odd">дата</th>
                        <th >номер</th>
                        <th class="Odd">вид</th>
                        <th >покупатель</th>
                        <th class="Odd">%</th>
                        <th> сумма</th>
                        <th class="Odd">пометка</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
