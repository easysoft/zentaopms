<?php
/**
 * The model file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class fileModel extends model
{
    public $savePath  = '';
    public $webPath   = '';
    public $now       = 0;

    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->now = time();
        $this->setSavePath();
        $this->setWebPath();
    }

    /**
     * Get files of an object.
     *
     * @param  string   $objectType
     * @param  string   $objectID
     * @param  string   $extra
     * @access public
     * @return array
     */
    public function getByObject($objectType, $objectID, $extra = '')
    {
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->andWhere('extra')->ne('editor')
            ->beginIF($extra)->andWhere('extra')->in($extra)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy('id')
            ->fetchAll('id');

        foreach($files as $file)
        {
            if($objectType == 'traincourse' or  $objectType == 'traincontents')
            {
                $file->realPath = $this->app->getWwwRoot() . 'data/course/' . $file->pathname;
                $file->webPath  = 'data/course/' . $file->pathname;
                continue;
            }

            $this->setFileWebAndRealPaths($file);
        }

        return $files;
    }

    /**
     * Delete files by object.
     *
     * @param  string    $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function deleteByObject($objectType, $objectID)
    {
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        foreach($files as $file)
        {
            $this->dao->update(TABLE_FILE)->set('deleted')->eq(1)->where('id')->eq($file->id)->exec();
        }

        return true;
    }

    /**
     * Get info of a file.
     *
     * @param  int    $fileID
     * @access public
     * @return object
     */
    public function getById($fileID)
    {
        $file = $this->dao->findById($fileID)->from(TABLE_FILE)->fetch();
        if(empty($file)) return false;

        if($file->objectType == 'traincourse' or $file->objectType == 'traincontents')
        {
            $file->realPath = $this->app->getWwwRoot() . 'data/course/' . $file->pathname;
            $file->webPath  = 'data/course/' . $file->pathname;

            return $file;
        }

        $this->setFileWebAndRealPaths($file);

        return $file;
    }

    /**
     * Get files by ID list.
     *
     * @param  int    $fileIdList
     * @access public
     * @return array
     */
    public function getByIdList($fileIdList)
    {
        if(empty($fileIdList)) return array();

        $files = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($fileIdList)->orderBy('id')->fetchAll('id');

        foreach($files as $file)
        {
            if($file->objectType == 'traincourse' or $file->objectType == 'traincontents')
            {
                $file->realPath = $this->app->getWwwRoot() . 'data/course/' . $file->pathname;
                $file->webPath  = 'data/course/' . $file->pathname;
                continue;
            }

            $this->setFileWebAndRealPaths($file);
        }

        return $files;
    }

    /**
     * Save upload.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $extra
     * @param  string $filesName
     * @param  string $labelsName
     * @access public
     * @return array
     */
    public function saveUpload($objectType = '', $objectID = 0, $extra = '', $filesName = 'files', $labelsName = 'labels')
    {
        $fileTitles = array();
        $now        = helper::today();
        $files      = $this->getUpload($filesName, $labelsName);

        foreach($files as $id => $file)
        {
            if($file['size'] == 0) continue;
            if(!move_uploaded_file($file['tmpname'], $this->savePath . $this->getSaveName($file['pathname']))) return false;

            $file = $this->compressImage($file);

            $file['objectType'] = $objectType;
            $file['objectID']   = $objectID;
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = $now;
            $file['extra']      = $extra;
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileTitles[$this->dao->lastInsertId()] = $file['title'];
        }
        return $fileTitles;
    }

    /**
     * Save a file.
     *
     * @param  object $file
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $extra
     * @param  string $filesName
     * @param  string $labelsName
     * @access public
     * @return array
     */
    public function saveAFile($file, $objectType = '', $objectID = 0, $extra = '', $filesName = 'files', $labelsName = 'labels')
    {
        $fileTitle  = new stdclass();
        $now        = helper::today();

        if($file['size'] == 0) return array();
        if(!move_uploaded_file($file['tmpname'], $this->savePath . $this->getSaveName($file['pathname']))) return false;

        $file = $this->compressImage($file);

        $file['objectType'] = $objectType;
        $file['objectID']   = $objectID;
        $file['addedBy']    = $this->app->user->account;
        $file['addedDate']  = $now;
        $file['extra']      = $extra;
        unset($file['tmpname']);
        $this->dao->insert(TABLE_FILE)->data($file)->exec();
        $fileTitle->id    = $this->dao->lastInsertId();
        $fileTitle->title = $file['title'];

        return $fileTitle;
    }

    /**
     * Get counts of uploaded files.
     *
     * @access public
     * @return int
     */
    public function getCount()
    {
        return count($this->getUpload());
    }

    /**
     * Get info of uploaded files.
     *
     * @param  string $htmlTagName
     * @param  string $labelsName
     * @access public
     * @return array
     */
    public function getUpload($htmlTagName = 'files', $labelsName = 'labels')
    {
        $files = array();
        if(!isset($_FILES[$htmlTagName])) return $files;

        if(!is_array($_FILES[$htmlTagName]['error']) and $_FILES[$htmlTagName]['error'] != 0) return $_FILES[$htmlTagName];

        $this->app->loadClass('purifier', true);
        $config   = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);

        /* If the file var name is an array. */
        if(is_array($_FILES[$htmlTagName]['name']))
        {
            extract($_FILES[$htmlTagName]);
            foreach($name as $id => $filename)
            {
                if(empty($filename)) continue;
                if(!validater::checkFileName($filename)) continue;

                $title             = isset($_POST[$labelsName][$id]) ? $_POST[$labelsName][$id] : '';
                $file['extension'] = $this->getExtension($filename);
                $file['pathname']  = $this->setPathName($id, $file['extension']);
                $file['title']     = (!empty($title) and $title != $filename) ? htmlSpecialString($title) : $filename;
                $file['title']     = $purifier->purify($file['title']);
                $file['size']      = $size[$id];
                $file['tmpname']   = $tmp_name[$id];
                $files[] = $file;
            }
        }
        else
        {
            if(empty($_FILES[$htmlTagName]['name'])) return $files;
            extract($_FILES[$htmlTagName]);
            if(!validater::checkFileName($name)) return array();;
            $title             = isset($_POST[$labelsName][0]) ? $_POST[$labelsName][0] : '';
            $file['extension'] = $this->getExtension($name);
            $file['pathname']  = $this->setPathName(0, $file['extension']);
            $file['title']     = (!empty($title) and $title != $name) ? htmlSpecialString($title) : $name;
            $file['title']     = $purifier->purify($file['title']);
            $file['size']      = $size;
            $file['tmpname']   = $tmp_name;
            return array($file);
        }
        return $files;
    }

    /**
     * get uploaded file from zui.uploader.
     *
     * @param  string $htmlTagName
     * @param  string $name
     * @access public
     * @return array
     */
    public function getUploadFile($htmlTagName = 'file', $name = '')
    {
        if(!isset($_FILES[$htmlTagName]) || empty($_FILES[$htmlTagName]['name'])) return;

        $this->app->loadClass('purifier', true);
        $config   = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);

        extract($_FILES[$htmlTagName]);
        if(!validater::checkFileName($name)) return array();
        if(empty($name) && $this->post->name) $name = $this->post->name;

        $file = array();
        $file['id'] = 0;
        $file['extension'] = $this->getExtension($name);
        $file['title']     = !empty($_POST['label']) ? htmlSpecialString($_POST['label']) : substr($name, 0, strpos($name, $file['extension']) - 1);
        $file['title']     = $purifier->purify($file['title']);
        $file['size']      = $_POST['size'];
        $file['tmpname']   = $tmp_name;
        $file['uuid']      = $_POST['uuid'];
        $file['pathname']  = $this->setPathName(0, $file['extension']);
        $file['chunkpath'] = 'chunks' . DS .'f_' . $file['uuid'] . '.' . $file['extension'] . '.part';
        $file['chunks']    = isset($_POST['chunks']) ? intval($_POST['chunks']) : 0;
        $file['chunk']     = isset($_POST['chunk'])  ? intval($_POST['chunk'])  : 0;

        /* Fix for build uuid like '../../'. */
        if(!preg_match('/[a-z0-9_]/i', $file['uuid'])) return false;

        if(stripos($this->config->file->allowed, ',' . $file['extension'] . ',') === false)
        {
            $file['pathname'] = $file['pathname'] . '.notAllowed';
        }

        return $file;
    }

    /**
     * Save uploaded file from zui.uploader.
     *
     * @param  int    $file
     * @param  int    $uid
     * @access public
     * @return array|bool
     */
    public function saveUploadFile($file, $uid)
    {
        $uploadFile = array();

        $tmpFilePath = $this->app->getTmpRoot() . 'uploadfiles/';
        if(!is_dir($tmpFilePath)) mkdir($tmpFilePath, 0777, true);

        $tmpFileSavePath = $tmpFilePath . $uid . '/';
        if(!is_dir($tmpFileSavePath)) mkdir($tmpFileSavePath);

        $fileName = basename($file['pathname']);
        $fileName = strpos($fileName, '.') === false ? $fileName : substr($fileName, 0, strpos($fileName, '.'));
        $file['realpath'] = $tmpFileSavePath . $fileName;

        if($file['chunks'] > 1)
        {
            $tmpFileChunkPath = $tmpFilePath . $file['chunkpath'];
            if(!file_exists($tmpFileChunkPath)) mkdir(dirname($tmpFileChunkPath));

            if($file['chunk'] > 0)
            {
                $fileChunk    = fopen($tmpFileChunkPath, 'a+b');
                $tmpChunkFile = fopen($file['tmpname'], 'rb');
                while($buff = fread($tmpChunkFile, 4069))
                {
                    fwrite($fileChunk, $buff);
                }
                fclose($fileChunk);
                fclose($tmpChunkFile);
            }
            else
            {
                if(!move_uploaded_file($file['tmpname'], $tmpFileChunkPath)) return false;
            }

            if($file['chunk'] == ($file['chunks'] - 1))
            {
                rename($tmpFileChunkPath, $file['realpath']);

                $uploadFile['extension'] = $file['extension'];
                $uploadFile['pathname']  = $file['pathname'];
                $uploadFile['title']     = $file['title'];
                $uploadFile['realpath']  = $file['realpath'];
                $uploadFile['size']      = $file['size'];
            }
        }
        else
        {
            if(!move_uploaded_file($file['tmpname'], $file['realpath'])) return false;

            $uploadFile['extension'] = $file['extension'];
            $uploadFile['pathname']  = $file['pathname'];
            $uploadFile['title']     = $file['title'];
            $uploadFile['realpath']  = $file['realpath'];
            $uploadFile['size']      = $file['size'];
        }

        return $uploadFile;
    }

    /**
     * Get extension of a file.
     *
     * @param  string    $filename
     * @access public
     * @return string
     */
    public function getExtension($filename)
    {
        $extension = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
        if($extension and strpos($extension, '::') !== false) $extension = substr($extension, 0, strpos($extension, '::'));

        if(empty($extension) or stripos(",{$this->config->file->dangers},", ",{$extension},") !== false) return 'txt';
        if(empty($extension) or stripos(",{$this->config->file->allowed},", ",{$extension},") === false) return 'txt';
        if($extension == 'php') return 'txt';
        return $extension;
    }

    /**
     * Get save name.
     *
     * @param  string    $pathName
     * @access public
     * @return string
     */
    public function getSaveName($pathName)
    {
        $saveName = strpos($pathName, '.') === false ? $pathName : substr($pathName, 0, strpos($pathName, '.'));
        return $saveName;
    }

    /**
     * Get real path name.
     *
     * @param  string    $pathName
     * @access public
     * @return string
     */
    public function getRealPathName($pathName)
    {
        $realPath = $this->savePath . $pathName;
        if(file_exists($realPath)) return $pathName;

        return $this->getSaveName($pathName);
    }

    /**
     * Get export tpl.
     *
     * @param  string $module
     * @access public
     * @return object
     */
    public function getExportTemplate($module)
    {
        return $this->dao->select('id,title,content,public')->from(TABLE_USERTPL)
            ->where('type')->eq("export$module")
            ->andwhere('account', $markLeft = true)->eq($this->app->user->account)
            ->orWhere('public')->eq('1')
            ->markRight(1)
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * Get tmp import path.
     *
     * @access public
     * @return string
     */
    public function getPathOfImportedFile()
    {
        $path = $this->app->getTmpRoot() . 'import';
        if(!is_dir($path)) mkdir($path, 0755, true);

        return $path;
    }

    /**
     * Save export template.
     *
     * @param  string $module
     * @access public
     * @return int
     */
    public function saveExportTemplate($module)
    {
        $template = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('type', "export$module")
            ->join('content', ',')
            ->get();

        if($template->title == $this->lang->file->defaultTPL)
        {
            dao::$errors[] = sprintf($this->lang->error->unique, $this->lang->file->tplTitle, $this->lang->file->defaultTPL);
            return false;
        }

        $condition = "`type`='export$module' and account='{$this->app->user->account}'";
        $this->dao->insert(TABLE_USERTPL)->data($template)->batchCheck('title, content', 'notempty')->check('title', 'unique', $condition)->exec();
        return $this->dao->lastInsertId();
    }

    /**
     * Set path name of the uploaded file to be saved.
     *
     * @param  int    $fileID
     * @param  string $extension
     * @access public
     * @return string
     */
    public function setPathName($fileID, $extension)
    {
        $sessionID  = session_id();
        $randString = substr($sessionID, mt_rand(0, strlen($sessionID) - 5), 3);
        return date('Ym/dHis', $this->now) . $fileID . mt_rand(0, 10000) . $randString . '.' . $extension;
    }

    /**
     * Set save path.
     *
     * @access public
     * @return void
     */
    public function setSavePath()
    {
        $savePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . date('Ym/', $this->now);
        if(!file_exists($savePath))
        {
            @mkdir($savePath, 0777, true);
            touch($savePath . 'index.html');
        }
        $this->savePath = dirname($savePath) . '/';
    }

    /**
     * Set the web path of upload files.
     *
     * @access public
     * @return void
     */
    public function setWebPath()
    {
        $this->webPath = $this->app->getWebRoot() . "data/upload/{$this->app->company->id}/";
    }

    /**
     * Set paths: realPath and webPath.
     *
     * @param  object $file
     * @access public
     * @return void
     */
    public function setFileWebAndRealPaths(&$file)
    {
        $pathName       = $this->getRealPathName($file->pathname);
        $file->realPath = $this->savePath . $pathName;
        $file->webPath  = $this->webPath . $pathName;
    }

    /**
     * Insert the set image size code.
     *
     * @param  string    $content
     * @param  int       $maxSize
     * @access public
     * @return string
     */
    public function setImgSize($content, $maxSize = 0)
    {
        if(empty($content)) return $content;

        $isonlybody = isonlybody();
        unset($_GET['onlybody']);

        $readLinkReg = str_replace(array('%fileID%', '/', '.', '?'), array('[0-9]+', '\/', '\.', '\?'), helper::createLink('file', 'read', 'fileID=(%fileID%)', '\w+'));

        $content = preg_replace('/ src="(' . $readLinkReg . ')" /', ' onload="setImageSize(this,' . $maxSize . ')" src="$1" ', $content);
        $content = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' onload="setImageSize(this,' . $maxSize . ')" src="' . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $content);

        if($isonlybody) $_GET['onlybody'] = 'yes';

        return str_replace(' src="data/upload', ' onload="setImageSize(this,' . $maxSize . ')" src="data/upload', $content);
    }

    /**
     * Check file exists or not.
     *
     * @param  object $file
     * @access public
     * @return bool
     */
    public function fileExists($file)
    {
        return file_exists($file->realPath);
    }

    /**
     * Unlink file.
     *
     * @param  object $file
     * @access public
     * @return bool|null
     */
    public function unlinkFile($file)
    {
        return @unlink($file->realPath);
    }

    /**
     * Replace a file.
     *
     * @access public
     * @return bool
     */
    public function replaceFile($fileID, $postName = 'upFile')
    {
        if($files = $this->getUpload($postName))
        {
            $file = $files[0];
            $filePath = $this->dao->select('pathname')->from(TABLE_FILE)->where('id')->eq($fileID)->fetch();
            $pathName = $filePath->pathname;
            $realPathName = $this->savePath . $this->getRealPathName($pathName);
            if(!is_dir(dirname($realPathName))) mkdir(dirname($realPathName));
            move_uploaded_file($file['tmpname'], $realPathName);

            $file['pathname'] = $pathName;
            $file = $this->compressImage($file);

            $fileInfo = new stdclass();
            $fileInfo->addedBy   = $this->app->user->account;
            $fileInfo->addedDate = helper::now();
            $fileInfo->size      = $file['size'];
            $this->dao->update(TABLE_FILE)->data($fileInfo)->where('id')->eq($fileID)->exec();
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Check file priv.
     *
     * @param  int    $file
     * @access public
     * @return bool
     */
    public function checkPriv($file)
    {
        $objectType = $file->objectType;
        $objectID   = $file->objectID;
        $table      = $this->config->objectTables[$objectType];

        if(!$table) return true;

        $objectGroup = array(
            'design'      => 'project',
            'issue'       => 'project',
            'risk'        => 'project',
            'story'       => 'product',
            'requirement' => 'product',
            'bug'         => 'product',
            'testcase'    => 'product',
            'testtask'    => 'product',
            'task'        => 'execution',
            'build'       => 'execution'
        );

        if(isset($objectGroup[$objectType]))
        {
            $groupName = $objectGroup[$objectType];
            $groupID   = $this->dao->findByID($objectID)->from($table)->fetch($groupName);
            return $this->loadModel($groupName)->checkPriv($groupID);
        }

        if($objectType == 'release')
        {
            $projectID = $this->dao->findByID($objectID)->from($table)->fetch('project');
            if((isset($projectID) and $projectID   > 0)) return $this->loadModel('project')->checkPriv($projectID);

            $productID= $this->dao->findByID($objectID)->from(TABLE_RELEASE)->fetch('product');
            return $this->loadModel('product')->checkPriv($productID);
        }

        if($objectType == 'doc')
        {
            $doc = $this->dao->findById($objectID)->from(TABLE_DOC)->fetch();
            return $this->loadModel('doc')->checkPrivDoc($doc);
        }

        if($objectType == 'feedback')
        {
            $productID     = $this->dao->findById($objectID)->from(TABLE_FEEDBACK)->fetch('product');
            $grantProducts = $this->loadModel('feedback')->getGrantProducts();
            return in_array($productID, array_keys($grantProducts));
        }

        return true;
    }

	/**
     * Compress image to config configured size.
     *
     * @param  string  $rawImage
     * @param  string  $target
     * @param  int     $x
     * @param  int     $y
     * @param  int     $width
     * @param  int     $height
     * @param  int     $resizeWidth
     * @param  int     $resizeHeight
     * @access public
     * @return void
     */
    public function cropImage($rawImage, $target, $x, $y, $width, $height, $resizeWidth = 0, $resizeHeight = 0)
    {
        $this->app->loadClass('phpthumb', true);

        if(!extension_loaded('gd')) return false;

        $croper = phpThumbFactory::create($rawImage);
        if($resizeWidth > 0) $croper->resize($resizeWidth, $resizeHeight);
        $croper->crop($x, $y, $width, $height);
        $croper->save($target);
    }

    /**
     * Paste image in kindeditor at firefox and chrome.
     *
     * @param  string    $data
     * @param  string    $uid
     * @param  bool      $safe
     * @access public
     * @return string
     */
    public function pasteImage($data, $uid = '', $safe = false)
    {
        if(empty($data)) return '';

        $dataLength = strlen($data);
        if(ini_get('pcre.backtrack_limit') < $dataLength) ini_set('pcre.backtrack_limit', $dataLength);
        preg_match_all('/<img src="(data:image\/(\S+);base64,(\S+))".*\/>/U', $data, $out);
        if($out[3])
        {
            foreach($out[3] as $key => $base64Image)
            {
                $base64Image = str_replace('\"', '"', $base64Image);
                $extension   = strtolower($out[2][$key]);
                if(!in_array($extension, $this->config->file->imageExtensions)) helper::end();
                $imageData = base64_decode($base64Image);

                $file['extension'] = $extension;
                $file['pathname']  = $this->setPathName($key, $file['extension']);
                $file['size']      = strlen($imageData);
                $file['addedBy']   = $this->app->user->account;
                $file['addedDate'] = helper::today();
                $file['title']     = str_replace(".$extension", '', basename($file['pathname']));

                file_put_contents($this->savePath . $this->getSaveName($file['pathname']), $imageData);
                $this->dao->insert(TABLE_FILE)->data($file)->exec();
                $fileID = $this->dao->lastInsertID();
                if($uid) $_SESSION['album'][$uid][] = $fileID;

                $data = str_replace($out[1][$key], helper::createLink('file', 'read', "fileID=$fileID", $file['extension']), $data);
            }
        }
        elseif($safe)
        {
            $data = fixer::stripDataTags(rawurldecode($data));
        }

        return $data;
    }

    /**
     * Parse CSV.
     *
     * @param  string    $fileName
     * @access public
     * @return array
     */
    public function parseCSV($fileName)
    {
        /* Parse file only in zentao. */
        if(strpos($fileName, $this->app->getBasePath()) !== 0) return array();

        $content = file_get_contents($fileName);
        /* Fix bug #890. */
        $content = str_replace(array("\r\n", "\r"), "\n", $content);
        $lines   = explode("\n", $content);

        $col  = -1;
        $row  = 0;
        $data = array();
        foreach($lines as $line)
        {
            $markNum = substr_count($line, '"') - substr_count($line, '\"');
            if(substr($line, -1) != ',' and (($markNum % 2 == 1 and $col != -1) or ($markNum % 2 == 0 and substr($line, -2) != ',"' and $col == -1))) $line .= ',';
            $line = str_replace(',"",', ',,', $line);
            $line = str_replace(',"",', ',,', $line);
            $line = preg_replace_callback('/(\"{2,})(\,+)/U', array($this, 'removeInterference'), $line);
            $line = str_replace('""', '"', $line);

            /* if only one column then line is the data. */
            if(strpos($line, ',') === false and isset($line[0]) and $line[0] != '"' and $col == -1)
            {
                $data[$row][0] = trim($line, '"');
            }
            else
            {
                /* if col is not -1, then the data of column is not end. */
                if($col != -1)
                {
                    $pos = strpos($line, '",');
                    if($pos === false)
                    {
                        $data[$row][$col] .= "\n" . $line;
                        $data[$row][$col] = str_replace('&comma;', ',', $data[$row][$col]);
                        continue;
                    }
                    else
                    {
                        $data[$row][$col] .= "\n" . substr($line, 0, $pos);
                        $data[$row][$col] = trim(str_replace('&comma;', ',', $data[$row][$col]));
                        $line = substr($line, $pos + 2);
                        $col++;
                    }
                }

                if($col == -1) $col = 0;
                /* explode cols with delimiter. */
                while($line)
                {
                    /* the cell has '"', the delimiter is '",'. */
                    if($line[0] == '"')
                    {
                        $pos  = strpos($line, '",');
                        if($pos === false)
                        {
                            $data[$row][$col] = substr($line, 1);
                            /* if line is not empty, then the data of cell is not end. */
                            if(strlen($line) >= 1) continue 2;
                            $line = '';
                        }
                        else
                        {
                            $data[$row][$col] = substr($line, 1, $pos - 1);
                            $line = substr($line, $pos + 2);
                        }
                        $data[$row][$col] = str_replace('&comma;', ',', $data[$row][$col]);
                    }
                    else
                    {
                        /* the delimiter default is ','. */
                        $pos = strpos($line, ',');
                        /* if line is not delimiter, then line is the data of cell. */
                        if($pos === false)
                        {
                            $data[$row][$col] = $line;
                            $line = '';
                        }
                        else
                        {
                            $data[$row][$col] = substr($line, 0, $pos);
                            $line = substr($line, $pos + 1);
                        }
                    }

                    $data[$row][$col] = trim(str_replace('&comma;', ',', $data[$row][$col]));
                    $col++;
                }
            }
            $row ++;
            $col = -1;
        }

        return $data;
    }

    /**
     * Remove interference for parse csv.
     *
     * @param  array    $matchs
     * @access private
     * @return string
     */
    private function removeInterference($matchs)
    {
        if(strlen($matchs[1]) % 2 == 1) return $matchs[1] . $matchs[2];
        return str_replace('""', '"', $matchs[1]) . str_replace(',', '&comma;', $matchs[2]);
    }

    /**
     * Process editor.
     *
     * @param  object    $data
     * @param  string    $editorList
     * @access public
     * @return object
     */
    public function processImgURL($data, $editorList, $uid = '')
    {
        if(is_string($editorList)) $editorList = explode(',', str_replace(' ', '', $editorList));
        if(empty($editorList)) return $data;

        $readLinkReg = helper::createLink('file', 'read', 'fileID=(%fileID%)', '(%viewType%)');
        $readLinkReg = str_replace(array('%fileID%', '%viewType%', '?', '/'), array('[0-9]+', '\w+', '\?', '\/'), $readLinkReg);
        $imageIdList = array();
        foreach($editorList as $editorID)
        {
            if(empty($editorID) or empty($data->$editorID)) continue;

            $imgURL = $this->config->requestType == 'GET' ? '{$2.$1}' : '{$1.$2}';

            $content = $this->pasteImage($data->$editorID, $uid);
            if($content) $data->$editorID = $content;

            $data->$editorID = preg_replace("/ src=\"$readLinkReg\" /", ' src="' . $imgURL . '" ', $data->$editorID);
            $data->$editorID = preg_replace("/ src=\"" . htmlSpecialString($readLinkReg) . "\" /", ' src="' . $imgURL . '" ', $data->$editorID);

            preg_match_all('/ src="{([0-9]+)\.\w+}"/', $data->$editorID, $matchs);
            if($matchs[1])
            {
                foreach($matchs[1] as $imageID) $imageIdList[$imageID] = $imageID;
            }
        }

        if(!empty($_SESSION['album'][$uid]))
        {
            foreach($_SESSION['album'][$uid] as $i => $imageID)
            {
                if(isset($imageIdList[$imageID])) $_SESSION['album']['used'][$uid][$imageID] = $imageID;
            }
        }
        return $data;
    }

    /**
     * Compress image
     *
     * @param  array    $file
     * @access public
     * @return array
     */
    public function compressImage($file)
    {
        if(!extension_loaded('gd') or !function_exists('imagecreatefromjpeg')) return $file;

        $pathName    = $file['pathname'];
        $fileName    = $this->savePath . $this->getSaveName($pathName);
        $suffix      = $file['extension'];
        $lowerSuffix = strtolower($suffix);

        if(!in_array($lowerSuffix, $this->config->file->image2Compress)) return $file;

        $quality        = 85;
        $newSuffix      = '.jpg';
        $compressedName = str_replace($suffix, $newSuffix, $pathName);

        $res  = $lowerSuffix == '.bmp' ? $this->imagecreatefrombmp($fileName) : imagecreatefromjpeg($fileName);
        imagejpeg($res, $fileName, $quality);

        $file['pathname']  = $compressedName;
        $file['extension'] = ltrim($newSuffix, '.');
        $file['size']      = filesize($fileName);
        return $file;
    }

    /**
     * Read 24bit BMP files
     * Author: de77
     * Licence: MIT
     * Webpage: de77.com
     * Version: 07.02.2010
     * Source : https://github.com/acustodioo/pic/blob/master/imagecreatefrombmp.function.php
     *
     * @param  string    $filename
     * @access public
     * @return resource
     */
    public function imagecreatefrombmp($filename)
    {
        $f = fopen($filename, "rb");

        //read header
        $header = fread($f, 54);
        $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
            'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
            'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

        if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
            return false;

        if ($header['bits_per_pixel'] != 24)
            return false;

        $wid2 = ceil((3 * $header['width']) / 4) * 4;

        $wid = $header['width'];
        $hei = $header['height'];

        $img = imagecreatetruecolor($header['width'], $header['height']);

        //read pixels
        for($y = $hei - 1; $y >= 0; $y--)
        {
            $row = fread($f, $wid2);
            $pixels = str_split($row, 3);

            for ($x = 0; $x < $wid; $x++) {
                imagesetpixel($img, $x, $y, $this->dwordize($pixels[$x]));
            }
        }
        fclose($f);
        return $img;
    }

    /**
     * Dwordize for imagecreatefrombmp
     *
     * @param  streing $str
     * @access private
     * @return int
     */
    private function dwordize($str)
    {
        $a = ord($str[0]);
        $b = ord($str[1]);
        $c = ord($str[2]);
        return $c * 256 * 256 + $b * 256 + $a;
    }

    /**
     * Update objectID.
     *
     * @param  int    $uid
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return bool
     */
    public function updateObjectID($uid, $objectID, $objectType)
    {
        if(empty($uid)) return true;

        $data = new stdclass();
        $data->objectID   = $objectID;
        $data->objectType = $objectType;
        if(!defined('RUN_MODE') OR RUN_MODE != 'api') $data->extra = 'editor';
        if(isset($_SESSION['album']['used'][$uid]) and $_SESSION['album']['used'][$uid])
        {
            $this->dao->update(TABLE_FILE)->data($data)->where('id')->in($_SESSION['album']['used'][$uid])->exec();
            return !dao::isError();
        }
    }

    /**
     * Revert real src.
     *
     * @param  object    $data
     * @param  string    $fields
     * @access public
     * @return object
     */
    public function replaceImgURL($data, $fields)
    {
        if(is_string($fields)) $fields = explode(',', str_replace(' ', '', $fields));

        $isonlybody = isonlybody();
        unset($_GET['onlybody']);

        foreach($fields as $field)
        {
            if(empty($field) or empty($data->$field)) continue;
            $data->$field = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $data->$field);

            /* Convert plain text URLs into HTML hyperlinks. */
            $moduleName = $this->app->getModuleName();
            $methodName = $this->app->getMethodName();
            if(isset($this->config->file->convertURL['common'][$methodName]) or isset($this->config->file->convertURL[$moduleName][$methodName]))
            {
                $fieldData = $data->$field;
                preg_match_all('/(<a[^>]*>.*<\/a>)/Ui', $fieldData, $aTags);
                preg_match_all('/(<img[^>]*>)/i', $fieldData, $imgTags);
                preg_match_all('/(<iframe[^>]*>[^<]*<\/iframe>)/i', $fieldData, $iframeTags);
                preg_match_all('/(<pre[^>]*>.*<\/pre>)/sUi', $fieldData, $preTags);

                foreach($aTags[0] as $i => $aTag) $fieldData = str_replace($aTag, "<A_{$i}>", $fieldData);
                foreach($imgTags[0] as $i => $imgTag) $fieldData = str_replace($imgTag, "<IMG_{$i}>", $fieldData);
                foreach($iframeTags[0] as $i => $iframeTag) $fieldData = str_replace($iframeTag, "<IFRAME_{$i}>", $fieldData);
                foreach($preTags[0] as $i => $preTag) $fieldData = str_replace($preTag, "<PRE_{$i}>", $fieldData);

                $fieldData = preg_replace('/(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|\&|-|%|;)+)/i', "<a href='\\0' target='_blank'>\\0</a>", $fieldData);

                foreach($aTags[0] as $i => $aTag) $fieldData = str_replace("<A_{$i}>", $aTag, $fieldData);
                foreach($imgTags[0] as $i => $imgTag) $fieldData = str_replace("<IMG_{$i}>", $imgTag, $fieldData);
                foreach($iframeTags[0] as $i => $iframeTag) $fieldData = str_replace("<IFRAME_{$i}>", $iframeTag, $fieldData);
                foreach($preTags[0] as $i => $preTag) $fieldData = str_replace("<PRE_{$i}>", $preTag, $fieldData);

                $data->$field = $fieldData;
            }
        }

        if($isonlybody) $_GET['onlybody'] = 'yes';
        return $data;
    }

    /**
     * Auto delete useless image.
     *
     * @param  int    $uid
     * @access public
     * @return void
     */
    public function autoDelete($uid)
    {
        if(!empty($_SESSION['album'][$uid]))
        {
            foreach($_SESSION['album'][$uid] as $i => $imageID)
            {
                if(!isset($_SESSION['album']['used'][$uid][$imageID]))
                {
                    $file = $this->getById($imageID);
                    $this->dao->delete()->from(TABLE_FILE)->where('id')->eq($imageID)->exec();
                    $this->unlinkFile($file);
                }
            }
            unset($_SESSION['album'][$uid]);
        }
    }

    /**
     * Send the download header to the client.
     *
     * @param  string    $fileName
     * @param  string    $fileType
     * @param  string    $content
     * @access public
     * @return void
     */
    public function sendDownHeader($fileName, $fileType, $content, $type = 'content')
    {
        /* Clean the ob content to make sure no space or utf-8 bom output. */
        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
        setcookie('downloading', 1, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);

        /* Only download upload file that is in zentao. */
        if($type == 'file' and stripos($content, $this->savePath) !== 0) helper::end();

        /* Append the extension name auto. */
        $extension = $fileType ? '.' . $fileType : '';
        if(strpos($fileName, $extension) === false) $fileName .= $extension;

        /* urlencode the filename for ie. */
        if(strpos($this->server->http_user_agent, 'MSIE') !== false or strpos($this->server->http_user_agent, 'Trident') !== false or strpos($this->server->http_user_agent, 'Edge') !== false) $fileName = urlencode($fileName);

        /* Judge the content type. */
        $mimes = $this->config->file->mimes;
        $contentType = isset($mimes[$fileType]) ? $mimes[$fileType] : $mimes['default'];

        header("Content-type: $contentType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        if($type == 'content') helper::end($content);
        if($type == 'file' and file_exists($content))
        {
            if(stripos($content, $this->app->getBasePath()) !== 0) helper::end();

            set_time_limit(0);
            $chunkSize = 10 * 1024 * 1024;
            $handle    = fopen($content, "r");
            while(!feof($handle)) echo fread($handle, $chunkSize);
            fclose($handle);
            helper::end();
        }
    }

    /**
     * Get image size.
     *
     * @access public
     * @param  object $file
     * @return array
     */
    public function getImageSize($file)
    {
        if($this->config->file->storageType == 'fs')
        {
            return file_exists($file->realPath) ? getimagesize($file->realPath) : array(0, 0, $file->extension);
        }
        else if($this->config->file->storageType == 's3')
        {
            $this->app->loadClass('ossclient', true);

            $config    = $this->config->file;
            $ossClient = new ossclient($config->accessKeyId, $config->accessKeySecret, $config->endpoint, $config->bucket);
            $info      = $ossClient->getImageInfo($file->pathname);

            return array($info->ImageWidth->value, $info->ImageHeight->value, 'img');
        }
    }

    /**
     * Get file pairs.
     *
     * @param  int    $IDs
     * @param  string $value
     * @access public
     * @return void
     */
    public function getPairs($IDs, $value = 'title')
    {
        return $this->dao->select("id,$value")->from(TABLE_FILE)
            ->where('id')->in($IDs)
            ->fetchPairs();
    }

    /**
     * Update test case version.
     *
     * @param  object $file
     * @access public
     * @return void
     */
    public function updateTestcaseVersion($file)
    {
        $oldCase   = $this->loadModel('testcase')->getByID($file->objectID);
        $isLibCase = ($oldCase->lib and empty($oldCase->product));
        if($isLibCase)
        {
            $fromcaseVersion  = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($file->objectID)->fetch('fromCaseVersion');
            $fromcaseVersion += 1;
            $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($file->objectID)->exec();
        }
    }

    /**
     * Process file info for object.
     *
     * @param  string $objectType
     * @param  object $oldObject
     * @param  object $newObject
     * @param  string $extra
     * @param  string $filesName
     * @param  string $labelsName
     * @access public
     * @return void
     */
    public function processFile4Object($objectType, $oldObject, $newObject, $extra = '', $filesName = 'files', $labelsName = 'labels')
    {
        $oldFiles    = empty($oldObject->files) ? '' : join(',', array_keys($oldObject->files));
        $deleteFiles = $newObject->deleteFiles;
        if(!empty($deleteFiles))
        {
            $this->dao->delete()->from(TABLE_FILE)->where('id')->in($deleteFiles)->exec();
            foreach($deleteFiles as $fileID)
            {
                $this->unlinkFile($oldObject->files[$fileID]);
                $oldFiles = empty($oldFiles) ? '' : trim(str_replace(",$fileID,", ',', ",$oldFiles,"), ',');
            }
        }

        $this->updateObjectID($this->post->uid, $oldObject->id, $objectType);
        $addedFiles = $this->saveUpload($objectType, $oldObject->id, $extra, $filesName, $labelsName);
        $addedFiles = empty($addedFiles) ? '' : ',' . join(',', array_keys($addedFiles));

        $newObject->files = trim($oldFiles . $addedFiles, ',');
        $oldObject->files = join(',', array_keys($oldObject->files));
    }

    /**
     * Get last modified timestamp of file.
     *
     * @param  object $file
     * @access public
     * @return int
     */
    public function fileMTime($file)
    {
        return filemtime($file->realPath);
    }

    /**
     * Get file size.
     *
     * @param  object $file
     * @access public
     * @return int
     */
    public function fileSize($file)
    {
        return filesize($file->realPath);
    }

    /**
     * Save file to local storage temporarily.
     *
     * @param  object $file
     * @access public
     * @return string
     */
    public function saveAsTempFile($file)
    {
        /* If the storage type is local, do nothing. */
        if($this->config->file->storageType == 'fs') return $file->realPath;
    }
}
