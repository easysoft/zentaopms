<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class projectstoryModelTest extends baseTest
{
    protected $moduleName = 'projectstory';
    protected $className  = 'model';

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
        $objects = $this->instance->getExecutionStories($projectID, $storyIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test buildSearchConfig method.
     *
     * @param  int $projectID
     * @access public
     * @return mixed
     */
    public function buildSearchConfigTest($projectID)
    {
        $result = $this->instance->buildSearchConfig($projectID);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
