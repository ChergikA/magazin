<?php
require("header.inc.php");
include("milib.inc");
global $db;

$id_doc = -1;

$my_const = new cls_my_const();
$kvo_def = $my_const->kvo_def;

$h_kod =''; $info_tovar=""; $kvo   = $kvo_def ; $cena   = '' ;

if (isset($_GET['id_doc']) ) { // 
    $id_doc = $_GET['id_doc'];
    if (write_doc($id_doc)) {
        $info_tovar = "<a style=' color: #1864fc; text-decoration: underline; font-size: 11px; ' 
                        href='javascript: podbor_tovar();'> ��������� �� �����-����� </a>";
    }
}

$idstr = '0';//�� � �������� ��������� ��������

// ������ �������� &kvoprn=" + kvoprihod
if (isset($_GET['kvoprn'])) {
    // ���� ���������� ������� �����-����, ��������� � ����������
    $txt_sql = "SELECT `name` FROM `const` WHERE `kod` LIKE 'PrintHKod' ";
    $sql     = mysql_query($txt_sql, $db);
    $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
    if ($s_arr['name'] == 1) {


        $kvoprn = $_GET['kvoprn'];
        $kod1c  = $_GET['kod1c'];
        $cn     = $_GET['cena'];
        $idtov  = id_tovar('', $kod1c);
        $idstr = trim($kod1c);

        $txt_sql = 'TRUNCATE cenniki'; // ������� ���� ��������
        $sql     = mysql_query($txt_sql, $db);


        $txt_sql = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `tip_cn`,`cena`)
                VALUES ('" . $idtov . "', '" . $kvoprn . "', '0' , '0', '".$cn."');";
        $sql     = mysql_query($txt_sql, $db);
        require("prn_hkod.php");
        prnxml();
    }
    else {
        echo '!!! �� ���������� ������� �����-����';
    }
    //kod1c
}

// ����������� ��� ��� ���� ��� ����� ������
if (isset($_GET['check_price'])) { //  name="checkPrice" value="1" checked 
    $_SESSION['check_price'] = $_GET['check_price'];
}
if (isset($_SESSION['check_price'])) {
    $check_price = $_SESSION['check_price'];
} else {
    $check_price = '';
}

//echo '='.$check_price;

// ���� ������ ����� ���
if (isset($_GET['h_kod']) ) {

    $h_kod = $_GET['h_kod'];
    $kvo   = $_GET['kvo'];
    $cena  = $_GET['cena'];
    $txt_sql = "SELECT `Tovar`, `Price`,`Ostatok` FROM `Tovar` WHERE  `Kod1C` = '" . $h_kod . "' or `Kod` = '" . $h_kod . "' ";
    if ($kvo == '' or $kvo == '0') {                    //����� ��� ��� ����������
        //���������� ���� � ������

        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if ($s_arr['Tovar'] == NULL) {
             $info_tovar = "<a style=' color: red; text-decoration: underline; font-size: 14px; ' 
                        href='javascript: open_tovar_new();'> ��� ������ � ����� $h_kod. �������, ����� ������� ����� �����</a>"; 
            
            $h_kod      = '';
        }else $info_tovar = $s_arr['Tovar'] . "  ����:" . $s_arr['Price'] . "  ���.:" . $s_arr['Ostatok'];


    }else { // ������� � ����������  
        
        if( ($cena=='0' or  $cena == '') and ( $check_price != '' ) ){

            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            $cena = $s_arr['Price'] ;
            
            
        }else{// ������� � ���� ������� ������ � ��
              
           //������� ���� ������

            $sql     = mysql_query($txt_sql, $db);
            $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
            if ($s_arr['Price'] == NULL) {
            $info_tovar = "<a style=' color: red; text-decoration: underline; font-size: 14px; ' 
                        href='javascript: open_tovar_new();'> ��� ������ � ����� $h_kod. �������, ����� ������� ����� �����</a>"; 
                
                $h_kod      = '';
            }else {
                $id_doc = $_GET['id_doc'];
                $h_kod  = $_GET['h_kod'];
                $kvo    = $_GET['kvo'];
                $cena   = $s_arr['Price'];
                if(  $check_price != ''  ) $cena  = $_GET['cena'];
               // echo '=' .  viddoc($id_doc) ;
                $err = upd_tabdoc($id_doc , $h_kod , $cena , $kvo , 0 , $cena , $s_arr['Ostatok'], viddoc($id_doc) );
                if($err != '') $info_tovar = $err;
                //������� ���� ����� ����� �� ����� ����
                $h_kod ='';  $kvo   = $kvo_def ; $cena   = '' ;
            }
            

        }


    }
}



