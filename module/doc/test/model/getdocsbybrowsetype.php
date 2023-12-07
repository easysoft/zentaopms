#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDocsByBrowseType();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '%文档%' ) AND ( 1 ))`");
$userqueryTable->gen(1);

$actionTable = zdTable('action');
$actionTable->objectType->range('doc,story,task,bug');
$actionTable->objectID->range('1-20');
$actionTable->action->range('edited');
$actionTable->actor->range('admin,user1,user2');
$actionTable->gen(100);

zdTable('docaction')->config('docaction')->gen(30);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$browseTypes = array('', 'all', 'bySearch', 'openedbyme', 'editedbyme', 'byediteddate', 'collectedbyme', 'test');
$queries     = array(0, 1, 2);
$modules     = array(0, 1, 100);
$sorts       = array('id_desc', 'id_asc', 'title_asc', 'title_desc');

$docTester = new docTest();
r($docTester->getDocsByBrowseTypeTest($browseTypes[0], $queries[0], $modules[0], $sorts[0])) && p()                      && e('0');                    // 测试空数据
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[0], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[2], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[2], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[2], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[1], $modules[2], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=1、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[2], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=2时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[2], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=2时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[2], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=2时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[1], $queries[2], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为所有、queryID=2时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[2], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[2], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[2], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[0], $modules[2], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=0、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[2], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[2], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[2], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[1], $modules[2], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=1、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[2], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[2], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[2], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[2], $queries[2], $modules[2], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为搜索、queryID=2、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我创建、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[0], $sorts[0])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[0], $sorts[1])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[0], $sorts[2])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[0], $sorts[3])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[1], $sorts[0])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[1], $sorts[1])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=0、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[1], $sorts[2])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=0、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[1], $sorts[3])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=0、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为我创建、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[2], $sorts[1])) && p()                      && e('0');                    // 获取类型为我创建、queryID=0、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[2], $sorts[2])) && p()                      && e('0');                    // 获取类型为我创建、queryID=0、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[0], $modules[2], $sorts[3])) && p()                      && e('0');                    // 获取类型为我创建、queryID=0、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[0], $sorts[0])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[0], $sorts[1])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=1、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[0], $sorts[2])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=1、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[0], $sorts[3])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=1、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[1], $sorts[0])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=1、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[1], $sorts[1])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=1、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[1], $sorts[2])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=1、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[1], $sorts[3])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=1、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为我创建、queryID=1、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[2], $sorts[1])) && p()                      && e('0');                    // 获取类型为我创建、queryID=1、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[2], $sorts[2])) && p()                      && e('0');                    // 获取类型为我创建、queryID=1、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[1], $modules[2], $sorts[3])) && p()                      && e('0');                    // 获取类型为我创建、queryID=1、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[0], $sorts[0])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=2、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[0], $sorts[1])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=2、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[0], $sorts[2])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=2、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[0], $sorts[3])) && p('41:title,lib,module') && e('产品文档41,26,0');      // 获取类型为我创建、queryID=2、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[1], $sorts[0])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=2、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[1], $sorts[1])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=2、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[1], $sorts[2])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=2、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[1], $sorts[3])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为我创建、queryID=2、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为我创建、queryID=2、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[2], $sorts[1])) && p()                      && e('0');                    // 获取类型为我创建、queryID=2、moduleID=100时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[2], $sorts[2])) && p()                      && e('0');                    // 获取类型为我创建、queryID=2、moduleID=100时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[3], $queries[2], $modules[2], $sorts[3])) && p()                      && e('0');                    // 获取类型为我创建、queryID=2、moduleID=100时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[1], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[1], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=1时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[1], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=1时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[1], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我编辑、queryID=0、moduleID=1时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[0], $modules[2], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[1], $modules[0], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[2], $modules[0], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=2、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[1], $modules[1], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=1、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[2], $modules[1], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=2、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[1], $modules[2], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=1、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[4], $queries[2], $modules[2], $sorts[0])) && p('17:title,lib,module') && e('自定义草稿文档17,6,2'); // 获取类型为我编辑、queryID=2、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为编辑日期排序、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为编辑日期排序、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为编辑日期排序、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为编辑日期排序、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[1], $sorts[0])) && p('46:title,lib,module') && e('产品草稿文档46,26,1');  // 获取类型为编辑日期排序、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[0], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为编辑日期排序、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[1], $modules[0], $sorts[0])) && p('50:title,lib,module') && e('产品草稿文档50,26,3');  // 获取类型为编辑日期排序、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[5], $queries[2], $modules[0], $sorts[0])) && p('50:title,lib,module') && e('产品草稿文档50,26,3');  // 获取类型为编辑日期排序、queryID=2、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[0], $sorts[1])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[0], $sorts[2])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[0], $sorts[3])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[1], $sorts[0])) && p()                      && e('0');                    // 获取类型为我收藏、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[0], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为我收藏、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[1], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[6], $queries[2], $modules[0], $sorts[0])) && p('1:title,lib,module')  && e('我的文档1,11,0');       // 获取类型为我收藏、queryID=2、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[0], $sorts[0])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[0], $sorts[1])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=0时，按照id正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[0], $sorts[2])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=0时，按照title正序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[0], $sorts[3])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=0时，按照title倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[1], $sorts[0])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=1时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[0], $modules[2], $sorts[0])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=0、moduleID=100时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[1], $modules[0], $sorts[0])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=1、moduleID=0时，按照id倒序排列的文档
r($docTester->getDocsByBrowseTypeTest($browseTypes[7], $queries[2], $modules[0], $sorts[0])) && p()                      && e('0');                    // 获取类型为不存在类型、queryID=2、moduleID=0时，按照id倒序排列的文档
