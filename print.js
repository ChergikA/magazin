//**********************baba-jaga@i.ua**********************
// �� ���� �������� xls �������
// ������ �� ��������� ������: wscript "d:/WebSerwer/domains/km/print.js"'
// ��� �� php $myok = shell_exec( 'wscript "d:/WebServer/domains/km/print.js"');
// �������� � ����� prn_hkod.php

    var objXL = WScript.CreateObject("Excel.Application");
    objXL.Visible = false;
    objXL.WorkBooks.Open("d:/WebSerwer/domains/km/data/hkod.xml");
    //objXL.WorkSheets("List1").PageSetup.Zoom =false ;
    //objXL.WorkSheets("Sheet1").PageSetup.FitToPagesWid e = 1;
    //objXL.WorkSheets("Sheet1").PageSetup.FitToPagesTal l = 10;
    objXL.WorkSheets("List1").PrintOut();
    objXL.Application.DisplayAlerts = 0;
    objXL.Quit();
   // WScript.echo('����������');