//"doc_chek.php?id_doc=" + iddoc + "&idstr=" + idstr + "&skidka=" + newskidka ;
//����������� ������������� ���� ����� ������ � ����������� ���
if (isset($_GET['newcena'])) {

    $newsk  = $_GET['newcena'];
    $id_str = $_GET['idstr'];
    $id_doc = $_GET['id_doc'];
    //$txt_sql =  "UPDATE `DocHd` SET `SkidkaProcent` = '". $newsk ."' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    //$sql     = mysql_query($txt_sql, $db);
    if (write_doc($id_doc)) {
        $txt_sql = " UPDATE `DocTab` SET `Cena` = '" . $newsk . "' WHERE `id` = '" . $id_str . "' ;";
        $sql     = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);


    }
}

//����������� ���������� ��������� ��� ����� ��� ������ ��� ��� ���������
if (isset($_GET['pometka'])) {

    $id_str = '';
    if (isset($_GET['idstr'])) $id_str = $_GET['idstr'];

    $pometka = $_GET['pometka'];
    $id_doc = $_GET['id_doc'];



    if(trim($id_str) == ''){
        $txt_sql = "UPDATE `DocHd` SET `Pometka` = '" . $pometka . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    }
        else {
        $txt_sql = " UPDATE `DocTab` SET `pometka` = '" . $pometka . "' WHERE `id` = '" . $id_str . "' ;";
    }


    $sql     = mysql_query($txt_sql, $db);

}


//��������� �� ������ ����
if (isset($_GET['newdatadoc'])) {


    $newdatadoc = $_GET['newdatadoc'];
    $id_doc     = $_GET['id_doc'];

    $txt_sql = "UPDATE `DocHd` SET `DataDoc` = '" . $newdatadoc . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
    $sql     = mysql_query($txt_sql, $db);

}


//"doc_priemka.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&newkod=" + kod  + "&idstr_=" + idstr_
// ����� ��������� � ������ ��� ������ ����� ������
if (isset($_GET['idstr_'])) {
    $id_str = $_GET['idstr_'];
    $id_doc = $_GET['id_doc'];
    $kod1c = $_GET['kod1c'];
    $newhkod = $_GET['newkod'];
    //$cn_in   = $_GET['cn_in_'];

    //echo 'newhkod = ' . $newhkod ;
    if (write_doc($id_doc)) {
        //���� ����� �� �������, ���� �� �������, ������ ����� ���
        $idt = id_tovar('', $newhkod);
        if ($idt == '') {
            $idt = id_tovar($newhkod);
        }
        

        if ($idt == '') { // ������ �������� ������ UPDATE `nk`.`Tovar` SET `Kod` = '45454556789' WHERE `Tovar`.`id_tovar` =1;
            $txt_sql = "UPDATE `Tovar` SET `Kod` = '$newhkod' WHERE `Kod1C` ='$kod1c';";
            $sql = mysql_query($txt_sql, $db);
        } else { // ����� �����, ������ ��� � ������ � ���� ������� ��� ��������
            //$txt_sql = " UPDATE `DocTab` SET `Tovar_id` = '" . $idt . "' WHERE `id` = '" . $id_str . "' ;";
            //$sql = mysql_query($txt_sql, $db);
            //$txt_sql = "UPDATE `Tovar` SET `PriceIn` = '$cn_in' WHERE `id_tovar` ='$idt';";
            echo '��� ���� ����� �������� � ���� ';
            
        }
        
    }
}


//document.location = "doc_chek.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
// �������� ������� ���������
if (isset($_GET['delstr'])) {
    $id_str = $_GET['delstr'];
    $id_doc = $_GET['id_doc'];

    delstrdoc( $id_doc , $id_str ) ;
}

