<?php
class projectstoryTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('projectstory');
    }

    /**
     * Set menu test
     *
     * @param  array $products
     * @param  int   $productID
     * @param  int   $branch
     * @access public
     * @return xml
     */
    public function setMenuTest($products = array(), $productID = 0, $branch = 0)
    {
        $objects = $this->objectModel->setMenu($products, $productID, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get execution stories test
     *
     * @param  int   mixed $projectID
     * @param  array $storyIdList
     * @access public
     * @return array
     */
    public function getExecutionStoriesTest($projectID, $storyIdList = array())
    {
        $objects = $this->objectModel->getExecutionStories($projectID, $storyIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
