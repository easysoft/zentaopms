<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class aiappModelTest extends baseTest
{
    protected $moduleName = 'aiapp';
    protected $className  = 'model';

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest()
    {
        $result = $this->instance;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLatestMiniPrograms method.
     *
     * @param object $pager
     * @param string $order
     * @access public
     * @return mixed
     */
    public function getLatestMiniProgramsTest($pager = null, $order = 'publishedDate_desc')
    {
        $result = $this->instance->getLatestMiniPrograms($pager, $order);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test countLatestMiniPrograms method.
     *
     * @access public
     * @return mixed
     */
    public function countLatestMiniProgramsTest()
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('countLatestMiniPrograms');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveMiniProgramMessage method.
     *
     * @param string $appID
     * @param string $type
     * @param string $content
     * @access public
     * @return mixed
     */
    public function saveMiniProgramMessageTest($appID, $type, $content)
    {
        $result = $this->instance->saveMiniProgramMessage($appID, $type, $content);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteHistoryMessagesByID method.
     *
     * @param string $appID
     * @param string $userID
     * @param array  $messageIDs
     * @access public
     * @return mixed
     */
    public function deleteHistoryMessagesByIDTest($appID, $userID, $messageIDs)
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('deleteHistoryMessagesByID');
        $method->setAccessible(true);
        $method->invoke($this->instance, $appID, $userID, $messageIDs);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getHistoryMessages method.
     *
     * @param string $appID
     * @param int    $limit
     * @access public
     * @return mixed
     */
    public function getHistoryMessagesTest($appID, $limit = 20)
    {
        $result = $this->instance->getHistoryMessages($appID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCollectedMiniProgramIDs method.
     *
     * @param string $userID
     * @param object $pager
     * @access public
     * @return mixed
     */
    public function getCollectedMiniProgramIDsTest($userID, $pager = null)
    {
        $result = $this->instance->getCollectedMiniProgramIDs($userID, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSquareCategoryArray method.
     *
     * @param array $collectedIDs
     * @param int   $latestSum
     * @access public
     * @return mixed
     */
    public function getSquareCategoryArrayTest($collectedIDs = null, $latestSum = null)
    {
        $result = $this->instance->getSquareCategoryArray($collectedIDs, $latestSum);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUsedCategoryArray method.
     *
     * @access public
     * @return mixed
     */
    public function getUsedCategoryArrayTest()
    {
        $result = $this->instance->getUsedCategoryArray();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}