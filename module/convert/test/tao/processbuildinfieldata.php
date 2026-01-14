#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processBuildinFieldData();
cid=15865

- 测试步骤1：正常输入story模块，包含zentao字段映射 >> 期望正确设置object属性
- 测试步骤2：测试空relations处理 >> 期望返回原始object
- 测试步骤3：测试多字段映射处理 >> 期望正确设置多个属性
- 测试步骤4：测试不存在的字段 >> 期望跳过不存在的字段
- 测试步骤5：测试空object处理 >> 期望返回填充后的object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

// 测试步骤1：正常输入story模块，包含zentao字段映射
$module = 'story';
$data = new stdclass();
$data->issuetype = 'Story';
$data->summary = 'Test summary';
$data->priority = '2';
$data->description = 'Test description';

$object = new stdclass();

$relations = array(
    'zentaoFieldStory' => array(
        'summary' => 'title',
        'priority' => 'pri',
        'description' => 'spec'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,pri,spec') && e('Test summary,2,Test description');

// 测试步骤2：测试空relations处理
$module = 'task';
$data = new stdclass();
$data->issuetype = 'Task';
$data->summary = 'Task title';

$object = new stdclass();
$relations = array();

$result = $convertTest->processBuildinFieldDataTest($module, $data, $object, $relations);
r(get_class($result)) && p() && e('stdClass');

// 测试步骤3：测试多字段映射处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'Bug';
$data->summary = 'Bug title';
$data->priority = '1';
$data->reporter = 'tester';
$data->assignee = 'developer';

$object = new stdclass();

$relations = array(
    'zentaoFieldBug' => array(
        'summary' => 'title',
        'priority' => 'severity',
        'reporter' => 'openedBy',
        'assignee' => 'assignedTo'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,severity,openedBy,assignedTo') && e('Bug title,1,tester,developer');

// 测试步骤4：测试不存在的字段
$module = 'story';
$data = new stdclass();
$data->issuetype = 'Story';
$data->summary = 'Story title';

$object = new stdclass();

$relations = array(
    'zentaoFieldStory' => array(
        'summary' => 'title',
        'nonexistent' => 'ignored'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title') && e('Story title');

// 测试步骤5：测试空object处理
$module = 'task';
$data = new stdclass();
$data->issuetype = 'Task';
$data->summary = 'Task summary';
$data->priority = '3';

$object = new stdclass();

$relations = array(
    'zentaoFieldTask' => array(
        'summary' => 'name',
        'priority' => 'pri'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('name,pri') && e('Task summary,3');