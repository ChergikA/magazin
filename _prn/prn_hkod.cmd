set fl = "c:\OpenServer\domains\km\data\cennik_sr.fods"


:loop

IF EXIST "c:\OpenServer\domains\km\data\cennik_sr.fods" (
    
"C:\Program Files\LibreOffice 4\program\soffice.exe" --minimized -pt "Kyocera FS-1120MFP GX" "c:\OpenServer\domains\km\data\cennik_sr.fods"
    del "c:\OpenServer\domains\km\data\cennik_sr.fods"
)

IF EXIST "c:\OpenServer\domains\km\data\hkod.fods" (
    
"C:\Program Files\LibreOffice 4\program\soffice.exe" --minimized -pt "TSC-TDP-225" "c:\OpenServer\domains\km\data\hkod.fods"
    del "c:\OpenServer\domains\km\data\hkod.fods"
)

ping -n 4 127.0.0.1 >nul
goto loop
 

