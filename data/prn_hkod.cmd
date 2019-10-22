set fl = "D:\WebSerwer\domains\km\data\cennik_sr.fods"


:loop

IF EXIST "D:\WebSerwer\domains\km\data\hkod.fods" (
    "C:\Program Files\LibreOffice 4\program\soffice.exe" --minimized -pt "TSC-TDP-225" "D:\WebSerwer\domains\km\data\hkod.fods"
    del "D:\WebSerwer\domains\km\data\hkod.fods"
)

IF EXIST "D:\WebSerwer\domains\km\data\cennik_sr.fods" (
    "C:\Program Files\LibreOffice 4\program\soffice.exe" --minimized -pt "LBP6018" "D:\WebSerwer\domains\km\data\cennik_sr.fods"
    del "D:\WebSerwer\domains\km\data\cennik_sr.fods"
)

ping -n 4 127.0.0.1 >nul
goto loop
 

