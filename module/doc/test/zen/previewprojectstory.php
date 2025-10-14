#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProjectStory();
timeout=0
cid=0

- 执行docTest模块的previewProjectStoryTest方法，参数是'setting', array  @1
- 执行docTest模块的previewProjectStoryTest方法，参数是'setting', array  @1
- 执行docTest模块的previewProjectStoryTest方法，参数是'list', array  @1
- 执行docTest模块的previewProjectStoryTest方法，参数是'setting', array  @1
- 执行docTest模块的previewProjectStoryTest方法，参数是'invalid', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('project')->loadYaml('project_previewprojectstory', false, 2)->gen(5);
zenData('story')->loadYaml('story_previewprojectstory', false, 2)->gen(20);
zenData('projectstory')->loadYaml('projectstory_previewprojectstory', false, 2)->gen(15);

su('admin');

$docTest = new docTest();

r($docTest->previewProjectStoryTest('setting', array('action' => 'preview', 'project' => 1, 'condition' => 'all'))) && p() && e('1');
r($docTest->previewProjectStoryTest('setting', array('action' => 'preview', 'project' => 2, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('')))) && p() && e('1');
r($docTest->previewProjectStoryTest('list', array(), '1,2,3')) && p() && e('1');
r($docTest->previewProjectStoryTest('setting', array('action' => 'preview', 'project' => 999, 'condition' => 'all'))) && p() && e('1');
r($docTest->previewProjectStoryTest('invalid', array('action' => 'preview', 'project' => 1, 'condition' => 'all'))) && p() && e('1');