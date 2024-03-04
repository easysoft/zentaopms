#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDynamic();
cid=1

- 获取每页5条，第1页动态数据 @30;29;28;27;26;
- 获取每页5条，第2页动态数据 @25;24;23;22;21;
- 获取每页10条，第1页动态数据 @30;29;28;27;26;25;24;23;22;21;
- 获取每页10条，第2页动态数据 @20;50;19;49;18;48;17;47;16;46;
- 获取每页20条，第1页动态数据 @30;29;28;27;26;25;24;23;22;21;20;50;19;49;18;48;17;47;16;46;
- 获取每页20条，第2页动态数据 @15;45;14;44;13;43;12;42;11;41;10;40;9;39;8;38;7;37;6;36;

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('api')->gen(0);
zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('action')->config('action')->gen(50);
zdTable('user')->gen(5);

$recPerPages = array(5, 10, 20);
$pagerIds    = array(1, 2);

$docTester = new docTest();
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[0])) && p() && e('30;29;28;27;26;');                                              // 获取每页5条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[1])) && p() && e('25;24;23;22;21;');                                              // 获取每页5条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[0])) && p() && e('30;29;28;27;26;25;24;23;22;21;');                               // 获取每页10条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[1])) && p() && e('20;50;19;49;18;48;17;47;16;46;');                               // 获取每页10条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[0])) && p() && e('30;29;28;27;26;25;24;23;22;21;20;50;19;49;18;48;17;47;16;46;'); // 获取每页20条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[1])) && p() && e('15;45;14;44;13;43;12;42;11;41;10;40;9;39;8;38;7;37;6;36;');     // 获取每页20条，第2页动态数据
