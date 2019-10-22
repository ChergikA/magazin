<?php


//*********************** baba-jaga@i.ua ********************************
// добавляем в файл хмл нужные секции
function addinxml($num, $kod1c, $kod, $tovar, $cena, $kvo , $ed) {

  $sum = sprintf("%.2f", $kvo * $cena );
  
      $ro = 'ro6';
    if(strlen($tovar)> 43) {
        $ro = 'ro9';
    }
    
  $str = '    <table:table-row table:style-name="'.$ro.'">  
     <table:table-cell table:style-name="ce5" office:value-type="string" >
      <text:p>'.$num.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce14" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$tovar.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce14"/>
     <table:table-cell table:style-name="ce25" office:value-type="string">
      <text:p>'.$ed.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce5" office:value-type="string">
      <text:p>'.$kvo.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce27" office:value-type="float" office:value="'.$cena.'">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce27" office:value-type="float" office:value="'.$sum.'">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce32"/>
     <table:table-cell table:style-name="ce5" office:value-type="string" >
      <text:p>'.$num.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce14" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$tovar.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce14"/>
     <table:table-cell table:style-name="ce25" office:value-type="string">
      <text:p>'.$ed.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce5" office:value-type="string">
      <text:p>'.$kvo.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce27" office:value-type="float" office:value="'.$cena.'">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce27" office:value-type="float" office:value="'.$sum.'">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce36" table:number-columns-repeated="1007"/>
    </table:table-row>';  
    
    $str = cnv_($str);
    

    return $str;
}

