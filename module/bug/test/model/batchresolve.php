#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('user1');

/**

title=测试bugModel->batchResolve();
timeout=0
cid=1

- 解决bug1 2 3,解决方式为bydesign
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @bydesign;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug4 5 6,解决方式为duplicate
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @duplicate;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug7 8 9,解决方式为external
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @external;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug10 11 12,解决方式为fixed
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;4:field属性 @fixed;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug13 14 15,解决方式为notrepro
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @notrepro;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug16 17 18,解决方式为postponed
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @postponed;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug19 20 21,解决方式为willnotfix
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @willnotfix;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug22 23 24,解决方式为tostory
 - 第0条的field属性 @resolution
 - 第0条的old属性 @~~
 - 第0条的new;3:field属性 @tostory;status
 - 第0条的old属性 @active
 - 第0条的new属性 @resolved

- 解决bug状态为resolve的bug
 - 第0条的field属性 @0
 - 第0条的old属性 @0
 - 第0条的new;3:field属性 @0
 - 第0条的old属性 @0
 - 第0条的new属性 @0

*/

$bug = zdTable('bug');
$bug->execution->range('11-50');
$bug->gen(50);

zdTable('project')->config('execution')->gen(50);

$bugIDList1 = array('1', '2', '3');
$bugIDList2 = array('4', '5', '6');
$bugIDList3 = array('7', '8', '9');
$bugIDList4 = array('10', '11', '12');
$bugIDList5 = array('13', '14', '15');
$bugIDList6 = array('16', '17', '18');
$bugIDList7 = array('19', '20', '21');
$bugIDList8 = array('22', '23', '24');

$resolutionList = array('bydesign', 'duplicate', 'external', 'fixed', 'notrepro', 'postponed', 'willnotfix', 'tostory');

$bug = new bugTest();
r($bug->batchResolveTest($bugIDList1, $resolutionList[0], $bugIDList1[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,bydesign;status,active,resolved');   // 解决bug1 2 3,解决方式为bydesign
r($bug->batchResolveTest($bugIDList2, $resolutionList[1], $bugIDList2[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,duplicate;status,active,resolved');  // 解决bug4 5 6,解决方式为duplicate
r($bug->batchResolveTest($bugIDList3, $resolutionList[2], $bugIDList3[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,external;status,active,resolved');   // 解决bug7 8 9,解决方式为external
r($bug->batchResolveTest($bugIDList4, $resolutionList[3], $bugIDList4[0])) && p('0:field,old,new;4:field,old,new') && e('resolution,~~,fixed;status,active,resolved');      // 解决bug10 11 12,解决方式为fixed
r($bug->batchResolveTest($bugIDList5, $resolutionList[4], $bugIDList5[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,notrepro;status,active,resolved');   // 解决bug13 14 15,解决方式为notrepro
r($bug->batchResolveTest($bugIDList6, $resolutionList[5], $bugIDList6[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,postponed;status,active,resolved');  // 解决bug16 17 18,解决方式为postponed
r($bug->batchResolveTest($bugIDList7, $resolutionList[6], $bugIDList7[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,willnotfix;status,active,resolved'); // 解决bug19 20 21,解决方式为willnotfix
r($bug->batchResolveTest($bugIDList8, $resolutionList[7], $bugIDList8[0])) && p('0:field,old,new;3:field,old,new') && e('resolution,~~,tostory;status,active,resolved');    // 解决bug22 23 24,解决方式为tostory
r($bug->batchResolveTest($bugIDList8, $resolutionList[7], $bugIDList8[0])) && p('0:field,old,new;3:field,old,new') && e('0');                                             // 解决bug状态为resolve的bug
