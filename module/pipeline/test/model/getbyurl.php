#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getByUrl();
cid=1

- 获取url为空的流水线ID @0
- 获取url为https:gitlabdev.qc.oop.cc/的流水线ID属性id @1
- 获取url为https:test.qc.oop.cc/的流水线ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(1);

$urlList = array('', 'https://gitlabdev.qc.oop.cc/', 'https://test.qc.oop.cc/');

$pipelineTester = new pipelineTest();
r($pipelineTester->getByUrlTest($urlList[0])) && p()     && e('0'); // 获取url为空的流水线ID
r($pipelineTester->getByUrlTest($urlList[1])) && p('id') && e('1'); // 获取url为https://gitlabdev.qc.oop.cc/的流水线ID
r($pipelineTester->getByUrlTest($urlList[2])) && p()     && e('0'); // 获取url为https://test.qc.oop.cc/的流水线ID
