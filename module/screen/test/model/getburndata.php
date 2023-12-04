#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('project')->config('project')->gen(1);
zdTable('project')->config('execution_burn')->gen(30, false, false);

/**
title=测试 screenModel->getBurnData();
cid=1
pid=1

测试生成的数据条数         >> 5
测试生成的数据是否正确     >> 项目集1--项目集4
测试生成的标签数据是否正确 >> 7/9
*/

$screen = new screenTest();

$data = $screen->getBurnDataTest();

r(count($data))          && p('')         && e(5);                    //测试生成的数据条数
r($data)                 && p('104:name') && e('项目集1--项目集4');   //测试生成的数据是否正确
r($data[104]->chartData) && p('labels:0') && e('7/9');                //测试生成的标签数据是否正确
