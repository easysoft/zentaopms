#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(10);

/**

title=测试 testcaseModel->batchReview();
timeout=0
cid=18964

*/

$testcase   = new testcaseModelTest();
$caseIDList = array(1, 2, 3, 4);

r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('1:status') && e('wait');        // 评审结果为 继续完善，状态是 wait        的用例评审后状态为 wait。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('2:status') && e('normal');      // 评审结果为 继续完善，状态是 normal      的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('3:status') && e('blocked');     // 评审结果为 继续完善，状态是 blocked     的用例评审后状态为 blocked。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('4:status') && e('investigate'); // 评审结果为 继续完善，状态是 investigate 的用例评审后状态为 investigate。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('1:status') && e('normal');      // 评审结果为 确认通过，状态是 wait        的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('2:status') && e('normal');      // 评审结果为 确认通过，状态是 normal      的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('3:status') && e('blocked');     // 评审结果为 确认通过，状态是 blocked     的用例评审后状态为 blocked。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('4:status') && e('investigate'); // 评审结果为 确认通过，状态是 investigate 的用例评审后状态为 investigate。
