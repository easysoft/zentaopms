#!/usr/bin/env php
<?php

/**

title=测试 docModel->getByID();
cid=16071

- 测试空数据 @0
- 测试docID=1的数据
 - 属性type @text
 - 属性title @文档标题1
- 测试docID=2的数据
 - 属性type @text
 - 属性title @文档标题2
- 测试docID=3的数据
 - 属性type @text
 - 属性title @文档标题3
- 测试docID=4的数据
 - 属性type @text
 - 属性title @文档标题4
- 测试docID=5的数据
 - 属性type @text
 - 属性title @文档标题5
- 测试docID=1的数据，并且设置图片大小
 - 属性type @text
 - 属性title @文档标题1
- 测试docID=2的数据，并且设置图片大小
 - 属性type @text
 - 属性title @文档标题2
- 测试docID=3的数据，并且设置图片大小
 - 属性type @text
 - 属性title @文档标题3
- 测试docID=4的数据，并且设置图片大小
 - 属性type @text
 - 属性title @文档标题4
- 测试docID=5的数据，并且设置图片大小
 - 属性type @text
 - 属性title @文档标题5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('doc')->gen(5);
zenData('doccontent')->loadYaml('doccontent')->gen(5);
zenData('user')->gen(5);
su('admin');

$docIds     = range(0, 5);
$setImgSize = array(false, true);

$docTester = new docModelTest();
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
