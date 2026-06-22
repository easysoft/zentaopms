#!/usr/bin/env php
<?php

/**

title=测试 aiModel::loadFormContextObjects();
timeout=0
cid=1

- 步骤1：可从 project 字段加载项目对象 id @11
- 步骤2：可从 story 字段加载需求对象 id @4
- 步骤3：根据关联链加载 product 对象 id @1
- 步骤4：根据 projectproduct 关联加载 product 对象 id @1
- 步骤5：空 fields 输入返回空数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(20);
zenData('product')->gen(20);
zenData('story')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(50);

su('admin');

$aiTest = new aiModelTest();

$formSchema = array
(
    'fields' => array
    (
        'project' => array('currentValue' => '11'),
        'story'   => array('currentValue' => '4'),
    )
);

$relations = array
(
    'project' => array
    (
        'product' => array('module' => 'product', 'via' => 'projectproduct'),
    ),
);

$context      = $aiTest->loadFormContextObjectsTest($formSchema, array('project', 'story'), $relations);
$emptyContext = $aiTest->loadFormContextObjectsTest(array('fields' => array()), array('project'), $relations);

r($context) && p('project:id') && e('11');
r($context) && p('story:id') && e('4');
r($context) && p('product:id') && e('1');
r(isset($context['execution'])) && p() && e('0');
r(count($emptyContext)) && p() && e('0');
