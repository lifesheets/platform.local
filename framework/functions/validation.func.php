<?php

/**
 * Повне видалення символів з тексту без заміни.
 *
 * Ця функція видаляє всі спеціальні символи з переданого тексту, які визначені у масиві `$special_chars`,
 * а також символи '%20' та '+' для видалення пробілів і пробілів в URL.
 * Функція також обрізає текст від крапок, дефісів і підкреслень на початку та в кінці рядка.
 * Повертає текст, оброблений функцією `htmlspecialchars()` для запобігання XSS-атакам.
 *
 * @param string $text Текст, з якого потрібно видалити спеціальні символи.
 * @return string Повертає текст без спеціальних символів.
 */

 function clearspecialchars($text) {
    // Проверка, является ли $text строкой, и обработка null
    if (!is_string($text) || $text === null) : $text = ''; endif;
    // Массив специальных символов, которые нужно удалить из текста
    $special_chars = array('?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr(0));
    // Замена символа неразрывного пробела на обычный пробел
    if (preg_match("#\x{00a0}#siu", $text)) : $text = preg_replace("#\x{00a0}#siu", ' ', $text); endif;
    // Удаление специальных символов
    if (strpbrk($text, implode('', $special_chars)) !== false) : $text = str_replace($special_chars, '', $text); endif;
    // Удаление '%20' и '+' (пробелы в URL)
    if (strpos($text, '%20') !== false || strpos($text, '+') !== false) : $text = str_replace(array('%20', '+'), '', $text); endif;
    // Обрезание точек, дефисов и подчеркиваний в начале и конце текста
    if ($text !== '') : $text = trim($text, '.-_'); endif;
    // Возвращение текста, обработанного функцией `htmlspecialchars()` для предотвращения XSS-атак
    return htmlspecialchars($text);
}
