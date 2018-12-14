<?php
class entry extends control
{
    /**
     * Visit entry.
     *
     * @param  int    $entryID
     * @param  string $referer
     * @access public
     * @return void
     */
    public function visit($entryID = '', $referer = '')
    {
        if(RUN_MODE != 'xuanxuan') die();

        $referer = !empty($_GET['referer']) ? $this->get->referer : $referer;
        if(empty($referer)) $referer = common::getSysURL() . $this->createLink('index', 'index', '', 'xhtml');

        $output = new stdclass();
        $output->module = $this->moduleName;
        $output->method = $this->methodName;
        $output->result = 'success';
        $output->users  = array();

        $query = '';
        $query = $this->config->sessionVar . '=' . session_id();

        $location = $referer;
        $pathinfo = parse_url($location);
        if(!empty($pathinfo['query']))
        {
            $location = substr($location, 0, strpos($location, '?'));
            $location = rtrim($location, '?') . "?{$query}&{$pathinfo['query']}";
        }
        else
        {
            $location = rtrim($location, '?') . "?$query";
        }
        $output->data = $location;

        if($this->session->userID)
        {
            $output->users = array($this->session->userID);
            $this->loadModel('user');
            $user = $this->dao->select('*')->from(TABLE_USER)->where('id')->eq($this->session->userID)->fetch();

            unset($user->password);
            $this->user->cleanLocked($user->account);

            $user->admin    = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $user->rights   = $this->user->authorize($user->account);
            $user->groups   = $this->user->getGroups($user->account);
            $user->view     = $this->user->grantUserView($user->account, $user->rights['acls']);

            $last = time();
            $user->last     = date(DT_DATETIME1, $last);
            $user->lastTime = $last;
            $user->ip       = $this->session->clientIP->IP;

            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }

        die($this->app->encrypt($output));
    }
}
