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
     * @param  string $params   noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname, can be sets of theme
     * @param  string $usersToAppended  account1,account2
     * @param  int    $maxCount 
     * @access public
     * @return array
     */
    public function getPairs($params = '', $usersToAppended = '', $maxCount = 0)
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
        $this->app->loadConfig('user');
        unset($this->config->user->moreLink);

        $users = $this->dao->select($fields)->from(TABLE_USER)
            ->where('1')
            ->beginIF(strpos($params, 'nodeleted') !== false or empty($this->config->user->showDeleted))->andWhere('deleted')->eq('0')->fi()
            ->orderBy($orderBy)
            ->beginIF($maxCount)->limit($maxCount)->fi()
            ->fetchAll('account');

        if($maxCount and $maxCount == count($users))
        {
            if(is_array($usersToAppended)) $usersToAppended = join(',', $usersToAppended);
            $moreLinkParams = "params={$params}&usersToAppended={$usersToAppended}";
            $this->config->user->moreLink = helper::createLink('user', 'ajaxGetMore', "params=" . base64_encode($moreLinkParams));
        }

        if($usersToAppended) $users += $this->dao->select($fields)->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll('account');

        /* Cycle the user records to append the first letter of his account. */
        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            if(strpos($params, 'noletter') !== false or !empty($this->config->isINT)) $firstLetter = '';
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
        $this->app->loadLang('user');
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
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword()) return;
        if(strtolower($_POST['account']) == 'guest') return false;

        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00' )
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->password1 == false, 'password', '')
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->remove('group, password1, password2, verifyPassword, passwordStrength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        $this->dao->insert(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($this->config->user->create->requiredFields, 'notempty')
            ->check('account', 'unique')
            ->check('account', 'account')
            ->checkIF($this->post->email != '', 'email', 'email')
            ->exec();
        if(!dao::isError())
        {
            $userID = $this->dao->lastInsertID();
            if($this->post->group)
            {
                $data = new stdClass();
                $data->account = $this->post->account;
                $data->group   = $this->post->group;
                $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
            }

            $this->computeUserView($user->account);
            $this->loadModel('action')->create('user', $userID, 'Created');
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
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) die(js::alert($this->lang->user->error->verifyPassword));

        $users    = fixer::input('post')->get();
        $data     = array();
        $accounts = array();
        for($i = 0; $i < $this->config->user->batchCreate; $i++)
        {
            $users->account[$i] = trim($users->account[$i]);
            if($users->account[$i] != '')
            {
                if(strtolower($users->account[$i]) == 'guest') die(js::error(sprintf($this->lang->user->error->reserved, $i + 1)));
                $account = $this->dao->select('account')->from(TABLE_USER)->where('account')->eq($users->account[$i])->fetch();
                if($account) die(js::error(sprintf($this->lang->user->error->accountDupl, $i + 1)));
                if(in_array($users->account[$i], $accounts)) die(js::error(sprintf($this->lang->user->error->accountDupl, $i + 1)));
                if(!validater::checkAccount($users->account[$i])) die(js::error(sprintf($this->lang->user->error->account, $i + 1)));
                if($users->realname[$i] == '') die(js::error(sprintf($this->lang->user->error->realname, $i + 1)));
                if($users->email[$i] and !validater::checkEmail($users->email[$i])) die(js::error(sprintf($this->lang->user->error->mail, $i + 1)));
                $users->password[$i] = (isset($prev['password']) and $users->ditto[$i] == 'on' and !$this->post->password[$i]) ? $prev['password'] : $this->post->password[$i];
                if(!validater::checkReg($users->password[$i], '|(.){6,}|')) die(js::error(sprintf($this->lang->user->error->password, $i + 1)));
                $role = $users->role[$i] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : '') : $users->role[$i];

                /* Check weak and common weak password. */
                if(isset($this->config->safe->mode) and $this->computePasswordStrength($users->password[$i]) < $this->config->safe->mode) die(js::error(sprintf($this->lang->user->error->weakPassword, $i + 1)));
                if(!empty($this->config->safe->changeWeak))
                {
                    if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                    if(strpos(",{$this->config->safe->weak},", ",{$users->password[$i]},") !== false) die(js::error(sprintf($this->lang->user->error->dangerPassword, $i + 1, $this->config->safe->weak)));
                }

                $data[$i] = new stdclass();
                $data[$i]->dept     = $users->dept[$i] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $users->dept[$i];
                $data[$i]->account  = $users->account[$i];
                $data[$i]->realname = $users->realname[$i];
                $data[$i]->role     = $role;
                $data[$i]->group    = $users->group[$i] == 'ditto' ? (isset($prev['group']) ? $prev['group'] : '') : $users->group[$i];
                $data[$i]->email    = $users->email[$i];
                $data[$i]->gender   = $users->gender[$i];
                $data[$i]->password = md5(trim($users->password[$i]));
                $data[$i]->commiter = $users->commiter[$i];
                $data[$i]->join     = empty($users->join[$i]) ? '0000-00-00' : ($users->join[$i]);
                $data[$i]->skype    = $users->skype[$i];
                $data[$i]->qq       = $users->qq[$i];
                $data[$i]->dingding = $users->dingding[$i];
                $data[$i]->weixin   = $users->weixin[$i];
                $data[$i]->mobile   = $users->mobile[$i];
                $data[$i]->slack    = $users->slack[$i];
                $data[$i]->whatsapp = $users->whatsapp[$i];
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
                $this->dao->replace(TABLE_USERGROUP)->data($group)->exec();
            }
            unset($user->group);
            $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();

            /* Fix bug #2941 */
            $userID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('user', $userID, 'Created');

            if(dao::isError())
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }
            else
            {
                $this->computeUserView($user->account);
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
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword(true)) return;

        $oldUser = $this->getById($userID, 'id');

        $userID = $oldUser->id;
        $user   = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->remove('password1, password2, groups,verifyPassword, passwordStrength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }
        $requiredFields = array();
        foreach(explode(',', $this->config->user->edit->requiredFields) as $field)
        {
            if(!isset($this->lang->user->contactFieldList[$field]) or strpos($this->config->user->contactField, $field) !== false) $requiredFields[$field] = $field;
        }
        $requiredFields = join(',', $requiredFields);

        $this->dao->update(TABLE_USER)->data($user)
            ->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->check('account', 'unique', "id != '$userID'")
            ->check('account', 'account')
            ->checkIF($this->post->email != '', 'email', 'email')
            ->where('id')->eq((int)$userID)
            ->exec();

        /* If account changed, update the privilege. */
        if($this->post->account != $oldUser->account)
        {
            $this->dao->update(TABLE_USERGROUP)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
            $this->dao->update(TABLE_USERVIEW)->set('account')->eq($this->post->account)->where('account')->eq($oldUser->account)->exec();
            if(strpos($this->app->company->admins, ',' . $oldUser->account . ',') !== false)
            {
                $admins = str_replace(',' . $oldUser->account . ',', ',' . $this->post->account . ',', $this->app->company->admins);
                $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
                if(!dao::isError()) $this->app->user->account = $this->post->account;
            }
        }

        $oldGroups = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->fetchPairs('group', 'group');
        $newGroups = zget($_POST, 'groups', array());
        sort($oldGroups);
        sort($newGroups);

        /* If change group then reset usergroup. */
        if(join(',', $oldGroups) != join(',', $newGroups))
        {
            /* Reset usergroup for account. */
            $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($this->post->account)->exec();

            /* Set usergroup for account. */
            if(isset($_POST['groups']))
            {
                foreach($this->post->groups as $groupID)
                {
                    $data          = new stdclass();
                    $data->account = $this->post->account;
                    $data->group   = $groupID;
                    $this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            /* Compute user view. */
            $this->computeUserView($this->post->account, true);
        }

        if(!empty($user->password) and $user->account == $this->app->user->account) $this->app->user->password = $user->password;
        if(!dao::isError())
        {
            $this->loadModel('score')->create('user', 'editProfile');
            $this->loadModel('action')->create('user', $userID, 'edited');
            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and $user->email != $oldUser->email)
            {
                $this->mail->syncSendCloud('delete', $oldUser->email);
                $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
    }

    /**
     * update session random.
     *
     * @access public
     * @return void
     */
    public function updateSessionRandom()
    {
        $random = mt_rand();
        $this->session->set('rand', $random);

        return $random;
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
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) die(js::alert($this->lang->user->error->verifyPassword));

        $oldUsers     = $this->dao->select('id, account, email')->from(TABLE_USER)->where('id')->in(array_keys($data->account))->fetchAll('id');
        $accountGroup = $this->dao->select('id, account')->from(TABLE_USER)->where('account')->in($data->account)->fetchGroup('account', 'id');

        $accounts = array();
        foreach($data->account as $id => $account)
        {
            $users[$id]['account']  = trim($account);
            $users[$id]['realname'] = $data->realname[$id];
            $users[$id]['commiter'] = $data->commiter[$id];
            $users[$id]['email']    = $data->email[$id];
            $users[$id]['join']     = $data->join[$id];
            $users[$id]['skype']    = $data->skype[$id];
            $users[$id]['qq']       = $data->qq[$id];
            $users[$id]['dingding'] = $data->dingding[$id];
            $users[$id]['weixin']   = $data->weixin[$id];
            $users[$id]['mobile']   = $data->mobile[$id];
            $users[$id]['slack']    = $data->slack[$id];
            $users[$id]['whatsapp'] = $data->whatsapp[$id];
            $users[$id]['phone']    = $data->phone[$id];
            $users[$id]['address']  = $data->address[$id];
            $users[$id]['zipcode']  = $data->zipcode[$id];
            $users[$id]['dept']     = $data->dept[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $data->dept[$id];
            $users[$id]['role']     = $data->role[$id] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : 0) : $data->role[$id];

            if(!empty($this->config->user->batchAppendFields))
            {
                $appendFields = explode(',', $this->config->user->batchAppendFields);
                foreach($appendFields as $appendField)
                {
                    if(empty($appendField)) continue;
                    if(!isset($data->$appendField)) continue;
                    $fieldList = $data->$appendField;
                    $users[$id][$appendField] = $fieldList[$id];
                }
            }

            if(isset($accountGroup[$account]) and count($accountGroup[$account]) > 1) die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(in_array($account, $accounts)) die(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(!validater::checkAccount($users[$id]['account'])) die(js::error(sprintf($this->lang->user->error->account, $id)));
            if($users[$id]['realname'] == '') die(js::error(sprintf($this->lang->user->error->realname, $id)));
            if($users[$id]['email'] and !validater::checkEmail($users[$id]['email'])) die(js::error(sprintf($this->lang->user->error->mail, $id)));

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
                $this->dao->update(TABLE_USERVIEW)->set('account')->eq($user['account'])->where('account')->eq($oldAccount)->exec();
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
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->remove('account, password1, password2, originalPassword, passwordStrength')
            ->get();

        if(empty($_POST['originalPassword']) or $this->post->originalPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['originalPassword'][] = $this->lang->user->error->originalPassword;
            return false;
        }

        $this->dao->update(TABLE_USER)->data($user)->autoCheck()->where('id')->eq((int)$userID)->exec();
        $this->app->user->password       = $user->password;
        $this->app->user->modifyPassword = false;
        if(!dao::isError())
        {
            $this->loadModel('score')->create('user', 'changePassword', $this->computePasswordStrength($this->post->password1));
        }
    }

    /**
     * Reset password.
     *
     * @access public
     * @return bool
     */
    public function resetPassword()
    {
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword()) return;

        $user = $this->getById($this->post->account);
        if(!$user) return false;

        $password = md5($this->post->password1);
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
        $_POST['password1'] = trim($_POST['password1']);
        $_POST['password2'] = trim($_POST['password2']);
        if(!$canNoPassword and empty($_POST['password1'])) dao::$errors['password'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
        if($this->post->password1 != false)
        {
            if($this->post->password1 != $this->post->password2) dao::$errors['password'][] = $this->lang->error->passwordsame;
            if(!validater::checkReg($this->post->password1, '|(.){6,}|')) dao::$errors['password'][] = $this->lang->error->passwordrule;

            if(isset($this->config->safe->mode) and ($this->post->passwordStrength < $this->config->safe->mode)) dao::$errors['password1'][] = $this->lang->user->weakPassword;
            if(!empty($this->config->safe->changeWeak))
            {
                if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                if(strpos(",{$this->config->safe->weak},", ",{$this->post->password1},") !== false) dao::$errors['password1'][] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
            }
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

            $user->lastTime       = $user->last;
            $user->last           = date(DT_DATETIME1, $last);
            $user->admin          = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $user->modifyPassword = ($user->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
            if($user->modifyPassword) $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
            if(!$user->modifyPassword and !empty($this->config->safe->changeWeak))
            {
                $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
                if($user->modifyPassword) $user->modifyPasswordReason = 'weak';
            }

            /* code for bug #2729. */
            if(defined('IN_USE')) $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();

            /* Create cycle todo in login. */
            $todoList = $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('account')->eq($user->account)->fetchAll('id');
            $this->loadModel('todo')->createByCycle($todoList);
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
        $this->loadModel('score')->create('user', 'login');
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
        $this->loadModel('score')->create('user', 'login');
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
                ->andWhere('role')->ne('limited')
                ->fetchAll();
            $acls = array();
            $viewAllow    = false;
            $productAllow = false;
            $projectAllow = false;
            $actionAllow  = false;
            foreach($groups as $group)
            {
                $acl = json_decode($group->acl, true);
                if(empty($group->acl))
                {
                    $productAllow = true;
                    $projectAllow = true;
                    $viewAllow    = true;
                    $actionAllow  = true;
                    break;
                }

                if(empty($acl['products'])) $productAllow = true;
                if(empty($acl['projects'])) $projectAllow = true;
                if(empty($acl['views']))    $viewAllow    = true;
                if(!isset($acl['actions'])) $actionAllow  = true;
                if(empty($acls) and !empty($acl))
                {
                    $acls = $acl;
                    continue;
                }

                if(!empty($acl['views'])) $acls['views'] = array_merge($acls['views'], $acl['views']);
                if(!empty($acl['products'])) $acls['products'] = !empty($acls['products']) ? array_merge($acls['products'], $acl['products']) : $acl['products'];
                if(!empty($acl['projects'])) $acls['projects'] = !empty($acls['projects']) ? array_merge($acls['projects'], $acl['projects']) : $acl['projects'];
                if(!empty($acl['actions'])) $acls['actions'] = !empty($acls['actions']) ? ($acl['actions'] + $acls['actions']) : $acl['actions'];
            }

            if($productAllow) $acls['products'] = array();
            if($projectAllow) $acls['projects'] = array();
            if($viewAllow)    $acls['views']    = array();
            if($actionAllow)  unset($acls['actions']);

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
        setcookie('keepLogin', 'on', $this->config->cookieLife, $this->config->webRoot, '', false, true);
        setcookie('za', $user->account, $this->config->cookieLife, $this->config->webRoot, '', false, true);
        setcookie('zp', sha1($user->account . $user->password . $this->server->request_time), $this->config->cookieLife, $this->config->webRoot, '', false, true);
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
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t2.id_desc')
            ->fetchAll();

        /* Judge whether the project is delayed. */
        foreach($projects as $project)
        {
            if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
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
     * @param string $account
     * @param string $params  withempty|withnote
     *
     * @access public
     * @return object
     */
    public function getContactLists($account, $params= '')
    {
        $contacts  = $this->getListByAccount($account);
        $globalIDs = isset($this->config->my->global->globalContacts) ? $this->config->my->global->globalContacts : '';

        if(!empty($globalIDs))
        {
            $globalIDs      = explode(',', $globalIDs);
            $globalContacts = $this->dao->select('id, listName')->from(TABLE_USERCONTACT)->where('id')->in($globalIDs)->fetchPairs();
            foreach($globalContacts as $id => $contact)
            {
                if(in_array($id, array_keys($contacts))) unset($globalContacts[$id]);
            }
            if(!empty($globalContacts)) $contacts = $globalContacts + $contacts;
        }

        if(empty($contacts)) return array();

        if(strpos($params, 'withempty') !== false) $contacts = array('' => '') + $contacts;
        if(strpos($params, 'withnote')  !== false) $contacts = array('' => $this->lang->user->contacts->common) + $contacts;

        return $contacts;
    }

    /**
     * Get Contact List by account.
     *
     * @param string $account
     *
     * @access public
     * @return array
     */
    public function getListByAccount($account)
    {
        return $this->dao->select('id, listName')->from(TABLE_USERCONTACT)->where('account')->eq($account)->fetchPairs();
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

        if(empty($data->listName))
        {
            dao::$errors['listName'][] = sprintf($this->lang->error->notempty, $this->lang->user->contacts->listName);
            die(js::error(dao::getError()));
        }

        $this->dao->insert(TABLE_USERCONTACT)->data($data)
            ->autoCheck()
            ->exec();
        if(dao::isError()) die(js::error(dao::getError()));

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

        if(empty($data->listName))
        {
            dao::$errors['listName'][] = sprintf($this->lang->error->notempty, $this->lang->user->contacts->listName);
            die(js::error(dao::getError()));
        }

        $this->dao->update(TABLE_USERCONTACT)->data($data)
            ->where('id')->eq($listID)
            ->exec();
        if(dao::isError()) die(js::error(dao::getError()));
    }

    /**
     * Update global contact.
     *
     * @param      $listID
     * @param bool $isPush
     *
     * @access public
     * @return void
     */
    public function setGlobalContacts($listID, $isPush = true)
    {
        $contacts    = $this->loadModel('setting')->getItem("owner=system&module=my&section=global&key=globalContacts");
        $contactsIDs = empty($contacts) ? array() : explode(',', $contacts);
        if($isPush)
        {
            if(!in_array($listID, $contactsIDs)) array_push($contactsIDs, $listID);
        }
        else
        {
            $key = array_search($listID, $contactsIDs);
            if($key !== false) array_splice($contactsIDs, $key, 1);
        }
        $this->loadModel('setting')->setItem('system.my.global.globalContacts', join(',', $contactsIDs));
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
        $newUser = new stdclass();
        foreach($user as $key => $value)$newUser->$key = $value;
        unset($newUser->password);
        unset($newUser->deleted);
        $newUser->company = $this->app->company->name;

        /* App client will use session id as token. */
        $newUser->token = session_id();

        return array('user' => $newUser);
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

    /**
     * Check Tmp dir.
     *
     * @access public
     * @return void
     */
    public function checkTmp()
    {
        if(!is_dir($this->app->tmpRoot))   mkdir($this->app->tmpRoot,   0755, true);
        if(!is_dir($this->app->cacheRoot)) mkdir($this->app->cacheRoot, 0755, true);
        if(!is_dir($this->app->logRoot))   mkdir($this->app->logRoot,   0755, true);
        if(!is_dir($this->app->logRoot))   return false;

        $file = $this->app->logRoot . DS . 'demo.txt';
        if($fp = @fopen($file, 'a+'))
        {
            @fclose($fp);
            @unlink($file);
        }
        else
        {
            return false;
        }
        return true;
    }

    /**
     * Compute user view.
     * 
     * @param  string $account 
     * @param  bool   $force 
     * @access public
     * @return object
     */
    public function computeUserView($account = '', $force = false)
    {
        if(empty($account)) $account = $this->session->user->account;
        if(empty($account)) return array();

        $userView = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch();
        if(empty($userView) or $force)
        {
            $isAdmin = strpos($this->app->company->admins, ',' . $account . ',') !== false;
            $groups  = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($account)->fetchPairs('group', 'group');
            $groups  = ',' . join(',', $groups) . ',';

            static $allProducts, $allProjects, $projectProducts, $teams;
            if($allProducts === null) $allProducts = $this->dao->select('id,PO,QD,RD,createdBy,acl,whitelist')->from(TABLE_PRODUCT)->where('acl')->ne('open')->fetchAll('id');
            if($allProjects === null) $allProjects = $this->dao->select('id,PO,PM,QD,RD,acl,whitelist')->from(TABLE_PROJECT)->where('acl')->ne('open')->fetchAll('id');
            if($projectProducts === null)
            {
                $stmt = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->query();
                while($projectProduct = $stmt->fetch())
                {
                    $projectProducts[$projectProduct->product][$projectProduct->project] = $projectProduct->project;
                }
            }
            if($teams === null)
            {
                $stmt = $this->dao->select('root,account')->from(TABLE_TEAM)->where('type')->eq('project')->query();
                while($team = $stmt->fetch()) $teams[$team->root][$team->account] = $team->account;
            }

            $userView = new stdclass();
            $userView->account  = $account;
            $userView->products = array();
            $userView->projects = array();
            if($isAdmin)
            {
                $userView->products = join(',', array_keys($allProducts));
                $userView->projects = join(',', array_keys($allProjects));
            }
            else
            {
                $products = array();
                foreach($allProducts as $id => $product)
                {
                    if($this->checkProductPriv($product, $account, $groups, zget($projectProducts, $id, array()), $teams)) $products[$id] = $id;
                }
                $userView->products = join(',', $products);

                $projects = array();
                foreach($allProjects as $id => $project)
                {
                    $projectTeams = isset($teams[$id]) ? $teams[$id] : array();
                    if($this->checkProjectPriv($project, $account, $groups, $projectTeams)) $projects[$id] = $id;
                }
                $userView->projects = join(',', $projects);
            }
            $this->dao->replace(TABLE_USERVIEW)->data($userView)->exec();
        }

        return $userView;
    }

    /**
     * Grant user view.
     * 
     * @param  string $account 
     * @param  array  $acls 
     * @access public
     * @return object
     */
    public function grantUserView($account = '', $acls = array())
    {
        if(empty($account)) $account = $this->session->user->account;
        if(empty($account)) return array();
        if(empty($acls) and !empty($this->session->user->rights['acls'])) $acls = $this->session->user->rights['acls'];

        $userView = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch();
        if(empty($userView)) $userView = $this->computeUserView($account);

        $openedProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('acl')->eq('open')->fetchAll('id');
        $openedProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('acl')->eq('open')->fetchAll('id');

        $openedProducts = join(',', array_keys($openedProducts));
        $openedProjects = join(',', array_keys($openedProjects));

        $userView->projects = rtrim($userView->projects, ',') . ',' . $openedProjects;
        $userView->products = rtrim($userView->products, ',') . ',' . $openedProducts;

        if(isset($_SESSION['user']->admin)) $isAdmin = $this->session->user->admin;
        if(!isset($isAdmin)) $isAdmin = strpos($this->app->company->admins, ",{$account},") !== false;
        if(!empty($acls['products']) and !$isAdmin)
        {
            $grantProducts = '';
            foreach($acls['products'] as $productID)
            {
                if(strpos(",{$userView->products},", ",{$productID},") !== false) $grantProducts .= ",{$productID}";
            }
            $userView->products = $grantProducts;
        }
        if(!empty($acls['projects']) and !$isAdmin)
        {
            $grantProjects = '';
            foreach($acls['projects'] as $projectID)
            {
                if(strpos(",{$userView->projects},", ",{$projectID},") !== false) $grantProjects .= ",{$projectID}";
            }
            $userView->projects = $grantProjects;
        }

        $userView->products = trim($userView->products, ',');
        $userView->projects = trim($userView->projects, ',');

        return $userView;
    }

    /**
     * Update user view.
     * 
     * @param  int|array $objectID 
     * @param  string    $objectType 
     * @param  array     $users 
     * @access public
     * @return void
     */
    public function updateUserView($objectIdList, $objectType, $users = array())
    {
        $table = '';
        if($objectType == 'product') $table = TABLE_PRODUCT;
        if($objectType == 'project') $table = TABLE_PROJECT;
        if(empty($table)) return false;

        if(is_numeric($objectIdList)) $objectIdList = array($objectIdList);
        if(!is_array($objectIdList)) return false;

        $allGroups  = $this->dao->select('account,`group`')->from(TABLE_USERGROUP)->fetchAll();
        $userGroups = array();
        $groupUsers = array();
        foreach($allGroups as $group)
        {
            if(!isset($userGroups[$group->account])) $userGroups[$group->account] = '';
            $userGroups[$group->account] .= "{$group->group},";
            $groupUsers[$group->group][$group->account] = $group->account;
        }

        $linkedProductProjects = array();
        $linkedProjectProducts = array();
        if($objectType == 'product')
        {
            $stmt = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->where('product')->in($objectIdList)->query();
            while($projectProduct = $stmt->fetch())
            {
                $linkedProductProjects[$projectProduct->product][$projectProduct->project] = $projectProduct->project;
                $linkedProjectProducts[$projectProduct->project][$projectProduct->product] = $projectProduct->product;
            }
        }

        $teamGroups = array();
        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('type')->eq('project')
            ->beginIF($objectType == 'product')->andWhere('root')->in(array_keys($linkedProjectProducts))->fi()
            ->beginIF($objectType == 'project')->andWhere('root')->in($objectIdList)->fi()
            ->query();
        while($team = $stmt->fetch())
        {
            if($objectType == 'product')
            {
                $productIdList = zget($linkedProjectProducts, $team->root, array());
                foreach($productIdList as $productID) $teamGroups[$productID][$team->root][$team->account] = $team->account;
            }
            elseif($objectType == 'project')
            {
                $teamGroups[$team->root][$team->account] = $team->account;
            }
        }

        $whiteList = array();
        $objects   = $this->dao->select('*')->from($table)->where('id')->in($objectIdList)->fetchAll('id');
        foreach($objects as $objectID => $object)
        {
            if($object->acl == 'open')
            {
                unset($objects[$objectID]);
                continue;
            }
            if(!empty($users)) continue;

            if($objectType == 'product') $whiteList += $this->getProductWhiteListUsers($object, $groupUsers, zget($linkedProductProjects, $objectID, array()), zget($teamGroups, $objectID, array()));
            if($objectType == 'project') $whiteList += $this->getProjectWhiteListUsers($object, $groupUsers, zget($teamGroups, $objectID, array()));
        }
        if(empty($objects)) return true;

        if($whiteList) $users = $whiteList;

        $field = $objectType == 'product' ? 'products' : 'projects';
        $stmt  = $this->dao->select("account,{$field}")->from(TABLE_USERVIEW)->where('account')->in($users);
        if($whiteList)
        {
            foreach($objects as $objectID => $object) $stmt->orWhere("CONCAT(',', {$field}, ',')")->like("%,{$objectID},%");
        }
        $userViews = $stmt->fetchPairs('account', $field);

        foreach($userViews as $account => $view)
        {
            foreach($objects as $objectID => $object)
            {
                $linkedProjects = $objectType == 'product' ? zget($linkedProductProjects, $objectID, array()) : array();
                $members        = zget($teamGroups, $objectID, array());

                if($objectType == 'product')
                {
                    $hasPriv = $this->checkProductPriv($object, $account, zget($userGroups, $account, ''), $linkedProjects, $members);
                    if($hasPriv and strpos(",{$view},", ",{$objectID},") === false)  $view .= ",{$objectID}";
                    if(!$hasPriv and strpos(",{$view},", ",{$objectID},") !== false) $view  = trim(str_replace(",{$objectID},", ',', ",{$view},"), ',');
                }
                elseif($objectType == 'project')
                {
                    $hasPriv = $this->checkProjectPriv($object, $account, zget($userGroups, $account, ''), $members);
                    if($hasPriv and strpos(",{$view},", ",{$objectID},") === false)  $view .= ",{$objectID}";
                    if(!$hasPriv and strpos(",{$view},", ",{$objectID},") !== false) $view  = trim(str_replace(",{$objectID},", ',', ",{$view},"), ',');
                }
            }
            if($userViews[$account] != $view) $this->dao->update(TABLE_USERVIEW)->set($field)->eq($view)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Check product priv.
     * 
     * @param  object $product 
     * @param  string $account 
     * @param  string $groups 
     * @param  array  $linkedProjects 
     * @param  array  $teams 
     * @access public
     * @return bool
     */
    public function checkProductPriv($product, $account, $groups, $linkedProjects, $teams) 
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;
        if($product->PO == $account OR $product->QD == $account OR $product->RD == $account OR $product->createdBy == $account OR (isset($product->feedback) && $product->feedback == $account)) return true;
        if($product->acl == 'open') return true;

        if($product->acl == 'custom')
        {
            foreach(explode(',', $product->whitelist) as $whitelist)
            {
                if(empty($whitelist)) continue;
                if(strpos(",{$groups},", ",$whitelist,") !== false) return true;
            }
        }

        if(!empty($linkedProjects))
        {
            foreach($linkedProjects as $projectID)
            {
                if(isset($teams[$projectID][$account])) return true;
            }
        }

        return false;
    }

    /**
     * Get product white list users.
     * 
     * @param  object $product 
     * @param  array  $groupUsers 
     * @param  array  $linkedProjects 
     * @param  array  $teams 
     * @access public
     * @return array
     */
    public function getProductWhiteListUsers($product, $groupUsers, $linkedProjects, $teams)
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin] = $admin;

        $users[$product->PO]        = $product->PO;
        $users[$product->QD]        = $product->QD;
        $users[$product->RD]        = $product->RD;
        $users[$product->createdBy] = $product->createdBy;
        if(isset($product->feedback)) $users[$product->feedback] = $product->feedback;

        if($product->acl == 'custom')
        {
            foreach(explode(',', $product->whitelist) as $whitelist)
            {
                if(empty($whitelist)) continue;
                $users += zget($groupUsers, $whitelist, array());
            }
        }

        if(!empty($linkedProjects))
        {
            foreach($linkedProjects as $projectID) $users += zget($teams, $projectID, array());
        }

        return $users;
    }

    /**
     * Check project priv.
     * 
     * @param  object    $project 
     * @param  string    $account 
     * @param  string    $groups 
     * @param  array     $teams 
     * @access public
     * @return bool
     */
    public function checkProjectPriv($project, $account, $groups, $teams)
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;
        if($project->PO == $account OR $project->QD == $account OR $project->RD == $account OR $project->PM == $account) return true;
        if($project->acl == 'open') return true;
        if(isset($teams[$account])) return true;

        if($project->acl == 'custom')
        {
            foreach(explode(',', $project->whitelist) as $whitelist)
            {
                if(empty($whitelist)) continue;
                if(strpos(",{$groups},", ",$whitelist,") !== false) return true;
            }
        }

        return false;
    }

    /**
     * Get project white list users.
     * 
     * @param  object $project 
     * @param  array  $groupUsers 
     * @param  array  $teams 
     * @access public
     * @return array
     */
    public function getProjectWhiteListUsers($project, $groupUsers, $teams)
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin] = $admin;

        $users[$project->PO]       = $project->PO;
        $users[$project->QD]       = $project->QD;
        $users[$project->RD]       = $project->RD;
        $users[$project->PM]       = $project->PM;
        $users[$project->openedBy] = $project->openedBy;
        if(isset($project->feedback)) $users[$project->feedback] = $project->feedback;

        $users += $teams;

        if($project->acl == 'custom')
        {
            foreach(explode(',', $project->whitelist) as $whitelist)
            {
                if(empty($whitelist)) continue;
                $users += zget($groupUsers, $whitelist, array());
            }
        }

        return $users;
    }

    /**
     * Judge an action is clickable or not.
     * 
     * @param  object    $user 
     * @param  string    $action 
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($user, $action)
    {
        global $config;
        $action = strtolower($action);

        if($action == 'unbind' and empty($user->ranzhi)) return false;
        if($action == 'unlock' and (strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) >= $config->user->lockMinutes * 60) return false;

        return true;
    }

    /**
     * Save user template.
     *
     * @param  string    $type 
     * @access public
     * @return void
     */
    public function saveUserTemplate($type)
    {
        $template = fixer::input('post')
            ->setDefault('account', $this->app->user->account)
            ->setDefault('type', $type)
            ->stripTags('content', $this->config->allowedTags)
            ->get();

        $condition = "`type`='$type' and account='{$this->app->user->account}'";
        $this->dao->insert(TABLE_USERTPL)->data($template)->batchCheck('title, content', 'notempty')->check('title', 'unique', $condition)->exec();
        if(!dao::isError()) $this->loadModel('score')->create('bug', 'saveTplModal', $this->dao->lastInsertID());
    }

    /**
     * Get User Template.
     * 
     * @param  string    $type 
     * @access public
     * @return array
     */
    public function getUserTemplates($type)
    {
        return $this->dao->select('id,account,title,content,public')
            ->from(TABLE_USERTPL)
            ->where('type')->eq($type)
            ->andwhere('account', true)->eq($this->app->user->account)
            ->orWhere('public')->eq('1')
            ->markRight(1)
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * Get personal data.
     * 
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getPersonalData($account = '')
    {
        if(empty($account)) $account = $this->app->user->account;

        $personalData = array();
        $personalData['createdTodo']  = $this->dao->select('count(*) as count')->from(TABLE_TODO)->where('account')->eq($account)->fetch('count');
        $personalData['createdStory'] = $this->dao->select('count(*) as count')->from(TABLE_STORY)->where('openedBy')->eq($account)->andWhere('type')->eq('story')->andWhere('deleted')->eq('0')->fetch('count');
        $personalData['finishedTask'] = $this->dao->select('count(*) as count')->from(TABLE_TASK)->where('finishedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        $personalData['resolvedBug']  = $this->dao->select('count(*) as count')->from(TABLE_BUG)->where('resolvedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        $personalData['createdCase']  = $this->dao->select('count(*) as count')->from(TABLE_CASE)->where('openedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');

        return $personalData;
    }
}
