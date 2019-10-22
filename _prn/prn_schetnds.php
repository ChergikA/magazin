<?php


//*********************** baba-jaga@i.ua ********************************
// добавляем в файл хмл нужные секции
function addinxml($num, $tovar, $cena, $kvo, $sum, $ed ) {

      $ro = 'ro3';
    if(strlen($tovar)> 43) {
        $ro = 'ro6';
    }
    
  $str = '    <table:table-row table:style-name="'.$ro.'">  
     <table:table-cell office:value-type="string">
      <text:p>'.$num.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$tovar.'</text:p>
     </table:table-cell>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="ce14"/>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>'.$ed.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>'.$kvo.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce19" office:value-type="string">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="ce5" office:value-type="string">
      <text:p>'.$num.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce10" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>'.$tovar.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:table-cell office:value-type="string">
      <text:p>'.$ed.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>'.$kvo.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce19" office:value-type="string">
      <text:p>'.$cena.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce17" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>';  
    
    $str = cnv_($str);
    

    return $str;
}

//*********************** baba-jaga@i.ua ********************************
// откр зап сохр
function prnxml($id_doc, $f_name){

    if($f_name == '') $f_name = 'cennik_sr'; // исторически чтоб не переделывать батник
    $handle = fopen("data/$f_name.fods", 'w');
    
    $no_simvols = array("\"", "'", "»", "«", "&", ">", "<", "#9675;");

    fwrite($handle, strfile('start', $id_doc, 0, 0, 0, 0  ));
    
    

    $txt_sql = "SELECT  `DocTab`.`id` AS idstr,  `DocTab`.`nomstr` ,  `Tovar`.`Kod` ,  
                    `Tovar`.`Tovar` ,  `Tovar`.`ed_izm` ,  `DocTab`.`Cena` ,
                     `DocTab`.`Kvo` , `DocTab`.`Skidka` ,  `DocHd`.`SumDoc` 
                FROM  `DocTab` 
                LEFT JOIN  `DocHd` ON  `DocTab`.`DocHd_id` =  `DocHd`.`id` 
                LEFT JOIN  `Tovar` ON  `DocTab`.`Tovar_id` =  `Tovar`.`id_tovar` 
                WHERE ( `DocTab`.`DocHd_id` =$id_doc AND  `DocTab`.`Kvo` >0 )
                ORDER BY  `DocTab`.`id` ASC ";
    
    
    $sql = cls_my_sql::tbl_sql($txt_sql);

    $sum_sum = 0;
    $skidka = 0;
    $sum_s_nds =0;

    while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH)) {
        $nom_str = $srt_arr['nomstr'];
        $cena = $srt_arr['Cena'] * (1 - $srt_arr['Skidka'] / 100); // цена со скидкой
        $cena = $cena - $cena / 6; //  цена без ндс
        $cena = sprintf('%.2f', $cena);
        $sum = sprintf("%.2f", $cena * $srt_arr['Kvo']);
        // $sum = $cena * $srt_arr['Kvo'];
        $sum_sum = $sum_sum + $sum; // сумма без НДС
        $skidka = $srt_arr['Skidka'];
        $ed = $srt_arr['ed_izm'];
        if ($ed == '')
            $ed = 'шт.';
        $nm = $srt_arr['Tovar'];
        $nm = str_replace($no_simvols, "", $nm);
        $sum_s_nds =   $srt_arr['SumDoc'];   
        $str = addinxml($nom_str, $nm, $cena, $srt_arr['Kvo'], $sum, $ed );
        fwrite($handle, $str);
    }    
    // $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f", $sum_sum);
    //$sum_s_nds = sprintf("%.2f", $sum_sum * 1.2);
    $sum_nds = sprintf("%.2f", $sum_s_nds - $sum_sum);
    

    fwrite($handle, strfile('end', $id_doc, $sum_sum, $sum_nds, $sum_s_nds, $skidka  ));
    fclose($handle);
    chmod("data/$f_name.fods", 0777);

    // prn_na_ftp();
    // только ос ubuntu
    // в меню Ubuntu "Запуск приложений" кладем скрипт, который мониторит наличие файла prn_hkod.fods
    // если есть  то выполняем :
    // libreoffice -pt "TSC_TDP-225" "/home/san/webServer/km/data/hkod.fods"
    // и удаляем его. и мониторим дальше
}

