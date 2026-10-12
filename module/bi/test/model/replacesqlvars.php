#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::replaceSqlVars();
timeout=0
cid=0

- 普通变量替换为字面量 @SELECT * FROM t WHERE a = 'x'
- 字符串字面量内的同名文本不被替换 @SELECT * FROM t WHERE a = 'x$ax'
- 变量名互为前缀时按最长匹配替换 @SELECT * FROM t WHERE 'AB' = 'A'
- 反引号标识符内的同名文本不被替换 @SELECT `$a` FROM t
- 行内注释内的变量不被替换 @SELECT * FROM t WHERE a = 'x' -- $a
- 块注释内的变量不被替换 @SELECT * FROM t WHERE a = 'x' /* $a
- 变量映射为空时保持SQL不变 @SELECT * FROM t WHERE a = $a

*/

$testObj = new biModelTest();

r($testObj->replaceSqlVarsTest('SELECT * FROM t WHERE a = $a', array('$a' => "'x'")) === "SELECT * FROM t WHERE a = 'x'")        && p() && e(1); // 普通变量替换为字面量
r($testObj->replaceSqlVarsTest("SELECT * FROM t WHERE a = 'x\$ax'", array('$a' => "'y'")) === "SELECT * FROM t WHERE a = 'x\$ax'") && p() && e(1); // 字符串字面量内的同名文本不被替换
r($testObj->replaceSqlVarsTest('SELECT * FROM t WHERE $ab = $a', array('$a' => "'A'", '$ab' => "'AB'")) === "SELECT * FROM t WHERE 'AB' = 'A'") && p() && e(1); // 变量名互为前缀时按最长匹配替换
r($testObj->replaceSqlVarsTest('SELECT `$a` FROM t', array('$a' => "'x'")) === 'SELECT `$a` FROM t')                             && p() && e(1); // 反引号标识符内的同名文本不被替换
r($testObj->replaceSqlVarsTest('SELECT * FROM t WHERE a = $a -- $a', array('$a' => "'x'")) === "SELECT * FROM t WHERE a = 'x' -- \$a") && p() && e(1); // 行内注释内的变量不被替换
r($testObj->replaceSqlVarsTest('SELECT * FROM t WHERE a = $a /* $a', array('$a' => "'x'")) === "SELECT * FROM t WHERE a = 'x' /* \$a") && p() && e(1); // 块注释内的变量不被替换
r($testObj->replaceSqlVarsTest('SELECT * FROM t WHERE a = $a', array()) === 'SELECT * FROM t WHERE a = $a')                      && p() && e(1); // 变量映射为空时保持SQL不变
