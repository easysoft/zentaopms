<?php
class ssoModel extends model
{
    /**
     * Get all auths. 
     * 
     * @access public
     * @return object
     */
    public function getAuths()
    {
        $auths = clone $this->config->sso;
        unset($auths->create);
        unset($auths->edit);
        return $auths;
    }

    /**
     * Get auth by code.
     * 
     * @param  string $code 
     * @access public
     * @return object 
     */
    public function getAuth($code)
    {
        return $this->config->sso->$code; 
    }

    /**
     * Create auth. 
     * 
     * @access public
     * @return void
     */
    public function createAuth()
    {
        $auth  = fixer::input('post')->get();
        $items = new stdClass();
        $items->{$this->post->code} = $auth;
        $this->loadModel('setting')->setItems("system.sso", $items);
    }

    /**
     * Update auth. 
     * 
     * @param  int    $code 
     * @access public
     * @return void
     */
    public function updateAuth($code)
    {
        $auth  = fixer::input('post')->get();
        $items = new stdClass();
        $items->$code = $auth;
        $this->loadModel('setting')->setItems("system.sso", $items);
    }

    /**
     * Delete auth. 
     * 
     * @param  string $code 
     * @access public
     * @return void
     */
    public function deleteAuth($code)
    { 
        $this->loadModel('setting')->deleteItems("owner=system&module=sso&section=$code");
    }

    /**
     * Get key of app. 
     * 
     * @param  string $app 
     * @access public
     * @return object 
     */
    public function getAppKey($app)
    {
        return $this->config->sso->$app->key;
    }

    /**
     * Check ip if is allowed.
     * 
     * @param  string $app 
     * @access public
     * @return bool 
     */
    public function checkIP($app)
    {
        $ipParts  = explode('.', $_SERVER['REMOTE_ADDR']);
        $allowIPs = explode(',', $this->config->sso->$app->ip);

        foreach($allowIPs as $allowIP)
        {
            $allowIPParts = explode('.', $allowIP);
            foreach($allowIPParts as $key => $allowIPPart)
            {
                if($allowIPPart == '*') $allowIPParts[$key] = $ipParts[$key];
            }
            if(implode('.', $allowIPParts) == $_SERVER['REMOTE_ADDR']) return true;
        }
        return false;
    }

    /**
     * Identify user.
     * 
     * @param  string $app 
     * @access public
     * @return bool | object 
     */
    public function identify($app)
    {
        if(!$this->checkIP($app)) return false;

        $key = $this->getAppKey($app);

        $account  = '';
        $authcode = '';
        if($this->post->account)  $account  = $this->post->account;
        if($this->get->account)   $account  = $this->get->account;
        if($this->post->authcode) $authcode = $this->post->authcode;
        if($this->get->authcode)  $authcode = $this->get->authcode;

        if(!$account or !$authcode or !$key) return false;
  
        $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->andWhere('deleted')->eq(0)
            ->fetch();

        if($user)
        {
            $code = md5($user->password . $key);
            if($code == $authcode) return $user;
        }

        return false;
    }

    /**
     * Create a key.
     * 
     * @access public
     * @return string 
     */
    public function createKey()
    {
        return md5(rand());
    }

    /**
     * Get all departments.
     * 
     * @access public
     * @return object 
     */
    public function getAllDepts()
    {
        return $this->dao->select('*')->from(TABLE_DEPT)->fetchAll();
    }

    /**
     * Get all users. 
     * 
     * @access public
     * @return object 
     */
    public function getAllUsers()
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->fetchAll();
    }
}
