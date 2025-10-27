#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewStory();
timeout=0
cid=0

- 执行docTest模块的previewStoryTest方法，参数是'story', 'setting', array  @5
- 执行docTest模块的previewStoryTest方法，参数是'epic', 'setting', array  @5
- 执行docTest模块的previewStoryTest方法，参数是'requirement', 'setting', array  @5
- 执行docTest模块的previewStoryTest方法，参数是'story', 'list', array  @3
- 执行docTest模块的previewStoryTest方法，参数是'story', 'setting', array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('product')->gen(5);
zenData('story')->gen(20);
zenData('user')->gen(5);

su('admin');

$docTest = new docTest();

// 测试步骤1：预览story类型需求，view=setting，有效产品ID
r($docTest->previewStoryTest('story', 'setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'), '')) && p() && e('5');

// 测试步骤2：预览epic类型需求，view=setting，有效产品ID  
r($docTest->previewStoryTest('epic', 'setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'), '')) && p() && e('5');

// 测试步骤3：预览requirement类型需求，view=setting，有效产品ID
r($docTest->previewStoryTest('requirement', 'setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'), '')) && p() && e('5');

// 测试步骤4：预览需求列表，view=list，有效ID列表
r($docTest->previewStoryTest('story', 'list', array(), '1,2,3')) && p() && e('3');

// 测试步骤5：预览需求，customSearch条件
r($docTest->previewStoryTest('story', 'setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('title'), 'operator' => array('include'), 'value' => array('需求'), 'andor' => array('and')), '')) && p() && e('3');