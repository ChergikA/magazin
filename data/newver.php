<?php

//********** 23-04-17 ����������� ��������� ���������� � ��� ������� 
function upd_mysql() {
//    
//    $txt_sql = "SHOW COLUMNS FROM  `Klient` LIKE  'telefon33'";
//if(! cls_my_sql::const_sql($txt_sql)){
//    echo '�������� �������';
//}else echo 'ok �������';
// ������� ��� ���� ���������, ��������������� � ������������
    
$txt_sql = "SHOW COLUMNS FROM  `Tovar` LIKE  'fix_prc_rozn'"; // �������� �� ������������� �������
    if (!cls_my_sql::const_sql($txt_sql)) {
        $txt_sql = "ALTER TABLE `Tovar` 
        ADD `fix_prc_rozn` DECIMAL(4,2) NOT NULL DEFAULT '0' COMMENT '������������� ������� ������� ��������� ����', 
        ADD `fix_prc_opt`  DECIMAL(4,2) NOT NULL DEFAULT '0' COMMENT '������������� ������� ������� ������� ����';";
        cls_my_sql::run_sql($txt_sql);
    }    
    
    
save_log(" <h3>��������!</h3> ", "� ��������� <<������� ������>> "
        . "<br> ��������� �����������   "
        . "<br> ����������� ������� ������� �� �����."
        . "<br> ���������� ����� ������� �� �����"
        . "<br> ������� �� �����: cfif4.1c@gmail.com");

}
?>
