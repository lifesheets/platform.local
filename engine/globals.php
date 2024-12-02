<?php

ob_start(); 

# Встановлює заголовки HTTP
header('Powered: LiveCMS');
header("Cache-control: public");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * 60 * 24) . " GMT");

# Запуск сесій
@session_name('SID');
@session_start();

$sessID = addslashes(session_id());

if (!preg_match('#[A-z0-9]{32}#i', $sessID)) {
  $sessID = md5(mt_rand(000000, 999999));
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
    # Видалення неприпустимих символів (NULL, керуючі символи та DEL).
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
    # Список потенційно небезпечних тегів і атрибутів.
    $blacklist = [
        # Небезпечні теги.
        'vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 
        'frameset', 'ilayer', 'layer', 'bgsound',
        # Обробники подій.
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
    # Видалення ключових слів із рядка.
    foreach ($blacklist as $keyword) {
        # Генеруємо регулярний вираз із врахуванням можливих роздільників.
        $pattern = '/';
        foreach (str_split($keyword) as $char) {
            $pattern .= preg_quote($char, '/') . '(&#[xX]0(?:9|a|b);?|&#(?:9|10|13);?)?';
        }
        $pattern .= '/i';
        # Замінюємо знайдені ключові слова на пробіл.
        $string = preg_replace($pattern, ' ', $string);
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
    return remove_script(addslashes(htmlspecialchars($data, ENT_QUOTES | ENT_HTML5)));
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

