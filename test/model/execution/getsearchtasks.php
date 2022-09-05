#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getSearchTasks();
cid=1
pid=1

测试通过sql语句获取3个任务 id 倒序 execution = '101' and deleted = '0' and parent >= 0 >> 910:name:子任务10;909:name:子任务9;908:name:子任务8;
测试通过sql语句获取3个任务 id 正序 execution = '101' and deleted = '0' and parent >= 0 >> 1:name:开发任务11;901:name:子任务1;902:name:子任务2;
测试通过sql语句获取5个任务 id 倒序 execution = '101' and deleted = '0' and parent >= 0 >> 910:name:子任务10;909:name:子任务9;908:name:子任务8;907:name:子任务7;906:name:子任务6;
测试通过sql语句获取5个任务 id 正序 execution = '101' and deleted = '0' and parent >= 0 >> 1:name:开发任务11;901:name:子任务1;902:name:子任务2;903:name:子任务3;904:name:子任务4;
测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0 >> 0
测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0 >> 0
测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0 >> 0
测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0 >> 0
测试通过sql语句获取3个任务 id 倒序 execution = '101' and story != '0' and parent >= 0 >> 1:name:开发任务11;
测试通过sql语句获取3个任务 id 正序 execution = '101' and story != '0' and parent >= 0 >> 1:name:开发任务11;
测试通过sql语句获取5个任务 id 倒序 execution = '101' and story != '0' and parent >= 0 >> 1:name:开发任务11;
测试通过sql语句获取5个任务 id 正序 execution = '101' and story != '0' and parent >= 0 >> 1:name:开发任务11;
测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0 >> 909:name:子任务9;901:name:子任务1;801:name:更多任务201;
测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0 >> 1:name:开发任务11;65:name:开发任务75;73:name:开发任务83;
测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0 >> 909:name:子任务9;901:name:子任务1;801:name:更多任务201;769:name:更多任务169;737:name:更多任务137;
测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0 >> 1:name:开发任务11;65:name:开发任务75;73:name:开发任务83;81:name:开发任务91;89:name:开发任务99;

*/

$condition = array();
$condition[] = "execution = '101' and deleted = '0' and parent >= 0";
$condition[] = "type = 'devel' and fromBug != '0' and parent >= 0";
$condition[] = "execution = '101' and story != '0' and parent >= 0";
$condition[] = "module like '%2%' and type = 'design' and parent >= 0";

$recPerPage = array('3', '5');
$orderBy = array('id_desc', 'id_asc');


$execution = new executionTest();
r($execution->getSearchTasksTest($condition[0], $recPerPage[0], $orderBy[0])) && p() && e('910:name:子任务10;909:name:子任务9;908:name:子任务8;');                                              // 测试通过sql语句获取3个任务 id 倒序 execution = '101' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[0], $orderBy[1])) && p() && e('1:name:开发任务11;901:name:子任务1;902:name:子任务2;');                                              // 测试通过sql语句获取3个任务 id 正序 execution = '101' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[1], $orderBy[0])) && p() && e('910:name:子任务10;909:name:子任务9;908:name:子任务8;907:name:子任务7;906:name:子任务6;');            // 测试通过sql语句获取5个任务 id 倒序 execution = '101' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[1], $orderBy[1])) && p() && e('1:name:开发任务11;901:name:子任务1;902:name:子任务2;903:name:子任务3;904:name:子任务4;');            // 测试通过sql语句获取5个任务 id 正序 execution = '101' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[0], $orderBy[0])) && p() && e('0');                                                                                                 // 测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[0], $orderBy[1])) && p() && e('0');                                                                                                 // 测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[1], $orderBy[0])) && p() && e('0');                                                                                                 // 测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[1], $orderBy[1])) && p() && e('0');                                                                                                 // 测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[0], $orderBy[0])) && p() && e('1:name:开发任务11;');                                                                                // 测试通过sql语句获取3个任务 id 倒序 execution = '101' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[0], $orderBy[1])) && p() && e('1:name:开发任务11;');                                                                                // 测试通过sql语句获取3个任务 id 正序 execution = '101' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[1], $orderBy[0])) && p() && e('1:name:开发任务11;');                                                                                // 测试通过sql语句获取5个任务 id 倒序 execution = '101' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[1], $orderBy[1])) && p() && e('1:name:开发任务11;');                                                                                // 测试通过sql语句获取5个任务 id 正序 execution = '101' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[0], $orderBy[0])) && p() && e('909:name:子任务9;901:name:子任务1;801:name:更多任务201;');                                           // 测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[0], $orderBy[1])) && p() && e('1:name:开发任务11;65:name:开发任务75;73:name:开发任务83;');                                          // 测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[1], $orderBy[0])) && p() && e('909:name:子任务9;901:name:子任务1;801:name:更多任务201;769:name:更多任务169;737:name:更多任务137;'); // 测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[1], $orderBy[1])) && p() && e('1:name:开发任务11;65:name:开发任务75;73:name:开发任务83;81:name:开发任务91;89:name:开发任务99;');    // 测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0
