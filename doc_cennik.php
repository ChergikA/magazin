<?php
require("header.inc.php");
include("milib.inc");
global $db;

$id_doc = 'list';



if ( isset($_GET['iddoc']) ) {
    $id_doc = $_GET['iddoc'];
}

// ��������� ����� � ���� ������
if ( isset($_GET['add']) ) {
   if($_GET['add'] == 1){ 
   // ��������� ����� � ���� ������ 
    add_cennic();
   }  else {
    //������� �������
    $txt_sql = 'TRUNCATE cenniki'; // ������� ���� ��������
    $sql     = mysql_query($txt_sql, $db);
    str_view_cennik();
    $id_doc = 'list';
    
   }
}


$my_const = new cls_my_const();
//$kvo_def = $my_const->kvo_def;

$info_tovar = "<a style=' color: #1864fc; text-decoration: underline; font-size: 11px; ' 
                        href='javascript: podbor_tovar();'> ��������� �� �����-����� </a>";

if ( isset($_POST['str']) ) { // ������ ������ ������

    $chto = $_POST['chto'];
    //echo '='.$chto;
    
    $txt_sql = 'TRUNCATE cenniki'; // ������� ���� ��������
    $sql     = mysql_query($txt_sql, $db);

   $str = $_POST['str'];
   //$str = iconv("UTF-8", "cp1251", $str);
   $str_arr = explode("^^",$str);
   $sumkvocn=0; $sumkvohlod=0;
   //echo 'kvo=' .count($str_arr)."<br> ";
   for ($i = 0; $i < count($str_arr)-1 ; $i++) {
    //echo $str_arr[$i]."<br> ";           //                                                            7    8     9      10
    //30171~~482000647666300059~~ ������ 20� 100 �/� "�" 059 "Me To You" ����.~~____~~____~~7~~false~~12~~false~~true~~false
    $zn_arr = explode("~~",$str_arr[$i]);
    $idtov = trim($zn_arr[0]);
    $hkod  = trim($zn_arr[1]);
    $nm_tov= trim($zn_arr[2]);

    $ed    = trim($zn_arr[3]);
    if($ed=='____')$ed='';

    $up    = trim($zn_arr[4]);
    if($up=='____')$up='';

    $kvohlod=0;
    $f=    trim($zn_arr[6]);
    if($f==='true')$kvohlod=$zn_arr[5];

    $kvocn=0;$tipcn='';
    $f=    trim($zn_arr[8]);
    if($f==='true'){$kvocn=$zn_arr[7] ; $tipcn='m' ; }
    $f=    trim($zn_arr[9]);
    if($f==='true'){$kvocn=$zn_arr[7] ; $tipcn='s' ; }
    $f=    trim($zn_arr[10]);
    if($f==='true'){$kvocn=$zn_arr[7] ; $tipcn='b' ; }

    //������� ��������� � ����
    $txt_sql = "SELECT `Tovar`,`name_in_cennik`, `Kod`,`ed_izm`,`v_upakovke` ,`vid_cennic`,`edit_time`  FROM `Tovar` WHERE `id_tovar` = " .$idtov ;
    $sql     = mysql_query($txt_sql, $db);
    $sql_arr = mysql_fetch_array($sql, MYSQL_BOTH);
    $upd=FALSE;
    if( trim($sql_arr['Tovar']) != $nm_tov  ){
        if( trim($sql_arr['name_in_cennik']) != $nm_tov  ) $upd=TRUE;
    }
    if( trim($sql_arr['Kod'])            != $hkod    ) $upd=TRUE;
    if( trim($sql_arr['ed_izm'])         != $ed      ) $upd=TRUE;
    if( trim($sql_arr['v_upakovke'])     != $up      ) $upd=TRUE;

    if( trim($sql_arr['vid_cennic'])     != $tipcn   ) {
        $edittime=$sql_arr['edit_time'];
        $txt_sql = "UPDATE `Tovar` SET `vid_cennic` = '".$tipcn."' , `edit_time` = '".$edittime."'  WHERE `Tovar`.`id_tovar` = " . $idtov ;
        $sqlu     = mysql_query($txt_sql, $db);
    }

    if($upd){
        $txt_sql = "UPDATE `Tovar` SET `Kod` = '".$hkod."',
                                `ed_izm` = '".$ed."',
                            `v_upakovke` = '".$up."',
                        `name_in_cennik` = '".$nm_tov."',
                              `redaktor` = '".  name_user() ."',
                               `magazin` = '".$my_const->magazin."',
                              `flg_edit` = '1' WHERE `Tovar`.`id_tovar` = " . $idtov ;
       $sqlu     = mysql_query($txt_sql, $db);
    }


    // ������� ����� ������ � �������
    if($kvocn+$kvohlod > 0){
        $txt_sql = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`, `kvo`, `tip_cn`)
                VALUES ('$idtov', '$kvohlod', '$kvocn', '1' , '".$tipcn."' );";
        $sql     = mysql_query($txt_sql, $db);
        $sumkvocn=$sumkvocn+$kvocn;
        $sumkvohlod=$sumkvohlod+$kvohlod;
    }

   } // ����� ����� �� ��������
   //if($sumkvocn>0) echo ' <script type="text/javascript">window.open("prn_cennik.php");</script>';
   
   
  // $chto = mal sr bol hk hk_cn
   
   if($chto == 'mal')  {include("prn/cennik_ml.php"); prnxml(); }
   if($chto == 'sr')   {include("prn/cennik_sr.php"); prnxml(); }
   if($chto == 'bol')  {include("prn/cennik_bl.php"); prnxml(); }
   if($chto == 'hk')   {include("prn/prn_hkod.php" ); prnxml(); }
   if($chto == 'hk_cn'){include("prn/prn_hkod.php" ); prnxml(); }
  
   
//   if($sumkvocn>0){
//       require_once("prn_cennik.php");
//      prnxml(); 
//   }elseif  ($sumkvohlod > 0) {
//        // ���� ���������� ������� �����-����, ��������� � ����������
//        $txt_sql = "SELECT `name` FROM `const` WHERE `kod` LIKE 'PrintHKod' ";
//        $sql     = mysql_query($txt_sql, $db);
//        $s_arr   = mysql_fetch_array($sql, MYSQL_BOTH);
//        if ($s_arr['name'] == 1) {
//
//            require_once("prn_hkod.php");
//            prnxml();
//            //echo '<script src="mylib.js?v14"</script> <br>';
//            //<script type="text/javascript">  prn_xls();  </script> ';
//        }
//    }

}

//***********************************  baba-jaga@i.ua  ********************************************************
//����� ������
 function echo_str($id_tov, $kvohkod, $kvocennik, $nomstr) {
    global $db;

    $txt_sql = "SELECT `Kod1C`,`Kod`,`Tovar`,`Price`,`PriceOpt`,`ed_izm`,`v_upakovke`,`vid_cennic`,`name_in_cennik`
        FROM `Tovar` WHERE `id_tovar` = " . $id_tov ;
    $sql     = mysql_query($txt_sql, $db);
    $s_arr = mysql_fetch_array($sql, MYSQL_BOTH);

    $nmTovar  = trim($s_arr['name_in_cennik']);
    if($nmTovar=='')$nmTovar=$s_arr['Tovar'];

    $odd = 'Odd'; $odd_='Odd_';
    if (($nomstr % 2) == 0) { //������� ������ �� 2
       $odd=''; $odd_='';
    }

    $ed = trim( $s_arr['ed_izm']);
    if( $ed == '' ) $ed='____';

    $up = trim($s_arr['v_upakovke']);
    if( $up == '' ) $up='____';

    $vid_cn = $s_arr['vid_cennic'];
    $ch_m='';$ch_s='';$ch_b='';
    if($vid_cn=='m') $ch_m = 'checked="checked"';
    if($vid_cn=='s') $ch_s = 'checked="checked"';
    if($vid_cn=='b') $ch_b = 'checked="checked"';


    echo '<tr> ';
    echo '<td class = "'.$odd.'">'.$nomstr.'</td>';
    echo '<th class = "'.$odd_.'lft"> '. $s_arr['Kod1C'] .'</th>';
    echo '<th class = "'.$odd_.'lft" colspan = "4" > <a href = "javascript:;" onclick = "javascript:show_rewin(event,'. "'".$id_tov."' , 'rename'" .' )" title = "�������� ������������ ��� �������" id = "rename'.$id_tov.'" > '.$nmTovar.'</a></th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" style = "text-align: center" ><input type = "text" id = "hkodkvo'.$id_tov.'" style="width: 38px" value = "'.$kvohkod.'" ></th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" ><input type = "checkbox" id = "hkodyes'.$id_tov.'" > </th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" style = "text-align: center" ><input type = "text" id = "cnkvo'.$id_tov.'" style="width: 38px" value = "'.$kvocennik.'" ></th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" > <input type = "checkbox" '.$ch_m.' id = "cnm'.$id_tov.'" onclick = "javascript:checkcennik(' . "'m','".$id_tov."'" . ' )" > </th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" > <input type = "checkbox" '.$ch_s.' id = "cns'.$id_tov.'" onclick = "javascript:checkcennik(' . "'s','".$id_tov."'" . ')" > </th>';
    echo '<th class = "'.$odd_.'lft" rowspan = "2" > <input type = "checkbox" '.$ch_b.' id = "cnb'.$id_tov.'" onclick = "javascript:checkcennik(' . "'b','".$id_tov."'" . ')"> </th>';
    echo '</tr>';

    echo '<tr>';
    echo '<th class = "'.$odd_.'notop" ></th>';
    echo '<th class = "'.$odd_.'lft" > <a href = "javascript:;" onclick = "javascript:show_rewin(event,' . " '".$id_tov."' , 'rehkod'" .' )" title = "�������� �����-���" id = "rehkod'.$id_tov.'" >'.$s_arr['Kod'].'</a> </th>';
    echo '<th class = "'.$odd_.'lft" > <a href = "javascript:;" onclick = "javascript:show_rewin(event,' . " '".$id_tov."' ,'reed' "   . ' )" title = "������������� ������� ���������" id = "reed'.$id_tov.'" >'.$ed.'</a> </th>';
    echo '<th class = "'.$odd_.'lft" > <a href = "javascript:;" onclick = "javascript:show_rewin(event,' . " '".$id_tov."' ,'reup' "   . ')" title = "������������� ���������� � ��������" id = "reup'.$id_tov.'" >'.$up.'</a> </th>';
    echo '<th class = "'.$odd_.'lft">'.$s_arr['Price'].'</th>';
    echo '<th class = "'.$odd_.'lft">'.$s_arr['PriceOpt'].'</th>';


    echo '</tr>';

    echo ' <script type="text/javascript"> arr_ktov.push('.$id_tov.')</script>';


    //<tr> <!--������ <thead> hkodyes066762-->
    //<td class = "Odd">1</td>
    //<th class = "Odd_lft"> 066762</th>
    //<th class = "Odd_lft" colspan = "4" > <a  onclick = "javascript:show_rewin(event, '066762' , 'rename' )" title = "�������� ������������ ��� �������" id = "rename066762" > �����-����. ��. �4 "Leo" L5030 "�����������" �� ����.</a></th>
    //<th class = "Odd_lft" rowspan = "2" style = "text-align: center" ><input type = "text" id = "hkodkvo066762" size = "2px" value = "7" ></th>
    //<th class = "Odd_lft" rowspan = "2" ><input type = "checkbox" id = "hkodyes066762" > </th>
    //<th class = "Odd_lft" rowspan = "2" style = "text-align: center" ><input type = "text" id = "cnkvo066762" size = "2px" value = "7" ></th>
    //<th class = "Odd_lft" rowspan = "2" > <input type = "checkbox" id = "cnm066762" onclick = "javascript:checkcennik('m','066762')" > </th>
    //<th class = "Odd_lft" rowspan = "2" > <input type = "checkbox" id = "cns066762" onclick = "javascript:checkcennik('s','066762')" > </th>
    //<th class = "Odd_lft" rowspan = "2"> <input type = "checkbox" id = "cnb066762" onclick = "javascript:checkcennik('b','066762')"> </th>
    //</tr>

    //<tr>
    //<th class = "Odd_notop" ></th>
    //<th class = "Odd_lft" > <a href = "#" onclick = "javascript:show_rewin(event, '066762' , 'rehkod' )" title = "�������� �����-���" id = "rehkod066762" >6909074905386</a> </th>
    //<th class = "Odd_lft" > <a href = "#" onclick = "javascript:show_rewin(event, '066762' ,'reed' )" title = "������������� ������� ���������" id = "reed066762" >��.</a> </th>
    //<th class = "Odd_lft" > <a href = "#" onclick = "javascript:show_rewin(event, '066762' ,'reup')" title = "������������� ���������� � ��������" id = "reup066762" >12/48</a> </th>
    //<th class = "Odd_lft"> 12.25 </th>
    //<th class = "Odd_lft"> 10.40 </th>


    //</tr>
}

//***********************************  baba-jaga@i.ua  ********************************************************
//������� ������ � ������� ��������
function str_view_cennik(){
    global $db;
    $txt_sql = "SELECT `id_tovar`,`kvo_hkod`,`kvo_cennik` FROM `cenniki` Where `kvo`>0";;
    $sql     = mysql_query($txt_sql, $db);
    $i=0;
    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        $i++;
        $idtov     = $srt_arr['id_tovar'];
        $kvohkod   = $srt_arr['kvo_hkod'];
        $kvocn     = $srt_arr['kvo_cennik'];
        echo_str( $srt_arr['id_tovar'] , $kvohkod, $kvocn, $i);
        
    }
    
    
    
}

//***********************************  baba-jaga@i.ua  ********************************************************
//������� �� ���� ���
function infocen_incennic(){
    global $db;
     $data_cen = $_GET['dt'];

     
   $txt_sql = "SELECT `log_price`.`id_tovar`,`Tovar`.`vid_cennic` 
         FROM log_price
         LEFT JOIN `Tovar` ON `log_price`.`id_tovar` = `Tovar`.`id_tovar` 
         WHERE `date_edit` > '$data_cen 00:00:00' and `date_edit` < '$data_cen 23:59:59'";

    $sql     = mysql_query($txt_sql, $db);
    // echo  ( '<br>' . $txt_sql . '<br>'  );
    $i=0;
    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        $i++;
        //$kod_tov = '"' . $srt_arr['Kod'] . '"';
        //echo_str( $srt_arr['id_tovar'] ,  $srt_arr['Ostatok'] , $i);
        // ������� ����� ������ � �������
        $idtov =  $srt_arr['id_tovar'];  
        $vidcn =  $srt_arr['vid_cennic'];
        if(trim($vidcn) == '')$vidcn='s';
        $txt_sqli = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`,`kvo`, `tip_cn`)
                                    VALUES ('$idtov',    '0'      ,    '1'      ,'1'  ,  '$vidcn' );";
        $sqli     = mysql_query($txt_sqli, $db);
        
    } 
     
}

