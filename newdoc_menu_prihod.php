<?php
require("header.inc.php");
include("milib.inc");
// **********************************baba-jaga@i.ua*******************************
// �������� ������ ����������� ������ (����������)
//


$nm_klient = '������� ����� �������� "����������� ������"';

//if ( isset($_GET['viddoc']) ) {
//    if($_GET['viddoc'] == '��������') $nm_klient = '������� ����� �������� "��������"';
//    if($_GET['viddoc'] == '�����������') $nm_klient = '������� ������ �� �������� �����';
//}




function opennewdoc() {
// ������� ����� �������� � ��������� ��� ���� �/� ������ onload
    global $db;
    $id_doc = -1;
    if (isset($_GET['newdoc'])) {
        $id_firm = $_GET['id_firm'];
        $vid_doc = $_GET['viddoc'];
        $id_doc  = add_newdoc($id_firm, $vid_doc);

        //if ($vid_doc == '�����������') {


            echo ' window.open("doc_prihod.php?id_doc= ' . $id_doc . ' " ); ';
       //}

        //echo '</script>';
    }
}



?>
<!--
������� ��� �����-�����
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

                    // ��������� ������ ������� ��� �������������
                    top.content.location  = "newdoc_list_prihod.php";

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
                <?php list_firm(); // ������� ������ ���� ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <?php list_viddoc(); // ������� ���� ���������� ?>
                &nbsp; &nbsp;&nbsp;&nbsp;
                <input type="button" name="kn_newdoc" id="kn_newdoc"  value="  �������  " onclick="open_doc()"    >
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
