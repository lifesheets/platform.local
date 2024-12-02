<?php

ob_start(); 
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
}

