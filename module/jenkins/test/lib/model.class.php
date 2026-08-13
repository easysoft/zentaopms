<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class jenkinsModelTest extends baseTest
{
    protected $moduleName = 'jenkins';
    protected $className  = 'model';

    /**
     * Test getDepthJobs method with direct mock data.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsDirectTest(): string
    {
        $mockJobs = array(
            (object)array(
                'name' => 'Simple Job',
                'url' => 'https://jenkins.example.com/job/simpleJob/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            ),
            (object)array(
                'name' => 'Param Job',
                'url' => 'https://jenkins.example.com/job/paramJob/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            ),
            (object)array(
                'name' => 'Folder 1',
                'url' => 'https://jenkins.example.com/job/folder1/',
                '_class' => 'com.cloudbees.hudson.plugins.folder.Folder',
                'buildable' => false,
                'jobs' => array(
                    (object)array(
                        'name' => 'Sub Job 1',
                        'url' => 'https://jenkins.example.com/job/folder1/job/subJob1/',
                        '_class' => 'hudson.model.FreeStyleProject',
                        'buildable' => true
                    ),
                    (object)array(
                        'name' => 'Sub Job 2',
                        'url' => 'https://jenkins.example.com/job/folder1/job/subJob2/',
                        '_class' => 'hudson.model.FreeStyleProject',
                        'buildable' => true
                    )
                )
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($mockJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with empty jobs array.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsEmptyTest(): string
    {
        $emptyJobs = array();
        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($emptyJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with maximum depth exceeded.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsMaxDepthTest(): string
    {
        $mockJobs = array(
            (object)array(
                'name' => 'Test Job',
                'url' => 'https://jenkins.example.com/job/testJob/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($mockJobs, $userPWD, 5); // depth > 4
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with invalid job data.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsInvalidDataTest(): string
    {
        $invalidJobs = array(
            (object)array(
                'name' => 'Invalid Job',
                // Missing url field
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            ),
            (object)array(
                'name' => 'Another Job',
                'url' => '', // Empty url
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($invalidJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with different job types.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsJobTypeTest(): string
    {
        $mockJobs = array(
            (object)array(
                'name' => 'Buildable Job',
                'url' => 'https://jenkins.example.com/job/buildableJob/',
                '_class' => 'com.cloudbees.hudson.plugins.folder.Folder',
                'buildable' => true // Force as job even though it's a folder
            ),
            (object)array(
                'name' => 'Regular Job',
                'url' => 'https://jenkins.example.com/job/regularJob/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            ),
            (object)array(
                'name' => 'Multibranch Pipeline',
                'url' => 'https://jenkins.example.com/job/multibranchPipeline/',
                '_class' => 'org.jenkinsci.plugins.workflow.multibranch.WorkflowMultiBranchProject',
                'buildable' => false
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($mockJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with URL encoding.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsUrlEncodingTest(): string
    {
        $mockJobs = array(
            (object)array(
                'name' => 'Hello World',
                'url' => 'https://jenkins.example.com/job/hello%20world/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            ),
            (object)array(
                'name' => 'Test Job',
                'url' => 'https://jenkins.example.com/job/test-job/',
                '_class' => 'hudson.model.FreeStyleProject',
                'buildable' => true
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($mockJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * Test getDepthJobs method with folder structure.
     *
     * @access public
     * @return string
     */
    public function getDepthJobsFolderTest(): string
    {
        // Test with simple folder that contains jobs but depth = 1 to avoid HTTP calls
        $mockJobs = array(
            (object)array(
                'name' => 'Folder',
                'url' => 'https://jenkins.example.com/job/folder/',
                '_class' => 'com.cloudbees.hudson.plugins.folder.Folder',
                'buildable' => false,
                'jobs' => array() // Empty jobs array
            )
        );

        $userPWD = 'test:test';
        $result = $this->instance->getDepthJobs($mockJobs, $userPWD, 1);
        if(dao::isError()) return dao::getError();

        return json_encode($result);
    }

    /**
     * 测试根据深度获取流水线。
     * Test get jobs by depth.
     *
     * @param  int    $depth
     * @access public
     * @return string
     */
    public function getDepthJobsTest(int $depth = 1): string
    {
        $userPWD       = "jenkins:11eb8b38c99143c7c6d872291e291abff4";
        $jenkinsServer = 'https://jenkinsdev.qc.oop.cc/';
        $response      = common::http($jenkinsServer . '/api/json/items/list' . ($depth ? "?depth=1" : ''), '', array(CURLOPT_USERPWD => $userPWD));
        $response      = json_decode($response);

        $tasks  = $this->jenkins->getDepthJobs($response->jobs, $userPWD, $depth);
        $return = '';
        foreach($tasks as $folder => $subTasks)
        {
            if(is_array($subTasks))
            {
                $return .= $this->getJobsByTest($subTasks);
            }
            else
            {
                $return .= "{$folder}:{$subTasks},";
            }
        }
        return trim($return, ',');
    }

    /**
     * 测试获取流水线下的目录名称和文件名称。
     * Test get the directory name and file name under the pipeline.
     *
     * @param  array  $tasks
     * @access public
     * @return string
     */
    protected function getJobsByTest(array $tasks): string
    {
        $return = '';
        foreach($tasks as $folder => $subTasks)
        {
            if(is_array($subTasks))
            {
                if(empty($subTasks)) $return .= "{$folder}:0,";
                $return .= $this->getJobsByTest($subTasks);
            }
            else
            {
                $return .= "{$folder}:{$subTasks},";
            }
        }
        return $return;
    }

    /**
     * 测试获取jenkins api 密码串。
     * Test get jenkins api userpwd string.
     *
     * @param  int    $jenkinsID
     * @access public
     * @return array
     */
    public function getApiUserPWDTest(int $jenkinsID)
    {
        global $tester;
        $jenkins = $tester->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($jenkinsID)->fetch();
        return $this->instance->getApiUserPWD($jenkins);
    }
}
