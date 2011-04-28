<?php
/**
 * The control file of file module of ZenTaoPMS.
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
            move_uploaded_file($file['tmpname'], $this->file->savePath . $file['pathname']);
            $url =  $this->file->webPath . $file['pathname'];

            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::today();
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();

            die(json_encode(array('error' => 0, 'url' => $url)));
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
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) $fileName = urlencode($fileName);
                header('Content-Description: File Transfer');
                header('Content-type: application/octet-stream');
                header("Content-Disposition: attachment; filename=$fileName");
                $fileData = file_get_contents($file->realPath);
                echo $fileData;
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
        $fileName = $this->post->fileName;
        $csvData  = $this->post->rows;
        $output   = '';

        /* format csvData form array to string. */
        $output .= '"'. implode('","', $this->post->fields) . '"' . "\n";
        foreach($csvData as $value)
        {
            $output .= '"'. implode('","', (array)$value) . '"' . "\n";
        }

        /* If the language is zh-cn, convert to gbk. */
        $clientLang = $this->app->getClientLang();
        if($clientLang == 'zh-cn')
        {
            if(function_exists('mb_convert_encoding'))
            {
                $output = @mb_convert_encoding($output, 'gbk', 'utf-8');
            }
            elseif(function_exists('iconv'))
            {
                $output = @iconv('utf-8', 'gbk', $output);
            }
        }

        if(strpos($fileName, '.csv') === false) $fileName .= '.csv';
        header('Content-type: application/csv');
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $output;
        die();
    }

    /**
     * export as xml format
     * 
     * @access public
     * @return void
     */
    public function export2XML() 
    {  
        $xmlData  = $this->post->rows;
        $fileName = $this->post->fileName;
        $fields   = $this->post->fields;
        $output   = '';
        $content  = '';
        $xmlMark  = '<?xml version="1.0" encoding="utf-8"?><xml>';
        $tmpArray = array();

        /* format xmlData from array to xml. */
        $content .= "<fields><field>" . implode("</field><field>", $fields) . "</field></fields>";
        foreach($xmlData as $row)
        {
            $tmpArray[] = "<row>" . implode("</row><row>", (array)$row) . "</row>";
        }
        $content .= "<rows>" . implode("</rows><rows>", $tmpArray) . "</rows>";
        $output   = $xmlMark . $content . '</xml>';

        if(strpos($fileName, '.xml') === false) $fileName .= '.xml';
        header('Content-type: application/xml');
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $output;
        die();
    }  

    /**
     * export as html format
     * 
     * @access public
     * @return void
     */
    public function export2HTML() 
    {  
        $htmlData = $this->post->rows;
        $fileName = $this->post->fileName;
        $fields   = $this->post->fields;
        $output   = '';
        $content  = '';
        $tmpArray = array();

        /* format htmlData from array to html. */
        $content .= "<thead><th>" . implode("</th><th>", $fields) . "</th></thead>";
        foreach($htmlData as $tbody)
        {
            $tmpArray[] = "<td>" . implode("</td><td>", (array)$tbody) . "</td>";
        }
        $content .= "<tbody><tr>" . implode("</tr></tbody><tbody><tr>", $tmpArray) . "</tr></tbody>";
        $output   = "<html><head><title>$fileName</title></head><body><table>$content</table></body></html>";

        if(strpos($fileName, '.html') === false) $fileName .= '.html';
        header('Content-type: application/html');
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $output;
        die();
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
}
