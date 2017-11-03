<?php
/**
 * Created by PhpStorm.
 * User: qiang
 * Date: 2017/10/12
 * Time: 下午5:16
 */

include_once "../../library/db.class.php";

$db_config = [
    'db_type' => 'mysql',     // 数据库类型
    'db_host' => 'localhost', // 数据库服务器地址
    'db_port' => '3306', // 数据库端口
    'db_user' => 'root', // 数据库用户名
    'db_pass' => 'root', // 数据库密码
    'db_name' => 'phpspider',  // 数据库名称
];
$start = $_REQUEST['start'];
$length = $_REQUEST['length'];

$db = new DB($db_config);
$rows = $db->table('rental')->limit("$start, $length")->find();
$rental_data = [];
foreach($rows as $key => $row){
    $row['title'] = "<a href = '".$row['url']."' target='blank'>". $row['title'] . "</a>";
    unset($row['id']);
    unset($row['url']);
    $rental_data[]= $row;
}
$sum = $db->table('rental')->count();

$data['recordsTotal'] = $sum;
$data['recordsFiltered'] = $sum;
$data['data'] = $rental_data;
echo json_encode($data);






