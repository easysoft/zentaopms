<?php
/**
 * The baisc model file of bugfree convert of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class bugfreeConvertModel extends convertModel
{
    public $map         = array();
    public $filePath    = '';
    static public $info = array();

    /* 构造函数，连接到数据库。*/
    public function __construct()
    {
        parent::__construct();
        parent::connectDB();
    }

    /* 检查Tables。*/
    public function checkTables()
    {
        return true;
    }

    /* 检查安装路径。*/
    public function checkPath()
    {
        $this->setPath();
        return file_exists($this->filePath);
    }

    /* 设置附件路径。*/
    public function setPath()
    {
        $this->filePath = realpath($this->post->installPath) . $this->app->getPathFix() . 'BugFile' . $this->app->getPathFix();
    }

    /* 执行转换。*/
    public function execute($version)
    {
    }

    /* 清空导入之后的数据。*/
    public function clear()
    {
        foreach($this->session->state as $table => $maxID)
        {
            $this->dao->dbh($this->dbh)->delete()->from($table)->where('id')->gt($maxID)->exec();
        }
    }
}
