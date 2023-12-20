#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getStakeholders();
cid=1

- 获取项目ID=0时，按照id倒序排列的所有干系人 @0
- 获取项目ID=0时，按照name倒序排列的所有干系人 @0
- 获取项目ID=0时，按照id倒序排列的内部干系人 @0
- 获取项目ID=0时，按照name倒序排列的内部干系人 @0
- 获取项目ID=0时，按照id倒序排列的外部干系人 @0
- 获取项目ID=0时，按照name倒序排列的外部干系人 @0
- 获取项目ID=0时，按照id倒序排列的关键干系人 @0
- 获取项目ID=0时，按照name倒序排列的关键干系人 @0
- 获取项目ID=0时，按照id倒序排列的不存在类型干系人 @0
- 获取项目ID=0时，按照name倒序排列的不存在类型干系人 @0
- 获取项目ID=11时，按照id倒序排列的所有干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID=11时，按照name倒序排列的所有干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID=11时，按照id倒序排列的内部干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID=11时，按照name倒序排列的内部干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID=11时，按照id倒序排列的外部干系人
 - 第16条的objectID属性 @11
 - 第16条的objectType属性 @project
 - 第16条的user属性 @user15
 - 第16条的type属性 @outside
 - 第16条的key属性 @1
- 获取项目ID=11时，按照name倒序排列的外部干系人
 - 第16条的objectID属性 @11
 - 第16条的objectType属性 @project
 - 第16条的user属性 @user15
 - 第16条的type属性 @outside
 - 第16条的key属性 @1
- 获取项目ID=11时，按照id倒序排列的关键干系人
 - 第12条的objectID属性 @11
 - 第12条的objectType属性 @project
 - 第12条的user属性 @user11
 - 第12条的type属性 @inside
 - 第12条的key属性 @1
- 获取项目ID=11时，按照name倒序排列的关键干系人
 - 第12条的objectID属性 @11
 - 第12条的objectType属性 @project
 - 第12条的user属性 @user11
 - 第12条的type属性 @inside
 - 第12条的key属性 @1
- 获取项目ID=11时，按照id倒序排列的不存在类型干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID=11时，按照name倒序排列的不存在类型干系人
 - 第11条的objectID属性 @11
 - 第11条的objectType属性 @project
 - 第11条的user属性 @user10
 - 第11条的type属性 @inside
 - 第11条的key属性 @0
- 获取项目ID不存在时，按照id倒序排列的所有干系人 @0
- 获取项目ID不存在时，按照name倒序排列的所有干系人 @0
- 获取项目ID不存在时，按照id倒序排列的内部干系人 @0
- 获取项目ID不存在时，按照name倒序排列的内部干系人 @0
- 获取项目ID不存在时，按照id倒序排列的外部干系人 @0
- 获取项目ID不存在时，按照name倒序排列的外部干系人 @0
- 获取项目ID不存在时，按照id倒序排列的关键干系人 @0
- 获取项目ID不存在时，按照name倒序排列的关键干系人 @0
- 获取项目ID不存在时，按照id倒序排列的不存在类型干系人 @0
- 获取项目ID不存在时，按照name倒序排列的不存在类型干系人 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->config('user')->gen(20);
zdTable('stakeholder')->config('stakeholder')->gen(20);
zdTable('project')->config('project')->gen(15);

$projectIds  = array(0, 11, 100);
$browseTypes = array('all', 'inside', 'outside', 'key', 'test');
$sorts       = array('id_desc', 'name_desc');

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[0], $sorts[0])) && p()                                       && e('0');                           // 获取项目ID=0时，按照id倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[0], $sorts[1])) && p()                                       && e('0');                           // 获取项目ID=0时，按照name倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[1], $sorts[0])) && p()                                       && e('0');                           // 获取项目ID=0时，按照id倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[1], $sorts[1])) && p()                                       && e('0');                           // 获取项目ID=0时，按照name倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[2], $sorts[0])) && p()                                       && e('0');                           // 获取项目ID=0时，按照id倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[2], $sorts[1])) && p()                                       && e('0');                           // 获取项目ID=0时，按照name倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[3], $sorts[0])) && p()                                       && e('0');                           // 获取项目ID=0时，按照id倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[3], $sorts[1])) && p()                                       && e('0');                           // 获取项目ID=0时，按照name倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[4], $sorts[0])) && p()                                       && e('0');                           // 获取项目ID=0时，按照id倒序排列的不存在类型干系人
r($stakeholderTester->getStakeholdersTest($projectIds[0], $browseTypes[4], $sorts[1])) && p()                                       && e('0');                           // 获取项目ID=0时，按照name倒序排列的不存在类型干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[0], $sorts[0])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照id倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[0], $sorts[1])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照name倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[1], $sorts[0])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照id倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[1], $sorts[1])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照name倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[2], $sorts[0])) && p('16:objectID,objectType,user,type,key') && e('11,project,user15,outside,1'); // 获取项目ID=11时，按照id倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[2], $sorts[1])) && p('16:objectID,objectType,user,type,key') && e('11,project,user15,outside,1'); // 获取项目ID=11时，按照name倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[3], $sorts[0])) && p('12:objectID,objectType,user,type,key') && e('11,project,user11,inside,1');  // 获取项目ID=11时，按照id倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[3], $sorts[1])) && p('12:objectID,objectType,user,type,key') && e('11,project,user11,inside,1');  // 获取项目ID=11时，按照name倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[4], $sorts[0])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照id倒序排列的不存在类型干系人
r($stakeholderTester->getStakeholdersTest($projectIds[1], $browseTypes[4], $sorts[1])) && p('11:objectID,objectType,user,type,key') && e('11,project,user10,inside,0');  // 获取项目ID=11时，按照name倒序排列的不存在类型干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[0], $sorts[0])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照id倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[0], $sorts[1])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照name倒序排列的所有干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[1], $sorts[0])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照id倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[1], $sorts[1])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照name倒序排列的内部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[2], $sorts[0])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照id倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[2], $sorts[1])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照name倒序排列的外部干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[3], $sorts[0])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照id倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[3], $sorts[1])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照name倒序排列的关键干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[4], $sorts[0])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照id倒序排列的不存在类型干系人
r($stakeholderTester->getStakeholdersTest($projectIds[2], $browseTypes[4], $sorts[1])) && p('')                                     && e('0');                           // 获取项目ID不存在时，按照name倒序排列的不存在类型干系人
