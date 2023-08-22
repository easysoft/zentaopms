#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchReview();
timeout=0
cid=1

*/

$testcase   = new testcaseTest();
$caseIDList = array(401, 402, 403, 404);

r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('401:status') && e('wait');        // 评审结果为 继续完善，状态是 wait        的用例评审后状态为 wait。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('402:status') && e('normal');      // 评审结果为 继续完善，状态是 normal      的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('403:status') && e('blocked');     // 评审结果为 继续完善，状态是 blocked     的用例评审后状态为 blocked。
r($testcase->batchReviewTest($caseIDList, 'clarify')) && p('404:status') && e('investigate'); // 评审结果为 继续完善，状态是 investigate 的用例评审后状态为 investigate。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('401:status') && e('normal');      // 评审结果为 确认通过，状态是 wait        的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('402:status') && e('normal');      // 评审结果为 确认通过，状态是 normal      的用例评审后状态为 normal。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('403:status') && e('blocked');     // 评审结果为 确认通过，状态是 blocked     的用例评审后状态为 blocked。
r($testcase->batchReviewTest($caseIDList, 'pass'))    && p('404:status') && e('investigate'); // 评审结果为 确认通过，状态是 investigate 的用例评审后状态为 investigate。
