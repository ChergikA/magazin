<?php


//echo '=' . session_save_path()  ;

//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'../sess/');
//session_set_cookie_params(36000 ,"/sess" ); // ����� ����� - 10 ����� , � ���� � ��������� ������
//session_start(); // �.� �������� ������� ���������� �������������, �� ������ ��� ���������� ����� (�����)

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 1);

// ����� ���������� �/� �����
//
//
                 // �/� global - �������� ����������� � ��
//
 // ����� ������ �� ����� ����� ������� ���� �����������

/*
 * ������� ������ ������� �����
 * ����� ������ � �������� ������
 */


//echo 'asdf';

//!@$ // ��� ���������� �������� ������ - ������ �����������

/* ������� ���������� */
//$db = mysql_connect('Localhost','root','111') OR DIE("��� ����������� � SQL");
/* ������� ���� ������. ���� ���������� ������ - ������� �� */
//mysql_select_db("Vivat",$db) or die(mysql_error());
//mysql_query('SET NAMES CP1251');              // ��� ��������� ����� � ���� � ���



?>

<html>
<head>
<title>���������� ���� ��������� �����������</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<!-- <meta http-equiv="pragma" content= "no-cache"> -->

</head>

<!--
    rows rows="60, *, 60" 1-� ������ 60 ������ ��� ��������� 3-�  -60
-->
<!-- -->

<frameset rows="78, *, 35" frameborder="0" framespacing="0" >
  <frame src="header.php" noresize name="header" scrolling="no" > <!-- ����� -->
  <frameset cols="190px, *">  <!-- ��� ������� -->
    <frame src="leftMenu.php" noresize scrolling="no"> <!-- ����� ������� -->
        <frameset rows="120, *">
            <frame src="list_menu.php"  noresize name="top_menu" >  <!-- �������������� ���� -->
            <frame src="php_info.php" noresize name="content">
         </frameset>
  </frameset>
  <frame src="footer.php" noresize name="footer" >
</frameset>

<noframes>
��� ��������� ����� ����� ����������, ����� ��� ������� ����������� ������. ��� �� ������ ���� �����, ������ ���
������� �� ������������ ������. ��� ������ ��� ���� �� � ��� ����� ������ ������ � ��� ����� ���� ��� �������. ���� �
���������� �������� ���� �������� ��������� �������.<br><br>
� �������� Opera ���� ����� � ���� "�����������" --> "������� ���������" --> "��������� ��� �����..." --> "���" � ���
��������� ������� "�������� ������". ����� ����� ���� ������������� ��� ��������.<br><br>
� ��������� ������� ��������� IE � Mozilla FireFox ��������� ������� ��������� ��������.
</noframes>

</html>