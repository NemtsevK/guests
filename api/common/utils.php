<?php

/**
 * Очистка от запрещённых символов
 * @param string|null $text
 * @param int $length
 * @return string|null
 */
function clean(?string $text, int $length = 100): ?string
{
    if (is_null($text) || $text === '') {
        return null;
    }

    $text = htmlspecialchars(strip_tags(stripslashes(trim($text))));

    if (iconv_strlen($text, 'UTF-8') > $length) {
        $text = iconv_substr($text, 0, $length, 'UTF-8');
    }

    return $text ?: null;
}



/**
 * проверка на уникальность поля в бд
 * @param $value
 * @param $field
 * @param null $id
 * @return bool
 */
function checkRepeat($value, $field, $id = null): bool
{
    global $connect;

    if ($value !== null) {
        if (isset($id)) {
            $query = $connect->prepare("SELECT * FROM contacts WHERE $field = ? AND id != ?");
            $query->execute([$value, $id]);
        } else {
            $query = $connect->prepare("SELECT * FROM contacts WHERE $field = ?");
            $query->execute([$value]);
        }

        $result = $query->fetch();

        return is_array($result);
    }

    return false;
}

/**
 * Валидация данных
 * @param $fields
 * @return array|true[]
 */
function validateFields($fields): array
{
    foreach ($fields as $items) {
        $value = $items['value'] ?? null;
        $pattern = $items['pattern'];

        if ($value !== null && !preg_match($pattern['regex'], $value)) {
            return ['success' => false, 'message' => $pattern['name']];
        }
    }

    return ['success' => true];
}

/**
 * Получить страну по номеру телефона
 * @param PDO $connection
 * @param string $phone
 * @return mixed|null
 */
function getCountryByPhone(PDO $connection, string $phone): mixed
{
    $prepare_string = array_reverse(prepareString($phone, 2, 4));
    $country = null;

    foreach ($prepare_string as $pattern) {
        $query = $connection->query("
            SELECT name FROM countries
            WHERE phone_pattern LIKE '{$pattern}%'
            ORDER BY phone_pattern
            LIMIT 1
        ");

        $result = $query->fetch();
        if ($result) {
            $country = $result['name'];
            break;
        }
    }

    return $country;
}

/**
 * Преобразование строки в массив строк, для поиска в базе данных
 * @param $input
 * @param $first
 * @param $last
 * @return array
 */
function prepareString($input, $first, $last): array
{
    $result = [];

    for ($i = $first; $i <= $last; $i++) {
        if (strlen($input) >= $i) {
            $result[] = substr($input, 0, $i);
        }
    }

    return $result;
}
