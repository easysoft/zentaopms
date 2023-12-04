#!/usr/bin/env php
<?php
/**

title=测试 docModel->getByID();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doc')->config('doc')->gen(5);
zdTable('doccontent')->config('doccontent')->gen(5);
zdTable('user')->gen(5);
su('admin');

$docIds     = range(0, 5);
$setImgSize = array(false, true);

$docTester = new docTest();
r($docTester->getByIDTest($docIds[0], $setImgSize[0])) && p()             && e('0');              // 测试空数据
r($docTester->getByIDTest($docIds[1], $setImgSize[0])) && p('type,title') && e('text,文档标题1'); // 测试docID=1的数据
r($docTester->getByIDTest($docIds[2], $setImgSize[0])) && p('type,title') && e('text,文档标题2'); // 测试docID=2的数据
r($docTester->getByIDTest($docIds[3], $setImgSize[0])) && p('type,title') && e('text,文档标题3'); // 测试docID=3的数据
r($docTester->getByIDTest($docIds[4], $setImgSize[0])) && p('type,title') && e('text,文档标题4'); // 测试docID=4的数据
r($docTester->getByIDTest($docIds[5], $setImgSize[0])) && p('type,title') && e('text,文档标题5'); // 测试docID=5的数据
r($docTester->getByIDTest($docIds[1], $setImgSize[1])) && p('type,title') && e('text,文档标题1'); // 测试docID=1的数据，并且设置图片大小
r($docTester->getByIDTest($docIds[2], $setImgSize[1])) && p('type,title') && e('text,文档标题2'); // 测试docID=2的数据，并且设置图片大小
r($docTester->getByIDTest($docIds[3], $setImgSize[1])) && p('type,title') && e('text,文档标题3'); // 测试docID=3的数据，并且设置图片大小
r($docTester->getByIDTest($docIds[4], $setImgSize[1])) && p('type,title') && e('text,文档标题4'); // 测试docID=4的数据，并且设置图片大小
r($docTester->getByIDTest($docIds[5], $setImgSize[1])) && p('type,title') && e('text,文档标题5'); // 测试docID=5的数据，并且设置图片大小
