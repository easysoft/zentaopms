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
     * Get tutorial product pairs.
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
     * Get module pairs for tutorial.
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
     * Get tutorial product.
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
     * Get product stats for tutorial.
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
     * Get project for tutorial.
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
     * Get tutorial project pairs.
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
     * Get project stats for tutorial.
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
     * Get execution stats for tutorial.
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
     * Get tutorial stories.
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
     * 测试1获取新手模式执行的需求键值对。
     * Get tutorial execution story pairs.
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
     * Get team members pairs.
     *
     * @access public
     * @return void
     */
    public function getTeamMembersPairsTest()
    {
        $objects = $this->objectModel->getTeamMembersPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial user pairs.
     *
     * @access public
     * @return void
     */
    public function getUserPairsTest()
    {
        $objects = $this->objectModel->getUserPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorialed.
     *
     * @access public
     * @return void
     */
    public function getTutorialedTest()
    {
        $objects = $this->objectModel->getTutorialed();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
