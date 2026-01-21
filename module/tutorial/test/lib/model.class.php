<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class tutorialModelTest extends baseTest
{
    protected $moduleName = 'tutorial';
    protected $className  = 'model';

    /**
     * Test getColumn method.
     *
     * @access public
     * @return object
     */
    public function getColumnTest()
    {
        $result = $this->invokeArgs('getColumn', []);
        if(dao::isError()) return dao::getError();
        return $result;
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
        if($this->instance->app->user->account != 'guest') $this->instance->app->user->modifyPassword = $modifyPassword;

        $return = $this->instance->checkNovice();

        if(dao::isError()) return dao::getError();

        return $return ? 1 : 0;
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
        $result = $this->instance->getBranches();
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
        $result = $this->instance->getBranchesByProject();
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
        $result = $this->instance->getBranchPairs();
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
        $result = $this->instance->getBug();
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
        $result = $this->instance->getBugs();
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
        $result = $this->instance->getBuild();
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
        $result = $this->instance->getBuildPairs();
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
        $result = $this->instance->getBuilds();
        if(dao::isError()) return dao::getError();

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
        $result = $this->instance->getCardGroup();
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
        $result = $this->instance->getCase();
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
        $result = $this->instance->getCases();
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
        $result = $this->instance->getCharter();
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
        $result = $this->instance->getCharters();
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
        $result = $this->instance->getColumns();
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
        $result = $this->instance->getCommits();
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
        $result = $this->instance->getDemand();
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
        $result = $this->instance->getDemandpool();
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
        $result = $this->instance->getDemands();
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
        $result = $this->instance->getDesign();
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
        $result = $this->instance->getDesigns();
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
        $result = $this->instance->getDoc();
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
        $result = $this->instance->getDocLib();
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
        $result = $this->instance->getDocLibs();
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
        $result = $this->instance->getDocs();
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
        $result = $this->instance->getDocTemplateSpaces();
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
        $result = $this->instance->getEpic();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getExecution();
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
        $result = $this->instance->getExecutionBurnData($dateList);
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getExecutionPairs();
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
        return $this->instance->getExecutionProducts();
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
        return $this->instance->getExecutionStats($browseType);
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
        return $this->instance->getExecutionStories();
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
        return $this->instance->getExecutionStoryPairs();
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
        $result = $this->instance->getFeedback();
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
        $result = $this->instance->getFeedbacks();
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
        $result = $this->instance->getGradePairs($type);
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
        $result = $this->instance->getGroups();
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
        $result = $this->instance->getIssue();
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
        $result = $this->instance->getIssues();
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
        $result = $this->instance->getLaneGroup();
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
        $result = $this->instance->getLibTree();
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
        $result = $this->instance->getMarket();
        if(dao::isError()) return dao::getError();

        return $result;
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
        $objects = $this->instance->getModulePairs();

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $result = $this->instance->getPlan();
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
        $result = $this->instance->getPlanPairs();
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
        $result = $this->instance->getPlans();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getProduct();
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
        $objects = $this->instance->getProductPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
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
        return $this->instance->getProductStats();
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
        $result = $this->instance->getProgram();
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
        $result = $this->instance->getProgramPairs();
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
        $result = $this->instance->getPrograms();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getProject();
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
        return $this->instance->getProjectPairs();
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
        return $this->instance->getProjectStats($browseType);
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
        $result = $this->instance->getRegionPairs();
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
        $result = $this->instance->getRelease();
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
        $result = $this->instance->getReleases();
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
        $result = $this->instance->getRepo();
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
        $result = $this->instance->getRepoPairs();
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
        $result = $this->instance->getRequirement();
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
        $result = $this->instance->getResearchStageStats();
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
        $result = $this->instance->getResult();
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
        $result = $this->instance->getResults();
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
        $result = $this->instance->getReview();
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
        $result = $this->instance->getReviews();
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
        $result = $this->instance->getRisk();
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
        $result = $this->instance->getRisks();
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
        $result = $this->instance->getRun();
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
        $result = $this->instance->getStage();
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
        $result = $this->instance->getStages();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getStories();
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
        $result = $this->instance->getStory();
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
        $result = $this->instance->getStoryByID($storyID);
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
        $result = $this->instance->getStoryGrade();
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
        $result = $this->instance->getStoryPairs();
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
        $result = $this->instance->getStoryPairs();
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
        $result = $this->instance->getSubSpaces($type);
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
        $result = $this->instance->getSystem();
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
        $result = $this->instance->getSystemList();
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
        $result = $this->instance->getSystemPairs();
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
        $result = $this->instance->getTask();
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
        $result = $this->instance->getTasks();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getTeamMembers();
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
        return $this->instance->getTeamMembersPairs();
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
        $result = $this->instance->getTestReport();
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
        $result = $this->instance->getTestReports();
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
        $result = $this->instance->getTesttask();
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
        $result = $this->instance->getTesttaskPairs();
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
        $result = $this->instance->getTesttasks();
        if(dao::isError()) return dao::getError();

        return $result;
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
        return $this->instance->getTutorialed();
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
        return $this->instance->getUserPairs();
    }
}
