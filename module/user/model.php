<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class userModel extends model
{
    /**
     * Set the menu.
     * 
     * @param  array  $users    user pairs
     * @param  string $account  current account
     * @access public
     * @return void
     */
    public function setMenu($users, $account)
    {
        $methodName = $this->app->getMethodName();
        $selectHtml = html::select('account', $users, $account, "onchange=\"switchAccount(this.value, '$methodName')\"");
        foreach($this->lang->user->menu as $key => $value)
        {
            $replace = ($key == 'account') ? $selectHtml : $account;
            common::setMenuVars($this->lang->user->menu, $key, $replace);
        }
    }

    /**
     * Get users list of current company.
     * 
     * @access public
     * @return void
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->orderBy('account')->fetchAll();
    }

    /**
     * Get the account=>relaname pairs.
     * 
     * @param  string $params   noletter|noempty|noclosed|nodeleted, can be sets of theme
     * @access public
     * @return array
     */
    public function getPairs($params = '')
    {
        $users = $this->dao->select('account, realname')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($params, 'nodeleted') !== false)
            ->fi()
            ->orderBy('account')->fetchPairs();
        foreach($users as $account => $realName)
        {
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            if(strpos($params, 'noletter') !== false) $firstLetter =  '';
            $users[$account] =  $firstLetter . ($realName ? $realName : $account);
        }
        if(strpos($params, 'noempty')  === false) $users = array('' => '') + $users;
        if(strpos($params, 'noclosed') === false) $users = $users + array('closed' => 'Closed');
        return $users;
    }
    
    /**
     * Appened deleted users to the user list.
     * 
     * @param  array    $users 
     * @param  string   $deleteds   the deleted users, can be a list
     * @access public
     * @return array new user lists with deleted users.
     */
    public function appendDeleted($users, $deleteds = '')
    {
        $deleteds = explode(',', $deleteds);
        foreach($deleteds as $deleted)
        {
            if(!isset($users[$deleted])) $users[$deleted] = $deleted . $this->lang->user->deleted;
        }
        return $users;
    }

    /**
     * Get user list with email and real name.
     * 
     * @param  string|array $users 
     * @access public
     * @return array
     */
    public function getRealNameAndEmails($users)
    {
        $users = $this->dao->select('account, email, realname')->from(TABLE_USER)->where('account')->in($users)->fetchAll('account');
        if(!$users) return array();
        foreach($users as $account => $user) if($user->realname == '') $user->realname = $account;
        return $users;
    }

    /**
     * Get user info by ID.
     * 
     * @param  int    $userID 
     * @access public
     * @return object|bool
     */
    public function getById($userID)
    {
        $user = $this->dao->select('*')->from(TABLE_USER)
            ->beginIF(is_numeric($userID))->where('id')->eq((int)$userID)->fi()
            ->beginIF(!is_numeric($userID))->where('account')->eq($userID)->fi()
            ->fetch();
        if(!$user) return false;
        $user->last = date(DT_DATETIME1, $user->last);
        return $user;
    }

    /**
     * Create a user.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!$this->checkPassword()) return;

        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
            ->setIF($this->post->password1 == false, 'password', '')
            ->remove('password1, password2')
            ->get();

        $this->dao->insert(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($this->config->user->create->requiredFields, 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->checkIF($this->post->email != false, 'email', 'email')
            ->exec();
    }

    /**
     * Update a user.
     * 
     * @param  int    $userID 
     * @access public
     * @return void
     */
    public function update($userID)
    {
        if(!$this->checkPassword()) return;

        $oldUser = $this->getById($userID);

        $userID = (int)$userID;
        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
            ->remove('password1, password2')
            ->specialChars('msn,qq,yahoo,gtalk,wangwang,mobile,phone,address,zipcode')
            ->get();

        $this->dao->update(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($this->config->user->edit->requiredFields, 'notempty')
            ->check('account', 'unique', "id != '$userID'")
            ->check('account', 'account')
            ->checkIF($this->post->email != false, 'email', 'email')
            ->where('id')->eq((int)$userID)
            ->exec();

        /* If account changed, update the privilege. */
        if($this->post->account != $oldUser->account)
        {
            $this->dao->update(TABLE_USERGROUP)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
            if(strpos($this->app->company->admins, ',' . $oldUser->account . ',') !== false)
            {
                $admins = ',' . $this->post->account . ',';
                $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec(false);
            }
        }
    }

    /**
     * Check the passwds posted.
     * 
     * @access public
     * @return bool
     */
    public function checkPassword()
    {
        if($this->post->password1 != false)
        {
            if($this->post->password1 != $this->post->password2) dao::$errors['password'][] = $this->lang->error->passwordsame;
            if(!validater::checkReg($this->post->password1, '|(.){6,}|')) dao::$errors['password'][] = $this->lang->error->passwordrule;
        }
        return !dao::isError();
    }
    
    /**
     * Identify a user.
     * 
     * @param   string $account     the user account
     * @param   string $password    the user password or auth hash
     * @access  public
     * @return  object
     */
    public function identify($account, $password)
    {
        if(!$account or !$password) return false;
  
        /* Get the user first. If $password length is 32, don't add the password condition.  */
        $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->beginIF(strlen($password) < 32)->andWhere('password')->eq(md5($password))->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch();

        /* If the length of $password is 32 or 40, checking by the auth hash. */
        if($user and strlen($password) == 32)
        {
            $hash = $this->session->rand ? md5($user->password . $this->session->rand) : $user->password;
            $user = $password == $hash ? $user : '';
        }
        elseif($user and strlen($password) == 40)
        {
            $hash = sha1($user->account . $user->password . $user->last);
            $user = $password == $hash ? $user : '';
        }

        if($user)
        {
            $ip   = $this->server->remove_addr;
            $last = $this->server->request_time;
            $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();
            $user->last = date(DT_DATETIME1, $user->last);
        }
        return $user;
    }

    /**
     * Identify user by PHP_AUTH_USER.
     * 
     * @access public
     * @return void
     */
    public function identifyByPhpAuth()
    {
        $account  = $this->server->php_auth_user;
        $password = $this->server->php_auth_pw;
        $user     = $this->identify($account, $password);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
    }

    /**
     * Identify user by cookie.
     * 
     * @access public
     * @return void
     */
    public function identifyByCookie()
    {
        $account  = $this->cookie->za;
        $authHash = $this->cookie->zp;
        $user     = $this->identify($account, $authHash);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');

        $this->keepLogin($user);
    }

    /**
     * Authorize a user.
     * 
     * @param   string $account
     * @access  public
     * @return  array the user rights.
     */
    public function authorize($account)
    {
        $account = filter_var($account, FILTER_SANITIZE_STRING);
        if(!$account) return false;

        $rights = array();
        if($account == 'guest')
        {
            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.id = t2.group')->where('t1.name')->eq('guest');
        }
        else
        {
            $sql = $this->dao->select('module, method')->from(TABLE_USERGROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.group = t2.group')
                ->where('t1.account')->eq($account);
        }
        $stmt = $sql->query();
        if(!$stmt) return $rights;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }
        return $rights;
    }

    /**
     * Keep the user in login state.
     * 
     * @param  string    $account 
     * @param  string    $password 
     * @access public
     * @return void
     */
    public function keepLogin($user)
    {
        setcookie('keepLogin', 'on', $this->config->cookieLife, $this->config->webRoot);
        setcookie('za', $user->account, $this->config->cookieLife, $this->config->webRoot);
        setcookie('zp', sha1($user->account . $user->password . $this->server->request_time), $this->config->cookieLife, $this->config->webRoot);
    }

    /* 
    /**
     * Judge a user is logon or not.
     * 
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        return ($this->session->user and $this->session->user->account != 'guest');
    }

    /**
     * Get groups a user belongs to.
     * 
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getGroups($account)
    {
        return $this->dao->findByAccount($account)->from(TABLE_USERGROUP)->fields('`group`')->fetchPairs();
    }

    /**
     * Get projects a user participated. 
     * 
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getProjects($account)
    {
        return $this->dao->select('t1.*,t2.*')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * Get bugs assigned to a user.
     * 
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getBugs($account)
    {
        return $this->dao->findByAssignedTo($account)->from(TABLE_BUG)->andWhere('deleted')->eq(0)->fetchAll();
    }
}
