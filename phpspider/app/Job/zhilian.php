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
        'sou.zhaopin.com',
        'jobs.zhaopin.com'
    ],
    'scan_urls' => [
        "http://sou.zhaopin.com/jobs/searchresult.ashx?jl=北京&kw=php&sm=0&p=1"
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
        [
            'name' => "url",  //网站地址
            'selector' => "//div[@class='h1-tit rel']/h1",
            'required' => false,
        ],
        [
            'name' => "salary_min",  //薪水小
            'selector' => "//div[6]/div[1]/ul/li[1]/strong/text()",
            'required' => false,
        ],
        [
            'name' => "salary_max",  //薪水大
            'selector' => "//div[6]/div[1]/ul/li[1]/strong/text()",
            'required' => false,
        ],
        [
            'name' => "welfare",  //福利
            'selector' => "//div[5]/div[1]/div[1]/div",
            'required' => false,
        ],
        [
            'name' => "experience",  //经验
            'selector' => "//div[6]/div[1]/ul/li[5]/strong",
            'required' => false,
        ],
        [
            'name' => "education",  //学历
            'selector' => "//div[6]/div[1]/ul/li[6]/strong",
            'required' => false,
        ],
        [
            'name' => "number",  //招聘人数
            'selector' => "//div[6]/div[1]/ul/li[7]/strong",
            'required' => false,
        ],
        [
            'name' => "company",   //公司
            'selector' => "//div[6]/div[2]/div[1]/p/a/text()",
            'required' => false,
        ],
        [
            'name' => "scale",   //规模
            'selector' => "//div[6]/div[2]/div[1]/ul/li[1]/strong",
            'required' => false,
        ],
        [
            'name' => "nature",   //公司性质
            'selector' => "//div[6]/div[2]/div[1]/ul/li[2]/strong",
            'required' => false,
        ],
        [
            'name' => "industry",  //公司行业
            'selector' => "//div[6]/div[2]/div[1]/ul/li[3]/strong/a/text()",
            'required' => false,
        ],
        [
            'name' => "address",    //地址
            'selector' => "//div[6]/div[2]/div[1]/ul/li[last()]/strong/text()",
            'required' => false,
        ],
        [
            'name' => "publish_date",  //发布日期
            'selector' => "//div[6]/div[1]/ul/li[3]/strong/span/text()",
            'required' => false,
        ],
    ],
];

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    db::set_connect('default', $db_config);
    db::init_mysql();

    //初步统计25页
    for ($i = 2; $i < 26; $i++)
    {
        $url = "http://sou.zhaopin.com/jobs/searchresult.ashx?jl=北京&kw=php&sm=0&p={$i}";
        $phpspider->add_scan_url($url);
    }
};

$spider->on_extract_field = function ($fieldname, $data, $page) {

    if ($fieldname == 'url') {
        $data = $page['url'];
    }

    if ($fieldname == 'salary_min') {
        $data = str_replace('元/月', '', $data);
        if(strpos($data, '-')){
            $arr = explode('-', $data);
            $data = $arr[0];
        }
    }

    if ($fieldname == 'salary_max') {
        $data = str_replace('元/月', '', $data);
        if(strpos($data, '-')){
            $arr = explode('-', $data);
            $data = $arr[1];
        }
    }

    if ($fieldname == 'welfare') {
        $repalce_arr = ['<span>', '</span>'];
        $data = str_replace($repalce_arr, ' ', $data);
    }

    if ($fieldname == 'number' || $fieldname == 'scale') {
        $data = str_replace('人', '', $data);
    }

    $data = trim($data);
    return $data;
};

$spider->start();
