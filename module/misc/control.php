<?php
/**
 * The control file of misc of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class misc extends control
{
    /**
     * Ping the server every 5 minutes to keep the session.
     * 
     * @access public
     * @return void
     */
    public function ping()
    {
        if(mt_rand(0, 1) == 1) $this->loadModel('setting')->setSN();
        die("<html><head><meta http-equiv='refresh' content='300' /></head><body></body></html>");
    }

    /**
     * Show php info.
     * 
     * @access public
     * @return void
     */
    public function phpinfo()
    {
        die(phpinfo());
    }

    /**
     * Show about info of zentao.
     * 
     * @access public
     * @return void
     */
    public function about()
    {
        die($this->display());
    }

    /**
     * Update nl.
     * 
     * @access public
     * @return void
     */
    public function updateNL()
    {
        $this->loadModel('upgrade')->updateNL();
    }

    /**
     * Check current version is latest or not.
     * 
     * @access public
     * @return void
     */
    public function checkUpdate()
    {
        $note    = isset($_GET['note'])    ? $_GET['note'] : '';
        $browser = isset($_GET['browser']) ? $_GET['browser'] : '';

        $this->view->note    = urldecode(helper::safe64Decode($note));
        $this->view->browser = $browser;
        $this->display();
    }

    /**
     * Check model extension logic
     * 
     * @access public
     * @return void
     */
    public function checkExtension()
    {
        echo $this->misc->hello();
        echo $this->misc->hello2();
    }

    /**
     * Down notify.
     * 
     * @access public
     * @return void
     */
    public function downNotify()
    {
        $notifyDir   = $this->app->getBasePath() . 'www/data/notify/';
        $packageFile = $notifyDir . 'notify.zip';
        $tmpDir      = $notifyDir . 'notify';
        $loginFile   = $tmpDir . '/notify/logininfo';

        $this->app->loadClass('pclzip', true);
        $sourceZip = new pclzip($packageFile);
        $files = $sourceZip->extract(PCLZIP_OPT_PATH, $notifyDir);
        if($files == 0) die("Error : ".$archive->errorInfo(true));

        $currentUser = $this->app->user;
        $loginInfo   = json_encode(array('account' => $currentUser->account, 'password' => $currentUser->password, 'zentaoRoot' => 'http://' . $this->config->default->domain));
        file_put_contents($loginFile, $loginInfo);

        unlink($packageFile);
        $newZip = new pclzip($packageFile);
        if($newZip->create($tmpDir, PCLZIP_OPT_REMOVE_PATH, $notifyDir))
        {
            $this->zfile = $this->app->loadClass('zfile');
            $this->zfile->removeDir($tmpDir);
        }
        $this->fetch('file', 'sendDownHeader', array('fileName' => 'notify.zip', 'zip', file_get_contents($packageFile)));
    }
}
