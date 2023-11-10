<?php
/**
 * The control file of editor of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class editor extends control
{
    /**
     * Construct
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        if($this->app->getMethodName() != 'turnon' and empty($this->config->global->editor)) $this->locate($this->createLink('dev', 'editor'));
    }

    /**
     * Show module files and edit them.
     *
     * @access public
     * @return void
     */
    public function index($type = 'editor')
    {
        $this->app->loadLang('dev');
        $this->view->title      = $this->lang->editor->common;
        $this->view->tab        = $type;
        $this->view->moduleTree = $this->loadModel('dev')->getTree($type, 'module');
        $this->display();
    }

    /**
     * Show files and methods of the module.
     *
     * @param  string $moduleDir
     * @access public
     * @return void
     */
    public function extend($moduleDir = '')
    {
        if(!isset($this->lang->{$moduleDir}->common)) $this->app->loadLang($moduleDir);

        $moduleFiles = $this->editor->getModuleFiles($moduleDir);
        $this->view->module = $moduleDir;
        $this->view->tree   = $this->editor->printTree($moduleFiles);
        $this->display();
    }

    /**
     * Edit extend.
     *
     * @param  string $filePath
     * @param  string $action
     * @param  string $isExtends
     * @access public
     * @return void
     */
    public function edit($filePath = '', $action = '', $isExtends = '')
    {
        $this->view->safeFilePath = $filePath;
        $fileContent = '';
        $extension   = 'php';
        if($filePath)
        {
            $filePath = helper::safe64Decode($filePath);
            if(strpos(strtolower($filePath), strtolower($this->app->getBasePath())) !== 0) return print($this->lang->editor->editFileError);
            if($action == 'extendOther' and file_exists($filePath)) $this->view->showContent = file_get_contents($filePath);

            if($action == 'edit' or $action == 'override')
            {
                if(file_exists($filePath))
                {
                    $fileContent = file_get_contents($filePath);
                    if($action == 'override')
                    {
                        $fileContent = str_replace("'../../", '$this->app->getModuleRoot() . \'', $fileContent);
                        $fileContent = str_replace(array('\'./', '"./'), array('\'../../view/', '"../../view'), $fileContent);
                    }
                }
                else
                {
                    $filePath = '';
                }
            }
            elseif($action == 'extendModel')
            {
                $fileContent = $this->editor->extendModel($filePath);
            }
            elseif($action == 'extendControl')
            {
                $okUrl = $this->editor->getExtendLink($filePath, 'extendControl', 'yes');
                $cancelUrl = $this->editor->getExtendLink($filePath, 'extendControl', 'no');
                if(!$isExtends) return print(js::confirm($this->lang->editor->extendConfirm, $okUrl, $cancelUrl));
                $fileContent = $this->editor->extendControl($filePath, $isExtends);
            }
            elseif($action == 'newPage')
            {
                $fileContent = $this->editor->newControl($filePath);
            }
            elseif(strrpos(basename($filePath), '.php') !== false and empty($fileContent))
            {
                $fileContent = "<?php\n";
            }

            $fileName  = basename($filePath);
            if(strpos($fileName, '.') !== false) $extension = substr($fileName, strpos($fileName, '.') + 1);
            if(strtolower($action) == 'newjs')  $extension = 'js';
            if(strtolower($action) == 'newcss') $extension = 'css';
        }
        $this->view->fileContent   = $fileContent;
        $this->view->filePath      = $filePath;
        $this->view->fileExtension = $extension;
        $this->view->action        = $action;
        $this->display();
    }

    /**
     * Set Page name.
     *
     * @param  string    $filePath
     * @access public
     * @return void
     */
    public function newPage($filePath)
    {
        $filePath = helper::safe64Decode($filePath);
        if($_POST)
        {
            $saveFilePath = $this->editor->getSavePath($filePath, 'newMethod');
            $extendLink   = $this->editor->getExtendLink($saveFilePath, 'newPage');
            if(file_exists($saveFilePath) and !$this->post->override) return $this->send(array('result' => 'success', 'callback' => "zui.Modal.confirm('{$this->lang->editor->repeatPage}').then((res) => {if(res) loadPage('{$extendLink}');});"));
            return $this->send(array('result' => 'success', 'load' => $extendLink));
        }
        $this->view->filePath = $filePath;
        $this->display();
    }

    /**
     * Save file to extension.
     *
     * @param  string $filePath
     * @access public
     * @return void
     */
    public function save($filePath = '', $action = '')
    {
        if($filePath and $_POST)
        {
            $filePath = helper::safe64Decode($filePath);
            if(strpos(strtolower($filePath), strtolower($this->app->getBasePath())) !== 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->editor->editFileError));

            $fileName = empty($_POST['fileName']) ? '' : trim($this->post->fileName);
            if($action != 'edit' and empty($fileName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->editor->emptyFileName));

            if($action != 'edit' and $action != 'newPage') $filePath = $this->editor->getSavePath($filePath, $action);
            if($action != 'edit' and $action != 'newPage' and file_exists($filePath) and !$this->post->override) return $this->send(array('result' => 'fail', 'message' => $this->lang->editor->repeatFile));

            $result = $this->editor->save($filePath);
            if(is_string($result)) return $this->send(array('result' => 'fail', 'message' => $result));

            return $this->send(array('result' => 'success', 'load' => inlink('edit', "filePath=" . helper::safe64Encode($filePath) . "&action=edit"), 'callback' => 'reloadExtendWin()'));
        }
    }

    /**
     * Delete extension file.
     *
     * @param  string $filePath
     * @access public
     * @return void
     */
    public function delete($filePath = '')
    {
        $filePath = helper::safe64Decode($filePath);

        if(file_exists($filePath) and unlink($filePath)) return $this->send(array('load' => true));
        return $this->send(array('result' => 'fail', 'message' => $this->lang->editor->notDelete));

    }

    /**
     * Switch editor feature.
     *
     * @param  int    $status     1|0
     * @access public
     * @return void
     */
    public function turnon($status)
    {
        $this->loadModel('setting')->setItem('system.common.global.editor', $status);

        $link = empty($status) ? $this->createLink('dev', 'editor') : $this->createLink('editor', 'index');
        return $this->send(array('result' => 'success', 'load' => $link));
    }
}
