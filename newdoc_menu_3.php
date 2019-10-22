<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// для создания переучета
//


global $db;



$nm_klient = 'Создать новый документ "Переучет"';
$id_prihodnika = '';

// проверка на ввод фрагмента, если введен заносим в сессию
if ( isset($_GET['fragment']) ) {
    $frgm = $_GET['fragment'];
    $_SESSION['frgm_tov_searh'] = $frgm;
}

//флажок только остатки передаем через скрытое поле
if ( isset($_GET['txt_only']) ) {
    $only = $_GET['txt_only'];
   // echo 'only='.$only;
    if( $only == 'no'  ) $_SESSION['only_searh'] = '';
    if( $only == 'checked'  ) $_SESSION['only_searh'] = $only;
}

if ( isset($_GET['viddoc']) ) {
    if($_GET['viddoc'] == 'Переучет') $nm_klient = 'Создать новый документ "Переучет"';
    if($_GET['viddoc'] == 'Перемещение') $nm_klient = 'Возврат товара на основной склад';
}




function opennewdoc() {
// создаем новый документ и открываем его окно ч/з скрипт onload
    global $db;
    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        $id_firm = $_GET['id_firm'];
        $vid_doc = $_GET['viddoc'];

        //echo '=';

        $id_doc  = add_newdoc($id_firm, $vid_doc);



        // echo '<script type="text/javascript">';
        if ($vid_doc == 'Переучет') {
            //strdoc =newdoc_menu_3.php?newdoc=newdoc&id_firm=5&viddoc=Переучет&fragment=ляляля&txtonly=checked
            if (isset($_GET['txtonly'])) { // создаем пустой док переучет
            }
            elseif (isset($_GET['fragment'])) { // заполняем строки удовл условию фрагмент
                $fr      = trim($_GET['fragment']);
                $txt_sql = "SELECT `Kod` , `Ostatok`
                            FROM `Tovar`
                            WHERE `Tovar` LIKE '%" . $fr . "%' and `flg_del` = 0 and `Ostatok` > 0
                            ORDER BY `Tovar`.`Tovar` ASC";

                $sql     = mysql_query($txt_sql, $db);
                while ($srt_arr = mysql_fetch_array($sql)) {
                    $err = upd_tabdoc($id_doc, $srt_arr['Kod'], 0, 0, 0, 0, $srt_arr['Ostatok']);
                }
                //echo "Alert($err);"   ;
            }
            else { // заполняем строки остатками по складу
                $txt_sql = "SELECT `Kod` , `Ostatok` FROM `Tovar` WHERE `Ostatok` > 0 AND `flg_del` = 0 ORDER BY `Tovar`.`Tovar` ASC";
                $sql     = mysql_query($txt_sql, $db);
                while ($srt_arr = mysql_fetch_array($sql)) {
                    $err = upd_tabdoc($id_doc, $srt_arr['Kod'], 0, 0, 0, 0, $srt_arr['Ostatok']);
                }
            }

            echo ' window.open("doc_pereuchet.php?id_doc= ' . $id_doc . ' " ); ';
        }
        if ($vid_doc == 'Перемещение') {


            echo ' window.open("doc_vzv_sklad.php?id_doc= ' . $id_doc . ' " ); ';
        }

        //echo '</script>';
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
            //**********************baba-jaga@i.ua**********************
            function on_load(){
                    if(!check_user() )  return;
                    <?php opennewdoc(); ?>

                    var only =  <?php echo '"' . only_searh() . '"'; ?>  ;

                    document.frm_searh.only.checked =  only ;

                    // обновляем нижнюю таблицу при необходимости
                    top.content.location  = "newdoc_list.php?viddoc=pereuchet";

                }



                //**********************baba-jaga@i.ua**********************
                // submit
                function go_searh(){

                    return false ;
                }


                //**********************baba-jaga@i.ua**********************
                // выставляем хидден в зависимости от флажка
                function on_off_only(){
                    //alert("="+ document.frm_searh.only.checked);
                    if(document.frm_searh.only.checked){
                        document.frm_searh.txt_only.value = "checked";
                    }else{
                        document.frm_searh.txt_only.value = "no";
                    }
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
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  создать  " onclick="open_doc()"    >
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



            <div style=" padding: 10px 0px 0px 0px " >


                Фрагмент товара для переучета:
                <input type="text" name="fragment" id ="fragment"  size="19" maxlength="13" tabindex="0"  >
                <input type="hidden"   name="txt_only"  id ="txt_only">
                <input type="checkbox" name="only" onclick="javascript:on_off_only()" > <span style="font-size: 12px" >не заполнять документ товаром </span>


            </div>
        </form>

    </body>
</html>
