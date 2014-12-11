<?php
/**
 * The model file of backup module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class backupModel extends model
{
    public function backSQL($backupFile)
    {
        $zdb = $this->app->loadClass('zdb');
        return $zdb->dump($backupFile);
    }

    public function backFile($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($backupFile);
        $zip->create($this->app->getAppRoot() . 'www/data/', PCLZIP_OPT_REMOVE_PATH, $this->app->getAppRoot() . 'www/data/');
        if($zip->errorCode != 0)
        {
            $return->result = false;
            $return->error  = $zip->errorInfo();
        }

        return $return;
    }

    public function restoreSQL($backupFile)
    {
        $zdb = $this->app->loadClass('zdb');
        return $zdb->import($backupFile);
    }

    public function restoreFile($backupFile)
    {
        $return = new stdclass();
        $return->result = true;
        $return->error  = '';

        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($backupFile);
        if($zip->extract(PCLZIP_OPT_PATH, $this->app->getAppRoot() . 'www/data/') == 0)
        {
            $return->result = false;
            $return->error  = $zip->errorInfo();
        }

        return $return;
    }
}
