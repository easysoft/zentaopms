#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(2);

/**

title=测试 testcaseModel->batchChangeType();
timeout=0
cid=18961

- 用例参数为空返回 false。 @0
- 用例参数不为空、类型参数为空返回 false。 @0
- 用例参数对应的用例不存在，返回 false。 @0
- 批量修改用例类型为 other 成功，返回 true。 @1
- 批量修改用例类型后类型为 other。
 - 第1条的type属性 @other
 - 第2条的type属性 @other
- 批量修改用例类型为 interface 成功，返回 true。 @1
- 批量修改用例类型后类型为 interface。
 - 第1条的type属性 @interface
 - 第2条的type属性 @interface
- 批量修改用例类型为 feature 成功，返回 true。 @1
- 批量修改用例类型后类型为 feature。
 - 第1条的type属性 @feature
 - 第2条的type属性 @feature
- 批量修改用例类型为 install 成功，返回 true。 @1
- 批量修改用例类型后类型为 install。
 - 第1条的type属性 @install
 - 第2条的type属性 @install
- 批量修改用例类型为 config 成功，返回 true。 @1
- 批量修改用例类型后类型为 config。
 - 第1条的type属性 @config
 - 第2条的type属性 @config
- 批量修改用例类型为 performance 成功，返回 true。 @1
- 批量修改用例类型后类型为 performance。
 - 第1条的type属性 @performance
 - 第2条的type属性 @performance
- 批量修改用例类型为 security 成功，返回 true。 @1
- 批量修改用例类型后类型为 security。
 - 第1条的type属性 @security
 - 第2条的type属性 @security
- 批量修改用例类型为 other 成功，返回 true。 @1
- 批量修改用例类型后类型为 other。
 - 第1条的type属性 @other
 - 第2条的type属性 @other
- 批量修改用例类型后记录日志。
 - 第0条的objectType属性 @case
 - 第0条的objectID属性 @2
 - 第0条的action属性 @edited
 - 第0条的extra属性 @Other
 - 第1条的objectType属性 @case
 - 第1条的objectID属性 @1
 - 第1条的action属性 @edited
 - 第1条的extra属性 @Other
- 批量修改用例类型后记录日志。
 - 第2条的objectType属性 @case
 - 第2条的objectID属性 @2
 - 第2条的action属性 @edited
 - 第2条的extra属性 @Security
 - 第3条的objectType属性 @case
 - 第3条的objectID属性 @1
 - 第3条的action属性 @edited
 - 第3条的extra属性 @Security
- 批量修改用例类型后记录日志。
 - 第4条的objectType属性 @case
 - 第4条的objectID属性 @2
 - 第4条的action属性 @edited
 - 第4条的extra属性 @Performance
 - 第5条的objectType属性 @case
 - 第5条的objectID属性 @1
 - 第5条的action属性 @edited
 - 第5条的extra属性 @Performance
- 批量修改用例类型后记录日志。
 - 第6条的objectType属性 @case
 - 第6条的objectID属性 @2
 - 第6条的action属性 @edited
 - 第6条的extra属性 @Config
 - 第7条的objectType属性 @case
 - 第7条的objectID属性 @1
 - 第7条的action属性 @edited
 - 第7条的extra属性 @Config
- 批量修改用例类型后记录日志。
 - 第8条的objectType属性 @case
 - 第8条的objectID属性 @2
 - 第8条的action属性 @edited
 - 第8条的extra属性 @Install
 - 第9条的objectType属性 @case
 - 第9条的objectID属性 @1
 - 第9条的action属性 @edited
 - 第9条的extra属性 @Install
- 批量修改用例类型后记录日志。
 - 第10条的objectType属性 @case
 - 第10条的objectID属性 @2
 - 第10条的action属性 @edited
 - 第10条的extra属性 @Feature
 - 第11条的objectType属性 @case
 - 第11条的objectID属性 @1
 - 第11条的action属性 @edited
 - 第11条的extra属性 @Feature
- 批量修改用例类型后记录日志。
 - 第12条的objectType属性 @case
 - 第12条的objectID属性 @2
 - 第12条的action属性 @edited
 - 第12条的extra属性 @Interface
 - 第13条的objectType属性 @case
 - 第13条的objectID属性 @1
 - 第13条的action属性 @edited
 - 第13条的extra属性 @Interface
- 批量修改用例类型后记录日志详情，type 字段从 security    变成 other。
 - 第0条的field属性 @type
 - 第0条的old属性 @security
 - 第0条的new属性 @other
 - 第1条的field属性 @type
 - 第1条的old属性 @security
 - 第1条的new属性 @other
- 批量修改用例类型后记录日志详情，type 字段从 performance 变成 security。
 - 第2条的field属性 @type
 - 第2条的old属性 @performance
 - 第2条的new属性 @security
 - 第3条的field属性 @type
 - 第3条的old属性 @performance
 - 第3条的new属性 @security
- 批量修改用例类型后记录日志详情，type 字段从 config      变成 performance。
 - 第4条的field属性 @type
 - 第4条的old属性 @config
 - 第4条的new属性 @performance
 - 第5条的field属性 @type
 - 第5条的old属性 @config
 - 第5条的new属性 @performance
- 批量修改用例类型后记录日志详情，type 字段从 install     变成 config。
 - 第6条的field属性 @type
 - 第6条的old属性 @install
 - 第6条的new属性 @config
 - 第7条的field属性 @type
 - 第7条的old属性 @install
 - 第7条的new属性 @config
- 批量修改用例类型后记录日志详情，type 字段从 feature     变成 install。
 - 第8条的field属性 @type
 - 第8条的old属性 @feature
 - 第8条的new属性 @install
 - 第9条的field属性 @type
 - 第9条的old属性 @feature
 - 第9条的new属性 @install
- 批量修改用例类型后记录日志详情，type 字段从 interface   变成 feature。
 - 第10条的field属性 @type
 - 第10条的old属性 @interface
 - 第10条的new属性 @feature
 - 第11条的field属性 @type
 - 第11条的old属性 @interface
 - 第11条的new属性 @feature
- 批量修改用例类型后记录日志详情，type 字段从 other       变成 interface。
 - 第12条的field属性 @type
 - 第12条的old属性 @other
 - 第12条的new属性 @interface
 - 第13条的field属性 @type
 - 第13条的old属性 @other
 - 第13条的new属性 @interface
- 批量修改的用例类型和类型参数一致返回 false。 @0
- 批量删除用例返回 true。 @1
- 批量修改已删除用例类型返回 false。 @0

*/

