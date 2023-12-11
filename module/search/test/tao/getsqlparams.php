#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getSummary();
timeout=0
cid=1

- 测试获取搜索 test 关键词的 unicode @test_
- 测试获取搜索 test 关键词的查询语句的 against 条件属性1 @(+"test_")
- 测试获取搜索 test 关键词的查询语句的 like 条件属性2 @OR title like '%test_%' OR content like '%test_%'
- 测试获取搜索 测试 关键词的 unicode @27979 35797
- 测试获取搜索 测试 关键词的查询语句的 against 条件属性1 @(+"27979 35797")
- 测试获取搜索 测试 关键词的查询语句的 like 条件属性2 @OR title like '%27979 35797%' OR content like '%27979 35797%'
- 测试获取搜索 12345 关键词的 unicode @|12345|
- 测试获取搜索 12345 关键词的查询语句的 against 条件属性1 @(+"|12345|") (-" 12345 ")
- 测试获取搜索 12345 关键词的查询语句的 like 条件属性2 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

$keywords = array('test', '测试', '12345');

$search = new searchTest();
r($search->getSqlParamsTest($keywords[0])) && p('0') && e('test_');                                                         //测试获取搜索 test 关键词的 unicode
r($search->getSqlParamsTest($keywords[0])) && p('1') && e('(+"test_")');                                                    //测试获取搜索 test 关键词的查询语句的 against 条件
r($search->getSqlParamsTest($keywords[0])) && p('2') && e("OR title like '%test_%' OR content like '%test_%'");             //测试获取搜索 test 关键词的查询语句的 like 条件
r($search->getSqlParamsTest($keywords[1])) && p('0') && e('27979 35797');                                                   //测试获取搜索 测试 关键词的 unicode
r($search->getSqlParamsTest($keywords[1])) && p('1') && e('(+"27979 35797")');                                              //测试获取搜索 测试 关键词的查询语句的 against 条件
r($search->getSqlParamsTest($keywords[1])) && p('2') && e("OR title like '%27979 35797%' OR content like '%27979 35797%'"); //测试获取搜索 测试 关键词的查询语句的 like 条件
r($search->getSqlParamsTest($keywords[2])) && p('0') && e('|12345|');                                                       //测试获取搜索 12345 关键词的 unicode
r($search->getSqlParamsTest($keywords[2])) && p('1') && e('(+"|12345|") (-" 12345 ")');                                     //测试获取搜索 12345 关键词的查询语句的 against 条件
r($search->getSqlParamsTest($keywords[2])) && p('2') && e('~~');                                                            //测试获取搜索 12345 关键词的查询语句的 like 条件