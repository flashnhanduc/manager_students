<?php
if(!defined( '_NhanDuc')){
    die ('Truy cap kh hop le');
}
function getAll($sql){
    global $conn;

    $stm = $conn -> prepare($sql);

    $stm -> execute();

    $result =$stm ->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
function getRows($sql){
    global $conn;

    $stm = $conn -> prepare($sql);

    $stm -> execute();

    $rel = $stm -> rowCount();

    return $rel;
}
function getOne($sql){
    global $conn;

    $stm = $conn -> prepare($sql);

    $stm -> execute();

    $result =$stm ->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}
// Insert dữ liệu
function insert($table, $data){
    /*
        $data = [
            'name' => 'Hùng',
            'email' => 'duc205@gmail.com',
            'phone' => '0389839375'
        ];
    */

    global $conn;

    $keys = array_keys($data);
    $cot = implode(',', $keys);
    $place = ':'.implode(',:', $keys);

    $sql = "INSERT INTO $table ($cot) VALUES ($place)"; // :name, :email...
    // echo $sql;

    $stm = $conn -> prepare($sql); // Chống SQL Injection

    // Thực thi câu lệnh
    $rel =$stm -> execute($data);
    return $rel;
}
// Update dữ liệu
function update($table, $data, $condition){
    /*
        $data = [
            'name' => 'Hùng Update',
            'email' => 'hung_new@gmail.com'
        ];
        $condition = "id = 1"; // Điều kiện để update
    */
    
    global $conn;

    $updateStr = '';
    foreach ($data as $key => $value) {
        $updateStr .= "$key=:$key,"; // Tạo chuỗi dạng: name=:name, email=:email,
    }

    $updateStr = rtrim($updateStr, ','); // Xóa dấu phẩy thừa ở cuối chuỗi

    $sql = "UPDATE $table SET $updateStr WHERE $condition";
    // echo $sql; // Bạn có thể echo để kiểm tra câu lệnh trước khi chạy

    $stm = $conn->prepare($sql);

    // Thực thi câu lệnh với dữ liệu truyền vào
    return $stm->execute($data);
}
function delete($table, $condition = ''){
    global $conn;

    // Bắt buộc phải có điều kiện mới cho xóa. Nếu rỗng, từ chối thực thi.
    if (empty(trim($condition))) {
        return false; 
    } 

    $sql = "DELETE FROM $table WHERE $condition";
    
    $stm = $conn->prepare($sql);
    return $stm->execute();
}
//lấy dòng dữ liệu new
function lastID(){
    global $conn ;
    return $conn -> lastInsertId();
}