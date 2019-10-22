<?php

require_once ("../header.inc.php");
//include("milib.inc");

//idtov='+id_tovar + "&fix=" + flg + "&prc=" + prc
if (isset($_GET['fix'])) { // изменяем фиксировнный прц наценки 
    
    $flg   = $_GET['fix'];
    $idtov = $_GET['idtov'];
    $prc   = $_GET['prc'];
    
    if($flg != 'fix' ) $prc=0;
    
    $txt_sql = "UPDATE `Tovar` SET `fix_prc_rozn`=$prc,`fix_prc_opt`=$prc WHERE `id_tovar`=$idtov";
    $sql     = mysql_query($txt_sql, $db);
   
    print 'idtov=' . $idtov . "<br>" ;
    
}

?>