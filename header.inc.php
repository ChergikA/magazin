<?php

ini_set('session.gc_maxlifetime', 36000);
ini_set('session.cookie_lifetime', 36000);
session_cache_expire(600);
ini_set("max_execution_time", '120');

//echo '=' . $_SERVER['DOCUMENT_ROOT'] ;
//ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'sess');
//ini_set('session.save_path', '/tmp');
//session_start();
//$_SESSION['test'] = 'test';



//session_set_cookie_params(36000 ,"/sess" ); // ����� ����� - 10 ����� , � ���� � ��������� ������
session_start(); // �.� �������� ������� ���������� �������������, �� ������ ��� ���������� ����� (�����)

//echo '=' . session_cache_expire() ;

//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors',  0);

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
$db = mysql_connect('localhost','root','111') OR DIE("��� ����������� � SQL");
/* ������� ���� ������. ���� ���������� ������ - ������� �� */
mysql_select_db("km",$db) or die(mysql_error());
mysql_query('SET NAMES CP1251');              // ��� ��������� ����� � ���� � ���



?>
