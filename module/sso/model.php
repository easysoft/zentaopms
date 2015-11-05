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
        return $this->dao->select('*')->from(TABLE_USER)->where('bindRanzhi')->eq($user)->andWhere('deleted')->eq('0')->fetch();
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
            $user->bindRanzhi = $this->session->ssoData->account;
            $this->dao->update(TABLE_USER)->set('bindRanzhi')->eq($user->bindRanzhi)->where('id')->eq($user->id)->exec();
        }
        elseif($data->bindType == 'add')
        {
            if(!$this->loadModel('user')->checkPassword()) return;
            $user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($data->account)->fetch();
            if($user) die(js::alert($this->lang->sso->bindHasAccount));

            $user = new stdclass();
            $user->account    = $data->account;
            $user->password   = md5($data->password1);
            $user->realname   = $data->realname;
            $user->gender     = isset($data->gender) ? $data->gender : '';
            $user->email      = $data->email;
            $user->bindRanzhi = $this->session->ssoData->account;

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
}
