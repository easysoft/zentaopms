#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::formatSeconds();
timeout=0
cid=0

- 秒数小于60秒 @ 输出"Xs"格式
- 秒数小于1小时 @ 输出"XmYs"格式
- 秒数大于等于1小时 @ 输出"XhYmZs"格式
- 秒数为0 @ 输出"0s"
- 负数输入 @ 归一化为0并按"0s"输出

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTest = new pipelineModelTest();

r($pipelineTest->formatSecondsTest(45)) && p() && e('45s');
r($pipelineTest->formatSecondsTest(330)) && p() && e('5m30s');
r($pipelineTest->formatSecondsTest(7290)) && p() && e('2h1m30s');
r($pipelineTest->formatSecondsTest(0)) && p() && e('0s');
r($pipelineTest->formatSecondsTest(-100)) && p() && e('0s');
