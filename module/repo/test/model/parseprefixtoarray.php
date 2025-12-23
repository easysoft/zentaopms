#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->parsePrefixToArray();
timeout=0
cid=18150

- 测试单个前缀 @feature
- 测试多个前缀（逗号分隔） @feature,bugfix,hotfix
- 测试带空格的前缀 @feature, bugfix , hotfix
- 测试空字符串 @0
- 测试只有逗号的字符串 @0
- 测试带空值的前缀 @feature,hotfix

*/

$repo = new repoTest();

r($repo->parsePrefixToArrayTest('feature'))                    && p() && e('feature');               // 测试单个前缀
r($repo->parsePrefixToArrayTest('feature,bugfix,hotfix'))      && p() && e('feature,bugfix,hotfix'); // 测试多个前缀（逗号分隔）
r($repo->parsePrefixToArrayTest(' feature , bugfix , hotfix ')) && p() && e('feature,bugfix,hotfix'); // 测试带空格的前缀
r($repo->parsePrefixToArrayTest(''))                           && p() && e('0');                     // 测试空字符串
r($repo->parsePrefixToArrayTest(',,,'))                        && p() && e('0');                     // 测试只有逗号的字符串
r($repo->parsePrefixToArrayTest('feature,,hotfix'))            && p() && e('feature,hotfix');        // 测试带空值的前缀
