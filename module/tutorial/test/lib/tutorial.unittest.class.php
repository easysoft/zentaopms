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

    /**
     * 测试获取新手模式用例。
     * Test get case.
     *
     * @access public
     * @return object
     */
    public function getCaseTest(): object
    {
        $result = $this->objectModel->getCase();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式用例列表。
     * Test get cases.
     *
     * @access public
     * @return array
     */
    public function getCasesTest(): array
    {
        $result = $this->objectModel->getCases();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式用例执行结果。
     * Test get result.
     *
     * @access public
     * @return object
     */
    public function getResultTest(): object
    {
        $result = $this->objectModel->getResult();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式用例执行结果列表。
     * Test get results.
     *
     * @access public
     * @return array
     */
    public function getResultsTest(): array
    {
        $result = $this->objectModel->getResults();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试单。
     * Test get testtask.
     *
     * @access public
     * @return object
     */
    public function getTesttaskTest(): object
    {
        $result = $this->objectModel->getTesttask();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试单列表。
     * Test get testtasks.
     *
     * @access public
     * @return array
     */
    public function getTesttasksTest(): array
    {
        $result = $this->objectModel->getTesttasks();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试单键值对。
     * Test get testtask pairs.
     *
     * @access public
     * @return array
     */
    public function getTesttaskPairsTest(): array
    {
        $result = $this->objectModel->getTesttaskPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试报告。
     * Test get testreport.
     *
     * @access public
     * @return object
     */
    public function getTestReportTest(): object
    {
        $result = $this->objectModel->getTestReport();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式测试报告列表。
     * Test get testreports.
     *
     * @access public
     * @return array
     */
    public function getTestReportsTest(): array
    {
        $result = $this->objectModel->getTestReports();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式Bug。
     * Test get bug.
     *
     * @access public
     * @return object
     */
    public function getBugTest(): object
    {
        $result = $this->objectModel->getBug();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式Bug列表。
     * Test get bugs.
     *
     * @access public
     * @return array
     */
    public function getBugsTest(): array
    {
        $result = $this->objectModel->getBugs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式问题。
     * Test get issue.
     *
     * @access public
     * @return object
     */
    public function getIssueTest(): object
    {
        $result = $this->objectModel->getIssue();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式问题列表。
     * Test get issues.
     *
     * @access public
     * @return array
     */
    public function getIssuesTest(): array
    {
        $result = $this->objectModel->getIssues();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式风险。
     * Test get risk.
     *
     * @access public
     * @return object
     */
    public function getRiskTest(): object
    {
        $result = $this->objectModel->getRisk();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式风险列表。
     * Test get risks.
     *
     * @access public
     * @return array
     */
    public function getRisksTest(): array
    {
        $result = $this->objectModel->getRisks();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式设计。
     * Test get design.
     *
     * @access public
     * @return object
     */
    public function getDesignTest(): object
    {
        $result = $this->objectModel->getDesign();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式设计列表。
     * Test get designs.
     *
     * @access public
     * @return array
     */
    public function getDesignsTest(): array
    {
        $result = $this->objectModel->getDesigns();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式评审。
     * Test get review.
     *
     * @access public
     * @return object
     */
    public function getReviewTest(): object
    {
        $result = $this->objectModel->getReview();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式评审列表。
     * Test get reviews.
     *
     * @access public
     * @return array
     */
    public function getReviewsTest(): array
    {
        $result = $this->objectModel->getReviews();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式看板默认区域键值对。
     * Test get region pairs.
     *
     * @access public
     * @return array
     */
    public function getRegionPairsTest(): array
    {
        $result = $this->objectModel->getRegionPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式看板组。
     * Test get groups.
     *
     * @access public
     * @return array
     */
    public function getGroupsTest(): array
    {
        $result = $this->objectModel->getGroups();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式看板泳道组。
     * Test get lane group.
     *
     * @access public
     * @return array
     */
    public function getLaneGroupTest(): array
    {
        $result = $this->objectModel->getLaneGroup();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式看板列。
     * Test get columns.
     *
     * @access public
     * @return array
     */
    public function getColumnsTest(): array
    {
        $result = $this->objectModel->getColumns();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
