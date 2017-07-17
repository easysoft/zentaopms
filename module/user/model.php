<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: model.php 5005 2013-07-03 08:39:11Z chencongzhi520@gmail.com $
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
     * Set users list.
     * 
     * @param  array    $users 
     * @param  string   $account 
     * @access public
     * @return html 
     */
    public function setUserList($users, $account)
    {
        if(!isset($users[$account]))
        {
            $user = $this->getById($account);
            if($user and $user->deleted) $users[$account] = zget($user, 'realname', $account);
        }
        return html::select('account', $users, $account, "onchange=\"switchAccount(this.value, '{$this->app->getMethodName()}')\" class='form-control chosen'");
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
     * Get the account=>realname pairs.
     * 
     * @param  string $params   noletter|noempty|noclosed|nodeleted|withguest|pofirst|devfirst|qafirst|pmfirst|realname, can be sets of theme
     * @param  string $usersToAppended  account1,account2 
     * @access public
     * @return array
     */
    public function getPairs($params = '', $usersToAppended = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getUserPairs();
        /* Set the query fields and orderBy condition.
         *
         * If there's xxfirst in the params, use INSTR function to get the position of role fields in a order string,
         * thus to make sure users of this role at first.
         */
        $fields = 'account, realname, deleted';
        if(strpos($params, 'pofirst') !== false) $fields .= ", INSTR(',pd,po,', role) AS roleOrder";
        if(strpos($params, 'pdfirst') !== false) $fields .= ", INSTR(',po,pd,', role) AS roleOrder";
        if(strpos($params, 'qafirst') !== false) $fields .= ", INSTR(',qd,qa,', role) AS roleOrder";
        if(strpos($params, 'qdfirst') !== false) $fields .= ", INSTR(',qa,qd,', role) AS roleOrder";
        if(strpos($params, 'pmfirst') !== false) $fields .= ", INSTR(',td,pm,', role) AS roleOrder";
        if(strpos($params, 'devfirst')!== false) $fields .= ", INSTR(',td,pm,qd,qa,dev,', role) AS roleOrder";
        $orderBy = strpos($params, 'first') !== false ? 'roleOrder DESC, account' : 'account';

        /* Get raw records. */
        $users = $this->dao->select($fields)->from(TABLE_USER)
            ->beginIF(strpos($params, 'nodeleted') !== false)->where('deleted')->eq(0)->fi()
            ->orderBy($orderBy)
            ->fetchAll('account');
        if($usersToAppended) $users += $this->dao->select($fields)->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll('account');

        /* Cycle the user records to append the first letter of his account. */
        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            if(strpos($params, 'noletter') !== false) $firstLetter =  '';
            $users[$account] =  $firstLetter . (($user->deleted and strpos($params, 'realname') === false) ? $account : ($user->realname ? $user->realname : $account));
        }

        /* Append empty, closed, and guest users. */
        if(strpos($params, 'noempty')   === false) $users = array('' => '') + $users;
        if(strpos($params, 'noclosed')  === false) $users = $users + array('closed' => 'Closed');
        if(strpos($params, 'withguest') !== false) $users = $users + array('guest' => 'Guest');

        return $users;
    }

    /**
     * Get commiters from the user table.
     * 
     * @access public
     * @return array 
     */
    public function getCommiters()
    {
        $rawCommiters = $this->dao->select('commiter, account, realname')->from(TABLE_USER)->where('commiter')->ne('')->fetchAll();
        if(!$rawCommiters) return array();

        $commiters = array();
        foreach($rawCommiters as $commiter)
        {
            $userCommiters = explode(',', $commiter->commiter);
            foreach($userCommiters as $userCommiter)
            {
                $commiters[$userCommiter] = $commiter->realname ? $commiter->realname : $commiter->account;
            }
        }

        return $commiters;
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
     * Get roles for some users.
     * 
     * @param  string    $users 
     * @access public
     * @return array
     */
    public function getUserRoles($users)
    {
        $users = $this->dao->select('account, role')->from(TABLE_USER)->where('account')->in($users)->fetchPairs();
        if(!$users) return array();

        foreach($users as $account => $role) $users[$account] = zget($this->lang->user->roleList, $role, $role);
        return $users;
    }

    /**
     * Get user info by ID.
     * 
     * @param  int    $userID 
     * @access public
     * @return object|bool
     */
    public function getById($userID, $field = 'account')
    {
        $user = $this->dao->select('*')->from(TABLE_USER)->where("`$field`")->eq($userID)->fetch();
        if(!$user) return false;
        $user->last = date(DT_DATETIME1, $user->last);
        return $user;
    }

    /**
     * Get users by sql.
     * 
     * @param  int    $query 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getByQuery($query, $pager = null, $orderBy = 'id')
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
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
            ->remove('group, password1, password2, verifyPassword')
            ->get();

        if(isset($this->config->safe->mode) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
        {
            dao::$errors['password1'][] = $this->lang->user->weakPassword;
            return false;
        }

        if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password)
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        $this->dao->insert(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($this->config->user->create->requiredFields, 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->checkIF($this->post->email != false, 'email', 'email')
            ->exec();
        if($this->post->group)
        {
            $data = new stdClass();
            $data->account = $this->post->account;
            $data->group   = $this->post->group;
            $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
        }

        if(!dao::isError())
        {
            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
        }
    }

    /**
     * Batch create users. 
     * 
     * @param  int    $users 
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password) die(js::alert($this->lang->user->error->verifyPassword));

        $users    = fixer::input('post')->get(); 
        $data     = array();
        $accounts = array();
        for($i = 0; $i < $this->config->user->batchCreate; $i++)
        {
            if($users->account[$i] != '')  
            {
                $account = $this->dao->select('account')->from(TABLE_USER)->where('account')->eq($users->account[$i])->fetch();
                if($account) die(js::error(sprintf($this->lang->user->error->accountDupl, $i+1)));
                if(in_array($users->account[$i], $accounts)) die(js::error(sprintf($this->lang->user->error->accountDupl, $i+1)));
                if(!validater::checkAccount($users->account[$i])) die(js::error(sprintf($this->lang->user->error->account, $i+1)));
                if($users->realname[$i] == '') die(js::error(sprintf($this->lang->user->error->realname, $i+1)));
                if($users->email[$i] and !validater::checkEmail($users->email[$i])) die(js::error(sprintf($this->lang->user->error->mail, $i+1)));
                $users->password[$i] = (isset($prev['password']) and $users->ditto[$i] == 'on' and empty($users->password[$i])) ? $prev['password'] : $users->password[$i];
                if(!validater::checkReg($users->password[$i], '|(.){6,}|')) die(js::error(sprintf($this->lang->user->error->password, $i+1)));
                $role = $users->role[$i] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : '') : $users->role[$i];

                $data[$i] = new stdclass();
                $data[$i]->dept     = $users->dept[$i] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $users->dept[$i];
                $data[$i]->account  = $users->account[$i];
                $data[$i]->realname = $users->realname[$i];
                $data[$i]->role     = $role;
                $data[$i]->group    = $users->group[$i] == 'ditto' ? (isset($prev['group']) ? $prev['group'] : '') : $users->group[$i];
                $data[$i]->email    = $users->email[$i];
                $data[$i]->gender   = $users->gender[$i];
                $data[$i]->password = md5($users->password[$i]); 
                $data[$i]->commiter = $users->commiter[$i];
                $data[$i]->join     = empty($users->join[$i]) ? '0000-00-00' : ($user->join[$i]);
                $data[$i]->skype    = $users->skype[$i];
                $data[$i]->qq       = $users->qq[$i];
                $data[$i]->yahoo    = $users->yahoo[$i];
                $data[$i]->gtalk    = $users->gtalk[$i];
                $data[$i]->wangwang = $users->wangwang[$i];
                $data[$i]->mobile   = $users->mobile[$i];
                $data[$i]->phone    = $users->phone[$i];
                $data[$i]->address  = $users->address[$i];
                $data[$i]->zipcode  = $users->zipcode[$i];

                /* Change for append field, such as feedback.*/
                if(!empty($this->config->user->batchAppendFields))
                {
                    $appendFields = explode(',', $this->config->user->batchAppendFields);
                    foreach($appendFields as $appendField)
                    {
                        if(empty($appendField)) continue;
                        if(!isset($users->$appendField)) continue;
                        $fieldList = $users->$appendField;
                        $data[$i]->$appendField = $fieldList[$i];
                    }
                }

                $accounts[$i]     = $data[$i]->account;
                $prev['dept']     = $data[$i]->dept;
                $prev['role']     = $data[$i]->role;
                $prev['group']    = $data[$i]->group;
                $prev['password'] = $users->password[$i];
            }
        }

        $this->loadModel('mail');
        foreach($data as $user)
        {
            if($user->group)
            {
                $group = new stdClass();
                $group->account = $user->account;
                $group->group   = $user->group;
                $this->dao->insert(TABLE_USERGROUP)->data($group)->exec();
            }
            unset($user->group);
            $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();
            if(dao::isError()) 
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }
            else
            {
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
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
        if(!$this->checkPassword(true)) return;

        $oldUser = $this->getById($userID, 'id');

        $userID = $oldUser->id;
        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
            ->remove('password1, password2, groups,verifyPassword')
            ->get();

        if(isset($this->config->safe->mode) and isset($user->password) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
        {
            dao::$errors['password1'][] = $this->lang->user->weakPassword;
            return false;
        }

        if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password)
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

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
                $admins = str_replace(',' . $oldUser->account . ',', ',' . $this->post->account . ',', $this->app->company->admins);
                $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
                if(!dao::isError()) $this->app->user->account = $this->post->account;
            }
        }

        if(isset($_POST['groups']))
        {
            $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->exec();
            foreach($this->post->groups as $groupID)
            {
                $data          = new stdclass();
                $data->account = $this->post->account;
                $data->group   = $groupID;
                $this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
            }
        }
        if(!empty($user->password) and $user->account == $this->app->user->account) $this->app->user->password = $user->password;

        if(!dao::isError())
        {
            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and $user->email != $oldUser->email)
            {
                $this->mail->syncSendCloud('delete', $oldUser->email);
                $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
    }

    /**
     * Batch edit user.
     * 
     * @access public
     * @return void
     */
    public function batchEdit()
    {
        $data = fixer::input('post')->get();
        if(empty($_POST['verifyPassword']) or md5($this->post->verifyPassword) != $this->app->user->password) die(js::alert($this->lang->user->error->verifyPassword));

        $oldUsers     = $this->dao->select('id, account, email')->from(TABLE_USER)->where('id')->in(array_keys($data->account))->fetchAll('id');
        $accountGroup = $this->dao->select('id, account')->from(TABLE_USER)->where('account')->in($data->account)->fetchGroup('account', 'id');

        $accounts = array();
        foreach($data->account as $id => $account)
        {
            $users[$id]['account']  = $account;
            $users[$id]['realname'] = $data->realname[$id];
            $users[$id]['commiter'] = $data->commiter[$id];
            $users[$id]['email']    = $data->email[$id];
            $users[$id]['join']     = $data->join[$id];
            $users[$id]['skype']    = $data->skype[$id];
            $users[$id]['qq']       = $data->qq[$id];
            $users[$id]['yahoo']    = $data->yahoo[$id];
            $users[$id]['gtalk']    = $data->gtalk[$id];
            $users[$id]['wangwang'] = $data->wangwang[$id];
            $users[$id]['mobile']   = $data->mobile[$id];
            $users[$id]['phone']    = $data->phone[$id];
            $users[$id]['address']  = $data->address[$id];
            $users[$id]['zipcode']  = $data->zipcode[$id];
            $users[$id]['dept']     = $data->dept[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $data->dept[$id];
            $users[$id]['role']     = $data->role[$id] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : 0) : $data->role[$id];

            if(isset($accountGroup[$account]) and count($accountGroup[$account]) > 1) die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(in_array($account, $accounts)) die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(!validater::checkAccount($users[$id]['account'])) die(js::error(sprintf($this->lang->user->error->account, $id)));
            if($users[$id]['realname'] == '') die(js::error(sprintf($this->lang->user->error->realname, $id)));
            if($users[$id]['email'] and !validater::checkEmail($users[$id]['email'])) die(js::error(sprintf($this->lang->user->error->mail, $id)));
            if(empty($users[$id]['role'])) die(js::error(sprintf($this->lang->user->error->role, $id)));

            $accounts[$id] = $account;
            $prev['dept']  = $users[$id]['dept'];
            $prev['role']  = $users[$id]['role'];
        }

        $this->loadModel('mail');
        foreach($users as $id => $user)
        {
            $this->dao->update(TABLE_USER)->data($user)->where('id')->eq((int)$id)->exec();
            $oldUser = $oldUsers[$id];
            if(!dao::isError())
            {
                if($this->config->mail->mta == 'sendcloud' and $user['email'] != $oldUser->email)
                {
                    $this->mail->syncSendCloud('delete', $oldUser->email);
                    $this->mail->syncSendCloud('sync', $user['email'], $user['realname']);
                }
            }

            if($user['account'] != $oldUser->account)
            {
                $oldAccount = $oldUser->account;
                $this->dao->update(TABLE_USERGROUP)->set('account')->eq($user['account'])->where('account')->eq($oldAccount)->exec();
                if(strpos($this->app->company->admins, ',' . $oldAccount . ',') !== false)
                {
                    $admins = str_replace(',' . $oldAccount . ',', ',' . $user['account'] . ',', $this->app->company->admins);
                    $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
                }
                if(!dao::isError() and $this->app->user->account == $oldAccount) $this->app->user->account = $users['account'];
            }
        }
    }

    /**
     * Update password 
     * 
     * @param  string $userID 
     * @access public
     * @return void
     */
    public function updatePassword($userID)
    {
        if(!$this->checkPassword()) return;
        
        $user = fixer::input('post')
            ->setIF($this->post->password1 != false, 'password', md5($this->post->password1))
            ->remove('account, password1, password2, originalPassword')
            ->get();

        if(isset($this->config->safe->mode) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
        {
            dao::$errors['password1'][] = $this->lang->user->weakPassword;
            return false;
        }

        if(empty($_POST['originalPassword']) or md5($this->post->originalPassword) != $this->app->user->password)
        {
            dao::$errors['originalPassword'][] = $this->lang->user->error->originalPassword;
            return false;
        }

        $this->dao->update(TABLE_USER)->data($user)->autoCheck()->where('id')->eq((int)$userID)->exec();
        $this->app->user->password       = $user->password;
        $this->app->user->modifyPassword = false;;
    }

    /**
     * Reset password.
     * 
     * @access public
     * @return bool
     */
    public function resetPassword()
    {
        if(!$this->checkPassword()) return;
        
        $user = $this->getById($this->post->account);
        if(!$user) return false;

        $password = md5($this->post->password1);
        if(isset($this->config->safe->mode) and $this->computePasswordStrength($this->post->password1) < $this->config->safe->mode)
        {
            dao::$errors['password1'][] = $this->lang->user->weakPassword;
            return false;
        }

        $this->dao->update(TABLE_USER)->set('password')->eq($password)->autoCheck()->where('account')->eq($this->post->account)->exec();
        return !dao::isError();
    }

    /**
     * Check the passwds posted.
     * 
     * @access public
     * @return bool
     */
    public function checkPassword($canNoPassword = false)
    {
        if(!$canNoPassword and empty($_POST['password1'])) dao::$errors['password'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
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
        $record = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->beginIF(strlen($password) < 32)->andWhere('password')->eq(md5($password))->fi()
            ->andWhere('deleted')->eq(0)
            ->fetch();

        /* If the length of $password is 32 or 40, checking by the auth hash. */
        $user = false;
        if($record)
        {
            $passwordLength = strlen($password);
            if($passwordLength < 32)
            {
                $user = $record;
            }
            elseif($passwordLength == 32)
            {
                $hash = $this->session->rand ? md5($record->password . $this->session->rand) : $record->password;
                $user = $password == $hash ? $record : '';
            }
            elseif($passwordLength == 40)
            {
                $hash = sha1($record->account . $record->password . $record->last);
                $user = $password == $hash ? $record : '';
            }
            if(!$user and md5($password) == $record->password) $user = $record;
        }

        if($user)
        {
            $ip   = $this->server->remote_addr;
            $last = $this->server->request_time;
            $user->last  = date(DT_DATETIME1, $last);
            $user->admin = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $user->modifyPassword = ($user->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
            if($user->modifyPassword) $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
            if(!$user->modifyPassword and !empty($this->config->safe->changeWeak))
            {
                $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
                if($user->modifyPassword) $user->modifyPasswordReason = 'weak';
            }

            $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();
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
        $user->groups = $this->getGroups($account);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('common')->loadConfigFromDB();
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
        $user->groups = $this->getGroups($account);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('common')->loadConfigFromDB();

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
            $acl  = $this->dao->select('acl')->from(TABLE_GROUP)->where('name')->eq('guest')->fetch('acl');
            $acls = empty($acl) ? array() : json_decode($acl, true);

            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.id = t2.group')->where('t1.name')->eq('guest');
        }
        else
        {
            $groups = $this->dao->select('t1.acl')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id=t2.group')
                ->where('t2.account')->eq($account)
                ->fetchAll();
            $acls = array();
            $viewAllow    = false;
            $productAllow = false;
            $projectAllow = false;
            foreach($groups as $group)
            {
                $acl = json_decode($group->acl, true);
                if(empty($group->acl))
                {
                    $productAllow = true;
                    $projectAllow = true;
                    $viewAllow    = true;
                    break;
                }
                if(empty($acl['products'])) $productAllow = true;
                if(empty($acl['projects'])) $projectAllow = true;
                if(empty($acl['views']))    $viewAllow    = true;
                if(empty($acls) and !empty($acl))
                {
                    $acls = $acl;
                    continue;
                }

                if(!empty($acl['views'])) $acls['views'] = array_merge($acls['views'], $acl['views']);
                if(!empty($acl['products'])) $acls['products'] = !empty($acls['products']) ? array_merge($acls['products'], $acl['products']) : $acl['products'];
                if(!empty($acl['projects'])) $acls['projects'] = !empty($acls['projects']) ? array_merge($acls['projects'], $acl['projects']) : $acl['projects'];
            }

            if($productAllow) $acls['products'] = array();
            if($projectAllow) $acls['projects'] = array();
            if($viewAllow)    $acls['views']    = array();

            $sql = $this->dao->select('module, method')->from(TABLE_USERGROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.group = t2.group')
                ->where('t1.account')->eq($account);
        }

        $stmt = $sql->query();
        if(!$stmt) return array('rights' => $rights, 'acls' => $acls);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }
        return array('rights' => $rights, 'acls' => $acls);
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
        $projects = $this->dao->select('t1.*,t2.*')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();

        /* Judge whether the project is delayed. */
        foreach($projects as $project)
        {
            if($project->status != 'done')
            {
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }
        }

        return $projects;
    }

    /**
     * Plus the fail times.
     * 
     * @param  int    $account 
     * @access public
     * @return void
     */
    public function failPlus($account)
    {
        $user  = $this->dao->select('fails')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if(empty($user)) return 0;

        $fails = $user->fails;
        $fails ++; 
        if($fails < $this->config->user->failTimes) 
        {
            $locked    = '0000-00-00 00:00:00';
            $failTimes = $fails;
        }
        else
        {
            $locked    = date('Y-m-d H:i:s', time());
            $failTimes = 0;
        }
        $this->dao->update(TABLE_USER)->set('fails')->eq($failTimes)->set('locked')->eq($locked)->where('account')->eq($account)->exec();
        return $fails;
    }

    /**
     * Check whether the user is locked. 
     * 
     * @param  int    $account 
     * @access public
     * @return void
     */
    public function checkLocked($account)
    {
        $user = $this->dao->select('locked')->from(TABLE_USER)->where('account')->eq($account)->fetch(); 
        if(empty($user)) return false;

        if((strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) > $this->config->user->lockMinutes * 60) return false;
        return true;
    }

    /**
     * Unlock the locked user. 
     * 
     * @param  int    $account 
     * @access public
     * @return void
     */
    public function cleanLocked($account)
    {
        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq('0000-00-00 00:00:00')->where('account')->eq($account)->exec();
    }

    /**
     * Unbind Ranzhi 
     * 
     * @param  string    $account 
     * @access public
     * @return void
     */
    public function unbind($account)
    {
        $this->dao->update(TABLE_USER)->set('ranzhi')->eq('')->where('account')->eq($account)->exec();
    }

    /**
     * Get contact list of a user.
     * 
     * @param  string    $account 
     * @param  string    $params   withempty|withnote 
     * @access public
     * @return object
     */
    public function getContactLists($account, $params= '')
    {
        $contacts = $this->dao->select('id, listName')->from(TABLE_USERCONTACT)->where('account')->eq($account)->fetchPairs();
        if(!$contacts) return array();

        if(strpos($params, 'withempty') !== false) $contacts = array('' => '') + $contacts;
        if(strpos($params, 'withnote')  !== false) $contacts = array('' => $this->lang->user->contacts->common) + $contacts;

        return $contacts;
    }

    /**
     * Get a contact list by id.
     * 
     * @param  int    $listID 
     * @access public
     * @return object
     */
    public function getContactListByID($listID)
    {
        return $this->dao->select('*')->from(TABLE_USERCONTACT)->where('id')->eq($listID)->fetch();
    }

    /**
     * Get user account and realname pairs from a contact list.
     * 
     * @param  string    $accountList 
     * @access public
     * @return array
     */
    public function getContactUserPairs($accountList)
    {
        return $this->dao->select('account, realname')->from(TABLE_USER)->where('account')->in($accountList)->fetchPairs();
    }

    /**
     * Create a contact list.
     * 
     * @param  string    $listName 
     * @param  string    $userList 
     * @access public
     * @return int
     */
    public function createContactList($listName, $userList)
    {
        $data = new stdclass();
        $data->listName = $listName;
        $data->userList = join(',', $userList);
        $data->account  = $this->app->user->account;

        $this->dao->insert(TABLE_USERCONTACT)->data($data)->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Update a contact list.
     * 
     * @param  int    $listID 
     * @param  string $listName 
     * @param  string $userList 
     * @access public
     * @return void
     */
    public function updateContactList($listID, $listName, $userList)
    {
        $data = new stdclass();
        $data->listName = $listName;
        $data->userList = join(',', $userList);

        $this->dao->update(TABLE_USERCONTACT)->data($data)->where('id')->eq($listID)->exec();
    }

    /**
     * Delete a contact list.
     * 
     * @param  int    $listID 
     * @access public
     * @return void
     */
    public function deleteContactList($listID)
    {
        return $this->dao->delete()->from(TABLE_USERCONTACT)->where('id')->eq($listID)->exec();
    }

    /**
     * Get data in JSON.
     * 
     * @param  object    $user 
     * @access public
     * @return array
     */
    public function getDataInJSON($user)
    {
        unset($user->password);
        unset($user->deleted);
        $user->company = $this->app->company->name;
        return array('user' => $user);
    }

    /**
     * Get weak users.
     * 
     * @access public
     * @return array
     */
    public function getWeakUsers()
    {
        $users = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetchAll();
        $weaks = array();
        foreach(explode(',', $this->config->safe->weak) as $weak)
        {
            $weak = md5(trim($weak));
            $weaks[$weak] = $weak;
        }

        $weakUsers = array();
        foreach($users as $user)
        {
            if(isset($weaks[$user->password]))
            {
                $user->weakReason = 'weak';
                $weakUsers[] = $user;
            }
            elseif($user->password == md5($user->account))
            {
                $user->weakReason = 'account';
                $weakUsers[] = $user;
            }
            elseif($user->phone and $user->password == md5($user->phone))
            {
                $user->weakReason = 'phone';
                $weakUsers[] = $user;
            }
            elseif($user->mobile and $user->password == md5($user->mobile))
            {
                $user->weakReason = 'mobile';
                $weakUsers[] = $user;
            }
            elseif($user->birthday and $user->password == md5($user->birthday))
            {
                $user->weakReason = 'birthday';
                $weakUsers[] = $user;
            }
        }

        return $weakUsers;
    }

    /**
     * Compute  password strength. 
     * 
     * @param  string    $password 
     * @access public
     * @return int
     */
    public function computePasswordStrength($password)
    {
        if(strlen($password) == 0) return 0;

        $strength = 0;
        $length   = strlen($password);

        $uniqueChars = '';
        $complexity  = array();
        $chars = str_split($password);
        foreach($chars as $letter)
        {
            $asc = ord($letter);
            if($asc >= 48 && $asc <= 57)
            {
                $complexity[2] = 2;
            }
            elseif($asc >= 65 && $asc <= 90)
            {
                $complexity[1] = 2;
            }
            elseif($asc >= 97 && $asc <= 122)
            {
                $complexity[0] = 1;
            }
            else
            {
                $complexity[3] = 3;
            }
            if(strpos($uniqueChars, $letter) === false) $uniqueChars .= $letter;
        }
        if(strlen($uniqueChars) > 4)$strength += strlen($uniqueChars) - 4;
        $strength += array_sum($complexity) + (2 * (count($complexity) - 1));
        if($length < 6 and $strength >= 10) $strength = 9;

        $strength = $strength > 29 ? 29 : $strength;
        $strength = floor($strength / 10);

        return $strength;
    }
}
