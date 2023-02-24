#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

$programplan = zdTable('project');
$programplan->id->range('10-15');
$programplan->code->range('1-5')->prefix('code');
$programplan->deleted->range('0-1');
$programplan->gen(5);

/**

title=测试 programplanModel->checkCodeUnique();
cid=1
pid=1

检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code1 >> code1
检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code3 >> code3
检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code5 >> code5
检查'code1', 'code2', 'code3', 'code4', 'code5' 在除了id为'10', '11', '12', '13', '14'外的未删除数据编号是否唯一 >> 1
检查'code6', 'code7' 在未删除的数据内编号是否唯一 >> 1
检查'code2', 'code4' 在未删除的数据内编号是否唯一 >> 1

*/

$codes = array();
$codes[] = array('code1', 'code2', 'code3', 'code4', 'code5');
$codes[] = array('code6', 'code7');
$codes[] = array('code2', 'code4');
$exclude = array('10', '11', '12', '13', '14');

$programplan = new programplanTest();

r($programplan->checkCodeUniqueTest($codes[0]))                  && p('code1') && e('code1'); // 检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code1
r($programplan->checkCodeUniqueTest($codes[0]))                  && p('code3') && e('code3'); // 检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code3
r($programplan->checkCodeUniqueTest($codes[0]))                  && p('code5') && e('code5'); // 检查'code1', 'code2', 'code3', 'code4', 'code5' 在未删除的数据内编号是否唯一 code5
r($programplan->checkCodeUniqueTest($codes[0], $exclude))        && p()        && e('1');     // 检查'code1', 'code2', 'code3', 'code4', 'code5' 在除了id为'10', '11', '12', '13', '14'外的未删除数据编号是否唯一
r($programplan->checkCodeUniqueTest($codes[1]))                  && p()        && e('1');     // 检查'code6', 'code7' 在未删除的数据内编号是否唯一
r($programplan->checkCodeUniqueTest($codes[2]))                  && p()        && e('1');     // 检查'code2', 'code4' 在未删除的数据内编号是否唯一
