<?php
/**
 * Напишите скрипт, который будет считать количество уникальных посетителей
 * сайта за день и записывать их в базу данных.
 */

$db_host = "localhost"; $db_user = "root"; $db_pass = ""; $db_name = "my_awesome_db";
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "
    CREATE TABLE views (id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, total_views INTEGER UNSIGNED NOT NULL);
    CREATE TABLE visitors (id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, visitor_ip VARCHAR(255) NOT NULL);
";
try {
    $conn->query($query);
} catch(RuntimeException $e) {
    /* handle */
}

/**
 * Подключение к БД и выполнение запроса на инкремент значения
 * @throws RuntimeException
 * @returns void
 */
function updateUniqueVisitors() {
    $query = "UPDATE views SET total_views = total_views + 1 WHERE id = 1";
    try {
        $conn->query($query);
    } catch(RuntimeException $e) {
        /* handle */
    }
}

/**
 * Подключение к БД и проверка пользователя на уникальность ip
 * @param string $visitor_ip
 * @throws RuntimeException
 * @return boolean
 */
function isUniqueIp(string $visitor_ip)
{
    $query = "SELECT * FROM page_views WHERE visitor_ip='$visitor_ip'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        return false;
    } else {
        return true;
    }
}


if (isUniqueIp($_SERVER['REMOTE_ADDR'])) {
    updateUniqueVisitors($conn);
}
