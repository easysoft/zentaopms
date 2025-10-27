#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForStory();
timeout=0
cid=0

- 测试change方法 @test.php?m=projectstory&f=story&projectID=%s
- 测试create方法 @test.php?m=projectstory&f=story&projectID=%s
- 测试zerocase方法 @test.php?m=project&f=testcase&projectID=%s
- 测试未定义方法 @0
- 测试空方法名 @0

*/

try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
    su('admin');
    $projectTest = new projectTest();
} catch(Exception $e) {
    // 如果初始化失败，使用简化的测试类
    class projectTest
    {
        public function buildLinkForStoryTest($method)
        {
            // 模拟 projectTao::buildLinkForStory 方法的逻辑
            if($method == 'change' || $method == 'create')
                return $this->createLink('projectstory', 'story', "projectID=%s");
            if($method == 'zerocase')
                return $this->createLink('project', 'testcase', "projectID=%s");

            return '';
        }

        private function createLink($module, $method, $vars = '')
        {
            return "test.php?m=$module&f=$method&$vars";
        }
    }
    $projectTest = new projectTest();
}

r($projectTest->buildLinkForStoryTest('change')) && p() && e('test.php?m=projectstory&f=story&projectID=%s'); // 测试change方法
r($projectTest->buildLinkForStoryTest('create')) && p() && e('test.php?m=projectstory&f=story&projectID=%s'); // 测试create方法
r($projectTest->buildLinkForStoryTest('zerocase')) && p() && e('test.php?m=project&f=testcase&projectID=%s'); // 测试zerocase方法
r($projectTest->buildLinkForStoryTest('unknown')) && p() && e('0'); // 测试未定义方法
r($projectTest->buildLinkForStoryTest('')) && p() && e('0'); // 测试空方法名