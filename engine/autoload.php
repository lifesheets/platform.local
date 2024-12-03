<?php

/**
 * Завантаження функцій із папки /system/functions/ та класів із /system/classes/.
 * Спочатку завантажуються всі файли, а потім виконуються, щоб уникнути помилок.
 */

// Масив для збереження імен файлів функцій
$function_files = [];

// Відкриття директорії з функціями
if ($dir_func_data = opendir(LCMS . '/functions')) {
    while (($dir_func = readdir($dir_func_data)) !== false) {
        // Перевірка, чи файл є PHP-файлом
        if (preg_match('#\.php$#i', $dir_func)) {
            $function_files[] = LCMS . '/functions/' . $dir_func;
        }
    }
    closedir($dir_func_data);
}

// Завантаження всіх функцій
foreach ($function_files as $function_file) {
    require_once($function_file);
}

// Автозавантаження класів із папки /system/classes/
spl_autoload_register(function ($class_name) {
    $class_file = LCMS . '/classes/' . $class_name . '.class.php';
    if (is_file($class_file)) {
        require_once($class_file);
    }
});
