<?php
declare(strict_types=1);
/**
 * The control file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: control.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
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
     * Build the old upload form.
     *
     * @param  int    $fileCount
     * @param  float  $percent
     * @param  string $filesName
     * @param  string $labelsName
     * @access public
     * @return void
     */
    public function buildOldForm(int $fileCount = 1, float $percent = 0.9, string $filesName = "files", string $labelsName = "labels")
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
     * @param string $uid
     * @param string $objectType
     * @param int    $objectID
     * @param string $extra
     * @param string $field
     * @param bool   $api
     * @access public
     * @return void
     */
    public function ajaxUpload(string $uid = '', string $objectType = '', int $objectID = 0, string $extra = '', string $field = 'imgFile', bool $api = false, string $gid = '')
    {
        $file = $this->file->getUpload($field);

        if(!isset($file[0]) or strpos(",{$this->config->file->allowed},", ",{$file[0]['extension']},") === false) return print(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat)));

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

                $file['addedBy']    = $this->app->user->account;
                $file['addedDate']  = helper::today();
                $file['objectType'] = $objectType;
                $file['objectID']   = $objectID;
                $file['extra']      = $extra;
                $file['gid']        = empty($gid) ? '' : base64_decode($gid);
                unset($file['tmpname']);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();

                $fileID = $this->dao->lastInsertID();
                $url    = $this->createLink('file', 'read', "fileID=$fileID", $file['extension']);
                if($uid) $_SESSION['album'][$uid][] = $fileID;
                if($api || (defined('RUN_MODE') && RUN_MODE == 'api'))
                {
                    if($uid) $_SESSION['album']['used'][$uid][$fileID] = $fileID;
                    $_SERVER['SCRIPT_NAME'] = 'index.php';
                    return $this->send(array('result' => 'success', 'status' => 'success', 'id' => $fileID, 'url' => $url, 'data' => array('id' => $fileID, 'url' => $url))); // 兼容老的 API 形式。 Compatible with the old API.
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
        $file = $this->file->getById($fileID);
        if(empty($file) || !$this->file->fileExists($file))
        {
            $this->view->error = $this->lang->file->fileNotFound;
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => $this->view->error));
            if(isInModal()) return $this->display();
            return print($this->view->error);
        }

        /* Unset the menu. */
        if(!empty($this->lang->{$this->app->tab}->menu)) $this->lang->{$this->app->tab}->menu = array();

        if(!$this->file->checkPriv($file))
        {
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->file->accessDenied, 'closeModal' => true, 'load' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->file->accessDenied, 'load' => helper::createLink('my', 'index')));
        }

        /* Judge the mode, down or open. */
        $mode = $this->fileZen->getDownloadMode($file, $mouse);

        /* Down the file. */
        if($mode != 'open')
        {
            $fileName = $file->title;
            if(!preg_match("/\.{$file->extension}$/", strtolower($fileName))) $fileName .= '.' . $file->extension;
            return $this->sendDownHeader($fileName, $file->extension, $file->realPath, 'file');
        }

        /* If the mode is open, locate directly. */
        $this->view->title    = $this->lang->file->previewFile;
        $this->view->file     = $file;
        $this->view->charset  = $this->get->charset ? $this->get->charset : $this->config->charset;
        $this->view->fileType = ($file->extension == 'txt') ? 'txt' : ($file->extension == 'mp4' ? 'video' : 'image');
        $this->display();
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
        $domain = common::getSysURL();

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
                    /* <img>标签替换成链接。 */
                    if(in_array($fieldName, $this->config->file->img2href))
                    {
                        $pattern         = '/<img src="\{([^"]*)\}" alt="([^"]*)"\s*\/>/';
                        $replacement     = '$1 (' . $domain . '/$2)';
                        $row->$fieldName = preg_replace($pattern, $replacement, $row->$fieldName);
                    }

                    if(isset($row->$fieldName) && is_numeric($row->$fieldName)) $row->$fieldName = $row->$fieldName . "\t";
                    if(isset($row->$fieldName)) $row->$fieldName = str_replace('&quot;', '“', $row->$fieldName);
                    $output .= isset($row->$fieldName) ? str_replace(array('"', '&nbsp;', '&gt;'), array('“', ' ', '>'), htmlSpecialString(strip_tags((string)$row->$fieldName, '<img>'))) : '';
                    $output .= '","';
                }
                $output .= '"' . "\n";
            }
            if($this->post->kind == 'task' && $this->config->vision != 'lite') $output .= $this->lang->file->childTaskTips;
        }
        $output = helper::replaceEmoji($output);
        $output = htmlspecialchars_decode(htmlspecialchars_decode($output, ENT_NOQUOTES), ENT_NOQUOTES);
        if(isset($_POST['encode']) && $this->post->encode != "utf-8") $output = helper::convertEncoding($output, 'utf-8', $this->post->encode . '//TRANSLIT');

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
                $fieldValue = isset($row->$fieldName) ? htmlSpecialString(strip_tags((string)$row->$fieldName, '<img>')) : '';
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
        $kind     = $this->post->kind;
        $fileName = $this->post->fileName;

        $output  = "<html xmlns='http://www.w3.org/1999/xhtml'>";
        $output .= "<head>";
        $output .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        $output .= "<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;} table th, table td {padding: 5px;}</style>";
        $output .= "<title>{$fileName}</title>";
        $output .= "</head>";

        $output .= "<body>";
        if($kind == 'task') $output .=  "<div style='color:red'>" . $this->lang->file->childTaskTips . '</div>';

        $output .= $this->fileZen->buildDownloadTable($fields, $rows, $kind, $rowspans, $colspans);
        $output .= "</body></html>";

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
            if(!helper::isAjaxRequest()) return print(js::confirm($this->lang->file->confirmDelete, $formUrl));
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->file->confirmDelete}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
        }
        else
        {
            $file = $this->file->getById($fileID);
            if(empty($file) || !$this->file->fileExists($file)) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->fileNotFound));

            $canDelete = $this->file->checkPriv($file);
            if($file->objectType == 'comment')
            {
                $action    = $this->loadModel('action')->fetchBaseInfo($file->objectID);
                $canDelete = ($this->app->user->admin || empty($action) || $action->actor == $this->app->user->account);
            }
            if(!$canDelete) return $this->send(array('result' => 'fail',  'message' => $this->lang->file->accessDenied));

            $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($fileID)->exec();
            $this->loadModel('action')->create($file->objectType, $file->objectID, 'deletedFile', '', $extra=$file->title);

            $this->fileZen->unlinkRealFile($file);

            /* Update test case version for test case synchronization. */
            if($file->objectType == 'testcase') $this->file->updateTestcaseVersion($file);
            if($file->objectType == 'charter')  $this->loadModel('charter')->updateFileByDelete($file);

            if(!helper::isAjaxRequest()) return print(js::reload('parent'));
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
    public function printFiles(array $files, string $fieldset, ?object $object = null, string $method = 'view', bool $showDelete = true, bool $showEdit = true)
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
            $result = $this->fileZen->updateFileName($fileID);
            if($result['result'] == 'fail') return $this->send($result);

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
            if(empty($file['extension']) or !in_array($file['extension'], $this->config->file->imageExtensions)) return print(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileFormat)));

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
        $templateID = (int)$this->file->saveExportTemplate($module);
        if(dao::isError())
        {
            $alert = '';
            $errors = dao::getError();
            foreach($errors as $errorContent) $alert .= is_array($errorContent) ? implode("\n", $errorContent) : $errorContent;
            return $this->send(array('alert' => $alert));
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
    public function read(int $fileID, int $stream = 0)
    {
        if(!($this->app->company->guest and $this->app->user->account == 'guest') and !$this->loadModel('user')->isLogon()) return print(js::locate($this->createLink('user', 'login')));

        $file = $this->file->getById($fileID);
        if(empty($file) or !$this->file->fileExists($file)) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->fileNotFound, 'load' => helper::createLink('my', 'index'), 'closeModal' => true));
        if(!$this->file->checkPriv($file)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->file->accessDenied, 'locate' => helper::createLink('my', 'index')), 'closeModal' => true));

        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        $mime = (isset($file->extension) and in_array($file->extension, $this->config->file->imageExtensions)) ? "image/{$file->extension}" : $this->config->file->mimes['default'];
        if(!$stream) helper::header('Content-type', $mime);

        $cacheMaxAge = 10 * 365 * 24 * 3600;
        helper::header('Cache-Control', 'private');
        helper::header('Pragma', 'cache');
        helper::header('Expires', gmdate('D, d M Y H:i:s', time() + $cacheMaxAge) . ' GMT');
        helper::header('Cache-Control', "max-age=$cacheMaxAge");

        if($stream)
        {
            echo base64_encode(file_get_contents($file->realPath));
        }
        else
        {
            $handle = fopen($file->realPath, "r");
            if($handle)
            {
                while(!feof($handle)) echo fgets($handle);
                fclose($handle);
            }
        }
    }

    /**
     * Query the file by id or gid.
     *
     * @param  int|string   $fileID
     * @param  string       $objectType  deprecated, only suggest to use id or gid
     * @param  int          $objectID    deprecated, only suggest to use id or gid
     * @param  string       $title       deprecated, only suggest to use id or gid
     * @param  string       $extra       deprecated, only suggest to use id or gid
     * @param  int          $stream      deprecated, only suggest to use id or gid
     * @access public
     * @return void
     */
    public function ajaxQuery(int|string $fileID, string $objectType = '', int $objectID = 0, string $title = '', string $extra = '', int $stream = 0)
    {
        if(!empty($fileID))
        {
            if(is_string($fileID) && !is_numeric($fileID))
            {
                $gid = base64_decode($fileID);
                if(strpos($gid, 'g-') === 0) $gid = substr($gid, 2);
                $file = $this->file->getByGid($gid);
            }
            else
            {
                $file = $this->file->getById((int)$fileID);
            }
            if($file)
            {
                if($this->viewType === 'json')
                {
                    echo json_encode(array('result' => 'success', 'data' => array('id' => $file->id, 'title' => $file->title, 'extension' => $file->extension, 'size' => $file->realPath, 'gid' => $file->gid, 'addedBy' => $file->addedBy, 'addedDate' => $file->addedDate, 'objectType' => $file->objectType, 'objectID' => $file->objectID)));
                    return;
                }

                $fileID = $file->id;
                if($stream) return $this->fetch('file', 'read', "fileID=$fileID&stream=$stream");
                return $this->fetch('file', 'download', "fileID=$fileID");
            }
            http_response_code(404);
            $this->sendError(404, '404 Not found');
        }

        if(!empty($title)) $title = urldecode(base64_decode($title));
        $file = $this->file->query($objectType, $objectID, $title, $extra);
        if(empty($file)) return $this->send(array('result' => 'fail', 'message' => $this->lang->file->fileNotFound, 'load' => helper::createLink('my', 'index'), 'closeModal' => true));

        if($this->viewType === 'json')
        {
            echo json_encode(array('result' => 'success', 'data' => array('id' => $file->id, 'title' => $file->title, 'extension' => $file->extension, 'size' => $file->realPath, 'gid' => $file->gid, 'addedBy' => $file->addedBy, 'addedDate' => $file->addedDate, 'objectType' => $file->objectType, 'objectID' => $file->objectID)));
            return;
        }

        $fileID = $file->id;
        if($stream) return $this->fetch('file', 'read', "fileID=$fileID&stream=$stream");
        return $this->fetch('file', 'download', "fileID=$fileID");
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
