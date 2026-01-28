#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getByID();
cid=18431

- 测试获取stakeholderID=0的干系人信息 @0
- 测试获取stakeholderID=1的干系人信息
 - 属性objectID @1
 - 属性objectType @program
 - 属性user @admin
 - 属性type @inside
 - 属性key @0
 - 属性createdBy @admin
 - 属性from @team
 - 属性name @admin
 - 属性companyName @易软天创网络科技有限公司
 - 属性company @1
- 测试获取stakeholderID不存在的干系人信息 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(20);
zenData('company')->gen(1);
zenData('stakeholder')->loadYaml('stakeholder')->gen(1);

$idList = array(0, 1, 2);

$stakeholderTester = new stakeholderModelTest();
r($stakeholderTester->getByIDTest($idList[0])) && p()                                                                            && e('0');                                                                    // 测试获取stakeholderID=0的干系人信息
r($stakeholderTester->getByIDTest($idList[1])) && p('objectID,objectType,user,type,key,createdBy,from,name,companyName,company') && e('1,program,admin,inside,0,admin,team,admin,易软天创网络科技有限公司,1'); // 测试获取stakeholderID=1的干系人信息
r($stakeholderTester->getByIDTest($idList[2])) && p()                                                                            && e('0');                                                                    // 测试获取stakeholderID不存在的干系人信息