//***********************************  baba-jaga@i.ua  ********************************************************
//������� ������ � �����-�����
function pricelist_incennic() {

    global $db;

    $txt_sql = "SELECT `id_tovar` , `Ostatok`, `vid_cennic` FROM `Tovar` ";
     

    $where = ' `flg_del` = 0 ';
    $limit = ' LIMIT 0 , 300 ';

    if (only_searh() != '') {
        $where = $where . ' AND `Ostatok` >0 ';
        $limit = '';
    }



    $kod_t    = kod_tov_searh();
    if ($kod_t == '��������')
        $kod_t    = '';
    $fragment = frgm_tov_searh();
    if ($fragment == '��������')
        $fragment = '';

    if ($kod_t != '') {
        $where .= " AND ((`Kod1C` LIKE '%" . $kod_t . "%') or (`Kod` LIKE '%" . $kod_t . "%'))";
        $limit = '';
    }
    elseif ($fragment != '') {
        if ($where !== '')
            $where = $where . ' AND ';
        $where = $where . " `Tovar` LIKE '%$fragment%' ";
        $limit = '';
    }

    if ($where !== '') {
        $where = ' WHERE ' . $where;
    }
    else {
        $limit   = ' LIMIT 0 , 300 ';
    }
    $txt_sql = $txt_sql . $where . " ORDER BY `Tovar` ASC" . $limit;
     //echo  ( '<br>' . $txt_sql . '<br>'  );
    $sql     = mysql_query($txt_sql, $db);
     //echo  ( '<br>' . $sql . '<br>'  );
    $i=0;
    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        $i++;
        //$kod_tov = '"' . $srt_arr['Kod'] . '"';
        //echo_str( $srt_arr['id_tovar'] ,  $srt_arr['Ostatok'] , $i);
        // ������� ����� ������ � �������
        $idtov =  $srt_arr['id_tovar'];  
        $kvo   =  $srt_arr['Ostatok'];
        $tipcn =  $srt_arr['vid_cennic'];
        $txt_sqli = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`,`kvo`, `tip_cn`)
                                    VALUES ('$idtov',    '$kvo',      '$kvo' ,  '$kvo' ,'$tipcn' );";
        $sqli     = mysql_query($txt_sqli, $db);
        
    }
}

//***********************************  baba-jaga@i.ua  ********************************************************
//  ������� ����� ������ � ���� �������
function add_cennic(){
    global $id_doc;
    global $db;
    //if($id_doc==-1) return FALSE;

    if($id_doc=='list') {
        pricelist_incennic();
        return TRUE;
    }

    if($id_doc=='info_cen') {
        infocen_incennic();
        return TRUE;
    }


    $txt_sql = "SELECT  `id_tovar` , `Kvo` , `Kvo2` , `vid_cennic` \n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$id_doc.")\n"
    . "ORDER BY `DocTab`.`timeShtamp` DESC ";



    $sql     = mysql_query($txt_sql, $db);
    //$i=0;
    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        //$i++;
        $idtov = $srt_arr['id_tovar'];
        $kvo   = $srt_arr['Kvo'];
        if($srt_arr['Kvo2'] > $kvo ) $kvo = $srt_arr['Kvo2'] ;
        $tipcn = $srt_arr['vid_cennic'];
        //$kod_tov = '"' . $srt_arr['Kod'] . '"';
        //echo_str( $srt_arr['id_tovar'] , $kvo, $i);
        
            // ������� ����� ������ � �������
        $txt_sqli = "INSERT INTO `cenniki` (`id_tovar`, `kvo_hkod`, `kvo_cennik`,`kvo`, `tip_cn`)
                                    VALUES ('$idtov',    '$kvo',      '$kvo' ,  '$kvo' ,'$tipcn' );";
        $sqli     = mysql_query($txt_sqli, $db);
        
    }

}



?>
<!--

-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title> �������� ������� </title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">

            // ��� ��������� ���� ���������
            //var arr_ktov = new Array('066762','000769','077762');
            var arr_ktov = new Array( );

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
            //������ ������� � ���� ��-�� �� ��������� . ������ ����� ���� ��/���
            function allcheck(){
                var ch = document.getElementById("allcheck") ;
                for(var i=0; i<arr_ktov.length; i++){
                    var id_el = 'hkodyes' + arr_ktov[i];
                    var element = document.getElementById( id_el );
                    element.checked = ch.checked ;

                }

            }

            //**********************baba-jaga@i.ua**********************
            //���������� ���� ������ ��� ���� ��������
            function allcheckcennik(razmer){
                var vid_ok = 'cn' + razmer;
                var m  = 'cnm';
                var s  = 'cns';
                var b  = 'cnb'
                for(var i=0; i<arr_ktov.length; i++){
                    var id_ = m + arr_ktov[i];
                    var element = document.getElementById( id_ );
                    element.checked = false ;
                    if(m==vid_ok) element.checked = true ;

                    id_ = s + arr_ktov[i];
                    element = document.getElementById( id_ );
                    element.checked = false ;
                    if(s==vid_ok) element.checked = true ;

                    id_ = b + arr_ktov[i];
                    element = document.getElementById( id_ );
                    element.checked = false ;
                    if(b==vid_ok) element.checked = true ;

                }

            }

             //**********************baba-jaga@i.ua**********************
            //���������� ���� ���������� ��������, �������� ��� ����� ������
            function re_kvo(){
                document.getElementById("win_kvo").style.visibility="hidden";

                var newvalue = document.getElementById('_editkvo').value ;
                var tip  = document.getElementById('_tip').value;

                for(var i=0; i<arr_ktov.length; i++){
                     var id_ = tip + arr_ktov[i];
                     document.getElementById( id_ ).value = newvalue ;
                }


            }

            //**********************baba-jaga@i.ua**********************
            //���������� ������ ������ ��� �������
            function checkcennik(razmer,kodtov){
                var vid_ok = 'cn' + razmer;
                var m  = 'cnm';
                var s  = 'cns';
                var b  = 'cnb';
                var id_ = m + kodtov;
                var element = document.getElementById( id_ );
                element.checked = false ;
                if(m==vid_ok) element.checked = true ;

                id_ = s + kodtov;
                element = document.getElementById( id_ );
                element.checked = false ;
                if(s==vid_ok) element.checked = true ;

                id_ = b + kodtov;
                element = document.getElementById( id_ );
                element.checked = false ;
                if(b==vid_ok) element.checked = true ;
            }



            //**********************baba-jaga@i.ua**********************
            //������ ��������� ���� �������������� ��������
            function hide_bar() {
                //document.getElementById('h_kod').focus();
                document.getElementById("win").style.visibility="hidden";
                document.getElementById("win_kvo").style.visibility="hidden";
            }


            //*********************baba-jaga@i.ua**********************
            // ���� �������������� ��������
            function show_rewin(event, kodtov , element){

                var html = document.documentElement;
                var body = document.body;

                var scrollTop = html.scrollTop || body && body.scrollTop || 0;
                scrollTop -= html.clientTop; // IE<8 alert("������� ���������: " + scrollTop);



                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("win");

                var width_ = 130;
                if(element=='rename') width_ = 370;

                obj.style.top = MouseY + scrollTop - 70 + 'px' ;
                obj.style.left = MouseX -160 + 'px' ;
                obj.style.width = width_ + 10 + 'px' ;
                obj.style.visibility = "visible";

                var edit =  document.getElementById(element+kodtov).text;
                //size="14"
                document.getElementById('_edit').style.width = width_ + 'px';
                document.getElementById('_edit').value     = edit;
                document.getElementById('_kodtov').value   = kodtov;
                document.getElementById('_element').value  = element;
                document.getElementById('_edit').select();
                document.getElementById('_edit').focus();

                // alert(document.getElementById('_edit').size);

                //document.getElementById(element+kodtov).scrollIntoView(true); // ���� � �������� ���������
                //alert("������� ���������: " + scrollTop + '  MouseY=' + MouseY );

            }

            //*********************baba-jaga@i.ua**********************
            // ���� �������������� ���������� ��������, �������� ��� ���� ��������
            function show_allkvo(event, tip){
                var MouseX = event.clientX + document.body.scrollLeft;
                var MouseY = event.clientY + document.body.scrollTop;
                var obj = document.getElementById("win_kvo");

                var width_ = 50;

                obj.style.top = MouseY - 40 + 'px' ;
                obj.style.left = MouseX -80 + 'px' ;
                obj.style.width = width_ + 10 + 'px' ;
                obj.style.visibility = "visible";

                var edit =  '';
                document.getElementById('_editkvo').style.width = width_ + 'px';
                document.getElementById('_editkvo').value     = edit;
                document.getElementById('_tip').value  = tip;
                document.getElementById('_editkvo').select();
                document.getElementById('_editkvo').focus();
            }


            //**********************baba-jaga@i.ua**********************
            //��������� �������� � ������
            function re_element_str(){

                document.getElementById("win").style.visibility="hidden";

                var newvalue = document.getElementById('_edit').value ;
                var kodtov   = document.getElementById('_kodtov').value;
                var element  = document.getElementById('_element').value;

                var id_el = element + kodtov;

               // alert(id_el);

                var el =  document.getElementById( id_el ) ;
                el.innerHTML = newvalue;
               // el.scrollIntoView(true); // ���� � �������� ���������

            }

            //**********************baba-jaga@i.ua**********************
            function prn(chto){
              $('ul.menu_body').slideToggle('medium');  
              var str='';
              var id_='';
              for(var i=0; i<arr_ktov.length; i++){
                  str+= arr_ktov[i] + '~~';
                id_ = 'rehkod' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).text + '~~';
                id_ = 'rename' + arr_ktov[i];
                //str+= encodeURIComponent( document.getElementById( id_ ).text) + '~~';
                str+=  document.getElementById( id_ ).text + '~~';
                id_ = 'reed' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).text + '~~';
                id_ = 'reup' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).text + '~~';
                id_ = 'hkodkvo' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).value + '~~';
                id_ = 'hkodyes' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).checked + '~~';
                id_ = 'cnkvo' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).value + '~~';
                id_ = 'cnm' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).checked + '~~';
                id_ = 'cns' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).checked + '~~';
                id_ = 'cnb' + arr_ktov[i];
                  str+=  document.getElementById( id_ ).checked + '^^';

              }
                // �������� ����� ����� 'one' ����� POST
              document.one.str.value  = str;
              document.one.chto.value = chto;
              document.one.submit();

            }
            

            //**********************baba-jaga@i.ua**********************
            function on_load(){

            }
            
            //**********************baba-jaga@i.ua**********************
            // ��������� ����� ������� �� �����-�����
            function podbor_tovar(){
                //alert('=' +iddoc + "  vid = " + viddoc );
                var iddoc = <?php echo "'" . $id_doc . "'" ; ?> ;
                window.open("podbor_tovar.php?id_doc=" + iddoc  );
                window.close();

            }

            //**********************baba-jaga@i.ua**********************
            // ������� ���� �������� 
            function clear_tab(){
                var iddoc = <?php echo "'" . $id_doc . "'" ; ?> ;
                document.location = "doc_cennik.php?id_doc=" + iddoc + "&add=0"  ;
                
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

                     <!-- ��� �������� �������� ������� �� ������  -->
                    <form name="one" id="one" method="post">
                        <input name="str"  id="str" type="hidden" >
                        <input name="chto" id="chto" type="hidden" >
                    </form>

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
                                    <td colspan="3" style="text-align: center " > <h3> ��������, �������, ����� </h3> </td>
                                    <td> &nbsp;</td>


                                    <td>  &nbsp; </td>
                                    <td> 
                                        <!--<a href="#" onclick="javascript:prn()" > <img src="images/btnPrint.jpg" border=0> </a>-->
                                        <div class="container" style="position:absolute ;  top: 5px;" >
                                            <img src="images/btnPrint.jpg" class="menu_head" />
                                            <ul class="menu_body">
                                                <li><a href="#" onclick="javascript:prn('mal')" >������� �����</a></li>
                                                <li><a href="#" onclick="javascript:prn('sr')"  >������� �������</a></li>
                                                <li><a href="#" onclick="javascript:prn('bol')" >������� �������</a></li>
                                                <li><a href="#" onclick="javascript:prn('hk')"  >����� ���</a></li>
                                                <li><a href="#" onclick="javascript:prn('hk_cn')"  >����� ��� + ����</a></li>
                                            </ul>
                                        </div>


                                    </td>
                                </tr>
                                <tr> <!-- ������ ������ ����� ���� � ������ -->
                                    <td colspan="3" >
                                        <h3 style="font-size: 1.2em; font-style: italic; font-weight: normal; " >
                                             <?php echo  $info_tovar ; ?>
                                        </h3>
                                    </td>
                                    <td> <h4>&nbsp;</h4> </td>
                                    <td> &nbsp;</td>
                                    <td><a href="#" onclick="javascript:clear_tab()" > <img src="images/btnDel.jpg" border=0> </a> </td>
                                </tr >

                            </table>


                   </form>



                    <div style=" padding: 10px 0px 0px 0px " >
                        <!-- ��������� ����� ��������� -->
                        <table cellspacing='0' border='0' class="Design5" >
                            <col width="35px">  <!-- �/� -->
                            <col width="100px"> <!-- ��� () -->
                            <col width="60px">  <!-- ������� -->
                            <col width="100px">  <!-- � ��. -->
                            <col width="60px">  <!-- ����. -->
                            <col width="60px">  <!-- ��� -->
                            <col width="50px">  <!-- ��-� �.� -->
                            <col width="27px">  <!-- v �.�. -->
                            <col width="50px">  <!-- ��-� �������� -->
                            <col width="27px"><!-- v ��� -->
                            <col width="27px"><!-- v �� -->
                            <col width="27px"><!-- v �� -->


                                <tr>
                                    <th>�/�</th>
                                    <th class="lft" style="text-align: center" >  ��� 1�</th>
                                    <th class="lft" colspan="4" style="text-align: center" > ������������ ��� �������</th>
                                    <th class="lft" colspan="2"style="text-align: center" > �����-��� </th>
                                    <th class="lft" colspan="4" style="text-align: center" > ������� </th>

                                </tr>

                                <tr >
                                    <th class="notop" >&nbsp;</th>
                                    <th class="lft" style="text-align: center" > �����-���</th>
                                    <th class="lft" style="text-align: center" >��.     </th>
                                    <th class="lft" style="text-align: center" > �. ��. </th>
                                    <th class="lft" style="text-align: center" >����.   </th>
                                    <th class="lft" style="text-align: center" >   ���  </th>

                                    <th class="lft" style="text-align: center" ><a href="#" onclick='javascript:show_allkvo(event,"hkodkvo")' title="���������� ���������� �������� �����-����"  id="rehkodkvo"  >  �-�� </a> </th>
                                    <th class="lft" > <input type="checkbox"  id="allcheck" onclick="javascript:allcheck()"   > </th>
                                    <th class="lft" style="text-align: center" ><a href="#" onclick="javascript:show_allkvo(event,'cnkvo')" title="���������� ���������� ��������"  id="recnkvo"  >  �-�� </a> </th>
                                    <th class="lft" ><a href='javascript:allcheckcennik("m")' title="��� ������� - �����"  id="recnm"  >  �.</a> </th>
                                    <th class="lft"><a href="javascript:allcheckcennik('s')" title="��� ������� - �������"  id="recns" >��.</a> </th>
                                    <th class="lft"><a href="javascript:allcheckcennik('b')" title="��� ������� - �������"  id="recns" >�.</a></th>

                                </tr>




                            <?php str_view_cennik() ; ?>


                        </table>
                    </div>

                    <div>
                        &nbsp;
                    </div>

                </td> <!-- ����� ������� ������� �������� ������� -->
                <td style="background: #f1f8fe;" >&nbsp;</td> <!-- ������ ������� �������� ������� -->
            </tr></table> <!--����� ������� ������� �������� -->

            <!-- ����������� ���� ��� �������������� ������ � ������� -->
            <div id=win class=bar>
                <div align=right>
                    <span style='cursor: pointer' title='�������' onclick='javascript:hide_bar()'>&nbsp; x &nbsp;</span>
                </div>

                    <input type="text" name="_edit" id ="_edit"  onchange="javascript:re_element_str()" >
                    <input type="hidden" id ="_kodtov">
                    <input type="hidden" id ="_element">
           </div>

                       <!-- ����������� ���� ��� �������������� ������ ���������� -->
            <div id=win_kvo class=bar>
                <div align=right>
                    <span style='cursor: pointer' title='�������' onclick='javascript:hide_bar()'>&nbsp; x &nbsp;</span>
                </div>

                    <input type="text" name="_editkvo" id ="_editkvo"  onchange="javascript:re_kvo()" >
                    <input type="hidden" id ="_tip">

           </div>



    </body>
</html>
