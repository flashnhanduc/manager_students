<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
function layout($layout)
{
    if (file_exists(_PATH_URL_TEMPALTES . '/assets/layouts/' . $layout . '.php')) {
        require_once _PATH_URL_TEMPALTES . '/assets/layouts/' . $layout . '.php';
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $content)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'lynhanduc123@gmail.com';                     //SMTP username
        $mail->Password   = 'yvjhqqlnocbjxofv';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom('lynhanduc0406@gmail.com', 'manager_students');
        $mail->addAddress($to);

        // Nội dung Email
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    } else {
        return false;
    }
}
function isGET()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    } else {
        return false;
    }
}
function filterData($method = '')
{
    $filterArr = []; // Chỉnh lại tên cho đúng chính tả "Filter"

    // 1. Trường hợp không truyền method (tự động nhận diện)
    if (empty($method)) {
        if (isGet() && !empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
        if (isPost() && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    } else {
        $method = strtolower($method);
        if ($method == 'get' && !empty($_GET)) {
        } else if ($method == 'post' && !empty($_POST)) {
        }
    }

    return $filterArr;
}
function validateEmail($email)
{
    if (!empty($email)) {
        $checkmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return $checkmail;
}
function validateInt($number)
{
    if (!empty($number)) {
        $checknumber = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checknumber;
}
function isPhone($phone)
{
    $phoneFirst = false;
    if ($phone[0] == '0') {
        $phoneFirst = true;
        $phone = substr($phone, 1);
    }
    $checkphone = false;
    if (validateInt($phone)) {
        $checkphone = true;
    }
    if ($phoneFirst & $checkphone) {
        return true;
    }
    return false;
}
//notification 
function getMsg( $msg , $type = 'success')
{
    echo '<div class="annouce-message alert alert-' . $type . '">';
    echo $msg;
    echo '</div>';
}
//show errors
function formError($error, $fieldName)
{
    return (!empty($errors[$fieldName])) ? '<div class ="error">' . reset($errors[$fieldName]) . '</div>' : false;
}
//show old data
function oldData($olData)
{
    return !empty($olData['email']) ? $olData['email'] : null;
}
function redirect($path, $pathFull = false){
    if($pathFull){
        header("Location: $path");
        exit();
    }else{
        $url = _HOST_URL . $path;
        header("Location: $url");
        exit();
    }
}
function isLogin(){
    $checkLogin = false;
    $token_login = getSessionFlash('token_login');
// echo $token_login;
$checkToken = getOne("SELECT *FROM token_login WHERE token = '$token_login'");
if(!empty($checkToken)){
     $checkLogin = true;
}else{
  removeSession('token_login');
}
return $checkLogin;
}
