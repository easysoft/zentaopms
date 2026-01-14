#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('product')->gen('42');

su('admin');

/**

title=测试 testcaseModel->isClickable();
cid=19012

- 测试用例1，是否可以进行 confirmchange 操作 @0
- 测试用例1，是否可以进行 confirmstorychange 操作 @0
- 测试用例1，是否可以进行 createbug 操作 @0
- 测试用例1，是否可以进行 review 操作 @0
- 测试用例1，是否可以进行 showscript 操作 @0
- 测试用例1，是否可以进行 edit 操作 @1
- 测试 caseStatus wait 是否可以进行 confirmchange 操作 @0
- 测试 caseStatus normal 是否可以进行 confirmchange 操作 @0
- 测试 caseVersion 0 是否可以进行 confirmchange 操作 @0
- 测试 caseVersion 10 是否可以进行 confirmchange 操作 @0
- 测试 caseStatus wait caseVersion 0 是否可以进行 confirmchange 操作 @0
- 测试 caseStatus wait caseVersion 10 是否可以进行 confirmchange 操作 @0
- 测试 caseStatus normal caseVersion 0 是否可以进行 confirmchange 操作 @0
- 测试 caseStatus normal caseVersion 10 是否可以进行 confirmchange 操作 @1
- 测试 needConfirm true browseType 空 是否可以进行 confirmstorychange 操作 @1
- 测试 browseType needConfirm 是否可以进行 confirmstorychange 操作 @1
- 测试 browseType normal 是否可以进行 confirmstorychange 操作 @0
- 测试 needConfirm true browseType needConfirm 是否可以进行 confirmstorychange 操作 @1
- 测试 needConfirm true browseType normal 是否可以进行 confirmstorychange 操作 @1
- 测试 caseFails 0 是否可以进行 createbug 操作 @0
- 测试 caseFails 10 是否可以进行 createbug 操作 @1
- 测试 status wait 是否可以进行 review 操作 @0
- 测试 status normal 是否可以进行 review 操作 @0
- 测试 caseStatus wait 是否可以进行 review 操作 @0
- 测试 caseStatus normal 是否可以进行 review 操作 @0
- 测试 needReview 1 是否可以进行 review 操作 @1
- 测试 forceReview 1 是否可以进行 review 操作 @1
- 测试 needReview 1 forceReview 1 是否可以进行 review 操作 @1
- 测试 status wait needReview 1是否可以进行 review 操作 @1
- 测试 status wait forceReview 1是否可以进行 review 操作 @1
- 测试 status wait needReview 1 forceReview 1是否可以进行 review 操作 @1
- 测试 status normal needReview 1是否可以进行 review 操作 @0
- 测试 status normal forceReview 1是否可以进行 review 操作 @0
- 测试 status normal needReview 1 forceReview 1是否可以进行 review 操作 @0
- 测试 status wait caseStatus wait needReview 1是否可以进行 review 操作 @1
- 测试 status wait caseStatus wait forceReview 1是否可以进行 review 操作 @1
- 测试 status wait caseStatus wait needReview 1 forceReview 1是否可以进行 review 操作 @1
- 测试 status wait caseStatus normal needReview 1是否可以进行 review 操作 @0
- 测试 status wait caseStatus normal forceReview 1是否可以进行 review 操作 @0
- 测试 status wait caseStatus normal needReview 1 forceReview 1是否可以进行 review 操作 @0
- 测试 status normal caseStatus wait needReview 1是否可以进行 review 操作 @1
- 测试 status normal caseStatus wait forceReview 1是否可以进行 review 操作 @1
- 测试 status normal caseStatus wait needReview 1 forceReview 1是否可以进行 review 操作 @1
- 测试 status normal caseStatus normal needReview 1是否可以进行 review 操作 @0
- 测试 status normal caseStatus normal forceReview 1是否可以进行 review 操作 @0
- 测试 status normal caseStatus normal needReview 1 forceReview 1是否可以进行 review 操作 @0
- 测试 auto auto 是否可以进行 showscript 操作 @1
- 测试 auto no 是否可以进行 showscript 操作 @0

*/

$waitCaseStatus   = array('caseStatus'  => 'wait');
$normalCaseStatus = array('caseStatus'  => 'normal');
$zeroCaseVersion  = array('caseVersion' => 0);
$tenCaseVersion   = array('caseVersion' => 10);
$needConfirm      = array('needconfirm' => true);
$notNeedConfirm   = array('needconfirm' => false);
$confirmType      = array('browseType'  => 'needconfirm');
$normalType       = array('browseType'  => 'normal');
$zeroCaseFails    = array('caseFails'   => 0);
$tenCaseFails     = array('caseFails'   => 10);
$waitStatus       = array('status'      => 'wait');
$normalStatus     = array('status'      => 'normal');
$autoAuto         = array('auto'        => 'auto');
$noAuto           = array('auto'        => 'no');

$actionList = array('confirmchange', 'confirmstorychange', 'createbug', 'review', 'showscript', 'edit');

$needReview  = array('needReview'  => 1);
$forceReview = array('forceReview' => 1);

$testcase = new testcaseModelTest();

r($testcase->isClickableTest($actionList[0])) && p()  && e('0'); // 测试用例1，是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[1])) && p()  && e('0'); // 测试用例1，是否可以进行 confirmstorychange 操作
r($testcase->isClickableTest($actionList[2])) && p()  && e('0'); // 测试用例1，是否可以进行 createbug 操作
r($testcase->isClickableTest($actionList[3])) && p()  && e('0'); // 测试用例1，是否可以进行 review 操作
r($testcase->isClickableTest($actionList[4])) && p()  && e('0'); // 测试用例1，是否可以进行 showscript 操作
r($testcase->isClickableTest($actionList[5])) && p()  && e('1'); // 测试用例1，是否可以进行 edit 操作

