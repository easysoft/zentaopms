<?php
helper::import('../../control.php');
class myIm extends im
{
    /**
     * Get serverInfo api.
     *
     * @param  string    $account
     * @param  string    $password
     * @param  string    $apiVersion
     * @param  int       $userID
     * @param  string    $version
     * @param  string    $device
     * @access public
     * @return void
     */
    public function sysGetServerInfo($account, $password, $apiVersion = '', $userID = 0, $version = '', $device = 'desktop')
    {
        /* Check if the user is locked. */
        if($this->loadModel('user')->checkLocked($account))
        {
            $output = new stdclass();
            $output->result = 'fail';
            $output->data   = 'locked';
            die($this->app->encrypt($output));
        }
        $this->app->loadConfig('file');
        if(isset($this->config->file->collaboraPath)) $this->config->integration = (object)array('office' => (object)array('officeEnabled' => true));
        parent::sysGetServerInfo($account, $password, $apiVersion, $userID, $version, $device);
    }
}
