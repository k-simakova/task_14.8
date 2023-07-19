<?php

require_once('functions.php');

// Обрабатываем отправку формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['login']) && isset($_POST['password'])) {
      $login = $_POST['login'];
      $password = $_POST['password'];
      if (existsUser($login) && checkPassword($login, $password)) {
          session_start();
          $_SESSION['username'] = $login;
          header('Location: index.php');
          exit();
      } else {
          $error = 'Неправильный логин или пароль';
      }
  } else {
      $error = 'Не заполнены все поля';
  }
}

// Проверяем, авторизован ли пользователь
if (getCurrentUser()) {
  header('Location: /index.php');
  exit();
}

?>
<html>
<body>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" name="login" placeholder="Логин">
      <input type="password" name="password" placeholder="Пароль">
      <input type="submit" value="Войти">
  </form>
</body>
</html>