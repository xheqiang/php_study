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

//拍不出数据 使用安居客爬

$configs = [
    'name' => '郑州新房-房天下',    //爬虫名称
    'log_show' => false,     //显示日志
    'log_file' => '../../data/log/House/zhengzhou_fang.log',   //日志文件
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
        'port' => 8806,
        'user' => 'vega',
        'pass' => 'vegagame',
        'name' => 'laravel_admin_study',
    ],
    'domains' => [      //爬取哪些域名下的网页
        'fang.com',
        'zz.fang.com',
        'newhouse.zz.fang.com'
    ],
    'scan_urls' => [
        "http://newhouse.zz.fang.com/house/s/b91/?ctm=1.zz.xf_search.page.2"
    ],
    'list_url_regexes' => [],
    'content_url_regexes' => [     //内容页面
        "http://newhouse.zz.fang.com/house/.*",
    ],
    'fields' => [
        [
            'name' => "name",  //楼盘名称
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[1]/div/h1/strong",
            'required' => false,
        ],
        [
            'name' => "url",  //网站地址
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[1]/div/h1/strong",
            'required' => false,
        ],
        [
            'name' => "money",  //均价
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[2]/div[1]/span",
            'required' => false,
        ],
        [
            'name' => "address",  //地址
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[10]/div/span",
            'required' => false,
        ],
        [
            'name' => "open_time",  //开盘时间
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[11]/div[1]/a",
            'required' => false,
        ],
        [
            'name' => "property_right",  //产权
            'selector' => "//div[7]/div/div[1]/div[1]/ul/li[5]/div[2]/div/p",
            'required' => false,
        ],
        [
            'name' => "developer",  //开发商
            'selector' => "//div[7]/div/div[1]/div[1]/ul/li[7]/div[2]",
            'required' => false,
        ],
        [
            'name' => "permit",  //预售证
            'selector' => "//div[7]/div/div[1]/div[2]/ul/li[8]/div[2]",
            'required' => false,
        ],
        [
            'name' => "property",  //物业
            'selector' => "//div[7]/div/div[1]/div[4]/ul/li[8]/div[2]",
            'required' => false,
        ],
        [
            'name' => "property_money",   //物业费
            'selector' => "//div[7]/div/div[1]/div[4]/ul/li[9]/div[2]",
            'required' => false,
        ],
        [
            'name' => "score",   //评分
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[1]/div/a/text()",
            'required' => false,
        ],
        [
            'name' => "telphone",   //电话
            'selector' => "//div[4]/div[3]/div[2]/div[1]/div[5]/div/p",
            'required' => false,
        ]
    ],
];

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    db::set_connect('default', $db_config);
    db::init_mysql();

    //初步统计25页
    /*for ($i = 2; $i < 25; $i++)
    {
        $j = $i + 1;
        $url = "http://newhouse.zz.fang.com/house/s/b9{$i}/?ctm=1.zz.xf_search.page.{$j}";
        $phpspider->add_scan_url($url);
    }*/
};

$spider->on_extract_field = function ($fieldname, $data, $page) {

    if ($fieldname == 'url') {
        $data = $page['url'];
    }

    $data = trim($data);
    return $data;
};

$spider->start();