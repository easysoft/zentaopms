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
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是$module, $data, $object, $relations 属性bugreporter @jira_user
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
 - 属性storyreporter @reporter_user
 - 属性storytimeoriginalestimate @4
 - 属性existing_data @preserve_this

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

// 测试步骤1：正常处理zentaoField字段映射
$module = 'story';
$data = new stdclass();
$data->issuetype = 'Story';
$data->summary = 'Test Story Title';
$data->priority = '2';
$data->description = 'Test story description';

$object = new stdclass();

$relations = array(
    'zentaoFieldStory' => array(
        'summary' => 'title',
        'priority' => 'pri',
        'description' => 'spec'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,pri,spec') && e('Test Story Title,2,Test story description');

// 测试步骤2：测试buildinFields的reporter字段特殊处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'Bug';
$data->reporter = 'jira_user_key';

$object = new stdclass();
$relations = array();

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('bugreporter') && e('jira_user');

// 测试步骤3：测试buildinFields的时间字段转换
$module = 'task';
$data = new stdclass();
$data->issuetype = 'Task';
$data->timeoriginalestimate = 7200; // 2小时=7200秒
$data->timespent = 3600; // 1小时=3600秒

$object = new stdclass();
$relations = array();

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('tasktimeoriginalestimate,tasktimespent') && e('2,1');

// 测试步骤4：测试多字段映射同时处理
$module = 'bug';
$data = new stdclass();
$data->issuetype = 'Bug';
$data->bug_title = 'Critical Bug';
$data->bug_priority = '1';
$data->bug_assignee = 'developer';

$object = new stdclass();
$object->existing_field = 'keep_this';

$relations = array(
    'zentaoFieldBug' => array(
        'bug_title' => 'title',
        'bug_priority' => 'severity',
        'bug_assignee' => 'assignedTo'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('title,severity,assignedTo,existing_field') && e('Critical Bug,1,developer,keep_this');

// 测试步骤5：测试buildinFlow参数控制字段处理
$module = 'story';
$data = new stdclass();
$data->issuetype = 'Story';
$data->summary = 'Flow Control Test';

$object = new stdclass();
$relations = array();

$result = $convertTest->processBuildinFieldDataTest($module, $data, $object, $relations, true);
r(is_object($result)) && p() && e(1);

// 测试步骤6：测试空数据和关系处理
$module = 'task';
$data = new stdclass();
$data->issuetype = 'Task';
$data->empty_field = '';
$data->null_field = null;

$object = new stdclass();
$object->pre_existing = 'original_value';

$relations = array(
    'zentaoFieldTask' => array(
        'empty_field' => 'mapped_empty',
        'null_field' => 'mapped_null',
        'missing_field' => 'mapped_missing'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('pre_existing') && e('original_value');

// 测试步骤7：测试复合场景综合处理
$module = 'story';
$data = new stdclass();
$data->issuetype = 'Story';
$data->custom_field_1 = 'custom_value_1';
$data->custom_field_2 = 'custom_value_2';
$data->reporter = 'reporter_key';
$data->timeoriginalestimate = 14400; // 4小时

$object = new stdclass();
$object->existing_data = 'preserve_this';

$relations = array(
    'zentaoFieldStory' => array(
        'custom_field_1' => 'mapped_field_1',
        'custom_field_2' => 'mapped_field_2'
    )
);

r($convertTest->processBuildinFieldDataTest($module, $data, $object, $relations)) && p('mapped_field_1,mapped_field_2,storyreporter,storytimeoriginalestimate,existing_data') && e('custom_value_1,custom_value_2,reporter_user,4,preserve_this');