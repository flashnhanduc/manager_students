<?php
if (!defined('_NhanDuc')) {
  die('Truy cap kh hop le');
}

// require_once './templates/assets/layouts/header-auth.php';
layout('header-auth');

if (isPost()) {
  $filter = filterdata();
  $erorr = [];

  // validate email
  if (empty(trim($filter['email']))) {
    $erorr['email']['required'] = 'Email la bat buoc phai nhap';
  } else {
    if (!validateEmail(trim($filter['email']))) {
      $erorr['email']['isEmail'] = 'Email khong dung dinh dang';
    }
  }

  // validate password
  if (empty($filter['password'])) {
    $erorr['password']['required'] = 'Mat khau bat buoc phai nhap';
  } else {
    if (strlen(trim($filter['password'])) < 6) {
      $erorr['password']['length'] = 'Mat khau phai lon hon 6 ki tu';
    }
  }

  if (empty($erorr)) {
    $email = $filter['email'];
    $password = $filter['password'];
    $checkEmail = getOne("SELECT * FROM users where Email ='$email'");
//     echo '<pre>'; 
// print_r($checkEmail); 
// echo '</pre>';
// die();
    if (!empty($checkEmail)) {
      if (!empty($password)) {
        $checkStatus = password_verify($password, $checkEmail['password']);
        if ($checkStatus) {
          $token = sha1(uniqid() . time());
          setSessionFlash('token_login', $token);
          $data = [
            'token' => $token,
            'create_at' => date('Y:m:d H:i:s'),
            'User_id' => $checkEmail["ID"]
          ];
          $inserToken = insert('token_login', $data);
          if ($inserToken) {
            setSessionFlash('msg', 'Đăng nhập thành công.');
            setSessionFlash('msg_type', 'success');
            redirect("/");
          } else {
            setSessionFlash('msg', 'Đăng nhập không thành công.');
            setSessionFlash('msg_type', 'success');
          }
        } else {
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
}

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$olddata = getSessionFlash('oldData') ?? [];
$erorrArr = getSessionFlash('errors') ?? [];
?>

<div class="login-container">
  <h3 class="text-center mb-4" style="font-weight: 700; color: #333;">Login</h3>

  <?php
  if (!empty($msg) && !empty($msg_type)) {

    getMsg($msg, $msg_type);
  }
  ?>

  <form action="" method="POST">
    <!-- Email  -->
    <div data-mdb-input-init class="form-outline mb-4">
      <label class="form-label" for="form2Example1">Email </label>
      <input type="email" name="email" id="form2Example1" value="<?php echo $olddata['email'] ?? ''; ?>" class="form-control" />

      <?php echo (!empty($erorrArr['email'])) ? '<small class="text-danger">' . reset($erorrArr['email']) . '</small>' : ''; ?>
    </div>

    <!-- Password input -->
    <div data-mdb-input-init class="form-outline mb-4">
      <label class="form-label" for="form2Example2">Password</label>
      <input type="password" name="password" id="form2Example2" class="form-control" />
      <?php echo (!empty($erorrArr['password'])) ? '<small class="text-danger">' . reset($erorrArr['password']) . '</small>' : ''; ?>
    </div>

    <div class="row mb-4">
      <div class="col d-flex justify-content-center">
        <!-- Checkbox -->
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
          <label class="form-check-label" for="form2Example31"> Remember me </label>
        </div>
      </div>

      <div class="col">
        <!-- Simple link -->
        <a href="<?php echo _HOST_URL ?>?module=auth&action=forgot">Forgot password?</a>
      </div>
    </div>

    <!-- Submit button -->
    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>

    <!-- Register buttons -->
    <div class="text-center">
      <p>Not a member? <a href="<?php echo _HOST_URL ?>?module=auth&action=register">Register</a></p>
    </div>
  </form>
</div>