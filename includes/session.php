<?php
if(!defined( '_NhanDuc')){
    die ('Truy cap kh hop le');
}
//set session
function setSession ($key ,$value){
    if (!empty(session_id())){
        $_SESSION[$key] =$value;
        return true ;
    }
    return false;
}
//get session
function getSession ($key = ''){
    if(empty($key)){            // Nếu KHÔNG truyền key nào cả
        return $_SESSION;       // Mới trả về toàn bộ Session
    }
    
    // Nếu có truyền key, thì chỉ lấy đúng phần tử đó ra thôi
    if(isset($_SESSION[$key])){
        return $_SESSION[$key];
    }
    
    return false;
}
function removeSession ($key = ''){
    if(empty($key)){
        session_destroy();
        return true;
    }
    else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        return true;
    }
    return false;
}
function setSessionFlash($key , $value){
    $key = $key.'Flash';
    $rel = setSession($key,$value);
    return $rel;
}
function getSessionFlash($key){
    $key = $key .'Flash';
    $rel = getSession($key);
    removeSession($key);
    return $rel;
}