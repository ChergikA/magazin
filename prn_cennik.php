<?php

//***********************************  baba-jaga@i.ua  **************************************



//*********************** baba-jaga@i.ua ********************************
// ��������� � ���� ��� ������ ������
function addinxml( $name, $cena, $cena_opt, $cena_kropt, $hkod, $strhkod ,  $kod1c, $break_page, $txt_kropt , $txt_opt) {

 $txtcenakropt = array();
 $txtcenaopt = array();
 
 $cls_sql = new cls_my_sql;
 
 
 $nmcennik = "������� �KANCLER.com�";
 
 if( cls_set::get_parametr('docPriemka', 'ZolotePero')  == 1 ){
    $nmcennik="�� ���������� �.�."; 
 }
 
 
 
 
 if($break_page){
    $ro = 'ro111';}  else {
    $ro = 'ro1';
    } 
  
    for ($j = 0; $j < 4; $j++) { 
       
       // ������� ���� �� �������� �� �� ���� �� ���
       if($nmcennik != "������� �KANCLER.com�"){ 
        $cena_kropt[$j]= '';
        $txt_kropt[$j] = '';
        $cena_opt[$j]  = '';
        $txt_opt[$j]   = '';
        $txtcenakropt[$j] = 'office:value-type="string"';
        $txtcenaopt[$j]   = 'office:value-type="string"';
       }else{
        
        $txtcenakropt[$j] = 'office:value-type="float" office:value="'.$cena_kropt[$j].'"';   
        if($txt_kropt[$j]==' ')$txtcenakropt[$j] = 'office:value-type="string"';
       
        $txtcenaopt[$j] = 'office:value-type="float" office:value="'.$cena_opt[$j].'"';   
        if($txt_opt[$j]==' ')$txtcenaopt[$j] = 'office:value-type="string"';
       }
      
    }
    
  $str = '
    <table:table-row table:style-name="'.$ro.'">
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="15"/>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$nmcennik.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$nmcennik.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$nmcennik.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$nmcennik.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce1"/>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$name[0].' <text:s/></text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$name[1].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$name[2].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$name[3].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce2"/>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:style-name="ce3" office:value-type="float" office:value="'.$cena[0].'" table:number-columns-spanned="1" table:number-rows-spanned="2">
      <text:p>'.$cena[0].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce8" '.$txtcenaopt[0].'>
      <text:p>'.$cena_opt[0].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$txt_opt[0].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11"/>
     <table:table-cell table:style-name="ce3" office:value-type="float" office:value="'.$cena[1].'" table:number-columns-spanned="1" table:number-rows-spanned="2">
      <text:p>'.$cena[1].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce8" '.$txtcenaopt[1].'>
      <text:p>'.$cena_opt[1].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$txt_opt[1].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11"/>
     <table:table-cell table:style-name="ce3" office:value-type="float" office:value="'.$cena[2].'" table:number-columns-spanned="1" table:number-rows-spanned="2">
      <text:p>'.$cena[2].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce8" '.$txtcenaopt[2].'>
      <text:p>'.$cena_opt[2].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$txt_opt[2].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11"/>
     <table:table-cell table:style-name="ce3" office:value-type="float" office:value="'.$cena[3].'" table:number-columns-spanned="1" table:number-rows-spanned="2">
      <text:p>'.$cena[3].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce8" '.$txtcenaopt[3].'>
      <text:p>'.$cena_opt[3].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$txt_opt[3].'</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro5">
     <table:covered-table-cell table:style-name="ce4"/>
     <table:table-cell table:style-name="ce9" '.$txtcenakropt[0].'>
      <text:p>'.$cena_kropt[0].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12" office:value-type="string">
      <text:p>'.$txt_kropt[0].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12"/>
     <table:covered-table-cell table:style-name="ce4"/>
     <table:table-cell table:style-name="ce9" '.$txtcenakropt[1].'>
      <text:p>'.$cena_kropt[1].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12" office:value-type="string">
      <text:p>'.$txt_kropt[1].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12"/>
     <table:covered-table-cell table:style-name="ce4"/>
     <table:table-cell table:style-name="ce9" '.$txtcenakropt[2].'>
      <text:p>'.$cena_kropt[2].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12" office:value-type="string">
      <text:p>'.$txt_kropt[2].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12"/>
     <table:covered-table-cell table:style-name="ce4"/>
     <table:table-cell table:style-name="ce9" '.$txtcenakropt[3].'>
      <text:p>'.$cena_kropt[3].'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce12" office:value-type="string">
      <text:p>'.$txt_kropt[3].'</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro6">
     <table:table-cell table:style-name="ce5" office:value-type="float" office:value="'.$kod1c[0].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$kod1c[0].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce10"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce5" office:value-type="float" office:value="'.$kod1c[1].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$kod1c[1].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce10"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce5" office:value-type="float" office:value="'.$kod1c[2].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$kod1c[2].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce10"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce5" office:value-type="float" office:value="'.$kod1c[3].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$kod1c[3].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce10"/>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce6" office:value-type="float" office:value="'.$strhkod[0].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$strhkod[0].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce6"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce6" office:value-type="float" office:value="'.$strhkod[1].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$strhkod[1].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce6"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce6" office:value-type="float" office:value="'.$strhkod[2].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$strhkod[2].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce6"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce6" office:value-type="float" office:value="'.$strhkod[3].'" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$strhkod[3].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce6"/>
     <table:table-cell table:number-columns-repeated="242"/>
    </table:table-row>';


    $str = cnv_($str);
    

    return$str;
}

