#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen(2);

/**

title=测试 testcaseModel->batchChangeType();
cid=1
pid=1

*/

$testcase   = new testcaseTest();
$caseIdList = array(array(), array(1, 2), array(3, 4));

r($testcase->batchChangeTypeTest($caseIdList[0], 1))  && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchChangeTypeTest($caseIdList[1], '')) && p() && e('0'); // 用例参数不为空、类型参数为空返回 false。
r($testcase->batchChangeTypeTest($caseIdList[2], 1))  && p() && e('0'); // 用例参数对应的用例不存在，返回 false。

r($testcase->batchChangeTypeTest($caseIdList[1], 'other'))        && p() && e('1');                // 批量修改用例类型为 other 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('other,other');             // 批量修改用例类型后类型为 other。
r($testcase->batchChangeTypeTest($caseIdList[1], 'interface'))    && p() && e('1');                // 批量修改用例类型为 interface 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('interface,interface');     // 批量修改用例类型后类型为 interface。
r($testcase->batchChangeTypeTest($caseIdList[1], 'feature'))      && p() && e('1');                // 批量修改用例类型为 feature 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('feature,feature');         // 批量修改用例类型后类型为 feature。
r($testcase->batchChangeTypeTest($caseIdList[1], 'install'))      && p() && e('1');                // 批量修改用例类型为 install 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('install,install');         // 批量修改用例类型后类型为 install。
r($testcase->batchChangeTypeTest($caseIdList[1], 'config'))       && p() && e('1');                // 批量修改用例类型为 config 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('config,config');           // 批量修改用例类型后类型为 config。
r($testcase->batchChangeTypeTest($caseIdList[1], 'performance'))  && p() && e('1');                // 批量修改用例类型为 performance 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('performance,performance'); // 批量修改用例类型后类型为 performance。
r($testcase->batchChangeTypeTest($caseIdList[1], 'security'))     && p() && e('1');                // 批量修改用例类型为 security 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('security,security');       // 批量修改用例类型后类型为 security。
r($testcase->batchChangeTypeTest($caseIdList[1], 'other'))        && p() && e('1');                // 批量修改用例类型为 other 成功，返回 true。
r($testcase->getByListTest($caseIdList[1])) && p('1:type;2:type') && e('other,other');             // 批量修改用例类型后类型为 other。

$actions = $testcase->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(14)->fetchAll();
r($actions) && p('0:objectType,objectID,action,extra;1:objectType,objectID,action,extra')   && e('case,2,edited,Other,case,1,edited,Other');             // 批量修改用例类型后记录日志。
r($actions) && p('2:objectType,objectID,action,extra;3:objectType,objectID,action,extra')   && e('case,2,edited,Security,case,1,edited,Security');       // 批量修改用例类型后记录日志。
r($actions) && p('4:objectType,objectID,action,extra;5:objectType,objectID,action,extra')   && e('case,2,edited,Performance,case,1,edited,Performance'); // 批量修改用例类型后记录日志。
r($actions) && p('6:objectType,objectID,action,extra;7:objectType,objectID,action,extra')   && e('case,2,edited,Config,case,1,edited,Config');           // 批量修改用例类型后记录日志。
r($actions) && p('8:objectType,objectID,action,extra;9:objectType,objectID,action,extra')   && e('case,2,edited,Install,case,1,edited,Install');         // 批量修改用例类型后记录日志。
r($actions) && p('10:objectType,objectID,action,extra;11:objectType,objectID,action,extra') && e('case,2,edited,Feature,case,1,edited,Feature');         // 批量修改用例类型后记录日志。
r($actions) && p('12:objectType,objectID,action,extra;13:objectType,objectID,action,extra') && e('case,2,edited,Interface,case,1,edited,Interface');     // 批量修改用例类型后记录日志。

$histories = $testcase->objectModel->dao->select('*')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(14)->fetchAll();
r($histories) && p('0:field,old,new;1:field,old,new')   && e('type,security,other,type,security,other');             // 批量修改用例类型后记录日志详情，type 字段从 security    变成 other。
r($histories) && p('2:field,old,new;3:field,old,new')   && e('type,performance,security,type,performance,security'); // 批量修改用例类型后记录日志详情，type 字段从 performance 变成 security。
r($histories) && p('4:field,old,new;5:field,old,new')   && e('type,config,performance,type,config,performance');     // 批量修改用例类型后记录日志详情，type 字段从 config      变成 performance。
r($histories) && p('6:field,old,new;7:field,old,new')   && e('type,install,config,type,install,config');             // 批量修改用例类型后记录日志详情，type 字段从 install     变成 config。
r($histories) && p('8:field,old,new;9:field,old,new')   && e('type,feature,install,type,feature,install');           // 批量修改用例类型后记录日志详情，type 字段从 feature     变成 install。
r($histories) && p('10:field,old,new;11:field,old,new') && e('type,interface,feature,type,interface,feature');       // 批量修改用例类型后记录日志详情，type 字段从 interface   变成 feature。
r($histories) && p('12:field,old,new;13:field,old,new') && e('type,other,interface,type,other,interface');           // 批量修改用例类型后记录日志详情，type 字段从 other       变成 interface。

r($testcase->batchChangeTypeTest($caseIdList[1], 'other'))     && p() && e('0'); // 批量修改的用例类型和类型参数一致返回 false。
r($testcase->batchDeleteTest($caseIdList[1], array()))         && p() && e('1'); // 批量删除用例返回 true。
r($testcase->batchChangeTypeTest($caseIdList[1], 'interface')) && p() && e('0'); // 批量修改已删除用例类型返回 false。
