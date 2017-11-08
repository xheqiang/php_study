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

$configs = [
    'name' => '智联招聘-PHP',    //爬虫名称
    'log_show' => false,     //显示日志
    'log_file' => '../../data/log/Job/zhilian.log',   //日志文件
    'log_type' => '', //记录日志类型
    'input_encoding' => null,   //输入编码 null 自动识别
    'output_encoding' => null,   //写入编码
    'tasknum' => '1',   //同时工作的爬虫任务数
    'interval' => 2000, //爬取每个网页时间间隔 单位毫秒
    'timeout' => 5,     //每个网页超时时间 默认秒
    'max_try' => 5,     //失败最大尝试次数
    'max_depth' => 1,   //爬取网页深度
    'user_agent' => phpspider::AGENT_PC, //浏览器类型
    'client_ip' => '180.97.33.107',   //爬虫IP
    'export' => [       //导出类型
        'type' => 'db',
        'table' => 'job',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ],
    'db_config' => [    //数据库配置
        'host' => '127.0.0.1',
        'port' => 8806,
        'user' => 'vega',
        'pass' => 'vegagame',
        'name' => 'laravel_admin_study',
    ],
    'domains' => [      //爬取哪些域名下的网页
        'zhaopin.com',
        'sou.zhaopin.com'
    ],
    'scan_urls' => [
        "http://sou.zhaopin.com/jobs/searchresult.ashx?jl=北京&kw=php&sm=0&p=1"
    ],
    'proxy' => [
        "123.186.228.92:4321",
    ],
    'list_url_regexes' => [],
    'content_url_regexes' => [     //内容页面
        "http://jobs.zhaopin.com/.*.htm.*",
    ],
    'fields' => [
        [
            'name' => "title",  //标题
            'selector' => "//div[5]/div[1]/div[1]/h1",
            'required' => false,
        ],
    ],
];

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    db::set_connect('default', $db_config);
    db::init_mysql();

    for ($i = 2; $i < 3; $i++)
    {
        $url = "http://sou.zhaopin.com/jobs/searchresult.ashx?jl=北京&kw=php&sm=0&p={$i}";
        $phpspider->add_scan_url($url);
    }
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
