<?php
class myTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('my');
    }

    /**
     * Function getProducts test by my
     *
     * @param  string $type
     * @access public
     * @return object
     */
    public function getProductsTest($type)
    {
        $objects = $this->objectModel->getProducts($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getDoingProjects test by my
     *
     * @access public
     * @return object
     */
    public function getDoingProjectsTest()
    {
        $objects = $this->objectModel->getDoingProjects();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getOverview test by my
     *
     * @access public
     * @return object
     */
    public function getOverviewTest()
    {
        $objects = $this->objectModel->getOverview();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getContribute test by my
     *
     * @access public
     * @return object
     */
    public function getContributeTest()
    {
        $objects = $this->objectModel->getContribute();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getActions test by my
     *
     * @access public
     * @return array
     */
    public function getActionsTest()
    {
        $objects = $this->objectModel->getActions();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getAssignedByMe test by my
     *
     * @param string $account
     * @param int    $limit
     * @param int    $pager
     * @param string $orderBy
     * @param int    $projectID
     * @param string $objectType
     * @access public
     * @return int
     */
    public function getAssignedByMeTest($account, $limit, $pager, $orderBy, $projectID, $objectType)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 20;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getAssignedByMe($account, $limit, $pager, $orderBy, $objectType);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Get testcases by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getTestcasesBySearchTest($queryID, $type, $orderBy)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 20;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getTestcasesBySearch($query, $type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get tasks by search.
     *
     * @param  string $account
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getTasksBySearchTest($account, $limit = 0)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 20;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getTasksBySearch($account, $limit, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get stories by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStoriesBySearchTest($queryID, $type, $orderBy)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 1;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getStoriesBySearch($queryID, $type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get requirements by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getRequirementsBySearchTest($queryID, $type, $orderBy)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 1;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getRequirementsBySearch($queryID, $type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

}
