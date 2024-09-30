<?php
declare(strict_types=1);
/**
 * The control file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: control.php 4460 2021-10-26 11:03:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class kanbanZen extends kanban
{
    /**
     * 创建看板时，向视图文件发送变量。
     * Assign variables to the view file when creating a kanban.
     *
     * @param  int    $spaceID
     * @param  string $type
     * @param  int    $copyKanbanID
     * @param  string $extra
     * @access public
     * @return void
     */
    protected function assignCreateVars(int $spaceID, string $type, int $copyKanbanID, string $extra)
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $enableImport  = 'on';
        $importObjects = array_keys($this->lang->kanban->importObjectList);
        if($copyKanbanID)
        {
            $copyKanban    = $this->kanban->getByID($copyKanbanID);
            $enableImport  = empty($copyKanban->object) ? 'off' : 'on';
            $importObjects = empty($copyKanban->object) ? array() : explode(',', $copyKanban->object);
            $spaceID       = $copyKanban->space;
        }

        unset($this->lang->kanban->featureBar['space']['involved']);

        $space      = $this->kanban->getSpaceById($spaceID);
        $spaceUsers = $spaceID == 0 ? ',' : trim($space->owner) . ',' . trim($space->team);
        $spacePairs = $this->kanban->getSpacePairs($type);
        $users      = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $ownerPairs = (isset($spacePairs[$spaceID])) ? $this->user->getPairs('noclosed|nodeleted', '', 0, $spaceUsers) : $users;

        $this->view->users         = $users;
        $this->view->ownerPairs    = $ownerPairs;
        $this->view->spaceID       = $spaceID;
        $this->view->spacePairs    = $spacePairs;
        $this->view->type          = $type;
        $this->view->typeList      = $this->lang->kanban->featureBar['space'];
        $this->view->kanbans       = $this->kanban->getPairs();
        $this->view->copyKanbanID  = $copyKanbanID;
        $this->view->copyKanban    = $copyKanbanID ? $copyKanban : '';
        $this->view->enableImport  = $enableImport;
        $this->view->importObjects = $importObjects;
        $this->view->copyRegion    = isset($output['copyRegion']) ? 1 : 0;
        $this->view->spaceTeam     = !empty($space->team) ? $space->team : '';

        $this->display();
    }

    /**
     * 设置用户头像。
     * Set user avatar data.
     *
     * @access public
     * @return void
     */
    public function setUserAvatar(): void
    {
        /* Get user list. */
        $userList    = array();
        $users       = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $avatarPairs = $this->user->getAvatarPairs('all');
        foreach($avatarPairs as $account => $avatar)
        {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar']   = $avatar;
        }
        $userList['closed']['account']  = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar']   = '';

        $this->view->userList = $userList;
    }

    /**
     * 模态框内移动看板。
     * Move card by modal.
     *
     * @param  int $cardID
     * @access public
     * @return void
     */
    public function moveCardByModal(int $cardID)
    {
        $card = $this->kanban->getCardByID($cardID);

        if($_POST)
        {
            $this->lang->kanban->region = $this->lang->kanbanregion->name;
            $this->lang->kanban->lane   = $this->lang->kanbanlane->name;
            $this->lang->kanban->column = $this->lang->kanbancolumn->common;
            $formData = form::data($this->config->kanban->form->moveCard)->get();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $cell = $this->kanban->getCellByCard($cardID, $card->kanban);
            $this->kanban->moveCard($cardID, $cell->column, $formData->column, $cell->lane, $formData->lane, $card->kanban);
        }
        else
        {
            $this->view->regions = $this->kanban->getRegionPairs($card->kanban);
            $this->view->card    = $this->kanban->getCardByID($cardID);
            $this->display();
        }
    }
}
