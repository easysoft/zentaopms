#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 searchModel->buildIndexQuery();
timeout=0
cid=1

- 构建未删除项目集的sql语句 @SELECT t1.* FROM `zt_project` AS t1  WHERE 1=1  AND  `type`  = 'program' AND  t1.deleted  = '0'
- 构建需求的sql语句 @SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'story' AND  t1.version=t2.version

- 构建需求的sql语句 @SELECT DISTINCT t1.*, t2.content, t2.digest FROM `zt_doc` AS t1  LEFT JOIN `zt_doccontent` AS t2  ON t1.id=t2.doc  WHERE t1.deleted  = '0' AND  t1.version=t2.version

- 构建查询所有项目的sql语句 @SELECT t1.* FROM `zt_project` AS t1  WHERE 1=1  AND  `type`  = 'project'

*/

global $tester;
$search = $tester->loadModel('search');

r($search->buildIndexQuery('program')->get())        && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE 1=1  AND  `type`  = 'program' AND  t1.deleted  = '0'");                                                                                             //构建未删除项目集的sql语句
r($search->buildIndexQuery('story')->get())          && p() && e("SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'story' AND  t1.version=t2.version");  //构建需求的sql语句
r($search->buildIndexQuery('doc')->get())            && p() && e("SELECT DISTINCT t1.*, t2.content, t2.digest FROM `zt_doc` AS t1  LEFT JOIN `zt_doccontent` AS t2  ON t1.id=t2.doc  WHERE t1.deleted  = '0' AND  t1.version=t2.version");                         //构建需求的sql语句
r($search->buildIndexQuery('project', false)->get()) && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE 1=1  AND  `type`  = 'project'");                                                                                                                    //构建查询所有项目的sql语句