//*********************** baba-jaga@i.ua ********************************
// откр зап сохр
function prnxml($id_doc, $f_name){

    if($f_name == '') $f_name = 'cennik_sr'; // исторически чтоб не переделывать батник
    $handle = fopen("data/$f_name.fods", 'w');

    $no_simvols = array("\"", "'", "»", "«","&",">","<","#9675;"  );

    fwrite($handle, strfile('start', $id_doc,0,0) ) ;

    $txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Kod1C`,"
                    ." `Tovar`.`Tovar`, `Tovar`.`ed_izm`, `DocTab`.`Cena`,"
                    ." `DocTab`.`Cena2`, `DocTab`.`Kvo2`, `DocTab`.`pometka`, "
                    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE `DocTab`.`DocHd_id` =$id_doc AND `DocTab`.`Kvo`>0
    ORDER BY `DocTab`.`timeShtamp` DESC ";
     //echo $txt_sql . '<br>';

    $num=1;
    $sum_itog=0;  $sum_sum=0; $skidka=0;
    $sql     = cls_my_sql::tbl_sql($txt_sql);
    while ($s_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
       // $hkod=str_replace('<', '&lt;', $s_arr['Kod']);
       $nm=$s_arr['Tovar'];
       $nm         = str_replace($no_simvols, "",$nm);
       
        $skidka = $s_arr['Skidka'];
	$cena = sprintf('%.2f', $s_arr['Cena']);
        $sum  = sprintf("%.2f", $cena * $s_arr['Kvo']);
        $it   = sprintf("%.2f", $s_arr['Cena'] *  $s_arr['Kvo']  * (1 - $s_arr['Skidka']/100  ) );
	$sum_itog = $sum_itog +   $s_arr['Cena'] *  $s_arr['Kvo']  * (1 - $s_arr['Skidka']/100  );
	$sum_sum  = $sum_sum + $sum;
       
         $ed = $s_arr['ed_izm'];
        if ($ed == '')
            $ed = 'шт.';
       
       $str = addinxml($num, $s_arr['Kod1C'], $s_arr['Kod'], $nm, $cena, $s_arr['Kvo'], $ed );
       $num++;
       fwrite($handle, $str ) ;
    }
    
    $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f",$sum_sum);

    fwrite($handle, strfile('end', $id_doc, $sum_itog, $sum_sum ) ) ;
    fclose($handle);
    chmod("data/$f_name.fods", 0777);
    
   // prn_na_ftp();

      // только ос ubuntu
   
   // в меню Ubuntu "Запуск приложений" кладем скрипт, который мониторит наличие файла prn_hkod.fods
   // если есть  то выполняем :
   // libreoffice -pt "TSC_TDP-225" "/home/san/webServer/km/data/hkod.fods"
   // и удаляем его. и мониторим дальше


}


function strfile($f,$id_doc,$sum_itog, $sum_vsego){
    
//    $my_const = new cls_my_const();
//    $skl = $my_const->magazin;
    $sumdoc = sumdoc($id_doc);

        // получим данные для шапки
     $txt_sql = "SELECT `firms`.`name_firm`,  `firms`.`name_full`, `firms`.`tel_firm`, `firms`.`INN`, `firms`.`nom_svid`,
                    `firms`.`name_bank`, `firms`.`r_schet`, `firms`.`mfo_bank`,`firms`.`adres`,`firms`.`info_nalog`,
                    `Klient`.`name_klient`,`DocHd`.`id`,`DocHd`.`nomDoc`,
                    `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`flg_optPrice` , `StatusDoc`.`nameStatus`,
                    `DocHd`.`SkidkaProcent`, `DocHd`.`Pometka`, `VidDoc`.`name_doc`, `users`.`full_name`
                FROM `firms`
                    LEFT JOIN `DocHd`     ON `firms`.`id` = `DocHd`.`firms_id`
                    LEFT JOIN `Klient`    ON `DocHd`.`Klient_id` = `Klient`.`id_`
                    LEFT JOIN `VidDoc`    ON `DocHd`.`VidDoc_id` = `VidDoc`.`Id` 
                    LEFT JOIN `StatusDoc` ON `DocHd`.`statusDoc` = `StatusDoc`.`idStatus`
                    LEFT JOIN `users`     ON `DocHd`.`users_id` = `users`.`id`
                WHERE (`DocHd`.`id` = " . $id_doc . " ) ";

//echo '<br>' . $txt_sql.'<br>';
     
    //$sql     = mysql_query($txt_sql, $db);
    $s_arr   = cls_my_sql::const_sql($txt_sql); // mysql_fetch_array($sql, MYSQL_BOTH);
    $nm_klient = $s_arr['name_klient'];
    $nom_doc  = $s_arr['nomDoc'] ;
    $phpdate = strtotime( $s_arr['DataDoc'] );
    $data_doc=date ("d.m.y", $phpdate);
    
    $vsego = 'Всього:' . $sum_itog .' грн.' ;
    if( $s_arr['SkidkaProcent'] != 0 ){
        $vsego = 'Всього:' . $sum_vsego .' грн. Знижка:' . $s_arr['SkidkaProcent'] .'% До сплати:' . $sum_itog  ;
    }
    
    
        //ІПН 3158207438, номер свідоцтва 564889
    $rekv_nalog = '';
    
    if($s_arr['tel_firm'] !=  '')      $rekv_nalog = "тел.: "  . $s_arr['tel_firm']; 
    //if($s_arr['INN']      !=  '')      $rekv_nalog .= ', IПН:' . $s_arr['INN'];
    //if($s_arr['nom_svid'] !=  '')      $rekv_nalog .= ', номер свідоцтва:' . $s_arr['nom_svid'];
   
    if($s_arr['adres'] !=  '')             $adres        = ""  . $s_arr['adres'];
    if($s_arr['INN']   !=  '')             $ipn          = 'IПН:' . $s_arr['INN'];
    if($s_arr['info_nalog']   !=  '')      $info_nalog   = $s_arr['info_nalog'];
    
    //echo '<br>' . $rekv_nalog.'<br>';
    
    $rekv_bank = ' '. $s_arr['name_bank'] . 
                       ' МФО:' . $s_arr['mfo_bank'] .
                       ' Р/р:' . $s_arr['r_schet'] ;
     //	Платіжні реквізити: ПАТ КБ "ПРИВАТБАНК"   МФО: 305299 Р/р: 26003050242818 
    //echo '<br>' . $rekv_bank.'<br>';
    
   $start = '<?xml version="1.0" encoding="UTF-8"?>

<office:document xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2" office:mimetype="application/vnd.oasis.opendocument.spreadsheet">
 <office:meta><meta:generator>LibreOffice/3.5$Linux_X86_64 LibreOffice_project/350m1$Build-2</meta:generator><meta:document-statistic meta:table-count="1" meta:cell-count="208" meta:object-count="0"/></office:meta>
 <office:settings>
  <config:config-item-set config:name="ooo:view-settings">
   <config:config-item config:name="VisibleAreaTop" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaWidth" config:type="int">27312</config:config-item>
   <config:config-item config:name="VisibleAreaHeight" config:type="int">15765</config:config-item>
   <config:config-item-map-indexed config:name="Views">
    <config:config-item-map-entry>
     <config:config-item config:name="ViewId" config:type="string">view1</config:config-item>
     <config:config-item-map-named config:name="Tables">
      <config:config-item-map-entry config:name="Sheet1">
       <config:config-item config:name="CursorPositionX" config:type="int">1</config:config-item>
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
     <config:config-item config:name="ActiveTable" config:type="string">Sheet1</config:config-item>
     <config:config-item config:name="HorizontalScrollbarWidth" config:type="int">0</config:config-item>
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
     <config:config-item config:name="HasSheetTabs" config:type="boolean">false</config:config-item>
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
   <config:config-item-map-indexed config:name="ForbiddenCharacters">
    <config:config-item-map-entry>
     <config:config-item config:name="Language" config:type="string">ru</config:config-item>
     <config:config-item config:name="Country" config:type="string">RU</config:config-item>
     <config:config-item config:name="Variant" config:type="string"/>
     <config:config-item config:name="BeginLine" config:type="string"/>
     <config:config-item config:name="EndLine" config:type="string"/>
    </config:config-item-map-entry>
   </config:config-item-map-indexed>
   <config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item>
   <config:config-item config:name="PrinterSetup" config:type="base64Binary">cQH+/0dPREVYLURUMgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQ1VQUzpHT0RFWC1EVDIAAAAAAAAAAAAAAAAAAAAAAAAWAAMAkwAAAAAAAAALAJETAAC2DwAASm9iRGF0YSAxCnByaW50ZXI9R09ERVgtRFQyCm9yaWVudGF0aW9uPVBvcnRyYWl0CmNvcGllcz0xCm1hcmdpbmRhanVzdG1lbnQ9MCwwLDAsMApjb2xvcmRlcHRoPTI0CnBzbGV2ZWw9MApwZGZkZXZpY2U9MQpjb2xvcmRldmljZT0wClBQRENvbnRleERhdGEKEgBDT01QQVRfRFVQTEVYX01PREUOAERVUExFWF9VTktOT1dO</config:config-item>
   <config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item>
   <config:config-item config:name="IsDocumentShared" config:type="boolean">false</config:config-item>
   <config:config-item config:name="HasSheetTabs" config:type="boolean">false</config:config-item>
   <config:config-item config:name="PrinterName" config:type="string">GODEX-DT2</config:config-item>
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
  <number:currency-style style:name="N109P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N109">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N109P0"/>
  </number:currency-style>
  <number:number-style style:name="N110P0" style:volatile="true">
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N110">
   <number:text>-</number:text>
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N110P0"/>
  </number:number-style>
  <number:number-style style:name="N111P0" style:volatile="true">
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N111">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="0" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N111P0"/>
  </number:number-style>
  <number:number-style style:name="N112P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N112">
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N112P0"/>
  </number:number-style>
  <number:number-style style:name="N113P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
  </number:number-style>
  <number:number-style style:name="N113">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N113P0"/>
  </number:number-style>
  <number:time-style style:name="N114">
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long"/>
  </number:time-style>
  <number:time-style style:name="N115" number:truncate-on-overflow="false">
   <number:hours/>
   <number:text>:</number:text>
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long"/>
  </number:time-style>
  <number:time-style style:name="N116">
   <number:minutes number:style="long"/>
   <number:text>:</number:text>
   <number:seconds number:style="long" number:decimal-places="1"/>
  </number:time-style>
  <number:number-style style:name="N117">
   <number:scientific-number number:decimal-places="1" number:min-integer-digits="3" number:min-exponent-digits="1"/>
  </number:number-style>
  <number:number-style style:name="N118">
   <number:number number:decimal-places="3" number:min-integer-digits="1"/>
  </number:number-style>
  <number:currency-style style:name="N119P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N119">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N119P0"/>
  </number:currency-style>
  <style:style style:name="Default" style:family="table-cell">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:padding="0.071cm" style:rotation-align="none"/>
   <style:paragraph-properties fo:text-align="start"/>
   <style:text-properties style:font-name="Arial1" fo:font-size="8pt" style:font-name-asian="WenQuanYi Micro Hei" style:font-size-asian="8pt" style:font-name-complex="Arial1" style:font-size-complex="8pt"/>
  </style:style>
  <style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N109"/>
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
   <style:table-column-properties fo:break-before="auto" style:column-width="0.748cm"/>
  </style:style>
  <style:style style:name="co2" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.565cm"/>
  </style:style>
  <style:style style:name="co3" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.178cm"/>
  </style:style>
  <style:style style:name="co4" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.808cm"/>
  </style:style>
  <style:style style:name="co5" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.817cm"/>
  </style:style>
  <style:style style:name="co6" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.203cm"/>
  </style:style>
  <style:style style:name="co7" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.838cm"/>
  </style:style>
  <style:style style:name="co8" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.769cm"/>
  </style:style>
  <style:style style:name="co9" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.411cm"/>
  </style:style>
  <style:style style:name="co10" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.771cm"/>
  </style:style>
  <style:style style:name="co11" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.633cm"/>
  </style:style>
  <style:style style:name="co12" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="3.743cm"/>
  </style:style>
  <style:style style:name="co13" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.794cm"/>
  </style:style>
  <style:style style:name="co14" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.134cm"/>
  </style:style>
  <style:style style:name="co15" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.656cm"/>
  </style:style>
  <style:style style:name="co16" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.61cm"/>
  </style:style>
  <style:style style:name="co17" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.459cm"/>
  </style:style>
  <style:style style:name="ro1" style:family="table-row">
   <style:table-row-properties style:row-height="0.661cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro2" style:family="table-row">
   <style:table-row-properties style:row-height="0.635cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro3" style:family="table-row">
   <style:table-row-properties style:row-height="0.497cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro4" style:family="table-row">
   <style:table-row-properties style:row-height="0.397cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro5" style:family="table-row">
   <style:table-row-properties style:row-height="0.455cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro6" style:family="table-row">
   <style:table-row-properties style:row-height="0.499cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro7" style:family="table-row">
   <style:table-row-properties style:row-height="0.289cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro8" style:family="table-row">
   <style:table-row-properties style:row-height="0.741cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro9" style:family="table-row">
   <style:table-row-properties style:row-height="0.894cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro10" style:family="table-row">
   <style:table-row-properties style:row-height="0.54cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro11" style:family="table-row">
   <style:table-row-properties style:row-height="0.953cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro12" style:family="table-row">
   <style:table-row-properties style:row-height="0.582cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro13" style:family="table-row">
   <style:table-row-properties style:row-height="0.45cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro14" style:family="table-row">
   <style:table-row-properties style:row-height="0.413cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro15" style:family="table-row">
   <style:table-row-properties style:row-height="0.452cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ta1" style:family="table" style:master-page-name="PageStyle_5f_Sheet1">
   <style:table-properties table:display="true" style:writing-mode="lr-tb"/>
  </style:style>
  <number:number-style style:name="N1">
   <number:number number:decimal-places="0" number:min-integer-digits="1"/>
  </number:number-style>
  <number:number-style style:name="N2">
   <number:number number:decimal-places="2" number:min-integer-digits="1"/>
  </number:number-style>
  <style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
  </style:style>
  <style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="9pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="9pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="9pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="8pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="8pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="8pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N1">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce6" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="11pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="11pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="11pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce7" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="12pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="12pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="12pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce8" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce9" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="14pt" fo:font-style="italic" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="14pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="14pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce10" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="12pt" fo:font-style="italic" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="12pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="12pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce11" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="italic" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce12" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="9pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="9pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="9pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce13" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce14" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" fo:border-bottom="0.31pt solid #0e0e0e" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border-left="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" fo:border-right="none" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" fo:border-top="0.31pt solid #0e0e0e" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce15" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="8pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="8pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="8pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce16" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="12pt" fo:font-style="italic" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="12pt" style:font-style-asian="italic" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="12pt" style:font-style-complex="italic" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce17" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="9pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="9pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="9pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce18" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce19" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" fo:border-bottom="0.31pt solid #0e0e0e" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border-left="none" style:direction="ltr" fo:padding="0.071cm" fo:border-right="none" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" fo:border-top="0.31pt solid #0e0e0e" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce20" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce21" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" fo:border-bottom="0.31pt solid #0e0e0e" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border-left="none" style:direction="ltr" fo:padding="0.071cm" fo:border-right="none" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" fo:border-top="none" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
  </style:style>
  <style:style style:name="ce22" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce23" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" fo:border-bottom="0.31pt solid #0e0e0e" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border-left="none" style:direction="ltr" fo:padding="0.071cm" fo:border-right="0.31pt solid #0e0e0e" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" fo:border-top="0.31pt solid #0e0e0e" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce24" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="start" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce25" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="center" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce26" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
  </style:style>
  <style:style style:name="ce27" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N118">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce28" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="none" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="11pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="11pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Times New Roman" style:font-size-complex="11pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce29" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="6pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="6pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="6pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce30" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N2">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" fo:border="0.31pt solid #0e0e0e" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce31" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce32" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce33" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce34" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="middle" style:vertical-justify="auto"/>
   <style:paragraph-properties fo:text-align="end" css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Times New Roman" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce35" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="automatic" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Arial1" fo:font-size="10pt" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:font-size-asian="10pt" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-name-complex="Arial1" style:font-size-complex="10pt" style:font-style-complex="normal" style:font-weight-complex="normal"/>
  </style:style>
  <style:style style:name="ce36" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:glyph-orientation-vertical="0" style:text-align-source="value-type" style:repeat-content="false" fo:wrap-option="no-wrap" style:direction="ltr" fo:padding="0.071cm" style:rotation-angle="0" style:rotation-align="none" style:shrink-to-fit="false" style:vertical-align="top" style:vertical-justify="auto"/>
   <style:paragraph-properties css3t:text-justify="auto" fo:margin-left="0cm" style:writing-mode="page"/>
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
   <style:page-layout-properties fo:page-width="29.7cm" fo:page-height="21.001cm" style:num-format="1" style:print-orientation="landscape" fo:margin="1cm" fo:margin-top="1cm" fo:margin-bottom="1cm" fo:margin-left="1cm" fo:margin-right="1cm" style:first-page-number="continue" style:writing-mode="lr-tb"/>
   <style:header-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-bottom="0cm"/>
   </style:header-style>
   <style:footer-style>
    <style:header-footer-properties fo:min-height="0.75cm" fo:margin-left="0cm" fo:margin-right="0cm" fo:margin-top="0cm"/>
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
    <text:p>Страница <text:page-number>1</text:page-number></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="Report" style:page-layout-name="pm2">
   <style:header>
    <style:region-left>
     <text:p><text:sheet-name>???</text:sheet-name> (<text:title>???</text:title>)</text:p>
    </style:region-left>
    <style:region-right>
     <text:p><text:date style:data-style-name="N2" text:date-value="2017-03-13">00.00.0000</text:date>, <text:time>00:00:00</text:time></text:p>
    </style:region-right>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer>
    <text:p>Страница <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p>
   </style:footer>
   <style:footer-left style:display="false"/>
  </style:master-page>
  <style:master-page style:name="PageStyle_5f_Sheet1" style:display-name="PageStyle_Sheet1" style:page-layout-name="pm3">
   <style:header style:display="false"/>
   <style:header-left style:display="false"/>
   <style:footer style:display="false"/>
   <style:footer-left style:display="false"/>
  </style:master-page>
 </office:master-styles>
 <office:body>
  <office:spreadsheet>
   <table:calculation-settings table:case-sensitive="false" table:automatic-find-labels="false" table:use-regular-expressions="false"/>
   <table:table table:name="Sheet1" table:style-name="ta1">
    <office:forms form:automatic-focus="false" form:apply-design-mode="false"/>
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co6" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co7" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co8" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co9" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co10" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co11" table:number-columns-repeated="2" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co12" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co13" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co14" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co15" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co16" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co11" table:number-columns-repeated="239" table:default-cell-style-name="ce1"/>
    <table:table-column table:style-name="co17" table:number-columns-repeated="768" table:default-cell-style-name="Default"/>
    <table:table-row table:style-name="ro1">
     <table:table-cell/>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>Рахунок № '.$nom_doc.' від '.$data_doc.' р.</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="8"/>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>Рахунок № '.$nom_doc.' від '.$data_doc.' р.</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="245"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell/>
     <table:table-cell table:style-name="ce10" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$s_arr['name_full'].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce16"/>
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce10" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$s_arr['name_full'].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce16"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell/>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$rekv_nalog.'</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce29"/>
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce11" office:value-type="string">
      <text:p>'.$rekv_nalog.'</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="245"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$adres .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$adres .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$ipn .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$ipn .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$rekv_bank .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="7" table:number-rows-spanned="1">
      <text:p>'.$rekv_bank .'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="6" table:style-name="ce17"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro5">
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string">
      <text:p>'.$info_nalog .'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="8"/>
     <table:table-cell table:style-name="ce2" office:value-type="string">
      <text:p>'.$info_nalog .'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" table:number-columns-repeated="6"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro6">
     <table:table-cell table:style-name="ce3"/>
     <table:table-cell table:style-name="ce13" office:value-type="string">
      <text:p>Платник:</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce18" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$nm_klient.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce22"/>
     <table:table-cell table:style-name="ce15" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce13" office:value-type="string">
      <text:p>Платник:</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce18" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$nm_klient.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce22"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro7">
     <table:table-cell table:style-name="ce3"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce18"/>
     <table:table-cell table:style-name="ce22" table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce15" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce13"/>
     <table:table-cell table:style-name="ce18"/>
     <table:table-cell table:style-name="ce22" table:number-columns-repeated="5"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro8">
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>№</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>Найменування товару</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce19"/>
     <table:covered-table-cell table:style-name="ce23"/>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Од.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>К-ть</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Ціна</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Сума</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce31"/>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>№</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>Найменування товару</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce19"/>
     <table:covered-table-cell table:style-name="ce23"/>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Од.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>К-ть</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Ціна</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>Сума</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce35" table:number-columns-repeated="1007"/>
    </table:table-row>';

$pdv =$sumdoc/6;
   
  $end = '   <table:table-row table:style-name="ro10">
     <table:table-cell table:style-name="ce3" table:number-columns-repeated="6"/>
     <table:table-cell table:style-name="ce28" office:value-type="string">
      <text:p>'.$vsego .'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" table:number-columns-repeated="8"/>
     <table:table-cell table:style-name="ce28" office:value-type="string">
      <text:p>'.$vsego .'</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro11">
     <table:table-cell table:style-name="ce6" office:value-type="string">
      <text:p></text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce15"/>
     <table:table-cell table:style-name="ce20" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p></text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce24"/>
     <table:table-cell table:style-name="ce15" table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce6" office:value-type="string">
      <text:p></text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce15"/>
     <table:table-cell table:style-name="ce33" office:value-type="string">
      <text:p></text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" table:number-columns-repeated="4"/>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro12">
     <table:table-cell table:style-name="ce7" office:value-type="string">
      <text:p>Рахунок склав(ла):</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="ce21"/>
     <table:table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce26"/>
     <table:table-cell table:number-columns-repeated="4"/>
     <table:table-cell table:style-name="ce7" office:value-type="string">
      <text:p>Рахунок склав(ла):</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="ce21"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce26"/>
     <table:table-cell table:number-columns-repeated="241"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce8" office:value-type="string">
      <text:p>Рахунок необхiдно сплатити протягом 3-х банкiвських днiв. У разi невиконання, фiрма</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" table:number-columns-repeated="12"/>
     <table:table-cell table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce34" office:value-type="string">
      <text:p>Рахунок необхiдно сплатити протягом 3-х банкiвських днiв. У разi невиконання, фiрма</text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro13">
     <table:table-cell table:style-name="ce8" office:value-type="string">
      <text:p>має право змiнити цiну на товар i не гарантує його наявнiсть на складi </text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce3" table:number-columns-repeated="12"/>
     <table:table-cell table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce34" office:value-type="string">
      <text:p>має право змiнити цiну на товар i не гарантує його наявнiсть на складi </text:p>
     </table:table-cell>
     <table:table-cell table:number-columns-repeated="239"/>
     <table:table-cell table:style-name="ce1" table:number-columns-repeated="768"/>
    </table:table-row>
    <table:table-row table:style-name="ro14" table:number-rows-repeated="1048538">
     <table:table-cell table:number-columns-repeated="1024"/>
    </table:table-row>
    <table:table-row table:style-name="ro15" table:number-rows-repeated="10">
     <table:table-cell table:number-columns-repeated="1024"/>
    </table:table-row>
    <table:table-row table:style-name="ro15">
     <table:table-cell table:number-columns-repeated="1024"/>
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
