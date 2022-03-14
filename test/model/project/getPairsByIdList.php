#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::getPairsByIdList;
cid=1
pid=1

查找ID为0、11、12、13的项目 >> 3
查找所有项目 >> 90

*/

$project = new Project('admin');

$findProject = array(0,11,12,13);
$findAllPro = array();

r($project->getByIdListFind($findProject)) && p() && e('3');  //查找ID为0、11、12、13的项目
r($project->getByIdListFind($findAllPro))  && p() && e('90'); //查找所有项目