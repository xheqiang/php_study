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

// 不考虑一居室 查找0-250w的市六区的普通住宅  pg{$i}sf1l2l3l4l5ep250/
// 市内六区域名规则 tj.lianjia.com/ershoufang/nankai|heping|hedong|hexi|hebei|hongqiao.../限定条件
$scan_area = 'heping';  //优化为一次性scan
$scan_config = [
    "heping" => [
        "page" => 8,
        "scan_url" => "https://tj.lianjia.com/ershoufang/heping/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/heping/pgpagesf1l2l3l4l5ep250/"
    ],
    "nankai" => [
        "page" => 81,
        "scan_url" => "https://tj.lianjia.com/ershoufang/nankai/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/nankai/pgpagesf1l2l3l4l5ep250/"
    ],
    "hexi" => [
        "page" => 66,
        "scan_url" => "https://tj.lianjia.com/ershoufang/hexi/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/hexi/pgpagesf1l2l3l4l5ep250/"
    ],
    "hebei" => [
        "page" => 57,
        "scan_url" => "https://tj.lianjia.com/ershoufang/hebei/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/hebei/pgpagesf1l2l3l4l5ep250/"
    ],
    "hedong" => [
        "page" => 81,
        "scan_url" => "https://tj.lianjia.com/ershoufang/hedong/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/hedong/pgpagesf1l2l3l4l5ep250/"
    ],
    "hongqiao" => [
        "page" => 37,
        "scan_url" => "https://tj.lianjia.com/ershoufang/hongqiao/pg1sf1l2l3l4l5ep250/",
        "scan_url_rule" => "https://tj.lianjia.com/ershoufang/hongqiao/pgpagesf1l2l3l4l5ep250/"
    ],
];
$configs = [
    'name' => '天津二手房-市内六区',    //爬虫名称
    'log_show' => false,     //显示日志
    'log_file' => '../../data/log/House/tianjin_lianjia.log',   //日志文件
    'log_type' => '', //记录日志类型
    'input_encoding' => null,   //输入编码 null 自动识别
    'output_encoding' => 'utf-8',   //写入编码
    'tasknum' => '1',   //同时工作的爬虫任务数
    'interval' => 2000, //爬取每个网页时间间隔 单位毫秒
    'timeout' => 5,     //每个网页超时时间 默认秒
    'max_try' => 5,     //失败最大尝试次数
    'max_depth' => 1,   //爬取网页深度
    'user_agent' => phpspider::AGENT_PC, //浏览器类型
    'client_ip' => '110.97.33.111',   //爬虫IP
    'export' => [       //导出类型
        'type' => 'db',
        'table' => 'phpspider_house',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ],
    'db_config' => [    //数据库配置
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'pass' => 'root',
        'name' => 'laravel_admin_study',
    ],
    'domains' => [      //爬取哪些域名下的网页
        'lianjia.com',
        'tj.lianjia.com',
        'tj.lianjia.com'
    ],
    'scan_area' => $scan_area,
    'scan_config' => $scan_config,
    'scan_urls' => [
        $scan_config[$scan_area]['scan_url']
    ],
    'list_url_regexes' => [],
    'content_url_regexes' => [     //内容页面
        "https://tj.lianjia.com/ershoufang/.*.html",
    ],
    'fields' => [
        [
            'name' => "title",  //标题
            'selector' => "//div[@class='title']/h1[@class='main']",
            'required' => false,
        ],
        [
            'name' => "url",  //url
            'selector' => "//div[@class='title']/h1[@class='main']",
            'required' => false,
        ],
        [
            'name' => "village",  //小区
            'selector' => "//div[@class='communityName']/a[@class='info ']",
            'required' => false,
        ],
        [
            'name' => "area",  //市六区
            'selector' => "//div[@class='areaName']/span[@class='info']/a[1]",
            'required' => false,
        ],
        [
            'name' => "region",  //区域
            'selector' => "//div[@class='areaName']/span[@class='info']/a[2]",
            'required' => false,
        ],
        [
            'name' => "total_price",  //总价
            'selector' => "//div[@class='price ']/span[@class='total']",
            'required' => false,
        ],
        [
            'name' => "unit_price",  //单价
            'selector' => "//div[@class='unitPrice']/span/text()",
            'required' => false,
        ],
        [
            'name' => "house_type",  //户型
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[1]/text()",
            'required' => false,
        ],
        [
            'name' => "acreage",  //面积
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[3]/text()",
            'required' => false,
        ],
        [
            'name' => "floor",  //楼层
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[2]/text()",
            'required' => false,
        ],
        [
            'name' => "orientation",  //朝向
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[7]/text()",
            'required' => false,
        ],
        [
            'name' => "renovation",  //装修
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[9]/text()",
            'required' => false,
        ],
        [
            'name' => "property_right",  //产权
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[13]/text()",
            'required' => false,
        ],
        [
            'name' => "heating",  //供暖
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[11]/text()",
            'required' => false,
        ],
        [
            'name' => "floor_scale",  //梯户比例
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[10]/text()",
            'required' => false,
        ],
        [
            'name' => "building_type",  //建筑类型
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[6]/text()",
            'required' => false,
        ],
        [
            'name' => "elevator",  //配备电梯
            'selector' => "//div[@class='base']/div[@class='content']/ul/li[12]/text()",
            'required' => false,
        ],
        [
            'name' => "listing_time",  //挂牌时间
            'selector' => "//div[@class='transaction']/div[@class='content']/ul/li[1]/span[2]",
            'required' => false,
        ],
        [
            'name' => "power_belong",  //产权所属
            'selector' => "//div[@class='transaction']/div[@class='content']/ul/li[6]/span[2]",
            'required' => false,
        ],
        [
            'name' => "house_age",  //房屋年限 是否满五...
            'selector' => "//div[@class='transaction']/div[@class='content']/ul/li[5]/span[2]",
            'required' => false,
        ],
        [
            'name' => "house_purpose",  //房屋用途
            'selector' => "//div[@class='transaction']/div[@class='content']/ul/li[4]/span[2]",
            'required' => false,
        ],
        [
            'name' => "mortgage",  //抵押情况
            'selector' => "//div[@class='transaction']/div[@class='content']/ul/li[7]/span[2]",
            'required' => false,
        ],
    ],
];

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    db::set_connect('default', $db_config);
    db::init_mysql();

    $scan_area = $phpspider->get_config("scan_area"); //新增配置不然无法获取
    $scan_config = $phpspider->get_config("scan_config");
    foreach($scan_config as $skey => $sval){
        for ($i = 1; $i <= $sval['page']; $i++){
            $url = str_replace("page", $i, $sval['scan_url_rule']);
            if($skey != $scan_area || $i != 1){   
                $phpspider->add_scan_url($url);
            }
        }
    }
    
};

$spider->on_extract_field = function ($fieldname, $data, $page) {

    if ($fieldname == 'url') {
        $data = $page['url'];
    }

    $data = trim($data);
    return $data;
};

$spider->start();
