<?php
/**
 * The model file of file module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class fileModel extends model
{
    public $savePath = '';
    public $webPath  = '';

    public function __construct()
    {
        parent::__construct();
        $this->setSavePath();
        $this->setWebPath();
    }

    public function getByObject($objectType, $objectID)
    {
        $files = array();
        $stmt = $this->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq($objectType)->andWhere('objectID')->eq((int)$objectID)->query();
        while($file = $stmt->fetch())
        {
            $file->fullPath = $this->webPath . $file->pathname;
            $files[] = $file;
        }
        return $files;
    }

    /* 保存上传的文件。*/
    public function saveUpload($htmlTagName = 'files', $objectType = '', $objectID = '')
    {
        $fileTitles = array();
        $now        = date('Y-m-d H:i:s');
        $files      = $this->getUpload($htmlTagName);

        foreach($files as $id => $file)
        {
            move_uploaded_file($file['tmpname'], $this->savePath . $file['pathname']);
            $file['company']    = $this->app->company->id;
            $file['objectType'] = $objectType;
            $file['objectID']   = $objectID;
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = $now;
            unset($file['tmpname']);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
            $fileTitles[$this->dao->lastInsertId()] = $file['title'];
        }
        return $fileTitles;
    }

    /* 获取上传的文件信息。*/
    private function getUpload($htmlTagName)
    {
        $files = array();
        if(!isset($_FILES[$htmlTagName])) return $files;

        /* 表单定义中的变量名是数组。*/
        if(is_array($_FILES[$htmlTagName]['name']))
        {
            extract($_FILES[$htmlTagName]);
            foreach($name as $id => $filename)
            {
                if(empty($filename)) continue;
                $file['extension'] = $this->getExtension($filename);
                $file['pathname']  = $this->setPathName($id, $file['extension']);
                $file['title']     = pathinfo($filename, PATHINFO_FILENAME);
                $file['size']      = $size[$id];
                $file['tmpname']   = $tmp_name[$id];
                $files[] = $file;
            }
        }
        else
        {
            if(empty($_FILES[$htmlTagName]['name'])) return $files;
            extract($_FILES[$htmlTagName]);
            $file['extension'] = $this->getExtension($name);
            $file['pathname']  = $this->setPathName(0, $file['extension']);
            $file['title']     = pathinfo($name, PATHINFO_FILENAME);
            $file['size']      = $size;
            $file['tmpname']   = $tmp_name;
            return array($file);
        }
        return $files;
    }

    /* 获取文件扩展名。*/
    private function getExtension($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if(empty($extension)) return 'txt';
        if(strpos($this->config->file->dangers, $extension) !== false) return 'txt';
        return $extension;
    }

    /* 设置要存储的文件名。*/
    private function setPathName($fileID, $extension)
    {
        return date('YmdHis') . $fileID . mt_rand(0, 10000) . '.' . $extension;
    }

    /* 设置存储路径。*/
    private function setSavePath()
    {
        $this->savePath = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/";
        if(!file_exists($this->savePath)) mkdir($this->savePath);
    }
    
    /* 设置web访问路径。*/
    private function setWebPath()
    {
        $this->webPath = $this->app->getWebRoot() . "data/upload/{$this->app->company->id}/";
    }
}
