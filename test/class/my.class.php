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
}
