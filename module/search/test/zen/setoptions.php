#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptions();
timeout=0
cid=0

 >> id,include,and,搜索
 >> 1,测试查询2
 >> 0
 >> 高,低
 >> 可以用逗号连接多个ID进行搜索。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$table = zenData('userquery');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->module->range('story,task,bug');
$table->title->range('查询1,查询2,查询3,查询4,查询5,查询6,查询7,查询8,查询9,查询10');
$table->form->range('form1,form2,form3');
$table->sql->range('sql1,sql2,sql3');
$table->gen(5);

su('admin');

$searchTest = new searchTest();

r($searchTest->setOptionsTest(
    array('id' => 'ID', 'title' => '标题', 'status' => '状态'),
    array(
        'id' => array('operator' => 'include', 'control' => 'input'),
        'title' => array('operator' => 'include', 'control' => 'input'),
        'status' => array('operator' => '=', 'control' => 'select', 'values' => array('active' => '激活', 'closed' => '关闭'))
    ),
    array()
)) && p('fields:0:name,operators:0:value,andOr:0:value,searchBtnText') && e('id,include,and,搜索');

r($searchTest->setOptionsTest(
    array('title' => '标题', 'content' => '内容'),
    array(
        'title' => array('operator' => 'include', 'control' => 'input'),
        'content' => array('operator' => 'include', 'control' => 'textarea')
    ),
    array(
        (object)array('id' => 1, 'title' => '测试查询1'),
        (object)array('id' => 2, 'title' => '测试查询2')
    )
)) && p('savedQuery:0:id,savedQuery:1:title') && e('1,测试查询2');

r($searchTest->setOptionsTest(
    array(),
    array(),
    array()
)) && p('fields') && e('0');

r($searchTest->setOptionsTest(
    array('priority' => '优先级', 'assignedTo' => '指派给'),
    array(
        'priority' => array('operator' => '=', 'control' => 'select', 'values' => array('1' => '高', '2' => '中', '3' => '低')),
        'assignedTo' => array('operator' => '=', 'control' => 'select')
    ),
    array()
)) && p('fields:0:values:1,fields:0:values:3') && e('高,低');

r($searchTest->setOptionsTest(
    array('id' => 'ID', 'name' => '名称'),
    array(
        'id' => array('operator' => 'include', 'control' => 'input'),
        'name' => array('operator' => 'include', 'control' => 'input')
    ),
    array()
)) && p('fields:0:placeholder') && e('可以用逗号连接多个ID进行搜索。');