//document.location = "doc_chek.php?id_doc=" + iddoc + "&prodano=no";
// ��������� ������ � ����������, ���� �������, ���� ������
$closedoc = '';
if (isset($_GET['prodano'])) {
    $prodano = $_GET['prodano'];
    $id_doc  = $_GET['id_doc'];

    if (write_doc($id_doc)) {

        if ($prodano == 'ok') {
            $idstatus = id_status('������');
        }
        else {
            $idstatus = id_status('������');
        }

        $txt_sql  = "UPDATE `DocHd` SET `statusDoc` = '" . $idstatus . "' WHERE `DocHd`.`id` = '" . $id_doc . "'; \n";
        $sql      = mysql_query($txt_sql, $db);
        savesumdoc($id_doc);
    }
    $closedoc = 'close';
}

// ���� ��������� ����� �������, ��� �� ����� �������� ���������
if (isset($_GET['id_doc'])) {
    $id_doc = $_GET['id_doc'];
    // ������� ������ ��� �����
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
    $data_doc =  datesql_to_str( $s_arr['DataDoc']);
    $nm_doc =  $s_arr['name_doc'] . ' �_' . $s_arr['nomDoc'] . ' ��:' . datesql_to_str( $s_arr['DataDoc']) ; //�������� ��� �_32 �� 28.11.2012
    $avtor  =  "�����: " . $s_arr['full_name'];
    $pometka = addslashes( $s_arr['Pometka']) ; // ���������� �������

    
    $radio1= 'checked="checked"' ; // �������� 
    $radio2= '';
    
    if( ! write_doc($id_doc)) {
        $nm_doc = $nm_doc . ' (��������)';
        $radio1= '';
        $radio2= 'checked="checked"'; // ������
    }     




}


if (isset($_GET['radio'])) {
    $radio = $_GET['radio'];
    if($radio=='Wr'){ //������
        $radio1= '';
        $radio2= 'checked="checked"'; // ������
        
    }  else { // ��������
        $radio1= 'checked="checked"' ; // �������� 
        $radio2= '';        
    }
}



