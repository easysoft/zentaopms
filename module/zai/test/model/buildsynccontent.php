#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->buildSyncContent();
timeout=0
cid=19810

- 测试组装 key @story-1
- 测试 content_type 为 markdown @markdown
- 测试 attrs 含 objectType @story
- 测试 attrs 含 objectID @1
- 测试 content 非空 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(1);
zenData('storyspec')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

$target  = $tester->loadModel('story')->getByID(1);
$content = $zai->buildSyncContentTest('story', $target);

r($content['key']) && p() && e('story-1'); // 测试组装 key
r($content['content_type']) && p() && e('markdown'); // 测试 content_type 为 markdown
r($content['attrs']['objectType']) && p() && e('story'); // 测试 attrs 含 objectType
r((string)$content['attrs']['objectID']) && p() && e('1'); // 测试 attrs 含 objectID
r((int)(!empty($content['content']))) && p() && e('1'); // 测试 content 非空
