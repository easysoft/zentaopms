<?php
/**
 * The baisc model file of bugfree convert of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     convert
 * @version     $Id $
 * @link        http://www.zentao.net
 */
class redmineConvertModel extends convertModel
{
    public $map         = array();
    public $filePath    = '';
    static public $info = array();

    /**
     * Connect to db auto.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        parent::connectDB();
    }

    /**
     * Check table.
     * 
     * @access public
     * @return bool
     */
    public function checkTables()
    {
        return true;
    }

    /**
     * Check the install path.
     * 
     * @access public
     * @return bool
     */
    public function checkPath()
    {
        $this->setPath();
        return file_exists($this->filePath);
    }

    /**
     * Set the path of attachments.
     * 
     * @access public
     * @return bool
     */
    public function setPath()
    {
        $this->filePath = realpath($this->post->installPath) . $this->app->getPathFix() . 'files' . $this->app->getPathFix();
    }

    /**
     * Excute the convert.
     * 
     * @param  int    $version 
     * @access public
     * @return void
     */
    public function execute($version)
    {
    }

    /**
     * Clear rows added in converting.
     * 
     * @access public
     * @return void
     */
    public function clear()
    {
        foreach($this->session->state as $table => $maxID)
        {
            $this->dao->dbh($this->dbh)->delete()->from($table)->where('id')->gt($maxID)->exec();
        }
    }
}
