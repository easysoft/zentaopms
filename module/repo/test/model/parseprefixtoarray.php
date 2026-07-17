#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/repobranchtype/test/lib/model.class.php';
su('admin');

/**

title=测试 repobranchtypeModel::parsePrefixToArray();
timeout=0
cid=18150

- 测试单个前缀 @feature
- 测试多个前缀（逗号分隔） @feature,bugfix,hotfix
- 测试带空格的前缀 @feature,bugfix,hotfix
- 测试空字符串 @0
- 测试只有逗号的字符串 @0
- 测试带空值的前缀 @feature,hotfix

*/

$repo = new repobranchtypeTest();

r($repo->parsePrefixToArrayTest('feature'))                     && p() && e('feature');
r($repo->parsePrefixToArrayTest('feature,bugfix,hotfix'))       && p() && e('feature,bugfix,hotfix');
r($repo->parsePrefixToArrayTest(' feature , bugfix , hotfix ')) && p() && e('feature,bugfix,hotfix');
r($repo->parsePrefixToArrayTest(''))                            && p() && e('0');
r($repo->parsePrefixToArrayTest(',,,'))                         && p() && e('0');
r($repo->parsePrefixToArrayTest('feature,,hotfix'))             && p() && e('feature,hotfix');
