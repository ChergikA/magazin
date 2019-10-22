<?php
require("header.inc.php");
include("milib.inc");
include ("print.inc");

// проверка на ввод штрих кода, если введен заносим в сессию
if ( isset($_GET['h_kod']) ) {
    $h_kod = $_GET['h_kod'];
    $_SESSION['kod_tov_searh'] = $h_kod;
}

// проверка на ввод фрагмента, если введен заносим в сессию
if ( isset($_GET['fragment']) ) {
    $frgm = $_GET['fragment'];
    $_SESSION['frgm_tov_searh'] = $frgm;
}

//флажок только остатки передаем через скрытое поле
if ( isset($_GET['txt_only']) ) {
    $only = $_GET['txt_only'];
   // echo 'only='.$only;
    if( $only == 'no'  ) $_SESSION['only_searh'] = '';
    if( $only == 'checked'  ) $_SESSION['only_searh'] = $only;
}

if ( isset($_GET['prncennik']) ) {
//prncennik=1&k_tov=" + ktov

    if(strlen($_GET['k_tov']) > 3 ){

    //echo 'печать ценник для :' . $_GET['k_tov'] ;
    $prn = new prn();
    $prn->prn_cennik($_GET['k_tov']);
    }
}


$nm_tov = 'Наименование товара';
$k_tov = kod_tov();
if($k_tov!='') $nm_tov = nm_tovar ($k_tov);



// если в нижней таблице выбрали товар , то нижнюю табл не обновляем, чтоб не прыгало
$refresh_pricelist = 'y';
if ( isset($_GET['kod_tov']) ) {
    $k_tov = $_GET['kod_tov'];
    $_SESSION['pricelist_kod_tov'] = $k_tov ;
    $nm_tov = nm_tovar($k_tov) ;
    $refresh_pricelist = 'n';
}
$id_tov = '';
$k_tov = kod_tov();

if($k_tov!='') {
    $nm_tov = nm_tovar ($k_tov);
    $id_tov = id_tovar($k_tov);
}



?>
<!--
менюшка для прайс-листа
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title></title>
        <link href="vivat.css" rel="stylesheet" type="text/css" media="screen" />

 <!--
    <script src="mylib.js?v2.0"></script>
    Чтоб файл на клиенте не кэшировался, а подгружался хотяб раз в день
-->
    <script src="mylib.js?v<?php echo  date('d.m.y') ; ?>" ></script>



<script type="text/javascript">



 //**********************baba-jaga@i.ua**********************
 function on_load(){

       // alert('=');
        if(!check_user() )  return;

           var hkod = <?php echo '"' . kod_tov_searh() . '"'; ?>  ;

           if(hkod == ""){
               document.frm_searh.h_kod.value ="штрихкод";
           }else{
               document.frm_searh.h_kod.value = hkod;
           }

           var frgm = <?php echo '"' . frgm_tov_searh() . '"'; ?>  ;
           //alert("="+hkod+".");
           if(frgm == ""){
               document.frm_searh.fragment.value ="фрагмент";
           }else{
               document.frm_searh.fragment.value = frgm;
           }

           var only =  <?php echo '"' . only_searh() . '"'; ?>  ;

           document.frm_searh.only.checked =  only ;

           document.getElementById('h_kod').focus();

           // обновляем нижнюю таблицу
           <?php
            if($refresh_pricelist == 'y') echo 'top.content.location  = "list_table.php";';
           ?>
 }

//**********************baba-jaga@i.ua**********************
function cennik(){
    window.open("doc_cennik.php?iddoc=list&add=1");
}


  //**********************baba-jaga@i.ua**********************
  function on_focus(element){

           if(element == 'h_kod'){
               // alert(element);
               //alert("te-" +  document.getElementById('h_kod').value );
               var hkod = document.frm_searh.h_kod.value;
               if(hkod == "штрихкод") {
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
               if( frgm  == "фрагмент") {
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
                    document.frm_searh.h_kod.value = "штрихкод";
                }
            }else if( element == 'fragment' ){
                var frgm = document.frm_searh.fragment.value;
                if( frgm  == "") {
                    document.frm_searh.fragment.value = "фрагмент";
                }
            }else alert("element=" + element);
        }

    //**********************baba-jaga@i.ua**********************
    // выставляем хидден в зависимости от флажка
   function on_off_only(){
       //alert("="+ document.frm_searh.only.checked);
       if(document.frm_searh.only.checked){
           document.frm_searh.txt_only.value = "checked";
       }else{
          document.frm_searh.txt_only.value = "no";
       }
   }

    //**********************baba-jaga@i.ua**********************
    // submit
    function go_searh(){
      return true ;
    }

 //**********************baba-jaga@i.ua**********************
           function opentovar(kak){
                if(kak=='edit'){
                    var id_tov = <?php echo '"' . $id_tov  . '"' ; ?> ;

                    if( id_tov == '' ) {
                        alert("Нужно выбрать товар");
                        return;
                    }

                    window.open("tovar_new.php?new=view&id_tov=" +id_tov);
                }else{
                    window.open("tovar_new.php?new=new" );
                }//
            }



</script>

    </head>
    <body onload="javascript:on_load()" >

        <div id="menu" style=" padding: 13px 0px 0px 0px;  "  >
            <ul>
                <li> <a href="javascript: opentovar('new');">новый товар</a></li>
                <li> <a href="javascript: opentovar('edit');"> карточка товара </a> </li>
                <li><a href="javascript: alert('Нет доступа');">история товара</a></li>
                <li><a href="javascript:cennik()">этикетки / ценники</a></li>
            </ul>
            <br>
        </div>

        <div style=" padding: 0px 0px 0px 0px; font-size: 18px; color: #AA0000;
                     text-align: right;  width: 538px; /* background: #fc0; */" >

              <?php echo $nm_tov ?>
        </div>

        <div style=" padding: 9px 0px 0px 0px;  " >

            <form name ="frm_searh"  id="frm_searh" onsubmit="return go_searh()" >
                Поиск: &nbsp; <input type="text" name="h_kod" id="h_kod" size="9" maxlength="13"
                                     tabindex="0" style="color: #8f8888;" onfocus="javascript:on_focus('h_kod')"
                                                                           onblur="javascript:off_focus('h_kod')" >
                &nbsp; &nbsp; &nbsp;
                <input type="text" name="fragment" id ="fragment"  size="19" maxlength="13"
                       tabindex="0" style="color: #969696 " onfocus="javascript:on_focus('fragment')"
                                                            onblur="javascript:off_focus('fragment')" >
                <input type="hidden"   name="txt_only" >
                <input type="checkbox" name="only" onclick="javascript:on_off_only()" > <span style="font-size: 12px" >только остатки </span>
                <input type="submit" name="submit" id="submit"  value="  Найти  "    >
            </form>
        </div>



        <div style=" padding: 10px 0px 0px 0px " >
            <table cellspacing='0' class="Design5" >
                <col width="120px">
                <col width="170px">
                <col width="280px">
                <col width="60px">
                <col width="60px">
                <col width="55px">




                <thead>
                    <tr>
                        <th>код 1С</th>
                        <th class="Odd">штрих код</th>
                        <th>наименование товара</th>
                        <th  class="Odd">розница</th>
                        <th >опт</th>
                        <th  class="Odd" >остаток</th>
                        <th >&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

            </table>
        </div>



    </body>
</html>
