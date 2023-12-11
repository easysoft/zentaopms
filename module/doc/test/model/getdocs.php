#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocs();
cid=1

- 获取libID=0、moduleID=0、browseType=all的文档列表
 - 第6条的lib属性 @11
 - 第6条的module属性 @1
 - 第6条的title属性 @我的草稿文档6
- 获取libID=0、moduleID=0、browseType=draft的文档列表 @0
- 获取libID=0、moduleID=1、browseType=all的文档列表
 - 第6条的lib属性 @11
 - 第6条的module属性 @1
 - 第6条的title属性 @我的草稿文档6
- 获取libID=0、moduleID=1、browseType=draft的文档列表 @0
- 获取libID=11、moduleID=0、browseType=all的文档列表
 - 第1条的lib属性 @11
 - 第1条的module属性 @0
 - 第1条的title属性 @我的文档1
- 获取libID=11、moduleID=0、browseType=draft的文档列表
 - 第6条的lib属性 @11
 - 第6条的module属性 @1
 - 第6条的title属性 @我的草稿文档6
- 获取libID=11、moduleID=1、browseType=all的文档列表
 - 第6条的lib属性 @11
 - 第6条的module属性 @1
 - 第6条的title属性 @我的草稿文档6
- 获取libID=11、moduleID=1、browseType=draft的文档列表
 - 第6条的lib属性 @11
 - 第6条的module属性 @1
 - 第6条的title属性 @我的草稿文档6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$libIdList   = array(0, 11);
$modules     = array(0, 1);
$browseTypes = array('all', 'draft');

$docTester = new docTest();
r($docTester->getDocsTest($libIdList[0], $modules[0], $browseTypes[0])) && p('6:lib,module,title') && e('11,1,我的草稿文档6'); // 获取libID=0、moduleID=0、browseType=all的文档列表
r($docTester->getDocsTest($libIdList[0], $modules[0], $browseTypes[1])) && p() && e('0');                                      // 获取libID=0、moduleID=0、browseType=draft的文档列表
r($docTester->getDocsTest($libIdList[0], $modules[1], $browseTypes[0])) && p('6:lib,module,title') && e('11,1,我的草稿文档6'); // 获取libID=0、moduleID=1、browseType=all的文档列表
r($docTester->getDocsTest($libIdList[0], $modules[1], $browseTypes[1])) && p()                     && e('0');                  // 获取libID=0、moduleID=1、browseType=draft的文档列表
r($docTester->getDocsTest($libIdList[1], $modules[0], $browseTypes[0])) && p('1:lib,module,title') && e('11,0,我的文档1');     // 获取libID=11、moduleID=0、browseType=all的文档列表
r($docTester->getDocsTest($libIdList[1], $modules[0], $browseTypes[1])) && p('6:lib,module,title') && e('11,1,我的草稿文档6'); // 获取libID=11、moduleID=0、browseType=draft的文档列表
r($docTester->getDocsTest($libIdList[1], $modules[1], $browseTypes[0])) && p('6:lib,module,title') && e('11,1,我的草稿文档6'); // 获取libID=11、moduleID=1、browseType=all的文档列表
r($docTester->getDocsTest($libIdList[1], $modules[1], $browseTypes[1])) && p('6:lib,module,title') && e('11,1,我的草稿文档6'); // 获取libID=11、moduleID=1、browseType=draft的文档列表
