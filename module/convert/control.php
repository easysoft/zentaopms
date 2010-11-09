<?php
/**
 * The control file of convert currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class convert extends control
{
    /* 安装程序首页。*/
    public function index()
    {
        $this->convert->saveState();
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
        $this->view->version = $version;
        $this->view->setting = $this->fetch('convert', $setFunc, "version=$version");
        $this->display();
    }

    /* BugFree的设置界面。*/
    public function setBugFree($version)
    {
        $this->view->source      = 'BugFree';
        $this->view->version     = $version;
        $this->view->tablePrefix = $version > 1 ? 'bf_' : '';
        $this->view->dbName      = $version > 1 ? 'bugfree2' : 'BugFree';
        $this->view->dbCharset   = 'utf8';
        $this->display();
    }

    /* 检查配置。*/
    public function checkConfig()
    {
        $checkFunc = 'check' . $this->post->source;
        $this->view->header->title = $this->lang->convert->checkConfig;
        $this->view->source        = $this->post->source;
        $this->view->checkResult   = $this->fetch('convert', $checkFunc, "version={$this->post->version}");
        $this->display();
    }

    /* 检查BugFree的设置。*/
    public function checkBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        $converter = new bugfreeConvertModel();

        /* 分别检查数据库、表和安装路径。*/
        $checkInfo['db'] = $converter->connectDB();
        //if(is_object($checkInfo['db'])) $checkInfo['table'] = $converter->checkTables();
        $checkInfo['path'] = $converter->checkPath();

        /* 计算检查结果。*/
        $result = 'pass';
        if(!is_object($checkInfo['db']) or !$checkInfo['path']) $result = 'fail';

        /* 赋值。*/
        $this->view->version   = $version;
        $this->view->source    = 'bugfree';
        $this->view->result    = $result;
        $this->view->checkInfo = $checkInfo;
        $this->display();
    }

    /* 执行转换。*/
    public function execute()
    {
        $convertFunc = 'convert' . $this->post->source;
        $this->view->header->title = $this->lang->convert->execute;
        $this->view->source        = $this->post->source;
        $this->view->version       = $this->post->version;
        $this->view->executeResult = $this->fetch('convert', $convertFunc, "version={$this->post->version}");
        $this->display();
    }

    /* 转换BugFree。*/
    public function convertBugFree($version)
    {
        helper::import('./converter/bugfree.php');
        helper::import("./converter/bugfree$version.php");
        $className = "bugfree{$version}ConvertModel";
        $converter = new $className();
        $this->view->version = $version;
        $this->view->result  = $converter->execute($version);
        $this->view->info    = bugfreeConvertModel::$info;
        $this->display();
    }
}
