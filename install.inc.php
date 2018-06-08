<?php
/**
 * FIFA赛事表
 *
 */
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `xiaojizhe_fifa_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `jia_name` varchar(100),
  `jia_img` varchar(100),
  `yi_name` varchar(100),
  `yi_img` varchar(100),
  `start_time` int(20),
  `is_end` int(1) default 0,
  `jia_fen` int(2),
  `yi_fen` int(2),
  `bf_integral` int(10),
  `spf_integral` int(10),
  `basic_integral` int(10) default 1000,
  `fj_status` int(1) default 0
) ENGINE=MyISAM;
EOF;
DB::query($sql);
/**
 * 参与用户表
 */
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `xiaojizhe_fifa_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(10) not null,
  `u_nickname` VARCHAR(100),
  `u_phone` VARCHAR(20)
) ENGINE=MyISAM;
EOF;
DB::query($sql);
/**
 * 猜比分投注记录
 */
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `xiaojizhe_fifa_bifen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(10) NOT NULL,
  `game_id` int(10) not null,
  `cai_jia` int(2),
  `cai_yi` int(2),
  `cai_time` int(20)
) ENGINE=MyISAM;
EOF;
DB::query($sql);
/**
 * 猜胜平负投注记录
 */
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `xiaojizhe_fifa_spf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(10) NOT NULL,
  `sai_id` int(10) not null,
  `cai_spf` int(1),
  `cai_time`int(20)
) ENGINE=MyISAM;
EOF;
DB::query($sql);
/**
 * FIFA中奖列表
 */
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `xiaojizhe_fifa_lucker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `u_id` int(10) not null,
  `sai_id` int(10) not null,
  `luck_integral` int(10),
  `dh_status` int(1) default 0
) ENGINE=MyISAM;
EOF;
DB::query($sql);
echo "数据表添加成功";