//*********************** baba-jaga@i.ua ********************************
// ���� ��� ����
function prnxml(){
    //$xmlString = file_get_contents('hkod.xml');
    //$xml       = new SimpleXMLElement($xmlString);
    $handle = fopen('data/cennik_sr.fods', 'w');
    //addinxml($xml, $tip, $zn, $n);

    fwrite($handle, strfile('start') ) ;

    $name       = array();
    $cena       = array();
    $cena_opt   = array();
    $cena_kropt = array();
    $hkod       = array();
    $strhkod    = array();
    $kod1c      = array();
    $txt_kropt  = array();
    

    global $db;
    $txt_sql = "SELECT `Tovar`.`Tovar`,`Tovar`.`Kod`,`Tovar`.`Kod1C`, `Tovar`.`Price`, `cenniki`.`kvo_cennik`,"
    ." `Tovar`.`PriceOpt`, `Tovar`.`PriceIn` "
    . "FROM `cenniki`\n"
    . " LEFT JOIN `Tovar` ON `cenniki`.`id_tovar` = `Tovar`.`id_tovar` \n"
    . "WHERE (`cenniki`.`kvo_cennik` >0)\n"
    . " ";
    
     //echo $txt_sql . '<br>';

    $sql = mysql_query($txt_sql, $db);
    $rows = mysql_num_rows($sql);
    
    $ok = FALSE;
    if($rows>0) $ok = TRUE;
    $c=0;
    $kvo_cn = mysql_result($sql, $c, 4);
    $break_page = FALSE;
    $kvo_kol=-1;
    //echo ''.$kvo_cn;
    while ($ok) {
         for ($j = 0; $j < 4; $j++) {
            $name[$j]='';$cena[$j]=0;$hkod[$j]='';$strhkod[$j]='';$kod1c[$j]=''; 
            $cena_kropt[$j]=0;$txt_kropt[$j]='��.���';$cena_opt[$j]=0;$txt_opt[$j]='���'; 
         }
         
         for ($j = 0; $j < 4; $j++) {
             
             //$hk =  mysql_result($sql, $c, 1);
             //$hk = code_128($hk);//&lt;
             //$hk = str_replace('<', '&lt;', $hk);
             
             $nm = mysql_result($sql, $c, 0);
             $nm = str_replace('&', '', $nm);
             $nm = str_replace('>', '', $nm);
             $nm = str_replace('<', '', $nm);
             $nm = str_replace('#9675;', '', $nm);
             
             
            $name[$j] = $nm;
            $cena[$j] = mysql_result($sql, $c, 3);
            $hkod[$j] = '';//$hk;
            $strhkod[$j] = mysql_result($sql, $c, 1);
            $kod1c[$j] = mysql_result($sql, $c, 2);
            $cena_opt[$j] = mysql_result($sql, $c, 5);
            if($cena_opt[$j]==0){
               $cena_opt[$j]=' ';
               $txt_opt[$j] =' ';
            }
            
            $cena_kropt[$j] = mysql_result($sql, $c, 6);
            if($cena_kropt[$j]==0){
               $cena_kropt[$j]=' ';
               $txt_kropt[$j] =' ';
            }
            

            $kvo_cn = $kvo_cn - 1;
            if ($kvo_cn == 0) {
                $c++;
                if ($c == $rows) {
                    $ok = FALSE;
                    break;
                }else{
                    $kvo_cn = mysql_result($sql, $c, 4);
                }
            }
        }

        $kvo_kol++;
        if($kvo_kol == 8){
           $break_page = TRUE; 
           $kvo_kol=0;
        }
        $str = addinxml( $name, $cena, $cena_opt, $cena_kropt, $hkod, $strhkod ,  $kod1c, $break_page,$txt_kropt,$txt_opt);
        $break_page = FALSE;
        fwrite($handle, $str ) ;

    }
    


    //$xml->saveXML('data/hkod.xml');
    fwrite($handle, strfile('end') ) ;
    fclose($handle);
    chmod('data/cennik_sr.fods', 0777);
    
  //  prn_na_ftp();

      // ������ �� ubuntu
   
   // � ���� Ubuntu "������ ����������" ������ ������, ������� ��������� ������� ����� prn_hkod.fods
   // ���� ����  �� ��������� :
   // libreoffice -pt "TSC_TDP-225" "/home/san/webServer/km/data/hkod.fods"
   // � ������� ���. � ��������� ������


}

