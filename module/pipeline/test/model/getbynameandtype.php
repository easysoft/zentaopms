#!/usr/bin/env php
<?php
/**

title=测试 pipelineModel->getByNameAndType();
cid=1

- 获取名称为空类型为空的流水线ID @0
- 获取名称为空类型为gitlab的流水线ID @0
- 获取名称为空类型为test的流水线ID @0
- 获取名称为gitLab类型为空的流水线ID @0
- 获取名称为gitLab类型为gitlab的流水线ID属性id @1
- 获取名称为gitLab类型为test的流水线ID @0
- 获取名称为test类型为空的流水线ID @0
- 获取名称为test类型为gitlab的流水线ID @0
- 获取名称为test类型为test的流水线ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pipeline.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(5);

$names = array('', 'gitLab', 'test');
$types = array('', 'gitlab', 'test');

$pipelineTester = new pipelineTest();
r($pipelineTester->getByNameAndTypeTest($names[0], $types[0])) && p()     && e('0'); // 获取名称为空类型为空的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[0], $types[1])) && p()     && e('0'); // 获取名称为空类型为gitlab的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[0], $types[2])) && p()     && e('0'); // 获取名称为空类型为test的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[1], $types[0])) && p()     && e('0'); // 获取名称为gitLab类型为空的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[1], $types[1])) && p('id') && e('1'); // 获取名称为gitLab类型为gitlab的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[1], $types[2])) && p()     && e('0'); // 获取名称为gitLab类型为test的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[2], $types[0])) && p()     && e('0'); // 获取名称为test类型为空的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[2], $types[1])) && p()     && e('0'); // 获取名称为test类型为gitlab的流水线ID
r($pipelineTester->getByNameAndTypeTest($names[2], $types[2])) && p()     && e('0'); // 获取名称为test类型为test的流水线ID
