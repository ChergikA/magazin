<?php
require("header.inc.php");
include("milib.inc");
global $db;

$alert_err = ''; // ����� ����� � ��������� �������� ������ ��� ������������ ����� ������
$readonly ='readonly="true"'; // ������� - ������ ������
$cnst = new cls_my_const();
$magazin = $cnst->magazin;

//
//
// ��������� ���� �� ���� ���� �������� ���� ����� ���� ����
if (isset($_GET['new'])) {

    $new = trim($_GET['new']);
    if ($new == 'new') {

        $nm_klient  = '������������ �������';
        $nm_klientfull = '';
        $h_kod      = '';
        $kl_okpo    = '';
        $kl_skidka  = '0';
        $kl_tel     = '';
        $kl_adr     = '';
        $kl_pometka = '';
        $date_edit  = date('d.m.y');
        $avtor      = name_user();
        $bonus      = '0';
        $dt_posetil = date('d.m.y');

        $okpo =  '';
        $inn  =   '';
        $nom_sv_nds =  '';
        $nm_magazin = $magazin ;


    }
    else { // �������� ������� ������ ��� �������

        $h_kod   = diskont_klienta();
        $txt_sql = "SELECT * FROM `Klient`  WHERE `diskont` = " . $h_kod;
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);


        $nm_klient  = $s_arr['name_klient'];
        $nm_klientfull = $s_arr['name_full'];
        $kl_kod     = $s_arr['kod'];
        $diskont    = $s_arr['diskont'];
        $kl_skidka  = $s_arr['Skidka'];
        $kl_tel     = $s_arr["telefon"];
        $kl_adr     = $s_arr['adres'];
        $kl_pometka = $s_arr['pometka'];
        $date_edit  = $s_arr['edit_time'];
        $avtor      = $s_arr['redaktor'];
        $bonus      = $s_arr['summ_nakopleno'];
        $dt_posetil = $s_arr['data_zakupki'];

        $dt_posetil = datesql_to_str($dt_posetil) ;

        $okpo =  $s_arr['OKPO'];
        $inn  =   $s_arr['INN'];
        $nom_sv_nds =  $s_arr['nomer_sv_NDS'];
        $nm_magazin = $s_arr['magazin'];

    }
}

