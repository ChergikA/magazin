<?php
// ��������� ���������� ���� ������ ��� ��������
 /*  	include("magazin.inc");
    $f = new Work_files();
	$consts = new Consts();
	$firm = $consts->firma;
	$tel = $consts->firma_tel;
	$str_nalog = $consts->str_nalog;
	$nalog_data = '&nbsp;&nbsp;��� : &nbsp;' . $consts->inn . ',&nbsp;&nbsp; ����� ��������:'  . $consts->nom_svidotstva;
	$nom_chek = $_GET['nom_chek'];
	$name_klient = $_GET['name_klient'];

    $filename =  $_GET['file'] ;  // ������ � ������� ������
	if($filename == '' ) $filename = $f->f_name; // ������ � ��������� �����
*/


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
   $tel_zkpo = " ����:&nbsp;" . $s_arr['okpo'] . "&nbsp;&nbsp;&nbsp; ���.:&nbsp;" . $s_arr['tel_firm'];
   $firm_adres = $s_arr['adres'];
   $str_nalog = $s_arr['Info_nalog'];
   $nalog_data = '&nbsp;��� : &nbsp;' . $s_arr['INN'] . ',&nbsp;&nbsp; ����� ��������:' . $s_arr['nom_svid'];
   $info_banc1 = '������ ��������:&nbsp;' .  $s_arr['name_bank'] . '&nbsp;&nbsp; ���:&nbsp;' .  $s_arr['mfo_bank'] ;
   $info_banc2 = '�/�:&nbsp;' . $s_arr['r_schet'] ;

   $txt_sql = "SELECT `DocHd`.`nomDoc`, `DocHd`.`DataDoc`, `DocHd`.`SumDoc`, `DocHd`.`SkidkaProcent`,
                       `Klient`.`name_klient`,  `Klient`.`name_full`
                FROM `DocHd`
                LEFT JOIN `Klient` ON `DocHd`.`Klient_id` = `Klient`.`id_`
                WHERE `DocHd`.`id` =" . $iddoc ;

   $s_arrHD = mysql_fetch_array(mysql_query($txt_sql, $db), MYSQL_BOTH);


$nom_chek = $s_arrHD['nomDoc'];
$name_klient = $s_arrHD['name_klient'];
if(trim($s_arrHD['name_full']) != '' ) $name_klient =$s_arrHD['name_full'];

$dateDoc = datesql_to_str( $s_arrHD['DataDoc']) ;


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
<title>������ ���������</title>
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
	{margin:.17in .16in .16in .22in;
	mso-header-margin:.17in;
	mso-footer-margin:.16in;
	mso-page-orientation:landscape;}
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
	mso-style-name:�������;
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
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl20
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl21
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl22
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;}
.xl23
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center-across;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;}
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
	border-left:none;}
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
	border-left:none;}
.xl26
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	white-space:normal;}
.xl27
	{mso-style-parent:style0;
	font-size:10.0pt;}
.xl28
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:0;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl29
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:center;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl30
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:Fixed;
	text-align:right;
	vertical-align:top;
	border:.5pt solid windowtext;}
