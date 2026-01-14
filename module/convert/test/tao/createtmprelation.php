#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTmpRelation();
timeout=0
cid=15849

- 执行convertTest模块的createTmpRelationTest方法，参数是'juser', 'admin', 'zuser', 'admin001', '' 
 - 属性AType @juser
 - 属性BType @zuser
 - 属性AID @admin
 - 属性BID @admin001
 - 属性extra @~~
- 执行convertTest模块的createTmpRelationTest方法，参数是'jproject', '1001', 'zproject', '2001', 'issue' 
 - 属性AType @jproject
 - 属性BType @zproject
 - 属性AID @1001
 - 属性BID @2001
 - 属性extra @issue
- 执行convertTest模块的createTmpRelationTest方法，参数是'jtask', 123, 'ztask', 456, '' 
 - 属性AType @jtask
 - 属性BType @ztask
 - 属性AID @123
 - 属性BID @456
 - 属性extra @~~
- 执行convertTest模块的createTmpRelationTest方法，参数是'', '', '', '', '' 
 - 属性AType @~~
 - 属性BType @~~
 - 属性AID @~~
 - 属性BID @~~
 - 属性extra @~~
- 执行convertTest模块的createTmpRelationTest方法，参数是'jbug', 'BUG-001', 'zbug', 'ZEN-001', 'migration' 
 - 属性AType @jbug
 - 属性BType @zbug
 - 属性AID @BUG-001
 - 属性BID @ZEN-001
 - 属性extra @migration

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 定义常量
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', 'jiratmprelation');

global $tester;

// 创建临时表
$sql = "CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL DEFAULT '',
  `AID` char(100) NOT NULL DEFAULT '',
  `BType` char(30) NOT NULL DEFAULT '',
  `BID` char(100) NOT NULL DEFAULT '',
  `extra` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

try {
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 忽略表创建错误
}

zenData('user')->gen(0);
zenData('product')->gen(0);

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->createTmpRelationTest('juser', 'admin', 'zuser', 'admin001', '')) && p('AType,BType,AID,BID,extra') && e('juser,zuser,admin,admin001,~~');
r($convertTest->createTmpRelationTest('jproject', '1001', 'zproject', '2001', 'issue')) && p('AType,BType,AID,BID,extra') && e('jproject,zproject,1001,2001,issue');
r($convertTest->createTmpRelationTest('jtask', 123, 'ztask', 456, '')) && p('AType,BType,AID,BID,extra') && e('jtask,ztask,123,456,~~');
r($convertTest->createTmpRelationTest('', '', '', '', '')) && p('AType,BType,AID,BID,extra') && e('~~,~~,~~,~~,~~');
r($convertTest->createTmpRelationTest('jbug', 'BUG-001', 'zbug', 'ZEN-001', 'migration')) && p('AType,BType,AID,BID,extra') && e('jbug,zbug,BUG-001,ZEN-001,migration');