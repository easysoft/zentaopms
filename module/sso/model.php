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
     * @param  string    $account
     * @access public
     * @return object|false
     */
    public function getBindUser(string $account): object|false
    {
        return $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($account)->andWhere('deleted')->eq('0')->fetch();
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
     * @return object|false
     */
    public function bind(): object|false
    {
        $data = fixer::input('post')->get();
        if($data->bindType == 'bind') return $this->ssoTao->bindZTUser($data);
        if($data->bindType == 'add')  return $this->ssoTao->addZTUser($data);
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
        return array('status' => 'success', 'id' => $this->dao->lastInsertId());
    }
}
