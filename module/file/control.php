<?php
/**
 * The control file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class file extends control
{
    /**
     * Build the upload form.
     * 
     * @param  int    $fileCount 
     * @param  float  $percent 
     * @access public
     * @return void
     */
    public function buildForm($fileCount = 2, $percent = 0.9)
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
        $this->view->fileCount = $fileCount;
        $this->view->percent   = $percent;
        $this->display();
    }

    /**
     * AJAX: get upload request from the web editor.
     * 
     * @access public
     * @return void
     */
    public function ajaxUpload()
    {
        $file = $this->file->getUpload('imgFile');
        $file = $file[0];
        if($file)
        {
            if(@move_uploaded_file($file['tmpname'], $this->file->savePath . $file['pathname']))
            {
                $url =  $this->file->webPath . $file['pathname'];

                $file['addedBy']    = $this->app->user->account;
                $file['addedDate']  = helper::today();
                unset($file['tmpname']);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();

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
     * Down a file.
     * 
     * @param  int    $fileID 
     * @param  string $mouse 
     * @access public
     * @return void
     */
    public function download($fileID, $mouse = '')
    {
        $file = $this->file->getById($fileID);

        /* Judge the mode, down or open. */
        $mode  = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html';
        if(stripos($fileTypes, $file->extension) !== false and $mouse == 'left') $mode = 'open';

        /* If the mode is open, locate directly. */
        if($mode == 'open')
        {
            if(file_exists($file->realPath))$this->locate($file->webPath);
            $this->app->error("The file you visit $fileID not found.", __FILE__, __LINE__, true);
        }
        else
        {
            /* Down the file. */
            if(file_exists($file->realPath))
            {
                $fileName = $file->title . '.' . $file->extension;
                $fileData = file_get_contents($file->realPath);
                $this->sendDownHeader($fileName, $file->extension, $fileData);
            }
            else
            {
                $this->app->error("The file you visit $fileID not found.", __FILE__, __LINE__, true);
            }
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
        if( $this->post->encode != "utf-8")
        {
            if(function_exists('mb_convert_encoding'))
            {
                $output = @mb_convert_encoding($output, $this->post->encode, 'utf-8');
            }
            elseif(function_exists('iconv'))
            {
                $output = @iconv('utf-8', $this->post->encode, $output);
            }
        }

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
        /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
        setcookie('downloading', 1);

        /* Append the extension name auto. */
        $extension = '.' . $fileType;
        if(strpos($fileName, $extension) === false) $fileName .= $extension;

        /* urlencode the filename for ie. */
        if(strpos($this->server->http_user_agent, 'MSIE') !== false) $fileName = urlencode($fileName);

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
     * @param  string    $fieldset 
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
}
