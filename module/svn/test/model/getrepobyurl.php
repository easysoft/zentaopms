#!/usr/bin/env php
<?php

/**

title=svnModel->getRepoByURL();
timeout=0
cid=1

- 不匹配的URL @0
- 部分匹配的URL @0
- 完全一致的URL
 - 属性id @1
 - 属性name @svn-1
- 有大写的URL
 - 属性id @1
 - 属性name @svn-1
- 有多余的路径的URL
 - 属性id @1
 - 属性name @svn-1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');

r($svn->getRepoByURL('http://svn.abc.com')) && p() && e('0'); // 不匹配的URL

r($svn->getRepoByURL('https://svn.qc.oop.cc')) && p() && e('0'); // 部分匹配的URL

r($svn->getRepoByURL('https://svn.qc.oop.cc/svn/unittest')) && p('id,name') && e('1,svn-1'); // 完全一致的URL

r($svn->getRepoByURL('https://SVN.QC.OOP.CC/svn/unittest/')) && p('id,name') && e('1,svn-1'); // 有大写的URL

r($svn->getRepoByURL('https://svn.qc.oop.cc/svn/unittest/master')) && p('id,name') && e('1,svn-1'); // 有多余的路径的URL