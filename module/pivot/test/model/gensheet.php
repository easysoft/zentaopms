#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->genSheet();
cid=1
pid=1

查看id为1002的透视表生成的分组信息是否正确。 >> 一级项目集,产品线,产品
查看id为1002的透视表是否显示列总计。 >> noShow
查看id为1002的透视表生成的列数量是否正确 >> 10
查看id为1002的透视表生成的列名称是否正确。 >> 一级项目集,Bug修复率

查看id为1000的透视表生成的分组信息是否正确。 >> program1,name
当lang存在时，label选用lang中对应的值。 >> program1,一级项目集;rate,工期偏差率
查看id为1000的透视表生成的列数据是否正确。 >> 项目11,-1.000;项目20,-1.000
查看id为1000的透视表生成的合并单元格数据是否正确。 >> 10,1;1

查看id为1001的透视表生成的分组信息是否正确。 >> 一级项目集,项目名称
查看id为1001的透视表生成的标签是否正确。 >> 一级项目集,一级项目集;单位时间交付需求规模数;单位时间交付需求规模数的求和
查看id为1001的透视表生成的列数据是否正确。 >> 项目11,3;项目20,12
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('user')->gen(20);
zenData('bug')->loadYaml('bug')->gen(20);
zenData('product')->loadYaml('product')->gen(10);
zenData('module')->gen(10);
zenData('case')->gen(10);
zenData('project')->loadYaml('project_gensheet')->gen(20);
zenData('product')->gen(10);
zenData('task')->gen(10);

global $tester;
$pivotTest = new pivotTest();

$pivotIDList = array(1002, 1000, 1001, 1025);

$pivot = $pivotTest->getByID($pivotIDList[0]);

list($sql, $filterFormat) = $pivotTest->getFilterFormat($pivot->sql, $pivot->filters);

$fields = json_decode(json_encode($pivot->fieldSettings), true);
$langs  = json_decode($pivot->langs, true) ?? array();

list($data, $configs) = $pivotTest->genSheet($fields, $pivot->settings, $sql, $filterFormat, $langs);

r($data->groups) && p('0,1,2') && e('一级项目集,产品线,产品');              //查看id为1002的透视表生成的分组信息是否正确。
r($data->showAllTotal) && p('') && e('0');                                 //查看id为1002的透视表是否显示列总计。
r(count($data->array)) && p('') && e('10');                                //查看id为1002的透视表生成的列数量是否正确
r(array_keys($data->array[0])) && p('0,13') && e('一级项目集,Bug修复率10'); //查看id为1002的透视表生成的列名称是否正确。

$pivot = $pivotTest->getByID($pivotIDList[1]);

list($sql, $filterFormat) = $pivotTest->getFilterFormat($pivot->sql, $pivot->filters);
$fields = json_decode(json_encode($pivot->fieldSettings), true);
$langs  = json_decode($pivot->langs, true) ?? array();

list($data, $configs) = $pivotTest->genSheet($fields, $pivot->settings, $sql, $filterFormat, $langs);
r($data->groups) && p('0,1') && e('program1,name');                                                  //查看id为1000的透视表生成的分组信息是否正确。
r($data->cols[0]) && p('0:name,label;9:name,label') && e('program1,一级项目集;rate,工期偏差率的求和'); //当lang存在时，label选用lang中对应的值。
r($data->array) && p('0:name,rate7;9:name,rate7') && e('项目11,-1;项目20,-1');                        //查看id为1000的透视表生成的列数据是否正确。
r($configs) && p('0:0,1;1:1') && e('10,1;1');                                                        //查看id为1000的透视表生成的合并单元格数据是否正确。

$pivot = $pivotTest->getByID($pivotIDList[2]);

list($sql, $filterFormat) = $pivotTest->getFilterFormat($pivot->sql, $pivot->filters);
$fields = json_decode(json_encode($pivot->fieldSettings), true);
$langs  = json_decode($pivot->langs, true) ?? array();

list($data, $configs) = $pivotTest->genSheet($fields, $pivot->settings, $sql, false, $langs);
r($data->groups) && p('0,1') && e('一级项目集,项目名称');                                                                           //查看id为1001的透视表生成的分组信息是否正确。
r($data->cols[0]) && p('0:name,label;8:name,label') && e('一级项目集,一级项目集;单位时间交付需求规模数,单位时间交付需求规模数的求和'); //查看id为1001的透视表生成的标签是否正确。
r($data->array) && p('0:项目名称,消耗工时1;9:项目名称,消耗工时1') && e('项目11,3;项目20,12');                                        //查看id为1001的透视表生成的列数据是否正确。
r($configs) && p('0:0,1') && e('10,1');                                                                                           //查看id为1001的透视表生成的合并单元格数据是否正确。
