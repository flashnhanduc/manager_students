<?php

if (!defined('_NhanDuc')) {
  die('Truy cap kh hop le');
}
layout('header');
layout('sidebar')
?>
<div class="container grid-user">
  <div class="container-fluid">
    <a href="?modules=users&action=add" class="btn btn-success mb-3">
      <i class="fa-solid fa-plus"></i> Thêm mới người dùng
    </a>
    <form class="mb-3" action="">
      <div class="row">
        <div class="col-3">
          <select name="" class="form-control" id="">
            <option value="">Nhóm người dùng</option>
            <option value="">admin</option>
            <option value="">student</option>
          </select>
        </div>
        <div class="col-7">
          <input type="text" name="" class="form-control" placeholder="Nhập thông tin tìm kiếm">
        </div>
        <div class="col-2">
          <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
        </div>
      </div>
    </form>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">STT</th>
          <th scope="col">Họ Và Tên</th>
          <th scope="col">Email</th>
          <th scope="col">Ngày Đăng Ký</th>
          <th scope="col">Nhóm</th>
          <th scope="col">Phân Quyền</th>
          <th scope="col">Sửa</th>
          <th scope="col">Xóa</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
          <td>Otto</td>
          <td><button class="btn btn-primary">Phân Quyền</button></td>
          <td><button class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></button></td>
          <td><button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php
layout('footer')
?>