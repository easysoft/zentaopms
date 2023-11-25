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
     * 构建自定义字段。
     * Prepare custom fields.
     *
     * @param  string $method
     * @param  string $requiredMethod
     * @access public
     * @return void
     */
    public function prepareCustomFields(string $method, string $requiredMethod): void
    {
        $availableField = 'available' . ucfirst($method) . 'Fields';
        $showField      = $method . 'Fields';

        /* 获取所有的联系方式字段。*/
        /* Get all contact fields. */
        $allContactFields = array_keys($this->lang->user->contactFieldList);
        /* 获取可用的联系方式字段，转为数组并去空、去重。*/
        /* Get available contact fields, convert to array and remove empty and duplicate. */
        $availableContactFields = array_unique(array_filter(explode(',', trim($this->config->user->contactField, ','))));
        /* 获取不可用的联系方式字段。*/
        /* Get unavailable contact fields. */
        $unAvailableContactFields = array_diff($allContactFields, $availableContactFields);
        /* 从配置文件获取所有可用字段，转为数组并去空、去重。*/
        /* Get all available fields from config file, convert to array and remove empty and duplicate. */
        $availableFields = array_unique(array_filter(explode(',', trim($this->config->user->$availableField, ','))));
        /* 从可用字段中去除不可用的联系方式字段。*/
        /* Remove unavailable contact fields from available fields. */
        $availableFields = array_diff($availableFields, $unAvailableContactFields);

        /* 获取可以显示的字段。*/
        /* Get fields that can be displayed. */
        $listFields = array();
        foreach($availableFields as $field) $listFields[$field] = $this->lang->user->$field;

        /* 从配置文件获取必填项字段，转为数组并去空、去重。*/
        /* Get required fields from config file, convert to array and remove empty and duplicate. */
        $requiredFields = array_unique(array_filter(explode(',', trim($this->config->user->$requiredMethod->requiredFields, ','))));
        /* 从数据库中获取应该显示的字段。*/
        /* Get fields that should be displayed from database. */
        $showFields = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=user&section=custom&key={$showField}");
        /* 从配置文件中获取应该显示的字段。*/
        /* Get fields that should be displayed from config file. */
        if(!$showFields) $showFields = $this->config->user->custom->$showField;
        /* 把应该显示的字段转为数组并去空、去重。*/
        /* Convert fields that should be displayed to array and remove empty and duplicate. */
        $showFields = array_unique(array_filter(explode(',', trim($showFields, ','))));
        /* 把应该显示的字段和必填项字段合并，确保自定义字段面板中必填项字段是被勾选中的。*/
        /* Merge fields that should be displayed and required fields to ensure that required fields are checked in the custom field panel. */
        $showFields = array_merge($showFields, $requiredFields);
        /* 把应该显示的字段和可用字段取交集。*/
        /* Get the intersection of fields that should be displayed and available fields. */
        $showFields = array_intersect($showFields, $availableFields);

        $this->view->listFields = $listFields;
        $this->view->showFields = $showFields;
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
