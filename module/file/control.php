<?php
/**
 * The control file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: control.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
class file extends control
{
    /**
     * Build the upload form.
     * 
     * @param  int    $fileCount 
     * @param  float  $percent 
     * @param  string $filesName
     * @param  string $labelsName
     * @access public
     * @return void
     */
    public function buildForm($fileCount = 1, $percent = 0.9, $filesName = 'files', $labelsName = 'labels')
    {
        if(!file_exists($this->file->savePath)) 
        {
            printf($this->lang->file->errorNotExists, $this->file->savePath);
            return false;
        }
        elseif(!is_writable($this->file->savePath))
        {
            printf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath);
            return false;
        }

        $this->view->fileCount  = $fileCount;
        $this->view->percent    = $percent;
        $this->view->filesName  = $filesName;
        $this->view->labelsName = $labelsName;
        $this->display();
    }

    /**
     * AJAX: get upload request from the web editor.
     * 
     * @access public
     * @return void
     */
    public function ajaxUpload($uid = '')
    {
        $file = $this->file->getUpload('imgFile');
        $file = $file[0];
        if($file)
        {
            if($file['size'] == 0) die(json_encode(array('error' => 1, 'message' => $this->lang->file->errorFileUpload)));
            if(@move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname'])))
            {
                /* Compress image for jpg and bmp. */
                $file = $this->file->compressImage($file);

                $file['addedBy']    = $this->app->user->account;
                $file['addedDate']  = helper::today();
                unset($file['tmpname']);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();

                $fileID = $this->dao->lastInsertID();
                $url    = $this->createLink('file', 'read', "fileID=$fileID", $file['extension']);
                if($uid) $_SESSION['album'][$uid][] = $fileID;
                die(json_encode(array('error' => 0, 'url' => $url)));
            }
            else
            {
                $error = strip_tags(sprintf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath));
                die(json_encode(array('error' => 1, 'message' => $error)));
            }
        }
    }

    /**
     * AJAX: get upload request from the web editor.
     * 
     * @access public
     * @return void
     */
    public function ajaxUeditorUpload($uid = '')
    {
        if($this->get->action == 'config')
        {
            die(json_encode($this->config->file->ueditor));
        }

        $file = $this->file->getUpload('upfile');
        $file = $file[0];
        if($file)
        {
            if($file['size'] == 0) die(json_encode(array('state' => $this->lang->file->errorFileUpload)));
            if(@move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname'])))
            {
                /* Compress image for jpg and bmp. */
                $file = $this->file->compressImage($file);

                $file['addedBy']    = $this->app->user->account;
                $file['addedDate']  = helper::today();
                unset($file['tmpname']);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();

                $fileID = $this->dao->lastInsertID();
                $url    = $this->createLink('file', 'read', "fileID=$fileID", $file['extension']);
                if($uid) $_SESSION['album'][$uid][] = $fileID;
                die(json_encode(array('state' => 'SUCCESS', 'url' => $url)));
            }
            else
            {
                $error = strip_tags(sprintf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath));
                die(json_encode(array('state' => $error)));
            }
        }
    }

    /**
     * Down a file.
     * 
     * @param  int    $fileID 
     * @param  string $mouse 
     * @access public
     * @return void
     */
    public function download($fileID, $mouse = '')
    {
        /* When get sid then change session id. */
        if(isset($_GET[$this->config->sessionVar]))
        {
            $sessionID = isset($_COOKIE[$this->config->sessionVar]) ? $_COOKIE[$this->config->sessionVar] : sha1(mt_rand());
            session_write_close();
            session_id($sessionID);
            session_start();
        }

        $file = $this->file->getById($fileID);

        /* Judge the mode, down or open. */
        $mode  = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html';
        if(stripos($fileTypes, $file->extension) !== false and $mouse == 'left') $mode = 'open';

        if(file_exists($file->realPath))
        {
            /* If the mode is open, locate directly. */
            if($mode == 'open')
            {
                if(stripos('txt|jpg|jpeg|gif|png|bmp', $file->extension) !== false)
                {
                    $this->view->file     = $file;
                    $this->view->charset  = $this->get->charset ? $this->get->charset : $this->config->charset;
                    $this->view->fileType = ($file->extension == 'txt') ? 'txt' : 'image';
                    $this->display();
                }
                else
                {
                    $this->locate($file->webPath);
                }
            }
            else
            {
                /* Down the file. */
                $fileName = $file->title . '.' . $file->extension;
                $fileData = file_get_contents($file->realPath);
                $this->sendDownHeader($fileName, $file->extension, $fileData);
            }
        }
        else
        {
            $this->app->triggerError("The file you visit $fileID not found.", __FILE__, __LINE__, true);
        }
    }

    /**
     * Export as csv format.
     * 
     * @access public
     * @return void
     */
    public function export2CSV()
    {
        $this->view->fields = $this->post->fields;
        $this->view->rows   = $this->post->rows;
        $output = $this->parse('file', 'export2csv');
        if($this->post->encode != "utf-8") $output = helper::convertEncoding($output, 'utf-8', $this->post->encode . '//TRANSLIT');

        $this->sendDownHeader($this->post->fileName, 'csv', $output);
    }

    /**
     * export as xml format
     * 
     * @access public
     * @return void
     */
    public function export2XML() 
    {  
        $this->view->fields = $this->post->fields;
        $this->view->rows   = $this->post->rows;
        
        $output = $this->parse('file', 'export2XML');

        $this->sendDownHeader($this->post->fileName, 'xml', $output);
    }  

    /**
     * export as html format
     * 
     * @access public
     * @return void
     */
    public function export2HTML() 
    {  
        $this->view->fields = $this->post->fields;
        $this->view->rows   = $this->post->rows;
        $this->host         = common::getSysURL();

        switch($this->post->kind)
        {
            case 'task':
            foreach($this->view->rows as $row)
            {
                $row->name = html::a($this->host . $this->createLink('task', 'view', "taskID=$row->id"), $row->name); 
            }
            break;
            case 'story':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('story', 'view', "storyID=$row->id"), $row->title);  
            }
            break;
            case 'bug':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('bug', 'view', "bugID=$row->id"), $row->title);  
            }
            break;
            case 'testcase':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('testcase', 'view', "caseID=$row->id"), $row->title);    
            }
            break;
        }
        $this->view->fileName = $this->post->fileName;
        $output = $this->parse('file', 'export2Html');

        $this->sendDownHeader($this->post->fileName, 'html', $output);
    }  

    /**
     * Send the download header to the client.
     * 
     * @param  string    $fileName 
     * @param  string    $extension 
     * @access public
     * @return void
     */
    public function sendDownHeader($fileName, $fileType, $content)
    {
        /* Clean the ob content to make sure no space or utf-8 bom output. */
        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
        setcookie('downloading', 1);

        /* Append the extension name auto. */
        $extension = '.' . $fileType;
        if(strpos($fileName, $extension) === false) $fileName .= $extension;

        /* urlencode the filename for ie. */
        if(strpos($this->server->http_user_agent, 'MSIE') !== false or strpos($this->server->http_user_agent, 'Trident') !== false) $fileName = urlencode($fileName);

        /* Judge the content type. */
        $mimes = $this->config->file->mimes;
        $contentType = isset($mimes[$fileType]) ? $mimes[$fileType] : $mimes['default'];

        header("Content-type: $contentType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        die($content);
    }

    /**
     * Delete a file.
     * 
     * @param  int    $fileID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($fileID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->file->confirmDelete, inlink('delete', "fileID=$fileID&confirm=yes")));
        }
        else
        {
            $file = $this->file->getById($fileID);
            $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($fileID)->exec();
            $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);
            @unlink($file->realPath);
            die(js::reload('parent'));
        }
    }

    /**
     * Print files. 
     * 
     * @param  array  $files 
     * @param  string $fieldset 
     * @access public
     * @return void
     */
    public function printFiles($files, $fieldset)
    {
        $this->view->files    = $files;
        $this->view->fieldset = $fieldset;
        $this->display();
    }

    /**
     * Edit file's name. 
     * 
     * @param  int    $fileID 
     * @access public
     * @return void
     */
    public function edit($fileID)
    {
        if($_POST)
        {
            $this->app->loadLang('action');
            $file = $this->file->getByID($fileID);
            $this->dao->update(TABLE_FILE)->set('title')->eq($this->post->fileName)->where('id')->eq($fileID)->exec();

            $extension = "." . $file->extension;
            $actionID = $this->loadModel('action')->create($file->objectType, $file->objectID, 'editfile', '', $this->post->fileName . $extension);
            $changes[] = array('field' => 'fileName', 'old' => $file->title . $extension, 'new' => $this->post->fileName . $extension);
            $this->action->logHistory($actionID, $changes);

            die(js::reload('parent.parent'));
        }

        $this->view->file = $this->file->getById($fileID);
        $this->display();
    }

    /**
     * Paste image in kindeditor at firefox and chrome. 
     * 
     * @access public
     * @return void
     */
    public function ajaxPasteImage($uid = '')
    {
        if($_POST)
        {
            echo $this->file->pasteImage($this->post->editor, $uid);
        }
    }

    /**
     * Upload Images.
     * 
     * @param  string    $module 
     * @param  string    $params 
     * @access public
     * @return void
     */
    public function uploadImages($module, $params, $uid = '', $locate = false)
    {
        if($locate)
        {
            $sessionName = $uid . 'ImagesFile';
            $imageFiles  = $this->session->$sessionName;
            $this->session->set($module . 'ImagesFile', $imageFiles);
            unset($_SESSION[$sessionName]);
            die(js::locate($this->createLink($module, 'batchCreate', helper::safe64Decode($params)), 'parent.parent'));
        }

        if($_FILES)
        {
            $file = $this->file->getUploadFile('file');
            if(!$file) die(json_encode(array('result' => 'fail', 'message' => $this->lang->error->noData)));
            if(empty($file['extension']) or !in_array($file['extension'], $this->config->file->imageExtensions))
            {
                die(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormate)));
            }

            $imageFile = $this->file->saveUploadFile($file, $uid);
            if($imageFile === false)
            {
                die(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileMove)));
            }
            else
            {
                if(!empty($imageFile))
                {
                    $sessionName = $uid . 'ImagesFile';
                    $imageFiles  = $this->session->$sessionName;
                    $fileName    = basename($imageFile['pathname']);
                    $imageFiles[$fileName] = $imageFile;
                    $this->session->set($sessionName, $imageFiles);
                }
                die(json_encode(array('result' => 'success', 'file' => $file, 'message' => $this->lang->file->uploadSuccess)));
            }
        }

        $this->view->uid    = empty($uid) ? uniqid() : $uid;
        $this->view->module = $module;
        $this->view->params = $params;

        $this->display();
    }

    /**
     * Build export tpl.
     * 
     * @param  string $module 
     * @param  int    $templateID 
     * @access public
     * @return void
     */
    public function buildExportTPL($module, $templateID = 0)
    {
        $templates       = $this->file->getExportTemplate($module);
        $templatePairs[] = $this->lang->file->defaultTPL;
        foreach($templates as $template) $templatePairs[$template->id] = ($template->public ? "[{$this->lang->public}] " : '') . $template->title;

        $this->view->templates     = $templates;
        $this->view->templatePairs = $templatePairs;
        $this->view->templateID    = $templateID;
        $this->display();
    }

    /**
     * Ajax save template.
     * 
     * @param  string $module 
     * @access public
     * @return void
     */
    public function ajaxSaveTemplate($module)
    {
        $templateID = $this->file->saveExportTemplate($module);
        if(dao::isError())
        {
            echo js::error(dao::getError(), $full = false);
            $templateID = 0;
        }
        die($this->fetch('file', 'buildExportTPL', "module=$module&templateID=$templateID"));
    }

    /**
     * Ajax delete template.
     * 
     * @param  int    $templateID 
     * @access public
     * @return void
     */
    public function ajaxDeleteTemplate($templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)->where('id')->eq($templateID)->andWhere('account')->eq($this->app->user->account)->exec();
        die();
    }

    /**
     * Read file. 
     * 
     * @param  int    $fileID 
     * @access public
     * @return void
     */
    public function read($fileID)
    {
        $file = $this->file->getById($fileID);
        if(empty($file) or !file_exists($file->realPath)) return false;

        $mime = in_array($file->extension, $this->config->file->imageExtensions) ? "image/{$file->extension}" : $this->config->file->mimes['default'];
        header("Content-type: $mime");

        $handle = fopen($file->realPath, "r");
        if($handle)
        {
            while(!feof($handle)) echo fgets($handle);
            fclose($handle);
        }
    }
}
