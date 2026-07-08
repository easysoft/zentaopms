#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::buildJenkinsTree();
timeout=0
cid=0

- 空数组count为0 @0
- 单个叶子text和value正确 @job1,job1
- 三个叶子count为3 @3
- 嵌套文件夹type=folder含1个子节点 @folder,1
- 空值被过滤count为2 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$pipelineZenTest = new pipelineZenTest();

r($pipelineZenTest->buildJenkinsTreeTest(array())) && p('count') && e('0'); // 空数组
r($pipelineZenTest->buildJenkinsTreeTest(array('job1' => 'job1'))) && p('firstText,firstValue') && e('job1,job1'); // 单个叶子
r($pipelineZenTest->buildJenkinsTreeTest(array('a' => 'a', 'b' => 'b', 'c' => 'c'))) && p('count') && e('3'); // 三个叶子
r($pipelineZenTest->buildJenkinsTreeTest(array('f' => array('s' => 's')))) && p('firstType,firstChildren') && e('folder,1'); // 嵌套文件夹
r($pipelineZenTest->buildJenkinsTreeTest(array('a' => 'a', 'b' => '', 'c' => 'c'))) && p('count') && e('2'); // 空值被跳过
