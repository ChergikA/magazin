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
                        href='javascript: podbor_tovar();'> ��������� �� �����-����� </a>";
    }
}

// ���� ������ ����� ���
if (isset($_GET['h_kod']) ) {

    $h_kod = $_GET['h_kod'];
    $kvo   = $_GET['kvo'];
    if ($kvo == '' or $kvo == '0') {                    //����� ��� ��� ����������
        //���������� ���� � ������
        $txt_sql = "SELECT `Tovar`, `Price`,`Ostatok` FROM `Tovar` WHERE `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Tovar'] == NULL) {
            $info_tovar = "��� ������ � �����:" . $h_kod;
            $h_kod      = '';
        }else $info_tovar = $s_arr['Tovar'] . "  ����:" . $s_arr['Price'] . "  ���.:" . $s_arr['Ostatok'];


    }else {                                             // ������� � ���������� ������� ������ � ��
        //������� ���� ������
        $txt_sql = "SELECT `Price` FROM `Tovar` WHERE `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Price'] == NULL) {
            $info_tovar = "��� ������ � �����:" . $h_kod;
            $h_kod      = '';
        }else {
            $id_doc = $_GET['id_doc'];
            $h_kod  = $_GET['h_kod'];
            $kvo    = $_GET['kvo'];
            $skidka = $_GET['skidka'];
            $err = upd_tabdoc($id_doc , $h_kod , $s_arr['Price'] , $kvo , $skidka, 0 , 0 );
            if($err != '') $info_tovar = $err;
            //������� ���� ����� ����� �� ����� ����
            $h_kod ='';  $kvo   = $kvo_def ; $play='play';
        }

    }
}

//����������� ������ ���� � ����������� ���
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
//����������� ������ � ����� ������ � ����������� ���
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

//����������� ���������� ��������� ��� ��� ���������
if (isset($_GET['pometka'])) {
    $pometka = $_GET['pometka'];
    $id_doc = $_GET['id_doc'];
    $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    $sql     = mysql_query($txt_sql, $db);

}

//����������� ����� �����
if (isset($_GET['newnomdoc'])) {
    $newnomdoc = $_GET['newnomdoc'];
    $id_doc = $_GET['id_doc'];
    $txt_sql = "UPDATE `DocHd` SET `nomDoc` = '" . $newnomdoc . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    $sql     = mysql_query($txt_sql, $db);

}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
// �������� ������� ���������
if (isset($_GET['delstr'])) {
    $id_str = $_GET['delstr'];
    $id_doc = $_GET['id_doc'];

    delstrdoc( $id_doc , $id_str ) ;
    $play='play';
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";
// ��������� ������ � ����������, ���� �������, ���� ������
$closedoc = '';
if (isset($_GET['prodano'])) {
    $prodano = $_GET['prodano'];
    $id_doc  = $_GET['id_doc'];
   // $sum_v_kassu = 0;
   // $oplata      = 0;
    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('������');
     //       $sum_v_kassu = $_GET['sum_v_kassu'];
     //       $oplata      = $_GET['oplata'];//

        }
        else {
            $idstatus = id_status('������');
        }

        $txt_sql  = "UPDATE `DocHd` SET `statusDoc`   = '" . $idstatus . "'
                                    WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql      = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }


    $closedoc = 'close';
}