function strfile($f,$id_doc, $sum, $sum_nds, $sum_s_nds,$skidka ){
    
//    $my_const = new cls_my_const();
//    $skl = $my_const->magazin;
    $txtskidka = '';
    if ($skidka != 0) {
        $txtskidka  = 'Цiна вказана з урахуванням знижки <text:s/>'.$skidka.'%:';
    }

        // получим данные для шапки
     $txt_sql = "SELECT `firms`.`name_firm`, `firms`.`name_full`, `firms`.`tel_firm`, `firms`.`INN`, `firms`.`nom_svid`,
                    `firms`.`name_bank`, `firms`.`r_schet`, `firms`.`mfo_bank`, `firms`.`okpo`, `firms`.`adres`,
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
    
    //ЗКПО: 39473754 <text:s text:c="2"/>тел.: (0522) 322612
    $rekv_nalog1 = 'ЗКПО:' . $s_arr['okpo'] . '<text:s text:c="2"/>тел.:' . $s_arr['tel_firm']  ;

        //ІПН : <text:s/>394737511235, <text:s/>номер свідоцтва:
    $rekv_nalog2 = '';
    if($s_arr['INN']      !=  '')      $rekv_nalog2 .= ', IПН:' . $s_arr['INN'];
    if($s_arr['nom_svid'] !=  '')      $rekv_nalog2 .= ', номер свідоцтва:' . $s_arr['nom_svid'];
    
    //Адреса: м. Кiровоград, вул. Велика Перспективна 17
    $rekv_nalog3 = 'Адреса: ' .  $s_arr['adres']  ;
    
    
    //echo '<br>' . $rekv_nalog.'<br>';
    
    $rekv_bank1 = 'Платіжні реквізити:'. $s_arr['name_bank'];
    $rekv_bank2 =  'МФО:' . $s_arr['mfo_bank'] . ' p/р:' . $s_arr['r_schet'] ;
     //	Платіжні реквізити: ПАТ КБ "ПРИВАТБАНК"   МФО: 305299 Р/р: 26003050242818 
    //echo '<br>' . $rekv_bank.'<br>';
    
   $start = '<?xml version="1.0" encoding="UTF-8"?>

<office:document xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2" office:mimetype="application/vnd.oasis.opendocument.spreadsheet">
 <office:meta><dc:title>Печать счет c НДС</dc:title><meta:generator>LibreOffice/3.5$Linux_X86_64 LibreOffice_project/350m1$Build-2</meta:generator><meta:document-statistic meta:table-count="1" meta:cell-count="162" meta:object-count="0"/><meta:user-defined meta:name="ProgId">Excel.Sheet</meta:user-defined></office:meta>
 <office:settings>
  <config:config-item-set config:name="ooo:view-settings">
   <config:config-item config:name="VisibleAreaTop" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item>
   <config:config-item config:name="VisibleAreaWidth" config:type="int">27610</config:config-item>
   <config:config-item config:name="VisibleAreaHeight" config:type="int">14922</config:config-item>
   <config:config-item-map-indexed config:name="Views">
    <config:config-item-map-entry>
     <config:config-item config:name="ViewId" config:type="string">view1</config:config-item>
     <config:config-item-map-named config:name="Tables">
      <config:config-item-map-entry config:name="Лист1">
       <config:config-item config:name="CursorPositionX" config:type="int">1</config:config-item>
       <config:config-item config:name="CursorPositionY" config:type="int">11</config:config-item>
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
     <config:config-item config:name="ActiveTable" config:type="string">Лист1</config:config-item>
     <config:config-item config:name="HorizontalScrollbarWidth" config:type="int">270</config:config-item>
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
   <config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item>
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
  <style:font-face style:name="Ubuntu" svg:font-family="Ubuntu" style:font-pitch="variable"/>
  <style:font-face style:name="Times New Roman" svg:font-family="&apos;Times New Roman&apos;" style:font-family-generic="roman" style:font-pitch="variable"/>
  <style:font-face style:name="Arial" svg:font-family="Arial" style:font-family-generic="swiss" style:font-pitch="variable"/>
  <style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
  <style:font-face style:name="Lohit Hindi" svg:font-family="&apos;Lohit Hindi&apos;" style:font-family-generic="system" style:font-pitch="variable"/>
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
  <number:currency-style style:name="N108P0" style:volatile="true">
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
  </number:currency-style>
  <number:currency-style style:name="N108">
   <style:text-properties fo:color="#ff0000"/>
   <number:text>-</number:text>
   <number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/>
   <number:text> </number:text>
   <number:currency-symbol number:language="ru" number:country="RU">руб.</number:currency-symbol>
   <style:map style:condition="value()&gt;=0" style:apply-style-name="N108P0"/>
  </number:currency-style>
  <style:style style:name="Default" style:family="table-cell">
   <style:text-properties style:font-name-asian="WenQuanYi Micro Hei" style:font-name-complex="Lohit Hindi"/>
  </style:style>
  <style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/>
  </style:style>
  <style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N108"/>
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
   <style:table-column-properties fo:break-before="auto" style:column-width="0.734cm"/>
  </style:style>
  <style:style style:name="co2" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.477cm"/>
  </style:style>
  <style:style style:name="co3" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.258cm"/>
  </style:style>
  <style:style style:name="co4" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="3.431cm"/>
  </style:style>
  <style:style style:name="co5" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.817cm"/>
  </style:style>
  <style:style style:name="co6" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.953cm"/>
  </style:style>
  <style:style style:name="co7" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.633cm"/>
  </style:style>
  <style:style style:name="co8" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.381cm"/>
  </style:style>
  <style:style style:name="co9" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.72cm"/>
  </style:style>
  <style:style style:name="co10" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.879cm"/>
  </style:style>
  <style:style style:name="co11" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="2.205cm"/>
  </style:style>
  <style:style style:name="co12" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="3.512cm"/>
  </style:style>
  <style:style style:name="co13" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.979cm"/>
  </style:style>
  <style:style style:name="co14" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="0.924cm"/>
  </style:style>
  <style:style style:name="co15" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.579cm"/>
  </style:style>
  <style:style style:name="co16" style:family="table-column">
   <style:table-column-properties fo:break-before="auto" style:column-width="1.498cm"/>
  </style:style>
  <style:style style:name="ro1" style:family="table-row">
   <style:table-row-properties style:row-height="0.473cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro2" style:family="table-row">
   <style:table-row-properties style:row-height="0.427cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro3" style:family="table-row">
   <style:table-row-properties style:row-height="0.446cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro4" style:family="table-row">
   <style:table-row-properties style:row-height="0.499cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro5" style:family="table-row">
   <style:table-row-properties style:row-height="0.974cm" fo:break-before="auto" style:use-optimal-row-height="false"/>
  </style:style>
  <style:style style:name="ro6" style:family="table-row">
   <style:table-row-properties style:row-height="0.841cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ro7" style:family="table-row">
   <style:table-row-properties style:row-height="0.452cm" fo:break-before="auto" style:use-optimal-row-height="true"/>
  </style:style>
  <style:style style:name="ta1" style:family="table" style:master-page-name="Default">
   <style:table-properties table:display="true" style:writing-mode="lr-tb"/>
  </style:style>
  <number:text-style style:name="N100">
   <number:text-content/>
  </number:text-style>
  <style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="none"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="12pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="12pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="12pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" style:text-align-source="fix" style:repeat-content="false" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="0.06pt solid #000000" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce6" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce7" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:font-weight="bold" style:font-weight-asian="bold" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce8" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
  </style:style>
  <style:style style:name="ce9" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="0.06pt solid #000000" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce10" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:wrap-option="wrap" fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce11" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="11pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="11pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="11pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce12" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:wrap-option="wrap" fo:border="none"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce13" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:border="0.06pt solid #000000" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties fo:font-weight="bold" style:font-weight-asian="bold" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce14" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:wrap-option="wrap" fo:border="0.06pt solid #000000"/>
  </style:style>
  <style:style style:name="ce15" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties fo:color="#0e0e0e" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="none" fo:country="none" style:font-name-asian="Ubuntu" style:font-size-asian="10pt" style:language-asian="none" style:country-asian="none" style:font-name-complex="Ubuntu" style:font-size-complex="10pt" style:language-complex="none" style:country-complex="none"/>
  </style:style>
  <style:style style:name="ce16" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:wrap-option="wrap"/>
   <style:text-properties fo:font-weight="bold" style:font-weight-asian="bold" style:font-weight-complex="bold"/>
  </style:style>
  <style:style style:name="ce17" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce18" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="0.06pt solid #000000" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce19" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N100">
   <style:table-cell-properties fo:border="0.06pt solid #000000"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce20" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false" fo:wrap-option="wrap" fo:border="0.06pt solid #000000" style:vertical-align="middle"/>
   <style:paragraph-properties fo:text-align="center"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce21" style:family="table-cell" style:parent-style-name="Default">
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce22" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties fo:wrap-option="wrap"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="bold" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="bold" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="bold" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:style style:name="ce23" style:family="table-cell" style:parent-style-name="Default">
   <style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/>
   <style:paragraph-properties fo:text-align="center" fo:margin-left="0cm"/>
   <style:text-properties style:use-window-font-color="true" style:text-outline="false" style:text-line-through-style="none" style:font-name="Times New Roman" fo:font-size="10pt" fo:language="ru" fo:country="RU" fo:font-style="normal" fo:text-shadow="none" style:text-underline-style="none" fo:font-weight="normal" style:text-underline-mode="continuous" style:text-overline-mode="continuous" style:text-line-through-mode="continuous" style:font-size-asian="10pt" style:language-asian="zh" style:country-asian="CN" style:font-style-asian="normal" style:font-weight-asian="normal" style:font-size-complex="10pt" style:language-complex="hi" style:country-complex="IN" style:font-style-complex="normal" style:font-weight-complex="normal" style:text-emphasize="none" style:font-relief="none" style:text-overline-style="none" style:text-overline-color="font-color"/>
  </style:style>
  <style:page-layout style:name="pm1">
   <style:page-layout-properties fo:page-width="29.7cm" fo:page-height="21.001cm" style:num-format="1" style:print-orientation="landscape" fo:margin="1cm" fo:margin-top="1cm" fo:margin-bottom="1cm" fo:margin-left="1cm" fo:margin-right="1cm" style:writing-mode="lr-tb"/>
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
  <style:style style:name="T1" style:family="text">
   <style:text-properties fo:language="none" fo:country="none" style:font-name-asian="Ubuntu" style:font-size-asian="6.40000009536743pt" style:language-asian="none" style:country-asian="none" style:font-name-complex="Ubuntu" style:font-size-complex="6.40000009536743pt" style:language-complex="none" style:country-complex="none"/>
  </style:style>
 </office:automatic-styles>
 <office:master-styles>
  <style:master-page style:name="Default" style:page-layout-name="pm1">
   <style:header style:display="false">
    <text:p><text:sheet-name>???</text:sheet-name></text:p>
   </style:header>
   <style:header-left style:display="false"/>
   <style:footer style:display="false">
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
 </office:master-styles>
 <office:body>
  <office:spreadsheet>
   <table:table table:name="Лист1" table:style-name="ta1">
    <table:table-column table:style-name="co1" table:default-cell-style-name="ce5"/>
    <table:table-column table:style-name="co2" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="ce14"/>
    <table:table-column table:style-name="co4" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co5" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co6" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co7" table:number-columns-repeated="2" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co8" table:default-cell-style-name="ce6"/>
    <table:table-column table:style-name="co9" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co10" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co11" table:default-cell-style-name="ce14"/>
    <table:table-column table:style-name="co12" table:default-cell-style-name="ce14"/>
    <table:table-column table:style-name="co13" table:default-cell-style-name="ce17"/>
    <table:table-column table:style-name="co14" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co15" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co16" table:default-cell-style-name="Default"/>
    <table:table-column table:style-name="co3" table:default-cell-style-name="ce6"/>
    <table:table-row table:style-name="ro1">
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="2" table:number-rows-spanned="1">
      <text:p>Постачальник:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce7"/>
     <table:table-cell table:style-name="ce11" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$s_arr['name_full'].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="2" table:number-rows-spanned="1">
      <text:p>Постачальник:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce7"/>
     <table:table-cell table:style-name="ce11" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$s_arr['name_full'].'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce7"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>Є платником податку на прибуток на загальних підставах</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>Є платником податку на прибуток на загальних підставах</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog1.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog1.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog2.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog2.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog3.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_nalog3.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_bank1.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$rekv_bank1.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce6"/>
     <table:table-cell table:style-name="ce15" office:value-type="string" table:number-columns-spanned="5" table:number-rows-spanned="1">
      <text:p><text:span text:style-name="T1">'.$rekv_bank2.'</text:span></text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="4"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce6"/>
     <table:table-cell table:style-name="ce15" office:value-type="string" table:number-columns-spanned="5" table:number-rows-spanned="1">
      <text:p><text:span text:style-name="T1">'.$rekv_bank2.'</text:span></text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="2" table:number-rows-spanned="1">
      <text:p>Платник:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce7"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$nm_klient.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce16"/>
     <table:table-cell table:style-name="ce22"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="2" table:number-rows-spanned="1">
      <text:p>Платник:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:style-name="ce16"/>
     <table:table-cell table:style-name="ce12" office:value-type="string" table:number-columns-spanned="6" table:number-rows-spanned="1">
      <text:p>'.$nm_klient.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="5" table:style-name="ce16"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro4">
     <table:table-cell table:style-name="ce3" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>Рахунок №_'.$nom_doc.' <text:s/>від: '.$data_doc.' p.</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="7" table:style-name="ce8"/>
     <table:table-cell table:style-name="ce23"/>
     <table:table-cell table:style-name="ce3" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>Рахунок №_'.$nom_doc.' <text:s/>від: '.$data_doc.' p.</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="7" table:style-name="ce8"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro5">
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>№</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>Найменування товару</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce13"/>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>Од.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>К-ть</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce18" office:value-type="string">
      <text:p>Ціна без ПДВ</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce20" office:value-type="string">
      <text:p>Сума без ПДВ</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="ce4" office:value-type="string">
      <text:p>№</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string" table:number-columns-spanned="3" table:number-rows-spanned="1">
      <text:p>Найменування товару</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="2" table:style-name="ce13"/>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>Од.</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce9" office:value-type="string">
      <text:p>К-ть</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce18" office:value-type="string">
      <text:p>Ціна без ПДВ</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce20" office:value-type="string">
      <text:p>Сума без ПДВ</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>';

     $end = '    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce6" table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="5" table:number-rows-spanned="1">
      <text:p>'.$txtskidka.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="4" table:style-name="ce7"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce6" table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="5" table:number-rows-spanned="1">
      <text:p>'.$txtskidka.'</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="4" table:style-name="ce7"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>Разом без ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="4" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>Разом без ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum_nds.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="4" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum_nds.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="3" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>Всього з ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum_s_nds.'</text:p>
     </table:table-cell>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="4" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string" table:number-columns-spanned="4" table:number-rows-spanned="1">
      <text:p>Всього з ПДВ:</text:p>
     </table:table-cell>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="ce7"/>
     <table:table-cell table:style-name="ce21" office:value-type="string">
      <text:p>'.$sum_s_nds.'</text:p>
     </table:table-cell>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="8" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="2" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:table-cell table:style-name="ce6" table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="4" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro3">
     <table:table-cell table:style-name="ce1" office:value-type="string">
      <text:p>Рахунок склав:________________</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce6"/>
     <table:table-cell table:style-name="ce2"/>
     <table:table-cell table:number-columns-repeated="3"/>
     <table:table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce1" office:value-type="string">
      <text:p>Рахунок склав:____________________</text:p>
     </table:table-cell>
     <table:table-cell/>
     <table:table-cell table:style-name="Default"/>
     <table:table-cell table:style-name="ce6"/>
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="4" table:number-rows-spanned="1"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:covered-table-cell table:style-name="Default"/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" table:number-columns-spanned="17" table:number-rows-spanned="1"/>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="2"/>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:covered-table-cell table:style-name="Default"/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>Рахунок необхiдно сплатити протягом трьох днiв. У разi невиконання, фiрма</text:p>
     </table:table-cell>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>Рахунок необхiдно сплатити протягом трьох днiв. У разi невиконання, фiрма</text:p>
     </table:table-cell>
     <table:covered-table-cell/>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro2">
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>має право змiнити цiну на товар i не гарантує його наявнiсть на складi</text:p>
     </table:table-cell>
     <table:covered-table-cell/>
     <table:covered-table-cell table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="5"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce2" office:value-type="string" table:number-columns-spanned="8" table:number-rows-spanned="1">
      <text:p>має право змiнити цiну на товар i не гарантує його наявнiсть на складi</text:p>
     </table:table-cell>
     <table:covered-table-cell/>
     <table:covered-table-cell table:number-columns-repeated="3" table:style-name="Default"/>
     <table:covered-table-cell table:number-columns-repeated="3"/>
     <table:table-cell/>
    </table:table-row>
    <table:table-row table:style-name="ro7">
     <table:table-cell table:style-name="ce6" table:number-columns-repeated="8"/>
     <table:table-cell/>
     <table:table-cell table:style-name="ce6" table:number-columns-repeated="8"/>
     <table:table-cell/>
    </table:table-row>
   </table:table>
   <table:named-expressions>
    <table:named-range table:name="HTML_1" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$R$29"/>
    <table:named-range table:name="HTML_all" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$R$29"/>
    <table:named-range table:name="HTML_tables" table:base-cell-address="$Лист1.$A$1" table:cell-range-address=".$A$1:.$A$1"/>
   </table:named-expressions>
  </office:spreadsheet>
 </office:body>
</office:document>';

 if($f=='start')     return cnv_ ( $start) ;
 if($f=='end')       return cnv_ ( $end );

}



?>
