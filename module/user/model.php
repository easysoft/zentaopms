<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: model.php 5005 2013-07-03 08:39:11Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class userModel extends model
{
    /**
     * 创建或编辑用户前的检查。
     * Check before create or edit a user.
     *
     * @param  object $user
     * @param  bool   $canNoPassword
     * @access public
     * @return bool
     */
    public function checkBeforeCreateOrEdit(object $user, bool $canNoPassword = false): bool
    {
        if(strtolower($user->account) == 'guest') dao::$errors['account'][] = sprintf($this->lang->user->error->reserved, $user->account);

        $this->checkPassword($user, $canNoPassword);
        $this->checkVerifyPassword($user->verifyPassword);

        return !dao::isError();
    }

    /**
     * 批量创建用户前的检查。
     * Check before batch creating users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return bool
     */
    public function checkBeforeBatchCreate(array $users, string $verifyPassword): bool
    {
        if(!$users) return true;

        $accounts = array_map(function($user){return $user->account;}, $users);
        $accounts = $this->dao->select('account')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs();

        foreach($users as $key => $user)
        {
            if(empty($user->account)) continue;

            if(strtolower($user->account) == 'guest') dao::$errors["account[{$key}]"][] = $this->lang->user->error->reserved;
            if(isset($accounts[$user->account])) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->unique, $this->lang->user->account, $user->account);
            if(!validater::checkAccount($user->account)) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->account, $this->lang->user->account);
            if(empty($user->realname)) dao::$errors["realname[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->realname);
            if(empty($user->visions)) dao::$errors["visions[{$key}][]"][] = sprintf($this->lang->error->notempty, $this->lang->user->visions);
            if(empty($user->password)) dao::$errors["password[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
            if(!empty($user->password) && !validater::checkReg($user->password, '|(.){6,}|')) dao::$errors["password[{$key}]"][] = $this->lang->user->error->password;
            if(!empty($user->email) && !validater::checkEmail($user->email)) dao::$errors["email[{$key}]"][] = sprintf($this->lang->error->email, $this->lang->user->email);
            if(!empty($user->phone) && !validater::checkPhone($user->phone)) dao::$errors["phone[{$key}]"][] = sprintf($this->lang->error->phone, $this->lang->user->phone);
            if(!empty($user->mobile) && !validater::checkMobile($user->mobile)) dao::$errors["mobile[{$key}]"][] = sprintf($this->lang->error->mobile, $this->lang->user->mobile);
            if(empty($user->gender)) dao::$errors["gender[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->gender);

            /* 检查密码强度是否符合安全设置。*/
            /* Check if the password strength meets the security settings. */
            if(isset($this->config->safe->mode) && $this->computePasswordStrength($user->password) < $this->config->safe->mode) dao::$errors["password[{$key}]"][] = $this->lang->user->error->weakPassword;

            /* 检查明文的弱密码。*/
            /* Check the weak password in clear text. */
            if(!empty($this->config->safe->changeWeak))
            {
                if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
                if(strpos(",{$this->config->safe->weak},", ",{$user->password},") !== false) dao::$errors["password[{$key}]"][] = sprintf($this->lang->user->error->dangerPassword, $this->config->safe->weak);
            }

            $accounts[$user->account] = $user->account;
        }

        $this->checkVerifyPassword($verifyPassword);

        return !dao::isError();
    }

    /**
     * 批量编辑用户前的检查。
     * Check before batch editing users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return bool
     */
    public function checkBeforeBatchUpdate(array $users, string $verifyPassword): bool
    {
        if(!$users) return true;

        foreach($users as $key => $user)
        {
            if(empty($user->realname)) dao::$errors["realname[{$key}]"][] = sprintf($this->lang->error->notempty, $this->lang->user->realname);
            if(empty($user->visions)) dao::$errors["visions[{$key}][]"][] = sprintf($this->lang->error->notempty, $this->lang->user->visions);
            if(!empty($user->email) && !validater::checkEmail($user->email)) dao::$errors["email[{$key}]"][] = sprintf($this->lang->error->email, $this->lang->user->email);
            if(!empty($user->phone) && !validater::checkPhone($user->phone)) dao::$errors["phone[{$key}]"][] = sprintf($this->lang->error->phone, $this->lang->user->phone);
            if(!empty($user->mobile) && !validater::checkMobile($user->mobile)) dao::$errors["mobile[{$key}]"][] = sprintf($this->lang->error->mobile, $this->lang->user->mobile);
        }

        $this->checkVerifyPassword($verifyPassword);

        return !dao::isError();
    }

    /**
     * 检查密码强度。
     * Check the posted password.
     *
     * @param  object $user
     * @param  bool   $canNoPassword
     * @access public
     * @return bool
     */
    public function checkPassword(object $user, bool $canNoPassword = false): bool
    {
        if(empty($user->password1))
        {
            if(!$canNoPassword) dao::$errors['password1'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
            return !dao::isError();
        }

        /* 检查密码强度是否符合安全设置。*/
        /* Check if the password strength meets the security settings. */
        if(isset($this->config->safe->mode) && ($user->passwordStrength < $this->config->safe->mode))
        {
            dao::$errors['password1'][] = zget($this->lang->user->placeholder->passwordStrengthCheck, $this->config->safe->mode, $this->lang->user->weakPassword);
        }
        else if($user->passwordLength < 6)
        {
            dao::$errors['password1'][] = zget($this->lang->user->placeholder->passwordStrengthCheck, 0, $this->lang->user->weakPassword);
        }

        if($user->password1 != $user->password2) dao::$errors['password1'][] = $this->lang->error->passwordsame;

        if(!empty($this->config->safe->changeWeak))
        {
            if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');

            /* 检查明文的弱密码。*/
            /* Check the weak password in clear text. */
            if(strpos(",{$this->config->safe->weak},", ",{$user->password1},") !== false) dao::$errors['password1'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);

            /* 检查加密后的弱密码。*/
            /* Check for encrypted weak password. */
            $weaks = array();
            foreach(explode(',', $this->config->safe->weak) as $weak) $weaks[] = md5(trim($weak));
            if(in_array(substr($user->password1, 0, 32), $weaks)) dao::$errors['password1'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
        }

        return !dao::isError();
    }

    /**
     * 检查当前用户密码是否正确。
     * Check if the current user password is correct.
     *
     * @param  string $verifyPassword
     * @access public
     * @return bool
     */
    public function checkVerifyPassword(string $verifyPassword): bool
    {
        if(empty($verifyPassword) || $verifyPassword != md5($this->app->user->password . $this->session->rand)) dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;

        return !dao::isError();
    }

    /**
     * 获取用户列表。
     * Get user list.
     *
     * @param  string $params
     * @param  string $fields
     * @access public
     * @return array
     */
    public function getList(string $params = 'nodeleted', string $fields = '*'): array
    {
        return $this->dao->select($fields)->from(TABLE_USER)
            ->where('1 = 1')
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq('inside')->fi()
            ->beginIF(strpos($params, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
            ->orderBy('account')
            ->fetchAll('', false);
    }

    /**
     * 根据用户 id 列表获取用户。
     * Get user list by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getListByIdList(array $idList): array
    {
        if(!$idList) return array();

        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($idList)
            ->orderBy('id')
            ->fetchAll('id');
    }

    /**
     * 根据用户名列表获取用户信息。
     * Get user info by account list.
     *
     * @param  array  $accounts
     * @param  string $keyField
     * @access public
     * @return array
     */
    public function getListByAccounts(array $accounts, string $keyField = 'id'): array
    {
        if(empty($accounts)) return array();

        return $this->dao->select('id,account,realname,avatar,role')->from(TABLE_USER)
            ->where('account')->in($accounts)
            ->fetchAll($keyField);
    }

    /**
     * 获取用户名和真实姓名的键值对。
     * Get account and realname pairs.
     *
     * @param  string       $params           noletter|noempty|nodeleted|noclosed|withguest|pofirst|devfirst|qafirst|pmfirst|realname|outside|inside|all, can be sets of theme
     * @param  string|array $usersToAppended  account1,account2
     * @param  int          $maxCount
     * @param  string|array $accounts
     * @access public
     * @return array
     */
    public function getPairs(string $params = '', string|array $usersToAppended = '', int $maxCount = 0, string|array $accounts = '')
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getUserPairs();

        /* 设置查询字段、条件字段、排序字段和索引字段。
         * Set query fields, condition fields, order fields and index field.
         *
         * 如果参数中有 xxfirst，使用 INSTR 函数获取角色字段在排序字符串中的位置，以确保用户在角色排序中排在前面。
         * If there's xxfirst in the params, use INSTR function to get the position of role fields in a order string,
         * thus to make sure users of this role at first.
         */
        $fields = 'id, account, realname, deleted';
        if(strpos($params, 'pofirst') !== false) $fields .= ", INSTR(',pd,po,', role) AS roleOrder";
        if(strpos($params, 'pdfirst') !== false) $fields .= ", INSTR(',po,pd,', role) AS roleOrder";
        if(strpos($params, 'qafirst') !== false) $fields .= ", INSTR(',qd,qa,', role) AS roleOrder";
        if(strpos($params, 'qdfirst') !== false) $fields .= ", INSTR(',qa,qd,', role) AS roleOrder";
        if(strpos($params, 'pmfirst') !== false) $fields .= ", INSTR(',td,pm,', role) AS roleOrder";
        if(strpos($params, 'devfirst')!== false) $fields .= ", INSTR(',td,pm,qd,qa,dev,', role) AS roleOrder";
        $type     = (strpos($params, 'outside') !== false) ? 'outside' : 'inside';
        $orderBy  = (strpos($params, 'first')   !== false) ? 'roleOrder DESC, account' : 'account';
        $keyField = (strpos($params, 'useid')   !== false) ? 'id' : "account";

        $users = $this->dao->select($fields)->from(TABLE_USER)
            ->where('1=1')
            ->beginIF(strpos($params, 'nodeleted') !== false || empty($this->config->user->showDeleted))->andWhere('deleted')->eq('0')->fi()
            ->beginIF(strpos($params, 'all') === false)->andWhere('type')->eq($type)->fi()
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->beginIF($this->config->vision && $this->app->rawModule !== 'kanban')->andWhere("FIND_IN_SET('{$this->config->vision}', visions)")->fi()
            ->orderBy($orderBy)
            ->beginIF($maxCount)->limit($maxCount)->fi()
            ->fetchAll($keyField);

        $this->processMoreLink($params, $usersToAppended, $maxCount, count($users));
        if($usersToAppended) $users += $this->fetchExtraUsers($usersToAppended, $fields, $keyField);
        $users = $this->processDisplayValue($users, $params);
        $users = $this->setCurrentUserFirst($users);

        /* Append empty, closed, and guest users. */
        if(strpos($params, 'noclosed')  === false) $users = $users + array('closed' => 'Closed');
        if(strpos($params, 'withguest') !== false) $users = $users + array('guest' => 'Guest');

        return $users;
    }

    /**
     * 处理获取更多用户的链接。
     * Process the more link.
     *
     * @param  string       $params
     * @param  string|array $usersToAppended
     * @param  int          $maxCount
     * @param  int          $userCount
     * @access public
     * @return bool
     */
    public function processMoreLink(string $params, string|array $usersToAppended, int $maxCount, int $userCount): bool
    {
        unset($this->config->user->moreLink);
        if($maxCount && $maxCount == $userCount)
        {
            if(is_array($usersToAppended)) $usersToAppended = join(',', $usersToAppended);
            $moreLinkParams = "params={$params}&usersToAppended={$usersToAppended}";

            $moreLink = helper::createLink('user', 'ajaxGetMore');
            $this->config->user->moreLink = $moreLink . (strpos($moreLink, '?') === false ? '?' : '&') . "params=" . base64_encode($moreLinkParams);
        }

        return true;
    }

    /**
     * 根据用户名获取额外的用户。
     * Get extra users by account.
     *
     * @param  string|array $usersToAppended
     * @param  string       $fields
     * @param  string       $keyField
     * @access public
     * @return array
     */
    public function fetchExtraUsers(string|array $usersToAppended, string $fields, string $keyField): array
    {
        if(!$usersToAppended) return array();

        return $this->dao->select($fields)->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll($keyField);
    }

    /**
     * 处理用户的显示名称。
     * Process the display value of users.
     *
     * @param  array  $users
     * @param  string $params
     * @access public
     * @return array
     */
    public function processDisplayValue(array $users, string $params): array
    {
        foreach($users as $account => $user)
        {
            if(strpos($params, 'showid') !== false)
            {
                $users[$account] = $user->id;
                continue;
            }

            $firstLetter = ucfirst(mb_substr($user->account, 0, 1)) . ':';
            if(strpos($params, 'noletter') !== false || !empty($this->config->isINT)) $firstLetter = '';
            $users[$account] =  $firstLetter . (($user->deleted && strpos($params, 'realname') === false) ? $user->account : ($user->realname ?: $user->account));
        }

        return $users;
    }

    /**
     * 获取用户名和头像的键值对。
     * Get account and avatar pairs.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getAvatarPairs(string $params = 'nodeleted')
    {
        $avatarPairs = array();
        $userList    = $this->getList($params, 'account,avatar');
        foreach($userList as $user) $avatarPairs[$user->account] = $user->avatar;

        return $avatarPairs;
    }

    /**
     * 获取源代码账号和真实姓名/用户名的键值对。
     * Get account and realname pairs.
     *
     * @param  string  $field
     * @access public
     * @return array
     */
    public function getCommiters(string $field = 'realname')
    {
        $rawCommiters = $this->dao->select('commiter, account, realname')->from(TABLE_USER)->where('commiter')->ne('')->fetchAll();
        if(!$rawCommiters) return array();

        $commiters = array();
        foreach($rawCommiters as $commiter)
        {
            $userCommiters = explode(',', $commiter->commiter);
            foreach($userCommiters as $userCommiter) $commiters[$userCommiter] = $commiter->$field ?: $commiter->account;
        }

        return $commiters;
    }

    /**
     * 获取用户名、真实姓名和邮箱组成的用户列表。
     * Get user list with account, realname and email.
     *
     * @param  string|array $users
     * @access public
     * @return array
     */
    public function getRealNameAndEmails(string|array $users): array
    {
        if(!$users) return array();
        return $this->dao->select("account, email, IF(realname = '', account, realname) AS realname")->from(TABLE_USER)->where('account')->in($users)->fetchAll('account');
    }

    /**
     * 获取用户名和角色名的键值对。
     * Get account and role pairs.
     *
     * @param  string|array $users
     * @access public
     * @return array
     */
    public function getUserRoles(string|array $users): array
    {
        if(!$users) return array();

        $users = $this->dao->select('account, role')->from(TABLE_USER)->where('account')->in($users)->fetchPairs();
        if(!$users) return array();

        foreach($users as $account => $role) $users[$account] = zget($this->lang->user->roleList, $role);
        return $users;
    }

    /**
     * 根据指定字段的值获取一个用户。
     * Get a user by the value of the specified field.
     *
     * @param  string  $userID
     * @param  string  $field id|account
     * @access public
     * @return object|bool
     */
    public function getById(string|int $userID, string $field = 'account'): object|bool
    {
        /* 如果 userID 参数为空并且当前用户是游客，返回当前用户，以确保仪表盘页面正常工作。*/
        /* If the userID param is empty and the current user is guest, return the current user to make sure the dashboard page works well. */
        if(empty($userID) && $this->app->user->account == 'guest') return $this->app->user;

        if($field == 'id')      $userID = (int)$userID;
        if($field == 'account') $userID = trim($userID);

        $user = $this->dao->select('*')->from(TABLE_USER)->where("`$field`")->eq($userID)->fetch();
        if(!$user) return false;

        $user->last = date(DT_DATETIME1, $user->last);
        return $user;
    }

    /**
     * 根据自定义查询语句获取用户。
     * Get users by custom query.
     *
     * @param  string $browseType inside|outside|all
     * @param  string $query
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getByQuery(string $browseType = 'inside', string $query = '', object $pager = null, string $orderBy = 'id'): array
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq('0')
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($browseType == 'inside')->andWhere('type')->eq('inside')->fi()
            ->beginIF($browseType == 'outside')->andWhere('type')->eq('outside')->fi()
            ->beginIF($this->config->vision)->andWhere("FIND_IN_SET('{$this->config->vision}', visions)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 添加一个用户。
     * Create a user.
     *
     * @param  object $user
     * @access public
     * @return false|int
     */
    public function create(object $user): bool|int
    {
        $this->checkBeforeCreateOrEdit($user);

        $this->dao->begin();

        $requiredFields = $this->config->user->create->requiredFields;
        if($user->type == 'outside')
        {
            if($user->new) $user->company = $this->createCompany($user->newCompany);
            $requiredFields = trim(str_replace(array(',dept,', ',commiter,'), '', ',' . $requiredFields . ','), ',');
        }

        $this->dao->insert(TABLE_USER)->data($user, 'new,newCompany,password1,password2,group,verifyPassword,passwordLength,passwordStrength')
            ->batchCheck($requiredFields, 'notempty')
            ->checkIF($user->account, 'account', 'unique')
            ->checkIF($user->account, 'account', 'account')
            ->checkIF($user->email, 'email', 'email')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return $this->rollback();

        $userID = $this->dao->lastInsertID();

        /* 创建用户组，更新用户视图并记录日志。*/
        /* Create user group, update user view and save log. */
        $groups = array_filter($user->group);
        if($groups) $this->createUserGroup($groups, $user->account);
        $this->loadModel('action')->create('user', $userID, 'Created');

        if(dao::isError()) return $this->rollback();

        $this->dao->commit();

        return $userID;
    }

    /**
     * 创建一个外部公司。
     * Create a outside company.
     *
     * @param  string $companyName
     * @access public
     * @return int
     */
    public function createCompany(string $companyName): int
    {
        if(empty($companyName))
        {
            dao::$errors['newCompany'][] = sprintf($this->lang->error->notempty, $this->lang->user->company);
            return 0;
        }

        $company = new stdClass();
        $company->name = $companyName;
        $this->dao->insert(TABLE_COMPANY)->data($company)->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * 创建用户组。
     * Create user group.
     *
     * @param  array  $groups
     * @param  string $account
     * @access public
     * @return bool
     */
    public function createUserGroup(array $groups, string $account): bool
    {
        if(empty($groups) || empty($account)) return false;

        $userGroup = new stdclass();
        $userGroup->account = $account;
        $userGroup->project = '';

        foreach($groups as $group)
        {
            $userGroup->group = $group;
            $this->dao->replace(TABLE_USERGROUP)->data($userGroup)->exec();
        }

        return !dao::isError();
    }

    /**
     * 批量创建用户。
     * Batch create users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return bool|array
     */
    public function batchCreate(array $users, string $verifyPassword): bool|array
    {
        if(!$users) return false;

        $this->checkBeforeBatchCreate($users, $verifyPassword);
        if(dao::isError()) return false;

        $this->loadModel('action');

        $this->dao->begin();

        $userIdList = array();
        $preCompany = 0;
        foreach($users as $index => $user)
        {
            if(empty($user->account)) continue;

            $user->password = md5($user->password);

            if($user->type == 'outside')
            {
                if($user->new) $user->company = $this->createCompany($user->newCompany);
                if($this->post->company[$index] != 'ditto') $preCompany = $user->company;
                if($this->post->company[$index] == 'ditto') $user->company = $preCompany;
            }

            $this->dao->insert(TABLE_USER)->data($user, 'new,newCompany,group')->autoCheck()->exec();
            if(dao::isError()) return $this->rollback();

            $userID = $this->dao->lastInsertID();

            /* 创建用户组，更新用户视图并记录日志。*/
            /* Create user group, update user view and save log. */
            $groups = array_filter($user->group);
            if($groups) $this->createUserGroup($groups, $user->account);
            $this->action->create('user', $userID, 'Created');

            if(dao::isError()) return $this->rollback();

            $userIdList[] = $userID;
        }

        $this->dao->commit();

        return $userIdList;
    }

    /**
     * 更新一个用户。
     * Update a user.
     *
     * @param  object $user
     * @access public
     * @return bool
     */
    public function update(object $user): bool
    {
        $oldUser = $this->getById($user->id, 'id');

        $this->checkBeforeCreateOrEdit($user, true);

        $this->dao->begin();

        if(!isset($user->type)) $user->type = $oldUser->type;
        if($user->type == 'outside' && $user->new) $user->company = $this->createCompany($user->newCompany);

        /* 获取所有的联系方式字段。*/
        /* Get all contact fields. */
        $allContactFields = array_keys($this->lang->user->contactFieldList);
        /* 获取可用的联系方式字段，转为数组并去空、去重。*/
        /* Get available contact fields, convert to array and remove empty and duplicate. */
        $availableContactFields = array_unique(array_filter(explode(',', trim($this->config->user->contactField, ','))));
        /* 获取不可用的联系方式字段。*/
        /* Get unavailable contact fields. */
        $unAvailableContactFields = array_diff($allContactFields, $availableContactFields);
        /* 从配置文件获取必填项字段，转为数组并去空、去重。*/
        /* Get required fields from config file, convert to array and remove empty and duplicate. */
        $requiredFields = array_unique(array_filter(explode(',', trim($this->config->user->edit->requiredFields, ','))));
        /* 从必填项字段中去除不可用的联系方式字段。*/
        /* Remove unavailable contact fields from available fields. */
        $requiredFields = implode(',', array_diff($requiredFields, $unAvailableContactFields));

        $this->dao->update(TABLE_USER)->data($user, 'new,newCompany,password1,password2,group,verifyPassword,passwordLength,passwordStrength')
            ->batchCheck($requiredFields, 'notempty')
            ->checkIF($user->account, 'account', 'unique', "id != '$user->id'")
            ->checkIF($user->account, 'account', 'account')
            ->checkIF($user->email, 'email',  'email')
            ->checkIF($user->phone, 'phone',  'phone')
            ->checkIF($user->mobile, 'mobile', 'mobile')
            ->autoCheck()
            ->where('id')->eq($user->id)
            ->exec();
        if(dao::isError()) return $this->rollBack();

        /* 更新用户组和用户视图并记录积分和日志。*/
        /* Update user group and user view, and save log and score. */
        $this->checkAccountChange($oldUser->account, $user->account);
        $this->checkGroupChange($user);
        $this->loadModel('score')->create('user', 'editProfile');
        $changes = common::createChanges($oldUser, $user);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('user', $user->id, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        if(dao::isError()) return $this->rollBack();

        $this->dao->commit();

        /* 更新当前用户的信息。*/
        /* Update current user's info. */
        if($user->account == $this->app->user->account)
        {
            if(!empty($user->password)) $this->app->user->password = $user->password;
            $this->app->user->realname = $user->realname;
            $this->app->user->role     = isset($user->role) ? $user->role : $oldUser->role;
        }

        return true;
    }

    /**
     * 检查用户名是否发生变化。
     * Check if the account changed.
     *
     * @param  string $oldAccount
     * @param  string $newAccount
     * @access public
     * @return bool
     */
    public function checkAccountChange(string $oldAccount, string $newAccount): bool
    {
        if($oldAccount == $newAccount) return false;

        /* 更新用户组和用户视图。*/
        /* Update the user group and user view. */
        $this->dao->update(TABLE_USERGROUP)->set('account')->eq($newAccount)->where('account')->eq($oldAccount)->exec();
        $this->dao->update(TABLE_USERVIEW)->set('account')->eq($newAccount)->where('account')->eq($oldAccount)->exec();

        /* 如果旧用户名是公司管理员，则更新公司管理员。*/
        /* If the old account is company admin, update the company admin. */
        if(strpos($this->app->company->admins, ',' . $oldAccount . ',') !== false)
        {
            $admins = str_replace(',' . $oldAccount . ',', ',' . $newAccount . ',', $this->app->company->admins);
            $this->dao->update(TABLE_COMPANY)->set('admins')->eq($admins)->where('id')->eq($this->app->company->id)->exec();
            if(dao::isError()) return false;

            $this->app->company->admins = $admins;
        }
        return !dao::isError();
    }

    /**
     * 检查权限组是否发生变化。
     * Check if the group changed.
     *
     * @param  object $user
     * @access public
     * @return bool
     */
    public function checkGroupChange(object $user): bool
    {
        $oldGroups = array_keys($this->loadModel('group')->getByAccount($user->account, true));
        $newGroups = array_unique(array_filter($user->group));

        sort($oldGroups);
        sort($newGroups);

        if(join(',', $oldGroups) == join(',', $newGroups)) return false;

        /* 如果权限组发生变化，则删除原有的权限组，重新创建并更新用户视图。*/
        /* If the group changed, delete the old group, create new group and update user view. */
        $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($user->account)->exec();
        if($newGroups) $this->createUserGroup($newGroups, $user->account);

        return !dao::isError();
    }

    /**
     * 批量编辑用户。
     * Batch update user.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return bool
     */
    public function batchUpdate(array $users, string $verifyPassword): bool
    {
        if(!$users) return false;

        $this->checkBeforeBatchUpdate($users, $verifyPassword);
        if(dao::isError()) return false;

        $this->loadModel('action');

        $accounts = array_map(function($user){return $user->account;}, $users);
        $oldUsers = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($accounts)->fetchAll('id');

        $this->dao->begin();

        foreach($users as $id => $user)
        {
            $this->dao->update(TABLE_USER)->data($user)->where('id')->eq($id)->autoCheck()->exec();
            if(dao::isError()) return $this->rollback();

            /* 更新用户组和用户视图并记录日志。*/
            /* Update user group and user view, and save log and score. */
            $oldUser = $oldUsers[$id];
            $changes = common::createChanges($oldUser, $user);
            if($changes)
            {
                $actionID = $this->action->create('user', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            if(dao::isError()) return $this->rollback();

            /* 更新当前用户的信息。*/
            /* Update current user's info. */
            if($user->account == $this->app->user->account)
            {
                $this->app->user->realname = $user->realname;
                $this->app->user->role     = $user->role;
            }
        }

        $this->dao->commit();

        return true;
    }

    /**
     * 更新当前用户的密码。
     * Update current user's password.
     *
     * @param  object $user
     * @access public
     * @return bool
     */
    public function updatePassword(object $user): bool
    {
        $this->checkPassword($user);

        if($user->originalPassword != md5($this->app->user->password . $this->session->rand)) dao::$errors['originalPassword'][] = $this->lang->user->error->originalPassword;
        if(dao::isError()) return false;

        $this->dao->update(TABLE_USER)->set('password')->eq($user->password)->where('id')->eq($this->app->user->id)->exec();
        if(dao::isError()) return false;

        $this->loadModel('score')->create('user', 'changePassword', $this->computePasswordStrength($user->password1));

        $this->app->user->password             = $user->password;
        $this->app->user->modifyPassword       = false;
        $this->app->user->modifyPasswordReason = '';

        $_SESSION['user'] = $this->app->user;

        return true;
    }

    /**
     * 重置密码。
     * Reset password.
     *
     * @param  object $user
     * @access public
     * @return bool
     */
    public function resetPassword(object $user): bool
    {
        $oldUser = $this->getById($user->account);
        if(!$oldUser)
        {
            dao::$errors['account'] = $this->lang->user->error->noUser;
            return false;
        }

        $this->checkPassword($user);

        $this->dao->update(TABLE_USER)->set('password')->eq($user->password)->where('account')->eq($user->account)->exec();

        return !dao::isError();
    }

    /**
     * 验证用户并设置相关属性。
     * Identify user and set related properties.
     *
     * @param  string $account              the user account
     * @param  string $password             the user password or auth hash
     * @param  int    $passwordStrength     the user password strength
     * @access public
     * @return bool|object
     */
    public function identify(string $account, string $password, int $passwordStrength = 0): bool|object
    {
        if(!$account || !$password) return false;
        if(!validater::checkAccount($account)) return false;

        $user = $this->identifyUser($account, $password);
        if(!$user) return false;

        $ip   = helper::getRemoteIp();
        $last = $this->server->request_time;
        $user = $this->checkNeedModifyPassword($user, $passwordStrength);

        $user->lastTime = $user->last;
        $user->last     = date(DT_DATETIME1, $last);
        $user->admin    = strpos($this->app->company->admins, ",{$user->account},") !== false;

        if($this->app->isServing())
        {
            $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();

            /* 登录后创建周期性待办。*/
            /* Create cycle todo after login. */
            $todoList = $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('deleted')->eq('0')->andWhere('account')->eq($user->account)->fetchAll('id');
            if($todoList) $this->loadModel('todo')->createByCycle($todoList);
        }

        if($user->avatar)
        {
            $avatarRoot = substr($user->avatar, 0, strpos($user->avatar, 'data/upload/'));
            if($this->config->webRoot != $avatarRoot) $user->avatar = substr_replace($user->avatar, $this->config->webRoot, 0, strlen($avatarRoot));
        }
        return $user;
    }

    /**
     * 根据用户名和密码验证用户。
     * Identify user by account and password.
     *
     * @param  string $account      the user account
     * @param  string $password     the user password or auth hash
     * @access public
     * @return bool|object
     */
    public function identifyUser(string $account, string $password): bool|object
    {
        $user = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq('0')->andWhere('account')->eq($account)->fetch();
        if(!$user) return false;

        $passwordLength = strlen($password);

        if($passwordLength == 32)
        {
            $hash = $this->session->rand ? md5($user->password . $this->session->rand) : $user->password;
            if($password == $hash) return $user;
        }

        if($passwordLength == 40)
        {
            $hash = sha1($user->account . $user->password . $user->last);
            if($password == $hash) return $user;
        }

        return md5($password) == $user->password ? $user : false;
    }

    /**
     * 检查是否需要修改密码。
     * Check if need to modify password.
     *
     * @param  object $user
     * @param  int    $passwordStrength
     * @access public
     * @return object
     */
    public function checkNeedModifyPassword(object $user, int $passwordStrength): object
    {
        /* 如果开启了首次登录修改密码功能，检查是否是首次登录。*/
        /* If the modify password on first login feature is enabled, check if it's the first login. */
        if(!empty($this->config->safe->modifyPasswordFirstLogin))
        {
            $user->modifyPassword = $user->visits == 0;
            if($user->modifyPassword)
            {
                $user->modifyPasswordReason = 'modifyPasswordFirstLogin';
                return $user;
            }
        }

        /* 如果开启了修改弱口令密码功能，检查是否是弱口令。*/
        /* If the modify weak password feature is enabled, check if it's a weak password. */
        if(!empty($this->config->safe->changeWeak))
        {
            $user->modifyPassword = $this->loadModel('admin')->checkWeak($user);
            if($user->modifyPassword)
            {
                $user->modifyPasswordReason = 'weak';
                return $user;
            }
        }

        /* 如果开启了密码强度检查功能，检查密码是否满足强度要求。*/
        /* If the password strength feature is enabled, check if the password is strong enough. */
        if(!empty($this->config->safe->mode) && $this->app->moduleName == 'user' && $this->app->methodName == 'login')
        {
            $user->modifyPassword = $passwordStrength < $this->config->safe->mode;
            if($user->modifyPassword)
            {
                $user->modifyPasswordReason = 'passwordStrengthWeak';
                return $user;
            }
        }

        return $user;
    }

    /**
     * 根据 PHP 的 HTTP 认证验证用户。
     * Identify user by PHP HTTP auth.
     *
     * @access public
     * @return bool
     */
    public function identifyByPhpAuth(): bool
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
     * 根据 Cookie 验证用户。
     * Identify user by cookie.
     *
     * @access public
     * @return bool
     */
    public function identifyByCookie(): bool
    {
        $account  = $this->cookie->za;
        $authHash = $this->cookie->zp;
        $user     = $this->identify($account, $authHash, 3); // Set passwordStrength=MAX_PASSWORD_STRENGTH, don't check modifyPassword.
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
     * 获取某用户的视图访问权限。
     * Get user acls.
     *
     * @param  string  $account
     * @access private
     * @return array
     */
    private function getUserAcls(string $account): array
    {
        if($account == 'guest')
        {
            $acls = $this->dao->select('acl')->from(TABLE_GROUP)->where('name')->eq('guest')->fetch('acl');
            return !empty($acls) ? json_decode($acls, true) : array();
        }

        $groups = $this->dao->select('t1.acl, t1.project')->from(TABLE_GROUP)->alias('t1')
            ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id=t2.`group`')
            ->where('t2.account')->eq($account)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.role')->ne('projectAdmin')
            ->andWhere('t1.role')->ne('limited')
            ->andWhere('t1.project')->eq(0)
            ->fetchAll();

        /* Init variables. */
        $acls         = array('programs' => array(), 'projects' => array(), 'products' => array(), 'sprints' => array(), 'views' => array(), 'actions' => array());
        $programAllow = $projectAllow = $productAllow = $sprintAllow = $viewAllow = $actionAllow = false;

        /* Authorize by group. */
        foreach($groups as $group)
        {
            /* 只要有一个权限分组没有配置过视图权限，就代表所有视图都没有访问限制。 */
            if(empty($group->acl))
            {
                $programAllow = $projectAllow = $productAllow = $sprintAllow = $viewAllow = $actionAllow = true;
                break;
            }

            $acl = json_decode($group->acl, true);

            /* 只要有一个权限分组的某个配置为空，就代表该配置没有访问限制。 */
            if(empty($acl['programs'])) $programAllow = true;
            if(empty($acl['projects'])) $projectAllow = true;
            if(empty($acl['products'])) $productAllow = true;
            if(empty($acl['sprints']))  $sprintAllow  = true;
            if(empty($acl['views']))    $viewAllow    = true;
            if(!isset($acl['actions'])) $actionAllow  = true;

            /* 将所有权限分组的视图访问限制合并。 */
            if(!$programAllow && !empty($acl['programs'])) $acls['programs'] = array_merge($acls['programs'], $acl['programs']);
            if(!$projectAllow && !empty($acl['projects'])) $acls['projects'] = array_merge($acls['projects'], $acl['projects']);
            if(!$productAllow && !empty($acl['products'])) $acls['products'] = array_merge($acls['products'], $acl['products']);
            if(!$sprintAllow  && !empty($acl['sprints']))  $acls['sprints']  = array_merge($acls['sprints'],  $acl['sprints']);
            if(!$actionAllow  && !empty($acl['actions']))  $acls['actions']  = array_merge($acls['actions'],  $acl['actions']);
            if(!$viewAllow    && !empty($acl['views']))    $acls['views']    = array_merge($acls['views'],    $acl['views']);
        }

        /* 只要有一个权限分组的某个配置为空，就代表该配置没有访问限制。 */
        if($programAllow) $acls['programs'] = array();
        if($projectAllow) $acls['projects'] = array();
        if($productAllow) $acls['products'] = array();
        if($sprintAllow)  $acls['sprints']  = array();
        if($viewAllow)    $acls['views']    = array();
        if($actionAllow)  $acls['actions']  = array();
        if($actionAllow && empty($acls['actions'])) unset($acls['actions']);

        return $acls;
    }

    /**
     * 获取某个用户的权限。
     * Get user's rights.
     *
     * @param   string $account
     * @access  public
     * @return  array the user rights.
     */
    public function authorize(string $account): array
    {
        $account = filter_var($account, FILTER_UNSAFE_RAW);
        if(!$account) return array();

        $acls = $this->getUserAcls($account);
        if($account == 'guest')
        {
            $stmt = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')->leftJoin(TABLE_GROUPPRIV)->alias('t2')->on('t1.id = t2.`group`')->where('t1.name')->eq('guest')->query();
        }
        else
        {
            $stmt = $this->dao->select('module, method')->from(TABLE_GROUP)->alias('t1')
                ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id = t2.`group`')
                ->leftJoin(TABLE_GROUPPRIV)->alias('t3')->on('t2.`group` = t3.`group`')
                ->where('t2.account')->eq($account)
                ->andWhere('t1.project')->eq(0)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->query();
        }

        if(!$stmt) return array('rights' => array(), 'acls' => $acls);

        /* 获取用户拥有的权限列表，而首页是大家都应该有的权限。 */
        $rights = array('index' => array('index' => 1), 'my' => array('index' => 1));
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($row['module'] && $row['method']) $rights[strtolower($row['module'])][strtolower($row['method'])] = true;
        }

        /* Get can manage projects by user. */
        $canManageProjects = $canManagePrograms = $canManageProducts = $canManageExecutions = '';
        if(!$this->app->upgrading)
        {
            $canManageObjects = $this->dao->select('programs,projects,products,executions')->from(TABLE_PROJECTADMIN)->where('account')->eq($account)->fetchAll();
            foreach($canManageObjects as $object)
            {
                if($object->projects)   $canManageProjects   .= $object->projects   . ',';
                if($object->products)   $canManageProducts   .= $object->products   . ',';
                if($object->programs)   $canManagePrograms   .= $object->programs   . ',';
                if($object->executions) $canManageExecutions .= $object->executions . ',';
            }
        }

        return array('rights' => $rights, 'acls' => $acls, 'projects' => $canManageProjects, 'programs' => $canManagePrograms, 'products' => $canManageProducts, 'executions' => $canManageExecutions);
    }

    /**
     * 获取用户所属权限组、权限和视图，记录日志并发放登录积分。
     * Get user's groups, rights and views, save log and give login score.
     *
     * @param  object $user
     * @param  bool   $addAction
     * @param  bool   $keepLogin
     * @access public
     * @return false|object
     */
    public function login(object $user, bool $addAction = true, bool $keepLogin = false): bool|object
    {
        if(empty($user->account)) return false;

        if($user->fails || $user->locked) $this->cleanLocked($user->account);

        /* 获取用户所属权限组、权限和视图，判断用户是否是管理员。*/
        /* Get user's groups, rights and views, and judge if the user is admin. */
        $user->rights = $this->authorize($user->account);
        $user->groups = $this->getGroups($user->account);
        $user->view   = $this->grantUserView($user->account, $user->rights['acls'], $user->rights['projects']);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;

        $this->session->set('user', $user);
        $this->app->user = $this->session->user;

        /* 记录登录日志并发放积分。*/
        /* Save log and give login score. */
        if(isset($user->id) and $addAction) $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');

        /* 保持登录状态。*/
        /* Keep login. */
        if($keepLogin) $this->keepLogin($user);

        return $user;
    }

    /**
     * 保持用户的登录状态。
     * Keep user's login state.
     *
     * @param  object $user
     * @access public
     * @return bool
     */
    public function keepLogin(object $user): bool
    {
        helper::setcookie('keepLogin', 'on');
        helper::setcookie('za', $user->account);
        helper::setcookie('zp', sha1($user->account . $user->password . $this->server->request_time));
        return true;
    }

    /**
     * 判断用户是否已经登录。
     * Check if the user has logged in.
     *
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        $user = $this->session->user;
        return ($user && !empty($user->account) && $user->account != 'guest');
    }

    /**
     * 获取用户所属的权限组。
     * Get groups a user belongs to.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getGroups(string $account): array
    {
        return $this->dao->findByAccount($account)->from(TABLE_USERGROUP)->fields('`group`')->fetchPairs();
    }

    /**
     * 根据界面类型获取权限组。
     * Get groups by visions.
     *
     * @param  string|array $visions
     * @access public
     * @return array
     */
    public function getGroupsByVisions(string|array $visions): array
    {
        if(is_string($visions)) $visions = array_unique(array_filter(explode(',', $visions)));
        if(!$visions) return array();

        $groups = $this->dao->select('id, name, vision')->from(TABLE_GROUP)
            ->where('project')->eq(0)
            ->andWhere('vision')->in($visions)
            ->fetchAll('id');

        $visionCount = count($visions);
        $visionList  = getVisions();

        foreach($groups as $id => $group)
        {
            $groups[$id] = $group->name;

            if($visionCount > 1 && !empty($visionList[$group->vision])) $groups[$id] = $visionList[$group->vision] . ' / ' . $group->name;
        }

        return $groups;
    }

    /**
     * 获取某个用户参与的项目。
     * Get the projects that a user participated in.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjects(string $account, string $status = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        $projects = $this->userTao->fetchProjects($account, $status, $orderBy, $pager);
        if(!$projects) return array();

        $projectStoryCountAndEstimate = $this->userTao->fetchProjectStoryCountAndEstimate(array_keys($projects));
        $projectExecutionCount        = $this->userTao->fetchProjectExecutionCount(array_keys($projects));

        foreach($projects as $project)
        {
            /* Judge whether the project is delayed. */
            if($project->status != 'done' && $project->status != 'closed' && $project->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }

            $projectStory = zget($projectStoryCountAndEstimate, $project->id, '');
            $project->storyPoints    = $projectStory ? round($projectStory->estimate, 1) : 0;
            $project->storyCount     = $projectStory ? $projectStory->count : 0;
            $project->executionCount = zget($projectExecutionCount, $project->id, 0);
        }

        return $projects;
    }

    /**
     * 获取某个用户参与的执行。
     * Get the executions that a user participated in.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutions(string $account, string $status = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        $executions = $this->userTao->fetchExecutions($account, $status, $orderBy, $pager);
        if(!$executions) return array();

        $projectIdList = array_map(function($execution){return $execution->project;}, $executions);
        $projectPairs  = $this->loadModel('project')->getPairsByIdList($projectIdList);
        $taskCountList = $this->userTao->fetchExecutionTaskCount($account, array_keys($executions));

        foreach($executions as $execution)
        {
            /* Judge whether the execution is delayed. */
            if($execution->status != 'done' && $execution->status != 'closed' && $execution->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $execution->end);
                if($delay > 0) $execution->delay = $delay;
            }

            $execution->projectName       = zget($projectPairs, $execution->project, '');
            $execution->assignedToMeTasks = zget($taskCountList, $execution->id, 0);
        }

        return $executions;
    }

    /**
     * 登录失败次数加 1。
     * The number of failed login attempts is increased by 1.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function failPlus(string $account): int
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
            $this->dao->update(TABLE_USER)->set('fails')->eq($fails)->set('locked = NULL')->where('account')->eq($account)->exec();
            return $fails;
        }

        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq(date('Y-m-d H:i:s'))->where('account')->eq($account)->exec();
        return $fails;
    }

    /**
     * 检查用户是否被锁定。
     * Check whether the user is locked.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function checkLocked(string $account): bool
    {
        $user = $this->dao->select('locked')->from(TABLE_USER)->where('account')->eq($account)->fetch();
        if(empty($user) || empty($user->locked)) return false;

        if($this->session->{"{$account}.loginLocked"} && (time() - strtotime($this->session->{"{$account}.loginLocked"})) <= $this->config->user->lockMinutes * 60) return true;

        if((time() - strtotime($user->locked)) > $this->config->user->lockMinutes * 60) return false;

        return true;
    }

    /**
     * 解锁被锁定的用户。
     * Unlock the locked user.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function cleanLocked(string $account): bool
    {
        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked = NULL')->where('account')->eq($account)->exec();

        unset($_SESSION['loginFails']);
        unset($_SESSION["{$account}.loginLocked"]);

        return !dao::isError();
    }

    /**
     * 解除禅道账号和 ZDOO 账号的绑定。
     * Unbind ZDOO account and ZenTao account.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function unbind(string $account): bool
    {
        $this->dao->update(TABLE_USER)->set('ranzhi')->eq('')->where('account')->eq($account)->exec();
        return !dao::isError();
    }

    /**
     * 上传头像。
     * Upload avatar.
     *
     * @access public
     * @return array
     */
    public function uploadAvatar(): array
    {
        $uploadResult = $this->loadModel('file')->saveUpload('avatar');
        if(!$uploadResult) return array('result' => 'fail', 'message' => $this->lang->fail);

        $fileIdList = array_keys($uploadResult);
        $file       = $this->file->getByID(end($fileIdList));

        if(!in_array($file->extension, array('jpg', 'jpeg', 'gif', 'png', 'bmp'))) return array('result' => 'fail', 'message' => $this->lang->user->error->uploadAvatar);
        return array('result' => 'success', 'message' => '', 'fileID' => $file->id, 'locate' => helper::createLink('user', 'cropavatar', "image={$file->id}"));
    }

    /**
     * 获取某个用户可以查看的联系人列表。
     * Get the contact list of a user.
     *
     * @param  string $account
     * @param  string $mode    pairs|list
     * @access public
     * @return array
     */
    public function getContactLists(string $account = '', string $mode = 'pairs'): array
    {
        if(!$account) $account = $this->app->user->account;

        $this->dao->select('*')->from(TABLE_USERCONTACT)
            ->where('account')->eq($account)
            ->orWhere('public')->eq(1)
            ->orderBy('public, id_desc');

        if($mode == 'pairs') return $this->dao->fetchPairs('id', 'listName');

        return $this->dao->fetchAll();
    }

    /**
     * 获取拥有迭代访问权限的用户列表。
     * Get users who have access to the parent stage.
     *
     * @param  int    $stageID
     * @access public
     * @return array
     */
    public function getParentStageAuthedUsers(int $stageID = 0): array
    {
        return $this->dao->select('account')->from(TABLE_USERVIEW)->where("FIND_IN_SET({$stageID}, sprints)")->fetchPairs();
    }

    /**
     * 根据 id 获取一个联系人列表。
     * Get a contact list by id.
     *
     * @param  int          $listID
     * @access public
     * @return object|false
     */
    public function getContactListByID(int $listID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_USERCONTACT)->where('id')->eq($listID)->fetch();
    }

    /**
     * 创建一个联系人列表。
     * Create a contact list.
     *
     * @param  object $userContact
     * @access public
     * @return bool
     */
    public function createContactList(object $userContact): bool
    {
        $this->dao->insert(TABLE_USERCONTACT)->data($userContact)
            ->batchCheck('listName,userList', 'notempty')
            ->check('listName', 'unique', "account = '{$userContact->account}'")
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * 更新一个联系人列表。
     * Update a contact list.
     *
     * @param  object $userContact
     * @access public
     * @return bool
     */
    public function updateContactList(object $userContact): bool
    {
        $this->dao->update(TABLE_USERCONTACT)->data($userContact)
            ->batchCheck('listName,userList', 'notempty')
            ->check('listName', 'unique', "id != '{$userContact->id}' AND account = '{$userContact->account}'")
            ->autoCheck()
            ->where('id')->eq($userContact->id)
            ->exec();
        return !dao::isError();
    }

    /**
     * 删除一个联系人列表。
     * Delete a contact list.
     *
     * @param  int    $listID
     * @access public
     * @return bool
     */
    public function deleteContactList(int $listID): bool
    {
        $this->dao->delete()->from(TABLE_USERCONTACT)->where('id')->eq($listID)->exec();
        return !dao::isError();
    }

    /**
     * 获取弱密码用户。
     * Get weak users.
     *
     * @access public
     * @return array
     */
    public function getWeakUsers(): array
    {
        $weaks = array();
        foreach(explode(',', $this->config->safe->weak) as $weak)
        {
            if($weak) $weaks[$weak] = md5(trim($weak));
        }

        $weakUsers = array();
        $users     = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll();
        foreach($users as $user)
        {
            if(isset($weaks[$user->password]) || in_array($user->password, $weaks))
            {
                $user->weakReason = 'weak';
                $weakUsers[] = $user;
                continue;
            }

            foreach(array('account', 'phone', 'mobile', 'birthday') as $field)
            {
                if(empty($user->$field)) continue;
                if($user->password == $user->$field || $user->password == md5($user->$field))
                {
                    $user->weakReason = $field;
                    $weakUsers[] = $user;
                    break;
                }
            }
        }

        return $weakUsers;
    }

    /**
     * 计算密码强度。
     * Compute password strength.
     *
     * @param  string $password
     * @access public
     * @return int
     */
    public function computePasswordStrength(string $password): int
    {
        $length = strlen($password);
        if($length == 0) return 0;

        $complexity = array();
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

        if($sumComplexity == 15 && $length >= 10) return 2;
        if(($sumComplexity == 7 || $sumComplexity == 15) && $length >= 6)  return 1;
        return 0;
    }

    /**
     * 初始化访问权限所属的数据。
     * Init user view objects.
     *
     * @param  bool    $force
     * @access private
     * @return array
     */
    private function initViewObjects(bool $force = false): array
    {
        $this->loadModel('project');

        static $allProducts, $allProjects, $allPrograms, $allSprints, $teams, $whiteList, $stakeholders;

        if(!$allProducts || $force) $allProducts = $this->loadModel('product')->getListByAcl('private');
        if(!$allProjects || $force) $allProjects = $this->project->getListByAclAndType('private,program', 'project');
        if(!$allPrograms || $force) $allPrograms = $this->project->getListByAclAndType('private,program', 'program');
        if(!$allSprints  || $force) $allSprints  = $this->project->getListByAclAndType('private', 'sprint,stage,kanban');

        if(!$teams || $force)
        {
            $teams    = array();
            $teamList = $this->project->getTeamListByType('project,execution');
            foreach($teamList as $team) $teams[$team->type][$team->root][$team->account] = $team->account;
        }

        /* Get white list. */
        if(!$whiteList || $force)
        {
            $whiteList = array();
            $aclList   = $this->project->getAclListByObjectType('program,project,sprint,product');
            foreach($aclList as $acl) $whiteList[$acl->objectType][$acl->objectID][$acl->account] = $acl->account;
        }

        /* Get stakeholders. */
        if(!$stakeholders || $force)
        {
            $stakeholders  = array();
            $cachedHolders = $this->mao->select('objectID, objectType, user')->from(TABLE_STAKEHOLDER)->fetchAll();
            foreach($cachedHolders as $holder) $stakeholders[$holder->objectType][$holder->objectID][$holder->user] = $holder->user;
        }

        return array($allProducts, $allProjects, $allPrograms, $allSprints, $teams, $whiteList, $stakeholders);
    }

    /**
     * 获取用户的可访问项目集。
     * Get program user view.
     *
     * @param  string  $account
     * @param  array   $allPrograms
     * @param  array   $manageObjects
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @param  array   $programStakeholderGroup
     * @access private
     * @return string
     */
    private function getProgramView(string $account, array $allPrograms, array $manageObjects, array $stakeholders, array $whiteList, array $programStakeholderGroup): string
    {
        $programView = '';
        if(!empty($manageObjects['programs']['isAdmin']))
        {
            $programView = join(',', array_keys($allPrograms));
        }
        else
        {
            $programs       = array();
            $managePrograms = isset($manageObjects['programs']['list']) ? $manageObjects['programs']['list'] : '';
            foreach($allPrograms as $programID => $program)
            {
                /* 如果是某个项目集的干系人，也可以访问该项目集。 */
                $programStakeholders = !empty($stakeholders['program'][$programID]) ? $stakeholders['program'][$programID] : array();
                if($program->acl == 'program') $programStakeholders += zget($programStakeholderGroup, $programID, array());

                /* 如果是某个项目集的白名单用户，也可以访问该项目集。 */
                $programWhiteList = !empty($whiteList['program'][$programID]) ? $whiteList['program'][$programID] : array();
                if($this->checkProgramPriv($program, $account, $programStakeholders, $programWhiteList)) $programs[$programID] = $programID;

                /* 如果有某个项目集的管理权限，也可以访问该项目集。 */
                if(strpos(",$managePrograms,", ",$programID,") !== false) $programs[$programID] = $programID;
            }
            $programView = join(',', $programs);
        }

        return $programView;
    }

    /**
     * 获取用户的可访问产品。
     * Get product user view.
     *
     * @param  string $account
     * @param  array  $allProducts
     * @param  array  $manageObjects
     * @param  array  $whiteList
     * @access private
     * @return string
     */
    private function getProductView(string $account, array $allProducts, array $manageObjects, array $whiteList): string
    {
        $productView = '';
        if(!empty($manageObjects['products']['isAdmin']))
        {
            $productView = join(',', array_keys($allProducts));
        }
        else
        {
            $products       = array();
            $manageProducts = isset($manageObjects['products']['list']) ? $manageObjects['products']['list'] : '';

            list($productTeams, $productStakeholders) = $this->getProductMembers($allProducts);
            foreach($allProducts as $productID => $product)
            {
                /* 根据团队、干系人、白名单判断是否可以访问该产品。 */
                $productTeam        = zget($productTeams, $productID, array());
                $productStakeholder = zget($productStakeholders, $productID, array());
                $productWhiteList   = !empty($whiteList['product'][$productID]) ? $whiteList['product'][$productID] : array();
                if($this->checkProductPriv($product, $account, $productTeam, $productStakeholder, $productWhiteList)) $products[$productID] = $productID;

                /* 如果有某个产品的管理权限，也可以访问该产品。 */
                if(strpos(",$manageProducts,", ",$productID,") !== false) $products[$productID] = $productID;
            }
            $productView = join(',', $products);
        }

        return $productView;
    }

    /**
     * 获取用户的可访问项目。
     * Get project user view.
     *
     * @param  string  $account
     * @param  array   $allProjects
     * @param  array   $manageObjects
     * @param  array   $teams
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @param  array   $projectStakeholderGroup
     * @access private
     * @return string
     */
    private function getProjectView(string $account, array $allProjects, array $manageObjects, array $teams, array $stakeholders, array $whiteList, array $projectStakeholderGroup): string
    {
        $projectView = '';
        if(!empty($manageObjects['projects']['isAdmin']))
        {
            $projectView = join(',', array_keys($allProjects));
        }
        else
        {
            $projects       = array();
            $manageProjects = isset($manageObjects['projects']['list']) ? $manageObjects['projects']['list'] : '';
            foreach($allProjects as $projectID => $project)
            {
                /* 根据团队、干系人、白名单判断是否可以访问该项目。 */
                $projectTeams        = !empty($teams['project'][$projectID])        ? $teams['project'][$projectID]        : array();
                $projectStakeholders = !empty($stakeholders['project'][$projectID]) ? $stakeholders['project'][$projectID] : array();
                $projectWhiteList    = !empty($whiteList['project'][$projectID])    ? $whiteList['project'][$projectID]    : array();
                if($project->acl == 'program') $projectStakeholders += zget($projectStakeholderGroup, $projectID, array());
                if($this->checkProjectPriv($project, $account, $projectStakeholders, $projectTeams, $projectWhiteList)) $projects[$projectID] = $projectID;

                /* 如果有某个项目管理权限，也可以访问该项目。 */
                if(strpos(",$manageProjects,", ",$projectID,") !== false) $projects[$projectID] = $projectID;
            }
            $projectView = join(',', $projects);
        }

        return $projectView;
    }

    /**
     * 获取用户的可访问迭代。
     * Get sprint user view.
     *
     * @param  string  $account
     * @param  array   $allSprints
     * @param  array   $manageObjects
     * @param  array   $teams
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @access private
     * @return string
     */
    private function getSprintView(string $account, array $allSprints, array $manageObjects, array $teams, array $stakeholders, array $whiteList): string
    {
        $sprintView = '';
        if(!empty($manageObjects['executions']['isAdmin']))
        {
            $sprintView = join(',', array_keys($allSprints));
        }
        else
        {
            $sprints          = array();
            $manageExecutions = isset($manageObjects['executions']['list']) ? $manageObjects['executions']['list'] : '';
            foreach($allSprints as $sprintID => $sprint)
            {
                /* 根据团队、干系人、白名单判断是否可以访问该迭代。 */
                $sprintTeams        = !empty($teams['execution'][$sprintID])        ? $teams['execution'][$sprintID]        : array();
                $sprintStakeholders = !empty($stakeholders['execution'][$sprintID]) ? $stakeholders['execution'][$sprintID] : array();
                $sprintWhiteList    = !empty($whiteList['sprint'][$sprintID])       ? $whiteList['sprint'][$sprintID]       : array();
                if($this->checkProjectPriv($sprint, $account, $sprintStakeholders, $sprintTeams, $sprintWhiteList)) $sprints[$sprintID] = $sprintID;

                /* 如果有某个迭代管理权限，也可以访问该迭代。 */
                if(strpos(",$manageExecutions,", ",$sprintID,") !== false) $sprints[$sprintID] = $sprintID;
            }
            $sprintView = join(',', $sprints);
        }

        return $sprintView;
    }

    /**
     * 按照类型分组获取用户管理的对象。
     * Get management objects by type grouping.
     *
     * @param  string  $account
     * @access private
     * @return array
     */
    private function getManageListGroupByType(string $account): array
    {
        $manageObjects = array();
        $projectAdmins = $this->dao->select('`group`,programs,products,projects,executions')->from(TABLE_PROJECTADMIN)->where('account')->eq($account)->fetchAll('group');
        foreach($projectAdmins as $projectAdmin)
        {
            foreach($projectAdmin as $key => $value)
            {
                if(!isset($manageObjects[$key]['list'])) $manageObjects[$key]['list'] = '';
                if($value == 'all')
                {
                    $manageObjects[$key]['isAdmin'] = 1;
                }
                else if($value)
                {
                    $manageObjects[$key]['list'] .= $value . ',';
                }
            }
        }

        return $manageObjects;
    }

    /**
     * 获取用户的可访问对象。
     * Compute user view.
     *
     * @param  string $account
     * @param  bool   $force    是否重新生成
     * @access public
     * @return object
     */
    public function computeUserView(string $account = '', bool $force = false): object
    {
        $userView = new stdclass();
        $userView->products = $userView->programs = $userView->projects = $userView->sprints = '';

        if(empty($account)) $account = $this->session->user->account;
        if(empty($account)) return $userView;

        $userView = $this->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch();
        if(!empty($userView) && !$force) return $userView;

        /* Init objects. */
        list($allProducts, $allProjects, $allPrograms, $allSprints, $teams, $whiteList, $stakeholders) = $this->initViewObjects($force);

        /* Init user view. */
        $userView = new stdclass();
        $userView->account = $account;

        $isAdmin = strpos($this->app->company->admins, ',' . $account . ',') !== false;
        if($isAdmin)
        {
            $userView->programs = join(',', array_keys($allPrograms));
            $userView->products = join(',', array_keys($allProducts));
            $userView->projects = join(',', array_keys($allProjects));
            $userView->sprints  = join(',', array_keys($allSprints));
        }
        else
        {
            /* Compute parent stakeholders. */
            $this->loadModel('stakeholder');
            $programStakeholderGroup = $this->stakeholder->getParentStakeholderGroup(array_keys($allPrograms));
            $projectStakeholderGroup = $this->stakeholder->getParentStakeholderGroup(array_keys($allProjects));

            /* 按照类型分组获取当前用户所拥有的的项目管理权限。 */
            $manageObjects = $this->getManageListGroupByType($account);

            /* 分别获取各类型的可浏览ID。 */
            $userView->programs = $this->getProgramView($account, $allPrograms, $manageObjects, $stakeholders, $whiteList, $programStakeholderGroup);
            $userView->products = $this->getProductView($account, $allProducts, $manageObjects, $whiteList);
            $userView->projects = $this->getProjectView($account, $allProjects, $manageObjects, $teams, $stakeholders, $whiteList, $projectStakeholderGroup);
            $userView->sprints  = $this->getSprintView($account, $allSprints, $manageObjects, $teams, $stakeholders, $whiteList);
        }

        /* 更新访问权限表。 */
        $this->dao->replace(TABLE_USERVIEW)->data($userView)->exec();

        return $userView;
    }

    /**
     * 获取产品关联的项目集干系人。
     * Get program stakeholder.
     *
     * @param  array   $programProduct
     * @access private
     * @return array
     */
    private function getProgramStakeholder($programProduct): array
    {
        $stakeholderGroups = array();
        $stmt = $this->dao->select('objectID,user')->from(TABLE_STAKEHOLDER)
            ->where('objectType')->eq('program')
            ->andWhere('objectID')->in(array_keys($programProduct))
            ->query();

        while($programStakeholder = $stmt->fetch())
        {
            $productIDList = zget($programProduct, $programStakeholder->objectID, array());
            foreach($productIDList as $productID) $stakeholderGroups[$productID][$programStakeholder->user] = $programStakeholder->user;
        }

        $programOwners = $this->mao->select('id,PM')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('id')->in(array_keys($programProduct))
            ->fetchAll();

        foreach($programOwners as $programOwner)
        {
            $productIDList = zget($programProduct, $programOwner->id, array());
            foreach($productIDList as $productID) $stakeholderGroups[$productID][$programOwner->PM] = $programOwner->PM;
        }

        return $stakeholderGroups;
    }

    /**
     * 获取产品的所属团队和干系人。
     * Get product teams and stakeholders.
     *
     * @param  array     $allProducts
     * @access protected
     * @return array
     */
    protected function getProductMembers(array $allProducts): array
    {
        /* Get product and project relation. */
        $projectProducts = array();
        $stmt = $this->dao->select('t1.project, t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->in(array_keys($allProducts))
            ->andWhere('t2.deleted')->eq('0')
            ->query();

        while($projectProduct = $stmt->fetch()) $projectProducts[$projectProduct->project][$projectProduct->product] = $projectProduct->product;

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
        if($programProduct) $stakeholderGroups = array_merge($stakeholderGroups, $this->getProgramStakeholder($programProduct));

        /* Get linked projects teams. */
        $teamsGroup = array();
        $teamList   = $this->loadModel('project')->getTeamListByType('project');
        foreach($teamList as $team)
        {
            if(!isset($projectProducts[$team->root])) continue;
            $productIdList = zget($projectProducts, $team->root, array());
            foreach($productIdList as $productID) $teamsGroup[$productID][$team->account] = $team->account;
        }

        return array($teamsGroup, $stakeholderGroups);
    }

    /**
     * 组装最终的用户可访问权限。
     * Grant user view.
     *
     * @param  string $account
     * @param  array  $acls
     * @param  string $projects 此人管理的项目列表
     * @access public
     * @return object
     */
    public function grantUserView(string $account = '', array $acls = array(), string $projects = ''): object
    {
        if(empty($account)) $account = $this->session->user->account;
        if(empty($account)) return new stdclass();
        if(empty($acls)     && !empty($this->session->user->rights['acls']))     $acls     = $this->session->user->rights['acls'];
        if(empty($projects) && !empty($this->session->user->rights['projects'])) $projects = $this->session->user->rights['projects'];

        $userView = $this->computeUserView($account, true);

        /* Get opened projects, programs, products and set it to userview. */
        $openedProducts = array_keys($this->loadModel('product')->getListByAcl('open'));
        $openedPrograms = array_keys($this->loadModel('project')->getListByAclAndType('open', 'program'));
        $openedProjects = array_keys($this->project->getListByAclAndType('open', 'project'));

        $userView->products = rtrim($userView->products, ',') . ',' . join(',', $openedProducts);
        $userView->programs = rtrim($userView->programs, ',') . ',' . join(',', $openedPrograms);
        $userView->projects = rtrim($userView->projects, ',') . ',' . join(',', $openedProjects);

        /* 合并用户视图权限到用户访问权限。 */
        $userView = $this->mergeAclsToUserView($account, $userView, $acls, $projects);

        $userView->products = trim($userView->products, ',');
        $userView->programs = trim($userView->programs, ',');
        $userView->projects = trim($userView->projects, ',');
        $userView->sprints  = trim($userView->sprints, ',');

        return $userView;
    }

    /**
     * 合并用户视图权限到用户访问权限。
     * Merge acls to userView.
     *
     * @param  string  $account
     * @param  object  $userView
     * @param  array   $acls
     * @param  string  $projects
     * @access private
     * @return object
     */
    private function mergeAclsToUserView(string $account, object $userView, array $acls, string $projects): object
    {
        if(isset($_SESSION['user']->admin)) $isAdmin = $this->session->user->admin;
        if(!isset($isAdmin))                $isAdmin = strpos($this->app->company->admins, ",{$account},") !== false;

        /* 权限分组-视野维护的优先级最高，所以这里进行了替换操作。*/
        /* View management has the highest priority, so there is a substitution. */
        if(!empty($acls['programs']) && !$isAdmin) $userView->programs = implode(',', $acls['programs']);
        if(!empty($acls['products']) && !$isAdmin) $userView->products = implode(',', $acls['products']);
        if(!empty($acls['sprints'])  && !$isAdmin) $userView->sprints  = implode(',', $acls['sprints']);
        if(!empty($acls['projects']) && !$isAdmin)
        {
            /* If is project admin, set projectID to userview. */
            if($projects) $acls['projects'] = array_merge($acls['projects'], explode(',', $projects));
            $userView->projects = implode(',', $acls['projects']);
        }

        /* 可以看到项目，就能看到项目下公开的迭代。 */
        /* Set opened sprints and stages into userview. */
        $openedSprints = $this->loadModel('project')->getListByAclAndType('open', 'sprint,stage,kanban');
        $openedSprints = array_filter(array_map(function($sprint) use ($userView) { if(strpos(",{$userView->projects},", ",{$sprint->project},") !== false) return $sprint->id; }, $openedSprints));

        $userView->sprints = rtrim($userView->sprints, ',')  . ',' . join(',', $openedSprints);

        $canViewSprints = $this->dao->select('executions')->from(TABLE_PROJECTADMIN)->where('account')->eq($account)->fetch('executions');
        if($canViewSprints) $userView->sprints .= ',' . $canViewSprints;

        return $userView;
    }

    /**
     * 更新某个对象下的用户可访问视图。
     * Update user view by object type.
     *
     * @param  array  $objectIDList
     * @param  string $objectType
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function updateUserView(array $objectIDList, string $objectType, array $users = array()): bool
    {
        if($objectType == 'program') return $this->updateProgramView($objectIDList, $users);
        if($objectType == 'product') return $this->updateProductView($objectIDList, $users);
        if($objectType == 'project') return $this->updateProjectView($objectIDList, $users);
        if($objectType == 'sprint')  return $this->updateSprintView($objectIDList, $users);

        return false;
    }

    /**
     * 更新项目集下的用户访问视图。
     * Update program user view.
     *
     * @param  array   $programIDList
     * @param  array   $users
     * @access private
     * @return bool
     */
    private function updateProgramView(array $programIDList, array $users): bool
    {
        $programs = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($programIDList)->andWhere('acl')->ne('open')->fetchAll('id');
        if(empty($programs)) return false;

        $stakeholderGroup       = $this->loadModel('stakeholder')->getStakeholderGroup($programIDList); // Get self stakeholders.
        $parentStakeholderGroup = $this->stakeholder->getParentStakeholderGroup($programIDList);        // Get all parent program and subprogram relation.
        $parentPMGroup          = $this->loadModel('program')->getParentPM($programIDList);             // Get all parent program and subprogram relation.
        $programAdmins          = $this->loadModel('group')->getAdmins($programIDList, 'programs');     // Get programs's admins.

        $stmt            = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->eq('program')->andWhere('objectID')->in($programIDList)->query();
        $whiteListGroup  = array();
        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        $authedUsers = $users;
        /* 如果没传users参数，则获取项目集关联的所有人。*/
        if(empty($users)) $authedUsers += $this->getObjectsAuthedUsers($programs, 'program', $stakeholderGroup, array(), $whiteListGroup, $programAdmins, $parentStakeholderGroup, $parentPMGroup);

        $userViews = $this->dao->select("account,programs")->from(TABLE_USERVIEW)->where('account')->in($authedUsers)->fetchPairs('account', 'programs');

        /* Judge auth and update view. */
        foreach(array_filter($authedUsers) as $account)
        {
            $view       = isset($userViews[$account]) ? $userViews[$account] : '';
            $latestView = $view;
            foreach($programs as $program)
            {
                $latestView = $this->getLatestUserView($account, $view, $program, 'program', $stakeholderGroup, array(), $whiteListGroup, $programAdmins, $parentStakeholderGroup, $parentPMGroup);
            }
            if($view != $latestView)
            {
                if(!isset($userViews[$account]))
                {
                    $userView = new stdclass();
                    $userView->account  = $account;
                    $userView->programs = $latestView;
                    $userView->products = $userView->projects = $userView->sprints = '';
                    $this->dao->insert(TABLE_USERVIEW)->data($userView)->exec();
                }
                else
                {
                    $this->dao->update(TABLE_USERVIEW)->set('programs')->eq($latestView)->where('account')->eq($account)->exec();
                }
            }
        }

        return true;
    }

    /**
     * 更新项目下的用户访问视图。
     * Update project view.
     *
     * @param  array   $projectIDList
     * @param  array   $users
     * @access private
     * @return bool
     */
    private function updateProjectView(array $projectIDList, array $users): bool
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->andWhere('acl')->ne('open')->fetchAll('id');
        if(empty($projects)) return false;

        /* Get team group. */
        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('root')->in($projectIDList)->andWhere('root')->ne(0)->query();
        $teamsGroup = array();
        while($team = $stmt->fetch()) $teamsGroup[$team->root][$team->account] = $team->account;

        /* Get white list group. */
        $stmt            = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->eq('project')->andWhere('objectID')->in($projectIDList)->query();
        $whiteListGroup  = array();
        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        $stakeholderGroup       = $this->loadModel('stakeholder')->getStakeholderGroup($projectIDList); // Get self stakeholders.
        $parentStakeholderGroup = $this->stakeholder->getParentStakeholderGroup($projectIDList);        // Get all parent program and subprogram relation.
        $projectAdmins          = $this->loadModel('group')->getAdmins($projectIDList, 'projects');     // Get projects's admins.

        /* Get auth users. */
        $authedUsers = $users;
        /* 如果没传users参数，则获取项目关联的所有人。*/
        if(empty($users)) $authedUsers += $this->getObjectsAuthedUsers($projects, 'project', $stakeholderGroup, $teamsGroup, $whiteListGroup, $projectAdmins, $parentStakeholderGroup, array());

        /* Get all projects user view. */
        $userViews = $this->dao->select("account,projects")->from(TABLE_USERVIEW)->where('account')->in($authedUsers)->fetchPairs('account', 'projects');

        /* Judge auth and update view. */
        foreach(array_filter($authedUsers) as $account)
        {
            $view       = isset($userViews[$account]) ? $userViews[$account] : '';
            $latestView = $view;
            foreach($projects as $project)
            {
                $latestView = $this->getLatestUserView($account, $view, $project, 'project', $stakeholderGroup, $teamsGroup, $whiteListGroup, $projectAdmins, $parentStakeholderGroup, array());
            }
            if($view != $latestView)
            {
                if(!isset($userViews[$account]))
                {
                    $userView = new stdclass();
                    $userView->account  = $account;
                    $userView->projects = $latestView;
                    $userView->products = $userView->programs = $userView->sprints = '';
                    $this->dao->insert(TABLE_USERVIEW)->data($userView)->exec();
                }
                else
                {
                    $this->dao->update(TABLE_USERVIEW)->set('projects')->eq($latestView)->where('account')->eq($account)->exec();
                }
            }
        }

        return true;
    }

    /**
     * 更新产品下的用户访问视图。
     * Update product user view.
     *
     * @param  array   $productIDList
     * @param  array   $user
     * @access private
     * @return bool
     */
    private function updateProductView(array $productIDList, array $users): bool
    {
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIDList)->andWhere('acl')->ne('open')->fetchAll('id', false);
        if(empty($products)) return false;

        list($teamsGroup, $stakeholderGroup) = $this->getProductMembers($products);

        /* Get white list group. */
        $stmt            = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->eq('product')->andWhere('objectID')->in($productIDList)->query();
        $whiteListGroup  = array();
        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        $productAdmins = $this->loadModel('group')->getAdmins($productIDList, 'products'); // Get products' admins.

        /* Get auth users. */
        $authedUsers = $users;
        /* 如果没传users参数，则获取项目关联的所有人。*/
        if(empty($users)) $authedUsers += $this->getObjectsAuthedUsers($products, 'product', $stakeholderGroup, $teamsGroup, $whiteListGroup, $productAdmins, array(), array());

        /* Get all products user view. */
        $userViews = $this->dao->select("account,products")->from(TABLE_USERVIEW)->where('account')->in($authedUsers)->fetchPairs('account', 'products');

        /* Judge auth and update view. */
        foreach(array_filter($authedUsers) as $account)
        {
            $view       = isset($userViews[$account]) ? $userViews[$account] : '';
            $latestView = $view;
            foreach($products as $productID => $product)
            {
                $latestView = $this->getLatestUserView($account, $view, $product, 'product', $stakeholderGroup, $teamsGroup, $whiteListGroup, $productAdmins, array(), array());
            }
            if($view != $latestView)
            {
                if(!isset($userViews[$account]))
                {
                    $userView = new stdclass();
                    $userView->account  = $account;
                    $userView->products = $latestView;
                    $userView->projects = $userView->programs = $userView->sprints = '';
                    $this->dao->insert(TABLE_USERVIEW)->data($userView)->exec();
                }
                else
                {
                    $this->dao->update(TABLE_USERVIEW)->set('products')->eq($latestView)->where('account')->eq($account)->exec();
                }
            }
        }

        return true;
    }

    /**
     * 更新迭代下的用户访问视图。
     * Update sprint view.
     *
     * @param  array   $sprintIDList
     * @param  array   $users
     * @access private
     * @return bool
     */
    private function updateSprintView(array $sprintIDList, array $users): bool
    {
        $sprints = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($sprintIDList)->andWhere('acl')->ne('open')->fetchAll('id');
        if(empty($sprints)) return false;

        $projectIDList = array();
        foreach($sprints as $sprint) $projectIDList[$sprint->project] = $sprint->project;

        $stmt       = $this->dao->select('root,account')->from(TABLE_TEAM)->where('type')->in('project,execution')->andWhere('root')->in(array_merge($sprintIDList, $projectIDList))->andWhere('root')->ne(0)->query();
        $teamsGroup = array();
        while($team = $stmt->fetch()) $teamsGroup[$team->root][$team->account] = $team->account;

        $stmt            = $this->dao->select('objectID,account')->from(TABLE_ACL)->where('objectType')->eq('sprint')->andWhere('objectID')->in($sprintIDList)->query();
        $whiteListGroup  = array();
        while($whiteList = $stmt->fetch()) $whiteListGroup[$whiteList->objectID][$whiteList->account] = $whiteList->account;

        $stakeholderGroup = $this->loadModel('stakeholder')->getStakeholderGroup($projectIDList); // Get parent project stakeholders.
        $executionAdmins  = $this->loadModel('group')->getAdmins($sprintIDList, 'executions');    // Get executions' admins.

        $authedUsers = $users;
        if(empty($users)) $authedUsers += $this->getObjectsAuthedUsers($sprints, 'sprint', $stakeholderGroup, $teamsGroup, $whiteListGroup, $executionAdmins, array(), array());
        $userViews = $this->dao->select("account,sprints")->from(TABLE_USERVIEW)->where('account')->in($authedUsers)->fetchPairs('account', 'sprints'); // Get all sprints user view.
        foreach(array_filter($authedUsers) as $account)
        {
            $view       = isset($userViews[$account]) ? $userViews[$account] : '';
            $latestView = $view;
            foreach($sprints as $sprint)
            {
                $latestView = $this->getLatestUserView($account, $view, $sprint, 'sprint', $stakeholderGroup, $teamsGroup, $whiteListGroup, $executionAdmins, array(), array());
            }
            if($view != $latestView)
            {
                if(!isset($userViews[$account]))
                {
                    $userView = new stdclass();
                    $userView->account  = $account;
                    $userView->sprints  = $latestView;
                    $userView->projects = $userView->programs = $userView->products = '';
                    $this->dao->insert(TABLE_USERVIEW)->data($userView)->exec();
                }
                else
                {
                    $this->dao->update(TABLE_USERVIEW)->set('sprints')->eq($latestView)->where('account')->eq($account)->exec();
                }
            }
        }

        return true;
    }

    /**
     * 获取该对象的关联用户。
     * Get objects authed users.
     *
     * @param  array   $objects
     * @param  string  $objectType
     * @param  array   $stakeholderGroup
     * @param  array   $teamsGroup
     * @param  array   $whiteListGroup
     * @param  array   $adminsGroup
     * @param  array   $parentStakeholderGroup
     * @param  array   $parentPMGroup
     * @access private
     * @return array
     */
    private function getObjectsAuthedUsers(array $objects, string $objectType, array $stakeholderGroup, array $teamsGroup, array $whiteListGroup, array $adminsGroup, array $parentStakeholderGroup, array $parentPMGroup): array
    {
        $authedUsers = array();
        foreach($objects as $object)
        {
            $stakeholders = zget($stakeholderGroup, $object->id, array());
            $teams        = zget($teamsGroup,       $object->id, array());
            $whiteList    = zget($whiteListGroup,   $object->id, array());
            $admins       = zget($adminsGroup,       $object->id, array());
            if($objectType == 'sprint') $parentTeams = zget($teamsGroup, $object->project, array());
            if($objectType == 'program' && $object->acl == 'program')
            {
                $parents = explode(',', $object->path);
                foreach($parents as $parentID)
                {
                    $stakeholders += zget($parentStakeholderGroup, $parentID, array());
                    $stakeholders += zget($parentPMGroup,          $parentID, array());
                }
            }
            else if($objectType == 'project' && $object->acl == 'program')
            {
                $stakeholders += zget($parentStakeholderGroup, $object->id, array());
            }

            if($objectType == 'program') $authedUsers += $this->getProgramAuthedUsers($object, $stakeholders, $whiteList, $admins);
            if($objectType == 'project') $authedUsers += $this->getProjectAuthedUsers($object, $stakeholders, $teams, $whiteList, $admins);
            if($objectType == 'product') $authedUsers += $this->getProductViewListUsers($object, $teams, $stakeholders, $whiteList, $admins);
            if($objectType == 'sprint')  $authedUsers += $this->getProjectAuthedUsers($object, $stakeholders, array_merge($teams, $parentTeams), $whiteList, $admins);

            /* If you have parent stage view permissions, you have child stage permissions. */
            if($objectType == 'sprint' && $object->type == 'stage' && $object->grade == 2)
            {
                $parentStageAuthedUsers = $this->getParentStageAuthedUsers($object->parent);
                $authedUsers = array_merge($authedUsers, $parentStageAuthedUsers);
            }
        }

        return $authedUsers;
    }

    /**
     * 获取用户对于该对象的最新访问权限。
     * Get latest user view.
     *
     * @param  string  $account
     * @param  string  $view
     * @param  object  $object
     * @param  string  $objectType
     * @param  array   $stakeholderGroup
     * @param  array   $teamsGroup
     * @param  array   $whiteListGroup
     * @param  array   $adminsGroup
     * @param  array   $parentStakeholderGroup
     * @param  array   $parentPMGroup
     * @access private
     * @return string
     */
    private function getLatestUserView(string $account, string $view, object $object, string $objectType, array $stakeholderGroup, array $teamsGroup, array $whiteListGroup, array $adminsGroup, array $parentStakeholderGroup, array $parentPMGroup): string
    {
        $stakeholders = zget($stakeholderGroup, $object->id, array());
        $teams        = zget($teamsGroup,       $object->id, array());
        $whiteList    = zget($whiteListGroup,   $object->id, array());
        $admins       = zget($adminsGroup,      $object->id, array());
        if(!empty($object->acl) && $object->acl == 'program')
        {
            $stakeholders += zget($parentStakeholderGroup, $object->id, array());
            if($objectType == 'program')
            {
                $stakeholders += zget($parentPMGroup, $object->id, array());
            }
        }

        $hasPriv = false;
        if($objectType == 'program') $hasPriv = $this->checkProgramPriv($object, $account, $stakeholders, $whiteList, $admins);
        if($objectType == 'project') $hasPriv = $this->checkProjectPriv($object, $account, $stakeholders, $teams, $whiteList, $admins);
        if($objectType == 'product') $hasPriv = $this->checkProductPriv($object, $account, $teams, $stakeholders, $whiteList, $admins);
        if($objectType == 'sprint')  $hasPriv = $this->checkProjectPriv($object, $account, $stakeholders, $teams, $whiteList, $admins);

        if($hasPriv  && strpos(",{$view},", ",{$object->id},") === false)  $view .= ",{$object->id}";
        if(!$hasPriv && strpos(",{$view},", ",{$object->id},") !== false)  $view  = trim(str_replace(",{$object->id},", ',', ",{$view},"), ',');

        return $view;
    }

    /**
     * 检查用户是否有此项目集的查看权限。
     * Check program priv.
     *
     * @param  object  $program
     * @param  string  $account
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @param  array   $admins
     * @access private
     * @return bool
     */
    private function checkProgramPriv(object $program, string $account, array $stakeholders = array(), array $whiteList = array(), $admins = array()): bool
    {
        /* 当前用户为管理员则判断为有权限。 */
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;

        /* 当前用户为项目集的PM或创建者则判断为有权限。 */
        if($program->PM == $account || $program->openedBy == $account) return true;

        /* 如果是项目集内公开，则检查所有父项目集的权限。 */
        if($program->parent != 0 && $program->acl == 'program')
        {
            $path    = str_replace(",{$program->id},", ',', "{$program->path}");
            $parents = $this->mao->select('openedBy,PM')->from(TABLE_PROGRAM)->where('id')->in($path)->fetchAll();
            foreach($parents as $parent)
            {
                /* 当前用户是其中一个父项目集的PM或创建者则判断为有权限。 */
                if($parent->PM == $account || $parent->openedBy == $account) return true;
            }
        }

        if($program->acl == 'open')        return true; // 如果项目集为公开则判断为有权限。
        if(isset($stakeholders[$account])) return true; // 如果该用户是项目集的干系人则判断为有权限。
        if(isset($whiteList[$account]))    return true; // 如果该用户是项目集的白名单成员则判断为有权限。
        if(isset($admins[$account]))       return true; // 如果该用户是项目集的管理人员则判断为有权限。

        return false;
    }

    /**
     * 检查用户是否有此项目或者迭代的查看权限。
     * Check project priv.
     *
     * @param  object  $project
     * @param  string  $account
     * @param  array   $stakeholders
     * @param  array   $teams
     * @param  array   $whiteList
     * @param  array   $admins
     * @access private
     * @return bool
     */
    private function checkProjectPriv(object $project, string $account, array $stakeholders, array $teams, array $whiteList, array $admins = array()): bool
    {
        /* 当前用户为管理员则判断为有权限。 */
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true;

        /* 当前用户为项目集的PO、QD、RD、PM则判断为有权限。 */
        if($project->PO == $account || $project->QD == $account || $project->RD == $account || $project->PM == $account) return true;

        if($project->acl == 'open')        return true; // 如果项目为公开则判断为有权限。
        if(isset($stakeholders[$account])) return true; // 如果该用户是项目的干系人则判断为有权限。
        if(isset($teams[$account]))        return true; // 如果该用户是项目的团队成员则判断为有权限。
        if(isset($whiteList[$account]))    return true; // 如果该用户是项目的白名单成员则判断为有权限。
        if(isset($admins[$account]))       return true; // 如果该用户是项目的管理人员则判断为有权限。

        /* 如果是项目类型并且项目集内公开，则检查所有父项目集的权限。 */
        if($project->type == 'project' && $project->parent != 0 && $project->acl == 'program')
        {
            $path     = str_replace(",{$project->id},", ',', "{$project->path}");
            $programs = $this->mao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->in($path)->fetchAll();
            foreach($programs as $program)
            {
                /* 当前用户是其中一个父项目集的PM或创建者则判断为有权限。 */
                if($program->PM == $account || $program->openedBy == $account) return true;
            }
        }

        /* 如果是迭代并且是私有的，则检查所属项目的权限。 */
        if(($project->type == 'sprint' || $project->type == 'stage' || $project->type == 'kanban') && $project->acl == 'private')
        {
            $project = $this->mao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->eq($project->project)->fetch();
            if(empty($project)) return false;

            /* 当前用户是所属项目的PM或创建者则判断为有权限。 */
            if($project->PM == $account || $project->openedBy == $account) return true;
        }

        return false;
    }

    /**
     * 检查用户是否有此产品的查看权限。
     * Check product priv.
     *
     * @param  object  $product
     * @param  string  $account
     * @param  array   $teams
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @param  array   $admins
     * @access private
     * @return bool
     */
    private function checkProductPriv(object $product, string $account, array $teams, array $stakeholders, array $whiteList, array $admins = array()): bool
    {
        if(strpos($this->app->company->admins, ',' . $account . ',') !== false) return true; // 当前用户为管理员则判断为有权限。
        if(strpos(",{$product->reviewer},",    ',' . $account . ',') !== false) return true; // 当前用户为产品的审批人则判断为有权限。
        if(strpos(",{$product->PMT},",         ',' . $account . ',') !== false) return true; // 当前用户为产品的PMT则判断为有权限。

        /* 当前产品为公开的则判断为有权限。 */
        if($product->acl == 'open') return true;

        /* 当前用户为产品的PO、QD、RD、创建者、反馈负责人、工单负责人则判断为有权限。 */
        if($product->PO == $account || $product->QD == $account || $product->RD == $account || $product->createdBy == $account) return true;
        if(isset($product->feedback) && $product->feedback == $account)                                                         return true;
        if(isset($product->ticket)   && $product->ticket == $account)                                                           return true;

        if(isset($stakeholders[$account])) return true; // 如果该用户是产品的干系人则判断为有权限。
        if(isset($teams[$account]))        return true; // 如果该用户是产品的团队成员则判断为有权限。
        if(isset($whiteList[$account]))    return true; // 如果该用户是产品的白名单成员则判断为有权限。
        if(isset($admins[$account]))       return true; // 如果该用户是产品的管理人员则判断为有权限。

        return false;
    }

    /**
     * Get program authed users.
     *
     * @param  object  $program
     * @param  array   $stakeholders
     * @param  array   $whiteList
     * @param  array   $admins
     * @access private
     * @return array
     */
    private function getProgramAuthedUsers(object $program, array $stakeholders, array $whiteList, array $admins): array
    {
        $users = array();
        $users[$program->openedBy] = $program->openedBy;
        $users[$program->PM]       = $program->PM;

        $users += $stakeholders ? $stakeholders : array();
        $users += $whiteList    ? $whiteList    : array();
        $users += $admins       ? $admins       : array();

        $admins = explode(',', trim($this->app->company->admins, ','));
        foreach($admins as $admin) $users[$admin] = $admin;

        /* 如果是项目集内公开，则所有父项目集的PM和创建者都是关系人。 */
        if($program->parent != 0 && $program->acl == 'program')
        {
            $path    = str_replace(",{$program->id},", ',', "{$program->path}");
            $parents = $this->mao->select('openedBy,PM')->from(TABLE_PROGRAM)->where('id')->in($path)->fetchAll();
            foreach($parents as $parent)
            {
                $users[$parent->openedBy] = $parent->openedBy;
                $users[$parent->PM]       = $parent->PM;
            }
        }

        return $users;
    }

    /**
     * 获取和项目或者迭代相关联的用户。
     * Get project authed users.
     *
     * @param  object  $project
     * @param  array   $stakeholders
     * @param  array   $teams
     * @param  array   $whiteList
     * @param  array   $admins
     * @access private
     * @return array
     */
    private function getProjectAuthedUsers(object $project, array $stakeholders, array $teams, array $whiteList, array $admins = array()): array
    {
        $users = array();
        $users[$project->openedBy] = $project->openedBy;
        $users[$project->PM]       = $project->PM;
        $users[$project->PO]       = $project->PO;
        $users[$project->QD]       = $project->QD;
        $users[$project->RD]       = $project->RD;

        $users += $stakeholders ? $stakeholders : array();
        $users += $teams        ? $teams        : array();
        $users += $whiteList    ? $whiteList    : array();
        $users += $admins       ? $admins       : array();

        $admins = explode(',', trim($this->app->company->admins, ','));
        foreach($admins as $admin) $users[$admin] = $admin;

        /* 如果是项目类型并且项目集内公开，则所有父项目集的PM和创建者都是关系人。 */
        if($project->type == 'project' && $project->parent != 0 && $project->acl == 'program')
        {
            $path     = str_replace(",{$project->id},", ',', "{$project->path}");
            $programs = $this->mao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->in($path)->fetchAll();
            foreach($programs as $program)
            {
                $users[$program->openedBy] = $program->openedBy;
                $users[$program->PM]       = $program->PM;
            }
        }

        /* 如果是迭代类型并且是私有的，则所属项目的PM和创建者是关系人。 */
        if(($project->type == 'sprint' || $project->type == 'stage' || $project->type == 'kanban') && $project->acl == 'private')
        {
            $parent = $this->mao->select('openedBy,PM')->from(TABLE_PROJECT)->where('id')->eq($project->project)->fetch();
            if($parent)
            {
                $users[$parent->openedBy] = $parent->openedBy;
                $users[$parent->PM]       = $parent->PM;
            }
        }

        return $users;
    }

    /**
     * 获取和产品相关联的用户。
     * Get product view list users.
     *
     * @param  object $product
     * @param  array  $teams
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProductViewListUsers(object $product, array $teams = array(), array $stakeholders = array(), array $whiteList = array(), array $admins = array()): array
    {
        $users = array();
        $users[$product->PO]        = $product->PO;
        $users[$product->QD]        = $product->QD;
        $users[$product->RD]        = $product->RD;
        $users[$product->createdBy] = $product->createdBy;
        if(isset($product->feedback)) $users[$product->feedback] = $product->feedback;
        if(isset($product->ticket))   $users[$product->ticket] = $product->ticket;

        foreach(explode(',', trim($this->app->company->admins, ','))    as $admin)   $users[$admin]   = $admin;
        foreach(explode(',', trim(zget($product, 'reviewer', ''), ',')) as $account) $users[$account] = $account;
        foreach(explode(',', trim(zget($product, 'PMT', ''), ','))      as $account) $users[$account] = $account;

        if(!$teams || !$stakeholders)
        {
            list($productTeams, $productStakeholders) = $this->getProductMembers(array($product->id => $product));
            if(!$teams)        $teams        = isset($productTeams[$product->id])        ? $productTeams[$product->id]        : array();
            if(!$stakeholders) $stakeholders = isset($productStakeholders[$product->id]) ? $productStakeholders[$product->id] : array();
        }

        if(!$whiteList) $whiteList = $this->dao->select('account')->from(TABLE_ACL)->where('objectType')->eq('product')->andWhere('objectID')->eq($product->id)->fetchPairs();
        if(!$admins)    $admins    = $this->dao->select('account')->from(TABLE_PROJECTADMIN)->where("FIND_IN_SET({$product->id}, products)")->orWhere('products')->eq('all')->fetchPairs();

        $users += $teams        ? $teams        : array();
        $users += $stakeholders ? $stakeholders : array();
        $users += $whiteList    ? $whiteList    : array();
        $users += $admins       ? $admins       : array();

        return $users;
    }

    /**
     * 获取项目、执行等对象的团队成员。
     * Get team members in object.
     *
     * @param  string|array|int $objects
     * @param  string           $type            project|execution
     * @param  string           $params
     * @param  string|array     $usersToAppended
     * @access public
     * @return array
     */
    public function getTeamMemberPairs(string|array|int $objects, string $type = 'project', string $params = '', string|array $usersToAppended = ''): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getTeamMembersPairs();

        if(empty($objects) && empty($usersToAppended)) return array();

        $keyField = strpos($params, 'useid') !== false ? 'id' : 'account';
        $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.type')->eq($type)
            ->andWhere('t1.root')->in($objects)
            ->beginIF($params == 'nodeleted' || empty($this->config->user->showDeleted))->andWhere('t2.deleted')->eq('0')->fi()
            ->fetchAll($keyField);

        if($usersToAppended) $users += $this->dao->select("id, account, realname")->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll($keyField);

        if(!$users) return array();

        /* 拼装成用户名为键，真实姓名为值的数组。 */
        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $users[$account] = $firstLetter . ($user->realname ? $user->realname : $user->account);
        }

        /* Put the current user first. */
        return $this->setCurrentUserFirst($users);
    }

    /**
     * 判断一个按钮是否可以点击。
     * Check if a button is clickable.
     *
     * @param  object $user
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable(object $user, string $action): bool
    {
        global $config, $app;
        $action = strtolower($action);

        if($action == 'unbind' && empty($user->ranzhi)) return false;
        if($action == 'unlock' && (time() - strtotime($user->locked)) >= $config->user->lockMinutes * 60) return false;
        if($action == 'delete' && strpos($app->company->admins, ",{$user->account},") !== false) return false;

        return true;
    }

    /**
     * 保存一个编辑器模板。
     * Save a editor template.
     *
     * @param  object $template
     * @access public
     * @return bool
     */
    public function saveUserTemplate(object $template): bool
    {
        $this->dao->insert(TABLE_USERTPL)->data($template)
            ->batchCheck('title, content', 'notempty')
            ->check('title', 'unique', "`type`='{$template->type}' AND account='{$template->account}'")
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $this->loadModel('score')->create('bug', 'saveTplModal', $this->dao->lastInsertID());

        return !dao::isError();
    }

    /**
     * Save old user template.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function saveOldUserTemplate(string $type)
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
     * 获取当前用户可以查看的模板列表。
     * Get user templates.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getUserTemplates(string $type): array
    {
        return $this->dao->select('id, account, title, content, public')
            ->from(TABLE_USERTPL)
            ->where('type')->eq($type)
            ->andwhere('account', true)->eq($this->app->user->account)
            ->orWhere('public')->eq('1')
            ->markRight(1)
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * 为 GitLab API 获取用户列表。
     * Get user list for GitLab API.
     *
     * @param  array  $accountList
     * @access public
     * @return array
     */
    public function getListForGitLabAPI(array $accountList): array
    {
        /* 第二个参数设为空，确保返回的是一个索引数组。*/
        /* Set the second param to empty, to make sure the return is an indexed array. */
        $users = $this->getListByAccounts($accountList, '');
        foreach($users as $user)
        {
            if($user->avatar)
            {
                $user->avatar = common::getSysURL() . $user->avatar;
                continue;
            }

            $user->avatar = "https://www.gravatar.com/avatar/" . md5($user->account) . "?d=identicon&s=80";
        }
        return $users;
    }

    /**
     * 获取可以创建需求的用户。
     * Get users who have authority to create stories.
     *
     * @access public
     * @return array
     */
    public function getCanCreateStoryUsers(): array
    {
        $users      = $this->getPairs('noclosed|nodeleted');
        $groupUsers = $this->dao->select('DISTINCT account')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUPPRIV)->alias('t2')->on('t1.group = t2.group')
            ->where('t2.module')->eq('story')
            ->andWhere('t2.method')->in('create,batchCreate')
            ->fetchPairs('account');

        foreach($users as $account => $realname)
        {
            if($realname && (isset($groupUsers[$account]) || strpos($this->app->company->admins, ",{$account},") !== false)) continue;

            unset($users[$account]);
        }

        return $users;
    }

    /**
     * 把第一个超级管理员设置为当前用户。
     * Set the first super admin as current user.
     *
     * @access public
     * @return bool
     */
    public function su(): bool
    {
        $company = $this->dao->select('admins')->from(TABLE_COMPANY)->fetch();
        $admins  = explode(',', trim($company->admins, ','));
        if(empty($admins[0])) helper::end('No admin users.');

        $this->app->user = $this->getById($admins[0]);

        return true;
    }

    /**
     * 把当前用户排到最前面。
     * Put the current user first.
     *
     * @param  array  $users
     * @access public
     * @return array
     */
    public function setCurrentUserFirst(array $users): array
    {
        $account = $this->app->user->account;
        if(!$users || !$account || !isset($users[$account])) return $users;

        return array($account => $users[$account]) + $users;
    }
}
