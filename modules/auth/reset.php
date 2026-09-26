<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
layout('header-auth');
$filter = [];
$erorr = [];
$filterGet = filterData('GET');
if (!empty($filterGet['token'])) {
    $tokenRest = $filterGet['token'];
}

if (!empty($tokenRest)) {
    //check token hop le
    $checkToken = getOne("SELECT * FROM users WHERE Foget_token = '$tokenRest'");
    if (!empty($checkToken)) {
        if (isPost()) {
            $filter = filterdata();
            $erorr = [];

            if (empty($filter['password'])) {
                $erorr['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['password'])) < 6) {
                    $erorr['password']['length'] = 'Mật khẩu phải từ 6 kí tự trở lên';
                }
            }
            // validate confirm_pass
            if (empty($filter['confirm_password'])) {
                $erorr['confirm_password']['required'] = 'Vui lòng nhập lại mật khẩu';
            } else {
                if (trim($filter['password']) !== trim($filter['confirm_password'])) {
                    $erorr['confirm_password']['like'] = 'Mật khẩu không khớp';
                }
            }
            if (empty($erorr)) {
                $password = password_hash($filter['password'], PASSWORD_DEFAULT);
                $data = [
                    'password' => $password,
                    'Foget_token' => null,
                    'update_at'   =>  date('Y:m:d H:i:s')
                ];
                $condition = "ID " . $checkToken['id'];
                $updateStatus = update('users', $data, $condition);
                if ($updateStatus) {
                   setSessionFlash('msg', 'Đổi mật khẩu thành công');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Đã có lỗi xảy ra');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Nhập cho đàng hoàng vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $erorr);
            }
        }
    } else {
        getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
    }
} else {
    getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$olddata = getSessionFlash('oldData') ?? [];
$erorrArr = getSessionFlash('errors') ?? [];

?>
<div class="login-container">
    <h3 class="text-center mb-3" style="font-weight: 700; color: #333;">RESET PASSWORD</h3>
    <p class="text-center mb-4" style="font-size: 13px; color: #777;">
        Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
    </p>
    <?php
    if (!empty($msg) && !empty($msg_type)) {

        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="POST">
        <div class="form-outline mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" />
            <?php echo (!empty($erorr['password'])) ? '<small class="text-danger">' . reset($erorr['password']) . '</small>' : ''; ?>

        </div>

        <div class="form-outline mb-4">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" />
            <?php echo (!empty($erorr['password'])) ? '<small class="text-danger">' . reset($erorr['password']) . '</small>' : ''; ?>

        </div>

        <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">

        <button type="submit" class="btn btn-primary btn-block mb-3">CHANGE PASSWORD</button>

        <div class="text-center">
            <p><a href="<?php echo _HOST_URL ?>?module=auth&action=login" style="text-decoration: none;">Hủy bỏ và quay lại</a></p>
        </div>
    </form>
</div>