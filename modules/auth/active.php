<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
require_once './templates/assets/layouts/header-auth.php';

$filter = filterData();
$checktoken = null; // Khởi tạo để tránh lỗi undefined
$errorMsg = '';

if (!empty($filter['token'])) {
    $token = $filter['token'];
    // 1. Truy vấn kiểm tra token có tồn tại trong DB không
    $sql = "SELECT * FROM users WHERE Active_token = '$token'";
    $checktoken = getOne($sql);

    if (!empty($checktoken)) {
        // 2. Nếu tìm thấy, thực hiện Update Status lên 1 và xóa Active_token
        $userId = $checktoken['ID']; // Kiểm tra lại tên cột ID trong database của bạn
        $dataUpdate = [
            'Status' => 1,
            'Active_token' => null // Xóa token sau khi dùng xong để bảo mật
        ];
        $updateStatus = update('users', $dataUpdate, "ID=$userId");
        
        if ($updateStatus) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
            $errorMsg = "Hệ thống đang gặp sự cố, vui lòng thử lại sau.";
        }
    } else {
        $isSuccess = false;
        $errorMsg = "Liên kết kích hoạt không tồn tại hoặc đã hết hạn.";
    }
} else {
    $isSuccess = false;
    $errorMsg = "Đường dẫn không hợp lệ, thiếu mã kích hoạt.";
}

?>

<div class="container" style="margin-top: 50px;">
    <div class="login-container text-center shadow p-5 bg-white rounded" style="max-width: 500px; margin: 0 auto;">
        
        <?php if ($isSuccess): ?>
            <div class="success-icon mb-4">
                <i class="fas fa-check-circle" style="font-size: 70px; color: #28a745;"></i>
            </div>

            <h3 class="mb-3" style="font-weight: 700; color: #333;">KÍCH HOẠT THÀNH CÔNG!</h3>

            <p class="mb-4" style="font-size: 15px; color: #666; line-height: 1.6;">
                Chúc mừng tài khoản của bạn đã được kích hoạt thành công.<br>
                Giờ đây bạn đã có thể đăng nhập vào hệ thống.
            </p>

            <a href="<?php echo _HOST_URL ?>?module=auth&action=login" class="btn btn-primary btn-block w-100">
                ĐĂNG NHẬP NGAY
            </a>

        <?php else: ?>
            <div class="error-icon mb-4">
                <i class="fas fa-times-circle" style="font-size: 70px; color: #dc3545;"></i>
            </div>

            <h3 class="mb-3" style="font-weight: 700; color: #333;">KÍCH HOẠT THẤT BẠI</h3>

            <p class="mb-4" style="font-size: 15px; color: #666; line-height: 1.6;">
                <?php echo $errorMsg; ?>
            </p>

            <a href="<?php echo _HOST_URL ?>?module=auth&action=register" class="btn btn-danger btn-block w-100">
                QUAY LẠI ĐĂNG KÝ
            </a>
        <?php endif; ?>

    </div>
</div>

