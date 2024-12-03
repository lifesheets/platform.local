<?php

declare(strict_types=1);

ob_start();

# Встановлення заголовків HTTP
header('Powered: LiveCMS');
header('Cache-control: public');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60 * 60 * 24) . ' GMT');

# Запуск сесій
session_name('SID');
session_start();

$sessID = session_id();
if (!preg_match('#[A-Za-z0-9]{32}#i', $sessID)) {
    $sessID = md5((string) random_int(100000, 999999));
}

/**
 * Видаляє потенційно небезпечні скрипти та символи зі строки.
 *
 * @param string|null $string Вхідний рядок для обробки.
 * @return string Очищений рядок.
 */

function remove_script(?string $string = null): string {
    if ($string === null) {
        return '';
    }

    // Видалення неприпустимих символів (NULL, керуючі символи та DEL).
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $string);

    // Чорний список небезпечних тегів та атрибутів
    $blacklist = [
        // Небезпечні теги.
        'vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 
        'frameset', 'ilayer', 'layer', 'bgsound',
        // Обробники подій.
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 
        'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 
        'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 
        'onblur', 'onbounce', 'oncellchange', 'onchange', 'oncontextmenu', 
        'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 
        'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 
        'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 
        'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 
        'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 
        'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 
        'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 
        'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 
        'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 
        'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 
        'onstart', 'onstop', 'onsubmit', 'onunload'
    ];

    foreach ($blacklist as $keyword) {
        $string = preg_replace('/' . preg_quote($keyword, '/') . '/iu', '', $string);
    }

    return $string;
}

/**
 * Фільтрує рядок для безпечного виведення.
 *
 * @param string $data Вхідний рядок.
 * @return string Очищений рядок.
 */

function _filter(string $data): string {
    return htmlspecialchars(remove_script($data), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

#  Поточний системний час
define('TM', time());

#  Дані сервера
define('PHP_SELF', _filter($_SERVER['PHP_SELF']));
define('HTTP_HOST', _filter($_SERVER['HTTP_HOST'] ?? ''));
define('SERVER_NAME', _filter($_SERVER['SERVER_NAME'] ?? ''));
define('HTTP_REFERER', _filter($_SERVER['HTTP_REFERER'] ?? 'none'));
define('BROWSER', _filter($_SERVER['HTTP_USER_AGENT'] ?? 'none'));
define('IP', _filter(filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?? ''));

#  Протокол
define('SCHEME', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://');

#  Повна URL-адреса запиту
define('REQUEST_URI', _filter($_SERVER['REQUEST_URI'] ?? '/'));

/**
 * Отримує значення з $_GET.
 *
 * @param string $key Ключ.
 * @param int $sanitize Очищення даних.
 * @return mixed Значення.
 */

function get(string $key, int $sanitize = 0): mixed {
    return $sanitize === 0 ? remove_script($_GET[$key] ?? '') : ($_GET[$key] ?? null);
}

/**
 * Отримує значення з $_POST.
 *
 * @param string $key Ключ.
 * @param int $sanitize Очищення даних.
 * @return mixed Значення.
 */

function post(string $key, int $sanitize = 0): mixed {
    return $sanitize === 0 ? remove_script($_POST[$key] ?? '') : ($_POST[$key] ?? null);
}

/**
 * Отримує значення з $_COOKIE.
 *
 * @param string $key Ключ.
 * @return string Значення.
 */

function cookie(string $key): string {
    return remove_script($_COOKIE[$key] ?? '');
}

/**
 * Отримує або встановлює значення в $_SESSION.
 *
 * @param string $key Ключ.
 * @param mixed $value Значення (для встановлення).
 * @return mixed Значення або стан.
 */

function session(string $key, mixed $value = 'no_data'): mixed {
    if ($value === 'no_data') {
        return $_SESSION[$key] ?? null;
    }

    $_SESSION[$key] = $value;
    return $value;
}

/**
 * Отримує або встановлює значення конфігурації.
 *
 * @param string $key Ключ.
 * @param mixed|null $value Значення (для встановлення).
 * @return mixed Значення або стан.
 */

function config(string $key, mixed $value = null): mixed {
    global $config;

    if ($value === null) {
        return _filter($config[$key] ?? '');
    }

    $config[$key] = $value;
    return $value;
}

/**
 * Визначає версію пристрою (мобільна чи десктоп).
 *
 * @return bool True для мобільних пристроїв.
 */

function is_mobile(): bool {
    $mobileDevices = ['iphone', 'android', 'mobile', 'ipad', 'ipod', 'blackberry', 'windows phone'];
    $agent = strtolower(BROWSER);

    foreach ($mobileDevices as $device) {
        if (str_contains($agent, $device)) {
            return true;
        }
    }

    return false;
}

/**
 * Перенаправляє на іншу сторінку.
 *
 * @param string $url URL-адреса.
 * @param int $refresh Час перед перенаправленням (секунди).
 * @return void
 */

function redirect(string $url, int $refresh = 0): void {
    if ($refresh > 0) {
        header("Refresh: $refresh; url=$url");
    } else {
        header("Location: $url");
    }
    exit;
}

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
