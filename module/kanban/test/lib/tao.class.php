<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class kanbanTaoTest extends baseTest
{
    protected $moduleName = 'kanban';
    protected $className  = 'tao';

    /**
     * Test refreshBugCards method.
     *
     * @param  array  $cardPairs     卡片对集合
     * @param  int    $executionID   执行ID
     * @param  string $otherCardList 其他卡片列表
     * @access public
     * @return array
     */
    public function refreshBugCardsTest($cardPairs = array(), $executionID = 0, $otherCardList = '')
    {
        $result = $this->invokeArgs('refreshBugCards', [$cardPairs, $executionID, $otherCardList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test refreshERURCards method.
     *
     * @param  array  $cardPairs     卡片对集合
     * @param  int    $executionID   执行ID
     * @param  string $otherCardList 其他卡片列表
     * @param  string $laneType      泳道类型
     * @access public
     * @return array
     */
    public function refreshERURCardsTest($cardPairs = array(), $executionID = 0, $otherCardList = '', $laneType = 'story')
    {
        $result = $this->invokeArgs('refreshERURCards', [$cardPairs, $executionID, $otherCardList, $laneType]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test refreshStoryCards method.
     *
     * @param  array  $cardPairs     卡片对集合
     * @param  int    $executionID   执行ID
     * @param  string $otherCardList 其他卡片列表
     * @access public
     * @return array
     */
    public function refreshStoryCardsTest($cardPairs = array(), $executionID = 0, $otherCardList = '')
    {
        $result = $this->invokeArgs('refreshStoryCards', [$cardPairs, $executionID, $otherCardList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateCardAssignedTo method.
     *
     * @param  int    $cardID            卡片ID
     * @param  string $oldAssignedToList 旧的指派人列表
     * @param  array  $users             用户数组
     * @access public
     * @return string
     */
    public function updateCardAssignedToTest($cardID = 0, $oldAssignedToList = '', $users = array())
    {
        global $tester;
        $this->invokeArgs('updateCardAssignedTo', [$cardID, $oldAssignedToList, $users]);
        if(dao::isError()) return dao::getError();

        $card = $tester->dao->select('assignedTo')->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->fetch();
        return $card ? $card->assignedTo : false;
    }

    /**
     * Test getStoryCardMenu method.
     *
     * @param  object $execution 执行对象
     * @param  array  $objects   需求对象数组
     * @access public
     * @return array
     */
    public function getStoryCardMenuTest($execution = null, $objects = array())
    {
        $result = $this->invokeArgs('getStoryCardMenu', [$execution, $objects]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
