#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processBuildinFieldData();
timeout=0
cid=15867

- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations
 - 属性title @Test Story Title
 - 属性pri @2
 - 属性spec @Test story description
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations 属性bugreporter @jira_user_key
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations
 - 属性tasktimeoriginalestimate @2
 - 属性tasktimespent @1
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations
 - 属性title @Critical Bug
 - 属性severity @1
 - 属性assignedTo @developer
 - 属性existing_field @keep_this
- 执行$result @1
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations 属性pre_existing @original_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations
 - 属性mapped_field_1 @custom_value_1
 - 属性mapped_field_2 @custom_value_2
 - 属性storyreporter @reporter_key
 - 属性storytimeoriginalestimate @4
 - 属性existing_data @preserve_this

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$user = zenData('user');
$user->account->range('admin,reporter1,developer');
$user->realname->range('管理员,报告人,开发者');
$user->gen(3);

su('admin');

$convertTest = new convertTaoTest();

// 测试步骤1：正常处理zentaoField字段映射
$module = 'story';
$data = new stdclass();
$data->issuetype = 'story';
$data->summary = 'Test Story Title';
$data->priority = '2';

$object = new stdclass();

$relations = array(
    'zentaoFieldstory' => array(
        'summary' => 'title',
        'priority' => 'pri'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,pri') && e('Test Story Title,2');

// 测试步骤2：测试buildinFields的reporter字段特殊处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'bug';
$data->reporter = 'jira_reporter';

$object = new stdclass();
$relations = array();

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations, false, array('jira_reporter' => 'reporter1'))) && p('bugreporter') && e('reporter1');

// 测试步骤3：测试buildinFields的时间字段转换
$module = 'task';
$data = new stdclass();
$data->issuetype = 'task';
$data->timeoriginalestimate = 7200; // 2小时=7200秒
$data->timespent = 3600; // 1小时=3600秒

$object = new stdclass();
$relations = array();

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('tasktimeoriginalestimate,tasktimespent') && e('2,1');

// 测试步骤4：测试多字段映射同时处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'bug';
$data->summary = 'Critical Bug';
$data->priority = '1';
$data->assignee = 'developer';

$object = new stdclass();
$object->existing_field = 'keep_this';

$relations = array(
    'zentaoFieldbug' => array(
        'summary' => 'title',
        'priority' => 'severity',
        'assignee' => 'assignedTo'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,severity,assignedTo,existing_field') && e('Critical Bug,1,developer,keep_this');

// 测试步骤5：测试buildinFlow参数控制字段处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'bug';
$data->summary = 'Flow Control Test';
$data->priority = '3';
$data->description = 'Flow description';

$object = new stdclass();
$relations = array();

$result = $convertTest->processBuildinFieldDataTest($module, $data, $object, $relations, true);
r($result) && p('bugsummary,bugpri,bugdesc') && e('Flow Control Test,3,Flow description');

// 测试步骤6：测试空数据和关系处理
$module = 'task';
$data = new stdclass();
$data->issuetype = 'task';
$data->empty_field = '';
$data->null_field = null;

$object = new stdclass();
$object->pre_existing = 'original_value';

$relations = array(
    'zentaoFieldtask' => array(
        'empty_field' => 'mapped_empty',
        'null_field' => 'mapped_null',
        'missing_field' => 'mapped_missing'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('pre_existing') && e('original_value');

// 测试步骤7：测试复合场景综合处理
$module = 'story';
$data = new stdclass();
$data->issuetype = 'story';
$data->custom_field_1 = 'custom_value_1';
$data->custom_field_2 = 'custom_value_2';
$data->reporter = 'jira_story_reporter';
$data->timeoriginalestimate = 14400; // 4小时

$object = new stdclass();
$object->existing_data = 'preserve_this';

$relations = array(
    'zentaoFieldstory' => array(
        'custom_field_1' => 'title',
        'custom_field_2' => 'category'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations, false, array('jira_story_reporter' => 'reporter1'))) && p('title,category,storyreporter,storytimeoriginalestimate,existing_data') && e('custom_value_1,custom_value_2,reporter1,4,preserve_this');
