<?php

require("header.inc.php");
include("milib.inc");

$iddoc = $_GET['iddoc'];

global $db;

$txt_sql = "SELECT `name_full`,`INN`,`tel_firm`,`nom_svid`,`okpo`,`Info_nalog`,`adres`,
                    `name_bank`,`mfo_bank`,`r_schet`
            FROM `firms` WHERE `id` IN (SELECT `firms_id` FROM `DocHd` WHERE `id`='$iddoc') ";

   $s_arr   = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);
   $firm = $s_arr['name_full'];
   $tel  = $s_arr['tel_firm'];
   $tel_zkpo = " ЗКПО:&nbsp;" . $s_arr['okpo'] . "&nbsp;&nbsp;&nbsp; тел.:&nbsp;" . $s_arr['tel_firm'];
   $firm_adres = $s_arr['adres'];
   $str_nalog = $s_arr['Info_nalog'];
   $nalog_data = '&nbsp;ІПН : &nbsp;' . $s_arr['INN'] . ',&nbsp;&nbsp; номер свідоцтва:' . $s_arr['nom_svid'];
   $info_banc1 = 'Платіжні реквізити:&nbsp;' .  $s_arr['name_bank'] . '&nbsp;&nbsp; МФО:&nbsp;' .  $s_arr['mfo_bank'] ;
   $info_banc2 = 'Р/р:&nbsp;' . $s_arr['r_schet'] ;

   $txt_sql = "SELECT `DocHd`.`nomDoc`, `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`SkidkaProcent`,
                       `Klient`.`name_klient`,  `Klient`.`name_full`
                FROM `DocHd`
                LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_`
                WHERE `DocHd`.`id` =" . $iddoc ;

   $s_arrHD = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);


$nom_chek = $s_arrHD['nomDoc'];
$name_klient = $s_arrHD['name_klient'];
if(trim($s_arrHD['name_full']) != '' ) $name_klient =$s_arrHD['name_full'];

$dateSchet = datesql_to_str( $s_arrHD['DataDoc']) ;


?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">
<link rel=File-List href="ttt.files/filelist.xml">
<link rel=Edit-Time-Data href="ttt.files/editdata.mso">
<link rel=OLE-Object-Data href="ttt.files/oledata.mso">
<title>Печать счет c НДС</title>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:LastAuthor>a_4ergik</o:LastAuthor>
  <o:LastPrinted>2011-10-18T02:51:07Z</o:LastPrinted>
  <o:Created>2011-10-18T03:05:10Z</o:Created>
  <o:LastSaved>2011-10-18T03:05:10Z</o:LastSaved>
  <o:Version>11.5703</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:Colors>
   <o:Color>
    <o:Index>10</o:Index>
    <o:RGB>#808000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>11</o:Index>
    <o:RGB>#000080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>14</o:Index>
    <o:RGB>#808080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>15</o:Index>
    <o:RGB>#C0C0C0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>16</o:Index>
    <o:RGB>#8080FF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>17</o:Index>
    <o:RGB>#802060</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>18</o:Index>
    <o:RGB>#FFFFC0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>19</o:Index>
    <o:RGB>#A0E0E0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>20</o:Index>
    <o:RGB>#600080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>22</o:Index>
    <o:RGB>#0080C0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>23</o:Index>
    <o:RGB>#C0C0FF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>24</o:Index>
    <o:RGB>#00CFFF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>25</o:Index>
    <o:RGB>#69FFFF</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>26</o:Index>
    <o:RGB>#E0FFE0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>27</o:Index>
    <o:RGB>#DD9CB3</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>28</o:Index>
    <o:RGB>#B38FEE</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>29</o:Index>
    <o:RGB>#2A6FF9</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>30</o:Index>
    <o:RGB>#3FB8CD</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>31</o:Index>
    <o:RGB>#488436</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>32</o:Index>
    <o:RGB>#958C41</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>33</o:Index>
    <o:RGB>#8E5E42</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>34</o:Index>
    <o:RGB>#A0627A</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>35</o:Index>
    <o:RGB>#624FAC</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>36</o:Index>
    <o:RGB>#1D2FBE</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>37</o:Index>
    <o:RGB>#286676</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>38</o:Index>
    <o:RGB>#004500</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>39</o:Index>
    <o:RGB>#453E01</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>40</o:Index>
    <o:RGB>#6A2813</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>41</o:Index>
    <o:RGB>#85396A</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>42</o:Index>
    <o:RGB>#4A3285</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>43</o:Index>
    <o:RGB>#C0DCC0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>44</o:Index>
    <o:RGB>#A6CAF0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>45</o:Index>
    <o:RGB>#800000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>46</o:Index>
    <o:RGB>#008000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>47</o:Index>
    <o:RGB>#000080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>48</o:Index>
    <o:RGB>#808000</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>49</o:Index>
    <o:RGB>#800080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>50</o:Index>
    <o:RGB>#008080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>51</o:Index>
    <o:RGB>#808080</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>52</o:Index>
    <o:RGB>#FFFBF0</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>53</o:Index>
    <o:RGB>#A0A0A4</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>54</o:Index>
    <o:RGB>#313900</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>55</o:Index>
    <o:RGB>#D9853E</o:RGB>
   </o:Color>
  </o:Colors>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<style>
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:" ";}
@page
	{margin:.98in .79in .98in .79in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}
.style0
	{mso-number-format:General;
	text-align:left;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	border:none;
	mso-protection:locked visible;
	mso-style-name:Обычный;
	mso-style-id:0;}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:none;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
.xl19
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	white-space:normal;}
.xl20
	{mso-style-parent:style0;
	vertical-align:top;}
.xl21
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	white-space:normal;}
.xl22
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	white-space:normal;}
.xl23
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	vertical-align:middle;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
.xl24
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	white-space:normal;}
.xl25
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	white-space:normal;}
.xl26
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	white-space:normal;}
.xl27
	{mso-style-parent:style0;
	font-size:10.0pt;
	white-space:normal;}
.xl28
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:0;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl29
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl30
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:"0\.000";
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl31
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:Fixed;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl32
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl33
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl34
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	mso-number-format:Fixed;
	text-align:right;
	border:.5pt solid windowtext;}
.xl35
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl36
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl37
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl38
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	vertical-align:top;}
.xl39
	{mso-style-parent:style0;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl40
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
.xl41
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-align:left;
	vertical-align:top;
	white-space:normal;}
.xl42
	{mso-style-parent:style0;
	white-space:normal;}
.xl43
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-style:italic;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl44
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	text-align:left;
	white-space:normal;}
.xl45
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;}
.xl46
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	border-top:none;
	border-right:.5pt solid windowtext;
	border-bottom:none;
	border-left:none;}
.xl47
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;}
.xl48
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	vertical-align:middle;}
.xl49
	{mso-style-parent:style0;
	font-weight:700;}
.xl50
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;}
.xl51
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	white-space:normal;}
.xl52
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	white-space:normal;}
.xl53
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-style:italic;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	white-space:normal;}
.xl54
	{mso-style-parent:style0;
	font-weight:700;
	vertical-align:top;}
.xl55
	{mso-style-parent:style0;
	font-weight:700;
	vertical-align:justify;}
.xl56
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl57
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:none;
	border-left:none;}
.xl58
	{mso-style-parent:style0;
	font-size:12.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;}
-->
</style>
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Sheet1</x:Name>
    <x:WorksheetOptions>
     <x:DefaultRowHeight>225</x:DefaultRowHeight>
     <x:Print>
      <x:ValidPrinterInfo/>
      <x:PaperSizeIndex>260</x:PaperSizeIndex>
      <x:HorizontalResolution>203</x:HorizontalResolution>
      <x:VerticalResolution>203</x:VerticalResolution>
     </x:Print>
     <x:Selected/>
     <x:Panes>
      <x:Pane>
       <x:Number>3</x:Number>
       <x:ActiveRow>30</x:ActiveRow>
       <x:ActiveCol>12</x:ActiveCol>
      </x:Pane>
     </x:Panes>
     <x:ProtectContents>False</x:ProtectContents>
     <x:ProtectObjects>False</x:ProtectObjects>
     <x:ProtectScenarios>False</x:ProtectScenarios>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  <x:HideWorkbookTabs/>
  <x:WindowHeight>4755</x:WindowHeight>
  <x:WindowWidth>9300</x:WindowWidth>
  <x:WindowTopX>0</x:WindowTopX>
  <x:WindowTopY>0</x:WindowTopY>
  <x:TabRatio>0</x:TabRatio>
  <x:RefModeR1C1/>
  <x:ProtectStructure>False</x:ProtectStructure>
  <x:ProtectWindows>False</x:ProtectWindows>
 </x:ExcelWorkbook>
</xml><![endif]-->
</head>

<body link=blue vlink="#B38FEE">

<table x:str border=0 cellpadding=0 cellspacing=0 width=1074 style='border-collapse:
 collapse;table-layout:fixed;width:807pt'>
 <col width=23 style='mso-width-source:userset;mso-width-alt:981;width:17pt'>
 <col width=68 style='mso-width-source:userset;mso-width-alt:2901;width:51pt'>
 <col width=71 style='mso-width-source:userset;mso-width-alt:3029;width:53pt'>
 <col width=137 style='mso-width-source:userset;mso-width-alt:5845;width:103pt'>
 <col width=31 style='mso-width-source:userset;mso-width-alt:1322;width:23pt'>
 <col width=46 style='mso-width-source:userset;mso-width-alt:1962;width:35pt'>
 <col width=72 style='mso-width-source:userset;mso-width-alt:3072;width:54pt'>
 <col width=76 style='mso-width-source:userset;mso-width-alt:3242;width:57pt'>
 <col width=16 style='mso-width-source:userset;mso-width-alt:682;width:12pt'>
 <col width=29 style='mso-width-source:userset;mso-width-alt:1237;width:22pt'>
 <col width=60 style='mso-width-source:userset;mso-width-alt:2560;width:45pt'>
 <col width=51 style='mso-width-source:userset;mso-width-alt:2176;width:38pt'>
 <col width=173 style='mso-width-source:userset;mso-width-alt:7381;width:130pt'>
 <col width=30 style='mso-width-source:userset;mso-width-alt:1280;width:23pt'>
 <col width=43 style='mso-width-source:userset;mso-width-alt:1834;width:32pt'>
 <col width=60 style='mso-width-source:userset;mso-width-alt:2560;width:45pt'>
 <col width=70 style='mso-width-source:userset;mso-width-alt:2986;width:53pt'>
 <col width=18 style='mso-width-source:userset;mso-width-alt:768;width:14pt'>
 <col width=62 span=238 style='mso-width-source:userset;mso-width-alt:2645;
 width:47pt'>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td colspan=2 height=16 class=xl49 width=91 style='height:12.0pt;width:68pt'>Постачальник:</td>
  <td colspan=6 class=xl44 width=433 style='width:325pt'>   <?php echo $firm ?>    </td>
  <td width=16 style='width:12pt'></td>
  <td colspan=2 class=xl49 width=89 style='width:67pt'>Постачальник:</td>
  <td colspan=6 class=xl44 width=427 style='width:321pt'>   <?php echo $firm ?>
  </td>
  <td width=18 style='width:14pt'></td>    </tr>


   <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 colspan=2 style='height:12.75pt;mso-ignore:colspan'></td>
  <td colspan=6 class=xl50 ><span
  style='mso-spacerun:yes'> </span><?php echo $str_nalog ?><span
  style='mso-spacerun:yes'> </span></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td colspan=6 class=xl50 ><span
  style='mso-spacerun:yes'> </span><?php echo $str_nalog ?><span
  style='mso-spacerun:yes'> </span></td>
  <td></td>
 </tr>


 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 colspan=2 style='height:12.75pt;mso-ignore:colspan'></td>
  <td colspan=6 class=xl50 x:str=<?php echo $tel_zkpo ?>><span
  style='mso-spacerun:yes'> </span><?php echo $tel_zkpo ?><span
  style='mso-spacerun:yes'> </span></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td colspan=6 class=xl50 x:str=<?php echo $tel_zkpo ?>><span
  style='mso-spacerun:yes'> </span><?php echo $tel_zkpo ?><span
  style='mso-spacerun:yes'> </span></td>
  <td></td>
 </tr>

 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 colspan=2 style='height:12.75pt;mso-ignore:colspan'></td>
  <td colspan=6 class=xl51 width=433 style='width:325pt'><?php echo $nalog_data ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td colspan=6 class=xl50><?php echo $nalog_data ?></td>
  <td></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td height=16 colspan=2 style='height:12.0pt;mso-ignore:colspan'></td>
  <td colspan=6 class=xl51 width=433 style='width:325pt'><?php echo 'Адреса:&nbsp;' . $firm_adres ?></td>
  <td colspan=3 style='mso-ignore:colspan'></td>
  <td colspan=6 class=xl50><?php echo 'Адреса:&nbsp;' . $firm_adres ?></td>
  <td></td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td height=17 colspan=2 style='height:12.75pt;mso-ignore:colspan'></td>
  <td colspan=6 class=xl53 width=433 style='width:325pt'><?php echo $info_banc1 ?></td>
  <td class=xl19></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td colspan=6 class=xl52 width=427 style='width:321pt'><?php echo $info_banc1 ?></td>
  <td></td>
 </tr>
 <tr height=16 style='mso-height-source:userset;height:12.0pt'>
  <td height=16 colspan=2 style='height:12.0pt;mso-ignore:colspan'></td>
  <td class=xl53></td>
  <td colspan=5 class=xl53 width=362 style='width:272pt'><?php echo $info_banc2 ?></td>
  <td class=xl19></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl52></td>
  <td colspan=5 class=xl52 width=376 style='width:283pt'><?php echo $info_banc2 ?><span
  style='mso-spacerun:yes'> </span></td>
  <td></td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td colspan=2 height=17 class=xl54 style='height:12.75pt'>Платник:</td>
  <td colspan=6 class=xl21 width=433 style='width:325pt'><?php echo $name_klient ?></td>
  <td class=xl21></td>
  <td colspan=2 class=xl55>Платник:</td>
  <td colspan=6 class=xl21 width=427 style='width:321pt'><?php echo $name_klient ?>&quot;</td>
  <td></td>
 </tr>
 <tr height=29 style='mso-height-source:userset;height:21.75pt'>
  <td colspan=8 height=29 class=xl43 style='height:21.75pt'><?php echo 'Рахунок №_' . $nom_chek . '&nbsp;&nbsp;від:&nbsp;' . $dateSchet . '&nbsp;p.' ?></td>
  <td></td>
  <td colspan=8 class=xl43><?php echo 'Рахунок №_' . $nom_chek . '&nbsp;&nbsp;від:&nbsp;' . $dateSchet . '&nbsp;p.' ?></td>
  <td></td>
 </tr>
 <tr class=xl27 height=28 style='mso-height-source:userset;height:21.0pt'>
  <td height=28 class=xl22 width=23 style='height:21.0pt;border-top:none;
  width:17pt'>№</td>
  <td colspan=3 class=xl23 align=center width=276 style='mso-ignore:colspan;
  border-right:.5pt solid black;width:207pt'>Найменування товару</td>
  <td class=xl22 width=31 style='border-top:none;border-left:none;width:23pt'>Од.</td>
  <td class=xl22 width=46 style='border-top:none;border-left:none;width:35pt'>К-ть</td>
  <td class=xl22 width=72 style='border-top:none;border-left:none;width:54pt'>Ціна
  без ПДВ</td>
  <td class=xl22 width=76 style='border-top:none;border-left:none;width:57pt'>Сума
  без ПДВ</td>
  <td class=xl26></td>
  <td class=xl22 width=29 style='border-top:none;width:22pt'>№</td>
  <td colspan=3 class=xl23 align=center width=284 style='mso-ignore:colspan;
  border-right:.5pt solid black;width:213pt'>Найменування товару</td>
  <td class=xl22 width=30 style='border-top:none;border-left:none;width:23pt'>Од.</td>
  <td class=xl22 width=43 style='border-top:none;border-left:none;width:32pt'>К-ть</td>
  <td class=xl22 width=60 style='border-top:none;border-left:none;width:45pt'>Ціна
  без ПДВ</td>
  <td class=xl22 width=70 style='border-top:none;border-left:none;width:53pt'>Сума
  без ПДВ</td>
  <td class=xl27></td>
   </tr>
<!--//
 <tr class=xl20 height=17 style='height:12.75pt'>
  <td height=17 class=xl28 style='height:12.75pt;border-top:none' x:num>1</td>
  <td colspan=3 class=xl40 width=276 style='border-left:none;width:207pt'>Ручка
  кулькова &quot;Cello Maxriter&quot; (0,7) синя</td>
  <td class=xl29 style='border-top:none'>шт.</td>
  <td class=xl28 style='border-top:none;border-left:none' x:num>100</td>
  <td class=xl30 style='border-top:none;border-left:none' x:num="2.25">2,250</td>
  <td class=xl31 style='border-top:none;border-left:none' x:num="225">225,00</td>
  <td class=xl32></td>
  <td class=xl28 style='border-top:none' x:num>1</td>
  <td colspan=3 class=xl40 width=284 style='border-left:none;width:213pt'>Ручка
  кулькова &quot;Cello Maxriter&quot; (0,7) синя</td>
  <td class=xl29 style='border-top:none'>шт.</td>
  <td class=xl28 style='border-top:none;border-left:none' x:num>100</td>
  <td class=xl30 style='border-top:none;border-left:none' x:num="2.25">2,250</td>
  <td class=xl31 style='border-top:none;border-left:none' x:num="225">225,00</td>
  <td class=xl20></td>
 </tr>//-->



 <?php
// выводим данные продаж в таблицу

$txt_sql = "SELECT `DocTab`.`id` as idstr , `DocTab`.`nomstr`, `Tovar`.`Kod`, `Tovar`.`Tovar`, `Tovar`.`ed_izm`,  `DocTab`.`Cena`,"
    ." `DocTab`.`Kvo`, `DocTab`.`Skidka`\n"
    . "FROM `DocTab`\n"
    . " LEFT JOIN `Tovar` ON `DocTab`.`Tovar_id` = `Tovar`.`id_tovar` \n"
    . "WHERE (`DocTab`.`DocHd_id` =".$iddoc." AND `DocTab`.`Kvo` > 0 )\n"
    . "ORDER BY `DocTab`.`id` ASC ";


$sql = mysql_query($txt_sql, $db);
$sum_itog=0;  $sum_sum=0; $skidka=0;
while ($srt_arr = mysql_fetch_array($sql, MYSQL_BOTH) ) {
        $nom_str = $srt_arr['nomstr'];
        $cena = $srt_arr['Cena'] * (1 - $srt_arr['Skidka'] / 100); // цена со скидкой
        $cena = $cena - $cena / 6; //  цена без ндс
        $cena = sprintf('%.2f', $cena);
        $sum = sprintf("%.2f", $cena * $srt_arr['Kvo']);
       // $sum = $cena * $srt_arr['Kvo'];
        $sum_sum = $sum_sum + $sum; // сумма без НДС
        $skidka = $srt_arr['Skidka'];
        $ed = $srt_arr['ed_izm'];
        if($ed=='')$ed='шт.';

        echo "
			 <tr class=xl20 height=17 style='height:12.75pt'>
			  <td height=17 class=xl28 style='height:12.75pt;border-top:none' x:num>$nom_str</td>
			  <td colspan=3 class=xl40 width=276 style='border-left:none;width:207pt'>".$srt_arr['Tovar']."</td>
			  <td class=xl29 style='border-top:none'>$ed</td>
			  <td class=xl28 style='border-top:none;border-left:none' x:num>".$srt_arr['Kvo']."</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num>$cena</td>
			  <td class=xl31 style='border-top:none;border-left:none' x:num>$sum</td>
			  <td class=xl32></td>
			  <td class=xl28 style='border-top:none' x:num>$nom_str</td>
			  <td colspan=3 class=xl40 width=284 style='border-left:none;width:213pt'>".$srt_arr['Tovar']."</td>
			  <td class=xl29 style='border-top:none'>$ed</td>
			  <td class=xl28 style='border-top:none;border-left:none' x:num>".$srt_arr['Kvo']."</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num=>$cena</td>
			  <td class=xl31 style='border-top:none;border-left:none' x:num=>$sum</td>
			  <td class=xl20></td>
			 </tr>        ";



     // $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f", $sum_sum);
    $sum_s_nds = sprintf("%.2f", $sum_sum * 1.2);
    $sum_nds = sprintf("%.2f", $sum_s_nds - $sum_sum);
    }


if ($skidka != 0) {
    echo "
		 <tr height=17 style='height:12.75pt'>
		  <td height=17 class=xl33 style='height:12.75pt;border-top:none'>&nbsp;</td>
		  <td class=xl33 style='border-top:none'>&nbsp;</td>
		  <td class=xl33 style='border-top:none'>&nbsp;</td>
		  <td colspan=5 class=xl56 style='border-right:.5pt solid black'
		  x:str>Цiна вказана з урахуванням  знижки &nbsp; $skidka%:<span style='mso-spacerun:yes'> </span></td>
		  <td class=xl35></td>
		  <td class=xl33 style='border-top:none'>&nbsp;</td>
		  <td class=xl33 style='border-top:none'>&nbsp;</td>
		  <td class=xl33 style='border-top:none'>&nbsp;</td>
		  <td colspan=5 class=xl56 style='border-right:.5pt solid black'
		  x:str>Цiна вказана з урахуванням знижки &nbsp; $skidka%:<span style='mso-spacerun:yes'> </span></td>
		  <td></td>
		 </tr> ";
}

?>

 <tr height=19 style='height:14.25pt'>
  <td height=19 colspan=3 class=xl35 style='height:14.25pt;mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black'
  x:str="Разом без ПДВ: ">Разом без ПДВ:<span style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-left:none' x:num><?php echo $sum_sum ?></td>
  <td colspan=4 class=xl35 style='mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black'
  x:str="Разом без ПДВ: ">Разом без ПДВ:<span style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-left:none' x:num><?php echo $sum_sum ?></td>
  <td></td>
 </tr>
 <tr height=19 style='height:14.25pt'>
  <td height=19 colspan=3 class=xl35 style='height:14.25pt;mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black' x:str>ПДВ:<span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-top:none;border-left:none' x:num><?php echo $sum_nds ?></td>
  <td colspan=4 class=xl35 style='mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black' x:str>ПДВ:<span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-top:none;border-left:none' x:num><?php echo $sum_nds ?></td>
  <td></td>
 </tr>
 <tr height=19 style='height:14.25pt'>
  <td height=19 colspan=3 class=xl35 style='height:14.25pt;mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black'
  x:str="Всього з ПДВ: ">Всього з ПДВ:<span style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-top:none;border-left:none' x:num><?php echo $sum_s_nds ?></td>
  <td colspan=4 class=xl35 style='mso-ignore:colspan'></td>
  <td colspan=4 class=xl45 style='border-right:.5pt solid black'
  x:str="Всього з ПДВ: ">Всього з ПДВ:<span style='mso-spacerun:yes'> </span></td>
  <td class=xl34 style='border-top:none;border-left:none' x:num><?php echo $sum_s_nds ?></td>
  <td></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.5pt'>
  <td colspan=8 height=14 class=xl41 width=524 style='height:10.5pt;width:393pt'></td>
  <td colspan=2 class=xl36 style='mso-ignore:colspan'></td>
  <td class=xl37></td>
  <td class=xl36></td>
  <td class=xl38></td>
  <td colspan=4 class=xl35 style='mso-ignore:colspan'></td>
  <td></td>
 </tr>
 <tr height=21 style='height:15.75pt'>
  <td colspan=3 height=21 class=xl58 style='height:15.75pt'>Рахунок склав(ла):</td>
  <td class=xl39>&nbsp;</td>
  <td colspan=5 style='mso-ignore:colspan'></td>
  <td colspan=3 class=xl58>Рахунок склав(ла):</td>
  <td class=xl39>&nbsp;</td>
  <td colspan=5 style='mso-ignore:colspan'></td>
 </tr>
 <tr height=15 style='height:11.25pt'>
  <td height=15 colspan=18 style='height:11.25pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td colspan=8 height=17 class=xl47 style='height:12.75pt'>Рахунок необхiдно
  сплатити протягом трьох днiв. У разi невиконання, фiрма</td>
  <td class=xl35></td>
  <td colspan=8 class=xl48>Рахунок необхiдно сплатити протягом трьох днiв. У
  разi невиконання, фiрма</td>
  <td></td>
 </tr>
 <tr height=17 style='mso-height-source:userset;height:12.75pt'>
  <td colspan=8 height=17 class=xl47 style='height:12.75pt'
  x:str="має право змiнити цiну на товар i не гарантує його наявнiсть на складi ">має
  право змiнити цiну на товар i не гарантує його наявнiсть на складi<span
  style='mso-spacerun:yes'> </span></td>
  <td class=xl35></td>
  <td colspan=8 class=xl48
  x:str="має право змiнити цiну на товар i не гарантує його наявнiсть на складi ">має
  право змiнити цiну на товар i не гарантує його наявнiсть на складi<span
  style='mso-spacerun:yes'> </span></td>
  <td></td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=23 style='width:17pt'></td>
  <td width=68 style='width:51pt'></td>
  <td width=71 style='width:53pt'></td>
  <td width=137 style='width:103pt'></td>
  <td width=31 style='width:23pt'></td>
  <td width=46 style='width:35pt'></td>
  <td width=72 style='width:54pt'></td>
  <td width=76 style='width:57pt'></td>
  <td width=16 style='width:12pt'></td>
  <td width=29 style='width:22pt'></td>
  <td width=60 style='width:45pt'></td>
  <td width=51 style='width:38pt'></td>
  <td width=173 style='width:130pt'></td>
  <td width=30 style='width:23pt'></td>
  <td width=43 style='width:32pt'></td>
  <td width=60 style='width:45pt'></td>
  <td width=70 style='width:53pt'></td>
  <td width=18 style='width:14pt'></td>
 </tr>
 <![endif]>
</table>

</body>

</html>
