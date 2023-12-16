<?php
class userZen extends user
{
    /**
     * 检查缓存目录和数据目录访问权限。如果不能访问，终止程序并输出提示信息。
     * Check the access permissions of the cache directory and data directory. If you cannot access, terminate the program and output the prompt message.
     *
     * @access public
     * @return void
     */
    public function checkDirPermission(): void
    {
        $canModifyDIR = true;
        if($this->checkTmp() === false)
        {
            $canModifyDIR = false;
            $folderPath   = $this->app->tmpRoot;
        }
        elseif(!is_dir($this->app->dataRoot) || substr(decoct(fileperms($this->app->dataRoot)), -4) != '0777')
        {
            $canModifyDIR = false;
            $folderPath   = $this->app->dataRoot;
        }

        if(!$canModifyDIR)
        {
            $lang    = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $this->lang->user->mkdirWin : $this->lang->user->mkdirLinux;
            $message = sprintf($lang, $folderPath, $folderPath, $folderPath, $folderPath);

            if($_POST) $this->send(array('result' => 'fail', 'message' => array('size' => 'md', 'message' => array('html' => str_replace("\n", '<br>', strip_tags($message))))));

            helper::end($message);
        }
    }

    /**
     * 检查缓存目录是否有写权限。
     * Check if the tmp directory is writable.
     *
     * @access public
     * @return bool
     */
    public function checkTmp(): bool
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
            return true;
        }

        return false;
    }

    /**
     * 获取一个用户用于 json 格式返回给前台。
     * Get a user for json format to return to the front end.
     *
     * @param  object $user
     * @access public
     * @return object
     */
    public function getUserForJSON(object $user): object
    {
        unset($user->password);
        unset($user->deleted);

        $user->token   = session_id(); // App client will use session id as token.
        $user->company = $this->app->company->name;

        return $user;
    }

    /**
     * 获取 FeatureBar 导航。
     * Get featureBar menus.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function getFeatureBarMenus(object $user): array
    {
        $moduleName = $this->app->moduleName;
        $methodName = $this->app->methodName;
        $storyType  = zget($this->app->params, 'storyType', '');
        $params     = "userID={$user->id}";

        $featureBarMenus = array();
        if(common::hasPriv($moduleName, 'todo')) $featureBarMenus['todo'] = array('active' => false, 'url' => $this->createLink($moduleName, 'todo', "$params&type=all"), 'text' => $this->lang->user->schedule);
        if(common::hasPriv($moduleName, 'task')) $featureBarMenus['task'] = array('active' => false, 'url' => $this->createLink($moduleName, 'task', $params), 'text' => $this->lang->user->task);

        if($this->config->URAndSR) $featureBarMenus['requirement'] = array('active' => false, 'url' => $this->createLink($moduleName, 'story', "$params&storyType=requirement"), 'text' => $this->lang->URCommon);

        if(common::hasPriv($moduleName, 'story'))    $featureBarMenus['story']    = array('active' => false, 'url' => $this->createLink($moduleName, 'story', "$params&storyType=story"), 'text' => $this->lang->SRCommon);
        if(common::hasPriv($moduleName, 'bug'))      $featureBarMenus['bug']      = array('active' => false, 'url' => $this->createLink($moduleName, 'bug', $params), 'text' => $this->lang->user->bug);
        if(common::hasPriv($moduleName, 'testtask')) $featureBarMenus['testtask'] = array('active' => false, 'url' => $this->createLink($moduleName, 'testtask', $params), 'text' => $this->lang->user->testTask);
        if(common::hasPriv($moduleName, 'testcase')) $featureBarMenus['testcase'] = array('active' => false, 'url' => $this->createLink($moduleName, 'testcase', $params), 'text' => $this->lang->user->testCase);

        if(common::hasPriv($moduleName, 'execution') && $this->config->systemMode == 'ALM') $featureBarMenus['execution'] = array('active' => false, 'url' => $this->createLink($moduleName, 'execution', $params), 'text' => $this->lang->user->execution);
        if(common::hasPriv($moduleName, 'issue')     && $this->config->edition == 'max')    $featureBarMenus['issue']     = array('active' => false, 'url' => $this->createLink($moduleName, 'issue', $params), 'text' => $this->lang->user->issue);
        if(common::hasPriv($moduleName, 'risk')      && $this->config->edition == 'max')    $featureBarMenus['risk']      = array('active' => false, 'url' => $this->createLink($moduleName, 'risk', $params), 'text' => $this->lang->user->risk);

        if(common::hasPriv($moduleName, 'dynamic')) $featureBarMenus['dynamic'] = array('active' => false, 'url' => $this->createLink($moduleName, 'dynamic', "$params&type=today"), 'text' => $this->lang->user->dynamic);
        if(common::hasPriv($moduleName, 'profile')) $featureBarMenus['profile'] = array('active' => false, 'url' => $this->createLink($moduleName, 'profile', $params), 'text' => $this->lang->user->profile);

        if($methodName != 'story') $featureBarMenus[$methodName]['active'] = true;
        if($methodName == 'story' && $storyType == 'story')       $featureBarMenus['story']['active']       = true;
        if($methodName == 'story' && $storyType == 'requirement') $featureBarMenus['requirement']['active'] = true;

        return $featureBarMenus;
    }

    /**
     * 登录。
     * Login.
     *
     * @param  string $referer
     * @param  string $viewType
     * @param  string $loginLink
     * @param  string $denyLink
     * @param  string $locateReferer
     * @param  string $locateWebRoot
     * @access public
     * @return array
     */
    public function login(string $referer = '', string $viewType = '', string $loginLink = '', string $denyLink = '', string $locateReferer = '', string $locateWebRoot = ''): array
    {
        if(empty($_POST) && (!isset($_GET['account']) || !isset($_GET['password']))) return array();

        /* 预处理账号和密码。*/
        /* Preprocess account and password. */
        $account  = '';
        $password = '';
        if($this->post->account)  $account  = trim($this->post->account);
        if($this->post->password) $password = trim($this->post->password);
        if($this->get->account)   $account  = trim($this->get->account);
        if($this->get->password)  $password = trim($this->get->password);

        if(!$account) return array();

        /* 如果用户被锁定返回相关信息。*/
        /* Return related information if the user is locked. */
        if($this->user->checkLocked($account)) return $this->responseForLocked($viewType);

        /* 如果开启了登录验证码检查验证码是否正确。*/
        /* Check if the login captcha is correct if the login captcha is enabled. */
        if((!empty($this->config->safe->loginCaptcha) && strtolower($this->post->captcha) != strtolower($this->session->captcha) && $viewType != 'json')) return array('result' => 'fail', 'message' => $this->lang->user->errorCaptcha);

        /* 验证账号和密码。*/
        /* Verify account and password. */
        $user = $this->user->identify($account, $password, $this->post->passwordStrength);

        /* 登录失败返回错误信息。*/
        /* Return error message if login failed. */
        if(!$user) return $this->responseForLoginfail($viewType, $account);

        /* 获取用户所属权限组、权限和视图，记录日志并发放登录积分。*/
        /* Get user's group, priv and view, save log and give login score. */
        $user = $this->user->login($user, true, $this->post->keepLogin);

        /* 以 json 格式返回用户数据。*/
        /* Return user data in json format. */
        if($viewType == 'json') return array('status' => 'success', 'user' => $this->getUserForJSON($user));

        /* 来源网址不满足条件时跳转到首页。*/
        /* Jump to home page if the referer does not meet the conditions. */
        if(!$referer || strpos($referer, $loginLink) !== false || strpos($referer, $denyLink) !== false || strpos($referer, 'ajax') !== false || strpos($referer, 'block') !== false) return array('result' => 'success', 'locate' => $locateWebRoot);

        /* 解析来源网址包含的模块和方法。*/
        /* Parse the module and method contained in the referer. */
        list($module, $method) = $this->parseLoginModuleAndMethod($referer);

        /* 如果模块和方法为空或者不合法则跳转到首页。*/
        /* Jump to home page if the module and method are empty or illegal. */
        if(empty($module) || empty($method) || !$this->app->checkModuleName($module, false) || !$this->app->checkMethodName($module, false)) return array('result' => 'success', 'locate' => $locateWebRoot);

        /* 如果有模块和方法的访问权限则跳转到来源网址。*/
        /* Jump to the referer if there is access to the module and method. */
        if(common::hasPriv($module, $method)) return array('result' => 'success', 'locate' => $locateReferer);

        /* 跳转到首页。*/
        /* Jump to home page. */
        return array('result' => 'success', 'locate' => $locateWebRoot);
    }

    /**
     * 解析来源网址包含的模块和方法。
     * Parse the module and method contained in the referer.
     *
     * @param  string $referer
     * @access public
     * @return array
     */
    public function parseLoginModuleAndMethod(string $referer): array
    {
        $module = '';
        $method = '';

        /* Get the module and method of the referer. */
        if($this->config->requestType == 'PATH_INFO')
        {
            $requestFix = $this->config->requestFix;

            $path = substr($referer, strrpos($referer, '/') + 1);
            $path = rtrim($path, '.html');
            if($path && strpos($path, $requestFix) !== false) list($module, $method) = explode($requestFix, $path);
        }
        else
        {
            $url   = html_entity_decode($referer);
            $param = substr($url, strrpos($url, '?') + 1);

            if(strpos($param, '&') !== false) list($module, $method) = explode('&', $param);
            $module = str_replace('m=', '', $module);
            $method = str_replace('f=', '', $method);
        }

        return array($module, $method);
    }

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
        $groups    = $this->dao->select('id, name, role, vision')->from(TABLE_GROUP)->where('vision')->eq($this->config->vision)->fetchAll();
        foreach($groups as $group)
        {
            $groupList[$group->id] = $group->name;
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
     * 重新加载语言项。
     * Reload language items.
     *
     * @param  string $lang
     * @access public
     * @return void
     */
    public function reloadLang(string $lang): void
    {
        $this->app->setClientLang($lang);
        $this->app->loadLang('user');
    }

    /**
     * 用户已登录时的响应。
     * Response when user has logged in.
     *
     * @param  string $referer
     * @param  string $viewType
     * @param  string $loginLink
     * @param  string $denyLink
     * @param  string $locateReferer
     * @param  string $locateWebRoot
     * @access public
     * @return array
     */
    public function responseForLogon(string $referer, string $viewType, string $loginLink, string $denyLink, string $locateReferer, string $locateWebRoot): array
    {
        /* 以 json 格式返回用户数据。*/
        /* Return user data in json format. */
        if($viewType == 'json') return array('status' => 'success', 'user' => $this->getUserForJSON($this->app->user));

        /* 来源网址不满足条件时跳转到首页。*/
        /* Jump to home page if the referer does not meet the conditions. */
        if(!$referer || strpos($referer, $loginLink) !== false || strpos($referer, $denyLink) !== false || strpos($referer, 'ajax') !== false || strpos($referer, 'block') !== false) return array('result' => 'success', 'locate' => $locateWebRoot);

        /* 跳转到来源网址。*/
        /* Jump to the referer. */
        return array('result' => 'success', 'locate' => $locateReferer);
    }

    /**
     * 用户被锁定时的响应。
     * Response when user is locked.
     *
     * @param  string $viewType
     * @access public
     * @return array
     */
    public function responseForLocked(string $viewType): array
    {
        $message = sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes);
        if($viewType == 'json') return array('status' => 'failed', 'reason' => $message);

        return array('result' => 'fail', 'message' => $message);
    }

    /**
     * 登录失败时的响应。
     * Response when login failed.
     *
     * @param  string $viewType
     * @param  string $account
     * @access public
     * @return array
     */
    public function responseForLoginFail(string $viewType, string $account): array
    {
        if($viewType == 'json') return array('status' => 'failed', 'reason' => $this->lang->user->loginFailed);

        $remainTimes = $this->config->user->failTimes - $this->user->failPlus($account);
        if($remainTimes <= 0) return array('result' => 'fail', 'message' => sprintf($this->lang->user->loginLocked, $this->config->user->lockMinutes));
        if($remainTimes <= 3) return array('result' => 'fail', 'message' => sprintf($this->lang->user->lockWarning, $remainTimes));

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        return array('result' => 'fail', 'message' => $this->lang->user->loginFailed);
    }

    /**
     * 设置来源地址。
     * Set referer.
     *
     * @param  string $referer
     * @access public
     * @return string
     */
    public function setReferer(string $referer = ''): string
    {
        $this->referer = $this->server->http_referer ? $this->server->http_referer: '';
        if(!empty($referer)) $this->referer = helper::safe64Decode($referer);
        if($this->post->referer) $this->referer = $this->post->referer;

        /* 构建禅道链接的正则表达式。*/
        /* Build zentao link regular expression. */
        $webRoot = $this->config->webRoot;
        $linkReg = $webRoot . 'index.php?' . $this->config->moduleVar . '=\w+&' . $this->config->methodVar . '=\w+';
        if($this->config->requestType == 'PATH_INFO') $linkReg = $webRoot . '\w+' . $this->config->requestFix . '\w+';
        $linkReg = str_replace(array('/', '.', '?', '-'), array('\/', '\.', '\?', '\-'), $linkReg);

        /* 检查来源地址是否为禅道链接。*/
        /* Check zentao link by regular expression. */
        return preg_match('/^' . $linkReg . '/', $this->referer) ? $this->referer : $webRoot;
    }
}
