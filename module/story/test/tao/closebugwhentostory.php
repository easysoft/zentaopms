#!/usr/bin/env php
<?php

/**

title=测试 storyModel->closeBugWhenToStory();
timeout=0
cid=18612

- 不传入Bug，也不传入需求。 @0
- 传入Bug，不传入需求。 @0
- 不传入Bug，传入需求。 @0
- 传入Bug，传入需求，检查字段。
 - 属性toStory @1
 - 属性status @closed
 - 属性resolution @tostory
- 传入Bug，传入需求，检查关联的附件。 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen(5);
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->product->range('1');
$storyTable->type->range('story{5}');
$storyTable->status->range('active{5}');
$storyTable->deleted->range('0{5}');
$storyTable->gen(5);

$bugTable = zenData('bug');
$bugTable->id->range('1');
$bugTable->product->range('1');
$bugTable->execution->range('0');
$bugTable->status->range('active');
$bugTable->deleted->range('0');
$bugTable->gen(1);
$file = zenData('file');
$file->objectType->range('bug');
$file->objectID->range('1');
$file->gen(5);
su('admin');

$storyTest = new storyTaoTest();
$storyTest->instance->mao->cache = null;
restoreObjectTables();

r($storyTest->closeBugWhenToStoryTest(0, 0)) && p() && e('0'); //不传入Bug，也不传入需求。
r($storyTest->closeBugWhenToStoryTest(1, 0)) && p() && e('0'); //传入Bug，不传入需求。
r($storyTest->closeBugWhenToStoryTest(0, 1)) && p() && e('0'); //不传入Bug，传入需求。

$bug = $storyTest->closeBugWhenToStoryTest(1, 1);
r($bug)                 && p('toStory,status,resolution') && e('1,closed,tostory'); //传入Bug，传入需求，检查字段。
r(count($bug['files'])) && p()                            && e('5');                //传入Bug，传入需求，检查关联的附件。