.xl31
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	vertical-align:top;}
.xl32
	{mso-style-parent:style0;
	font-size:9.0pt;
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
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	vertical-align:top;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl35
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl36
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl37
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:right;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl38
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	mso-number-format:Fixed;
	text-align:right;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl39
	{mso-style-parent:style0;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl40
	{mso-style-parent:style0;
	font-size:14.0pt;
	font-weight:700;
	font-style:italic;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl41
	{mso-style-parent:style0;
	font-size:12.0pt;
	font-weight:700;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl42
	{mso-style-parent:style0;
	font-size:11.0pt;
	font-weight:700;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;}
.xl43
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
.xl44
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-weight:700;
	text-align:right;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl45
	{mso-style-parent:style0;
	font-size:10.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;}
.xl46
	{mso-style-parent:style0;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;}
.xl47
	{mso-style-parent:style0;
	font-size:9.0pt;
	font-family:"Times New Roman", serif;
	mso-font-charset:204;
	text-align:left;
	vertical-align:top;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	white-space:normal;}
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
      <x:PaperSizeIndex>9</x:PaperSizeIndex>
      <x:HorizontalResolution>600</x:HorizontalResolution>
      <x:VerticalResolution>0</x:VerticalResolution>
     </x:Print>
     <x:Selected/>
     <x:Panes>
      <x:Pane>
       <x:Number>3</x:Number>
       <x:ActiveRow>17</x:ActiveRow>
       <x:ActiveCol>2</x:ActiveCol>
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

<table x:str border=0 cellpadding=0 cellspacing=0 width=1069 style='border-collapse:
 collapse;table-layout:fixed;width:802pt'>
 <col width=22 style='mso-width-source:userset;mso-width-alt:938;width:17pt'>
 <col width=99 style='mso-width-source:userset;mso-width-alt:4224;width:74pt'>
 <col width=83 style='mso-width-source:userset;mso-width-alt:3541;width:62pt'>
 <col width=119 style='mso-width-source:userset;mso-width-alt:5077;width:89pt'>
 <col width=37 span=2 style='mso-width-source:userset;mso-width-alt:1578;
 width:28pt'>
 <col width=48 style='mso-width-source:userset;mso-width-alt:2048;width:36pt'>
 <col width=70 style='mso-width-source:userset;mso-width-alt:2986;width:53pt'>
 <col width=23 style='mso-width-source:userset;mso-width-alt:981;width:17pt'>
 <col width=26 style='mso-width-source:userset;mso-width-alt:1109;width:20pt'>
 <col width=99 style='mso-width-source:userset;mso-width-alt:4224;width:74pt'>
 <col width=95 style='mso-width-source:userset;mso-width-alt:4053;width:71pt'>
 <col width=123 style='mso-width-source:userset;mso-width-alt:5248;width:92pt'>
 <col width=35 style='mso-width-source:userset;mso-width-alt:1493;width:26pt'>
 <col width=37 style='mso-width-source:userset;mso-width-alt:1578;width:28pt'>
 <col width=45 style='mso-width-source:userset;mso-width-alt:1920;width:34pt'>
 <col width=71 style='mso-width-source:userset;mso-width-alt:3029;width:53pt'>
 <col width=62 span=239 style='mso-width-source:userset;mso-width-alt:2645;
 width:47pt'>
 <tr height=26 style='height:19.5pt'>
  <td height=26 class=xl19 width=22 style='height:19.5pt;width:17pt'></td>
  <td colspan=7 class=xl40 width=493 style='width:370pt'> <?php echo $firm ?> </td>
  <td class=xl19 width=23 style='width:17pt'></td>
  <td class=xl19 width=26 style='width:20pt'></td>
  <td colspan=7 class=xl40 width=505 style='width:378pt'> <?php echo $firm ?> </td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl19 style='height:12.75pt'></td>
  <td colspan=7 class=xl20> <?php echo $str_nalog ?> </td>
  <td colspan=2 class=xl19 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl20> <?php echo $str_nalog ?> </td>
 </tr>
 <tr height=23 style='mso-height-source:userset;height:17.25pt'>
  <td height=23 style='height:17.25pt'></td>
  <td colspan=7 class=xl41><?php echo '�������� �_' .  $nom_chek . '&nbsp;&nbsp;��:&nbsp;'. $dateDoc .'&nbsp;p.' ?></td>

  <td class=xl19></td>
  <td></td>
  <td colspan=7 class=xl41><?php echo '�������� �_' .  $nom_chek . '&nbsp;&nbsp;��:&nbsp;'. $dateDoc .'&nbsp;p.' ?></td>
 </tr>
 <tr height=28 style='mso-height-source:userset;height:21.0pt'>
  <td height=28 class=xl19 style='height:21.0pt'></td>
  <td colspan=7 class=xl42><?php echo $name_klient ?> </td>
  <td colspan=2 class=xl19 style='mso-ignore:colspan'></td>
  <td colspan=7 class=xl42><?php echo $name_klient ?> </td>
 </tr>
 <tr height=27 style='mso-height-source:userset;height:20.25pt'>
  <td height=27 style='height:20.25pt'></td>
  <td class=xl21>����� ����:</td>
  <td colspan=6 class=xl45><span style='mso-spacerun:yes'>�</span></td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl21>����� ����:</td>
  <td colspan=6 class=xl45><span style='mso-spacerun:yes'>�</span></td>
 </tr>
 <tr height=25 style='mso-height-source:userset;height:18.75pt'>
  <td height=25 style='height:18.75pt'></td>
  <td class=xl21>�� ���i���i���:</td>
  <td colspan=6 class=xl45>���i�:<span
  style='mso-spacerun:yes'>����������������� </span>�_<span
  style='mso-spacerun:yes'>���������� </span>�i�:<span
  style='mso-spacerun:yes'>������ </span>.<span style='mso-spacerun:yes'>
  </span>.</td>
  <td colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl21>�� ���i���i���:</td>
  <td colspan=6 class=xl45>���i�:<span
  style='mso-spacerun:yes'>����������������� </span>�_<span
  style='mso-spacerun:yes'>���������� </span>�i�:<span
  style='mso-spacerun:yes'>������ </span>.<span style='mso-spacerun:yes'>
  </span>.</td>
 </tr>
 <tr height=10 style='mso-height-source:userset;height:7.5pt'>
  <td colspan=8 height=10 class=xl46 style='height:7.5pt'>&nbsp;</td>
  <td class=xl19></td>
  <td colspan=8 class=xl46>&nbsp;</td>
 </tr>
 <tr class=xl27 height=17 style='height:12.75pt'>
  <td height=17 class=xl22 style='height:12.75pt;border-top:none'>�</td>
  <td colspan=3 class=xl23 align=center style='mso-ignore:colspan;border-right:
  .5pt solid black'>������������ ������</td>
  <td class=xl22 style='border-top:none;border-left:none'>��.</td>
  <td class=xl22 style='border-top:none;border-left:none'>�-��</td>
  <td class=xl26 width=48 style='border-top:none;border-left:none;width:36pt'>ֳ��</td>
  <td class=xl26 width=70 style='border-top:none;border-left:none;width:53pt'>����</td>
  <td class=xl20></td>
  <td class=xl22 style='border-top:none'>�</td>
  <td colspan=3 class=xl23 align=center style='mso-ignore:colspan;border-right:
  .5pt solid black'>������������ ������</td>
  <td class=xl22 style='border-top:none;border-left:none'>��.</td>
  <td class=xl22 style='border-top:none;border-left:none'>�-��</td>
  <td class=xl26 width=45 style='border-top:none;border-left:none;width:34pt'>ֳ��</td>
  <td class=xl26 width=71 style='border-top:none;border-left:none;width:53pt'>����</td>
 </tr>


  <?php
 // ������� ������ ������ � �������
 /* ���� ���� ���������� � �������� ��� ������.
	if (is_writable($filename)) {

	// ���, �����, ����, �������, �-�� ,������, ��������, ����, �����, ������, ������(������)
    //  0     1     2      3       4      5        6       7      8       9      10
	$file_list=fopen($filename,'r'); // 2
	$nom_str = 1; $sum_itog=0;  $sum_sum=0; $skidka=0;
		while (!feof($file_list)) {
	     	$str = fgets($file_list);
	        $srt_arr = explode("~~",$str);

	        if ($srt_arr[0] == '') continue; //��������� ������

	       	//$pos = stripos($srt_arr[10],'���');
			//if ($pos === false ) continue;  /* echo "������� ������ NOT ��������� � ������� ����� $pos ";
	        if ($srt_arr[6] != $nom_chek ) continue;
	        if ( trim($srt_arr[10]) != '����' ) continue;
	  		   $sum = sprintf("%.2f", $srt_arr[2] *  $srt_arr[4]);
	           $it = sprintf("%.2f", $srt_arr[2] *  $srt_arr[4]  * (1 - $srt_arr[5]/100  ) );
	           $sum_itog = $sum_itog +   $srt_arr[2] *  $srt_arr[4]  * (1 - $srt_arr[5]/100  );
	           $sum_sum = $sum_sum + $sum;
	           $skidka = $srt_arr[5];
	           $cena = sprintf('%.2f', $srt_arr[2]); */

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
	$skidka = $srt_arr['Skidka'];
	$cena = sprintf('%.2f', $srt_arr['Cena']);
        $sum = sprintf("%.2f", $cena * $srt_arr['Kvo']);
        $it = sprintf("%.2f", $srt_arr['Cena'] *  $srt_arr['Kvo']  * (1 - $srt_arr['Skidka']/100  ) );
	$sum_itog = $sum_itog +   $srt_arr['Cena'] *  $srt_arr['Kvo']  * (1 - $srt_arr['Skidka']/100  );
	$sum_sum = $sum_sum + $sum;
        $ed = $srt_arr['ed_izm'];
        if($ed=='')$ed='��.';

	       echo " <tr class=xl32 height=16 style='height:12.0pt'>
			  <td height=16 class=xl28 style='height:12.0pt;border-top:none' x:num>$nom_str</td>
			  <td colspan=3 class=xl47 width=301 style='border-left:none;width:225pt'>".$srt_arr['Tovar']."</td>
			  <td class=xl29 style='border-top:none'>$ed</td>
			  <td class=xl28 style='border-top:none;border-left:none' x:num >".$srt_arr['Kvo']."</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num=>$cena</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num=>$sum</td>
			  <td class=xl31></td>
			  <td class=xl28 style='border-top:none' x:num>$nom_str</td>
			  <td colspan=3 class=xl47 width=317 style='border-left:none;width:237pt'>".$srt_arr['Tovar']."</td>
			  <td class=xl29 style='border-top:none'>$ed</td>
			  <td class=xl28 style='border-top:none;border-left:none' x:num>".$srt_arr['Kvo']."</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num>$cena</td>
			  <td class=xl30 style='border-top:none;border-left:none' x:num>$sum</td>
			 </tr> ";

/*		echo "<tr class=xl36 height=17 style='height:12.75pt'>
			 <td height=17 class=xl32 style='height:12.75pt;border-top:none' x:num>$nom_str</td>
			 <td colspan=3 class=xl46 width=263 style='border-left:none;width:198pt'>$srt_arr[1]</td>
			 <td class=xl33 style='border-top:none'>��.</td>
			 <td class=xl32 style='border-top:none;border-left:none' x:num>$srt_arr[4]</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$cena</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$sum</td>
			 <td class=xl35></td>
			 <td class=xl32 style='border-top:none' x:num>$nom_str</td>
			 <td colspan=3 class=xl46 width=267 style='border-left:none;width:201pt'>$srt_arr[1]</td>
			 <td class=xl33 style='border-top:none'>��.</td>
			 <td class=xl32 style='border-top:none;border-left:none' x:num>$srt_arr[4]</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$cena</td>
			 <td class=xl34 style='border-top:none;border-left:none' x:num>$sum</td>
			 </tr>";*/


		}

    $sum_itog  = sprintf("%.2f",$sum_itog);
    $sum_sum = sprintf("%.2f",$sum_sum);

?>


<!--// <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl33 style='height:12.75pt;border-top:none'>&nbsp;</td>
  <td colspan=7 class=xl43>���� ������� � ����������� ������ - 39%</td>
  <td class=xl19></td>
  <td class=xl33 style='border-top:none'>&nbsp;</td>
  <td colspan=7 class=xl43>���� ������� � ����������� ������ - 39%</td>
 </tr>  align='right' //-->
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20  class=xl19 style='height:15.0pt;mso-ignore:colspan' ></td>
  <td class=xl37 colspan=6 align='right' >
     <?php
		 echo '������:&nbsp;' . $sum_sum . '&nbsp;���.&nbsp;&nbsp;';
		 if($skidka != 0 ) {
		 	echo '&nbsp;&nbsp;������:&nbsp;&nbsp;' . $skidka . '%&nbsp;&nbsp; �� ������:&nbsp;' . $sum_itog . '&nbsp;���.';
		 }
	?>
  </td>
  <td class=xl38 x:num></td>
  <td class=xl19 colspan=2 style='mso-ignore:colspan'></td>
  <td class=xl37 colspan=6 >
     <?php
		 echo '������:&nbsp;' . $sum_sum . '&nbsp;���.&nbsp;&nbsp;';
		 if($skidka != 0 ) {
		 	echo '&nbsp;&nbsp;������:&nbsp;&nbsp;' . $skidka . '%&nbsp;&nbsp; �� ������:&nbsp;' . $sum_itog . '&nbsp;���.';
		 }
	?>
  </td>
  <td class=xl38 x:num></td>
 </tr>
 <tr height=15 style='mso-height-source:userset;height:11.25pt'>
  <td height=15 colspan=17 class=xl19 style='height:11.25pt;mso-ignore:colspan'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 class=xl19 style='height:12.75pt'></td>
  <td class=xl34>³���������(��)</td>
  <td class=xl35>&nbsp;</td>
  <td class=xl39></td>
  <td class=xl36>&nbsp;</td>
  <td class=xl34>�������(��)</td>
  <td class=xl36>&nbsp;</td>
  <td class=xl36>&nbsp;</td>
  <td colspan=2 class=xl19 style='mso-ignore:colspan'></td>
  <td class=xl34>³���������(��)</td>
  <td class=xl35>&nbsp;</td>
  <td class=xl39></td>
  <td class=xl36>&nbsp;</td>
  <td class=xl34>�������(��)</td>
  <td class=xl36>&nbsp;</td>
  <td class=xl36>&nbsp;</td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=22 style='width:17pt'></td>
  <td width=99 style='width:74pt'></td>
  <td width=83 style='width:62pt'></td>
  <td width=119 style='width:89pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=48 style='width:36pt'></td>
  <td width=70 style='width:53pt'></td>
  <td width=23 style='width:17pt'></td>
  <td width=26 style='width:20pt'></td>
  <td width=99 style='width:74pt'></td>
  <td width=95 style='width:71pt'></td>
  <td width=123 style='width:92pt'></td>
  <td width=35 style='width:26pt'></td>
  <td width=37 style='width:28pt'></td>
  <td width=45 style='width:34pt'></td>
  <td width=71 style='width:53pt'></td>
 </tr>
 <![endif]>
</table>

</body>

</html>
