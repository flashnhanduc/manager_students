<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
if (isLogin()) {
    $token = getSession('token_login');
    $removeToken = delete('token_login', "token = '$token'");

    if ($removeToken) {
        removeSession('token_login');
        redirect('?module=auth&action=login');
    } else {
        setSessionFlash('msg', 'Lỗi hệ thống vui lòng thử lại.');
        setSessionFlash('msg_type', 'danger');
    }
} else {
    setSessionFlash('msg', 'Lỗi hệ thống vui lòng thử lại.');
    setSessionFlash('msg_type', 'danger');
}
