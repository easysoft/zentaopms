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
     * @access private
     * @return bool
     */
    private function checkBeforeCreateOrEdit(object $user, bool $canNoPassword = false): bool
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
     * @access private
     * @return bool
     */
    private function checkBeforeBatchCreate(array $users, string $verifyPassword): bool
    {
        if(!$users) return true;

        $accounts = array_map(function($user){return $user->account;}, $users);
        $accounts = $this->dao->select('account')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs();

        foreach($users as $key => $user)
        {
            if(empty($user->account))
            {
                unset($users[$key]);
                continue;
            }

            if(strtolower($user->account) == 'guest') dao::$errors["account[{$key}]"][] = $this->lang->user->error->reserved;
            if(isset($accounts[$user->account])) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->unique, $this->lang->user->account, $user->account);
            if(!validater::checkAccount($user->account)) dao::$errors["account[{$key}]"][] = sprintf($this->lang->error->account, $this->lang->user->account);
            if(!validater::checkReg($user->password, '|(.){6,}|')) dao::$errors["password[{$key}]"][] = $this->lang->user->error->password;
            if($user->email and !validater::checkEmail($user->email)) dao::$errors["email[{$key}]"][] = sprintf($this->lang->error->email, $this->lang->user->email);
            if($user->phone and !validater::checkPhone($user->phone)) dao::$errors["phone[{$key}]"][] = sprintf($this->lang->error->phone, $this->lang->user->phone);
            if($user->mobile and !validater::checkMobile($user->mobile)) dao::$errors["mobile[{$key}]"][] = sprintf($this->lang->error->mobile, $this->lang->user->mobile);

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
     * @access private
     * @return bool
     */
    private function checkBeforeBatchUpdate(array $users, string $verifyPassword): bool
    {
        if(!$users) return true;

        foreach($users as $key => $user)
        {
            if($user->email and !validater::checkEmail($user->email)) dao::$errors["email[{$key}]"][] = sprintf($this->lang->error->email, $this->lang->user->email);
            if($user->phone and !validater::checkPhone($user->phone)) dao::$errors["phone[{$key}]"][] = sprintf($this->lang->error->phone, $this->lang->user->phone);
            if($user->mobile and !validater::checkMobile($user->mobile)) dao::$errors["mobile[{$key}]"][] = sprintf($this->lang->error->mobile, $this->lang->user->mobile);
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
     * @access private
     * @return bool
     */
    private function checkPassword(object $user, bool $canNoPassword = false): bool
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
            foreach(explode(',', $this->config->safe->weak) as $weak) $weaks[$weak] = md5(trim($weak));
            if(isset($weaks[substr($user->password1, 0, 32)])) dao::$errors['password1'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
        }

        return !dao::isError();
    }

    /**
     * 检查当前用户密码是否正确。
     * Check if the current user password is correct.
     *
     * @param  string $verifyPassword
     * @access private
     * @return bool
     */
    private function checkVerifyPassword(string $verifyPassword): bool
    {
        if(empty($verifyPassword) || $verifyPassword != md5($this->app->user->password . $this->session->rand)) dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;

        return !dao::isError();
    }

    /**
     * 根据用户 id 列表获取用户。
     * Get users by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByIdList(array $idList): array
    {
        if(!$idList) return array();

        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($idList)
            ->orderBy('id')
            ->fetchAll('id');
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
            ->fetchAll();
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
    public function getListByAccounts(array $accounts = array(), string $keyField = 'id'): array
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
        if($usersToAppended) $users += $this->fetchExtraUsers($params, $usersToAppended, $users, $fields, $keyField);
        $users = $this->processDisplayValue($users, $params);
        $users = $this->processAccountSort($users);

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
     * @access private
     * @return void
     */
    private function processMoreLink(string $params, string|array $usersToAppended, int $maxCount, int $userCount): void
    {
        unset($this->config->user->moreLink);
        if($maxCount && $maxCount == $userCount)
        {
            if(is_array($usersToAppended)) $usersToAppended = join(',', $usersToAppended);
            $moreLinkParams = "params={$params}&usersToAppended={$usersToAppended}";

            $moreLink = helper::createLink('user', 'ajaxGetMore');
            $this->config->user->moreLink = $moreLink . (strpos($moreLink, '?') === false ? '?' : '&') . "params=" . base64_encode($moreLinkParams);
        }
    }

    /**
     * 获取额外的用户。
     * Get extra users.
     *
     * @param  string       $params
     * @param  string|array $usersToAppended
     * @param  array        $users
     * @param  string       $fields
     * @param  string       $keyField
     * @access private
     * @return array
     */
    private function fetchExtraUsers(string $params, string|array $usersToAppended, array $users, string $fields, string $keyField): array
    {
        if($usersToAppended) return $users;

        return $this->dao->select($fields)->from(TABLE_USER)
            ->where('account')->in($usersToAppended)
            ->beginIF(strpos($params, 'nodeleted') !== false)->andWhere('deleted')->eq('0')->fi()
            ->fetchAll($keyField);
    }

    /**
     * 处理用户的显示名称。
     * Process the display value of users.
     *
     * @param  array  $users
     * @param  string $params
     * @access private
     * @return array
     */
    private function processDisplayValue(array $users, string $params): array
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
     * @return bool|int
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
        $this->computeUserView($user->account);
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
        $this->checkBeforeBatchCreate($users, $verifyPassword);
        if(dao::isError()) return false;

        $this->loadModel('action');

        $this->dao->begin();

        $userIdList = array();
        foreach($users as $user)
        {
            if(empty($user->account)) continue;

            $user->password = md5($user->password);

            if($user->type == 'outside' && $user->new) $user->company = $this->createCompany($user->newCompany);

            $this->dao->insert(TABLE_USER)->data($user, 'new,newCompany,group')->exec();
            if(dao::isError()) return $this->rollback();

            $userID = $this->dao->lastInsertID();

            /* 创建用户组，更新用户视图并记录日志。*/
            /* Create user group, update user view and save log. */
            $groups = array_filter($user->group);
            if($groups) $this->createUserGroup($groups, $user->account);
            $this->computeUserView($user->account);
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
        $this->checkBeforeCreateOrEdit($user, true);

        $this->dao->begin();

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
        $oldUser = $this->getById($user->id, 'id');
        $this->checkAccountChange($oldUser->account, $user->account);
        $this->checkGroupChange($user);
        $this->loadModel('score')->create('user', 'editProfile');
        $changes = common::createChanges($user, $oldUser);
        if($changes)
        {
            $actionID = $this->action->create('user', $user->id, 'edited');
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
            $this->app->user->role     = $user->role;
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
        if($oldAccount == $newAccount) return true;

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

            $this->app->user->account = $newAccount;
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
        $oldGroups = $this->dao->select('`group`')->from(TABLE_USERGROUP)->where('account')->eq($user->account)->fetchPairs();
        $newGroups = array_unique(array_filter($user->group));

        sort($oldGroups);
        sort($newGroups);

        if(join(',', $oldGroups) == join(',', $newGroups)) return true;

        /* 如果权限组发生变化，则删除原有的权限组，重新创建并更新用户视图。*/
        /* If the group changed, delete the old group, create new group and update user view. */
        $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($user->account)->exec();
        if($newGroups) $this->createUserGroup($newGroups, $user->account);
        $this->computeUserView($user->account, true);

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
        $this->checkBeforeBatchUpdate($users, $verifyPassword);
        if(dao::isError()) return false;

        $this->loadModel('action');

        $accounts = array_map(function($user){return $user->account;}, $users);
        $oldUsers = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($accounts)->fetchAll('id');

        $this->dao->begin();

        foreach($users as $id => $user)
        {
            $this->dao->update(TABLE_USER)->data($user)->where('id')->eq($id)->exec();
            if(dao::isError()) return $this->rollback();

            /* 更新用户组和用户视图并记录日志。*/
            /* Update user group and user view, and save log and score. */
            $oldUser = $oldUsers[$id];
            $changes = common::createChanges($user, $oldUser);
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
            dao::$errors[] = sprintf($this->lang->user->error->notExists, $user->account);
            return false;
        }

        $this->checkPassword($user);

        $this->dao->update(TABLE_USER)->set('password')->eq($user->password)->where('account')->eq($user->account)->exec();

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
            if($this->app->isServing()) $this->dao->update(TABLE_USER)->set('visits = visits + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('account')->eq($account)->exec();

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
        if(!$this->app->upgrading)
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

        /* Set basic priv when no any priv. */
        $rights['index']['index'] = 1;
        $rights['my']['index']    = 1;

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
        if(!$user) return false;

        $this->cleanLocked($user->account);

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
     * @return void
     */
    public function keepLogin(object $user): void
    {
        helper::setcookie('keepLogin', 'on');
        helper::setcookie('za', $user->account);
        helper::setcookie('zp', sha1($user->account . $user->password . $this->server->request_time));
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

            if($visionCount > 1) $groups[$id] = $visionList[$group->vision] . ' / ' . $group->name;
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
                if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += (float)$task->left;
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
            $this->dao->update(TABLE_USER)->set('fails')->eq($fails)->set('locked = NULL')->where('account')->eq($account)->exec();
        }
        else
        {
            $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked')->eq(date('Y-m-d H:i:s'))->where('account')->eq($account)->exec();
        }

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
        if(empty($user) or is_null($user->locked)) return false;

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
        $this->dao->update(TABLE_USER)->set('fails')->eq(0)->set('locked = null')->where('account')->eq($account)->exec();

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
     * Create a contact list.
     *
     * @access public
     * @return bool
     */
    public function createContactList(): bool
    {
        $data = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->setDefault('public', 0)
            ->join('userList', ',')
            ->get();

        $this->dao->insert(TABLE_USERCONTACT)->data($data)
            ->batchCheck('listName,userList', 'notempty')
            ->check('listName', 'unique', "account = '{$data->account}'")
            ->autoCheck()
            ->exec();
        return !dao::isError();
    }

    /**
     * Update a contact list.
     *
     * @param  int    $listID
     * @access public
     * @return bool
     */
    public function updateContactList($listID): bool
    {
        $data = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->setDefault('public', 0)
            ->join('userList', ',')
            ->get();

        $this->dao->update(TABLE_USERCONTACT)->data($data)
            ->batchCheck('listName,userList', 'notempty')
            ->check('listName', 'unique', "id != '$listID' AND account = '{$data->account}'")
            ->autoCheck()
            ->where('id')->eq($listID)
            ->exec();
        return !dao::isError();
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
            $allProducts = $allPrograms = $allProjects = $allSprints = $teams = $stakeholders = $productWhiteList = $whiteList = null;
            if($allProducts === null) $allProducts = $this->dao->select('id,PO,QD,RD,createdBy,acl,whitelist,program,createdBy,reviewer,PMT')->from(TABLE_PRODUCT)->where('acl')->ne('open')->fetchAll('id');
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
                $stmt         = $this->dao->select('objectID,user')->from(TABLE_STAKEHOLDER)->query();
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
    public function updateUserView(int|array $objectIdList, string $objectType, array $users = array())
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
     * @param  array  $users
     * @access public
     * @return void
     */
    public function updateProgramView(array $programIdList, array $users)
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
    public function updateProjectView(array $projectIdList, array $users)
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
    public function updateProductView(array $productIdList, array $users)
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
    public function updateSprintView(array $sprintIdList, array $users)
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
    public function checkProgramPriv(object $program, string $account, array $stakeholders = array(), array $whiteList = array(), $admins = array()): bool
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
    public function checkProjectPriv(object $project, string $account, array $stakeholders, array $teams, array $whiteList, array $admins = array()): bool
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
    public function checkProductPriv(object $product, string $account, string $groups, array $teams, array $stakeholders, array $whiteList, array $admins = array()): bool
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
    public function getProjectAuthedUsers(object $project, array $stakeholders, array $teams, array $whiteList, array $admins = array()): array
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
            if($parent)
            {
                $users[$parent->openedBy] = $parent->openedBy;
                $users[$parent->PM]       = $parent->PM;
            }
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
    public function getProgramAuthedUsers(object $program, array $stakeholders, array $whiteList, array $admins): array
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin) $users[$admin] = $admin;

        $users[$program->openedBy] = $program->openedBy;
        $users[$program->PM]       = $program->PM;

        $users += $stakeholders ? $stakeholders : array();
        $users += $whiteList ? $whiteList : array();
        $users += $admins ? $admins : array();

        return array_filter($users);
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
    public function getSprintAuthedUsers(object $sprint, array $stakeholders, array $teams, array $whiteList, array $admins): array
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
    public function getProductViewListUsers(object $product, array|null $teams = null, array|null $stakeholders = null, array|null $whiteList = null, array|null $admins = null): array
    {
        $users = array();

        foreach(explode(',', trim($this->app->company->admins, ',')) as $admin)      $users[$admin]   = $admin;
        foreach(explode(',', trim(zget($product, 'reviewer', ''), ',')) as $account) $users[$account] = $account;
        foreach(explode(',', trim(zget($product, 'PMT', ''), ',')) as $account)      $users[$account] = $account;

        $users[$product->PO]        = $product->PO;
        $users[$product->QD]        = $product->QD;
        $users[$product->RD]        = $product->RD;
        $users[$product->createdBy] = $product->createdBy;
        if(isset($product->feedback)) $users[$product->feedback] = $product->feedback;

        if($teams === null and $stakeholders === null)
        {
            list($productTeams, $productStakeholders) = $this->getProductMembers(array($product->id => $product));
            $teams        = isset($productTeams[$product->id])        ? $productTeams[$product->id]        : array();
            $stakeholders = isset($productStakeholders[$product->id]) ? $productStakeholders[$product->id] : array();
        }

        if($whiteList === null)
        {
            $whiteList = $this->dao->select('account')->from(TABLE_ACL)
                ->where('objectType')->eq('product')
                ->andWhere('objectID')->eq($product->id)
                ->fetchPairs();
        }

        if($admins === null)
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
     * 获取项目、执行等对象的团队成员。
     * Get team members in object.
     *
     * @param  string|array|int $objectIds
     * @param  string           $type            project|execution
     * @param  string           $params
     * @param  string|array     $usersToAppended
     * @access public
     * @return array
     */
    public function getTeamMemberPairs(string|array|int $objectIds, string $type = 'project', string $params = '', string|array $usersToAppended = ''): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getTeamMembersPairs();

        if(empty($objectIds) and empty($usersToAppended)) return array();

        $keyField = strpos($params, 'useid') !== false ? 'id' : 'account';
        $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.type')->eq($type)
            ->andWhere('t1.root')->in($objectIds)
            ->beginIF($params == 'nodeleted' or empty($this->config->user->showDeleted))
            ->andWhere('t2.deleted')->eq('0')
            ->fi()
            ->fetchAll($keyField);

        if($usersToAppended) $users += $this->dao->select("id, account, realname")->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll($keyField);

        if(!$users) return array();

        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $users[$account] =  $firstLetter . ($user->realname ? $user->realname : $user->account);
        }

        /* Put the current user first. */
        return $this->processAccountSort($users);
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
        global $config, $app;
        $action = strtolower($action);

        if($action == 'unbind' && empty($user->ranzhi)) return false;
        if($action == 'unlock' && (strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) >= $config->user->lockMinutes * 60) return false;
        if($action == 'delete' && strpos($app->company->admins, ",{$user->account},") !== false) return false;

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
        $personalData['createdRequirements'] = $this->dao->select($t1Count)->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->andWhere('t1.type')->eq('requirement')->fetch('count');
        $personalData['createdStories']      = $this->dao->select($t1Count)->from(TABLE_STORY)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->andWhere('t1.type')->eq('story')->fetch('count');
        $personalData['createdBugs']         = $this->dao->select($t1Count)->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.openedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        $personalData['resolvedBugs']        = $this->dao->select($t1Count)->from(TABLE_BUG)->alias('t1')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        $personalData['createdCases']        = $this->dao->select($count)->from(TABLE_CASE)->where('openedBy')->eq($account)->andWhere('deleted')->eq('0')->fetch('count');

        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $personalData['createdRisks']   = $this->dao->select($t1Count)->from(TABLE_RISK)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.createdBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['resolvedRisks']  = $this->dao->select($t1Count)->from(TABLE_RISK)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['createdIssues']  = $this->dao->select($t1Count)->from(TABLE_ISSUE)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.createdBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
            $personalData['resolvedIssues'] = $this->dao->select($t1Count)->from(TABLE_ISSUE)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->where('t1.resolvedBy')->eq($account)->andWhere('t1.deleted')->eq('0')->andWhere('t2.deleted')->eq('0')->fetch('count');
        }
        $personalData['createdDocs']   = $this->dao->select($count)->from(TABLE_DOC)->where('addedBy')->eq($account)->andWhere('lib')->ne('')->andWhere('deleted')->eq('0')->andWhere('vision')->eq($this->config->vision)->fetch('count');
        $personalData['finishedTasks'] = $this->dao->select($t1Count)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t3')->on("t1.id = t3.task and t3.account = '{$account}'")
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
     * 把第一个超级管理员设置为当前用户。
     * Set the first super admin as current user.
     *
     * @access public
     * @return void
     */
    public function su()
    {
        $company = $this->dao->select('admins')->from(TABLE_COMPANY)->fetch();
        $admins  = explode(',', trim($company->admins, ','));
        if(empty($admins[0])) helper::end('No admin users.');

        $this->app->user = $this->getById($admins[0]);
    }

    /**
     * Put the current user first.
     *
     * @param  array  $users
     * @access public
     * @return array
     */
    public function processAccountSort(array $users = array()): array
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