// chekit = true � ����� ���� �����, ���� ����������
if (isset($_GET['new_kod'])) {
    $new = trim($_GET['new_kod']);

    // ������ ����� ���� ������ � ���� ������� � � 1�
    //	18289~~��� �������~~����(���)~~������~~�������~~�����~~�������~~����������~~����������~~�������-�����~~�������~~�������
    //   0         1           2         3         4      5       6         7           8             9            10     11
    //~~�����������(����)~~�����\��������~~��������~~������1~~������1~~������1~~������2~~������2~~������2~~������3~~������3~~������3~~������4~~������4~~������4
    //      12                 13            14        15       16       17       18       19        20        21      22       23      24       25        26

    $nm_klient    = trim($_GET['new_name']);
    $nm_full    = trim($_GET['fullname']);
    $okpo         = trim($_GET['okpo']); // OKPO         2
    $nomsvnds     = trim($_GET['nomsvnds']);
    $inn          =  trim($_GET['inn']);
    $kl_skidka         = trim($_GET['new_skidka']); // skidka     3
    $kl_tel            = trim($_GET['new_tel']); // telefone      4
    $kl_adr            = trim($_GET['new_adres']); // adres       5
    $kl_pometka        = trim($_GET['new_pometka']); // pometka   10
    $h_kod          = trim($_GET['h_kod']);    //diskont       11

    $magazin        =  trim($_GET['magazin']);
    if(trim($_GET['magazin'])=='') $magazin = $cnst->magazin;

    $dtposetil      = str_to_datesql( $_GET['dt_posetil']);

    $save_ok        = TRUE;
    if ( ($nm_klient == '') or ( strpos( $nm_klient , '�����������')>0 )  ) {
        $save_ok   = FALSE;
        $alert_err = '�� ������ ��� �������';
    }

    if($nm_full=='')$nm_full=$nm_klient;


    if ($save_ok) {

        $txt_sql = "SELECT `id_` FROM `Klient` WHERE `diskont` = '" . trim($h_kod) ."'" ;
        $sql     = mysql_query($txt_sql, $db);
        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
        if( $s_arr['id_'] == NULL ){ // ����� ������
           $txt_sql = "INSERT INTO `Klient` (`name_klient`,`name_full`)
                VALUES ('".$nm_klient."','".$h_kod."');";
           $sql = mysql_query($txt_sql, $db);
           $id_klient=mysql_insert_id(); // ���������� id ������ ��� ����������� ������
          } else {
           $id_klient = $s_arr['id_'];
          }

          if($h_kod==''){

              $h_kod= date('ymdHis') ;
          }

          //`id_``kod``name_klient``name_full``OKPO``Skidka``telefon`
          //`adres``INN``nomer_sv_NDS``pometka``diskont`
          //`data_zakupki` `redaktor` `magazin` `flg_edit`

          // ��������� ������ ���� ������ ��� ���������, ���� ��������� �����
          $txt_sql = "UPDATE `Klient` SET `name_klient` = '".$nm_klient."',
                                          `name_full` = '".$nm_full."',
                                          `OKPO` = '".$okpo."',
                                          `Skidka` = '".$kl_skidka."',
                                          `telefon` = '".$kl_tel."',
                                          `adres` = '".$kl_adr."',
                                          `INN` = '".$inn."',
                                          `nomer_sv_NDS` = '".$nomsvnds."',
                                          `pometka` = '".$kl_pometka."',
                                          `diskont` = '".$h_kod."',
                                          `redaktor` = '". name_user() ."',
                                          `data_zakupki` = '".$dtposetil."',
                                          `magazin` = '".$magazin."',
                                          `flg_edit` = '1'
                                          WHERE `Klient`.`id_` = ".$id_klient.";";
          $sql     = mysql_query($txt_sql, $db);
          $alert_err = '������ ���������';

    }
}

