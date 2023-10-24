#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchReview();
cid=1
pid=1

测试评审case 401 402 为clarify >> 102
测试评审case 403 404 为clarify >> 0
测试评审case 405 406 为clarify >> 103
测试评审case 407 408 为clarify >> 0
测试评审case 409 410 为clarify >> 104
测试评审case 401 402 为pass >> 105
测试评审case 403 404 为pass >> 0
测试评审case 405 406 为pass >> 106
测试评审case 407 408 为pass >> 0
测试评审case 409 410 为pass >> 107

*/
$caseIDList = array(array(401, 402), array(403, 404), array(405, 406), array(407, 408), array(409, 410));
$resultList = array('clarify', 'pass');

$testcase = new testcaseTest();

r($testcase->batchReviewTest($caseIDList[0], $resultList[0])) && p('401') && e('102'); // 测试评审case 401 402 为clarify
r($testcase->batchReviewTest($caseIDList[1], $resultList[0])) && p()      && e('0');   // 测试评审case 403 404 为clarify
r($testcase->batchReviewTest($caseIDList[2], $resultList[0])) && p('405') && e('103'); // 测试评审case 405 406 为clarify
r($testcase->batchReviewTest($caseIDList[3], $resultList[0])) && p()      && e('0');   // 测试评审case 407 408 为clarify
r($testcase->batchReviewTest($caseIDList[4], $resultList[0])) && p('409') && e('104'); // 测试评审case 409 410 为clarify
r($testcase->batchReviewTest($caseIDList[0], $resultList[1])) && p('401') && e('105'); // 测试评审case 401 402 为pass
r($testcase->batchReviewTest($caseIDList[1], $resultList[1])) && p()      && e('0');   // 测试评审case 403 404 为pass
r($testcase->batchReviewTest($caseIDList[2], $resultList[1])) && p('405') && e('106'); // 测试评审case 405 406 为pass
r($testcase->batchReviewTest($caseIDList[3], $resultList[1])) && p()      && e('0');   // 测试评审case 407 408 为pass
r($testcase->batchReviewTest($caseIDList[4], $resultList[1])) && p('409') && e('107'); // 测试评审case 409 410 为pass
