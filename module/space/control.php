<?php
/**
 * The control file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu <liumemgyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: control.php 4460 2021-12-08 11:03:02 $
 * @link        https://www.zentao.net
 */
class space extends control
{
    /**
     * create space.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $spaceID = $this->kanban->createSpace();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($spaceID) $actionID = $this->loadModel('action')->create('space', $spaceID, 'opened');
        }

        $users = $this->user->getPairs('noclosed|nodeleted');

        $this->view->title      = $this->lang->kanban->spaceCreate;
        $this->view->position[] = $this->lang->kanban->spaceCreate;
        $this->view->users      = $this->user->getPairs('noclosed|nodeleted');
        $this->display();
    }

}
