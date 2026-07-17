#!/usr/bin/env php
<?php

/**

title=测试 aiZen::getPostData();
timeout=0
cid=0

- 测试POST数据优先返回属性data:name @测试智能体
- 测试POST数据保留数组字段数量属性fieldCount @2
- 测试POST数据不会返回错误属性hasError @0
- 测试空请求体JSON解析失败属性isNull @1
- 测试空请求体返回错误信息属性error @JSON解析失败：Syntax error

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$aiTest = new aiZenTest();

$post = array();
$post['name']   = '测试智能体';
$post['fields'] = array(
    array('name' => '标题', 'type' => 'text'),
    array('name' => '描述', 'type' => 'textarea')
);

r($aiTest->getPostDataTest($post)) && p('data:name')  && e('测试智能体');
r($aiTest->getPostDataTest($post)) && p('fieldCount') && e('2');
r($aiTest->getPostDataTest($post)) && p('hasError')   && e('0');
r($aiTest->getPostDataTest())      && p('isNull')     && e('1');
r($aiTest->getPostDataTest())      && p('error')      && e('JSON解析失败：Syntax error');