?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
         <title> <?php echo  $nm_klient ; ?> </title>

        <script src="src/js/jscal2.js"></script>
        <script src="src/js/lang/ru.js"></script>
        <link rel="stylesheet" type="text/css" href="src/css/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="src/css/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="src/css/steel/steel.css" />

        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>
        <script type="text/javascript">
            //**********************baba-jaga@i.ua**********************
            function check_it(){

               var n = document.forms['edit_klient'].ok_save.checked;
               //alert(n);
               return n;

            }

            //**********************baba-jaga@i.ua**********************
            function on_load(){
                var msg =  <?php echo '"' . $alert_err . '"' ; ?> ;
                if(msg=='������ ���������'){
                    closeWin();
                    return ;
                }

                if(msg != ''){
                    alert(msg);
                }
            }

        </script>
    </head>
    <body onload="javascript:on_load()" >


        <form name='edit_klient' onsubmit = "return check_it()">

            <table width=100% border=0 cellpadding=2 bordercolor='#FBF0DB' bgcolor ='#FBF0DB' >
                <!--//
                //-->

                <col width="120"  >
                <col>


                <tr >
                    <td   bgcolor='#FBF0DB' bordercolor='#FBF0DB'  >
                        <H3 align="left" >����������:</H3>
                    </td>
                    <td>
                        <input type="text" name="new_name" size="70px" maxlength="87" value=<?php echo "'" . $nm_klient . "'"; ?>  tabindex="0"  >
                    </td>
                </tr>
                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                        ������ ��������: <input type="text" size="54px" id="fullname" name="fullname" style="text-align: left"  value= <?php echo "'" . $nm_klientfull. "'"; ?> >
                    </td>
                </tr>

                <tr >
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td  bgcolor='#FBF0DB' bordercolor='#FBF0DB'>
                        �������: <input type="text" name="h_kod" size="8px" maxlength="13"  <?php echo  $readonly  ; ?>  value=<?php echo "'" . $h_kod  . "'"; ?>  >
                        <input type="hidden" name="new_kod" size="14" maxlength="13" value=<?php echo "'" . $new . "'"; ?> >
                        &nbsp;&nbsp;  &nbsp;&nbsp;
                        ������: <input type="text" name="new_skidka" size="8px" maxlength="3" value=<?php echo "'" . $kl_skidka . "'"; ?>  tabindex="1"  >
                        &nbsp;&nbsp;  &nbsp;&nbsp;
                        ���� ���������: <input type="text" name="dt_posetil" size="11px" maxlength="3"  readonly="true" value=<?php echo "'" . $dt_posetil . "'"; ?>   >
                    </td>
                </tr>

                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                        �������: <input type="text" name="new_tel" size="12px" maxlength="32" value=<?php echo "'" . $kl_tel . "'"; ?>  tabindex="2"  >
                        &nbsp;&nbsp;
                        ����� ��������: <input type="text" name="new_adres" size="31px" maxlength="32" value=<?php echo "'" . $kl_adr . "'"; ?>  tabindex="3"  >
                    </td>

                </tr>



                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                        ����:&nbsp;&nbsp; <input type="text" size="12px" name="okpo" id="okpo" style="text-align: left"  value=  <?php echo "'" . $okpo. "'"; ?> >
                        &nbsp;&nbsp;
                        ���: <input type="text" name="inn" size="11px" maxlength="32" value=<?php echo "'" . $inn . "'"; ?>  >
                        &nbsp;&nbsp;
                        �_��. ���: <input type="text" name="nomsvnds" size="12px" maxlength="32" value=<?php echo "'" . $nom_sv_nds . "'"; ?>   >
                    </td>

                </tr>

                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                        �������: <input type="text" size="12px" name="magazin" readonly="true" value=<?php echo "'" . $nm_magazin . "'"; ?>>
                         &nbsp;&nbsp;
                        �����: <input type="text" size="11px" name="avtor" readonly="true" value=<?php echo "'" . $avtor . "'"; ?>>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ����:&nbsp;<input type="text" size="15px" name="dttime" readonly="true" value=<?php echo "'" . $date_edit . "'"; ?>>
                    </td>

                </tr>

                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                    ����������: <input type="text" name="new_pometka" size="58px" maxlength="64" value=<?php echo "'" . $kl_pometka . "'"; ?>    >
                    </td>
                </tr>


                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB'>
                            &nbsp;&nbsp;
                    </td>



                </tr>

                <tr>
                    <td bgcolor='#FBF0DB' bordercolor='#FBF0DB'  ></td>
                    <td bgcolor='#FBF0DB' >

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="ok_save"  > ��� �����!
                        <button name="bt_new_klient" value="new_klient" type="submit" tabindex="8"  >��������</button>
                        &nbsp;&nbsp;
                        <button name="bt_clear_klient" value="clear" type="button" tabindex="9" onclick="javascript:closeWin()"  >��������</button>

                    </td>

                </tr>


            </table>

            <hr><hr>

        </form>


    </body>
</html>


<script type="text/javascript">
    //<![CDATA[

    function updateFields(cal) {

        var date = cal.selection.get();
        if (date) {
            date = Calendar.intToDate(date);
            //document.forms['sales'].f_date1.value = Calendar.printDate(date, "%d-%m-%Y");
        }
        cal.hide();

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
        if(kn=="f_btn2") cal.manageFields( "f_btn2", "f_date2", "%d.%m.%Y");
        if(kn=="f_btn3") cal.manageFields( "f_btn3", "f_date3", "%d.%m.%Y");
        if(kn=="f_btn4") cal.manageFields( "f_btn4", "f_date4", "%d.%m.%Y");
    }


    //]]>



</script>