r($testcase->isClickableTest($actionList[0], $waitCaseStatus))                                  && p() && e('0'); // 测试 caseStatus wait 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], $normalCaseStatus))                                && p() && e('0'); // 测试 caseStatus normal 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], $zeroCaseVersion))                                 && p() && e('0'); // 测试 caseVersion 0 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], $tenCaseVersion))                                  && p() && e('0'); // 测试 caseVersion 10 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], array_merge($waitCaseStatus, $zeroCaseVersion)))   && p() && e('0'); // 测试 caseStatus wait caseVersion 0 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], array_merge($waitCaseStatus, $tenCaseVersion)))    && p() && e('0'); // 测试 caseStatus wait caseVersion 10 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], array_merge($normalCaseStatus, $zeroCaseVersion))) && p() && e('0'); // 测试 caseStatus normal caseVersion 0 是否可以进行 confirmchange 操作
r($testcase->isClickableTest($actionList[0], array_merge($normalCaseStatus, $tenCaseVersion)))  && p() && e('1'); // 测试 caseStatus normal caseVersion 10 是否可以进行 confirmchange 操作

r($testcase->isClickableTest($actionList[1], $needConfirm))                            && p() && e('1'); // 测试 needConfirm true browseType 空 是否可以进行 confirmstorychange 操作
r($testcase->isClickableTest($actionList[1], $confirmType))                            && p() && e('1'); // 测试 browseType needConfirm 是否可以进行 confirmstorychange 操作
r($testcase->isClickableTest($actionList[1], $normalType))                             && p() && e('0'); // 测试 browseType normal 是否可以进行 confirmstorychange 操作
r($testcase->isClickableTest($actionList[1], array_merge($needConfirm, $confirmType))) && p() && e('1'); // 测试 needConfirm true browseType needConfirm 是否可以进行 confirmstorychange 操作
r($testcase->isClickableTest($actionList[1], array_merge($needConfirm, $normalType)))  && p() && e('1'); // 测试 needConfirm true browseType normal 是否可以进行 confirmstorychange 操作

r($testcase->isClickableTest($actionList[2], $zeroCaseFails)) && p() && e('0'); // 测试 caseFails 0 是否可以进行 createbug 操作
r($testcase->isClickableTest($actionList[2], $tenCaseFails))  && p() && e('1'); // 测试 caseFails 10 是否可以进行 createbug 操作

r($testcase->isClickableTest($actionList[3], $waitStatus))                                                                           && p() && e('0'); // 测试 status wait 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $normalStatus))                                                                         && p() && e('0'); // 测试 status normal 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $waitCaseStatus))                                                                       && p() && e('0'); // 测试 caseStatus wait 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $normalCaseStatus))                                                                     && p() && e('0'); // 测试 caseStatus normal 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array(), $needReview))                                                                  && p() && e('1'); // 测试 needReview 1 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array(), $forceReview))                                                                 && p() && e('1'); // 测试 forceReview 1 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array(), array_merge($needReview, $forceReview)))                                       && p() && e('1'); // 测试 needReview 1 forceReview 1 是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $waitStatus, $needReview))                                                              && p() && e('1'); // 测试 status wait needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $waitStatus, $forceReview))                                                             && p() && e('1'); // 测试 status wait forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $waitStatus, array_merge($needReview, $forceReview)))                                   && p() && e('1'); // 测试 status wait needReview 1 forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $normalStatus, $needReview))                                                            && p() && e('0'); // 测试 status normal needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $normalStatus, $forceReview))                                                           && p() && e('0'); // 测试 status normal forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], $normalStatus, array_merge($needReview, $forceReview)))                                 && p() && e('0'); // 测试 status normal needReview 1 forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $waitCaseStatus), $needReview))                                && p() && e('1'); // 测试 status wait caseStatus wait needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $waitCaseStatus), $forceReview))                               && p() && e('1'); // 测试 status wait caseStatus wait forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $waitCaseStatus), array_merge($needReview, $forceReview)))     && p() && e('1'); // 测试 status wait caseStatus wait needReview 1 forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $normalCaseStatus), $needReview))                              && p() && e('0'); // 测试 status wait caseStatus normal needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $normalCaseStatus), $forceReview))                             && p() && e('0'); // 测试 status wait caseStatus normal forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($waitStatus, $normalCaseStatus), array_merge($needReview, $forceReview)))   && p() && e('0'); // 测试 status wait caseStatus normal needReview 1 forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $waitCaseStatus), $needReview))                              && p() && e('1'); // 测试 status normal caseStatus wait needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $waitCaseStatus), $forceReview))                             && p() && e('1'); // 测试 status normal caseStatus wait forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $waitCaseStatus), array_merge($needReview, $forceReview)))   && p() && e('1'); // 测试 status normal caseStatus wait needReview 1 forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $normalCaseStatus), $needReview))                            && p() && e('0'); // 测试 status normal caseStatus normal needReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $normalCaseStatus), $forceReview))                           && p() && e('0'); // 测试 status normal caseStatus normal forceReview 1是否可以进行 review 操作
r($testcase->isClickableTest($actionList[3], array_merge($normalStatus, $normalCaseStatus), array_merge($needReview, $forceReview))) && p() && e('0'); // 测试 status normal caseStatus normal needReview 1 forceReview 1是否可以进行 review 操作

r($testcase->isClickableTest($actionList[4], $autoAuto)) && p() && e('1'); // 测试 auto auto 是否可以进行 showscript 操作
r($testcase->isClickableTest($actionList[4], $noAuto))   && p() && e('0'); // 测试 auto no 是否可以进行 showscript 操作
