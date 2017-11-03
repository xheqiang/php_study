<?php
/**
 * Created by PhpStorm.
 * User: qiang
 * Date: 2017/10/10
 * Time: 下午4:31
 */

use phpspider\core\phpspider;
use phpspider\core\db;

require_once __DIR__ . '/../../autoloader.php';

/* Do NOT delete this comment */
/* 不要删除这段注释 */

//手动list
$scan_url_list = [];
for ($i = 0; $i < 32; $i++) {
    $scan_url_list[] = "http://zu.fang.com/house1-j041/c20-d23500-g21-i3{$i}-n31-l310/";
}
$scan_url_list = array_values($scan_url_list);

$configs = [
    'name' => '房天下-6号线',    //爬虫名称
    'log_show' => false,     //显示日志
    'log_file' => '../../data/Rental/Line6.log',   //日志文件
    'log_type' => '', //记录日志类型
    'input_encoding' => null,   //输入编码 null 自动识别
    'output_encoding' => 'utf-8',   //写入编码
    'tasknum' => '1',   //同时工作的爬虫任务数
    'interval' => 2000, //爬取每个网页时间间隔 单位毫秒
    'timeout' => 5,     //每个网页超时时间 默认秒
    'max_try' => 5,     //失败最大尝试次数
    'max_depth' => 1,   //爬取网页深度
    'user_agent' => phpspider::AGENT_PC, //浏览器类型
    'client_ip' => '180.97.33.107',   //爬虫IP
    'export' => [       //导出类型
        'type' => 'db',
        'table' => 'rental',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ],
    'db_config' => [    //数据库配置
        'host' => '127.0.0.1',
        'port' => 8806,
        'user' => 'vega',
        'pass' => 'vegagame',
        'name' => 'laravel_admin_study',
    ],
    'domains' => [      //爬取哪些域名下的网页
        'fang.com',
        'zu.fang.com'
    ],
    'scan_urls' => $scan_url_list,
    'list_url_regexes' => [],
    /*'scan_urls' => [    //定义爬虫的入口链接
        'http://zu.fang.com/house1-j041/c20-d23500-g21-i30-n31-l310/'
    ],
    'list_url_regexes' => [     //列表页面
        "http://zu.fang.com/house1-j041/c20-d23500-g21-i3\d+-n31-l310/"
    ],*/
    'content_url_regexes' => [     //内容页面
        "http://zu.fang.com/chuzu/.*.htm",
    ],

    'fields' => [
        [
            'name' => "title",  //标题
            'selector' => "//div[@class='h1-tit rel']/h1",
            'required' => false,
        ],
        [
            'name' => "url",  //网站地址
            'selector' => "//div[@class='h1-tit rel']/h1",
            'required' => false,
        ],
        [
            'name' => "room",   //厅室
            'selector' => "//div[5]/div[3]/div[2]/ul[1]/li[2]/text()[2]",
            'required' => false,
        ],
        [
            'name' => "area",   //面积
            'selector' => "//div[5]/div[3]/div[2]/ul[1]/li[2]/span[4]",
            'required' => false,
        ],
        [
            'name' => "money",   //租金
            'selector' => "//div[5]/div[3]/div[2]/ul[1]/li[1]/strong",
            'required' => false,
        ],
        [
            'name' => "method",  //出租方式
            'selector' => "//div[5]/div[3]/div[2]/ul[1]/li[1]/text()",
            'required' => false,
        ],
        [
            'name' => "village",    //小区
            'selector' => "//div/div/div/ul[@class='house-info']/li[3]/a[1]",
            'required' => false,
        ],
        [
            'name' => "address",    //地址
            'selector' => "//div[5]/div[3]/div[2]/ul[1]/li[4]/text()",
            'required' => false,
        ],
        [
            'name' => "contacts",    //联系人
            'selector' => "//div[5]/div[3]/div[2]/div/span[2]",
            'required' => false,
        ],
        [
            'name' => "telphone",    //联系电话
            'selector' => "//div[5]/div[3]/div[2]/div/span[1]",
            'required' => false,
        ],
        [
            'name' => "line",    //几号线
            'selector' => "//div[6]/div[3]/div[2]/div[1]/span[1]",
            'required' => false,
        ],
        [
            'name' => "updated_at",  //更新时间
            'selector' => "//div[5]/div[2]/p[1]/span[2]",
            'required' => false,
        ],
    ],
];

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    db::set_connect('default', $db_config);
    db::init_mysql();
};

$spider->on_extract_field = function ($fieldname, $data, $page) {

    if ($fieldname == 'title' && !empty($data)) {
        $data = str_replace('&#13;', '', $data);
    }

    if ($fieldname == 'area' && !empty($data)) {
        preg_match_all('/\d+/', $data, $matchs);
        if(!empty($matchs[0][1])){
            $data = $matchs[0][1] . "㎡";
        }
    }

    if ($fieldname == 'url') {
        $data = $page['url'];
    }

    if ($fieldname == 'method' && !empty($data)) {
        $data = str_replace('元/月', '', $data);
    }

    if ($fieldname == 'line') {
        $data = '6号线';
    }

    if ($fieldname == 'updated_at') {
        $data = str_replace('更新时间：', '', $data);
        $data = date("Y-m-d H:i:s", strtotime($data));
    }

    $data = trim($data);
    return $data;
};

$spider->start();
