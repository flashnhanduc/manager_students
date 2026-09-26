<?php
if(!defined( '_NhanDuc')){
    die ('Truy cap kh hop le');
}
layout('header-auth');

?>
<div class="login-container">
    <h3 class="text-center mb-3" style="font-weight: 700; color: #333;">RESET PASSWORD</h3>
    <p class="text-center mb-4" style="font-size: 13px; color: #777;">
        Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
    </p>
    
    <form action="" method="POST">
        <div class="form-outline mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required />
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" required />
        </div>

        <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">

        <button type="submit" class="btn btn-primary btn-block mb-3">CHANGE PASSWORD</button>

        <div class="text-center">
            <p><a href="login.php" style="text-decoration: none;">Hủy bỏ và quay lại</a></p>
        </div>
    </form>
</div>