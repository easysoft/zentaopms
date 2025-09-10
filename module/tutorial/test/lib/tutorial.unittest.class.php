<?php
declare(strict_types=1);
class tutorialTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('tutorial');
    }

    /**
     * 测试检查新手模式配置。
     * Test check novice mode config.
     *
     * @param  int       $modifyPassword
     * @access public
     * @return int|array
     */
    public function checkNoviceTest(int $modifyPassword): int|array
    {
        global $tester;
        if($tester->app->user->account != 'guest') $tester->app->user->modifyPassword = $modifyPassword;

        $return = $this->objectModel->checkNovice();

        if(dao::isError()) return dao::getError();

        return $return ? 1 : 0;
    }

    /**
     * 测试获取新手模式产品键值对。
     * Test get tutorial product pairs.
     *
     * @access public
     * @return array
     */
    public function getProductPairsTest(): array
    {
        $objects = $this->objectModel->getProductPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取新手模式模块键值对。
     * Test get module pairs for tutorial.
     *
     * @access public
     * @return array
     */
    public function getModulePairsTest(): array
    {
        $objects = $this->objectModel->getModulePairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取新手模式产品信息。
     * Test get tutorial product.
     *
     * @access public
     * @return object
     */
    public function getProductTest(): object
    {
        return $this->objectModel->getProduct();
    }

    /**
     * 测试获取新手模式产品统计数据。
     * Test get product stats for tutorial.
     *
     * @access public
     * @return array
     */
    public function getProductStatsTest(): array
    {
        return $this->objectModel->getProductStats();
    }

    /**
     * 测试获取新手模式项目信息。
     * Test get project for tutorial.
     *
     * @access public
     * @return object
     */
    public function getProjectTest(): object
    {
        return $this->objectModel->getProject();
    }

    /**
     * 测试获取新手模式项目键值对。
     * Test get tutorial project pairs.
     *
     * @access public
     * @return array
     */
    public function getProjectPairsTest(): array
    {
        return $this->objectModel->getProjectPairs();
    }

    /**
     * 测试获取新手模式项目统计数据。
     * Test get project stats for tutorial.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProjectStatsTest(string $browseType = ''): array
    {
        return $this->objectModel->getProjectStats($browseType);
    }

    /**
     * 测试获取新手模式执行统计数据。
     * Test get execution stats for tutorial.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getExecutionStatsTest(string $browseType = ''): array
    {
        return $this->objectModel->getExecutionStats($browseType);
    }

    /**
     * 测试获取新手模式需求键值对。
     * Test get tutorial stories.
     *
     * @access public
     * @return array
     */
    public function getStoriesTest(): array
    {
        return $this->objectModel->getStories();
    }

    /**
     * 测试获取新手模式执行键值对。
     * Test get tutorial Execution pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionPairsTest(): array
    {
        return $this->objectModel->getExecutionPairs();
    }

    /**
     * 测试获取新手模式的执行。
     * Test get tutorial execution.
     *
     * @access public
     * @return object
     */
    public function getExecutionTest(): object
    {
        return $this->objectModel->getExecution();
    }

    /**
     * 测试获取新手模式产品信息。
     * Test get tutorial execution products.
     *
     * @access public
     * @return array
     */
    public function getExecutionProductsTest(): array
    {
        return $this->objectModel->getExecutionProducts();
    }

    /**
     * 测试获取新手模式执行的需求。
     * Test get tutorial execution stories.
     *
     * @access public
     * @return array
     */
    public function getExecutionStoriesTest(): array
    {
        return $this->objectModel->getExecutionStories();
    }

    /**
     * 测试获取新手模式执行的需求键值对。
     * Test get tutorial execution story pairs.
     *
     * @access public
     * @return array
     */
    public function getExecutionStoryPairsTest(): array
    {
        return $this->objectModel->getExecutionStoryPairs();
    }

    /**
     * 测试获取新手模式团队成员。
     * Test get tutorial team members.
     *
     * @access public
     * @return array
     */
    public function getTeamMembersTest(): array
    {
        return $this->objectModel->getTeamMembers();
    }

    /**
     * 测试获取团队成员键值对。
     * Test get team members pairs.
     *
     * @access public
     * @return array
     */
    public function getTeamMembersPairsTest(): array
    {
        return $this->objectModel->getTeamMembersPairs();
    }

    /**
     * 测试获取新手模式用户键值对。
     * Test get tutorial user pairs.
     *
     * @access public
     * @return array
     */
    public function getUserPairsTest(): array
    {
        return $this->objectModel->getUserPairs();
    }

    /**
     * 测试获取新手模式进度。
     * Get tutorialed.
     *
     * @access public
     * @return string
     */
    public function getTutorialedTest(): string
    {
        return $this->objectModel->getTutorialed();
    }

    /**
     * 测试获取新手模式迭代燃尽图数据。
     * Test get execution burn data for tutorial.
     *
     * @param  array $dateList
     * @access public
     * @return array
     */
    public function getExecutionBurnDataTest(array $dateList): array
    {
        $result = $this->objectModel->getExecutionBurnData($dateList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式研发需求键值对。
     * Test get tutorial story pairs.
     *
     * @access public
     * @return array
     */
    public function getStoryPairsTest(): array
    {
        $result = $this->objectModel->getStoryPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式研发需求。
     * Test get tutorial story.
     *
     * @access public
     * @return object
     */
    public function getStoryTest(): object
    {
        $result = $this->objectModel->getStory();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式业务需求。
     * Test get tutorial epic.
     *
     * @access public
     * @return object
     */
    public function getEpicTest(): object
    {
        $result = $this->objectModel->getEpic();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式用户需求。
     * Test get tutorial requirement.
     *
     * @access public
     * @return object
     */
    public function getRequirementTest(): object
    {
        $result = $this->objectModel->getRequirement();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试根据需求ID获取需求详情。
     * Test get story by ID.
     *
     * @param  int $storyID
     * @access public
     * @return object
     */
    public function getStoryByIDTest(int $storyID): object
    {
        $result = $this->objectModel->getStoryByID($storyID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取需求层级。
     * Test get story grade.
     *
     * @access public
     * @return array
     */
    public function getStoryGradeTest(): array
    {
        $result = $this->objectModel->getStoryGrade();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取需求层级键值对。
     * Test get story grade pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGradePairsTest(string $type): array
    {
        $result = $this->objectModel->getGradePairs($type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式阶段。
     * Test get tutorial stage.
     *
     * @access public
     * @return object
     */
    public function getStageTest(): object
    {
        $result = $this->objectModel->getStage();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式阶段列表。
     * Test get tutorial stages.
     *
     * @access public
     * @return array
     */
    public function getStagesTest(): array
    {
        $result = $this->objectModel->getStages();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式任务。
     * Test get task.
     *
     * @access public
     * @return object
     */
    public function getTaskTest(): object
    {
        $result = $this->objectModel->getTask();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式任务列表。
     * Test get tasks.
     *
     * @access public
     * @return array
     */
    public function getTasksTest(): array
    {
        $result = $this->objectModel->getTasks();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式版本。
     * Test get build.
     *
     * @access public
     * @return object
     */
    public function getBuildTest(): object
    {
        $result = $this->objectModel->getBuild();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式版本列表。
     * Test get builds.
     *
     * @access public
     * @return array
     */
    public function getBuildsTest(): array
    {
        $result = $this->objectModel->getBuilds();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式版本键值对。
     * Test get build pairs.
     *
     * @access public
     * @return array
     */
    public function getBuildPairsTest(): array
    {
        $result = $this->objectModel->getBuildPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试单。
     * Test get run.
     *
     * @access public
     * @return object
     */
    public function getRunTest(): object
    {
        $result = $this->objectModel->getRun();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
