<?
function archive_delite()//функция ищет лог, архивирует его, удаляет старые архивы с логом
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/tar_gz.php");//подключаем библиотеку для работы с архивами
    set_time_limit(3600);//архивы большие
    $dir    = '/home/bitrix/xxx/back/';//путь к бекапам
    $out=false;
    if (file_exists("/home/bitrix/xxx/import_element_log.txt")){
        $out=true;//если файл лога существует 
    }
    if ($out){
        $files1 = scandir($dir);//получаем список всех заархивированных бекапов
        $i=1;$MAX_I=9999;//ограничение на бесконечный цикл
        while((file_exists("/home/bitrix/xxx/back/backup_".$i.".tar.gz"))&& ($i < $MAX_I)){//проверсяем все бекапы в паке
            if (filectime("/home/bitrix/xxx/back/backup_".$i.".tar.gz")< (strtotime("now")-604800000)){//если бекапу больше недели удаляем его
                unlink("/home/bitrix/xxx/back/backup_".$i.".tar.gz");
            }
            $i++;
        }
        if ($i==$MAX_I){
            $i=1;//если бекапов нету то начнем с начала
        }
        $oArchiver = new CArchiver('/home/bitrix/xxx/back/backup_'.$i.'.tar.gz', false);//создаем бекап
        $tres = $oArchiver->add("/home/bitrix/xxx/import_element_log.txt");//добавляем туда лог
        $arErrors = &$oArchiver->GetErrors();

        if(count($arErrors)==0)
        {
            unlink("/home/bitrix/xxx/import_element_log.txt");//если все прошло успешко удаляем лог
        }
    }
    return "archive_delite();";
}
