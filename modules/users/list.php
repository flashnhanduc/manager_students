<?php

if (!defined('_NhanDuc')) {
  die('Truy cap kh hop le');
}
layout('header');
layout('sidebar');
$filter = filterData();
$stringWhere = '';
$group = 0;
$keywork = '';

if (isGET()) {
  if (isset($filter['keywork'])) {
    $keywork = $filter['keywork'];
  }
  if (isset($filter['group'])) {
    $group = $filter['group'];
  }

  if (!empty($keywork)) {
    if ($stringWhere == '') {
      $stringWhere .= ' WHERE ';
    } else {
      $stringWhere .= ' AND ';
    }
    $stringWhere .= " (a.FullName LIKE '%$keywork%' OR a.Email LIKE '%$keywork%') ";
  }

  if (!empty($group)) {
    if ($stringWhere == '') {
      $stringWhere .= ' WHERE ';
    } else {
      $stringWhere .= ' AND ';
    }
    $stringWhere .= " a.Group_id = $group ";
  }
}
// xu ly pagination 
$maxData = getRows("SELECT ID FROM users"); // ham get dem cot 
$perPage = 3;
$maxPage = ceil($maxData / $perPage);
$offset = 0;
//get page 
if (isset($filter['page'])) {
  $page = $filter['page'];
}
if ($page > $maxPage || $page < 1) {
  $page = 1;
}
if (isset($page)) {
  $offset = ($page - 1) * $perPage;
}
// echo $maxPage;

$getDataUser = getAll("SELECT a.ID, a.FullName, a.Email, a.Create_at, b.name 
FROM users a INNER JOIN user_groups b 
ON a.Group_id = b.id $stringWhere 
ORDER BY a.Create_at DESC
LIMIT $offset, $perPage
");
// echo '<pre>';
// print_r($filter);
// echo '</pre>';
// die();

$getDataGroup = getAll("SELECT * FROM user_groups");
//  echo '<pre>';
// print_r($getDataGroup);
// echo '</pre>';
// die()
?>
<div class="container grid-user">
  <div class="container-fluid">
    <a href="?modules=users&action=add" class="btn btn-success mb-3">
      <i class="fa-solid fa-plus"></i> Thêm mới người dùng
    </a>
    <form class="mb-3" action="" method="GET">
      <input type="hidden" name="module" value="users">
      <input type="hidden" name="action" value="list">
      <div class="row">
        <div class="col-3">
          <select name="group" class="form-control" id="">
            <option value="">Nhóm người dùng</option>
            <?php foreach ($getDataGroup as $items): ?>
              <option value="<?php echo $items['id']; ?>" <?php echo ($group == $items['id']) ? 'selected' : ''; ?>>
                <?php echo $items['Name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-7">
          <input type="text" name="keywork" value="<?php echo (!empty($keywork)) ? $keywork : false; ?>" class="form-control" placeholder="Nhập thông tin tìm kiếm">
        </div>
        <div class="col-2">
          <button type="submit" name="" class="btn btn-primary w-100">Tìm kiếm</button>
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
        <?php
        foreach ($getDataUser as $key => $items):
        ?>
          <tr>
            <th scope="row"><?php echo $key + 1 ?></th>
            <td><?php echo $items['FullName'] ?></td>
            <td><?php echo $items['Email'] ?></td>
            <td><?php echo $items['Create_at'] ?></td>
            <td><?php echo $items['name'] ?></td>
            <td><a href="?module=users&action=permission&id=<?php echo $items['ID'] ?>" class="btn btn-primary">Phân Quyền</a></td>
            <td><a href="?module=users&action=edit&id=<?php echo $items['ID'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="?module=users&action=delete&id=<?php echo $items['ID'] ?>" onclick="return confirm('Bạn có xác nhận xóa không')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
          </tr>
        <?php
        endforeach;
        ?>
      </tbody>
    </table>
<nav aria-label="Page navigation example">
  <ul class="pagination">
    
    <!-- Nút Previous -->
    <?php if ($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?module=users&action=list&page=<?php echo ($page - 1); ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Tính toán giới hạn nút số -->
    <?php
    $start = $page - 1;
    if ($start < 1) {
      $start = 1;
    }
    
    $end = $page + 1;
    if ($end > $maxPage) {
      $end = $maxPage;
    }
    ?>

    <!-- In dấu 3 chấm nếu khoảng cách đầu còn xa -->
    <?php if ($start > 1): ?>
      <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
    <?php endif; ?>

    <!-- Vòng lặp in các số trang -->
    <?php for ($i = $start; $i <= $end; $i++) : ?>
      <!-- Nếu $i đúng bằng trang hiện tại, thêm class 'active' để nút sáng lên -->
      <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
        <a class="page-link" href="?module=users&action=list&page=<?php echo $i; ?>">
          <?php echo $i; ?>
        </a>
      </li>
    <?php endfor; ?>

    <!-- In dấu 3 chấm nếu khoảng cách đuôi còn xa -->
    <?php if ($end < $maxPage): ?>
      <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
    <?php endif; ?>

    <!-- Nút Next -->
    <?php if ($page < $maxPage): ?>
      <li class="page-item">
        <!-- Đã sửa aria-label thành Next -->
        <a class="page-link" href="?module=users&action=list&page=<?php echo ($page + 1); ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    <?php endif; ?>

  </ul>
</nav>
  </div>

</div>

<?php
layout('footer')
?>