$testcase   = new testcaseModelTest();
$caseIdList = array(array(), array(1, 2), array(3, 4));

r($testcase->batchChangeTypeTest($caseIdList[0], 1))  && p() && e('0'); // 用例参数为空返回 false。
r($testcase->batchChangeTypeTest($caseIdList[1], '')) && p() && e('0'); // 用例参数不为空、类型参数为空返回 false。
r($testcase->batchChangeTypeTest($caseIdList[2], 1))  && p() && e('0'); // 用例参数对应的用例不存在，返回 false。

r($testcase->batchChangeTypeTest($caseIdList[1], 'other'))        && p() && e('1');                         // 批量修改用例类型为 other 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('other,other');             // 批量修改用例类型后类型为 other。
r($testcase->batchChangeTypeTest($caseIdList[1], 'interface'))    && p() && e('1');                         // 批量修改用例类型为 interface 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('interface,interface');     // 批量修改用例类型后类型为 interface。
r($testcase->batchChangeTypeTest($caseIdList[1], 'feature'))      && p() && e('1');                         // 批量修改用例类型为 feature 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('feature,feature');         // 批量修改用例类型后类型为 feature。
r($testcase->batchChangeTypeTest($caseIdList[1], 'install'))      && p() && e('1');                         // 批量修改用例类型为 install 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('install,install');         // 批量修改用例类型后类型为 install。
r($testcase->batchChangeTypeTest($caseIdList[1], 'config'))       && p() && e('1');                         // 批量修改用例类型为 config 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('config,config');           // 批量修改用例类型后类型为 config。
r($testcase->batchChangeTypeTest($caseIdList[1], 'performance'))  && p() && e('1');                         // 批量修改用例类型为 performance 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('performance,performance'); // 批量修改用例类型后类型为 performance。
r($testcase->batchChangeTypeTest($caseIdList[1], 'security'))     && p() && e('1');                         // 批量修改用例类型为 security 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('security,security');       // 批量修改用例类型后类型为 security。
r($testcase->batchChangeTypeTest($caseIdList[1], 'other'))        && p() && e('1');                         // 批量修改用例类型为 other 成功，返回 true。
r($testcase->objectModel->getByList($caseIdList[1])) && p('1:type;2:type') && e('other,other');             // 批量修改用例类型后类型为 other。

$actions = $testcase->objectModel->dao->select('objectType,objectID,action,extra')->from(TABLE_ACTION)->orderBy('id_desc')->limit(14)->fetchAll();
r($actions) && p('0:objectType,objectID,action,extra;1:objectType,objectID,action,extra')   && e('case,2,edited,Other,case,1,edited,Other');             // 批量修改用例类型后记录日志。
r($actions) && p('2:objectType,objectID,action,extra;3:objectType,objectID,action,extra')   && e('case,2,edited,Security,case,1,edited,Security');       // 批量修改用例类型后记录日志。
r($actions) && p('4:objectType,objectID,action,extra;5:objectType,objectID,action,extra')   && e('case,2,edited,Performance,case,1,edited,Performance'); // 批量修改用例类型后记录日志。
r($actions) && p('6:objectType,objectID,action,extra;7:objectType,objectID,action,extra')   && e('case,2,edited,Config,case,1,edited,Config');           // 批量修改用例类型后记录日志。
r($actions) && p('8:objectType,objectID,action,extra;9:objectType,objectID,action,extra')   && e('case,2,edited,Install,case,1,edited,Install');         // 批量修改用例类型后记录日志。
r($actions) && p('10:objectType,objectID,action,extra;11:objectType,objectID,action,extra') && e('case,2,edited,Feature,case,1,edited,Feature');         // 批量修改用例类型后记录日志。
r($actions) && p('12:objectType,objectID,action,extra;13:objectType,objectID,action,extra') && e('case,2,edited,Interface,case,1,edited,Interface');     // 批量修改用例类型后记录日志。

$histories = $testcase->objectModel->dao->select('field,old,new')->from(TABLE_HISTORY)->orderBy('id_desc')->limit(14)->fetchAll();
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
