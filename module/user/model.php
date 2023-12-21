<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * Set users list.
     *
     * @param  array    $users
     * @param  string   $account
     * @access public
     * @return string
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
     * Get inside users list of current company.
     *
     * @param  string $params
     * @param  string $fields
     * @access public
     * @return array
     */
    public function getList($params = 'nodeleted', $fields = '*')
    {
        return $this->dao->select($fields)->from(TABLE_USER)
            ->where('1=1')
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq('inside')->fi()
            ->beginIF(strpos($params, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
            ->orderBy('account')
            ->fetchAll();
    }

    /**
     * Get user information by accounts.
     *
     * @param  array    $accounts
     * @param  string   $keyField
     * @access public
     * @return array
     */
    public function getListByAccounts($accounts = array(), $keyField = 'id')
    {
        if(empty($accounts)) return array();

        return $this->dao->select('id,account,realname,avatar,role')->from(TABLE_USER)
            ->where('account')->in($accounts)
            ->fetchAll($keyField);
    }

    /**
     * Get the account=>realname pairs.
     *
     * @param  string       $params   noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @param  string       $usersToAppended  account1,account2
     * @param  int          $maxCount
     * @param  string|array $accounts
     * @access public
     * @return array
     */
    public function getPairs($params = '', $usersToAppended = '', $maxCount = 0, $accounts = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getUserPairs();
        /* Set the query fields and orderBy condition.
         *
         * If there's xxfirst in the params, use INSTR function to get the position of role fields in a order string,
         * thus to make sure users of this role at first.
         */
        $fields = 'id, account, realname, deleted';
        $type   = (strpos($params, 'outside') !== false) ? 'outside' : 'inside';
        if(strpos($params, 'pofirst') !== false) $fields .= ", INSTR(',pd,po,', role) AS roleOrder";
        if(strpos($params, 'pdfirst') !== false) $fields .= ", INSTR(',po,pd,', role) AS roleOrder";
        if(strpos($params, 'qafirst') !== false) $fields .= ", INSTR(',qd,qa,', role) AS roleOrder";
        if(strpos($params, 'qdfirst') !== false) $fields .= ", INSTR(',qa,qd,', role) AS roleOrder";
        if(strpos($params, 'pmfirst') !== false) $fields .= ", INSTR(',td,pm,', role) AS roleOrder";
        if(strpos($params, 'devfirst')!== false) $fields .= ", INSTR(',td,pm,qd,qa,dev,', role) AS roleOrder";
        $orderBy = strpos($params, 'first') !== false ? 'roleOrder DESC, account' : 'account';

        $keyField = (strpos($params, 'useid')!== false) ? 'id' : "account";

        /* Get raw records. */
        $this->app->loadConfig('user');
        unset($this->config->user->moreLink);

        $users = $this->dao->select($fields)->from(TABLE_USER)
            ->where('1=1')
            ->beginIF(strpos($params, 'nodeleted') !== false or empty($this->config->user->showDeleted))->andWhere('deleted')->eq('0')->fi()
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq($type)->fi()
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->beginIF($this->config->vision and $this->app->rawModule !== 'kanban')->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy($orderBy)
            ->beginIF($maxCount)->limit($maxCount)->fi()
            ->fetchAll($keyField);

        if($maxCount and $maxCount == count($users))
        {
            if(is_array($usersToAppended)) $usersToAppended = join(',', $usersToAppended);
            $moreLinkParams = "params={$params}&usersToAppended={$usersToAppended}";

            $moreLink  = helper::createLink('user', 'ajaxGetMore');
            $moreLink .= strpos($moreLink, '?') === false ? '?' : '&';
            $moreLink .= "params=" . base64_encode($moreLinkParams);
            $this->config->user->moreLink = $moreLink;
        }

        if($usersToAppended)
        {
            $users += $this->dao->select($fields)->from(TABLE_USER)
                ->where('account')->in($usersToAppended)
                ->beginIF(strpos($params, 'nodeleted') !== false)->andWhere('deleted')->eq('0')->fi()
                ->fetchAll($keyField);
        }

        /* Cycle the user records to append the first letter of his account. */
        foreach($users as $account => $user)
        {
            if(strpos($params, 'showid') !== false)
            {
                $users[$account] = $user->id;
            }
            else
            {
                $firstLetter = ucfirst(mb_substr($user->account, 0, 1)) . ':';
                if(strpos($params, 'noletter') !== false or !empty($this->config->isINT)) $firstLetter = '';
                $users[$account] =  $firstLetter . (($user->deleted and strpos($params, 'realname') === false) ? $user->account : ($user->realname ? $user->realname : $user->account));
            }
        }

        /* Put the current user first. */
        $users = $this->processAccountSort($users);

        /* Append empty, closed, and guest users. */
        if(strpos($params, 'noempty')   === false) $users = array('' => '') + $users;
        if(strpos($params, 'noclosed')  === false) $users = $users + array('closed' => 'Closed');
        if(strpos($params, 'withguest') !== false) $users = $users + array('guest' => 'Guest');

        return $users;
    }

    /**
     * Get user's avatar pairs.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getAvatarPairs($params = 'nodeleted')
    {
        $avatarPairs = array();
        $userList    = $this->getList($params, 'account,avatar');
        foreach($userList as $user) $avatarPairs[$user->account] = $user->avatar;

        return $avatarPairs;
    }

    /**
     * Get commiters from the user table.
     *
     * @param  string  $field
     * @access public
     * @return array
     */
    public function getCommiters($field = 'realname')
    {
        $rawCommiters = $this->dao->select('commiter, account, realname')->from(TABLE_USER)->where('commiter')->ne('')->fetchAll();
        if(!$rawCommiters) return array();

        $commiters = array();
        foreach($rawCommiters as $commiter)
        {
            $userCommiters = explode(',', $commiter->commiter);
            foreach($userCommiters as $userCommiter)
            {
                $commiters[$userCommiter] = $commiter->$field ? $commiter->$field : $commiter->account;
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
     * @param  bool      $needRole
     * @access public
     * @return array
     */
    public function getUserRoles($users, $needRole = false)
    {
        $this->app->loadLang('user');
        $users = $this->dao->select('account, role')->from(TABLE_USER)->where('account')->in($users)->fetchPairs();
        if(!$users) return array();
        if($needRole) return $users;

        foreach($users as $account => $role) $users[$account] = zget($this->lang->user->roleList, $role, $role);
        return $users;
    }

    /**
     * Get users info list for displaying
     *
     * @param  array    $accounts
     * @param  int      $deptID
     * @param  string   $type
     * @access public
     * @return array
     */
    public function getUserDisplayInfos($accounts, $deptID = 0, $type = 'inside')
    {
        $users = $this->dao->select('account, realname, avatar, dept, role')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetchAll('account');

        $depts = $this->loadModel('dept')->getDeptPairs($deptID);

        $infos = array();
        foreach($users as $info)
        {
            $info->roleName = zget($this->lang->user->roleList, $info->role, '');
            if(isset($depts[$info->dept])) $info->deptName = $depts[$info->dept];
            if(empty($info->avatar))   unset($info->avatar);
            if(empty($info->realname)) unset($info->realname);
            if(empty($info->role))     unset($info->role);
            if(empty($info->roleName)) unset($info->roleName);
            $infos[$info->account] = $info;
        }
        return $infos;
    }

    /**
     * Get user info by ID.
     *
     * @param  string  $userID
     * @param  string  $field id|account
     * @access public
     * @return object|bool
     */
    public function getById($userID, $field = 'account')
    {
        /* Return current user when user is guest or empty to make sure pages in dashboard work fine. */
        if(empty($userID) && $this->app->user->account == 'guest') return $this->app->user;

        if($field == 'id') $userID = (int)$userID;
        if($field == 'account') $userID = str_replace(' ', '', $userID);

        $user = $this->dao->select('*')->from(TABLE_USER)->where("`$field`")->eq($userID)->fetch();
        if(!$user) return false;
        $user->last = date(DT_DATETIME1, $user->last);
        return $user;
    }

    /**
     * Get users by sql.
     *
     * @param  string  $browseType inside|outside|all
     * @param  string  $query
     * @param  object  $pager
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getByQuery($browseType = 'inside', $query = '', $pager = null, $orderBy = 'id')
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($browseType == 'inside')->andWhere('type')->eq('inside')->fi()
            ->beginIF($browseType == 'outside')->andWhere('type')->eq('outside')->fi()
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Create a user.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $_POST['account'] = trim($_POST['account']);
        if(!$this->checkPassword()) return false;
        if(strtolower($_POST['account']) == 'guest') return false;

        $user = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setDefault('type', 'inside')
            ->setDefault('company', 0)
            ->setDefault('visions', '')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->password1 == false, 'password', '')
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->join('visions', ',')
            ->remove('new, group, password1, password2, verifyPassword, passwordStrength,passwordLength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        if(isset($_POST['new']))
        {
            if(empty($user->company))
            {
                dao::$errors['company'][] = $this->lang->user->error->companyEmpty;
                return false;
            }

            $company = new stdClass();
            $company->name = $user->company;
            $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

            $user->company = $this->dao->lastInsertID();
        }

        if($user->type == 'outside')
        {
            $requiredFieldList = explode(',', $this->config->user->create->requiredFields);
            if(in_array('dept', $requiredFieldList))     unset($requiredFieldList[array_search('dept', $requiredFieldList)]);
            if(in_array('commiter', $requiredFieldList)) unset($requiredFieldList[array_search('commiter', $requiredFieldList)]);
            $this->config->user->create->requiredFields = implode(',', $requiredFieldList);
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

            /* Set usergroup for account. */
            if(isset($_POST['group']))
            {
                foreach($this->post->group as $groupID)
                {
                    $data          = new stdclass();
                    $data->account = $this->post->account;
                    $data->group   = $groupID;
                    $data->project = '';
                    $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            $this->computeUserView($user->account);
            $this->loadModel('action')->create('user', $userID, 'Created');
            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);

            return $userID;
        }
    }

    /**
     * Batch create users.
     *
     * @param  int    $users
     * @access public
     * @return array
     */
    public function batchCreate()
    {
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) helper::end(js::alert($this->lang->user->error->verifyPassword));

        $users    = fixer::input('post')->get();
        $data     = array();
        $accounts = array();
        for($i = 1; $i <= $this->config->user->batchCreate; $i++)
        {
            $users->account[$i] = trim($users->account[$i]);
            if($users->account[$i] != '')
            {
                if(strtolower($users->account[$i]) == 'guest') helper::end(js::error(sprintf($this->lang->user->error->reserved, $i)));
                $account = $this->dao->select('account')->from(TABLE_USER)->where('account')->eq($users->account[$i])->fetch();
                if($account) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $i)));
                if(in_array($users->account[$i], $accounts)) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $i)));
                if(!validater::checkAccount($users->account[$i])) helper::end(js::error(sprintf($this->lang->user->error->account, $i)));
                if($users->realname[$i] == '') helper::end(js::error(sprintf($this->lang->user->error->realname, $i)));
                if(empty($users->visions[$i])) helper::end(js::error(sprintf($this->lang->user->error->visions, $i)));
                if($users->email[$i] and !validater::checkEmail($users->email[$i])) helper::end(js::error(sprintf($this->lang->user->error->mail, $i)));
                $users->password[$i] = (isset($prev['password']) and $users->ditto[$i] == 'on' and !$this->post->password[$i]) ? $prev['password'] : $this->post->password[$i];
                if(!validater::checkReg($users->password[$i], '|(.){6,}|')) helper::end(js::error(sprintf($this->lang->user->error->password, $i)));
                $role    = $users->role[$i] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : '') : $users->role[$i];
                $visions = in_array('ditto', $users->visions[$i]) ? (isset($prev['visions']) ? $prev['visions'] : array()) : $users->visions[$i];

                /* Check weak and common weak password. */
                if(isset($this->config->safe->mode) and $this->computePasswordStrength($users->password[$i]) < $this->config->safe->mode) helper::end(js::error(sprintf($this->lang->user->error->weakPassword, $i)));
                if(!empty($this->config->safe->changeWeak))
                {
                    if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                    if(strpos(",{$this->config->safe->weak},", ",{$users->password[$i]},") !== false) helper::end(js::error(sprintf($this->lang->user->error->dangerPassword, $i, $this->config->safe->weak)));
                }

                $data[$i] = new stdclass();
                if($users->userType == 'outside')
                {
                    $data[$i]->company    = $users->company[$i] == 'ditto' ? (isset($prev['company']) ? $prev['company'] : 0) : $users->company[$i];
                    $data[$i]->newCompany = isset($users->new[$i]) ? true : false;
                    $data[$i]->type       = 'outside';
                }
                else
                {
                    $data[$i]->dept = $users->dept[$i] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $users->dept[$i];
                    $data[$i]->join = empty($users->join[$i]) ? '0000-00-00' : ($users->join[$i]);
                    $data[$i]->type = 'inside';
                }
                $data[$i]->account  = $users->account[$i];
                $data[$i]->realname = $users->realname[$i];
                $data[$i]->role     = $role;
                $data[$i]->group    = in_array('ditto', isset($users->group[$i]) ? $users->group[$i] : array()) ? (isset($prev['group']) ? $prev['group'] : '') : $users->group[$i];
                $data[$i]->email    = $users->email[$i];
                $data[$i]->gender   = $users->gender[$i];
                $data[$i]->password = md5(trim($users->password[$i]));
                $data[$i]->commiter = $users->commiter[$i];
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
                $data[$i]->visions  = join(',', $visions);

                /* Check required fields. */
                foreach(explode(',', $this->config->user->create->requiredFields) as $field)
                {
                    $field = trim($field);
                    if(empty($field)) continue;

                    if(!isset($data[$i]->$field)) continue;
                    if(!empty($data[$i]->$field)) continue;

                    helper::end(js::error(sprintf($this->lang->error->notempty, $this->lang->user->$field)));
                }

                /* Change for append field, such as feedback. */
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
                if($users->userType == 'outside') $prev['company']  = $data[$i]->company;
                $prev['dept']     = $data[$i]->dept;
                $prev['role']     = $data[$i]->role;
                $prev['group']    = $data[$i]->group;
                $prev['visions']  = $visions;
                $prev['password'] = $users->password[$i];
            }
        }

        $this->loadModel('mail');
        $userIDList = array();
        foreach($data as $user)
        {
            if($user->type == 'outside' and $user->newCompany)
            {
                $company = new stdClass();
                $company->name = $user->company;
                $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

                $user->company = $this->dao->lastInsertID();
            }
            unset($user->newCompany);

            if(is_array($user->group))
            {
                foreach($user->group as $group)
                {
                    $groups = new stdClass();
                    $groups->account = $user->account;
                    $groups->group   = $group;
                    $groups->project = '';
                    $this->dao->insert(TABLE_USERGROUP)->data($groups)->exec();
                }
            }
            unset($user->group);
            $this->dao->insert(TABLE_USER)->data($user)->autoCheck()
                ->checkIF($user->email  != '', 'email',  'email')
                ->checkIF($user->phone  != '', 'phone',  'phone')
                ->checkIF($user->mobile != '', 'mobile', 'mobile')
                ->exec();

            /* Fix bug #2941 */
            $userID       = $this->dao->lastInsertID();
            $userIDList[] = $userID;
            $this->loadModel('action')->create('user', $userID, 'Created');

            if(dao::isError())
            {
                echo js::error(dao::getError());
                helper::end(js::reload('parent'));
            }
            else
            {
                $this->computeUserView($user->account);
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
        return $userIDList;
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
        if(!$this->checkPassword(true)) return false;

        $oldUser = $this->getById($userID, 'id');

        $userID = $oldUser->id;
        $user   = fixer::input('post')
            ->setDefault('join', '0000-00-00')
            ->setDefault('company', 0)
            ->setDefault('visions', '')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->setIF($this->post->email != false, 'email', trim($this->post->email))
            ->join('visions', ',')
            ->remove('new, password1, password2, groups,verifyPassword, passwordStrength,passwordLength')
            ->get();

        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;
            return false;
        }

        if(isset($_POST['new']))
        {
            if(empty($user->company))
            {
                dao::$errors['company'][] = $this->lang->user->error->companyEmpty;
                return false;
            }

            $company = new stdClass();
            $company->name = $user->company;
            $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

            $user->company = $this->dao->lastInsertID();
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
            ->checkIF($this->post->email  != '', 'email',  'email')
            ->checkIF($this->post->phone  != '', 'phone',  'phone')
            ->checkIF($this->post->mobile != '', 'mobile', 'mobile')
            ->where('id')->eq((int)$userID)
            ->exec();
        if(dao::isError()) return false;

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

        $userVisionList = $this->getVisionList();
        $user           = $this->getById($userID, 'id');
        $oldGroups      = array_keys($this->loadModel('group')->getByAccount($user->account, count($userVisionList) > 1 ? true : false));
        $newGroups      = zget($_POST, 'groups', array());
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
                    $data->project = '';
                    $this->dao->replace(TABLE_USERGROUP)->data($data)->exec();
                }
            }

            /* Compute user view. */
            $this->computeUserView($this->post->account, true);
        }

        if(!dao::isError())
        {
            if($user->account == $this->app->user->account)
            {
                if(!empty($user->password)) $this->app->user->password = $user->password;
                if(!empty($user->realname)) $this->app->user->realname = $user->realname;
            }
            $this->loadModel('score')->create('user', 'editProfile');
            $this->loadModel('action')->create('user', $userID, 'edited');
            $this->loadModel('mail');
            if($this->config->mail->mta == 'sendcloud' and $user->email != $oldUser->email)
            {
                $this->mail->syncSendCloud('delete', $oldUser->email);
                $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }

        if($_POST['account'] == $this->app->user->account and $user->role != $_SESSION['user']->role) $_SESSION['user']->role = $user->role;
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
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) helper::end(js::alert($this->lang->user->error->verifyPassword));

        $oldUsers     = $this->dao->select('id, account, email')->from(TABLE_USER)->where('id')->in(array_keys($data->account))->fetchAll('id');
        $accountGroup = $this->dao->select('id, account')->from(TABLE_USER)->where('account')->in($data->account)->fetchGroup('account', 'id');

        $accounts = array();
        foreach($data->account as $id => $account)
        {
            $users[$id]['account']  = trim($account);
            $users[$id]['realname'] = $data->realname[$id];
            $users[$id]['commiter'] = $data->commiter[$id];
            $users[$id]['email']    = $data->email[$id];
            $users[$id]['type']     = $data->type[$id];
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
            $users[$id]['visions']  = !empty($data->visions[$id]) ? join(',', $data->visions[$id]) : '';
            $users[$id]['dept']     = $data->dept[$id] == 'ditto' ? (isset($prev['dept']) ? $prev['dept'] : 0) : $data->dept[$id];
            $users[$id]['role']     = $data->role[$id] == 'ditto' ? (isset($prev['role']) ? $prev['role'] : 0) : $data->role[$id];

            /* Check required fields. */
            foreach(explode(',', $this->config->user->edit->requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($users[$id][$field])) continue;
                if(!empty($users[$id][$field])) continue;

                helper::end(js::error(sprintf($this->lang->error->notempty, $this->lang->user->$field)));
            }

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

            if(isset($accountGroup[$account]) and count($accountGroup[$account]) > 1) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(in_array($account, $accounts)) helper::end(js::error(sprintf($this->lang->user->error->accountDupl, $id)));
            if(!validater::checkAccount($users[$id]['account'])) helper::end(js::error(sprintf($this->lang->user->error->account, $id)));
            if($users[$id]['realname'] == '') helper::end(js::error(sprintf($this->lang->user->error->realname, $id)));
            if($users[$id]['email'] and !validater::checkEmail($users[$id]['email'])) helper::end(js::error(sprintf($this->lang->user->error->mail, $id)));

            $accounts[$id] = $account;
            $prev['dept']  = $users[$id]['dept'];
            $prev['role']  = $users[$id]['role'];
        }

        $this->loadModel('mail');
        foreach($users as $id => $user)
        {
            $this->dao->update(TABLE_USER)->data($user)
                ->autoCheck()
                ->checkIF($user['email']  != '', 'email',  'email')
                ->checkIF($user['phone']  != '', 'phone',  'phone')
                ->checkIF($user['mobile'] != '', 'mobile', 'mobile')
                ->where('id')->eq((int)$id)
                ->exec();
            $oldUser = $oldUsers[$id];
            if(dao::isError())
            {
                echo js::error(dao::getError());
                helper::end(js::reload('parent'));
            }
            else
            {
                if($this->config->mail->mta == 'sendcloud' and $user['email'] != $oldUser->email)
                {
                    $this->mail->syncSendCloud('delete', $oldUser->email);
                    $this->mail->syncSendCloud('sync', $user['email'], $user['realname']);
                }

                if($this->app->user->account == $user['account'] and !empty($user['realname'])) $this->app->user->realname = $user['realname'];
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
     * Update password.
     *
     * @param  string $userID
     * @access public
     * @return bool
     */
    public function updatePassword($userID)
    {
        if(!$this->checkPassword()) return false;

        $user = fixer::input('post')
            ->setIF($this->post->password1 != false, 'password', substr($this->post->password1, 0, 32))
            ->remove('account, password1, password2, originalPassword, passwordStrength,passwordLength')
            ->get();

        if(empty($_POST['originalPassword']) or $this->post->originalPassword != md5($this->app->user->password . $this->session->rand))
        {
            dao::$errors['originalPassword'][] = $this->lang->user->error->originalPassword;
            return false;
        }

        $this->dao->update(TABLE_USER)->data($user)->autoCheck()->where('id')->eq((int)$userID)->exec();
        $_SESSION['user']->password      = $user->password;
        $this->app->user->password       = $user->password;
        $this->app->user->modifyPassword = false;
        if(!dao::isError())
        {
            if(!empty($this->app->user->modifyPasswordReason)) $this->app->user->modifyPasswordReason = '';
            $this->loadModel('score')->create('user', 'changePassword', $this->computePasswordStrength($this->post->password1));
        }
        return true;
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
        if(!$this->checkPassword()) return false;

        $user = $this->getById($this->post->account);
        if(!$user) return false;

        $password = substr($this->post->password1, 0, 32);
        $this->dao->update(TABLE_USER)->set('password')->eq($password)->autoCheck()->where('account')->eq($this->post->account)->exec();
        return !dao::isError();
    }

    /**
     * Check the passwds posted.
     *
     * @param  bool   $canNoPassword
     * @access public
     * @return bool
     */
    public function checkPassword($canNoPassword = false)
    {
        $_POST['password1'] = trim($_POST['password1']);
        $_POST['password2'] = trim($_POST['password2']);
        if(!$canNoPassword and empty($_POST['password1'])) dao::$errors['password1'][] = sprintf($this->lang->error->notempty, $this->lang->user->password) . '<br/>';
        if(!empty($_POST['password1']))
        {
            if(isset($this->config->safe->mode) and ($this->post->passwordStrength < $this->config->safe->mode)) dao::$errors['password1'][] = zget($this->lang->user->placeholder->passwordStrengthCheck, $this->config->safe->mode, $this->lang->user->weakPassword) . '<br/>';

            if(isset($_POST['passwordLength']) and $this->post->passwordLength < 6 and empty(dao::$errors['password1'])) dao::$errors['password1'][] = zget($this->lang->user->placeholder->passwordStrengthCheck, 0, $this->lang->user->weakPassword) . '<br/>';

            if($this->post->password1 != $this->post->password2) dao::$errors['password1'][] = $this->lang->error->passwordsame . '<br/>';

            if(!empty($this->config->safe->changeWeak))
            {
                if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');

                if(strpos(",{$this->config->safe->weak},", ",{$this->post->password1},") !== false) dao::$errors['password1'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);

                $weaks = array();
                foreach(explode(',', $this->config->safe->weak) as $weak)
                {
                    $weak = md5(trim($weak));
                    $weaks[$weak] = $weak;
                }
                if(isset($weaks[substr($this->post->password1, 0, 32)])) dao::$errors['password1'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
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
        /* Check account rule in login.  */
        if(!validater::checkAccount($account)) return false;

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
            $ip   = helper::getRemoteIp();
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
            /* Check weak password when login. */
            if(!$user->modifyPassword and $this->app->moduleName == 'user' and $this->app->methodName == 'login' and isset($_POST['passwordStrength']))
            {
                $user->modifyPassword = (isset($this->config->safe->mode) and $this->post->passwordStrength < $this->config->safe->mode);
                if($user->modifyPassword) $user->modifyPasswordReason = 'passwordStrengthWeak';
            }

            /* code for bug #2729. */
            if(defined('IN_USE')) $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();

            /* Create cycle todo in login. */
            $todoList = $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('deleted')->eq('0')->andWhere('account')->eq($user->account)->fetchAll('id');
            $this->loadModel('todo')->createByCycle($todoList);

            /* Fix bug #17082. */
            if($user->avatar)
            {
                $avatarRoot = substr($user->avatar, 0, strpos($user->avatar, 'data/upload/'));
                if($this->config->webRoot != $avatarRoot) $user->avatar = substr_replace($user->avatar, $this->config->webRoot, 0, strlen($avatarRoot));
            }
        }
        return $user;
    }

    /**
     * Identify user by PHP_AUTH_USER.
     *
     * @access public
     * @return bool
     */
    public function identifyByPhpAuth()
    {
        $account  = $this->server->php_auth_user;
        $password = $this->server->php_auth_pw;
        $user     = $this->identify($account, $password);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $user->groups = $this->getGroups($account);
        $user->view   = $this->grantUserView($user->account, $user->rights['acls']);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');
        $this->loadModel('common')->loadConfigFromDB();

        return true;
    }

    /**
     * Identify user by cookie.
     *
     * @access public
     * @return bool
     */
    public function identifyByCookie()
    {
        $account  = $this->cookie->za;
        $authHash = $this->cookie->zp;
        $user     = $this->identify($account, $authHash);
        if(!$user) return false;

        $user->rights = $this->authorize($account);
        $user->groups = $this->getGroups($account);
        $user->view   = $this->grantUserView($user->account, $user->rights['acls']);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');
        $this->loadModel('common')->loadConfigFromDB();

        $this->keepLogin($user);

        return true;
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
        $account = filter_var($account, FILTER_UNSAFE_RAW);
        if(!$account) return false;

        $rights = array();
        if($account == 'guest')
        {
            $acl  = $this->dao->select('acl')->from(TABLE_GROUP)->where('name')->eq('guest')->fetch('acl');
            $acls = empty($acl) ? array() : json_decode($acl, true);

            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')
                ->on('t1.id = t2.`group`')->where('t1.name')->eq('guest');
        }
        else
        {
            $groups = $this->dao->select('t1.acl, t1.project')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id=t2.`group`')
                ->where('t2.account')->eq($account)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('t1.role')->ne('projectAdmin')
                ->andWhere('t1.role')->ne('limited')
                ->fetchAll();

            /* Init variables. */
            $acls = array();
            $programAllow = false;
            $projectAllow = false;
            $productAllow = false;
            $sprintAllow  = false;
            $viewAllow    = false;
            $actionAllow  = false;

            /* Authorize by group. */
            foreach($groups as $group)
            {
                if(empty($group->acl))
                {
                    $programAllow = true;
                    $projectAllow = true;
                    $productAllow = true;
                    $sprintAllow  = true;
                    $viewAllow    = true;
                    $actionAllow  = true;
                    break;
                }

                $acl = json_decode($group->acl, true);
                if(empty($acl['programs'])) $programAllow = true;
                if(empty($acl['projects'])) $projectAllow = true;
                if(empty($acl['products'])) $productAllow = true;
                if(empty($acl['sprints']))  $sprintAllow  = true;
                if(empty($acl['views']))    $viewAllow    = true;
                if(!isset($acl['actions'])) $actionAllow  = true;
                if(empty($acls) and !empty($acl))
                {
                    $acls = $acl;
                    continue;
                }

                /* Merge acls. */
                if(!empty($acl['programs'])) $acls['programs'] = !empty($acls['programs']) ? array_merge($acls['programs'], $acl['programs']) : $acl['programs'];
                if(!empty($acl['projects'])) $acls['projects'] = !empty($acls['projects']) ? array_merge($acls['projects'], $acl['projects']) : $acl['projects'];
                if(!empty($acl['products'])) $acls['products'] = !empty($acls['products']) ? array_merge($acls['products'], $acl['products']) : $acl['products'];
                if(!empty($acl['sprints']))  $acls['sprints']  = !empty($acls['sprints'])  ? array_merge($acls['sprints'],  $acl['sprints'])  : $acl['sprints'];
                if(!empty($acl['actions']))  $acls['actions']  = !empty($acls['actions'])  ? array_merge($acl['actions'], $acls['actions']) : $acl['actions'];
                if(!empty($acl['views']))    $acls['views']    = array_merge($acls['views'], $acl['views']);
            }

            if($programAllow) $acls['programs'] = array();
            if($projectAllow) $acls['projects'] = array();
            if($productAllow) $acls['products'] = array();
            if($sprintAllow)  $acls['sprints']  = array();
            if($viewAllow)    $acls['views']    = array();
            if($actionAllow)  unset($acls['actions']);

            $sql = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id = t2.`group`')
                ->leftJoin(TABLE_GROUPPRIV)->alias('t3')->on('t2.`group` = t3.`group`')
                ->where('t2.account')->eq($account)
                ->andWhere('t1.project')->eq(0)
                ->andWhere('t1.vision')->eq($this->config->vision);
        }

        $stmt = $sql->query();
        if(!$stmt) return array('rights' => $rights, 'acls' => $acls);
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($row['module'] and $row['method']) $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }

        /* Get can manage projects by user. */
        $canManageProjects   = '';
        $canManagePrograms   = '';
        $canManageProducts   = '';
        $canManageExecutions = '';
        if(!defined('IN_UPGRADE'))
        {
            $canManageObjects = $this->dao->select('programs,projects,products,executions')->from(TABLE_PROJECTADMIN)->where('account')->eq($account)->fetchAll();
            foreach($canManageObjects as $object)
            {
                $canManageProjects   .= $object->projects   . ',';
                $canManageProducts   .= $object->products   . ',';
                $canManagePrograms   .= $object->programs   . ',';
                $canManageExecutions .= $object->executions . ',';
            }
        }

        /* Set basic privs. */
        $rights['index']['index'] = 1;
        $rights['my']['index']    = 1;

        return array('rights' => $rights, 'acls' => $acls, 'projects' => $canManageProjects, 'programs' => $canManagePrograms, 'products' => $canManageProducts, 'executions' => $canManageExecutions);
    }

    /**
     * login function.
     *
     * @param  object $user
     * @param  bool   $addAction
     * @access public
     * @return false|object
     */
    public function login($user, $addAction = true)
    {
        if(!$user) return false;

        $this->cleanLocked($user->account);

        /* Authorize him and save to session. */
        $user->rights = $this->authorize($user->account);
        $user->groups = $this->getGroups($user->account);
        $user->view   = $this->grantUserView($user->account, $user->rights['acls'], $user->rights['projects']);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;

        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        if(isset($user->id) and $addAction) $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');

        /* Keep login. */
        if($this->post->keepLogin) $this->keepLogin($user);

        return $user;
    }

    /**
     * Keep the user in login state.
     *
     * @param  object    $user
     * @access public
     * @return void
     */
    public function keepLogin($user)
    {
        setcookie('keepLogin', 'on', $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('za', $user->account, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('zp', sha1($user->account . $user->password . $this->server->request_time), $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
    }

    /**
     * Judge a user is logon or not.
     *
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        $user = $this->session->user;
        return ($user and !empty($user->account) and $user->account != 'guest');
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
     * Get groups by visions.
     *
     * @param  array $visions
     * @access public
     * @return array
     */
    public function getGroupsByVisions($visions)
    {
        if(!is_array($visions)) return array();
        $groups = $this->dao->select('id, name, vision')->from(TABLE_GROUP)
            ->where('project')->eq(0)
            ->andWhere('vision')->in($visions)
            ->fetchAll('id');

        $visionList = $this->getVisionList();

        foreach($groups as $key => $group)
        {
            $groups[$key] = $group->name;
            if(count($visions) > 1) $groups[$key] = $visionList[$group->vision] . ' / ' . $group->name;
        }

        return $groups;
    }

    /**
     * Get the project or execution in which the user participates..
     *
     * @param  string $account
     * @param  string $type project|execution
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getObjects($account, $type = 'execution', $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $objectType    = $type == 'execution' ? 'sprint,stage,kanban' : $type;
        $myObjectsList = $this->dao->select('t1.*,t2.*')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.type')->eq($type)
            ->andWhere('t2.type')->in($objectType)
            ->beginIF(strpos('doing|wait|suspended|closed', $status) !== false)->andWhere('status')->eq($status)->fi()
            ->beginIF($status == 'done')->andWhere('status')->in('done,closed')->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notin('done,closed')->fi()
            ->beginIF($status == 'openedbyme')->andWhere('openedBy')->eq($account)->fi()
            ->beginIF($type == 'execution')->andWhere('t2.multiple')->eq('1')->fi()
            ->beginIF($type == 'execution' and !$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'project' and !$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->orderBy("t2.$orderBy")
            ->page($pager)
            ->fetchAll('root');

        $objectIdList  = array();
        $projectIdList = array();
        foreach($myObjectsList as $object)
        {
            $objectIdList[]  = $object->id;
            $projectIdList[] = $object->project;
        }

        /* Get all tasks and compute totalConsumed, totalLeft, totalWait, progress according to them. */
        $hours       = array();
        $emptyHour   = array('totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0, 'waitTasks' => 0, 'assignedToMeTasks' => 0, 'doneTasks' => 0, 'taskTotal' => 0);
        $searchField = $type == 'project' ? 'project' : 'execution';
        $tasks       = $this->dao->select('id, project, execution, consumed, `left`, status, assignedTo,finishedBy')
            ->from(TABLE_TASK)
            ->where('parent')->lt(1)
            ->andWhere($searchField)->in($objectIdList)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchGroup($searchField, 'id');

        /* Compute totalEstimate, totalConsumed, totalLeft. */
        foreach($tasks as $objectID => $objectTasks)
        {
            $hour = (object)$emptyHour;
            $hour->taskTotal = count($objectTasks);
            foreach($objectTasks as $task)
            {
                if($task->status == 'wait') $hour->waitTasks += 1;
                if($task->finishedBy != '') $hour->doneTasks += 1;
                if($task->status != 'cancel') $hour->totalConsumed += $task->consumed;
                if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += $task->left;
                if($task->assignedTo == $account) $hour->assignedToMeTasks += 1;
            }
            $hours[$objectID] = $hour;
        }

        /* Compute totalReal and progress. */
        foreach($hours as $hour)
        {
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 2) * 100 : 0;
        }

        $myObjects   = array();
        $projectList = $this->loadModel('project')->getByIdList($projectIdList);
        foreach($myObjectsList as $object)
        {
            /* Judge whether the project or execution is delayed. */
            if($object->status != 'done' and $object->status != 'closed' and $object->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $object->end);
                if($delay > 0) $object->delay = $delay;
            }

            /* Process the hours. */
            $object->progress          = isset($hours[$object->id]) ? $hours[$object->id]->progress : 0;
            $object->waitTasks         = isset($hours[$object->id]) ? $hours[$object->id]->waitTasks : 0;
            $object->doneTasks         = isset($hours[$object->id]) ? $hours[$object->id]->doneTasks : 0;
            $object->taskTotal         = isset($hours[$object->id]) ? $hours[$object->id]->taskTotal : 0;
            $object->totalConsumed     = isset($hours[$object->id]) ? $hours[$object->id]->totalConsumed : 0;
            $object->assignedToMeTasks = isset($hours[$object->id]) ? $hours[$object->id]->assignedToMeTasks : 0;

            if($object->project)
            {
                $parentProject = zget($projectList, $object->project, '');
                $object->projectName = $parentProject ? $parentProject->name : '';
            }
            $myObjects[$object->id] = $object;
        }

        return $myObjects;
    }

    /**
     * Plus the fail times.
     *
     * @param  string    $account
     * @access public
     * @return int
     */
    public function failPlus($account)
    {
        if(!validater::checkAccount($account)) return 0;

        /* Save session fails. */
        $sessionFails  = (int)$this->session->loginFails;
        $sessionFails += 1;
        $this->session->set('loginFails', $sessionFails);
        if($sessionFails >= $this->config->user->failTimes) $this->session->set("{$account}.loginLocked", date('Y-m-d H:i:s'));

        $user = $this->dao->select('fails')->from(TABLE_USER)->where('account')->eq($account)->fetch();
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
            $locked    = date('Y-m-d H:i:s');
            $failTimes = 0;
        }
        $this->dao->update(TABLE_USER)->set('fails')->eq($failTimes)->set('locked')->eq($locked)->where('account')->eq($account)->exec();

        return $fails;
    }

    /**
     * Check whether the user is locked.
     *
     * @param  string    $account
     * @access public
     * @return bool
     */
    public function checkLocked($account)
    {
        if($this->session->{"{$account}.loginLocked"} and (time() - strtotime($this->session->{"{$account}.loginLocked"})) <= $this->config->user->lockMinutes * 60) return true;

        $user = $this->dao->select('locked')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if(empty($user)) return false;

        if((time() - strtotime($user->locked)) > $this->config->user->lockMinutes * 60) return false;
        return true;
    }

    /**
     * Unlock the locked user.
     *
     * @param  string    $account
     * @access public
     * @return void
     */
    public function cleanLocked($account)
    {
        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq('0000-00-00 00:00:00')->where('account')->eq($account)->exec();

        unset($_SESSION['loginFails']);
        unset($_SESSION["{$account}.loginLocked"]);
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
     * Upload avatar.
     *
     * @access public
     * @return array
     */
    public function uploadAvatar()
    {
        $uploadResult = $this->loadModel('file')->saveUpload('avatar');
        if(!$uploadResult) return array('result' => 'fail', 'message' => $this->lang->fail);

        $fileIdList = array_keys($uploadResult);
        $file       = $this->file->getByID($fileIdList[0]);

        return array('result' => 'success', 'message' => '', 'locate' => helper::createLink('user', 'cropavatar', "image={$file->id}"));
    }

    /**
     * Get contact list of a user.
     *
     * @param string $account
     * @param string $params  withempty|withnote
     *
     * @access public
     * @return array
     */
    public function getContactLists($account, $params = '')
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
     * Get users who have access to the parent stage.
     *
     * @param  int    $stageID
     * @access public
     * @return object
     */
    public function getParentStageAuthedUsers($stageID = 0)
    {
        return $this->dao->select('account')->from(TABLE_USERVIEW)->where("CONCAT(',', sprints, ',')")->like("%,{$stageID},%")->fetchPairs();
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
     * @param  string|array    $accountList
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
     * @param  array     $userList
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
            return print(js::error(dao::getError()));
        }

        $this->dao->insert(TABLE_USERCONTACT)->data($data)
            ->check('listName', 'unique', "account = '{$data->account}'")
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * Update a contact list.
     *
     * @param  int    $listID
     * @param  string $listName
     * @param  array  $userList
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
            return false;
        }

        $this->dao->update(TABLE_USERCONTACT)->data($data)
            ->where('id')->eq($listID)
            ->exec();
        if(dao::isError()) return false;
    }

    /**
     * Update global contact.
     *
     * @param int  $listID
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

        $complexity  = array();
        $chars = str_split($password);
        foreach($chars as $letter)
        {
            $asc = ord($letter);
            if($asc >= 48 && $asc <= 57)
            {
                $complexity[0] = 1;
            }
            elseif($asc >= 65 && $asc <= 90)
            {
                $complexity[1] = 2;
            }
            elseif($asc >= 97 && $asc <= 122)
            {
                $complexity[2] = 4;
            }
            else
            {
                $complexity[3] = 8;
            }
        }
        $sumComplexity = array_sum($complexity);

        if(($sumComplexity == 7 or $sumComplexity == 15) and $length >= 6)  $strength = 1;
        if($sumComplexity == 15 and $length >= 10) $strength = 2;

        return $strength;
    }

    /**
     * Check Tmp dir.
     *
     * @access public
     * @return bool
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

            /* Init objects. */
            static $allProducts, $allPrograms, $allProjects, $allSprints, $teams, $stakeholders, $productWhiteList, $whiteList;
            if($allProducts === null) $allProducts = $this->dao->select('id,PO,QD,RD,acl,whitelist,program,createdBy')->from(TABLE_PRODUCT)->where('acl')->ne('open')->fetchAll('id');
            if($allProjects === null) $allProjects = $this->dao->select('id,PO,PM,QD,RD,acl,type,path,parent,openedBy')->from(TABLE_PROJECT)->where('acl')->ne('open')->andWhere('type')->eq('project')->fetchAll('id');
            if($allPrograms === null) $allPrograms = $this->dao->select('id,PO,PM,QD,RD,acl,type,path,parent,openedBy')->from(TABLE_PROGRAM)->where('acl')->ne('open')->andWhere('type')->eq('program')->fetchAll('id');
            if($allSprints  === null) $allSprints  = $this->dao->select('id,PO,PM,QD,RD,acl,project,path,parent,type,openedBy')->from(TABLE_PROJECT)->where('acl')->eq('private')->andWhere('type')->in('sprint,stage,kanban')->fetchAll('id');

            /* Get admins. */
            $manageObjects = array();
            $projectAdmins = $this->dao->select('`group`,programs,products,projects,executions')->from(TABLE_PROJECTADMIN)->where('account')->eq($account)->fetchAll('group');
            foreach($projectAdmins as $projectAdmin)
            {
                foreach($projectAdmin as $key => $value)
                {
                    $manageObjects[$key]['list'] = isset($manageObjects[$key]['list']) ? $manageObjects[$key]['list'] : '';

                    if($value == 'all')
                    {
                        $manageObjects[$key]['isAdmin'] = 1;
                    }
                    else
                    {
                        $manageObjects[$key]['list'] .= $value . ',';
                    }
                }
            }

            /* Get teams. */
            if($teams === null)
            {
                $teams = array();
                $stmt  = $this->dao->select('root,account')->from(TABLE_TEAM)->where('type')->in('project,execution')->query();
                while($team = $stmt->fetch()) $teams[$team->root][$team->account] = $team->account;
            }

            /* Get product white list. */
            if($productWhiteList === null)
            {
                $productWhiteList = array();
                $stmt = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->eq('product')->query();
                while($acl = $stmt->fetch()) $productWhiteList[$acl->objectID][$acl->account] = $acl->account;
            }

            /* Get white list. */
            if($whiteList === null)
            {
                $whiteList = array();
                $stmt      = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->in('program,project,sprint')->query();
                while($acl = $stmt->fetch()) $whiteList[$acl->objectID][$acl->account] = $acl->account;
            }

            /* Get stakeholders. */
            if($stakeholders === null)
            {
                $stakeholders = array();
                $stmt         = $this->dao->select('objectID,user')->from(TABLE_STAKEHOLDER)->where('deleted')->eq('0')->query();
                while($stakeholder = $stmt->fetch()) $stakeholders[$stakeholder->objectID][$stakeholder->user] = $stakeholder->user;
            }

            /* Compute parent stakeholders. */
            $this->loadModel('stakeholder');
            $programStakeholderGroup = $this->stakeholder->getParentStakeholderGroup(array_keys($allPrograms));
            $projectStakeholderGroup = $this->stakeholder->getParentStakeholderGroup(array_keys($allProjects));

            list($productTeams, $productStakeholders) = $this->getProductMembers($allProducts);

            /* Init user view. */
            $userView = new stdclass();
            $userView->account  = $account;
            $userView->programs = array();
            $userView->products = array();
            $userView->projects = array();
            $userView->sprints  = array();

            if($isAdmin)
            {
                $userView->programs = join(',', array_keys($allPrograms));
                $userView->products = join(',', array_keys($allProducts));
                $userView->projects = join(',', array_keys($allProjects));
                $userView->sprints  = join(',', array_keys($allSprints));
            }
            else
            {
                /* Process program userview. */
                if(!empty($manageObjects['programs']['isAdmin']))
                {
                    $userView->programs = join(',', array_keys($allPrograms));
                }
                else
                {
                    $programs       = array();
                    $managePrograms = isset($manageObjects['programs']['list']) ? $manageObjects['programs']['list'] : '';
                    foreach($allPrograms as $id => $program)
                    {
                        $programStakeholders = zget($stakeholders, $id, array());
                        if($program->acl == 'program') $programStakeholders += zget($programStakeholderGroup, $id, array());
                        if($this->checkProgramPriv($program, $account, $programStakeholders, zget($whiteList, $id, array()))) $programs[$id] = $id;
                        if(strpos(",$managePrograms,", ",$id,") !== false) $programs[$id] = $id;
                    }
                    $userView->programs = join(',', $programs);
                }

                /* Process product userview. */
                if(!empty($manageObjects['products']['isAdmin']))
                {
                    $userView->products = join(',', array_keys($allProducts));
                }
                else
                {
                    $products       = array();
                    $manageProducts = isset($manageObjects['products']['list']) ? $manageObjects['products']['list'] : '';
                    foreach($allProducts as $id => $product)
                    {
                        if($this->checkProductPriv($product, $account, $groups, zget($productTeams, $product->id, array()), zget($productStakeholders, $product->id, array()), zget($productWhiteList, $product->id, array()))) $products[$id] = $id;
                        if(strpos(",$manageProducts,", ",$id,") !== false) $products[$id] = $id;
                    }
                    $userView->products = join(',', $products);
                }

                /* Process project userview. */
                if(!empty($manageObjects['projects']['isAdmin']))
                {
                    $userView->projects = join(',', array_keys($allProjects));
                }
                else
                {
                    $projects       = array();
                    $manageProjects = isset($manageObjects['projects']['list']) ? $manageObjects['projects']['list'] : '';
                    foreach($allProjects as $id => $project)
                    {
                        $projectTeams        = zget($teams, $id, array());
                        $projectStakeholders = zget($stakeholders, $id, array());
                        if($project->acl == 'program') $projectStakeholders += zget($projectStakeholderGroup, $id, array());
                        if($this->checkProjectPriv($project, $account, $projectStakeholders, $projectTeams, zget($whiteList, $id, array()))) $projects[$id] = $id;
                        if(strpos(",$manageProjects,", ",$id,") !== false) $projects[$id] = $id;
                    }
                    $userView->projects = join(',', $projects);
                }

                /* Process sprint userview. */
                if(!empty($manageObjects['executions']['isAdmin']))
                {
                    $userView->sprints = join(',', array_keys($allSprints));
                }
                else
                {
                    $sprints          = array();
                    $manageExecutions = isset($manageObjects['executions']['list']) ? $manageObjects['executions']['list'] : '';
                    foreach($allSprints as $id => $sprint)
                    {
                        $sprintTeams        = zget($teams, $id, array());
                        $sprintStakeholders = zget($stakeholders, $sprint->project, array());
                        if($this->checkSprintPriv($sprint, $account, $sprintStakeholders, $sprintTeams, zget($whiteList, $id, array()))) $sprints[$id] = $id;
                        if(strpos(",$manageExecutions,", ",$id,") !== false) $sprints[$id] = $id;
                    }
                    $userView->sprints = join(',', $sprints);
                }
            }
            $this->dao->replace(TABLE_USERVIEW)->data($userView)->exec();
        }

        return $userView;
    }

    /**
     * Get product teams and stakeholders.
     *
     * @param  array $allProducts
     * @access public
     * @return array
     */
    public function getProductMembers($allProducts)
    {
        /* Get product and project relation. */
        $projectProducts = array();
        $productProjects = array();
        $stmt = $this->dao->select('t1.project, t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->in(array_keys($allProducts))
            ->andWhere('t2.deleted')->eq('0')
            ->query();
        while($projectProduct = $stmt->fetch())
        {
            $productProjects[$projectProduct->product][$projectProduct->project] = $projectProduct->project;
            $projectProducts[$projectProduct->project][$projectProduct->product] = $projectProduct->product;
        }

        /* Get linked projects teams. */
        $teamGroups = array();
        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('1=1')
            ->andWhere('type')->eq('project')
            ->andWhere('root')->in(array_keys($projectProducts))
            ->andWhere('root')->ne(0)
            ->query();

        while($team = $stmt->fetch())
        {
            $productIdList = zget($projectProducts, $team->root, array());
            foreach($productIdList as $productID) $teamGroups[$productID][$team->account] = $team->account;
        }

        /* Get linked projects stakeholders. */
        $stmt = $this->dao->select('objectID,user')->from(TABLE_STAKEHOLDER)
            ->where('objectType')->eq('project')
            ->andWhere('objectID')->in(array_keys($projectProducts))
            ->andWhere('deleted')->eq(0)
            ->query();

        $stakeholderGroups = array();
        while($stakeholder = $stmt->fetch())
        {
            $productIdList = zget($projectProducts, $stakeholder->objectID, array());
            foreach($productIdList as $productID) $stakeholderGroups[$productID][$stakeholder->user] = $stakeholder->user;
        }

        /* Get linked programs stakeholders. */
        $programProduct = array();
        foreach($allProducts as $product)
        {
            if($product->program) $programProduct[$product->program][$product->id] = $product->id;
        }

        if($programProduct)
        {
            $stmt = $this->dao->select('objectID,user')->from(TABLE_STAKEHOLDER)
                ->where('objectType')->eq('program')
                ->andWhere('objectID')->in(array_keys($programProduct))
                ->query();

            while($programStakeholder = $stmt->fetch())
            {
                $productIdList = zget($programProduct, $programStakeholder->objectID, array());
                foreach($productIdList as $productID) $stakeholderGroups[$productID][$programStakeholder->user] = $programStakeholder->user;
            }

            $sql = $this->dao->select('id,PM')->from(TABLE_PROGRAM)
                ->where('type')->eq('program')
                ->andWhere('id')->in(array_keys($programProduct))
                ->query();

            while($programOwner = $sql->fetch())
            {
                $productIdList = zget($programProduct, $programOwner->id, array());
                foreach($productIdList as $productID) $stakeholderGroups[$productID][$programOwner->PM] = $programOwner->PM;
            }
        }

        return array($teamGroups, $stakeholderGroups);
    }

    /**
     * Grant user view.
     *
     * @param  string  $account
     * @param  array   $acls
     * @param  string  $projects
     * @access public
     * @return object
     */
    public function grantUserView($account = '', $acls = array(), $projects = '')
    {
        if(empty($account)) $account = $this->session->user->account;
        if(empty($account)) return array();
        if(empty($acls) and !empty($this->session->user->rights['acls']))  $acls     = $this->session->user->rights['acls'];
        if(!$projects and isset($this->session->user->rights['projects'])) $projects = $this->session->user->rights['projects'];

        /* If userview is empty, init it. */
        $userView = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch();
        if(empty($userView)) $userView = $this->computeUserView($account);

        /* Get opened projects, programs, products and set it to userview. */
        $openedPrograms = $this->dao->select('id')->from(TABLE_PROJECT)->where('acl')->eq('open')->andWhere('type')->eq('program')->fetchAll('id');
        $openedProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('acl')->eq('open')->andWhere('type')->eq('project')->fetchAll('id');
        $openedProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('acl')->eq('open')->fetchAll('id');

        $openedPrograms = join(',', array_keys($openedPrograms));
        $openedProducts = join(',', array_keys($openedProducts));
        $openedProjects = join(',', array_keys($openedProjects));

        $userView->programs = rtrim($userView->programs, ',') . ',' . $openedPrograms;
        $userView->products = rtrim($userView->products, ',') . ',' . $openedProducts;
        $userView->projects = rtrim($userView->projects, ',') . ',' . $openedProjects;

        if(isset($_SESSION['user']->admin)) $isAdmin = $this->session->user->admin;
        if(!isset($isAdmin)) $isAdmin = strpos($this->app->company->admins, ",{$account},") !== false;

        /* 权限分组-视野维护的优先级最高，所以这里进行了替换操作。*/
        /* View management has the highest priority, so there is a substitution. */
        if(!empty($acls['programs']) and !$isAdmin)
        {
            $userView->programs = implode(',', $acls['programs']);
        }
        if(!empty($acls['projects']) and !$isAdmin)
        {
            /* If is project admin, set projectID to userview. */
            if($projects) $acls['projects'] = array_merge($acls['projects'], explode(',', $projects));
            $userView->projects = implode(',', $acls['projects']);
        }
        if(!empty($acls['products']) and !$isAdmin)
        {
            $userView->products = implode(',', $acls['products']);
        }

        /* Set opened sprints and stages into userview. */
        $openedSprints = $this->dao->select('id')->from(TABLE_PROJECT)
            ->where('acl')->eq('open')
            ->andWhere('type')->in('sprint,stage,kanban')
            ->andWhere('project')->in($userView->projects)
            ->fetchAll('id');

        $openedSprints     = join(',', array_keys($openedSprints));
        $userView->sprints = rtrim($userView->sprints, ',')  . ',' . $openedSprints;

        if(!empty($acls['sprints']) and !$isAdmin)
        {
            $userView->sprints = implode(',', $acls['sprints']);
        }

        $userView->products = trim($userView->products, ',');
        $userView->programs = trim($userView->programs, ',');
        $userView->projects = trim($userView->projects, ',');
        $userView->sprints  = trim($userView->sprints, ',');

        return $userView;
    }

    /**
     * Update user view by object type.
     *
     * @param  int|array  $objectIdList
     * @param  string $objectType
     * @param  array  $users
     * @access public
     * @return void
     */
    public function updateUserView($objectIdList, $objectType, $users = array())
    {
        if(is_numeric($objectIdList)) $objectIdList = array($objectIdList);
        if(!is_array($objectIdList)) return false;

        if($objectType == 'program') $this->updateProgramView($objectIdList, $users);
        if($objectType == 'product') $this->updateProductView($objectIdList, $users);
        if($objectType == 'project') $this->updateProjectView($objectIdList, $users);
        if($objectType == 'sprint')  $this->updateSprintView($objectIdList, $users);
    }

    /**
     * Update program user view.
     *
     * @param  array  $programIdList
     * @param  array  $user
     * @access public
     * @return void
     */
    public function updateProgramView($programIdList, $users)
    {
        $programs = $this->dao->select('id, PM, PO, QD, RD, openedBy, acl, parent, path')->from(TABLE_PROJECT)
            ->where('id')->in($programIdList)
            ->andWhere('acl')->ne('open')
            ->fetchAll('id');
        if(empty($programs)) return true;

        /* Get self stakeholders. */
        $stakeholderGroup = $this->loadModel('stakeholder')->getStakeholderGroup($programIdList);

        /* Get all parent program and subprogram relation. */
        $parentStakeholderGroup = $this->stakeholder->getParentStakeholderGroup($programIdList);

        /* Get all parent program and subprogram relation. */
        $parentPMGroup = $this->loadModel('program')->getParentPM($programIdList);

        /* Get programs's admins. */
        $programAdmins = $this->loadModel('group')->getAdmins($programIdList, 'programs');

        $whiteListGroup = array();
        $stmt = $this->dao->select('objectID,account')->from(TABLE_ACL)
            ->where('objectType')->eq('program')
            ->andWhere('objectID')->in($programIdList)
            ->query();

        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        /* Get auth users. */
        $authedUsers = array();
        if(!empty($users)) $authedUsers = $users;
        if(empty($users))
        {
            foreach($programs as $program)
            {
                $stakeholders = zget($stakeholderGroup, $program->id, array());
                $whiteList    = zget($whiteListGroup, $program->id, array());
                $admins       = zget($programAdmins, $program->id, array());
                if($program->acl == 'program')
                {
                    $parentIds = explode(',', $program->path);
                    foreach($parentIds as $parentId)
                    {
                        $stakeholders += zget($parentStakeholderGroup, $parentId, array());
                        $stakeholders += zget($parentPMGroup, $parentId, array());
                    }
                }
                $authedUsers += $this->getProgramAuthedUsers($program, $stakeholders, $whiteList, $admins);
            }
        }

        /* Get all programs user view. */
        $stmt  = $this->dao->select("account,programs")->from(TABLE_USERVIEW)->where('account')->in($authedUsers);
        if(empty($users) and $authedUsers)
        {
            foreach($programs as $programID => $program) $stmt->orWhere("CONCAT(',', programs, ',')")->like("%,{$programID},%");
        }
        $userViews = $stmt->fetchPairs('account', 'programs');

        /* Judge auth and update view. */
        foreach($userViews as $account => $view)
        {
            foreach($programs as $programID => $program)
            {
                $stakeholders = zget($stakeholderGroup, $program->id, array());
                $whiteList    = zget($whiteListGroup, $program->id, array());
                $admins       = zget($programAdmins, $program->id, array());
                if($program->acl == 'program')
                {
                    $stakeholders += zget($parentStakeholderGroup, $program->id, array());
                    $stakeholders += zget($parentPMGroup, $program->id, array());
                }

                $hasPriv = $this->checkProgramPriv($program, $account, $stakeholders, $whiteList, $admins);
                if($hasPriv and strpos(",{$view},", ",{$programID},") === false)  $view .= ",{$programID}";
                if(!$hasPriv and strpos(",{$view},", ",{$programID},") !== false) $view  = trim(str_replace(",{$programID},", ',', ",{$view},"), ',');
            }
            if($userViews[$account] != $view) $this->dao->update(TABLE_USERVIEW)->set('programs')->eq($view)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Update project view
     *
     * @param  array $projectIdList
     * @param  array $users
     * @access public
     * @return void
     */
    public function updateProjectView($projectIdList, $users)
    {
        $projects = $this->dao->select('id, PM, PO, QD, RD, openedBy, acl, parent, path, type')->from(TABLE_PROJECT)
            ->where('id')->in($projectIdList)
            ->andWhere('acl')->ne('open')
            ->fetchAll('id');
        if(empty($projects)) return true;

        /* Get team group. */
        $teamGroups = array();
        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('type')->eq('project')
            ->andWhere('root')->in($projectIdList)
            ->andWhere('root')->ne(0)
            ->query();

        while($team = $stmt->fetch()) $teamGroups[$team->root][$team->account] = $team->account;

        /* Get white list group. */
        $whiteListGroup = array();
        $stmt = $this->dao->select('objectID,account')->from(TABLE_ACL)
            ->where('objectType')->eq('project')
            ->andWhere('objectID')->in($projectIdList)
            ->query();

        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        /* Get self stakeholders. */
        $stakeholderGroup = $this->loadModel('stakeholder')->getStakeholderGroup($projectIdList);

        /* Get projects's admins. */
        $projectAdmins = $this->loadModel('group')->getAdmins($projectIdList, 'projects');

        /* Get all parent program and subprogram relation. */
        $parentStakeholderGroup = $this->stakeholder->getParentStakeholderGroup($projectIdList);

        /* Get auth users. */
        $authedUsers = array();
        if(!empty($users)) $authedUsers = $users;
        if(empty($users))
        {
            foreach($projects as $project)
            {
                $stakeholders = zget($stakeholderGroup, $project->id, array());
                $teams        = zget($teamGroups, $project->id, array());
                $whiteList    = zget($whiteListGroup, $project->id, array());
                $admins       = zget($projectAdmins, $project->id, array());
                if($project->acl == 'program') $stakeholders += zget($parentStakeholderGroup, $project->id, array());

                $authedUsers += $this->getProjectAuthedUsers($project, $stakeholders, $teams, $whiteList, $admins);
            }
        }

        /* Get all projects user view. */
        $stmt  = $this->dao->select("account,projects")->from(TABLE_USERVIEW)->where('account')->in($authedUsers);
        if(empty($users) and $authedUsers)
        {
            foreach($projects as $projectID => $project) $stmt->orWhere("CONCAT(',', projects, ',')")->like("%,{$projectID},%");
        }
        $userViews = $stmt->fetchPairs('account', 'projects');

        /* Judge auth and update view. */
        foreach($userViews as $account => $view)
        {
            foreach($projects as $projectID => $project)
            {
                $stakeholders = zget($stakeholderGroup, $project->id, array());
                $teams        = zget($teamGroups, $project->id, array());
                $whiteList    = zget($whiteListGroup, $project->id, array());
                $admins       = zget($projectAdmins, $project->id, array());
                if($project->acl == 'program') $stakeholders += zget($parentStakeholderGroup, $project->id, array());

                $hasPriv = $this->checkProjectPriv($project, $account, $stakeholders, $teams, $whiteList, $admins);
                if($hasPriv and strpos(",{$view},", ",{$projectID},") === false)  $view .= ",{$projectID}";
                if(!$hasPriv and strpos(",{$view},", ",{$projectID},") !== false) $view  = trim(str_replace(",{$projectID},", ',', ",{$view},"), ',');
            }
            if($userViews[$account] != $view) $this->dao->update(TABLE_USERVIEW)->set('projects')->eq($view)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Update product user view.
     *
     * @param  array  $productIdList
     * @param  array  $user
     * @access public
     * @return void
     */
    public function updateProductView($productIdList, $users)
    {
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->andWhere('acl')->ne('open')->fetchAll('id');
        if(empty($products)) return true;

        /* Get all groups for whiteList. */
        $allGroups  = $this->dao->select('account, `group`')->from(TABLE_USERGROUP)->fetchAll();
        $userGroups = array();
        foreach($allGroups as $group)
        {
            if(!isset($userGroups[$group->account])) $userGroups[$group->account] = '';
            $userGroups[$group->account] .= "{$group->group},";
        }

        list($productTeams, $productStakeholders) = $this->getProductMembers($products);

        /* Get white list group. */
        $whiteListGroup = array();
        $stmt = $this->dao->select('objectID,account')->from(TABLE_ACL)
            ->where('objectType')->eq('product')
            ->andWhere('objectID')->in($productIdList)
            ->query();

        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        /* Get products' admins. */
        $productAdmins = $this->loadModel('group')->getAdmins($productIdList, 'products');

        /* Get product view list. */
        $viewList = array();
        if(empty($users))
        {
            foreach($products as $productID => $product)
            {
                $teams        = zget($productTeams, $productID, array());
                $stakeholders = zget($productStakeholders, $productID, array());
                $whiteList    = zget($whiteListGroup, $productID, array());
                $admins       = zget($productAdmins, $productID, array());
                $viewList    += $this->getProductViewListUsers($product, $teams, $stakeholders, $whiteList, $admins);
            }

            $users = $viewList;
        }

        $stmt = $this->dao->select("account,products")->from(TABLE_USERVIEW)->where('account')->in($users);
        foreach($products as $productID => $product) $stmt->orWhere("CONCAT(',', products, ',')")->like("%,{$productID},%");
        $userViews = $stmt->fetchPairs('account', 'products');

        /* Process user view. */
        foreach($userViews as $account => $view)
        {
            foreach($products as $productID => $product)
            {
                $members      = zget($productTeams, $productID, array());
                $stakeholders = zget($productStakeholders, $productID, array());
                $whiteList    = zget($whiteListGroup, $productID, array());
                $admins       = zget($productAdmins, $productID, array());

                $hasPriv = $this->checkProductPriv($product, $account, zget($userGroups, $account, ''), $members, $stakeholders, $whiteList, $admins);
                if($hasPriv and strpos(",{$view},", ",{$productID},") === false)  $view .= ",{$productID}";
                if(!$hasPriv and strpos(",{$view},", ",{$productID},") !== false) $view  = trim(str_replace(",{$productID},", ',', ",{$view},"), ',');
            }
            if($userViews[$account] != $view) $this->dao->update(TABLE_USERVIEW)->set('products')->eq($view)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Update sprint view.
     *
     * @param  array $sprintIdList
     * @param  array $users
     * @access public
     * @return void
     */
    public function updateSprintView($sprintIdList, $users)
    {
        $sprints = $this->dao->select('id, project, PM, PO, QD, RD, openedBy, acl, parent, path, grade, type')->from(TABLE_PROJECT)
            ->where('id')->in($sprintIdList)
            ->andWhere('acl')->ne('open')
            ->fetchAll('id');
        if(empty($sprints)) return true;

        $parentIdList = array();
        foreach($sprints as $sprint) $parentIdList[$sprint->project] = $sprint->project;

        /* Get team group. */
        $teamGroups = array();
        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)
            ->where('type')->in('project,execution')
            ->andWhere('root')->in(array_merge($sprintIdList, $parentIdList))
            ->andWhere('root')->ne(0)
            ->query();

        while($team = $stmt->fetch()) $teamGroups[$team->root][$team->account] = $team->account;

        /* Get white list group. */
        $whiteListGroup = array();
        $stmt = $this->dao->select('objectID,account')->from(TABLE_ACL)
            ->where('objectType')->eq('sprint')
            ->andWhere('objectID')->in($sprintIdList)
            ->query();

        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        $projectIdList = array();
        foreach($sprints as $sprintID => $sprint) $projectIdList[$sprint->project] = $sprint->project;

        /* Get parent project stakeholders. */
        $stakeholderGroup = $this->loadModel('stakeholder')->getStakeholderGroup($projectIdList);

        /* Get executions' admins. */
        $executionAdmins = $this->loadModel('group')->getAdmins($sprintIdList, 'executions');

        /* Get auth users. */
        $authedUsers = array();
        if(!empty($users)) $authedUsers = $users;
        if(empty($users))
        {
            foreach($sprints as $sprint)
            {
                $stakeholders = zget($stakeholderGroup, $sprint->project, array());
                $teams        = zget($teamGroups, $sprint->id, array());
                $parentTeams  = zget($teamGroups, $sprint->project, array());
                $whiteList    = zget($whiteListGroup, $sprint->project, array());
                $admins       = zget($executionAdmins, $sprint->id, array());

                $authedUsers += $this->getSprintAuthedUsers($sprint, $stakeholders, array_merge($teams, $parentTeams), $whiteList, $admins);

                /* If you have parent stage view permissions, you have child stage permissions. */
                if($sprint->type == 'stage' && $sprint->grade == 2)
                {
                    $parentStageAuthedUsers = $this->getParentStageAuthedUsers($sprint->parent);
                    $authedUsers = array_merge($authedUsers, $parentStageAuthedUsers);
                }
            }
        }

        /* Get all sprints user view. */
        $stmt  = $this->dao->select("account,sprints")->from(TABLE_USERVIEW)->where('account')->in($authedUsers);
        if(empty($users) and $authedUsers)
        {
            foreach($sprints as $sprintID => $sprint) $stmt->orWhere("CONCAT(',', sprints, ',')")->like("%,{$sprintID},%");
        }
        $userViews = $stmt->fetchPairs('account', 'sprints');

        /* Judge auth and update view. */
        foreach($userViews as $account => $view)
        {
            foreach($sprints as $sprintID => $sprint)
            {
                $stakeholders = zget($stakeholderGroup, $sprint->project, array());
                $teams        = zget($teamGroups, $sprint->id, array());
                $whiteList    = zget($whiteListGroup, $sprint->id, array());
                $admins       = zget($executionAdmins, $sprint->id, array());

                $hasPriv = $this->checkSprintPriv($sprint, $account, $stakeholders, $teams, $whiteList, $admins);
                if($hasPriv and strpos(",{$view},", ",{$sprintID},") === false)  $view .= ",{$sprintID}";
                if(!$hasPriv and strpos(",{$view},", ",{$sprintID},") !== false) $view  = trim(str_replace(",{$sprintID},", ',', ",{$view},"), ',');
            }
            if($userViews[$account] != $view) $this->dao->update(TABLE_USERVIEW)->set('sprints')->eq($view)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Check program priv
     *
     * @param  object $program
     * @param  string $account
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProgramPriv($program, $account, $stakeholders, $whiteList, $admins = array())
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;

        if($program->PM == $account || $program->openedBy == $account) return true;

        /* Parent program managers. */
        if($program->parent != 0 && $program->acl == 'program')
        {
            $path    = str_replace(",{$program->id},", ',', "{$program->path}");
            $parents = $this->dao->select('openedBy,PM')->from(TABLE_PROGRAM)->where('id')->in($path)->fetchAll();
            foreach($parents as $parent) if($parent->PM == $account) return true;
        }

        if($program->acl == 'open') return true;

        if(isset($stakeholders[$account])) return true;
        if(isset($whiteList[$account]))    return true;
        if(isset($admins[$account]))       return true;

        return false;
    }

    /**
     * Check project priv.
     *
     * @param  object    $project
     * @param  string    $account
     * @param  string    $groups
     * @param  array     $teams
     * @param  array     $whiteList
     * @param  array     $admins
     * @access public
     * @return bool
     */
    public function checkProjectPriv($project, $account, $stakeholders, $teams, $whiteList, $admins = array())
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;
        if($project->PO == $account OR $project->QD == $account OR $project->RD == $account OR $project->PM == $account) return true;

        if($project->acl == 'open')        return true;
        if(isset($teams[$account]))        return true;
        if(isset($stakeholders[$account])) return true;
        if(isset($whiteList[$account]))    return true;
        if(isset($admins[$account]))       return true;

        /* Parent program managers. */
        if($project->type == 'project' && $project->parent != 0 && $project->acl == 'program')
        {
            $path     = str_replace(",{$project->id},", ',', "{$project->path}");
            $programs = $this->dao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->in($path)->fetchAll();
            foreach($programs as $program) if($program->PM == $account) return true;
        }

        /* Judge sprint auth. */
        if(($project->type == 'sprint' or $project->type == 'stage' or $project->type == 'kanban') and $project->acl == 'private')
        {
            $parent = $this->dao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->eq($project->project)->fetch();
            if(empty($parent)) return false;
            if($parent->PM == $account or $parent->openedBy == $account) return true;
        }

        return false;
    }

    /**
     * Check sprint priv.
     *
     * @param  object    $project
     * @param  string    $account
     * @param  string    $groups
     * @param  array     $teams
     * @param  array     $whiteList
     * @param  array     $admins
     * @access public
     * @return bool
     */
    public function checkSprintPriv($sprint, $account, $stakeholders, $teams, $whiteList, $admins = array())
    {
        return $this->checkProjectPriv($sprint, $account, $stakeholders, $teams, $whiteList, $admins);
    }

    /**
     * Check product priv.
     *
     * @param  object $product
     * @param  string $account
     * @param  string $groups
     * @param  array  $linkedProjects
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProductPriv($product, $account, $groups, $teams, $stakeholders, $whiteList, $admins = array())
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;
        if(strpos(",{$product->reviewer},", ',' . $account . ',') !== false)    return true;
        if(strpos(",{$product->PMT},", ',' . $account . ',') !== false)         return true;
        if($product->PO == $account OR $product->QD == $account OR $product->RD == $account OR $product->createdBy == $account OR (isset($product->feedback) && $product->feedback == $account)) return true;
        if($product->acl == 'open') return true;

        if(isset($teams[$account]))        return true;
        if(isset($stakeholders[$account])) return true;
        if(isset($whiteList[$account]))    return true;
        if(isset($admins[$account]))       return true;

        return false;
    }

    /**
     * Get project authed users.
     *
     * @param  object $project
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProjectAuthedUsers($project, $stakeholders, $teams, $whiteList, $admins = array())
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin] = $admin;

        $users[$project->openedBy] = $project->openedBy;
        $users[$project->PM]       = $project->PM;
        $users[$project->PO]       = $project->PO;
        $users[$project->QD]       = $project->QD;
        $users[$project->RD]       = $project->RD;

        $users += $stakeholders ? $stakeholders : array();
        $users += $teams ? $teams : array();
        $users += $whiteList ? $whiteList : array();
        $users += $admins ? $admins : array();

        /* Parent program managers. */
        if($project->type == 'project' && $project->parent != 0 && $project->acl == 'program')
        {
            $path     = str_replace(",{$project->id},", ',', "{$project->path}");
            $programs = $this->dao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->in($path)->fetchAll();
            foreach($programs as $program)
            {
                $users[$program->openedBy] = $program->openedBy;
                $users[$program->PM]       = $program->PM;
            }
        }

        /* Judge sprint auth. */
        if(($project->type == 'sprint' || $project->type == 'stage') && $project->acl == 'private')
        {
            $parent = $this->dao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->eq($project->project)->fetch();
            $users[$parent->openedBy] = $parent->openedBy;
            $users[$parent->PM]       = $parent->PM;
        }

        return $users;
    }

    /**
     * Get program authed users.
     *
     * @param  object $program
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProgramAuthedUsers($program, $stakeholders, $whiteList, $admins)
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin] = $admin;

        $users[$program->openedBy] = $program->openedBy;
        $users[$program->PM]       = $program->PM;

        $users += $stakeholders ? $stakeholders : array();
        $users += $whiteList ? $whiteList : array();
        $users += $admins ? $admins : array();

        return $users;
    }

    /**
     * Get sprint authed users.
     *
     * @param  object $sprint
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getSprintAuthedUsers($sprint, $stakeholders, $teams, $whiteList, $admins)
    {
        return $this->getProjectAuthedUsers($sprint, $stakeholders, $teams, $whiteList, $admins);
    }

    /**
     * Get product view list users.
     *
     * @param  object $product
     * @param  array  $linkedProjects
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProductViewListUsers($product, $teams, $stakeholders, $whiteList, $admins)
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin]   = $admin;
        foreach(explode(',', trim($product->reviewer, ',')) as $account)        $users[$account] = $account;
        if(isset($product->PMT))
        {
            foreach(explode(',', trim($product->PMT, ',')) as $account) $users[$account] = $account;
        }

        $users[$product->PO]        = $product->PO;
        $users[$product->QD]        = $product->QD;
        $users[$product->RD]        = $product->RD;
        $users[$product->createdBy] = $product->createdBy;
        if(isset($product->feedback)) $users[$product->feedback] = $product->feedback;

        if($teams === '' and $stakeholders === '')
        {
            list($productTeams, $productStakeholders) = $this->getProductMembers(array($product->id => $product));
            $teams        = isset($productTeams[$product->id])        ? $productTeams[$product->id]        : array();
            $stakeholders = isset($productStakeholders[$product->id]) ? $productStakeholders[$product->id] : array();
        }

        if($whiteList === '')
        {
            $whiteList = $this->dao->select('account')->from(TABLE_ACL)
                ->where('objectType')->eq('product')
                ->andWhere('objectID')->eq($product->id)
                ->fetchPairs();
        }

        if($admins === '')
        {
            $admins = $this->dao->select('account')->from(TABLE_PROJECTADMIN)
                ->where("CONCAT(',', products, ',')")->like("%,$product->id,%")
                ->orWhere('products')->eq('all')
                ->fetchPairs();
        }

        $users += $teams ? $teams : array();
        $users += $stakeholders ? $stakeholders : array();
        $users += $whiteList ? $whiteList : array();
        $users += $admins ? $admins : array();

        return $users;
    }

    /**
     * Get team members in object.
     *
     * @param  int    $objectID
     * @param  string $type     project|execution
     * @param  string $params
     * @param  string $usersToAppended
     * @access public
     * @return array
     */
    public function getTeamMemberPairs($objectID, $type = 'project', $params = '', $usersToAppended = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembersPairs();

        $keyField = strpos($params, 'useid') !== false ? 'id' : 'account';
        $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$objectID)
            ->andWhere('t1.type')->eq($type)
            ->beginIF($params == 'nodeleted' or empty($this->config->user->showDeleted))
            ->andWhere('t2.deleted')->eq(0)
            ->fi()
            ->fetchAll($keyField);

        if($usersToAppended) $users += $this->dao->select("id, account, realname")->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll($keyField);

        if(!$users) return array('' => '');

        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $users[$account] =  $firstLetter . ($user->realname ? $user->realname : $user->account);
        }

        /* Put the current user first. */
        $users = $this->processAccountSort($users);

        return array('' => '') + $users;
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
        if($action == 'unlock' and (strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked ? $user->locked : '0000-00-00 00:00:00')) >= $config->user->lockMinutes * 60) return false;

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
        $count   = 'count(id) AS count';
        $t1Count = 'count(t1.id) AS count';

        $personalData = array();
        $personalData['createdTodos']        = $this->dao->select($count)->from(TABLE_TODO)->where('account')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        $personalData['createdRequirements'] = $this->dao->select($t1Count)->from(TABLE_STORY)->alias('t1')->leftjoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->andWhere('t1.type')->eq('requirement')->fetch('count');
        $personalData['createdStories']      = $this->dao->select($t1Count)->from(TABLE_STORY)->alias('t1')->leftjoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->andWhere('t1.type')->eq('story')->fetch('count');
        $personalData['createdBugs']         = $this->dao->select($t1Count)->from(TABLE_BUG)->alias('t1')->leftjoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        $personalData['resolvedBugs']        = $this->dao->select($t1Count)->from(TABLE_BUG)->alias('t1')->leftjoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        $personalData['createdCases']        = $this->dao->select($count)->from(TABLE_CASE)->where('openedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');
        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            $personalData['createdRisks']   = $this->dao->select($t1Count)->from(TABLE_RISK)->alias('t1')->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.createdBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['resolvedRisks']  = $this->dao->select($t1Count)->from(TABLE_RISK)->alias('t1')->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['createdIssues']  = $this->dao->select($t1Count)->from(TABLE_ISSUE)->alias('t1')->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.createdBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['resolvedIssues'] = $this->dao->select($t1Count)->from(TABLE_ISSUE)->alias('t1')->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        }
        $personalData['createdDocs']   = $this->dao->select($count)->from(TABLE_DOC)->where('addedBy')->eq($account)->andWhere('lib')->ne('')->andWhere('deleted')->eq('0')->andWhere('vision')->eq($this->config->vision)->fetch('count');
        $personalData['finishedTasks'] = $this->dao->select($t1Count)->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftjoin(TABLE_TASKTEAM)->alias('t3')->on("t1.id = t3.task and t3.account = '{$account}'")
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.finishedBy', true)->eq($account)
            ->orWhere('t3.status')->eq("done")
            ->markRight(1)
            ->fetch('count');

        return $personalData;
    }

    /**
     * Get users details for API.
     *
     * @param  array  $userList
     * @access public
     * @return array
     */
    public function getUserDetailsForAPI($userList)
    {
        $users = $this->dao->select($this->config->user->detailFields)->from(TABLE_USER)->where("account")->in($userList)->fetchAll();

        $userDetails = array();
        foreach($users as $index => $user)
        {
            $user->url = helper::createLink('user', 'profile', "userID={$user->id}");

            if($user->avatar != "")
            {
                $user->avatar = common::getSysURL() . $user->avatar;
            }
            else
            {
                $user->avatar = "https://www.gravatar.com/avatar/" . md5($user->account) . "?d=identicon&s=80";
            }

            $userDetails[$user->account] = $user;
        }
        return $userDetails;
    }

    /**
     * Get vision list.
     *
     * @access public
     * @return array
     */
    public function getVisionList()
    {
        $visionList = $this->lang->visionList;

        foreach($visionList as $visionKey => $visionName)
        {
            if(strpos($this->config->visions, ",{$visionKey},") === false) unset($visionList[$visionKey]);
        }

        return $visionList;
    }

    /**
     * Get users who have authority to create stories.
     *
     * @access public
     * @return array
     */
    public function getCanCreateStoryUsers()
    {
        $users     = $this->getPairs('noclosed|nodeleted');
        $groupList = $this->dao->select('*')->from(TABLE_USERGROUP)
            ->where('account')->in(array_keys($users))
            ->fetchGroup('account', 'group');

        $hasPrivGroups = $this->dao->select('*')->from(TABLE_GROUPPRIV)
            ->where('module')->eq('story')
            ->andWhere('(method')->eq('create')
            ->orWhere('method')->eq('batchCreate')
            ->markRight(1)
            ->fetchAll('group');

        foreach($users as $account => $user)
        {
            if(empty($user) or strpos($this->app->company->admins, ",{$account},") !== false) continue;

            if(!isset($groupList[$account]))
            {
                unset($users[$account]);
                continue;
            }

            $groups  = $groupList[$account];
            $hasPriv = false;
            foreach($groups as $groupID => $group)
            {
                if(isset($hasPrivGroups[$groupID]))
                {
                    $hasPriv = true;
                    break;
                }
            }

            if(!$hasPriv) unset($users[$account]);
        }

        return $users;
    }

    /**
     * Switch admin of ZenTao.
     *
     * @access public
     * @return void
     */
    public function su()
    {
        $company = $this->dao->select('admins')->from(TABLE_COMPANY)->fetch();
        $admins  = explode(',', trim($company->admins, ','));
        $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($admins[0])->fetch();
    }

    /**
     * Put the current user first.
     *
     * @param  array    $users
     * @access public
     * @return array
     */
    public function processAccountSort($users = array())
    {
        if(isset($this->app->user->account) and isset($users[$this->app->user->account]))
        {
            $currentUser = array();
            $currentUser[$this->app->user->account] = $users[$this->app->user->account];
            unset($users[$this->app->user->account]);
            $users = $currentUser + $users;
        }
        return $users;
    }
}
