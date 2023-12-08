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
     * Get tutorial project pairs.
     *
     * @access public
     * @return void
     */
    public function getProjectPairsTest()
    {
        $objects = $this->objectModel->getProjectPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get project stats for tutorial.
     *
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function getProjectStatsTest($browseType = '')
    {
        $objects = $this->objectModel->getProjectStats($browseType = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial stories.
     *
     * @access public
     * @return void
     */
    public function getStoriesTest()
    {
        $objects = $this->objectModel->getStories();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial Execution pairs.
     *
     * @access public
     * @return void
     */
    public function getExecutionPairsTest()
    {
        $objects = $this->objectModel->getExecutionPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial execution.
     *
     * @access public
     * @return void
     */
    public function getExecutionTest()
    {
        $objects = $this->objectModel->getExecution();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial execution products.
     *
     * @access public
     * @return void
     */
    public function getExecutionProductsTest()
    {
        $objects = $this->objectModel->getExecutionProducts();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial execution stories.
     *
     * @access public
     * @return void
     */
    public function getExecutionStoriesTest()
    {
        $objects = $this->objectModel->getExecutionStories();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial execution story pairs.
     *
     * @access public
     * @return void
     */
    public function getExecutionStoryPairsTest()
    {
        $objects = $this->objectModel->getExecutionStoryPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tutorial team members.
     *
     * @access public
     * @return void
     */
    public function getTeamMembersTest()
    {
        $objects = $this->objectModel->getTeamMembers();

        if(dao::isError()) return dao::getError();

        return $objects;
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
