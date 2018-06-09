<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/9
 * Time: 13:39
 */
$password = $_GET['password'];
if($password=='555389'){
    DB::query("DROP TABLE `xiaojizhe_fifa_game`");
    DB::query("DROP TABLE `xiaojizhe_fifa_user`");
    DB::query("DROP TABLE `xiaojizhe_fifa_bifen`");
    DB::query("DROP TABLE `xiaojizhe_fifa_spf`");
    DB::query("DROP TABLE `xiaojizhe_fifa_lucker`");
    echo "Íê³É";
}