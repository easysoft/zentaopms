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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class install extends control
{
    /* 构造函数，检查是否是通过安装入口调用。*/
    public function __construct()
    {
        if(!defined('IN_INSTALL')) die();
        parent::__construct();
    }

    /* 安装程序首页。*/
    public function index()
    {
        if(!isset($this->config->installed) or !$this->config->installed) $this->session->set('installing', true);

        $this->view->header->title = $this->lang->install->welcome;

        /* 获得官方网站最新的版本。*/
        $snoopy = $this->app->loadClass('snoopy');
        if(@$snoopy->fetchText('http://www.zentao.net/misc-getlatestrelease.json'))
        {
            $result = json_decode($snoopy->results);
            if(isset($result->release) and $this->config->version != $result->release->version)
            {
                $this->view->latestRelease = $result->release;
            }
        }

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
        $this->display();
    }

    /* 生成配置文件。*/
    public function step3()
    {
        if(!empty($_POST))
        {
            $return = $this->install->checkConfig();
            if($return->result == 'ok')
            {
                $this->view = (object)$_POST;
                $this->view->lang   = $this->lang;
                $this->view->config = $this->config;
                $this->view->domain = $this->server->HTTP_HOST;
                $this->view->header->title = $this->lang->install->saveConfig;
                $this->display();
            }
            else
            {
                $this->view->header->title = $this->lang->install->saveConfig;
                $this->view->error = $return->error;
                $this->display();
            }
        }
        else
        {
            $this->locate($this->createLink('install'));
        }
    }

    /* 第四步，创建公司，生成管理员帐号。*/
    public function step4()
    {
        if(!empty($_POST))
        {
            $this->install->grantPriv();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('setting')->updateVersion($this->config->version);
            $this->setting->setSN();
            echo (js::alert($this->lang->install->success));
            unset($_SESSION['installing']);
            session_destroy();
            die(js::locate('index.php', 'parent'));
        }

        $this->view->header->title = $this->lang->install->getPriv;
        if(!isset($this->config->installed) or !$this->config->installed)
        {
            $this->view->error = $this->lang->install->errorNotSaveConfig;
            $this->display();
        }
        else
        {
            $this->view->pmsDomain = $this->server->HTTP_HOST;
            $this->display();
        }
    }
}
