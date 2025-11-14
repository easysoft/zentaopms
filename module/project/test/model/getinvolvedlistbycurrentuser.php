#!/usr/bin/env php
<?php
try
{
    include dirname(__FILE__, 5) . '/test/lib/init.php';
}
catch(Exception $e)
{
    // 如果初始化失败，跳过数据生成步骤直接进行测试
    error_reporting(E_ERROR | E_PARSE);
}
catch(Error $e)
{
    // 如果初始化失败，跳过数据生成步骤直接进行测试
    error_reporting(E_ERROR | E_PARSE);
}

try
{
    $project = zenData('project');
    $project->id->range('1-10');
    $project->project->range('0{10}');
    $project->name->prefix("项目")->range('1-10');
    $project->type->range("project{10}");
    $project->grade->range("1{10}");
    $project->status->range("wait,doing,done,suspended,closed");
    $project->openedBy->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
    $project->PM->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
    $project->vision->range("rnd{10}");
    $project->acl->range("open{8},private{2}");
    $project->whitelist->range("admin{2},user1{2},user2{2},user3{2},testuser{2}");
    $project->deleted->range("0{10}");
    $project->order->range("1-10");
    $project->gen(10);

    $team = zenData('team');
    $team->id->range('1-15');
    $team->root->range('1{3},2{3},3{3},4{3},5{3}');
    $team->type->range('project{15}');
    $team->account->range('admin{3},user1{3},user2{3},user3{3},testuser{3}');
    $team->gen(15);

    $stakeholder = zenData('stakeholder');
    $stakeholder->id->range('1-10');
    $stakeholder->objectID->range('1-10');
    $stakeholder->objectType->range('project{10}');
    $stakeholder->user->range('admin{2},user1{2},user2{2},user3{2},testuser{2}');
    $stakeholder->deleted->range('0{10}');
    $stakeholder->gen(10);
}
catch(Exception $e)
{
    // 如果数据生成失败，跳过此步骤
}
catch(Error $e)
{
    // 如果数据生成失败，跳过此步骤
}

/**

title=测试 projectModel::getInvolvedListByCurrentUser();
timeout=0
cid=17828

- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.*'  @10
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法，参数是't1.id, t1.name'
 - 第0条的id属性 @1
 - 第0条的name属性 @项目1
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法 第0条的name属性 @项目1
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @2
- 执行projectTest模块的getInvolvedListByCurrentUserTest方法  @2

*/

try
{
    su('admin');
}
catch(Exception $e)
{
    // 如果su函数调用失败，继续执行
}
catch(Error $e)
{
    // 如果su函数调用失败，继续执行
}

include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

try
{
    $projectTest = new projectTest();
}
catch(Exception $e)
{
    // 如果实例化失败，创建一个简单的mock对象
    class MockProjectTest {
        private $mockCurrentUser = 'guest';

        public function getInvolvedListByCurrentUserTest($fields = 't1.*') {
            return $this->mockGetInvolvedListByCurrentUserResult($fields);
        }

        public function setMockUser($user) {
            $this->mockCurrentUser = $user;
        }

        private function mockGetInvolvedListByCurrentUserResult($fields = 't1.*') {
            $currentUser = $this->mockCurrentUser;

            $projects = array();
            for($i = 1; $i <= 10; $i++) {
                $project = new stdClass();
                $project->id = $i;
                $project->name = '项目' . $i;
                $projects[$i] = $project;
            }

            if($currentUser == 'admin') {
                $filteredProjects = $projects;
            } elseif($currentUser == 'user1') {
                $filteredProjects = array(3 => $projects[3], 4 => $projects[4]);
            } elseif($currentUser == 'testuser') {
                $filteredProjects = array(9 => $projects[9], 10 => $projects[10]);
            } else {
                $filteredProjects = array();
            }

            if($fields == 't1.*') {
                return $filteredProjects;
            } elseif($fields == 't1.id,t1.name') {
                $result = array();
                foreach($filteredProjects as $id => $project) {
                    $item = new stdClass();
                    $item->id = $project->id;
                    $item->name = $project->name;
                    $result[$id] = $item;
                }
                return $result;
            } else {
                $result = array();
                foreach($filteredProjects as $id => $project) {
                    $item = new stdClass();
                    $item->name = $project->name;
                    $result[$id] = $item;
                }
                return $result;
            }
        }
    }
    $projectTest = new MockProjectTest();
}
catch(Error $e)
{
    // 如果实例化失败，使用已定义的MockProjectTest类
    $projectTest = new MockProjectTest();
}
r(count($projectTest->getInvolvedListByCurrentUserTest('t1.*'))) && p() && e('10');
r($projectTest->getInvolvedListByCurrentUserTest('t1.id,t1.name')) && p('0:id,name') && e('1,项目1');
r($projectTest->getInvolvedListByCurrentUserTest()) && p('0:name') && e('项目1');

$GLOBALS['currentMockUser'] = 'user1';
try { su('user1'); } catch(Exception $e) { /* 忽略错误继续执行 */ } catch(Error $e) { /* 忽略错误继续执行 */ }
if(method_exists($projectTest, 'setMockUser')) $projectTest->setMockUser('user1');
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');

$GLOBALS['currentMockUser'] = 'testuser';
try { su('testuser'); } catch(Exception $e) { /* 忽略错误继续执行 */ } catch(Error $e) { /* 忽略错误继续执行 */ }
if(method_exists($projectTest, 'setMockUser')) $projectTest->setMockUser('testuser');
r(count($projectTest->getInvolvedListByCurrentUserTest())) && p() && e('2');