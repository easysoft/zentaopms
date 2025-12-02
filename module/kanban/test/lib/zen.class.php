<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class kanbanZenTest extends baseTest
{
    protected $moduleName = 'kanban';
    protected $className  = 'zen';

    /**
     * Test moveCardByModal method.
     *
     * @param  int $cardID 卡片ID
     * @access public
     * @return mixed
     */
    public function moveCardByModalTest(int $cardID)
    {
        global $tester;

        // 模拟moveCardByModal方法的核心逻辑
        $card = $tester->loadModel('kanban')->getCardByID($cardID);
        if(!$card) return array('regions' => 0, 'card' => array());

        $regions = $tester->loadModel('kanban')->getRegionPairs($card->kanban);

        $result = array();
        $result['regions'] = count($regions);
        $result['card'] = array(
            'id'     => $card->id,
            'name'   => $card->name,
            'kanban' => $card->kanban,
            'status' => $card->status,
        );

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test moveCardByModal method with POST data.
     *
     * @param  int   $cardID   卡片ID
     * @param  int   $columnID 目标列ID
     * @param  int   $laneID   目标泳道ID
     * @access public
     * @return mixed
     */
    public function moveCardByModalPostTest(int $cardID, int $columnID, int $laneID)
    {
        global $tester;

        $_POST = array('column' => $columnID, 'lane' => $laneID, 'region' => 1);
        $result = $this->invokeArgs('moveCardByModal', [$cardID]);
        if(dao::isError()) return dao::getError();

        // 检查卡片是否移动成功
        $cell = $tester->loadModel('kanban')->getCellByCard($cardID, 0);
        return $cell ? array('column' => $cell->column, 'lane' => $cell->lane) : false;
    }

    /**
     * Test setUserAvatar method.
     *
     * @access public
     * @return array
     */
    public function setUserAvatarTest()
    {
        // 调用被测方法
        $this->invokeArgs('setUserAvatar', []);

        if(dao::isError()) return dao::getError();

        // 获取设置到view中的userList
        $view = $this->getProperty('view');
        $userList = $view->userList;

        if(empty($userList)) return array('count' => 0, 'hasClosed' => false);

        $result = array();
        $result['count'] = count($userList);
        $result['hasClosed'] = isset($userList['closed']);
        $result['hasAvatar'] = true;
        $result['hasRealname'] = true;

        foreach($userList as $account => $user)
        {
            if($account === 'closed') continue;
            if(!isset($user['avatar'])) $result['hasAvatar'] = false;
            if(!isset($user['realname'])) $result['hasRealname'] = false;
        }

        return $result;
    }
}