// ���� ��������� ����� �������, ��� �� ����� �������� ���������
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    // ������� ������ ��� �����
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
    $nm_doc =  $s_arr['name_doc'] . ' �_' . $s_arr['nomDoc'] . ' ��:' . datesql_to_str( $s_arr['DataDoc']) ; //�������� ��� �_32 �� 28.11.2012
    $avtor  =  "�����: " . $s_arr['full_name'];
    $pometka = addslashes( $s_arr['Pometka']) ; // ���������� �������

    $writedoc = write_doc($id_doc);
    if( ! $writedoc ) $nm_doc = $nm_doc . ' (��������)';
    
    
    $from_nkl = '';
    if($no_nds != 0){
       $from_nkl =' <li><a href="#" onclick="javascript:prn(\'prn_nkl\')"  >���������� ���������</a></li>
                   <li><a href="#" onclick="javascript:prn(\'save_nkl\')" >�������� ���������</a></li>';
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
//������� ������ ���������
function str_view(){
    global $id_doc;
    global $db;
    if($id_doc==-1) return FALSE;
    //                               <tr>
  //                                  <td>�/�</td>
  //                                  <th class="Odd"> ���</th>
  //                                  <th>             ������������</th>
  //                                  <th  class="Odd">�-��</th>
  //                                  <td>             ����</td>
  //                                  <th class="Odd">�����</th>
  //                                  <td>             ��. %</td>
 //                                   <th  class="Odd">����</th>
 //                                   <th>�</th>
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


            // ������������ ������ ��� ������ �� jQuery
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
            //����� ������ � ���������
            function re_skidka(){
                //alert('reskidka');

                var iddoc = <?php echo $id_doc; ?> ;
                var newskidka = document.frm_searh.skidka_doc.value;

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&newskidka=" + newskidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //����� ������ � ������
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
            //����� ����� ���� � ����
            function re_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                var skidka= document.frm_searh.skidka_doc.value;
                document.location = "doc_schet.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo + "&skidka=" + skidka ;
            }

            //**********************baba-jaga@i.ua**********************
            //������ ������ ������ �� ����� ����� ����
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_schet.php?id_doc=" + iddoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //��������� ����������� ������ ��� �������������� ������
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
            //������ ��������� ���� �������������� ������
            function hide_bar() {
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("winpometka").style.visibility="hidden";
                document.getElementById("winnomdoc").style.visibility="hidden";
            }

            //*********************baba-jaga@i.ua**********************
            //���� �������������� ����������
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
            //���� �������������� ������
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
            //���������� ������� � ��������� ����
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                document.getElementById('h_kod').focus();
                document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
            }

            //*********************baba-jaga@i.ua**********************
            //�������� ����� ���-�� � ��������� ����
            function re_nomdoc(){
                var iddoc      = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;
                var writedoc   = <?php echo "'". $writedoc . "'" ; ?> ;

                document.getElementById('h_kod').focus();
                document.getElementById("winnomdoc").style.visibility="hidden";

                if(writedoc != '1') {
                    alert("����� �������� ����� ����� ���������. ���������.");
                    return;
                }
                if(sum_oplata > 0){
                    alert("���� ������ �� �����. ����� �������� ����� ����� ���������");
                    return;
                }

                var newnomdoc = document.getElementById('_editnomdoc').value ;

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_schet.php?id_doc=" + iddoc + "&newnomdoc=" + newnomdoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //������ �������� ������
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_schet.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }


            //**********************baba-jaga@i.ua**********************
            //������� ����� ��������� ��� � �������� �������
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;

                var sumdoc = document.getElementById("sum_vsego").value ;

               if(sumdoc == 0){ alert('���� ������.'); return; }
               if(sumdoc > sum_oplata){ alert('����� ��������� ��������� ����� ������.'); return; }

                document.location = "doc_schet.php?id_doc=" + iddoc + "&prodano=ok" ;

            }

            //**********************baba-jaga@i.ua**********************
            // ������ ������� ��������� ��� � �������� ������
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                var sum_oplata = <?php echo $sumoplata ; ?> ;

                if(sum_oplata > 0){ alert('���� �������, ��� ������ �������.'); return; }

                if(confirm('������� ����?')) {
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
 
                    
                


               // hide_bar(); // �� ���������� ���� ����
                //alert('c:  '+ document.referrer); // � ����� ������� ������

            }
            
                        //**********************baba-jaga@i.ua**********************
            // ��������� ����� ������� �� �����-�����
            function podbor_tovar(){
                //alert('=' +iddoc + "  vid = " + viddoc );
                var iddoc = <?php echo $id_doc; ?> ;
                window.open("podbor_tovar.php?id_doc=" + iddoc );
                window.close();

            }

            //**********************baba-jaga@i.ua**********************
            // �� ���� ��������� ��� �������
            function plays() {
              var snd = new Audio("images/ok.wav");

               snd.preload = "auto";

                 snd.load();
                 snd.play();

            }

            //**********************baba-jaga@i.ua**********************
            // ������ ������ ���������
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

        <!-- ������� ������� �������� -->
        <table cellspacing='3' border='0' style=" width: 100%;   " >
            <col >
            <col width="800px">
            <col >
            <tr>
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- ����� ������� �������� ������� -->
                <td> <!-- ������� ������� �������� ������� -->


                    <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                        <!-- ������� �������������� ������ ����� ����� -->
                        <table cellspacing='0' border='0' style=" width: 100%;  " >
                            <col width="170px">
                            <col width="200px">
                            <col width="190px">
                            <col width="100px">
                            <col width="80px">
                            <col >
                            <tr> <!-- ������ ������ ����� -->
                                <td> <h3 style="font-size: 1.3em; text-align: left " > <?php echo $nm_firm; ?>  </h3> </td>
                                <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" >
                                    <a href="#" onclick="javascript:show_editnomdoc(event, <?php echo "'" . $nomerdoc . "'" ; ?> )" >   <?php echo $nm_doc; ?>  </a>
                                    </h3>
                                </td>
                                <td colspan="3" > <h3 style="font-size: 1.1em; text-align: right " > <?php echo $avtor; ?>   </h3> </td>
                            </tr>
                            <tr> <!-- ������ ������ ����� -->
                                <td colspan="3" > <h4> <?php echo $nm_klient; ?>  </h4> </td>

                                <td> <h4> ������: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_oplata" id ="sum_oplata" size="5" value= <?php echo '"' . $sumoplata . '"'; ?>  > </td>
                                <td> <a href="#" onclick="javascript:prodano()" > <img src="images/btnOk.jpg" border=0> </a> </td>
                            </tr>
                            <tr> <!-- ������ ������ ����� ���� � ������ -->
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
                                                <li><a href="#" onclick="javascript:prn('prn_chet')" >���������� ����</a></li>
                                                <li><a href="#" onclick="javascript:prn('save_chet')">�������� ����</a></li>
                                                <?php echo $from_nkl ; ?> 

                                            </ul> 
                                            <a id="dwnl_nakl"  href='data/nakladna.fods' download></a>
                                            <a id="dwnl_schet" href='data/schet.fods'    download></a>

                                            
                                    </div>
                                </td>
                            </tr>
                            <tr> <!-- ��������� ������ ����� -->
                                <td colspan="2" >

                                    ��� ������: &nbsp; <input type="text" name="h_kod" id="h_kod" size="11" maxlength="19"
                                                              tabindex="0" onchange="javascript:re_h_kod()" >
                                    &nbsp; &nbsp; &nbsp; �-��:
                                    <input type="text" name="kvo" id ="kvo"  size="3" maxlength="13"
                                           tabindex="1" onchange="javascript:re_h_kod()" >


                                </td>

                                <td> <h4 style="float: left; /* ��������� ������ ���� ��� ����� �������*/" > ������: </h4>
                                    <input type="text" name="skidka_doc" id ="skidka_doc" size="8" onchange="javascript:re_skidka()" style="float: left;"  value= <?php echo '"' . $skidka_doc . '"'; ?> >
                                <h4> % </h4></td>

                                <td> <h4> �����: </h4> </td>
                                <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php echo '"' . sumdoc($id_doc) . '"'; ?>  > </td>
                                <td> <a href="#" onclick="javascript:ne_prodano()" > <img src="images/btnClear.jpg" border=0> </a>  </td>

                            </tr>
                        </table>


                    </form>



                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- ��������� ����� ��������� -->
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
                                    <td>�/�</td>
                                    <th class="Odd"> ���</th>
                                    <th>             ������������</th>
                                    <th  class="Odd">�-��</th>
                                    <td>             ����</td>
                                    <th class="Odd">�����</th>
                                    <td>             ��. %</td>
                                    <th  class="Odd">����</th>
                                    <th>&nbsp;</th>

                                </tr>
                            </thead>

                            <?php str_view() ; ?>


                        </table>
                    </div>

                    <div>
                        <br>
                        <a href="#" onclick="javascript:show_editpometka(event, <?php echo "'" . $pometka . "'" ; ?> )" > ����������:</a>  <?php echo $pometka ; ?>

                    </div>

                </td> <!-- ����� ������� ������� �������� ������� -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- ������ ������� �������� ������� -->
            </tr></table> <!--����� ������� ������� �������� -->

            <!-- ����������� ���� ��� �������������� ������ � ������� -->
            <div id=win class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ������: <input type="text" name="_edit" id ="_edit" size="5" onchange="javascript:re_skidka_str()" > %
                <input type="hidden" id ="_idstr">
            </div>

            <!-- ����������� ���� ��� �������������� ���������� -->
            <div id=winpometka class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ����������: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >

            </div>

                        <!-- ����������� ����� ���-�� -->
            <div id=winnomdoc class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ����� �����: <input type="text" name="_editnomdoc" id ="_editnomdoc"  onchange="javascript:re_nomdoc()" >

            </div>


    </body>
</html>
