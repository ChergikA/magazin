<?php
require("header.inc.php");
include("milib.inc");



if (isset($_SESSION['txt_user'])) {
    $txt_user = $_SESSION['txt_user'];
}
else {
    $txt_user = "";
}


if ( isset($_GET['txt_user']) ) {
    $txt_user = $_GET['txt_user'];
    $txt_pass = $_GET['txt_pass'];
    if(check_user($txt_user, $txt_pass)) {
        
        // ����� ���� ������� ����������
        $nm_file = cls_set::get_parametr('docPriemka', 'docPriemkaFile'); 
        
        //echo 'nm_f=' . $nm_file ;
        $txt_sql = "UPDATE `VidDoc` SET `php_file`='$nm_file' WHERE `nameIsXML`='priemka'";
        cls_my_sql::run_sql($txt_sql);
        
        
        
        $_SESSION['txt_user'] = $txt_user;
    }else { // �������� ����� ������. ������� ������ ���� ����
        if(isset($_SESSION['txt_user'])) unset ( $_SESSION['txt_user'] );
        echo '�� ������ ������';

    }
}

// ������� ����� � ���� � ��������� ��� ������
function check_user($txt_user, $txt_pass) {

    global $db;
    $loginOK = FALSE;

    $txt_sql = "SELECT `pas` FROM `users` WHERE `login` = '" . trim($txt_user) . "' LIMIT 0, 30 ";
    $sql     = mysql_query($txt_sql, $db) or die(mysql_error());

    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        if ($s_arr['pas'] == $txt_pass)
            $loginOK = TRUE;
    }

    return $loginOK;
}



?>


<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title>������� ��������</title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />


        <script type="text/javascript">

            //***********************************  baba-jaga@i.ua  *************************************************
            function on_load(){
                 top.header.location = "header.php"; // ���� ������ ���� � ��� ���� �� ���� ������ ��� ����
            }

            //***********************************  baba-jaga@i.ua  *************************************************
            // ��������� ����� �� ���� ������
            function user_pas(){
                var txt_user = document.getElementById("txt_user").value;
                if(txt_user.length < 1 ){
                    // window.alert("���� ������, �� ���� ������");
                    document.frm_menu.txt_user.focus();
                    return false;
                }else{ // ��������� ��� ��������� ������
                    //window.alert("name=" + txt_user + "����� �� �������" );
                    var txt_pass = document.getElementById('txt_pass').value;
                    if(txt_pass.length < 1) { //������ ������
                        document.frm_menu.txt_pass.focus();
                        return false;
                    }else{

                        return true; // ������ � ������ � �����

                    }
                }
            }

            //***********************************  baba-jaga@i.ua  ********************************************************
            // ���� ������������ ���� � ������ �� ������� ���������� �����
            // �������� �� �������� body � ��� ����� �� �������
            function open_html(name_menu){
               // alert("glob_u ="+ txt_user );
                top.header.location = "header.php"; // ���� ������ ���� � ��� ���� �� ���� ������ ��� ����

                //var name_user = top.header.name_user;

                //alert('=' + name_user + "=" );

                //if( name_user == ""   ){
                    //alert("glob_u ="+ txt_user );
               //     top.top_menu.location = "notUser.php";
             //     top.content.location  = "notUser.php";
               // }else{ // � ������������� ��� � �������
                   if(name_menu == 'list'){
                        top.top_menu.location = "list_menu.php";
                       // top.content.location  = "list_table.php";
                    }else if(name_menu == 'klients'){
                        top.top_menu.location = "klients_menu.php";
                       // top.content.location  = "klients_table.php?tip_top='klient'";
                    }else if(name_menu == 'new_doc'){
                        top.top_menu.location = "newdoc_menu.php";
                       // top.content.location  = "klients_table.php?tip_top='newdoc'";
                    }else if(name_menu == 'list_doc'){
                        top.top_menu.location = "doclist_menu.php";
                    }else if(name_menu == 'obmen'){
                        top.top_menu.location = "obmen_menu.php";
                    }else{ // ���� ���� ��� �� �������, ��������� �����-����
                        top.top_menu.location = "list_menu.php";
                    }

                }

            //}
        </script>

    </head>
    <body onload="javascript:open_html('')" >


        <div id ="sidebar">

            <h2><a href="javascript:open_html('list')">�����-����</a></h2>
            <h2><a href="javascript:open_html('klients')">����������</a></h2>
            <h2><a href="javascript:open_html('new_doc')">����� ��������</a></h2>
            <h2><a href="javascript:open_html('list_doc')">������ ����������</a></h2>
            <h2><a href="javascript:open_html('obmen')">����� �������</a></h2>


        </div>




        <div style="padding-left: 10px ; padding-top: 270px" >

            <form name='frm_menu' onsubmit="return user_pas()" >

                ��������: <br> <input type="text" name="txt_user" id ="txt_user"  size="14" maxlength="13" value=""  tabindex="0"  >  <br> <br>
                ������: <br>  <input type="password" name="txt_pass" id="txt_pass"  size="14" maxlength="13" value=""> <br><br>
                <center>
                    <input type="submit" name="submit" id="submit"  value="      ��      "    >
                </center>
            </form>
        </div>


    </body>
</html>