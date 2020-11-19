<?php
/**
 * У нас есть список товаров, которые покупали наши клиентов.
 * Нам надо создать на основе этого списка базу данных на MySQL.
 * Нам важно знать имена клиентов и товаров в базе, ничего лишнего хранить в базе не нужно.
 * Создайте структуру базы и запишите в нее тестовые данные,
 * сделайте выборку из базы так чтобы вывести список клиентов,
 * которые купили 3 или более разных товаров.
 */

/**
 * Пример подключение к БД mysql
 */
$servername = "localhost"; $username = "root"; $password = ""; $dbname = "my_awesome_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Создание таблицы типа many-to-many с двумя primary keys
 */
$sql = "CREATE TABLE user_products (
    user_id SMALLINT UNSIGNED NOT NULL,
    user_name VARCHAR(30),
    sku VARCHAR(20),
    PRIMARY KEY (user_id, sku)
)";

try {
    $conn->query($sql);
} catch(Exception $e) {
    /* handle */
}

/**
 * Добавление новых записей
 */
$sql = "
    INSERT INTO user_products (user_id, user_name, sku) VALUES (1, 'pit', 'qwe');
    INSERT INTO user_products (user_id, user_name, sku) VALUES (1, 'mat', 'piu');
    INSERT INTO user_products (user_id, user_name, sku) VALUES (2, 'bot', 'qwe');
";
try {
    $conn->query($sql);
} catch(Exception $e) {
    /* handle */
}

/**
 * выборка пользователей, которые купили 3 или более разных товаров.
 */
$sql = "
SELECT user_name
FROM (SELECT count(user_id) as cnt 
      FROM (SELECT DISTINCT sku, user_id FROM user_products) 
      GROUP BY user_id)
WHERE cnt > 2;
";

try {
    $conn->query($sql);
} catch(Exception $e) {
    /* handle */
}

$conn->close();