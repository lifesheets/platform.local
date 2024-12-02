<?php

/**
 * Клас для управління викликами файлів та директорій
 */

class direct {

    /**
     * Фільтрація даних з GET-запиту.
     *
     * @param string $get_name Назва GET-параметра.
     * @return string Повертає очищене значення GET-параметра або 'no_data', якщо дані відсутні.
     */

    public static function get($name) {
        // Фільтруємо вхідні дані з GET-параметра
        $filter = filter_input(INPUT_GET, $name, FILTER_SANITIZE_ENCODED);
        // Видаляємо зайві спецсимволи, повернуті функцією FILTER_SANITIZE_ENCODED
        $get = clearspecialchars($filter);
        // Перевіряємо, чи довжина отриманого значення більша за нуль.
        $data = (strlen($get) > 0) ? $get : 'no_data';
        // Повертаємо значення змінної $data
        return $data;
    }

}
