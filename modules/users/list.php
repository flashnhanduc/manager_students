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

if(isGET()){
    if(isset($filter['keywork'])){
        $keywork = $filter['keywork'];
    }
    if(isset($filter['group'])){
        $group = $filter['group'];
    }

    if(!empty($keywork)){
        if($stringWhere == ''){
            $stringWhere .= ' WHERE ';
        } else {
            $stringWhere .= ' AND ';
        }
        $stringWhere .= " (a.FullName LIKE '%$keywork%' OR a.Email LIKE '%$keywork%') ";
    }

    if(!empty($group)){
        if($stringWhere == ''){
            $stringWhere .= ' WHERE ';
        } else {
            $stringWhere .= ' AND ';
        }
        $stringWhere .= " a.Group_id = $group ";
    }
}

$getDataUser = getAll("SELECT a.ID, a.FullName, a.Email, a.Create_at, b.name 
FROM users a INNER JOIN user_groups b 
ON a.Group_id = b.id $stringWhere 
ORDER BY a.Create_at DESC");
// echo '<pre>';
// print_r($getDataUser);
// echo '</pre>';
// die()

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
            <?php foreach($getDataGroup as $items): ?>
            <option value="<?php echo $items['id']; ?>" <?php echo ($group == $items['id']) ? 'selected' : ''; ?>>
        <?php echo $items['Name']; ?> 
    </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-7">
          <input type="text" name="keywork" value="<?php echo (!empty($keywork)) ? $keywork : false ; ?>" class="form-control" placeholder="Nhập thông tin tìm kiếm">
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
          <th scope="row"><?php echo $key+1 ?></th>
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
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>
    </nav>
    </nav>
  </div>

</div>

<?php
layout('footer')
?>