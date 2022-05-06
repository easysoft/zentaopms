<?php
class fileTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('file');
    }

    public function __constructTest()
    {
        $objects = $this->objectModel->construct();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get files of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getByObjectTest($objectType, $objectID, $extra = '')
    {
        $objects = $this->objectModel->getByObject($objectType, $objectID, $extra = '');
        foreach($objects as $object)
        {
            if(isset($object->webPath)) $object->webPath = substr($object->webPath, strpos($object->webPath, '/data/upload'));
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get info of a file.
     *
     * @param  int    $fileID
     * @access public
     * @return object
     */
    public function getByIdTest($fileID)
    {
        $object = $this->objectModel->getById($fileID);
        if(isset($object->webPath)) $object->webPath = substr($object->webPath, strpos($object->webPath, '/data/upload'));

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function saveUploadTest($objectType = '', $objectID = '', $extra = '', $filesName = 'files', $labelsName = 'labels')
    {
        $objects = $this->objectModel->saveUpload($objectType = '', $objectID = '', $extra = '', $filesName = 'files', $labelsName = 'labels');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get counts of uploaded files.
     *
     * @param  array  $files
     * @param  array  $labels
     * @access public
     * @return int
     */
    public function getCountTest($files, $labels)
    {
        $_FILES['files'] = $files;
        $_POST['labels'] = $labels;

        $count = $this->objectModel->getCount();

        unset($_FILES['files']);
        unset($_POST['labels']);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * Test get info of uploaded files.
     *
     * @param  array $files
     * @param  array $labels
     * @access public
     * @return array
     */
    public function getUploadTest($files, $labels)
    {
        $_FILES['files'] = $files;
        $_POST['labels'] = $labels;

        $objects = $this->objectModel->getUpload($htmlTagName = 'files', $labelsName = 'labels');

        unset($_FILES['files']);
        unset($_POST['labels']);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get uploaded file from zui.uploader.
     *
     * @param  array  $image
     * @param  array  $files
     * @access public
     * @return object
     */
    public function getUploadFileTest($image, $files)
    {
        $_FILES['file'] = $files;

        foreach($image as $key => $value) $_POST[$key] = $value;

        $object = $this->objectModel->getUploadFile($htmlTagName = 'file');

        unset($_POST);
        unset($_FILES['file']);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function saveUploadFileTest($file, $uid)
    {
        $objects = $this->objectModel->saveUploadFile($file, $uid);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get extension of a file.
     *
     * @param  string $filename
     * @access public
     * @return string
     */
    public function getExtensionTest($filename)
    {
        $string = $this->objectModel->getExtension($filename);

        if(dao::isError()) return dao::getError();

        return $string;
    }

    public function getSaveNameTest($pathName)
    {
        $objects = $this->objectModel->getSaveName($pathName);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get real path name.
     *
     * @param  string $pathName
     * @access public
     * @return string
     */
    public function getRealPathNameTest($pathName)
    {
        $realpath = $this->objectModel->getRealPathName($pathName);

        if(dao::isError()) return dao::getError();

        return $realpath;
    }

    public function getExportTemplateTest($module)
    {
        $objects = $this->objectModel->getExportTemplate($module);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get tmp import path.
     *
     * @access public
     * @return string
     */
    public function getPathOfImportedFileTest()
    {
        $path = $this->objectModel->getPathOfImportedFile();
        $path = substr($path, strpos($path, 'tmp'));

        if(dao::isError()) return dao::getError();

        return $path;
    }

    public function saveExportTemplateTest($module)
    {
        $objects = $this->objectModel->saveExportTemplate($module);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setPathNameTest($fileID, $extension)
    {
        $objects = $this->objectModel->setPathName($fileID, $extension);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test set save path.
     *
     * @access public
     * @return string
     */
    public function setSavePathTest()
    {
        $this->objectModel->setSavePath();

        if(dao::isError()) return dao::getError();

        global $tester;
        $path = substr($tester->file->savePath, strpos($tester->file->savePath, '/www/'));
        return $path;
    }

    /**
     * Test set the web path of upload files.
     *
     * @access public
     * @return string
     */
    public function setWebPathTest()
    {
        $this->objectModel->setWebPath();


        if(dao::isError()) return dao::getError();

        global $tester;
        $path = substr($tester->file->webPath, strpos($tester->file->webPath, '/test/model/'));
        return $path;
    }

    public function setImgSizeTest($content, $maxSize = 0)
    {
        $objects = $this->objectModel->setImgSize($content, $maxSize = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function replaceFileTest($fileID, $postName = 'upFile')
    {
        $objects = $this->objectModel->replaceFile($fileID, $postName = 'upFile');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function cropImageTest($rawImage, $target, $x, $y, $width, $height, $resizeWidth = 0, $resizeHeight = 0)
    {
        $objects = $this->objectModel->cropImage($rawImage, $target, $x, $y, $width, $height, $resizeWidth = 0, $resizeHeight = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function pasteImageTest($data, $uid = '', $safe = false)
    {
        $objects = $this->objectModel->pasteImage($data, $uid = '', $safe = false);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseCSVTest($fileName)
    {
        $objects = $this->objectModel->parseCSV($fileName);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processImgURLTest($data, $editorList, $uid = '')
    {
        $objects = $this->objectModel->processImgURL($data, $editorList, $uid = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test compress image.
     *
     * @param  object $file
     * @access public
     * @return object
     */
    public function compressImageTest($file)
    {
        $object = $this->objectModel->compressImage($file);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function imagecreatefrombmpTest($filename)
    {
        $objects = $this->objectModel->imagecreatefrombmp($filename);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test update objectID.
     *
     * @param  int    $uid
     * @param  int    $objectID
     * @param  string $objectType
     * @param  array  $albums
     * @access public
     * @return array
     */
    public function updateObjectIDTest($uid, $objectID, $objectType, $albums)
    {
        $_SESSION['album'] = $albums;

        $this->objectModel->updateObjectID($uid, $objectID, $objectType);

        unset($_SESSION['album']);

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_FILE)->where('id')->in($albums['used'][$uid])->fetchAll('id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function replaceImgURLTest($data, $fields)
    {
        $objects = $this->objectModel->replaceImgURL($data, $fields);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sendDownHeaderTest($fileName, $fileType, $content, $type = 'content')
    {
        $objects = $this->objectModel->sendDownHeader($fileName, $fileType, $content, $type = 'content');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getImageSizeTest($file)
    {
        $objects = $this->objectModel->getImageSize($file);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get file pairs.
     *
     * @param  array  $IDs
     * @param  string $value
     * @access public
     * @return array
     */
    public function getPairsTest($IDs, $value = 'title')
    {
        $objects = $this->objectModel->getPairs($IDs, $value);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
