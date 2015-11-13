<?php
class ssoModel extends model
{
    /**
     * Check Key.
     * 
     * @access public
     * @return bool
     */
    public function checkKey()
    {
        if(!isset($this->config->sso->turnon) or !$this->config->sso->turnon) return false;
        if(empty($this->config->sso->key)) return false;
        return $this->get->hash == $this->config->sso->key;
    }

    /**
     * Get bind user.
     * 
     * @param  string    $user 
     * @access public
     * @return object
     */
    public function getBindUser($user)
    {
        return $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($user)->andWhere('deleted')->eq('0')->fetch();
    }

    /**
     * Get bind users with ranzhi.
     * 
     * @access public
     * @return array
     */
    public function getBindUsers()
    {
        return $this->dao->select('account,ranzhi')->from(TABLE_USER)->where('ranzhi')->ne('')->andWhere('deleted')->eq('0')->fetchPairs('ranzhi', 'account');
    }

    /**
     * Bind user. 
     * 
     * @access public
     * @return object
     */
    public function bind()
    {
        $data = fixer::input('post')->get();
        if($data->bindType == 'bind')
        {
            if(empty($data->bindPassword))die(js::alert($this->lang->sso->bindNoPassword));
            $password = md5($data->bindPassword);
            $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->bindUser)->andWhere('password')->eq($password)->andWhere('deleted')->eq('0')->fetch();
            if(empty($user))die(js::alert($this->lang->sso->bindNoUser));
            $user->ranzhi = $this->session->ssoData->account;
            $this->dao->update(TABLE_USER)->set('ranzhi')->eq($user->ranzhi)->where('id')->eq($user->id)->exec();
        }
        elseif($data->bindType == 'add')
        {
            if(!$this->loadModel('user')->checkPassword()) return;
            $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->account)->fetch();
            if($user) die(js::alert($this->lang->sso->bindHasAccount));

            if(isset($this->config->safe->mode) and $this->user->computePasswordStrength($data->password1) < $this->config->safe->mode)
            {
                dao::$errors['password1'][] = $this->lang->user->weakPassword;
                return false;
            }

            $user = new stdclass();
            $user->account    = $data->account;
            $user->password   = md5($data->password1);
            $user->realname   = $data->realname;
            $user->gender     = isset($data->gender) ? $data->gender : '';
            $user->email      = $data->email;
            $user->ranzhi     = $this->session->ssoData->account;

            $this->dao->insert(TABLE_USER)->data($user)
                ->autoCheck()
                ->batchCheck($this->config->user->create->requiredFields, 'notempty')
                ->check('account', 'unique')
                ->check('account', 'account')
                ->checkIF($user->email != false, 'email', 'email')
                ->exec();
        }

        return $user;
    }

    /**
     * Create a user from ranzhi.
     * 
     * @access public
     * @return void
     */
    public function createUser()
    {
        $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($this->post->account)->fetch();
        if($user) return array('status' => 'fail', 'data' => $this->lang->sso->bindHasAccount); 

        $user = new stdclass();
        $user->account  = $this->post->account;
        $user->realname = $this->post->realname;
        $user->email    = $this->post->email;
        $user->gender   = $this->post->gender;
        $user->ranzhi   = $this->post->account;

        $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();

        if(dao::isError()) return array('status' => 'fail', 'data' => dao::getError()); 
        return array('status' => 'success'); 
    }
}
