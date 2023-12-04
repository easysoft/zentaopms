<?php
/**
 * The control file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    public function buildForm(int $fileCount = 1, float $percent = 0.9, string $filesName = "files", string $labelsName = "labels")
    {
        if(!file_exists($this->file->savePath)) return printf($this->lang->file->errorNotExists, $this->file->savePath);
        if(!is_writable($this->file->savePath)) return printf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath);

        $this->view->filesName  = $filesName;
        $this->view->labelsName = $labelsName;
        $this->display();
    }

    /**
     * AJAX: get upload request from the web editor.
     *
     * @param  string $uid
     * @access public
     * @return void
     */
    public function ajaxUpload(string $uid = '')
    {
        $file = $this->file->getUpload('imgFile');

        if(!isset($file[0]) or !in_array($file[0]['extension'], $this->config->file->imageExtensions)) return print(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormate)));

        $file = $file[0];
        if($file)
        {
            if($file['size'] == 0)
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return print(json_encode(array('status' => 'error', 'message' => $this->lang->file->errorFileUpload)));
                return print(json_encode(array('error' => 1, 'message' => $this->lang->file->errorFileUpload)));
            }

            if(@move_uploaded_file($file['tmpname'], $this->file->savePath . $this->file->getSaveName($file['pathname'])))
            {
                /* Compress image for jpg and bmp. */
                $file = $this->file->compressImage($file);

                $file['addedBy']   = $this->app->user->account;
                $file['addedDate'] = helper::today();
                unset($file['tmpname']);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();

                $fileID = $this->dao->lastInsertID();
                $url    = $this->createLink('file', 'read', "fileID=$fileID", $file['extension']);
                if($uid) $_SESSION['album'][$uid][] = $fileID;
                if(defined('RUN_MODE') && RUN_MODE == 'api')
                {
                    if($uid) $_SESSION['album']['used'][$uid][$fileID] = $fileID;
                    $_SERVER['SCRIPT_NAME'] = 'index.php';
                    return $this->send(array('status' => 'success', 'id' => $fileID, 'url' => $url));
                }
                return print(json_encode(array('error' => 0, 'url' => $url)));
            }
            else
            {
                $error = strip_tags(sprintf($this->lang->file->errorCanNotWrite, $this->file->savePath, $this->file->savePath));
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'error', 'message' => $error));
                return print(json_encode(array('error' => 1, 'message' => $error)));
            }
        }
        return $this->send(array('status' => 'error', 'message' => $this->lang->file->uploadImagesExplain));
    }

    /**
     * Preview a file.
     *
     * @param  int    $fileID
     * @param  string $mouse
     * @access public
     * @return void
     */
    public function preview(int $fileID, string $mouse = '')
    {
        return print($this->fetch('file', 'download', "fileID=$fileID&mouse=$mouse"));
    }

    /**
     * Down a file.
     *
     * @param  int    $fileID
     * @param  string $mouse
     * @access public
     * @return void
     */
    public function download(int $fileID, string $mouse = '')
    {
        if(session_id() != $this->app->sessionID) helper::restartSession($this->app->sessionID);
        $file = $this->file->getById($fileID);
        if(empty($file))
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => $this->lang->file->fileNotFound));
            if(isInModal())
            {
                $this->view->error = $this->lang->file->fileNotFound;
                return $this->display();
            }
            return print($this->lang->file->fileNotFound);
        }

        if(!$this->file->checkPriv($file))
        {
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->file->accessDenied, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->file->accessDenied, 'load' => helper::createLink('my', 'index')));
        }

        /* Judge the mode, down or open. */
        $mode      = 'down';
        $fileTypes = 'txt|jpg|jpeg|gif|png|bmp|xml|html|mp4';
        if(stripos($fileTypes, $file->extension) !== false && $mouse == 'left') $mode = 'open';
        if($file->extension == 'txt')
        {
            $extension = 'txt';
            if(($position = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $position + 1);
            if($extension != 'txt') $mode = 'down';
            $file->extension = $extension;
        }

        if($this->file->fileExists($file))
        {
            /* If the mode is open, locate directly. */
            if($mode == 'open')
            {
                if(stripos('txt|jpg|jpeg|gif|png|bmp|mp4', $file->extension) !== false)
                {
                    $this->view->file     = $file;
                    $this->view->charset  = $this->get->charset ? $this->get->charset : $this->config->charset;
                    $this->view->fileType = ($file->extension == 'txt') ? 'txt' : ($file->extension == 'mp4' ? 'video' : 'image');
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
                $fileName = $file->title;
                if(!preg_match("/\.{$file->extension}$/", $fileName)) $fileName .= '.' . $file->extension;
                $this->sendDownHeader($fileName, $file->extension, $file->realPath, 'file');
            }
        }
        else
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => $this->lang->file->fileNotFound));
            if(isInModal())
            {
                $this->view->error = $this->lang->file->fileNotFound;
                return $this->display();
            }
            return print($this->lang->file->fileNotFound);
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
        $fields = $this->post->fields;
        $rows   = $this->post->rows;

        /* Build csv content. */
        $output = '';
        if($rows)
        {
            $output .= '"'. implode('","', $fields) . '"' . "\n";
            foreach($rows as $row)
            {
                $output .= '"';
                foreach($fields as $fieldName => $fieldLabel)
                {
                    $output .= isset($row->$fieldName) ? str_replace(array('"', '&nbsp;'), array('“', ' '), htmlspecialchars_decode(strip_tags($row->$fieldName, '<img>'))) : '';
                    $output .= '","';
                }
                $output .= '"' . "\n";
            }
            if($this->post->kind == 'task' && $this->config->vision != 'lite') $output .= $this->lang->file->childTaskTips;
        }
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
        $fields = $this->post->fields;
        $rows   = $this->post->rows;
        $output = "<?xml version='1.0' encoding='utf-8'?><xml>\n";

        $output .= "<fields>";
        foreach($fields as $fieldName => $fieldLabel) $output .= "  <$fieldName>$fieldLabel</$fieldName>\n";
        $output .= "</fields>";

        $output .= "<rows>";
        foreach($rows as $row)
        {
            $output .= "  <row>\n";
            foreach($fields as $fieldName => $fieldLabel)
            {
                $fieldValue = isset($row->$fieldName) ? htmlSpecialString($row->$fieldName) : '';
                $output    .= "    <$fieldName>$fieldValue</$fieldName>\n";
            }
            $output .= "  </row>\n";
        }
        $output .= "</rows></xml>";

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
        $fields   = $this->post->fields;
        $rows     = $this->post->rows;
        $rowspans = $this->post->rowspan ? $this->post->rowspan : array();
        $colspans = $this->post->colspan ? $this->post->colspan : array();
        $host     = common::getSysURL();
        $kind     = $this->post->kind;
        $fileName = $this->post->fileName;

        $output  = "<html xmlns='http://www.w3.org/1999/xhtml'>";
        $output .= "<head>";
        $output .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        $output .= "<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;} table th, table td {padding: 5px;}</style>";
        $output .= "<title>{$fileName}</title>";
        $output .= "</head>";

        $output .= "<body>";
        if($this->post->kind == 'task') $output .=  "<div style='color:red'>" . $this->lang->file->childTaskTips . '</div>';

        $output .= "<table>";
        $output .= "<tr>";
        $output .= implode("\n", array_map(function($fieldLabel){return "<th><nobr>$fieldLabel</nobr></th>";}, $fields));
        $output .= "</tr>";

        $i = 0;
        foreach($rows as $row)
        {
            if(in_array($kind, array('story', 'bug', 'testcase'))) $row->title = html::a($host . $this->createLink($kind, 'view', "{$kind}ID=$row->id"), $row->title);
            if($kind == 'task') $row->name = html::a($host . $this->createLink('task', 'view', "taskID=$row->id"), $row->name);

            $output    .= "<tr valign='top'>\n";
            $col        = 0;
            $endColspan = 0;
            foreach($fields as $fieldName => $fieldLabel)
            {
                $col ++;
                if(!empty($endColspan) && $col < $endColspan) continue;
                if(isset($endRowspan[$fieldName]) && $i < $endRowspan[$fieldName]) continue;

                $fieldValue = isset($row->$fieldName) ? $row->$fieldName : '';

                $rowspan = '';
                if(isset($rowspans[$i]) && isset($rowspans[$i]['rows'][$fieldName]))
                {
                    $rowspan = "rowspan='{$rowspans[$i]['rows'][$fieldName]}'";
                    $endRowspan[$fieldName] = $i + $rowspans[$i]['rows'][$fieldName];
                }

                $colspan = '';
                if(isset($colspans[$i]) && isset($colspans[$i]['cols'][$fieldName]))
                {
                    $colspan    = "colspan='{$colspans[$i]['cols'][$fieldName]}'";
                    $endColspan = $col + $colspans[$i]['cols'][$fieldName];
                }

                if($fieldValue) $fieldValue = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . $host . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $fieldValue);
                $output .= "<td $rowspan $colspan><nobr>$fieldValue</nobr></td>\n";
            }
            $output .= "</tr>\n";
            $i++;
        }
        $output .= "</table></body></html>";

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
    public function sendDownHeader(string $fileName, string $fileType, string $content, string $type = 'content')
    {
        $this->file->sendDownHeader($fileName, $fileType, $content, $type);
    }

    /**
     * Delete a file.
     *
     * @param  int    $fileID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete(int $fileID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $formUrl = $this->createLink('file', 'delete', "fileID=$fileID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->file->confirmDelete}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
        }
        else
        {
            $file = $this->file->getById($fileID);
            $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($fileID)->exec();
            $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);

            /* Fix Bug #1518. */
            $fileRecord = $this->dao->select('id')->from(TABLE_FILE)->where('pathname')->eq($file->pathname)->fetch();
            if(empty($fileRecord)) $this->file->unlinkFile($file);

            /* Update test case version for test case synchronization. */
            if($file->objectType == 'testcase') $this->file->updateTestcaseVersion($file);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }
    }

    /**
     * Print files.
     *
     * @param  array  $files
     * @param  string $fieldset
     * @param  object $object
     * @param  string $method
     * @param  bool   $showDelete
     * @param  bool   $showEdit
     * @access public
     * @return void
     */
    public function printFiles(array $files, string $fieldset, object|null $object = null, string $method = 'view', bool $showDelete = true, bool $showEdit = true)
    {
        $this->view->files      = $files;
        $this->view->fieldset   = $fieldset;
        $this->view->object     = $object;
        $this->view->method     = $method;
        $this->view->showDelete = $showDelete;
        $this->view->showEdit   = $showEdit;

        if(empty($files)) return null;
        if(strpos('view,edit', $method) !== false and $this->app->clientDevice != 'mobile') return $this->display('file', 'viewfiles');
        $this->display();
    }

    /**
     * Edit file's name.
     *
     * @param  int    $fileID
     * @access public
     * @return void
     */
    public function edit(int $fileID)
    {
        if($_POST)
        {
            $this->app->loadLang('action');
            $file = $this->file->getByID($fileID);
            $data = fixer::input('post')->get();
            if(validater::checkLength($data->fileName, 80, 1) == false)
            {
                $errTip = $this->lang->error->length;
                return print(js::alert(sprintf($errTip[1], $this->lang->file->title, 80, 1)));
            }
            $fileName = $data->fileName . '.' . $data->extension;
            $this->dao->update(TABLE_FILE)->set('title')->eq($fileName)->where('id')->eq($fileID)->exec();

            $extension = "." . $file->extension;
            $actionID  = $this->loadModel('action')->create($file->objectType, $file->objectID, 'editfile', '', $fileName);
            $changes[] = array('field' => 'fileName', 'old' => $file->title, 'new' => $fileName, 'diff' => '');
            $this->action->logHistory($actionID, $changes);

            /* Update test case version for test case synchronization. */
            if($file->objectType == 'testcase' and $file->title != $fileName) $this->file->updateTestcaseVersion($file);
            $newFile = $this->file->getByID($fileID);

            if($this->app->clientDevice == 'mobile') return $this->send(array('load' => true));
            return print(json_encode($newFile));
        }

        if($this->app->clientDevice == 'mobile')
        {
            $file = $this->file->getById($fileID);
            if(strrpos($file->title, '.') !== false)
            {
                /* Fix the file name exe.exe */
                $title     = explode('.', $file->title);
                $extension = end($title);
                if($file->extension == 'txt' && $extension != $file->extension) $file->extension = $extension;
                array_pop($title);
                $file->title = implode('.', $title);
            }

            $this->view->file = $file;
            $this->display();
         }
    }

    /**
     * Paste image in kindeditor at firefox and chrome.
     *
     * @access public
     * @return void
     */
    public function ajaxPasteImg(string $uid = '')
    {
        if($_POST) return print($this->file->pasteImage($this->post->editor, $uid, true));
    }

    /**
     * Upload Images.
     *
     * @param  string    $module
     * @param  string    $params
     * @access public
     * @return void
     */
    public function uploadImages(string $module, string $params, string $uid = '', string $locate = '')
    {
        if($locate)
        {
            $sessionName = $uid . 'ImagesFile';
            $imageFiles  = $this->session->$sessionName;
            $this->session->set($module . 'ImagesFile', $imageFiles);
            unset($_SESSION[$sessionName]);
            return $this->send(array('result' => 'success', 'load' => $this->createLink($module, 'batchCreate', helper::safe64Decode($params))));
        }

        if(strtolower($this->server->request_method) == 'post')
        {
            $file = $this->file->getChunkedFile();
            if(!$file) return print(json_encode(array('result' => 'fail', 'message' => $this->lang->error->noData)));
            if(empty($file['extension']) or !in_array($file['extension'], $this->config->file->imageExtensions)) return print(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormate)));

            $imageFile = $this->file->saveChunkedFile($file, $uid);
            if(!empty($imageFile))
            {
                $sessionName = $uid . 'ImagesFile';
                $imageFiles  = $this->session->$sessionName;
                $fileName    = basename($imageFile['pathname']);

                if(empty($imageFiles)) $imageFiles = array();
                $imageFiles[$fileName] = $imageFile;
                $this->session->set($sessionName, $imageFiles);
            }
            return print(json_encode(array('result' => 'success', 'file' => $file, 'message' => $this->lang->file->uploadSuccess)));
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
    public function buildExportTPL(string $module, int $templateID = 0)
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
    public function ajaxSaveTemplate(string $module)
    {
        $templateID = $this->file->saveExportTemplate($module);
        if(dao::isError())
        {
            echo js::error(dao::getError(), false);
            $templateID = 0;
        }
        return print($this->fetch('file', 'buildExportTPL', "module=$module&templateID=$templateID"));
    }

    /**
     * Ajax delete template.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxDeleteTemplate(int $templateID)
    {
        $this->dao->delete()->from(TABLE_USERTPL)->where('id')->eq($templateID)->andWhere('account')->eq($this->app->user->account)->exec();
    }

    /**
     * Read file.
     *
     * @param  int    $fileID
     * @access public
     * @return void
     */
    public function read(int $fileID)
    {
        if(!($this->app->company->guest and $this->app->user->account == 'guest') and !$this->loadModel('user')->isLogon()) return print(js::locate($this->createLink('user', 'login')));
        $file = $this->file->getById($fileID);
        if(!$this->file->checkPriv($file)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->file->accessDenied, 'locate' => helper::createLink('my', 'index')), 'closeModal' => true));
        if(empty($file) or !$this->file->fileExists($file)) return false;

        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        $mime = (isset($file->extension) and in_array($file->extension, $this->config->file->imageExtensions)) ? "image/{$file->extension}" : $this->config->file->mimes['default'];
        helper::header('Content-type', $mime);

        $cacheMaxAge = 10 * 365 * 24 * 3600;
        helper::header('Cache-Control', 'private');
        helper::header('Pragma', 'cache');
        helper::header('Expires', gmdate('D, d M Y H:i:s', time() + $cacheMaxAge) . ' GMT');
        helper::header('Cache-Control', "max-age=$cacheMaxAge");

        $handle = fopen($file->realPath, "r");
        if($handle)
        {
            while(!feof($handle)) echo fgets($handle);
            fclose($handle);
        }
    }


    /**
     * 关闭升级到企业版提示。
     * Close the biz guide.
     *
     * @param  string $moduleName
     * @access public
     * @return void
     */
    public function ajaxCloseBizGuide(string $moduleName)
    {
        $path = "{$this->app->user->account}.{$moduleName}.closeBizGuide@rnd";
        $this->loadModel('setting')->setItem($path, 1);
    }
}
