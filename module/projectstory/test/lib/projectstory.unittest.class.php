<?php
class projectstoryTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('projectstory');
    }

    /**
     * Get execution stories test
     *
     * @param  int   $projectID
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
