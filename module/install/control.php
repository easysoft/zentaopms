<?php
/**
 * The control file of install currentModule of ZenTaoMS.
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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class install extends control
{
    /* 安装程序首页。*/
    public function index()
    {
        $this->view->header->title = $this->lang->install->welcome;
        $this->display();
    }

    /* 第一步： 系统检查。*/
    public function step1()
    {
        $this->view->header->title  = $this->lang->install->checking;
        $this->view->phpVersion     = $this->install->getPhpVersion();
        $this->view->phpResult      = $this->install->checkPHP();
        $this->view->pdoResult      = $this->install->checkPDO();
        $this->view->pdoMySQLResult = $this->install->checkPDOMySQL();
        $this->view->tmpRootInfo    = $this->install->getTmpRoot();
        $this->view->tmpRootResult  = $this->install->checkTmpRoot();
        $this->view->dataRootInfo   = $this->install->getDataRoot();
        $this->view->dataRootResult = $this->install->checkDataRoot();
        $this->view->iniInfo        = $this->install->getIniInfo();
        $this->display();
    }

    /* 第二步：配置表单。*/
    public function step2()
    {
        $this->view->header->title = $this->lang->install->setConfig;
        $this->view->webRoot = $this->install->getWebRoot();
        $this->display();
    }

    /* 生成配置文件。*/
    public function step3()
    {
        if(!empty($_POST))
        {
            $this->view = (object)$_POST;
            $this->view->lang   = $this->lang;
            $this->view->config = $this->config;
            $this->view->header->title = $this->lang->install->saveConfig;
            $this->display();
        }
    }
}
