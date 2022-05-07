<?php
class tutorialTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('tutorial');
    }

    public function checkNoviceTest()
    {
        $this->app->user->modifyPassword = 1;
        $objects = $this->objectModel->checkNovice();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductPairsTest()
    {
        $objects = $this->objectModel->getProductPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getModulePairsTest()
    {
        $objects = $this->objectModel->getModulePairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductTest()
    {
        $objects = $this->objectModel->getProduct();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductStatsTest()
    {
        $objects = $this->objectModel->getProductStats();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectTest()
    {
        $objects = $this->objectModel->getProject();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectPairsTest()
    {
        $objects = $this->objectModel->getProjectPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectStatsTest($browseType = '')
    {
        $objects = $this->objectModel->getProjectStats($browseType = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStoriesTest()
    {
        $objects = $this->objectModel->getStories();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionPairsTest()
    {
        $objects = $this->objectModel->getExecutionPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionTest()
    {
        $objects = $this->objectModel->getExecution();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionProductsTest()
    {
        $objects = $this->objectModel->getExecutionProducts();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionStoriesTest()
    {
        $objects = $this->objectModel->getExecutionStories();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionStoryPairsTest()
    {
        $objects = $this->objectModel->getExecutionStoryPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTeamMembersTest()
    {
        $objects = $this->objectModel->getTeamMembers();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTeamMembersPairsTest()
    {
        $objects = $this->objectModel->getTeamMembersPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserPairsTest()
    {
        $objects = $this->objectModel->getUserPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTutorialedTest()
    {
        $objects = $this->objectModel->getTutorialed();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
