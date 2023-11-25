<?php
class userZen extends user
{
    /**
     * 构建职位和权限组数据。
     * Prepare roles and groups data.
     *
     * @access public
     * @return void
     */
    public function prepareRolesAndGroups(): void
    {
        $groupList = array();
        $roleGroup = array();
        $groups    = $this->dao->select('id, name, role, vision')->from(TABLE_GROUP)->fetchAll();
        foreach($groups as $group)
        {
            if($group->vision == $this->config->vision) $groupList[$group->id] = $group->name;
            if($group->role) $roleGroup[$group->role] = $group->id;
        }

        $this->view->groupList = $groupList;
        $this->view->roleGroup = $roleGroup;
    }

    /**
     * 创建用户前的检查。
     * Check before creating a user.
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
     * @access public
     * @return bool
     */
    public function checkVerifyPassword(string $verifyPassword): bool
    {
        if(empty($verifyPassword) || $verifyPassword != md5($this->app->user->password . $this->session->rand)) dao::$errors['verifyPassword'][] = $this->lang->user->error->verifyPassword;

        return !dao::isError();
    }
}
