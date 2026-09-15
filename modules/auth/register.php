<?php
if (!defined('_NhanDuc')) {
    die('Truy cap kh hop le');
}
layout('header-auth');

// SỬA 1: Khởi tạo mảng rỗng mặc định để khi vừa vào trang (chưa bấm nút) PHP sẽ không báo lỗi "Undefined variable"
$filter = [];
$erorr = [];
$msg = '';

if (isPost()) {
    $filter = filterdata();

    // validate fullname
    if (empty(trim($filter['fullname']))) {
        $erorr['fullname']['required'] = "Họ tên bắt buộc phải nhập";
    } else {
        // SỬA 2 (Rất nguy hiểm): Bạn dùng `empty(...) > 5`. Hàm empty() chỉ trả về True/False (0 hoặc 1), nên nó luôn nhỏ hơn 5. 
        // Phải dùng hàm strlen() để đếm số lượng ký tự.
        if (strlen(trim($filter['fullname'])) < 5) {
            $erorr['fullname']['length'] = "Họ tên phải từ 5 kí tự trở lên";
        }
    }

    // validate email
    if (empty(trim($filter['email']))) {
        $erorr['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        if (!validateEmail(trim($filter['email']))) {
            $erorr['email']['isEmail'] = 'Email không đúng định dạng';
        } else {
            $email = $filter['email'];
            $checkmail = getRows("SELECT * FROM users where Email ='$email'");
            if ($checkmail > 0) {
                $erorr['email']['check'] = 'Email đã tồn tại';
            }
        }
    }

    // validate phone 
    if (empty($filter['phone'])) {
        $erorr['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    } else {
        if (!isPhone($filter['phone'])) {
            $erorr['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
        }
    }

    // validate password
    if (empty($filter['password'])) {
        $erorr['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
    } else {
        if (strlen(trim($filter['password'])) < 6) {
            $erorr['password']['length'] = 'Mật khẩu phải từ 6 kí tự trở lên';
        }
    }

    // validate confirm_pass
    // SỬA 3: Ở đây bạn copy code kiểm tra từ trên xuống nhưng quên đổi tên biến. Đã đổi thành 'confirm_password'.
    if (empty($filter['confirm_password'])) {
        $erorr['confirm_password']['required'] = 'Vui lòng nhập lại mật khẩu';
    } else {
        if (trim($filter['password']) !== trim($filter['confirm_password'])) {
            $erorr['confirm_password']['like'] = 'Mật khẩu không khớp';
        }
    }

    // NẾU KHÔNG CÓ LỖI -> TIẾN HÀNH ĐĂNG KÝ
    if (empty($erorr)) {
        $activeToken = sha1(uniqid() . time());

        $data = [
            'FullName'     => $filter['fullname'],
            'Email'        => $filter['email'],
            'Phone'        => $filter['phone'],
            'password'     => password_hash($filter['password'], PASSWORD_DEFAULT),
            'Address'      => $filter['address'] ?? null,
            'Foget_token'  => '',
            'Active_token' => $activeToken,
            'Status'       => 0,
            'Group_id'     => 1,
            'Create_at'    => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('users', $data);

        if ($insertStatus) {
            $to = $filter['email'];
            $subject = 'Kích hoạt tài khoản Manager Students - Nhân Đức';

            // Tạo link kích hoạt
            $linkActive = _HOST_URL . '/?module=auth&action=active&token=' . $activeToken;

            // Thiết kế nội dung mail
            $content = '
            <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                <div style="background-color: #007bff; color: white; padding: 20px; text-align: center;">
                    <h2 style="margin: 0;">Chào mừng bạn đến với Manager Students!</h2>
                </div>
                <div style="padding: 20px;">
                    <p>Chào <strong>' . $filter['fullname'] . '</strong>,</p>
                    <p>Chúc mừng bạn đã đăng ký thành công tài khoản tại hệ thống của <strong>Nhân Đức</strong>.</p>
                    <p>Để bắt đầu sử dụng, bạn vui lòng nhấn vào nút bên dưới để kích hoạt tài khoản:</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $linkActive . '" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">KÍCH HOẠT TÀI KHOẢN</a>
                    </div>
                </div>
            </div>
            ';
            
            sendMail($to, $subject, $content);
            
            // SỬA 4: Khi đăng ký thành công, lưu thông báo vào Flash Session và CHUYỂN HƯỚNG ngay sang Login.
            // Trang Login sẽ nhận được Session này và hiện thông báo màu xanh.
            setSessionFlash('msg', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt.');
            setSessionFlash('msg_type', 'success');
            header("Location: " . _HOST_URL . "?module=auth&action=login");
            exit; 

        } else {
            $msg = 'Lỗi hệ thống không thể đăng ký.';
        }
    } 
    // NẾU CÓ LỖI
    else {
        $msg = 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại!';
        
        // SỬA 5 (Lý do gây lỗi sập trang Login): 
        // Bỏ lưu setSessionFlash('error', $erorr) ở đây! 
        // Vì ta không chuyển trang, ta hiển thị lỗi trực tiếp trên trang Register bằng biến $erorr nên không cần nhét vào Session.
    }
}
?>

<div class="container" style="max-width: 500px; margin: 50px auto;">
    <div class="login-container shadow p-4 bg-white rounded">
        <h3 class="text-center mb-4" style="font-weight: 700; color: #333;">REGISTER</h3>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?php echo (empty($erorr)) ? 'success' : 'danger'; ?> text-center">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-outline mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Nhập họ tên..." value="<?php echo $filter['fullname'] ?? ''; ?>" />
                <?php echo (!empty($erorr['fullname'])) ? '<small class="text-danger">' . reset($erorr['fullname']) . '</small>' : '';  ?>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại..." value="<?php echo $filter['phone'] ?? ''; ?>" />
                <?php echo (!empty($erorr['phone'])) ? '<small class="text-danger">' . reset($erorr['phone']) . '</small>' : ''; ?>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Email address</label>
                <input type="text" name="email" class="form-control" placeholder="example@gmail.com" value="<?php echo $filter['email'] ?? ''; ?>" />
                <?php echo (!empty($erorr['email'])) ? '<small class="text-danger">' . reset($erorr['email']) . '</small>' : ''; ?>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" />
                <?php echo (!empty($erorr['password'])) ? '<small class="text-danger">' . reset($erorr['password']) . '</small>' : ''; ?>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" />
                <?php echo (!empty($erorr['confirm_password'])) ? '<small class="text-danger">' . reset($erorr['confirm_password']) . '</small>' : ''; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block w-100">CREATE ACCOUNT</button>

            <div class="text-center mt-3">
                <p>Already a member? <a href="<?php echo _HOST_URL ?>?module=auth&action=login">Login here</a></p>
            </div>
        </form>
    </div>
</div>