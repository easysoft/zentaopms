<?php
/**
 * The control file of file module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class file extends control
{
    /* 生成文件上传的表单。*/
    public function buildForm($fileCount = 2)
    {
        $this->view->fileCount = $fileCount;
        $this->display();
    }

    /* 下载一个文件。*/
    public function download($fileID)
    {
        $file = $this->file->getById($fileID);
        if(file_exists($file->realPath))$this->locate($file->webPath);
        $this->app->error("The file you visit $fileID not found.", __FILE__, __LINE__, true);
    }
}
