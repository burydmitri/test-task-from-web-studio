<?php
// Файлы phpmailer
require './phpmailer/PHPMailer.php';
require './phpmailer/SMTP.php';
require './phpmailer/Exception.php';

function validate_name($data)
{
    $err = ""; /* присваиваем переменной $err пустую строку */
    if (strlen($data) < 2 || strlen($data) > 50) /* если длина переменной $data меньше 2 или больше 50 символов*/
        $err = "Длина имени должна быть от 2 до 50 символов";
    if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $data)) /* если в имени содержатся недопустимые символы */
        $err = $err . "В написании имени допустимы только буквы латинского и русского алфавита,цифры, символ подчеркивания и пробел";
    if (!empty($err))
        return $err;
    else
        return 0;
}
function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool
{
    return preg_match('/^[0-9]{' . $minDigits . ',' . $maxDigits . '}\z/', $s);
}
function validate_phone(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool
{
    if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
        $count = 1;
        $telephone = str_replace(['+'], '', $telephone, $count); //remove +
    }

    //remove white space, dots, hyphens and brackets
    $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);

    //are we left with digits only?
    if (isDigits($telephone, $minDigits, $maxDigits)) {
        return 0;
    } else {
        return "Неверный формат номера телефона! Допускаются только цифры и символы '.', '-', '(', ')' и '+', но только первым символом";
    }
}

function validate_inputs($data_name, $data_phone)
{
    $err = "";
    $err_name = validate_name($data_name); /* вызываем функцию валидации имени пользователя */
    $error_phone = validate_phone($data_phone); /* вызываем функцию валидации телефона пользователя */
    if (!empty($err_name))
        $err = $err_name;
    if (!empty($error_phone))
        $err = $err . $error_phone;
    return $err;
}


# проверка, что ошибки нет
if (!error_get_last()) {

    // Переменные, которые отправляет пользователь
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $error_form = validate_inputs($name, $phone);

    if (!empty($error_form)) { /* если найдена ошибка */
        $data['result'] = "error";
        $data['info'] = "Сообщение не было отправлено. Ошибка при отправке письма. Неправельно введены имя и/или телефон.";
    } else {
        // Формирование самого письма
        $title = "Вам оставили заявку!";
        $body = "
    <h2>Новое письмо</h2>
    <b>Имя:</b> $name<br>
    <b>Телефон:</b> $phone<br><br>
    ";

        // Настройки PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth  = true;
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function ($str, $level) {
            $GLOBALS['data']['debug'][] = $str;
        };

        // Настройки вашей почты
        $mail->Host       = 'smtp.yandex.com'; // SMTP сервера вашей почты
        $mail->Username   = 'login'; // Логин на почте //PATRIOT
        $mail->Password   = 'password'; // Пароль на почте
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('email', 'PATRIOT'); // Адрес самой почты и имя отправителя

        // Получатель письма
        $mail->addAddress('email');

        // Отправка сообщения
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $body;

        // Проверяем отправленность сообщения
        if ($mail->send()) {
            $data['result'] = "success";
            $data['info'] = "Сообщение успешно отправлено!";
        } else {
            $data['result'] = "error";
            $data['info'] = "Сообщение не было отправлено. Ошибка при отправке письма";
            $data['desc'] = "Причина ошибки: {$mail->ErrorInfo}";
        }
    }
} else {
    $data['result'] = "error";
    $data['info'] = "В коде присутствует ошибка";
    $data['desc'] = error_get_last();
}

// Отправка результата
header('Content-Type: application/json');
echo json_encode($data);
