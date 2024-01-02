#!/usr/bin/env php
<?php
/**

title=测试 docModel->getDynamic();
cid=1

- 获取每页5条，第1页动态数据 @32;31;30;29;28;
- 获取每页5条，第2页动态数据 @27;26;25;24;23;
- 获取每页10条，第1页动态数据 @32;31;30;29;28;27;26;25;24;23;
- 获取每页10条，第2页动态数据 @22;21;20;19;18;50;17;49;16;48;
- 获取每页20条，第1页动态数据 @32;31;30;29;28;27;26;25;24;23;22;21;20;19;18;50;17;49;16;48;
- 获取每页20条，第2页动态数据 @15;47;14;46;13;45;12;44;11;43;10;42;9;41;8;40;7;39;6;38;

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
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[0])) && p() && e('32;31;30;29;28;');                                              // 获取每页5条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[0], $pagerIds[1])) && p() && e('27;26;25;24;23;');                                              // 获取每页5条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[0])) && p() && e('32;31;30;29;28;27;26;25;24;23;');                               // 获取每页10条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[1], $pagerIds[1])) && p() && e('22;21;20;19;18;50;17;49;16;48;');                               // 获取每页10条，第2页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[0])) && p() && e('32;31;30;29;28;27;26;25;24;23;22;21;20;19;18;50;17;49;16;48;'); // 获取每页20条，第1页动态数据
r($docTester->getDynamicTest($recPerPages[2], $pagerIds[1])) && p() && e('15;47;14;46;13;45;12;44;11;43;10;42;9;41;8;40;7;39;6;38;');     // 获取每页20条，第2页动态数据
