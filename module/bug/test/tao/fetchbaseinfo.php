#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('bug')->config('fetchbaseinfo')->gen(5);

/**

title=bugTao->fetchBaseInfo();
timeout=0
cid=1


*/

global $tester;
$bug  = $tester->loadModel('bug');
$bug0 = $bug->fetchBaseInfo(0);
$bug1 = $bug->fetchBaseInfo(1);
$bug2 = $bug->fetchBaseInfo(2);
$bug3 = $bug->fetchBaseInfo(3);
$bug4 = $bug->fetchBaseInfo(4);
$bug5 = $bug->fetchBaseInfo(5);

r($bug0) && p() && e(0);                                 // 检查 bug 不存在时输出是否正确。

r($bug1) && p('title')      && e('第1个bug');            // 检查第 1 个 bug 标题是否正确。
r($bug1) && p('status')     && e('active');              // 检查第 1 个 bug 状态是否正确。
r($bug1) && p('openedBy')   && e('user1');               // 检查第 1 个 bug 创建人否正确。
r($bug1) && p('openedDate') && e('2023-01-01 00:00:00'); // 检查第 1 个 bug 创建时间是否正确。
r($bug1) && p('deleted')    && e('0');                   // 检查第 1 个 bug 标记删除是否正确。

r($bug2) && p('title')      && e('第2个bug');            // 检查第 2 个 bug 标题是否正确。
r($bug2) && p('status')     && e('resolved');            // 检查第 2 个 bug 状态是否正确。
r($bug2) && p('openedBy')   && e('user2');               // 检查第 2 个 bug 创建人否正确。
r($bug2) && p('openedDate') && e('2023-01-02 00:00:00'); // 检查第 2 个 bug 创建时间是否正确。
r($bug2) && p('deleted')    && e('1');                   // 检查第 2 个 bug 标记删除是否正确。

r($bug3) && p('title')      && e('第3个bug');            // 检查第 3 个 bug 标题是否正确。
r($bug3) && p('status')     && e('closed');              // 检查第 3 个 bug 状态是否正确。
r($bug3) && p('openedBy')   && e('user3');               // 检查第 3 个 bug 创建人否正确。
r($bug3) && p('openedDate') && e('2023-01-03 00:00:00'); // 检查第 3 个 bug 创建时间是否正确。
r($bug3) && p('deleted')    && e('0');                   // 检查第 3 个 bug 标记删除是否正确。

r($bug4) && p('title')      && e('第4个bug');            // 检查第 4 个 bug 标题是否正确。
r($bug4) && p('status')     && e('resolved');            // 检查第 4 个 bug 状态是否正确。
r($bug4) && p('openedBy')   && e('user4');               // 检查第 4 个 bug 创建人否正确。
r($bug4) && p('openedDate') && e('2023-01-04 00:00:00'); // 检查第 4 个 bug 创建时间是否正确。
r($bug4) && p('deleted')    && e('1');                   // 检查第 4 个 bug 标记删除是否正确。

r($bug5) && p('title')      && e('第5个bug');            // 检查第 5 个 bug 标题是否正确。
r($bug5) && p('status')     && e('active');              // 检查第 5 个 bug 状态是否正确。
r($bug5) && p('openedBy')   && e('user5');               // 检查第 5 个 bug 创建人否正确。
r($bug5) && p('openedDate') && e('2023-01-05 00:00:00'); // 检查第 5 个 bug 创建时间是否正确。
r($bug5) && p('deleted')    && e('0');                   // 检查第 5 个 bug 标记删除是否正确。
