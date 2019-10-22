<?php

//********** 23-04-17 необходимые изменения записываем в эту функцию 
function upd_mysql() {
//    
//    $txt_sql = "SHOW COLUMNS FROM  `Klient` LIKE  'telefon33'";
//if(! cls_my_sql::const_sql($txt_sql)){
//    echo 'Добавить столбец';
//}else echo 'ok столбец';
// добавим два вида документа, расформирование и комплектация
    
$txt_sql = "SHOW COLUMNS FROM  `Tovar` LIKE  'fix_prc_rozn'"; // Проверка на существование столбца
    if (!cls_my_sql::const_sql($txt_sql)) {
        $txt_sql = "ALTER TABLE `Tovar` 
        ADD `fix_prc_rozn` DECIMAL(4,2) NOT NULL DEFAULT '0' COMMENT 'фиксированный процент наценки розничной цены', 
        ADD `fix_prc_opt`  DECIMAL(4,2) NOT NULL DEFAULT '0' COMMENT 'фиксированный процент наценки оптовой цены';";
        cls_my_sql::run_sql($txt_sql);
    }    
    
    
save_log(" <h3>Внимание!</h3> ", "В документе <<Приемка товара>> "
        . "<br> Добавлена возможность   "
        . "<br> фиксировать процент наценки на товар."
        . "<br> Инструкции будут высланы на почту"
        . "<br> Вопросы на почту: cfif4.1c@gmail.com");

}
?>
