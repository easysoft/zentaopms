<?php
/**
 * The model file of admin module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class adminModel extends model
{
    /* 获得整个pms系统的统计信息。*/
    public function getStatOfPMS()
    {
        $sql = "SHOW TABLE STATUS";
        $tables = $this->dbh->query($sql)->fetchALL();
    }

    /* 获得某一个公司的统计信息。*/
    public function getStatOfCompany($companyID)
    {
    }

    /* 获得系统的运行信息。*/
    public function getStatOfSys()
    {
    }
}
