<?php
/**
 * The control file of convert currentModule of ZenTaoMS.
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
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class convert extends control
{
    /* 安装程序首页。*/
    public function index()
    {
        $this->view->header->title = $this->lang->convert->common;
        $this->display();
    }

    /* 选择系统。*/
    public function selectSource()
    {
        $this->view->header->title = $this->lang->convert->common . $this->lang->colon;
        $this->display();
    }

    /* 转换参数设置。*/
    public function setConfig()
    {
        if(!$this->post->source) 
        {
            echo js::alert($this->lang->convert->mustSelectSource);
            die(js::locate('back'));
        }
        list($sourceName, $version) = explode('_', $this->post->source);
        $setFunc = "set$sourceName";
        $this->view->header->title = $this->lang->convert->setting;
        $this->view->source  = $sourceName;
        $this->view->setting = $this->fetch('convert', $setFunc, "version=$version");
        $this->display();
    }

    /* BugFree的设置界面。*/
    public function setBugFree($version)
    {
        $this->view->source      = 'BugFree';
        $this->view->version     = $version;
        $this->view->tablePrefix = $version > 1 ? 'bf' : '';
        $this->view->dbName      = 'BugFree';
        $this->display();
    }

    /* 检查配置。*/
    public function checkConfig()
    {
        $checkFunc = 'check' . $this->post->source;
        $this->view->header->title = $this->lang->convert->checkConfig;
        $this->view->source  = $this->post->source;
        $this->view->checkResult = $this->fetch('convert', $checkFunc, "version={$this->post->version}");
        $this->display();
    }

    /* 检查BugFree的设置。*/
    public function checkBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        $converter = new bugfreeConvertModel();
        $checkResult['connectDB']   = $converter->connectDB();
        $checkResult['checkTables'] = $converter->checkTables();
        $checkResult['checkRoot']   = $converter->checkRoot();
        a($checkResult);
        $this->view->source  = 'bugfree';
        $this->view->version = $version;
        $this->display();
    }

    /* 执行转换。*/
    public function execute()
    {
        $convertFunc = 'convert' . $this->post->source;
        $this->$convertFunc($this->post->version);
    }

    /* 转换BugFree。*/
    public function convertBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        $converter = new bugfreeConvertModel();
        $converter->execute();
    }
}
