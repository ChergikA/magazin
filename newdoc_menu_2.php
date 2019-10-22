<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// ПРИЕМКА ТОВАРА выбор приходника для приемки

$nm_klient = 'ПРИЕМКА ТОВАРА';
$id_prihodnika = '';
// если в нижней таблице выбрали приходник , то нижнюю табл не обновляем, чтоб не прыгало
$refresh_pricelist = 'y';
if ( isset($_GET['namedoc']) ) {
    $nm_klient =  $_GET['namedoc'];
    $id_prihodnika =  $_GET['kod_tov'];
    //newdoc_menu_2.php?kod_tov=" + iddoc + "&namedoc=" +namedoc
    $refresh_pricelist = 'n';
}

$go_mail = '';
if ( isset($_GET['new_mail']) ) {
  
    if ( cls_set::get_parametr('docPriemka', 'docPriemkaMail') == 1){
        echo '=читаем мыло';
        require_once('pop3/Pop3Imap.php');  
        $go_mail = 'y';
    }

}



function opennewdoc() {
// создаем новый документ и открываем его окно ч/з скрипт onload
    global $db;
    $get_mail = cls_set::get_parametr('docPriemka', 'docPriemkaMail');
    $zolote_pero = cls_set::get_parametr('docPriemka', 'ZolotePero');

    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        $id_firm = $_GET['id_firm'];
        $vid_doc = $_GET['viddoc'];

        $idprihodnik = $_GET['idprihodnik'];

        $id_doc = add_newdoc($id_firm, $vid_doc);


        // сформируем пометку для приемки
        $txt_sql = "SELECT `nomDoc` , `DataDoc`, `Pometka`  FROM `DocHd` WHERE `id` = '" . $idprihodnik . "' ";
        $s_arr = cls_my_sql::const_sql($txt_sql);
        $p_mail = $s_arr['Pometka'];
        $p_priemki = 'Приходный документ № ' . $s_arr['nomDoc'] . ' от:' . $s_arr['DataDoc'] . '  ' . $p_mail;



        // echo '<br> p_mail=' . $p_mail . "<br>" ;
        // сформируем пометку для приходника
        $txt_sql = "SELECT `nomDoc` , `DataDoc` FROM `DocHd` WHERE `id` = '" . $id_doc . "' ";
        $s_arr = cls_my_sql::const_sql($txt_sql);
        $p_prihodki = 'Приемка № ' . $s_arr['nomDoc'] . ' от:' . $s_arr['DataDoc'];

        $statusprinjato = id_status('Принят');

        // обмен линками на документы
        // приходнику поставим статус принят
        if ($get_mail == 0) {
            $lnk = $idprihodnik;
        } else { //файл пришел по почте, это приходка
            $lnk = '0';
        }

        $txt_sql = "UPDATE `DocHd` SET `statusDoc` = '" . $statusprinjato . "',
                                         `Pometka` = '" . $p_prihodki . "',
                                         `link_doc_id` = '" . $id_doc . "' WHERE `DocHd`.`id` = '" . $idprihodnik . "' ; ";
        cls_my_sql::run_sql($txt_sql);

        $txt_sql = "UPDATE `DocHd` SET   `Pometka` = '" . $p_priemki . "',
                                         `link_doc_id` = '" . $lnk . "' WHERE `DocHd`.`id` = '" . $id_doc . "' ; ";
        cls_my_sql::run_sql($txt_sql);

        // заполним таб часть приемки
        //$txt_sql = "SELECT `Tovar_id`,`Kvo`,`nomstr` FROM `DocTab` WHERE `DocHd_id` = '" .  $idprihodnik .  "' ORDER BY `DocTab`.`nomstr`";

        if ($get_mail == 0) {

            $txt_sql = "SELECT `Tovar`.`Kod`, `DocTab`.`Kvo`\n"
                    . "FROM `DocTab`\n"
                    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
                    . "WHERE (`DocTab`.`DocHd_id` ='" . $idprihodnik . "')\n"
                    . "ORDER BY `DocTab`.`nomstr` ASC\n"
                    . " ";

            $sql = cls_my_sql::tbl_sql($txt_sql);
            while ($srt_arr = mysql_fetch_array($sql)) {

                $err = upd_tabdoc($id_doc, $srt_arr['Kod'], cena_tovar($srt_arr['Kod']), 0, 0, cena_tovar($srt_arr['Kod']), $srt_arr['Kvo']);
                //echo "Alert($err);"   ;
            }
        } else { // 
            // читаем текстовый файл и заполняем док
            if (trim($p_mail) !== '') {
                $srt_arr = explode(";", $p_mail);
                $f_name = $srt_arr[1];
                // echo ''.$f_name . '<br>' ;
                if (is_writable($f_name)) {
                    $handle = fopen($f_name, 'r');
                    while (!feof($handle)) {
                        $str = fgets($handle);
                        //echo '<br> str='.$str . '<br>' ;
                        $srt_arr = explode(chr(9), $str);

                        if ($srt_arr[0] == '')
                            continue;
                        if ($srt_arr[1] == '')
                            continue;
                        if ($srt_arr[2] == '')
                            continue;
                        if ($srt_arr[5] == '')
                            continue;
                        if ($srt_arr[6] == '')
                            continue;
                        if ($srt_arr[7] == '')
                            continue;
                        if ($srt_arr[8] == '')
                            continue;
                        if ($srt_arr[8] == 'Штрих код')
                            continue;

                        /*
                          echo '0=' . $srt_arr[0] . '  1='. $srt_arr[1] . '  2='. $srt_arr[2] . '  3='. $srt_arr[3] .
                          '  4='. $srt_arr[4] . '  5='. $srt_arr[5] . '  6='. $srt_arr[6]
                          . '  7='. $srt_arr[7] . '  8='. $srt_arr[8] . '  9='. $srt_arr[9];
                         */
                        //<br>0=  1=ТРЕБОВАНИЕ №  2=31067213  3=  4=  5=НеизвестныйОбъект  6=  7=  8=  9=ctr=от 24 Вересня 2014 р.
                        //<br>0=от 24 Вересня 2014 р.  1=  2=  3=  4=  5=  6=  7=  8=  9=ctr=		
                        //<br>0=1  1=80145  2=Бумага  А3 "Zoom" С 80г/м2 (500л) #  3=  4=шт.  5=10  6=87.4   7=874     8='6416764001018  9=20%
                        //<br>0=4  1=4052   2=Лента для касс. апп. 57х21 SL **     3=  4=шт.  5=360 6=1.069  7=384.84  8='0              9=20%

                        $hkod = $srt_arr[8];
                        //echo '<br>$hkod1='. $hkod ;
                        $hkod = str_replace("'", "", $hkod);
                        $kod1c = trim($srt_arr[1]);
                        //while (strlen($kod1c) < 6) {
                        //    $kod1c = '0' . $kod1c;
                        //}

                        $newname_tov = trim($srt_arr[2]);
                        $sel_ed = trim($srt_arr[4]);
                        $pricein = trim($srt_arr[6]);
                        $id_tovar = '';

                        //Золотому перу видеть цены канцлера
                        $price_knc = 0;
                        if ($zolote_pero == 1)
                            $price_knc = trim($srt_arr[10]);


                        //echo '<br>$hkod='. $hkod ;
                        if (strlen($hkod) > 2) {
                            //echo ' ищем по штрих коду';
                            $kodtov = trim($hkod);
                            $id_tovar = id_tovar($kodtov);
                            // echo 'idt='.$id_tovar;
                        }

                        if ($id_tovar === '') {
                            // ищем по коду 1с
                            $kodtov = $kod1c;
                            $id_tovar = id_tovar('', $kodtov);
                            //echo 'idt2='.$id_tovar;
                        }

                        if ($id_tovar === '') {
                            // ищем по имени
                            $txt_sql = "SELECT `id_tovar`, `Kod1C`,`Kod` FROM `Tovar` WHERE `Tovar` LIKE '$newname_tov'";
                            $s_arr = cls_my_sql::const_sql($txt_sql);

                            $id_tovar = $s_arr['id_tovar'];
                            if ($id_tovar == NULL) {
                                $id_tovar = '';
                            } else {
                                $kod1c = $s_arr['Kod1C'];
                                $kodtov = $s_arr['Kod'];
                            }
                            //echo 'idt3='.$id_tovar;
                        }


                        //echo '<br>ktov='. $kodtov ;cena_tovar($kodtov)
                        if ($id_tovar !== '') { // обновим входную
                            $txt_sql = " UPDATE `Tovar` SET `PriceIn` = '" . $srt_arr[6] . "' WHERE `id_tovar` = '" . $id_tovar . "' ;";
                            cls_my_sql::run_sql($txt_sql);
                            $err = upd_tabdoc($id_doc, $kodtov, $price_knc, 0, 0, cena_tovar($kodtov), $srt_arr[5]);
                        } else { // if( strpos($err, 'нет в БД товара с кодом:') != FALSE ){//новый товар
                            $txt_sql = "INSERT INTO `Tovar` (`id_group`      , `Kod1C`     , `Kod`,
                                             `Tovar`         , `Price`     , `PriceOpt`, `PriceIn` , `NDS`,
                                             `ed_izm`        , `v_upakovke`, `Sostav`  , `strana`,
                                             `redaktor`      , `magazin`   , `flg_edit` , `charakteristiks`)
                                     VALUES ('' , '$kod1c'      , '$kodtov'    ,
                                              '$newname_tov' , '0.00'     , '0.00'  , '$pricein'   ,'1'   ,
                                              '$sel_ed'       , NULL        , ''      , '',
                                              '" . name_user() . "', NULL        ,'1'       , ''  );";

                            if (cls_my_sql::id_inserta($txt_sql) !== NULL) {
                                $err = upd_tabdoc($id_doc, $kodtov, $price_knc, 0, 0, cena_tovar($kodtov), $srt_arr[5]);
                            }
                        }
                    }// конец чтения тхт
                    fclose($handle);
                }
            } // конец если читаем текстовый файл
        }   // конец если пометка указывает на файл принят с мыла       


        echo ' window.open("doc_priemka.php?id_doc= ' . $id_doc . ' " ); ';
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
        <title>приемка товара</title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript">
            //**********************baba-jaga@i.ua**********************
            function on_load(){
                if(!check_user() )  return;
                <?php opennewdoc(); ?>
                // обновляем нижнюю таблицу при необходимости
                var repricelist = <?php echo '"' . $refresh_pricelist . '"'; ?> ;
                if( repricelist == "y" ) top.content.location  = "newdoc_list_prihodov.php";
                
                var go_mail = <?php echo "'" . $go_mail . "'"; ?> ; 
                if( go_mail == 'y' ) top.top_menu.location = "newdoc_menu_2.php?id_firm='1'&viddoc='Приемка'";

            }



            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){

                return true ;
            }
            
            //**********************baba-jaga@i.ua**********************
            function new_mail(){
                top.top_menu.location  = "newdoc_menu_2.php?new_mail=new_mail"  ;
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
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  получить почту " onclick="new_mail()"    >
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  создать документ  " onclick="open_doc()"    >
                
                <br>
            </div>

            <div style=" padding: 15px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                <?php echo $nm_klient ?>
                <input type="hidden"   id="nm_klient"  value=  <?php echo '"' . $id_prihodnika . '"' ?> >

            </div>

            <div style=" padding: 4px 0px 0px 0px;  " >
                    &nbsp; &nbsp;&nbsp;&nbsp;


            </div>
        </form>


        <div style=" padding: 10px 0px 0px 0px " >
            <table cellspacing='0' border='0' class="Design5" >
                <col width="120px">
                <col width="120px">
                <col width="120px">
                <col width="340px">
                <col >



                <thead>
                    <tr>
                        <td class="Odd"></td>
                        <th>Дата</th>
                        <td  class="Odd">Номер</td>
                        <th>пометка</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
