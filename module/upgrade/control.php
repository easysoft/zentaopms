<?php
/**
 * The control file of upgrade module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class upgrade extends control
{
    /* 升级程序首页。*/
    public function index()
    {
        $this->display();
    }

    /* 选择系统。*/
    public function selectVersion()
    {
        $version = str_replace(array(' ', '.'), array('', '_'), $this->config->installedVersion);
        $version = strtolower($version);
        $this->view->header->title = $this->lang->upgrade->common . $this->lang->colon . $this->lang->upgrade->selectVersion;
        $this->view->position[]    = $this->lang->upgrade->common;
        $this->view->version       = $version;
        $this->display();
    }

    /* 确认。*/
    public function confirm()
    {
        $this->view->header->title = $this->lang->upgrade->confirm;
        $this->view->position[]    = $this->lang->upgrade->common;
        $this->view->confirm       = $this->upgrade->confirm($this->post->fromVersion);
        $this->view->fromVersion   = $this->post->fromVersion;

        $this->display();
    }

    /* 执行转换。*/
    public function execute()
    {
        $this->upgrade->execute($this->post->fromVersion);

        $this->view->header->title = $this->lang->upgrade->result;
        $this->view->position[]    = $this->lang->upgrade->common;

        if(!$this->upgrade->isError())
        {
            $this->view->result = 'success';
        }
        else
        {
            $this->view->result = 'fail';
            $this->view->errors = $this->upgrade->getError();
        }
        $this->display();
    }
}
