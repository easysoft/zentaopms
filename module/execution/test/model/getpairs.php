#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution')->gen(30);

/**

title=测试executionModel->getPairs();
timeout=0
cid=16332

- 敏捷项目执行查看属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目执行查看属性109 @/阶段13
- 看板项目执行查看属性126 @/看板30
- 敏捷项目执行统计 @5
- 敏捷项目执行统计 @12
- 敏捷项目执行统计 @3
- 敏捷项目查看所有执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看所有执行属性109 @/阶段13
- 看板项目查看所有执行属性126 @/看板30
- 敏捷项目查看未关闭执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看未关闭执行属性109 @/阶段13
- 看板项目查看未关闭执行属性126 @/看板30
- 敏捷项目查看非需求、设计、评审阶段的执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看非需求、设计、评审阶段的执行属性109 @/阶段13
- 看板项目查看非需求、设计、评审阶段的执行属性126 @/看板30
- 敏捷项目查看包括已删除的执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看包括已删除的执行属性109 @/阶段13
- 看板项目查看包括已删除的执行属性126 @/看板30
- 敏捷项目查看包括非影子执行属性103 @/迭代7
- 瀑布项目查看包括非影子执行属性109 @/阶段13
- 看板项目查看包括非影子执行属性126 @/看板30
- 敏捷项目查看非父阶段执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看非父阶段执行属性112 @/阶段10/阶段16
- 看板项目查看非父阶段执行属性126 @/看板30
- 敏捷项目按照排序正序获取执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目按照排序正序获取执行属性109 @/阶段13
- 看板项目按照排序正序获取执行属性126 @/看板30
- 敏捷项目查看没有前缀/的执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目查看没有前缀/的执行属性109 @阶段13
- 看板项目查看没有前缀/的执行属性126 @看板30
- 敏捷项目获取带项目名称的执行属性105 @敏捷项目1(不启用迭代的项目)
- 瀑布项目获取带项目名称的执行属性109 @瀑布项目2/阶段13
- 看板项目获取带项目名称的执行属性126 @看板项目4/看板30
- 敏捷项目获取非影子执行属性103 @/迭代7
- 瀑布项目获取非影子执行属性109 @/阶段13
- 看板项目获取非影子执行属性126 @/看板30

*/

$projectIDList = array(11, 60, 100);
$count         = array(0, 1);
$modeList      = array('', 'all', 'noclosed', 'stagefilter', 'withdelete', 'multiple', 'leaf', 'order_asc', 'noprefix', 'withobject', 'hideMultiple');

$executionTester = new executionModelTest();
$executionTester->executionModel->app->user->admin = true;
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[0]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目执行查看
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[0]))  && p('109') && e('/阶段13');                     // 瀑布项目执行查看
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[0]))  && p('126') && e('/看板30');                     // 看板项目执行查看
r($executionTester->getPairsTest($projectIDList[0], $count[1], $modeList[0]))  && p()      && e('5');                           // 敏捷项目执行统计
r($executionTester->getPairsTest($projectIDList[1], $count[1], $modeList[0]))  && p()      && e('12');                          // 敏捷项目执行统计
r($executionTester->getPairsTest($projectIDList[2], $count[1], $modeList[0]))  && p()      && e('3');                           // 敏捷项目执行统计
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[1]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看所有执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[1]))  && p('109') && e('/阶段13');                     // 瀑布项目查看所有执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[1]))  && p('126') && e('/看板30');                     // 看板项目查看所有执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[2]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看未关闭执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[2]))  && p('109') && e('/阶段13');                     // 瀑布项目查看未关闭执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[2]))  && p('126') && e('/看板30');                     // 看板项目查看未关闭执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[3]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看非需求、设计、评审阶段的执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[3]))  && p('109') && e('/阶段13');                     // 瀑布项目查看非需求、设计、评审阶段的执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[3]))  && p('126') && e('/看板30');                     // 看板项目查看非需求、设计、评审阶段的执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[4]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看包括已删除的执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[4]))  && p('109') && e('/阶段13');                     // 瀑布项目查看包括已删除的执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[4]))  && p('126') && e('/看板30');                     // 看板项目查看包括已删除的执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[5]))  && p('103') && e('/迭代7');                      // 敏捷项目查看包括非影子执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[5]))  && p('109') && e('/阶段13');                     // 瀑布项目查看包括非影子执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[5]))  && p('126') && e('/看板30');                     // 看板项目查看包括非影子执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[6]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看非父阶段执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[6]))  && p('112') && e('/阶段10/阶段16');              // 瀑布项目查看非父阶段执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[6]))  && p('126') && e('/看板30');                     // 看板项目查看非父阶段执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[7]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目按照排序正序获取执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[7]))  && p('109') && e('/阶段13');                     // 瀑布项目按照排序正序获取执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[7]))  && p('126') && e('/看板30');                     // 看板项目按照排序正序获取执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[8]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目查看没有前缀/的执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[8]))  && p('109') && e('阶段13');                      // 瀑布项目查看没有前缀/的执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[8]))  && p('126') && e('看板30');                      // 看板项目查看没有前缀/的执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[9]))  && p('105') && e('敏捷项目1(不启用迭代的项目)'); // 敏捷项目获取带项目名称的执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[9]))  && p('109') && e('瀑布项目2/阶段13');            // 瀑布项目获取带项目名称的执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[9]))  && p('126') && e('看板项目4/看板30');            // 看板项目获取带项目名称的执行
r($executionTester->getPairsTest($projectIDList[0], $count[0], $modeList[10])) && p('103') && e('/迭代7');                      // 敏捷项目获取非影子执行
r($executionTester->getPairsTest($projectIDList[1], $count[0], $modeList[10])) && p('109') && e('/阶段13');                     // 瀑布项目获取非影子执行
r($executionTester->getPairsTest($projectIDList[2], $count[0], $modeList[10])) && p('126') && e('/看板30');                     // 看板项目获取非影子执行
