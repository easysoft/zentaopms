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

    /**
     * 测试获取新手模式看板列对象。
     * Test get column.
     *
     * @access public
     * @return object
     */
    public function getColumnTest(): object
    {
        $result = $this->objectModel->getColumn();
        return $result;
    }

    /**
     * 测试获取新手模式看板卡片组。
     * Test get card group.
     *
     * @access public
     * @return array
     */
    public function getCardGroupTest(): array
    {
        $result = $this->objectModel->getCardGroup();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式计划。
     * Test get plan.
     *
     * @access public
     * @return object
     */
    public function getPlanTest(): object
    {
        $result = $this->objectModel->getPlan();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式计划列表。
     * Test get plans.
     *
     * @access public
     * @return array
     */
    public function getPlansTest(): array
    {
        $result = $this->objectModel->getPlans();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式计划键值对。
     * Test get plan pairs.
     *
     * @access public
     * @return array
     */
    public function getPlanPairsTest(): array
    {
        $result = $this->objectModel->getPlanPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式系统。
     * Test get system.
     *
     * @access public
     * @return object
     */
    public function getSystemTest(): object
    {
        $result = $this->objectModel->getSystem();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式应用键值对。
     * Test get system pairs.
     *
     * @access public
     * @return array
     */
    public function getSystemPairsTest(): array
    {
        $result = $this->objectModel->getSystemPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式产品应用列表。
     * Test get product app list.
     *
     * @access public
     * @return array
     */
    public function getSystemListTest(): array
    {
        $result = $this->objectModel->getSystemList();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRelease method.
     *
     * @access public
     * @return object
     */
    public function getReleaseTest(): object
    {
        $result = $this->objectModel->getRelease();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getReleases method.
     *
     * @access public
     * @return array
     */
    public function getReleasesTest(): array
    {
        $result = $this->objectModel->getReleases();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式项目集键值对。
     * Test get program pairs.
     *
     * @access public
     * @return array
     */
    public function getProgramPairsTest(): array
    {
        $result = $this->objectModel->getProgramPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式项目集。
     * Test get program.
     *
     * @access public
     * @return object
     */
    public function getProgramTest(): object
    {
        $result = $this->objectModel->getProgram();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式项目集列表。
     * Test get programs.
     *
     * @access public
     * @return array
     */
    public function getProgramsTest(): array
    {
        $result = $this->objectModel->getPrograms();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式分支键值对。
     * Test get branch pairs.
     *
     * @access public
     * @return array
     */
    public function getBranchPairsTest(): array
    {
        $result = $this->objectModel->getBranchPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式项目关联分支。
     * Test get branches by project.
     *
     * @access public
     * @return array
     */
    public function getBranchesByProjectTest(): array
    {
        $result = $this->objectModel->getBranchesByProject();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式分支列表。
     * Test get branches.
     *
     * @access public
     * @return array
     */
    public function getBranchesTest(): array
    {
        $result = $this->objectModel->getBranches();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式反馈。
     * Test get feedback.
     *
     * @access public
     * @return object
     */
    public function getFeedbackTest(): object
    {
        $result = $this->objectModel->getFeedback();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试获取新手模式反馈列表。
     * Test get feedbacks.
     *
     * @access public
     * @return array
     */
    public function getFeedbacksTest(): array
    {
        $result = $this->objectModel->getFeedbacks();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSubSpaces method.
     *
     * @param  string $type
     * @access public
     * @return array|null
     */
    public function getSubSpacesTest(string $type = 'custom'): array|null
    {
        $result = $this->objectModel->getSubSpaces($type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDocTemplateSpaces method.
     *
     * @access public
     * @return array
     */
    public function getDocTemplateSpacesTest(): array
    {
        $result = $this->objectModel->getDocTemplateSpaces();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDocLib method.
     *
     * @access public
     * @return object
     */
    public function getDocLibTest(): object
    {
        $result = $this->objectModel->getDocLib();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDocLibs method.
     *
     * @access public
     * @return array
     */
    public function getDocLibsTest(): array
    {
        $result = $this->objectModel->getDocLibs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLibTree method.
     *
     * @access public
     * @return array
     */
    public function getLibTreeTest(): array
    {
        $result = $this->objectModel->getLibTree();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDoc method.
     *
     * @access public
     * @return object
     */
    public function getDocTest(): object
    {
        $result = $this->objectModel->getDoc();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDocs method.
     *
     * @access public
     * @return array
     */
    public function getDocsTest(): array
    {
        $result = $this->objectModel->getDocs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDemandpool method.
     *
     * @access public
     * @return object
     */
    public function getDemandpoolTest(): object
    {
        $result = $this->objectModel->getDemandpool();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDemand method.
     *
     * @access public
     * @return object
     */
    public function getDemandTest(): object
    {
        $result = $this->objectModel->getDemand();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDemands method.
     *
     * @access public
     * @return array
     */
    public function getDemandsTest(): array
    {
        $result = $this->objectModel->getDemands();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getResearchStageStats method.
     *
     * @access public
     * @return array
     */
    public function getResearchStageStatsTest(): array
    {
        $result = $this->objectModel->getResearchStageStats();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMarket method.
     *
     * @access public
     * @return object
     */
    public function getMarketTest(): object
    {
        $result = $this->objectModel->getMarket();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCharter method.
     *
     * @access public
     * @return object
     */
    public function getCharterTest(): object
    {
        $result = $this->objectModel->getCharter();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCharters method.
     *
     * @access public
     * @return array
     */
    public function getChartersTest(): array
    {
        $result = $this->objectModel->getCharters();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRepoPairs method.
     *
     * @access public
     * @return array
     */
    public function getRepoPairsTest(): array
    {
        $result = $this->objectModel->getRepoPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRepo method.
     *
     * @access public
     * @return object
     */
    public function getRepoTest(): object
    {
        $result = $this->objectModel->getRepo();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCommits method.
     *
     * @access public
     * @return array
     */
    public function getCommitsTest(): array
    {
        $result = $this->objectModel->getCommits();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