/*
   <Row ss:AutoFitHeight="0" ss:Height="15.75">
    <Cell ss:Index="2" ss:StyleID="s28"><Data ss:Type="String">   1.30</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="19.5">
    <Cell ss:Index="2" ss:StyleID="s26"><Data ss:Type="String">C41&lt;830032461434@</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="9">
    <Cell ss:Index="2" ss:StyleID="s27"><Data ss:Type="String">71791</Data></Cell>
   </Row>
 *
   <Row ss:AutoFitHeight="0" ss:Height="22.5">
    <Cell ss:Index="2" ss:StyleID="s28"><Data ss:Type="String">   1.30</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="19.5">
    <Cell ss:Index="2" ss:StyleID="s26"><Data ss:Type="String">C41&lt;830032461434@</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="9.75">
    <Cell ss:Index="2" ss:StyleID="s27"><Data ss:Type="String">71791</Data></Cell>
   </Row>
*/


function strfile($f){
   $start = '<?xml version="1.0" encoding="UTF-8"?>

<office:document xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2" office:mimetype="application/vnd.oasis.opendocument.spreadsheet">
 <office:meta><meta:initial-creator>san </meta:initial-creator><meta:creation-date>2014-01-11T14:51:27</meta:creation-date><dc:creator>san </dc:creator><dc:date>2014-09-29T16:11:35</dc:date><meta:print-date>2014-01-11T15:34:15</meta:print-date><meta:editing-cycles>5</meta:editing-cycles><meta:editing-duration>PT20M6S</meta:editing-duration><meta:generator>LibreOffice/3.5$Linux_X86_64 LibreOffice_project/350m1$Build-2</meta:generator><meta:document-statistic meta:table-count="1" meta:cell-count="36" meta:object-count="0"/></office:meta>
 <office:settings>
  <config:config-item-set config:name="ooo:view-settings">
   <config:config-item config:name="VisibleAreaTop" config:type="int">262</config:config-item>
   <config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaWidth" config:type="int">19651</config:config-item>
   <config:config-item config:name="VisibleAreaHeight" config:type="int">2939</config:config-item>
   <config:config-item-map-indexed config:name="Views">
    <config:config-item-map-entry>
     <config:config-item config:name="ViewId" config:type="string">view1</config:config-item>
     <config:config-item-map-named config:name="Tables">
      <config:config-item-map-entry config:name="����1">
       <config:config-item config:name="CursorPositionX" config:type="int">5</config:config-item>
       <config:config-item config:name="CursorPositionY" config:type="int">10</config:config-item>
       <config:config-item config:name="HorizontalSplitMode" config:type="short">0</config:config-item>
       <config:config-item config:name="VerticalSplitMode" config:type="short">0</config:config-item>
       <config:config-item config:name="HorizontalSplitPosition" config:type="int">0</config:config-item>
       <config:config-item config:name="VerticalSplitPosition" config:type="int">0</config:config-item>
       <config:config-item config:name="ActiveSplitRange" config:type="short">2</config:config-item>
       <config:config-item config:name="PositionLeft" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionRight" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionTop" config:type="int">0</config:config-item>
       <config:config-item config:name="PositionBottom" config:type="int">0</config:config-item>
       <config:config-item config:name="ZoomType" config:type="short">0</config:config-item>
       <config:config-item config:name="ZoomValue" config:type="int">100</config:config-item>
       <config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item>
       <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
      </config:config-item-map-entry>
     </config:config-item-map-named>
     <config:config-item config:name="ActiveTable" config:type="string">����1</config:config-item>
     <config:config-item config:name="HorizontalScrollbarWidth" config:type="int">215</config:config-item>
     <config:config-item config:name="ZoomType" config:type="short">0</config:config-item>
     <config:config-item config:name="ZoomValue" config:type="int">100</config:config-item>
     <config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item>
     <config:config-item config:name="ShowPageBreakPreview" config:type="boolean">false</config:config-item>
     <config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item>
     <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
     <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
     <config:config-item config:name="GridColor" config:type="long">12632256</config:config-item>
     <config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item>
     <config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item>
     <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
     <config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item>
     <config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item>
     <config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item>
     <config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item>
     <config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item>
     <config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item>
     <config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item>
     <config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item>
    </config:config-item-map-entry>
   </config:config-item-map-indexed>
  </config:config-item-set>
  <config:config-item-set config:name="ooo:configuration-settings">
   <config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item>
   <config:config-item config:name="LoadReadonly" config:type="boolean">false</config:config-item>
   <config:config-item config:name="UpdateFromTemplate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="GridColor" config:type="long">12632256</config:config-item>
   <config:config-item config:name="AllowPrintJobCancel" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item>
   <config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item>
   <config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item>
   <config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterSetup" config:type="base64Binary"/>
   <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsDocumentShared" config:type="boolean">false</config:config-item>
   <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterName" config:type="string"/>
   <config:config-item config:name="LinkUpdateMode" config:type="short">3</config:config-item>
   <config:config-item config:name="IsKernAsianPunctuation" config:type="boolean">false</config:config-item>
   <config:config-item config:name="SaveVersionOnClose" config:type="boolean">false</config:config-item>
   <config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item>
   <config:config-item config:name="ApplyUserData" config:type="boolean">true</config:config-item>
   <config:config-item config:name="RasterResolutionX" config:type="int">1000</config:config-item>
   <config:config-item config:name="RasterResolutionY" config:type="int">1000</config:config-item>
   <config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item>
   <config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item>
   <config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item>
   <config:config-item config:name="CharacterCompressionType" config:type="short">0</config:config-item>
   <config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item>
   <config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item>
  </config:config-item-set>
 </office:settings>
 <office:scripts>
  <office:script script:language="ooo:Basic">
   <ooo:libraries xmlns:ooo="http://openoffice.org/2004/office" xmlns:xlink="http://www.w3.org/1999/xlink"/>
  </office:script>
 </office:scripts>
 <office:font-face-decls>
  <style:font-face style:name="Times New Roman" svg:font-family="&apos;Times New Roman&apos;" style:font-family-generic="roman"/>
  <style:font-face style:name="Arial1" svg:font-family="Arial" style:font-family-generic="swiss"/>
  <style:font-face style:name="Arial" svg:font-family="Arial" style:font-family-generic="swiss" style:font-pitch="variable"/>
  <style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
  <style:font-face style:name="WenQuanYi Micro Hei" svg:font-family="&apos;WenQuanYi Micro Hei&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
 </office:font-face-decls>
 <office:styles>
  <style:default-style style:family="table-cell">
   <style:paragraph-properties style:tab-stop-distance="1.25cm"/>
   <style:text-properties style:font-name="Arial" fo:language="ru" fo:country="RU" style:font-name-asian="DejaVu Sans" style:language-asian="zh" style:country-asian="CN" style:font-name-complex="DejaVu Sans" style:language-complex="hi" style:country-complex="IN"/>
  </style:default-style>
  <number:number-style style:name="N0">
   <number:number number:min-integer-digits="1"/>
  </number:number-style>
  <number:currency-style style:name="N116P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">���.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N116">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">���.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N116P0"/>
  </number:currency-style>
  <number:number-style style:name="N108P0" style:volatile="true">
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N108">
   <number:text>-</number:text>
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N108P0"/>
  </number:number-style>
  <number:number-style style:name="N109P0" style:volatile="true">
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N109">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N109P0"/>
  </number:number-style>
  <number:number-style style:name="N110P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N110">
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N110P0"/>
  </number:number-style>
  <number:number-style style:name="N111P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N111">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N111P0"/>
  </number:number-style>
  <number:time-style style:name="N112">
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long"/>
  </number:time-style>
  <number:time-style style:name="N113" number:truncate-on-overflow="false">
   <number:hours/>
   <number:text>:</number:text>
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long"/>
  </number:time-style>
  <number:time-style style:name="N114">
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long" number:decimal-places="1"/>
  </number:time-style>
  <number:number-style style:name="N115">
   <number:scientific-number number:decimal-places="1" number:min-integer-digits="3" number:min-exponent-digits="1"/>
  </number:number-style>
  <style:style style:name="Default" style:family="table-cell">
   <style:table-cell-properties fo:padding="0.071cm" style:rotation-align="none"/>
   <style:text-properties style:font-name="Arial1" style:font-name-asian="WenQuanYi Micro Hei" style:font-name-complex="Arial1"/>
  </style:style>
  <style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N116"/>
  <style:style style:name="Heading" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Heading1" style:family="table-cell" style:parent-style-name="Heading">
   <style:table-cell-properties style:rotation-angle="90"/>
  </style:style>
 </office:styles>
 <office:automatic-styles>
  <style:style style:name="co1" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.341cm"/>
  </style:style>
  <style:style style:name="co2" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.498cm"/>
  </style:style>
  <style:style style:name="co3" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.898cm"/>
  </style:style>
  <style:style style:name="co4" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.199cm"/>
  </style:style>
  <style:style style:name="co5" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.953cm"/>
  </style:style>
  <style:style style:name="co6" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.258cm"/>
  </style:style>
   <style:style style:name="ro111" style:family="table-row">
   <style:table-row-properties style:row-height="0.263cm" fo:break-before="page" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro1" style:family="table-row">
   <style:table-row-properties style:row-height="0.263cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro2" style:family="table-row">
   <style:table-row-properties style:row-height="0.393cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro3" style:family="table-row">
   <style:table-row-properties style:row-height="0.788cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro4" style:family="table-row">
   <style:table-row-properties style:row-height="0.497cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro5" style:family="table-row">
   <style:table-row-properties style:row-height="0.499cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro6" style:family="table-row">
   <style:table-row-properties style:row-height="0.367cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ta1" style:family="table" style:master-page-name="PageStyle_5f_����1">
   <style:table-properties table:display="true" style:writing-mode="lr-tb"/>
  </style:style>
  <number:number-style style:name="N2">
   <number:number number:decimal-places="2" number:min-integer-digits="1"/>
  </number:number-style>
  <style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:diagonal-bl-tr="none" style:diagonal-tl-br="none" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:diagonal-bl-tr="none" style:diagonal-tl-br="none" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="9pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="9pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="9pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="16pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="16pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="16pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="16pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="16pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="16pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="7pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="7pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="7pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce6" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" fo:border-bottom="0.06pt solid #000000" style:diagonal-bl-tr="none" style:diagonal-tl-br="none" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border-left="none" style:direction="ltr" fo:padding="0.071cm" fo:border-right="none" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" fo:border-top="none" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="7pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="7pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="7pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce7" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:padding="0.071cm"/>
  </style:style>
  <style:style style:name="ce8" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:diagonal-bl-tr="none" style:diagonal-tl-br="none" fo:border="none" fo:padding="0.071cm" style:rotation-align="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce9" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties fo:padding="0.071cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce10" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="7pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="7pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="7pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce11" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:diagonal-bl-tr="none" style:diagonal-tl-br="none" fo:border="none" fo:padding="0.071cm" style:rotation-align="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="8pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="8pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="8pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce12" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties fo:padding="0.071cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="8pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="8pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="8pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce13" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:padding="0.071cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="7pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="7pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="7pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:page-layout style:name="pm1">
   <style:page-layout-properties style:first-page-number="continue" style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm"/>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm"/>
   </style:footer-style>
  </style:page-layout>
  <style:page-layout style:name="pm2">
   <style:page-layout-properties style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0.25cm" fo:border="2.49pt solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0">
     <style:background-image/>
    </style:header-footer-properties>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0.25cm" fo:border="2.49pt solid #000000" fo:padding="0.018cm" fo:background-color="#c0c0c0">
     <style:background-image/>
    </style:header-footer-properties>
   </style:footer-style>
  </style:page-layout>
  <style:page-layout style:name="pm3">
   <style:page-layout-properties style:num-format="1" style:print-orientation="portrait" fo:margin-top="1cm" fo:margin-bottom="0cm" fo:margin-left="1cm" fo:margin-right="0cm" style:scale-to="100%" style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0.9cm" fo:margin-right="1.9cm" fo:margin-bottom="0cm"/>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0.9cm" fo:margin-right="1.9cm" fo:margin-top="0cm"/>
   </style:footer-style>
  </style:page-layout>
 </office:automatic-styles>
 <office:master-styles>
  <style:master-page style:name="Default" style:page-layout-name="pm1">
   <style:header>
    <text:p><text:sheet-name>???</text:sheet-name></text:p>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer>
    <text:p>�������� <text:page-number>1</text:page-number></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="Report" style:page-layout-name="pm2">
   <style:header>
    <style:region-left>
     <text:p><text:sheet-name>???</text:sheet-name> (<text:title>???</text:title>)</text:p>
    </style:region-left>
    <style:region-right>
     <text:p><text:date style:data-style-name="N2" text:date-value="2014-09-29">00.00.0000</text:date>, <text:time>00:00:00</text:time></text:p>
    </style:region-right>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer>
    <text:p>�������� <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="PageStyle_5f_����1" style:display-name="PageStyle_����1" style:page-layout-name="pm3">
   <style:header style:display="false"/>
   <style:header-left style:display="false"/>
   <style:footer style:display="false"/>
   <style:footer-left style:display="false"/>
  </style:master-page>
 </office:master-styles>
 <office:body>
  <office:spreadsheet>
   <table:calculation-settings table:case-sensitive="false" table:use-regular-expressions="false"/>
   <table:table table:name="����1" table:style-name="ta1">
    <office:forms form:automatic-focus="false" form:apply-design-mode="false"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="ce7"/>
    <table:table-column table:style-name="co6" table:number-columns-repeated="242" table:default-cell-style-name="ce7"/>
    ';


  $end = '    
    <table:table-row table:style-name="ro1">
     <table:table-cell table:number-columns-repeated="257"/>
    </table:table-row>
    <table:table-row table:style-name="ro4" table:number-rows-repeated="1048567">
     <table:table-cell table:number-columns-repeated="257"/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:number-columns-repeated="257"/>
    </table:table-row>
   </table:table>
   <table:named-expressions/>
  </office:spreadsheet>
 </office:body>
</office:document>';

 if($f=='start')     return cnv_ ( $start) ;
 if($f=='end')       return cnv_ ( $end );

}



?>
