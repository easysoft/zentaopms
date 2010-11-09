<?php
/**
 * The control file of upgrade module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
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
