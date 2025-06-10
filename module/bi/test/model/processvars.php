#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->processVars();
timeout=0
cid=1

*/

$bi = new biTest();

$sqls = array();

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT DISTINCT id,estimate hour FROM zt_story t1 WHERE 1 = ( SELECT COUNT(DISTINCT id,estimate) FROM zt_story t2 WHERE t2.estimate> t1.estimate)
EOT;

$sqls[] = <<<EOT
SELECT t1.name, t1.PM, t1.begin, t1.realBegan, t3.realname FROM zt_project AS t1 LEFT JOIN zt_team AS t2 ON t1.id = t2.root AND t2.type = 'execution' LEFT JOIN zt_user AS t3 ON t2.account = t3.account WHERE t1.name LIKE '%zentaopms%' AND t1.realBegan > '2022-01-01' order by t1.id
EOT;
