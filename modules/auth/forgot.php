<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
layout('header-auth');
$filter = [];
$erorr = [];
if (isPost()) {
    $filter = filterdata();

    // validate email
    if (empty(trim($filter['email']))) {
        $erorr['email']['required'] = 'Email la bat buoc phai nhap';
    } else {
        if (!validateEmail(trim($filter['email']))) {
            $erorr['email']['isEmail'] = 'Email khong dung dinh dang';
        }
    }
}
if (empty($erorr)) {
    if (!empty($filter['email'])) {
        $email = $filter['email'];
        $checkEmail = getOne("SELECT * FROM USERS WHERE email = '$email'");
        //insert table
        if (!empty($checkEmail)) {
            $forget_token = sha1(uniqid() . time());
            $data = [
                'Foget_token' => $forget_token,
            ];
            $condition = "ID=".$checkEmail['ID'];
            $checkStatus = update('users', $data, $condition);
            if ($checkStatus) {
                $to = $email;
                $subject = 'Reset MẬT KHẨU tài khoản Manager Students - Nhân Đức';
                $linkActive = _HOST_URL . '/?module=auth&action=reset&token=' . $forget_token;
                $content = '
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                <div style="background-color: #007bff; color: white; padding: 20px; text-align: center;">
                    <h2 style="margin: 0;">Chào mừng bạn đến với Manager Students!</h2>
                </div>
                <div style="padding: 20px;">
                    <p>Chào <strong>' . $email . '</strong>,</p>
                    <p>Để bắt thay đổi mật khẩu tài khoản bạn vui lòng kích hoạt vào link bên dưới:</p>
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $linkActive . '" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">RESET MẬT KHẨU TÀI KHOẢN</a>
                    </div>
                </div>
            </div>
            ';

                sendMail($to, $subject, $content);
                setSessionFlash('msg', 'Gửi yêu cầu thành công! Vui lòng kiểm tra email để kích hoạt.');
                setSessionFlash('msg_type', 'success');
                header("Location: " . _HOST_URL . "?module=auth&action=login");
                exit;
            }

            // NẾU CÓ LỖI
            else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại');
                setSessionFlash('msg_type', 'danger');
            }
        }
    }
} else {
    setSessionFlash('msg', 'Vui lòng kiểm tra lại');
    setSessionFlash('msg_type', 'danger');
    setSessionFlash('oldData', $filter);
    setSessionFlash('errors', $erorr);
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$olddata = getSessionFlash('oldData') ?? [];
$erorrArr = getSessionFlash('errors') ?? [];
?>
<div class="login-container">
    <h3 class="text-center mb-3" style="font-weight: 700; color: #333;">FORGOT PASSWORD</h3>
    <p class="text-center mb-4" style="font-size: 13px; color: #777;">
        Nhập email của bạn để nhận hướng dẫn khôi phục mật khẩu.
    </p>
    <?php
    if (!empty($msg) && !empty($msg_type)) {

        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="POST">
        <div class="form-outline mb-4">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required />
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-3">RESET PASSWORD</button>

        <div class="text-center">
            <p><a href="<?php echo _HOST_URL ?>?module=auth&action=login" style="text-decoration: none;">← Quay lại đăng nhập</a></p>
        </div>
    </form>
</div>