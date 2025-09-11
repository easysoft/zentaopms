#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTmpRelation();
timeout=0
cid=0

- 执行convertTest模块的createTmpRelationTest方法，参数是'juser', 'admin', 'zuser', 'admin001', '' 
 - 属性AType @juser
 - 属性BType @zuser
 - 属性AID @admin
 - 属性BID @admin001
 - 属性extra @
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
 - 属性extra @
- 执行convertTest模块的createTmpRelationTest方法，参数是'', '', '', '', '' 
 - 属性AType @
 - 属性BType @
 - 属性AID @
 - 属性BID @
 - 属性extra @
- 执行convertTest模块的createTmpRelationTest方法，参数是'jbug', 'BUG-001', 'zbug', 'ZEN-001', 'migration' 
 - 属性AType @jbug
 - 属性BType @zbug
 - 属性AID @BUG-001
 - 属性BID @ZEN-001
 - 属性extra @migration

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 创建临时表
global $tester;
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

try {
    $tester->dbh->exec($sql);
    // 清空表数据确保测试环境干净
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

// 3. 定义常量（如果没有定义的话）
if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

// 4. 用户登录（选择合适角色）
su('admin');

// 5. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 6. 执行测试步骤
r($convertTest->createTmpRelationTest('juser', 'admin', 'zuser', 'admin001', '')) && p('AType,BType,AID,BID,extra') && e('juser,zuser,admin,admin001,');
r($convertTest->createTmpRelationTest('jproject', '1001', 'zproject', '2001', 'issue')) && p('AType,BType,AID,BID,extra') && e('jproject,zproject,1001,2001,issue');
r($convertTest->createTmpRelationTest('jtask', 123, 'ztask', 456, '')) && p('AType,BType,AID,BID,extra') && e('jtask,ztask,123,456,');
r($convertTest->createTmpRelationTest('', '', '', '', '')) && p('AType,BType,AID,BID,extra') && e(',,,,');
r($convertTest->createTmpRelationTest('jbug', 'BUG-001', 'zbug', 'ZEN-001', 'migration')) && p('AType,BType,AID,BID,extra') && e('jbug,zbug,BUG-001,ZEN-001,migration');