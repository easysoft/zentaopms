<?php
/**
 * The control file of file module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class file extends control
{
    /* 生成文件上传的表单。*/
    public function buildForm($fileCount = 2, $percent = 0.9)
    {
        $this->view->fileCount = $fileCount;
        $this->view->percent   = $percent;
        $this->display();
    }

    /* ajax接口，用于接收编辑器附件上传。*/
    public function ajaxUpload()
    {
        $file = $this->file->getUpload('imgFile');
        $file = $file[0];
        if($file)
        {
            move_uploaded_file($file['tmpname'], $this->file->savePath . $file['pathname']);
            $url =  $this->file->webPath . $file['pathname'];

            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::today();
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();

            die(json_encode(array('error' => 0, 'url' => $url)));
        }
    }

    /* 下载一个文件。*/
    public function download($fileID, $mouse = '')
    {
        $file = $this->file->getById($fileID);

        /* 判断是下载还是打开。*/
        $mode  = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html';
        if(strpos($fileTypes, $file->extension) !== false and $mouse == 'left') $mode = 'open';

        /* 模式为open，直接在浏览器打开。*/
        if($mode == 'open')
        {
            if(file_exists($file->realPath))$this->locate($file->webPath);
            $this->app->error("The file you visit $fileID not found.", __FILE__, __LINE__, true);
        }
        else
        {
            /* 下载文件。*/
            if(file_exists($file->realPath))
            {
                $fileName = $file->title . '.' . $file->extension;
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) $fileName = urlencode($fileName);
                header('Content-Description: File Transfer');
                header('Content-type: application/octet-stream');
                header("Content-Disposition: attachment; filename=$fileName");
                $fileData = file_get_contents($file->realPath);
                echo $fileData;
            }
            $this->app->error("The file you visit $fileID not found.", __FILE__, __LINE__, true);
        }
    }

    /* 导出csv格式的文件。*/
    public function export2csv($agent)
    {
        $fileName   = $this->post->fileName;
        $csvData    = stripslashes($this->post->csvData);

        /* 如果是中文，尝试将编码转为gbk. */
        $clientLang = $this->app->getClientLang();
        if($clientLang == 'zh-cn')
        {
            if(function_exists('mb_convert_encoding'))
            {
                $csvData = @mb_convert_encoding($csvData, 'gbk', 'utf-8');
            }
            elseif(function_exists('iconv'))
            {
                $csvData = @iconv('utf-8', 'gbk', $csvData);
            }
        }

        if(strpos($fileName, '.csv') === false) $fileName .= '.csv';
        if($agent == 'ie') $fileName = urlencode($fileName);
        header('Content-type: application/csv');
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $csvData;
        die();
    }

    /* 删除一个文件。*/
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

    /* 显示下载及删除链接。*/
    public function printFiles($files, $fieldset)
    {
        $this->view->files    = $files;
        $this->view->fieldset = $fieldset;
        $this->display();
    }
}
