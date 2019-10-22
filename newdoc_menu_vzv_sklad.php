<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// для создания Возврата на слад
//


global $db;


// какой документ выбрали
$vid_doc = "Перемещение";
if ( isset($_GET['viddoc']) ) {
    $vid_doc = $_GET['viddoc'];

}

if($vid_doc == 'Перемещение')  $nm_klient = 'Создать новый документ "Возврат на склад"';
if($vid_doc == 'Комплектация') $nm_klient = 'Создать новый документ "Комплектация" <br>
                                            формирует набор из других товаров';
if($vid_doc == 'Раскомплектация') $nm_klient = 'Создать новый документ "Раскомплектация" <br>
                                            разукомплектовывает товар';



function opennewdoc() {
// создаем новый документ и открываем его окно ч/з скрипт onload
    global $db;
    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        $id_firm = $_GET['id_firm'];
        $vid_doc = $_GET['viddoc'];
        $id_doc  = add_newdoc($id_firm, $vid_doc);

        if ($vid_doc == 'Перемещение') {
            echo ' window.open("doc_vzv_sklad.php?id_doc= ' . $id_doc . ' " ); ';
        }elseif ($vid_doc == 'Комплектация') {
            echo ' window.open("doc_komplekt.php?id_doc= ' . $id_doc . ' " ); ';
        }elseif ($vid_doc == 'Раскомплектация') {
            echo ' window.open("doc_raskomplekt.php?id_doc= ' . $id_doc . ' " ); ';
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

                    // обновляем нижнюю таблицу при необходимости
                    top.content.location  = "newdoc_list_vzv_sklad.php";

                }



                //**********************baba-jaga@i.ua**********************
                // submit
                function go_searh(){

                    return false ;
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


            </div>

            <div style=" padding: 4px 0px 0px 0px;  " >
                &nbsp; &nbsp;&nbsp;&nbsp;


            </div>



            <div style=" padding: 10px 0px 0px 0px " >


                &nbsp; &nbsp;&nbsp;&nbsp;
            </div>
        </form>

    </body>
</html>
