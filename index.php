<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start();//header ,cookie

require_once 'config.php';
require_once './includes/connect.php';
require_once './includes/database.php';
require_once './includes/session.php';
require_once './includes/mailer/Exception.php';
require_once './includes/mailer/PHPMailer.php';
require_once './includes/mailer/SMTP.php';

require_once './templates/assets/layouts/index.php';
require_once './includes/function.php';

// $rel =isPhone('123456789');
// if($rel){
//     echo 'hop le';
// }else{
//     echo 'kh hop le';

// }

// $rel = validateInt('6.5');
// var_dump($rel);
// sendMail('lynhanduc0406@gmail.com','tesstmail', 'đăng ký thành công');
//  $data = [
//             'name' => 'NhanDuc',
//             'slug' => 'nhan_duc',     
//         ];
// insert('course_category',$data);
// $dataUpdate = [
//     'name' => 'NhanDuc',
//     'slug' => 'nhan_duc_new',
// ];

// update('course_category', $dataUpdate, "id = 2");
// die();
// delete('course_category', "id = 2");

// // Hoặc xóa danh mục khóa học có ID là 5
// if (delete('course_category', "id = 2")) {
//     echo "Đã xóa thành công!";
// } else {
//     echo "Xóa thất bại, vui lòng kiểm tra lại.";
// }


// $rel = getAll("SELECT * FROM course");
// echo '<pre>';
// print_r($rel);
// echo '</pre>';
// die();

// setSession('nhanducsession','testsesionnew');
// $user = getSession('nhanduc');
// var_dump($user) ;
// echo '<pre>'; // Thêm thẻ này để mảng hiện ra xuống dòng cho dễ đọc
// print_r($_SESSION);
// echo '</pre>';
// removeSession('nhanduc');
// setSessionFlash('nhanduc123','anhiuem');

// $rel = getSessionFlash('nhanduc123');

// var_dump ($rel); 
// sendMail('lynhanduc0406@gmail.com','đnagư nhan','không có gì');
$module = _MODULES;
$action = _ACTION; 

if(!empty($_GET['module'])){
    $module = $_GET['module'];
}

if(!empty($_GET['action'])){
    $action = $_GET['action'];
}

$path = 'modules/'.$module.'/'.$action.'.php';

if(!empty($path)){
    if (file_exists($path)){
        require_once $path;
    }
    else{
        require_once './modules/errors/404.php';

    }
}
else{
    require_once './modules/errors/500.php';
}