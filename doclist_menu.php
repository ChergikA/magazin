<?php
require("header.inc.php");
include("milib.inc");


//������� ������ ����
function list_firm_(){

    global $db;

    echo '<select id ="sel_firm" style="width: 140px " >';
    echo ' <option selected="selected" value=""> ��� �����</option>'; // � ��� ������ �������

    $txt_sql = "SELECT `id`,`name_firm` FROM `firms` ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {
        echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
    }
    echo '</select>';

}

//������� ������ ����� ����������
function list_viddoc_(){

    global $db;

    echo '<select id ="sel_doc" style="width: 140px " >';
    echo '<option selected="selected" value="">��� ����</option>'; // � ��� ������ �������

    $txt_sql =  "SELECT `Id`,`name_doc` FROM `VidDoc` ORDER BY `VidDoc`.`Id` ASC  ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {
        echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
    }
    echo '</select>';

}

//������� ������ ����� ����������
function list_statusdoc(){

    global $db;

    echo '<select id ="sost_doc" style="width: 130px ">';
    echo '<option selected="selected" value="">��� ����</option>'; // � ��� ������ �������

    $txt_sql =   "SELECT `idStatus`,`nameStatus` FROM `StatusDoc` ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {
        echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
    }
    echo '</select>';

}

//������� ������ �������
function list_avtordoc(){

    global $db;

    echo '<select id ="avtor_doc" style="width: 130px ">';
    echo '<option selected="selected" value="">�����</option>'; // � ��� ������ �������

    $txt_sql = "SELECT `id`,`full_name` FROM `users` WHERE `fl_del` = 0 ORDER BY `users`.`id` ASC ";
    $sql     = mysql_query($txt_sql, $db);
    while ($srt_arr = mysql_fetch_row($sql)) {
        echo '<option                     value="'.$srt_arr[0].'">'.$srt_arr[1].'</option>';
    }
    echo '</select>';

}

?>
<!--
������ ����������

-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

    <!--  ���� ���� �� ������� �� �����������, � ����������� ����� ��� � ���� -->
    <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>

<script type="text/javascript">
 //**********************baba-jaga@i.ua**********************
 function on_load(){

     if(!check_user() )  return;

     document.getElementById("f_date1").value = <?php echo '"' . date('d.m.y') . '"'; ?> ;
     document.getElementById("f_date2").value = <?php echo '"' . date('d.m.y') . '"'; ?> ;
     re_listdoc();

 }

  //**********************baba-jaga@i.ua**********************
 function open_calendar(btn){
    var  popupURL = 'calendar.php?btn='+btn;
    var popup = window.open(popupURL, null, 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, width=214, height=214 , left=200 ,top=210 ');
 }

 //**********************baba-jaga@i.ua**********************
function re_listdoc(){
    var dt1 = document.getElementById("f_date1").value;
    var dt2 = document.getElementById("f_date2").value;

    var str_loc =  "doclist_table.php?dt1="+dt1+"&dt2="+dt2 ;

    var f = document.getElementById("sel_firm");
    var firm = f.options[f.selectedIndex].value;

    if(firm != '' ) str_loc = str_loc + "&firm="+firm ;


    f = document.getElementById("sel_doc");
    var vid_doc = f.options[f.selectedIndex].value;

    if(vid_doc != '' ) str_loc = str_loc + "&vid_doc="+ vid_doc ;

    f = document.getElementById("sost_doc"); // ������
    var status_doc = f.options[f.selectedIndex].value;

    if(status_doc != '' ) str_loc = str_loc + "&status_doc="+ status_doc ;

    f = document.getElementById("avtor_doc"); //
    var avtor_doc = f.options[f.selectedIndex].value;

    if(avtor_doc != '' ) str_loc = str_loc + "&avtor_doc="+ avtor_doc ;

    var h_kod = document.getElementById("h_kod").value;
    if(h_kod != '' ) str_loc = str_loc + "&h_kod="+ h_kod ;

    var fragment = document.getElementById("fragment").value;
    if(fragment != '' ) str_loc = str_loc + "&fragment="+ fragment ;

    var ch = document.getElementById("check_viewtovar") ;
    if(ch.checked){
        str_loc = str_loc + "&viewtovar=1" ;
    }

   //alert(str_loc);

    top.content.location  = str_loc ;
}



</script>

    </head>
    <body onload="javascript:on_load()" >
        <!-- ������� �������������� ������ ����� ����� -->
        <table cellspacing='1' border='0' style=" width: 100%;  " >
            <col width="170px">
            <col width="180px">
            <col width="180px">
            <col width="80px">
            <col width="100px">
            <col >
            <tr> <!-- ������ ������ ����� -->
                <td> <h3 style="font-size: 1.3em; text-align: left " >������ ���������� </h3> </td>

                <td style="text-align: right" >
                    <?php list_firm_(); // ������� ������ ���� ?>
                </td>
                <td style="text-align: right">
                    c:
                    <input type="text"  style="width: 80px " id="f_date1" name="f_date1"  readonly="true" >
                    <button id="f_btn1"  onmouseup="javascript:open_calendar('f_date1')" >...</button>

                </td>
                <td colspan="2" style="text-align: right ">
                    ��:
                    <input type="text"  style="width: 80px " id="f_date2" name="f_date2"  readonly="true" >
                    <button id="f_btn2" onmouseup="javascript:open_calendar('f_date2')" >...</button>
                </td>
                <td  > &nbsp; </td>
            </tr>
            <tr > <!-- ������ ������ ����� -->
                <td style="text-align: right" > ���������: </td>
                <td style="text-align: right">
                    ���: <?php list_viddoc_(); // ������� ������ ����� ����������  ?>

                </td>
                <td style="text-align: right">
                    ���: <?php list_statusdoc(); // ������� ������ �������� ����������  ?>

                </td>
                <td colspan="2" style="text-align: right">
                    �����: <?php list_avtordoc(); // ������� ������ ������ ����������  ?>

                </td>

                <td>  &nbsp; </td>
            </tr>

            <tr style="text-align: right" > <!-- ������ ������ ����� -->
                <td colspan="3" >
                    �-��� �������: <input type="text" name="h_kod" id="h_kod" size="7" maxlength="13" >
                    &nbsp; &nbsp;  �������� �����:
                    <input type="text" name="fragment" id ="fragment"  size="8" maxlength="13">


                    <input type="checkbox"  id="check_viewtovar" title="���������� �����" > 


                </td>
                <td colspan="2">
                    <input type="button" name="button_ok" id="button_ok" onclick="javascript:re_listdoc()"  value="  ��������  "    >
                </td>
                <td>  &nbsp; </td>
            </tr>
        </table>



        <div style=" padding: 0px 0px 0px 0px " >
            <table cellspacing='0' class="Design5" >
                <col width="30px">
                <col width="75px">
                <col width="75px">
                <col width="160px">
                <col width="210px">
                <col width="39px">
                <col width="69px">
                <col >

                <thead>
                    <tr>
                        <th >&nbsp;</th> <!-- ��������� ���������-->
                        <th class="Odd">����</th>
                        <th >�����</th>
                        <th class="Odd">���</th>
                        <th >����������</th>
                        <th class="Odd">%</th>
                        <th> �����</th>
                        <th class="Odd">�������</th>
                    </tr>
                </thead>

            </table>
        </div>



    </body>
</html>
