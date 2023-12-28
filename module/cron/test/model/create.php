#!/usr/bin/env php
<?php

/**

title=测试 cronModel->create();
timeout=0
cid=1

- 命令为空时候返回值属性command @『命令』不能为空。
- 添加之后对比添加数据信息
 - 属性command @test comand
 - 属性type @zentao
- 添加之后对比添加数据信息
 - 属性m @55
 - 属性h @23
 - 属性dom @30
 - 属性mon @12
 - 属性dow @6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron           = new cronTest();
$cron1          = new stdClass();
$cron1->m       = '55';
$cron1->h       = '23';
$cron1->dom     = '30';
$cron1->mon     = '12';
$cron1->dow     = '6';
$cron1->remark  = '';
$cron1->type    = 'zentao' ;
$cron1->command = '';
$result1        = $cron->createTest($cron1);

$cron1->command = 'test comand';
$result2        = $cron->createTest($cron1);

r($result1) && p('command')         && e('『命令』不能为空。');   //命令为空时候返回值
r($result2) && p('command,type')    && e('test comand,zentao'); //添加之后对比添加数据信息
r($result2) && p('m,h,dom,mon,dow') && e('55,23,30,12,6');      //添加之后对比添加数据信息