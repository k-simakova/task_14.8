<?php
function writeUsersList (){
       // данные пользователей
       $users = [
        ['login' => 'user1', 'password' => 'password1'],
        ['login' => 'user2', 'password' => 'password2'],
        ['login' => 'user3', 'password' => 'password3']
    ];

    // шифрование паролей
    foreach ($users as &$user) {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    }

    // запись данных в файл
    file_put_contents('users.txt', serialize($users));
     
}
// Функция, возвращающая массив всех пользователей и хэшей их паролей
function getUsersList() {
    $file_users = file_get_contents('users.txt');
    $users = unserialize($file_users);
    return $users;
}

// Функция, проверяющая существование пользователя по логину
function existsUser($login) {
    $users = getUsersList();
    foreach ($users as $user) {
        if ($user['login'] == $login) {
            return true;
        }
    }
    return false;
}

// Функция, проверяющая правильность введенного пароля
function checkPassword($login, $password) {
    $users = getUsersList();
    foreach ($users as $user) {
        if ($user['login'] == $login && password_verify($password, $user['password'])) {
            return true;
        }
    }
    return false;
}

// Функция, возвращающая имя текущего пользователя или null, если он не авторизован
function getCurrentUser() {
    session_start();
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    } else {
        return null;
    }
}


?>
