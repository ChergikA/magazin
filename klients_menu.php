<?php
require("header.inc.php");
include("milib.inc");


// �������� �� ���� ����� ����, ���� ������ ������� � ������
if ( isset($_GET['h_kod']) ) {
    $h_kod = $_GET['h_kod'];
    if(trim($h_kod) == '' ) $h_kod = '�������';
    $_SESSION['diskont_klienta'] = $h_kod;
}

// �������� �� ���� ���������, ���� ������ ������� � ������
if ( isset($_GET['fragment']) ) {

    $frgm = $_GET['fragment'];
    if(trim($frgm) == '' ) $_SESSION['list_klient_searh'] = '��������';
    else $_SESSION['list_klient_searh'] = $frgm;
}

// ��� �� ������ ������ ��� ����� ��������
$h_kod = diskont_klienta () ;


// ��� �� ������ ������ ��� ����� �������� �������
if( isset( $_SESSION['list_klient_searh']) ) {
        $frgm = trim( $_SESSION['list_klient_searh']) ;
    }else {
        $frgm = '��������' ;
}



$nm_klient = '������������ �������';

if($h_kod != '�������') $nm_klient = nm_klient ($h_kod);

$_SESSION['imenin'] = 'no';
if ( isset($_GET['imenin']) ) {
    //echo '������� ���� ������������';
    $_SESSION['list_klient_searh'] = '��������';
    $h_kod = '�������';
    $_SESSION['diskont_klienta'] = $h_kod;
    $nm_klient = '������������ �������';
    $_SESSION['imenin'] = 'ok';
}

// ���� � ������ ������� ������� ����� , �� ������ ���� �� ���������, ���� �� �������
$refresh_pricelist = 'y';
if ( isset($_GET['kod_tov']) ) {

    $d_kl = $_GET['kod_tov'];
    $_SESSION['diskont_klienta'] = $d_kl ;
    $nm_klient = nm_klient($d_kl) ;

    $h_kod = '�������';

    $refresh_pricelist = 'n';

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

            // ��� ��������� ���� ��������� ������
            var active = true;

            //**********************baba-jaga@i.ua**********************
            function on_load(){

                 if(!check_user() )  return;

                document.frm_searh.h_kod.value = <?php echo '"' . $h_kod . '"' ; ?> ;
                document.frm_searh.fragment.value =<?php echo '"' . $frgm . '"' ; ?>;
                document.getElementById('h_kod').focus();

                // ��������� ������ �������
                <?php
                if ($refresh_pricelist == 'y')
                    echo 'top.content.location  = "klients_table.php";';
                ?>

            }

            //**********************baba-jaga@i.ua**********************
            // ��� body
            function onfocus(){
                if(active == false) {
                    //var loc = new String( document.location );
                    //loc = loc.substr(0,loc.indexOf('&idstr=')); // ����� ������ �� #
                    //loc = loc.substr(0,loc.indexOf('#')); // ����� ������ �� #
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
                               if(hkod == "�������") {
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
                               if( frgm  == "��������") {
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
                                   document.frm_searh.h_kod.value = "�������";
                               }
                           }else if( element == 'fragment' ){
                               var frgm = document.frm_searh.fragment.value;
                               if( frgm  == "") {
                                   document.frm_searh.fragment.value = "��������";
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
                               if(diskont == '�������') {
                                   alert('�������� �������');
                               }else{
                                active=false;
                                window.open("klients_new.php?new=edit");
                               }
                           }else if(kak=='�������'){
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
                <li> <a href="javascript:open_klient('new')">����� ������</a></li>
                <li> <a href="javascript:open_klient('edit')"> �������� ������� </a> </li>
                <li><a href="javascript:open_klient('�������')">������� �������</a></li>
            </ul>
            <br>
        </div>

        <div style=" padding: 0px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: center ;  width: 538px; /* background: #fc0; */" >

                <?php echo $nm_klient ?>

        </div>

        <div style=" padding: 9px 0px 0px 0px;  " >

            <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                �����: &nbsp; <input type="text" name="h_kod" id="h_kod" size="9" maxlength="13"
                                     tabindex="0" style="color: #8f8888;" onfocus="javascript:on_focus('h_kod')"
                                                                           onblur="javascript:off_focus('h_kod')" >
                &nbsp; &nbsp; &nbsp;
                <input type="text" name="fragment" id ="fragment"  size="19" maxlength="13"
                       tabindex="0" style="color: #969696 " onfocus="javascript:on_focus('fragment')"
                                                            onblur="javascript:off_focus('fragment')" >
                <input type="hidden"   name="txt_only" >
                <input type="submit" name="submit" id="submit"  value="  �����  "    >
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
                        <td class="Odd">�������</td>
                        <th>������������</th>
                        <td  class="Odd">������</td>
                        <th >�������</th>
                        <th>�������</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>


    </body>
</html>