//***********************************  baba-jaga@i.ua  ********************************************************
//������� ������ ���������
function str_view(){
    global $id_doc;
    global $db;
    global $radio1;
    if($id_doc==-1) return FALSE;


    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Kod1C`, `Tovar`.`Tovar`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Cena2`, `DocTab`.`Kvo2`, `DocTab`.`pometka`, "
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`timeShtamp` DESC ";
    
    
    if($radio1 != ''){ //����� ���������
        $txt_sql .= " LIMIT 0, 20";
    }

     //   color: #F93D00 ; /* �������  */
     //   color: #00f  /* �����  */

    $sql     = mysql_query($txt_sql, $db);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        $st = '';
        $razn =    $s_arr['Kvo'] - $s_arr['Kvo2']   ; //  ������� -������
        if($razn < 0){
            $st = ' style=" color: #F93D00 ;"';
        }elseif($razn > 0) {
            $st = ' style=" color: #00f ;"';
        }

        $prnkod = trim($s_arr['Kod']);
        if(strlen($prnkod) < 10 ){// �������� ������ ����
            $prnkod = '<a href = "javascript:;"  onclick="javascript:prnhkod('.'event' .','. "'" . $s_arr['Kod1C'] . "'" .','.  "'".  $s_arr['Kvo2'] .  "'". ','.  "'" . $s_arr['Kvo'] . "'" . ','.  "'" . $s_arr['Cena'] . "'" .  ' )" > <img src="images/b_print.png" border=0>' . $s_arr['Kod'] .' </a>';
            
        }
        
        $nomstr = '<a href = "javascript:;"  onclick="javascript:rehkod('.'event' .','. "'" . $s_arr['Kod1C'] . "'" .','.  "'".  $s_arr['Kod'] .  "'" .','.  "'".  $s_arr['idstr'] .  "'" .  ' )" > <img src="images/s_reload.png" border=0>' .  $s_arr['nomstr'] .' </a>';


        echo '<tr  id="'.$s_arr['Kod'].'" >';
        echo '<td>'. $nomstr .'</td>';
        echo '<th class="Odd">'. $prnkod .'</th>';
        echo '<th '. $st .' >'. $s_arr['Tovar'] .'</th>';
        echo '<td  class="Odd" '. $st .' >'. $s_arr['Kvo2'] .'</td>';  //������
        echo '<td '. $st .' >'. $s_arr['Kvo'] .'</td>';           // �������
        echo '<td class="Odd" '. $st .' >'. $razn .'</td>';

        echo '<td >'. $s_arr['Cena'] .'</td>';


        $pometka = '___';
        if(  trim($s_arr['pometka']) != '') $pometka = $s_arr['pometka'];
        echo '<td >            <a href="#"           onclick="javascript:show_editpometka(event,'. $s_arr['idstr'] .',' . "'" . $s_arr['pometka'] . "'" . ' )" > '. $pometka .' </a>   </td>';

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

        <script src="src/js/jscal2.js"></script>
        <script src="src/js/lang/ru.js"></script>
        <link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />

        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

        <script type="text/javascript">

            //��� ��������� � ������� ����� ��������
            var idstr_ =  <?php echo '"'. $idstr . '"' ; ?> ;
            

            //**********************baba-jaga@i.ua**********************
            // submit
            function go_searh(){
                return true ;
            }

            //**********************baba-jaga@i.ua**********************
            //������ �������� �����-����
            function prnhkod( event,kod1c,kvoprihod,kvo,cena ){
                if(kvoprihod<kvo)kvoprihod=kvo;
                if(kvoprihod<1) return;
                
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("prnbar");

                obj.style.width =  '300px' ;
                obj.style.top = MouseY +120 + 'px' ;
                obj.style.left = MouseX -60 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_kod_1c').value  = kod1c;
                document.getElementById('_cena').value  = cena;
                document.getElementById('_kolvo').value  = kvo;
                document.getElementById('_kolvo').focus();

                var iddoc = <?php echo $id_doc; ?> ;
                //document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;

            }
            // ��� ������� ����������� � ������������ ���� ��� ������ �������� � �������� ���-��
             function prn_et () {
                var kod1c =   document.getElementById('_kod_1c').value ; 
                var cena   =  document.getElementById('_cena').value ;
                var iddoc = <?php echo $id_doc; ?> ;
                var kvoprihod= document.getElementById('_kolvo').value;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;
            }
            
            //**********************baba-jaga@i.ua**********************
            //����� ����� ���� � ����
            function re_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                var h_kod = document.frm_searh.h_kod.value;
                var kvo   = document.frm_searh.kvo.value;
                var cena  = document.frm_searh.cena.value;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&h_kod=" + h_kod + "&kvo=" + kvo + "&cena=" + cena ;
            }
            
                        //**********************baba-jaga@i.ua**********************
            //����� ����� ���� ��� ������ �� �����
            function rehkod( event,kod1c,kod,idstr_ ){

                //alert("="+idstr_);
                    
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("rehkod");

                obj.style.width =  '300px' ;
                obj.style.top = MouseY +120 + 'px' ;
                obj.style.left = MouseX -60 + 'px' ;
                obj.style.visibility = "visible";
                document.getElementById('_kod_1c').value  = kod1c;
                document.getElementById('_kod').value  = kod;
                document.getElementById('idstr_').value  = idstr_;
                document.getElementById('_kod').focus();
                document.getElementById('_kod').select();

                var iddoc = <?php echo $id_doc; ?> ;
                //document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&kvoprn=" + kvoprihod  + "&cena=" + cena  ;

            }
            
             // ��� ������� ���������� ��� ����� ����� ���� ��� ������ � ������
             function re_rehkod() {
                var kod1c =   document.getElementById('_kod_1c').value ; 
                var kod   =   document.getElementById('_kod').value ;
                var iddoc = <?php echo $id_doc; ?> ;
                var idstr_= document.getElementById('idstr_').value;
                //var id_cn =  'priceIn' +  idstr_ ; 
                //alert(idstr_);
                //var cn_in_ = document.getElementById(id_cn ).value;
                //alert('cn='+cn_in_);
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&kod1c=" + kod1c + "&newkod=" + kod  + "&idstr_=" + idstr_   ;
            }

            //**********************baba-jaga@i.ua**********************
            //������ ������ ������ �� ����� ����� ����
            function otmena_h_kod(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc ;
            }

            //**********************baba-jaga@i.ua**********************
            //��������� ����������� ������ ��� �������������� ������������� ����
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
            //������ ��������� ���� �������������� ������ ���� ��� �������
            function hide_bar() {
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("winpometka").style.visibility="hidden";
                document.getElementById("prnbar").style.visibility="hidden";
                document.getElementById("rehkod").style.visibility="hidden";
            }



            //*********************baba-jaga@i.ua**********************
            // ���� �������������� ����������
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
            //����� ������������� ���� � ������
            function re_skidka_str(){
                ///alert('reskidka_str');
                var iddoc = <?php echo $id_doc; ?> ;
                document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden"

                var newskidka = document.getElementById('_edit').value ;
                var idstr     = document.getElementById('_idstr').value;
                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );

                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&idstr=" + idstr + "&newcena=" + newskidka ;
            }

            //*********************baba-jaga@i.ua**********************
            //���������� ������� � ��������� ����
            function re_pometka(){
                var iddoc = <?php echo $id_doc; ?> ;
                var newpometka = document.getElementById('_editpometka').value ;
                var idstr     = document.getElementById('_idstrp').value;
                //document.getElementById('h_kod').focus();
                //document.getElementById("winpometka").style.visibility="hidden"

                //alert('=' + document.frm_searh.skidka_doc.value + ' iddoc= ' + iddoc  );
                if(idstr == '') {
                   document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&pometka=" + newpometka ;
                }else{
                   document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&idstr=" + idstr + "&pometka=" + newpometka  ;
                }

            }

            //**********************baba-jaga@i.ua**********************
            //������ �������� ������
            function delstr(idstr ){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&delstr=" + idstr  ;
            }


            //**********************baba-jaga@i.ua**********************
            //������� ����� ��������� ��� � �������� �������
            function prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&prodano=ok";

            }

            //**********************baba-jaga@i.ua**********************
            // ������ ������� ��������� ��� � �������� ������
            function ne_prodano(){
                var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&prodano=no";

            }
            
            //**********************baba-jaga@i.ua**********************
             function open_tovar_new () { 
                window.open("tovar_new.php?new=new" ); 
            }

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                var h_kod =   <?php echo '"' . $h_kod . '"'; ?>;

                var closedoc =   <?php echo '"' . $closedoc . '"'; ?>;
                //alert('=='+closedoc);
                if(closedoc != '') window.close();

                if (idstr_  != 0 ) return;

                document.frm_searh.h_kod.value =  h_kod  ;
                document.frm_searh.kvo.value  = <?php echo '"'  . $kvo . '"'; ?>;
                document.frm_searh.cena.value  = <?php echo '"' . $cena . '"'; ?>;
                var check_price =  <?php echo '"'. $check_price . '"' ; ?> ;
                var cn = <?php echo '"' . $cena . '"'; ?>;
                var kv = <?php echo '"'  . $kvo . '"'; ?>;
                if( h_kod == '' ) document.getElementById('h_kod').focus();
                else if(kv == '' || kv == '0'  ) {
                    document.getElementById('kvo').focus() ;
                    document.getElementById('kvo').select() ;
                }else{
                    
                    if(check_price != ''){
                       document.getElementById('cena').focus() ;
                       document.getElementById('cena').select() ;  
                    }
                }

                plays();
               // hide_bar(); // �� ���������� ���� ����

            }
            
            //**********************baba-jaga@i.ua**********************
            // ������ ���� ��� ��������� ��� ���          
            function recheckprice(){
                
               var iddoc = <?php echo $id_doc; ?> ;
               var check = '';
                
                if(document.frm_searh.checkPrice.checked)  check = 'checked';
               document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&check_price=" + check ; 

            }
            
            //**********************baba-jaga@i.ua**********************
            // ��������� ������ �������� ��� ��������
            function reradio(tip){
                //alert(document.getElementById('radio1').value);
                //alert( tip);
                 var iddoc = <?php echo $id_doc; ?> ;
                document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&radio=" + tip ;
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
            // ������ ������ �������
            function closeWin(){
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


        </script>
    </head>
    <body onload="javascript:on_load()" >

        <!-- ������� ������� �������� -->
        <table cellspacing='3' border='0' style=" width: 100%;  margin: 0px " >
            <col >
            <col width="800px">
            <col >
            <tr>
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- ����� ������� �������� ������� -->
                <td> <!-- ������� ������� �������� ������� -->



                            <!-- ������� �������������� ������ ����� ����� -->
                            <table cellspacing='0' border='0' style=" width: 100%;  " >
                                <col width="190px">
                                <col width="180px">
                                <col width="190px">
                                <col width="100px">
                                <col width="80px">
                                <col >
                                <tr> <!-- ������ ������ ����� -->
                                    <td> <h3 style="font-size: 1.3em; text-align: left " > <?php echo  $nm_firm ; ?>  </h3> </td>
                                    <td colspan="2" > <h3 style="font-size: 1.3em; text-align: center" > <?php echo  $nm_doc ; ?>
                                        <input type="hidden" id="f_date1" name="f_date1" value= <?php echo "'" . $data_doc . "'"; ?> >
                                         <button id="f_btn1" onmouseup="javascript:openCal('f_btn1')" >...</button></h3>
                                    </td>
                                    <td colspan="3" > <h3 style="font-size: 1.1em; text-align: right " > <?php echo  $avtor ; ?>   </h3> </td>



                                </tr>
                           <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                                <tr> <!-- ������ ������ ����� -->
                                    <td colspan="3" > <h4> <?php echo  $nm_klient ; ?>  </h4> </td>
                                    <td  colspan="2" style="text-align: left" > 
                                        <input name="radio1" id="radio1" type="radio" onchange="javascript:reradio('Pr')" value="yes"  <?php echo  $radio1 ; ?>  > ��������
                                        <input name="radio1" id="radio1" type="radio" onchange="javascript:reradio('Wr')" value="no"   <?php echo  $radio2 ; ?>  > ��������
                                    </td>
                                    <td> <a href="#" onclick="javascript:prodano()" > <img src="images/btnDA.jpg" border=0> </a> </td>
                                </tr>
                                <tr> <!-- ������ ������ ����� ���� � ������ -->
                                   
                                    <td colspan="3"   >
                                        <h3 style="font-size: 1.2em; font-style: italic; font-weight: normal; " >
                                             <?php echo  $info_tovar ; ?>
                                        </h3>
                                    </td>
                                    <td colspan="2" style="text-align: left">
                                        <input type="checkbox" name="checkPrice"  id="checkPrice" onchange="javascript:recheckprice()" value="yes" <?php echo $check_price  ; ?> ; > &nbsp; ����������� ����
                                    </td>
                                    <td><a href="#" onclick="javascript:closeWin()" > <img src="images/btnClose.jpg" border=0> </a> </td>
                                </tr >
                                <tr  > <!-- ��������� ������ ����� -->
                                    <td colspan="3" >
                                        ��� ������: &nbsp; <input type="text" name="h_kod" id="h_kod" size="7" maxlength="19"
                                                                  tabindex="0" onchange="javascript:re_h_kod()" >
                                        &nbsp; &nbsp; &nbsp; �-��:
                                        <input type="text" name="kvo" id ="kvo"  style="width: 30px" maxlength="5"
                                               tabindex="1" onchange="javascript:re_h_kod()" >
                                       <input type="text" name="cena" id ="cena" style="width: 50px" maxlength="13"
                                              tabindex="1" onchange="javascript:re_h_kod()" >
                                        <input type="button" name="button_ok" id="button_ok"  onclick="javascript:re_h_kod()" value="  Ok  "    >
                                       <!-- <input type="button" name="button_re" id="button_re"  onclick="javascript:otmena_h_kod()" value="  ������  "    > -->

                                    </td>
                                    <td>  <h4> �����: </h4> </td>
                                    <td> <input type="text" readonly="true" name="sum_vsego" id ="sum_vsego" size="5" value= <?php echo '"' . sumdoc($id_doc) . '"' ; ?>  > </td>
                                    <td> <a href="#" onclick="javascript:ne_prodano()" > <img src="images/btnDel.jpg" border=0> </a>  </td>

                                </tr>
                         </form>
                            </table>






                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- ��������� ����� ��������� -->
                        <table cellspacing='0' border='0' class="Design5" >
                            <col width="65px">
                            <col width="140px">
                            <col width="280px">  <!-- ������������ -->
                            <col width="60px">
                            <col width="60px">
                            <col width="60px">
                            <col width="60px">
                            <col width="98px"><!-- pometka -->


                            <thead>
                                <tr>
                                    <td>�/�</td>
                                    <th class="Odd"> ���</th>
                                    <th>             ������������</th>
                                    <th  class="Odd"> �-�� <br> �������� </th>
                                    <th>             �-�� <br> ������� </th>
                                    <th  class="Odd">��������/<br>�������</th>
                                    <th >����</th>
                                    <th class="Odd" >�������</th>
                                    <th>&nbsp;</th>

                                </tr>
                            </thead>

                            <?php  str_view() ; ?>


                        </table>
                    </div>

                    <div>
                        <br>
                        <a href="#" onclick="javascript:show_editpometka(event, '' ,<?php echo "'" . $pometka . "'" ; ?> )" > ����������:</a>  <?php echo $pometka ; ?>

                    </div>

                </td> <!-- ����� ������� ������� �������� ������� -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- ������ ������� �������� ������� -->
            </tr></table> <!--����� ������� ������� �������� -->

            <!-- ����������� ���� ��� �������������� ������ � ������� -->
            <div id=win class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ����: <input type="text" name="_edit" id ="_edit" size="5" onchange="javascript:re_skidka_str()" >
                <input type="hidden" id ="_idstr">
            </div>

            <!-- ����������� ���� ��� �������������� ���������� -->
            <div id=winpometka class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ����������: <input type="text" name="_editpometka" id ="_editpometka"  onchange="javascript:re_pometka()" >
                <input type="hidden" id ="_idstrp">
            </div>

             <!-- ����������� ���� ��� ������ �������� -->
            <div id=prnbar class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ���-�� ��������: <input type="text" name="_kolvo" id ="_kolvo" size="5" >
                <input type="button" name="btn_prn" id="btn_prn"  onclick="javascript:prn_et()" value="  Ok  "    >
                
                <input type="hidden" id ="_kod_1c">
                <input type="hidden" id ="_cena">
                
            </div>
            <!-- ����������� ���� ��� ����� ��������� -->
            <div id=rehkod class=bar>
                <div align=right>
                <span style='cursor: pointer' title='�������' onclick='hide_bar()'>x</span>
                </div>
                ��������: <input type="text" name="_kod" id ="_kod" size="12" >
                <input type="button" name="btn_prn" id="btn_prn"  onclick="javascript:re_rehkod()" value="  Ok  "    >
                <input type="hidden" id ="idstr_">
                <input type="hidden" id ="_kod_1c">
                
                
            </div>


    </body>



    <script type="text/javascript">
        //<![CDATA[

        function updateFields(cal) {
            var iddoc = <?php echo $id_doc; ?> ;
            var date = cal.selection.get();
            if (date) {
                date = Calendar.intToDate(date);
                //document.forms['sales'].f_date1.value = Calendar.printDate(date, "%d-%m-%Y");
            }
            cal.hide();

            //alert('='+cal.selection.get() );
            document.location = "doc_pereuchet.php?id_doc=" + iddoc + "&newdatadoc=" + cal.selection.get() ;

        };

        var cal = Calendar.setup({
            onSelect: updateFields,
            showTime: false,
            align   : "R",
            //flat    : "calendar-container", // ID of the parent element
            firstDay : 1                     // ������ ���� ������ - �����������


        });

        function openCal(kn){

            if(kn=="f_btn1") cal.manageFields( "f_btn1", "f_date1", "%d.%m.%Y");
        }


        //]]>



    </script>


    <script type="text/javascript">
        //����� ���������� �������� ��� ������ JavaScript, � DOM ������ ���� ��������� ��������.
           var el = document.getElementById(idstr_);
           el.scrollIntoView(true);
    </script>

</html>
