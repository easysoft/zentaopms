<?php
class fileTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('file');
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

    /**
     * Test get info of uploaded files.
     *
     * @param  array $files
     * @param  array $labels
     * @access public
     * @return array
     */
    public function getUploadTest(array $files, array $labels): array
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
     * 测试 replaceFile 方法。
     * Test replaceFile method.
     *
     * @param  int   $fileID
     * @param  array $files
     * @param  array $labels
     * @access public
     * @return array
     */
    public function replaceFileTest(int $fileID, array $files, array $labels): array
    {
        $_FILES['upFile'] = $files;
        $_POST['labels'] = $labels;

        $objects = $this->objectModel->replaceFile($fileID);

        unset($_FILES['files']);
        unset($_POST['labels']);

        $file = $this->objectModel->dao->select('*')->from(TABLE_FILE)->where('id')->eq($fileID)->fetch();
        return empty($file) ? array() : (array)$file;
    }

    /**
     * Test get counts of uploaded files.
     *
     * @param  array  $files
     * @param  array  $labels
     * @access public
     * @return int
     */
    public function getCountTest(array $files, array $labels): int
    {
        $_FILES['files'] = $files;
        $_POST['labels'] = $labels;

        $count = $this->objectModel->getCount();

        unset($_FILES['files']);
        unset($_POST['labels']);

        return $count;
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

    /**
     * Test get save name.
     *
     * @param  string $pathName
     * @access public
     * @return string
     */
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

    /**
     * Test get export tpl.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getExportTemplateTest($module)
    {
        $objects = $this->objectModel->getExportTemplate($module);

        if(dao::isError()) return dao::getError();

        return count($objects);
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

    /**
     * 测试 setPathName 方法。
     * Test setPathName method.
     *
     * @param  int    $fileID
     * @param  string $extension
     * @access public
     * @return array
     */
    public function setPathNameTest(int $fileID, string $extension): array
    {
        $pathName = $this->objectModel->setPathName($fileID, $extension);
        $reg = date('Ym\\\/dHis', $this->objectModel->now) . $fileID . '\w+' . '\.' . $extension;

        $result['name'] = $pathName;
        $result['reg']  = preg_match("/{$reg}/", $pathName);
        return $result;
    }

    /**
     * Test save export template.
     *
     * @param  string $module
     * @access public
     * @return object
     */
    public function saveExportTemplateTest($module, $file)
    {
        foreach($file as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->saveExportTemplate($module);

        unset($_POST);

        if(dao::isError()) return dao::getError()['title'][0];

        global $tester;
        $object = $tester->dao->select('*')->from(TABLE_USERTPL)->where('id')->eq($objectID)->fetch();
        return $object;
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
        global $tester;
        $tester->config->webRoot = '/';
        $this->objectModel->setWebPath();

        return $this->objectModel->webPath;
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

    /**
     * 测试 setFileWebAndRealPaths 方法。
     * Test setFileWebAndRealPaths method.
     *
     * @param  int    $fileID
     * @access public
     * @return object
     */
    public function setFileWebAndRealPathsTest(int $fileID): object
    {
        $this->objectModel->config->webRoot = '/';

        $file = $this->objectModel->getById($fileID);
        $this->objectModel->setFileWebAndRealPaths($file);
        return $file;
    }

    /**
     * 测试 updateTestcaseVersion 方法。
     * Test updateTestcaseVersion method.
     *
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function updateTestcaseVersionTest(int $objectID): bool
    {
        $file = new stdclass();
        $file->objectID = $objectID;

        $oldCase = $this->objectModel->dao->select('*')->from(TABLE_CASE)->where('`fromCaseID`')->eq($objectID)->fetch();
        if(empty($oldCase)) return false;

        $this->objectModel->updateTestcaseVersion($file);

        $case = $this->objectModel->dao->select('*')->from(TABLE_CASE)->where('`fromCaseID`')->eq($objectID)->fetch();

        return $oldCase->fromCaseVersion != $case->fromCaseVersion;
    }

    /**
     * 测试 autoDelete 方法，返回file表数据行数
     * Test autoDelete methid, return rows count of file table.
     *
     * @access public
     * @return int
     */
    public function autoDeleteTest(string $uid): int
    {
        $this->objectModel->autoDelete($uid);
        return $this->objectModel->dao->select('count(*) AS count')->from(TABLE_FILE)->fetch('count');
    }

    public function processFile4ObjectTest(string $objectType, object $oldObject, object $newObject): array
    {
        $this->objectModel->processFile4Object($objectType, $oldObject, $newObject);
        $count = $this->objectModel->dao->select('count(*) AS count')->from(TABLE_FILE)->fetch('count');

        return array('old' => $oldObject, 'new' => $newObject, 'count' => $count);
    }
}
