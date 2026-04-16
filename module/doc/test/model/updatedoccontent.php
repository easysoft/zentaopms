#!/usr/bin/env php
<?php

/**
title=测试 docModel->updateDocContent();
timeout=0
cid=16057

- 测试空gidMap时保持不变 @1
- 测试doc类型替换sourceId @1
- 测试未映射gid保持不变 @1
- 测试content中fileID也被替换 @1
- 测试多个gid同时替换 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTester = new docModelTest();
global $tester;

zenData('doclib')->gen(1);
zenData('doc')->gen(0);
zenData('doccontent')->gen(0);

$rawContent1 = '{"props":{"sourceId":"oldGid1"}},{"props":{"sourceId":"oldGid2"}}';
$htmlContent1 = '<p><img src="fileID=oldGid1&"/>text<img src="fileID=oldGid2&"/></p>';

$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('文档1')->set('rawContent')->eq($rawContent1)->set('content')->eq($htmlContent1)->set('addedBy')->eq('admin')->set('addedDate')->eq(helper::now())->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(2)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('文档2')->set('rawContent')->eq($rawContent1)->set('content')->eq($htmlContent1)->set('addedBy')->eq('admin')->set('addedDate')->eq(helper::now())->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(3)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('文档3')->set('rawContent')->eq($rawContent1)->set('content')->eq($htmlContent1)->set('addedBy')->eq('admin')->set('addedDate')->eq(helper::now())->exec();

$r1 = $docTester->updateDocContentTest(1, 'text', array());
$unchanged = strpos($r1->rawContent, 'oldGid1') !== false ? '1' : '0';
r($unchanged) && p() && e('1');

$r2 = $docTester->updateDocContentTest(2, 'text', array('oldGid1' => 'newGid1'));
$hasNew = strpos($r2->rawContent, 'newGid1') !== false ? '1' : '0';
r($hasNew) && p() && e('1');

$stillOld = strpos($r2->rawContent, 'oldGid2') !== false ? '1' : '0';
r($stillOld) && p() && e('1');

$hasNewFile = strpos($r2->content, 'fileID=newGid1&') !== false ? '1' : '0';
r($hasNewFile) && p() && e('1');

$r3 = $docTester->updateDocContentTest(3, 'text', array('oldGid1' => 'newA', 'oldGid2' => 'newB'));
$hasBoth = strpos($r3->rawContent, 'newA') !== false && strpos($r3->rawContent, 'newB') !== false ? '1' : '0';
r($hasBoth) && p() && e('1');
