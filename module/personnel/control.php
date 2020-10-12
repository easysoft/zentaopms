<?php
/**
 * The control file of personnel of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class personnel extends control
{
    /**
     * Get a list of people who can be accessed.
     *
     * @param  int  $programID
     * @access public
     * @return void
     */
    public function accessible($programID = 0)
    {
        $this->loadModel('program');
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $this->view->title      = $this->lang->personnel->accessible;
        $this->view->position[] = $this->lang->personnel->accessible;

        $this->display();
    }

    /**
     * Access to investable personnel.
     *
     * @param  int  $programID
     * @access public
     * @return void
     */
    public function putinto($programID = 0)
    {
        $this->loadModel('program');
        $this->lang->navGroup->program = 'program';
        $this->lang->program->switcherMenu = $this->program->getPGMCommonAction() . $this->program->getPGMSwitcher($programID);
        $this->program->setPGMViewMenu($programID);

        $this->view->title      = $this->lang->personnel->putinto;
        $this->view->position[] = $this->lang->personnel->putinto;

        $this->display();
    }
}
