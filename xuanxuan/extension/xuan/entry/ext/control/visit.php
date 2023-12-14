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
        $server  = $this->loadModel('im')->getServer('zentao');
        if(empty($referer)) $referer = $server . str_replace('/x.php', '/index.php', $this->createLink('my', 'index', '', 'html'));

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

            $this->user->cleanLocked($user->account);

            $user->lastTime       = $user->last;
            $user->last           = date(DT_DATETIME1, $user->last);
            $user->admin          = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $user->modifyPassword = ($user->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
            if($user->modifyPassword) $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
            if(!$user->modifyPassword and !empty($this->config->safe->changeWeak))
            {
                $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
                if($user->modifyPassword) $user->modifyPasswordReason = 'weak';
            }

            $user->rights   = $this->user->authorize($user->account);
            $user->groups   = $this->user->getGroups($user->account);
            $user->view     = $this->user->grantUserView($user->account, $user->rights['acls']);

            $last = time();
            $user->last     = date(DT_DATETIME1, $last);
            $user->lastTime = $last;
            $user->ip       = helper::getRemoteIp();

            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }

        $this->loadModel('im')->sendOutput($output, 'entry/visitResponse');
    }
}
