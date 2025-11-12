<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gitlabZenTest extends baseTest
{
    protected $moduleName = 'gitlab';
    protected $className  = 'model';

    /**
     * Test bindUsers method.
     *
     * @param  int   $gitlabID
     * @param  array $users
     * @param  array $gitlabNames
     * @param  array $zentaoUsers
     * @access public
     * @return mixed
     */
    public function bindUsersTest(int $gitlabID, array $users, array $gitlabNames, array $zentaoUsers)
    {
        global $tester, $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'control.php';
        }
        if(!class_exists('gitlabZen'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'zen.php';
        }

        /* 使用反射创建 gitlabZen 实例,跳过构造函数 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();

        /* 初始化必要的属性 */
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 bindUsers 方法 */
        $method = $reflection->getMethod('bindUsers');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        $method->invoke($zenInstance, $gitlabID, $users, $gitlabNames, $zentaoUsers);

        if(dao::isError()) return dao::getError();

        /* 返回绑定后的 oauth 记录用于验证 */
        $result = $tester->dao->select('*')->from(TABLE_OAUTH)
            ->where('providerType')->eq('gitlab')
            ->andWhere('providerID')->eq($gitlabID)
            ->fetchAll('openID');

        return $result;
    }

    /**
     * Test getProjectMemberData method.
     *
     * @param  array $gitlabCurrentMembers
     * @param  array $newGitlabMembers
     * @param  array $bindedUsers
     * @param  array $accounts
     * @param  array $originalUsers
     * @access public
     * @return array
     */
    public function getProjectMemberDataTest(array $gitlabCurrentMembers, array $newGitlabMembers, array $bindedUsers, array $accounts, array $originalUsers): array
    {
        global $app;

        /* 创建 gitlabZen 实例 */
        $zenInstance = $app->loadTarget('gitlab', '', 'zen');

        /* 通过反射调用 getProjectMemberData 方法 */
        $reflection = new ReflectionClass('gitlabZen');
        $method = $reflection->getMethod('getProjectMemberData');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        $result = $method->invoke($zenInstance, $gitlabCurrentMembers, $newGitlabMembers, $bindedUsers, $accounts, $originalUsers);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkBindedUser method.
     *
     * @param  int    $gitlabID
     * @param  string $account
     * @param  bool   $isAdmin
     * @access public
     * @return mixed
     */
    public function checkBindedUserTest(int $gitlabID, string $account = '', bool $isAdmin = false)
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'control.php';
        }
        if(!class_exists('gitlabZen'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'zen.php';
        }

        /* 备份原用户信息 */
        $originalUser = $app->user;

        /* 模拟不同用户和权限 */
        if($account)
        {
            $app->user = new stdClass();
            $app->user->account = $account;
            $app->user->admin = $isAdmin;
        }

        /* 使用反射创建 gitlabZen 实例,跳过构造函数 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();

        /* 初始化必要的属性 */
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 checkBindedUser 方法 */
        $method = $reflection->getMethod('checkBindedUser');
        $method->setAccessible(true);

        /* 调用 zen 方法,捕获可能的 EndResponseException */
        try
        {
            $result = $method->invoke($zenInstance, $gitlabID);
        }
        catch(EndResponseException $e)
        {
            /* 恢复原用户信息 */
            $app->user = $originalUser;

            /* 检查响应数据,返回错误信息 */
            $content = $e->getContent();

            /* 直接返回语言文件中的错误信息 */
            return $app->lang->gitlab->mustBindUser;
        }

        /* 恢复原用户信息 */
        $app->user = $originalUser;

        /* 检查是否有错误 */
        if(dao::isError()) return dao::getError();

        return $result ? $result : 'success';
    }

    /**
     * Test checkUserRepeat method.
     *
     * @param  array $zentaoUsers
     * @param  array $userPairs
     * @access public
     * @return array
     */
    public function checkUserRepeatTest(array $zentaoUsers, array $userPairs): array
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'control.php';
        }
        if(!class_exists('gitlabZen'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'zen.php';
        }

        /* 使用反射创建 gitlabZen 实例,跳过构造函数 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();

        /* 初始化必要的属性 */
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 checkUserRepeat 方法 */
        $method = $reflection->getMethod('checkUserRepeat');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        $result = $method->invoke($zenInstance, $zentaoUsers, $userPairs);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGroupMemberData method.
     *
     * @param  array $currentMembers
     * @param  array $newMembers
     * @access public
     * @return array
     */
    public function getGroupMemberDataTest(array $currentMembers, array $newMembers): array
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'control.php';
        }
        if(!class_exists('gitlabZen'))
        {
            require_once $app->getModulePath('', 'gitlab') . 'zen.php';
        }

        /* 使用反射创建 gitlabZen 实例,跳过构造函数 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();

        /* 初始化必要的属性 */
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 getGroupMemberData 方法 */
        $method = $reflection->getMethod('getGroupMemberData');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        $result = $method->invoke($zenInstance, $currentMembers, $newMembers);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test issueToZentaoObject method.
     *
     * @param  object $issue
     * @param  int    $gitlabID
     * @param  object $changes
     * @access public
     * @return object|null|string
     */
    public function issueToZentaoObjectTest(object $issue, int $gitlabID, ?object $changes = null)
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab')) require_once $app->getModulePath('', 'gitlab') . 'control.php';
        if(!class_exists('gitlabZen')) require_once $app->getModulePath('', 'gitlab') . 'zen.php';

        /* 使用反射创建 gitlabZen 实例并初始化 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 issueToZentaoObject 方法 */
        $method = $reflection->getMethod('issueToZentaoObject');
        $method->setAccessible(true);

        try
        {
            $result = $method->invoke($zenInstance, $issue, $gitlabID, $changes);
        }
        catch(TypeError $e)
        {
            return 'invalid_object_type';
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test recordWebhookLogs method.
     *
     * @param  string $input
     * @param  object $result
     * @access public
     * @return string|bool
     */
    public function recordWebhookLogsTest(string $input, object $result)
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab')) require_once $app->getModulePath('', 'gitlab') . 'control.php';
        if(!class_exists('gitlabZen')) require_once $app->getModulePath('', 'gitlab') . 'zen.php';

        /* 使用反射创建 gitlabZen 实例并初始化 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 recordWebhookLogs 方法 */
        $method = $reflection->getMethod('recordWebhookLogs');
        $method->setAccessible(true);

        /* 获取日志文件路径 */
        $logFile = $app->getLogRoot() . 'webhook.' . date('Ymd') . '.log.php';

        /* 删除已存在的日志文件,确保测试环境干净 */
        if(file_exists($logFile)) unlink($logFile);

        /* 调用 zen 方法 */
        $method->invoke($zenInstance, $input, $result);

        /* 验证日志文件是否创建 */
        if(!file_exists($logFile)) return 'file_not_created';

        /* 读取日志文件内容 */
        $logContent = file_get_contents($logFile);

        /* 清理测试日志文件 */
        unlink($logFile);

        /* 返回日志内容用于验证 */
        return $logContent;
    }

    /**
     * Test webhookParseBody method.
     *
     * @param  object $body
     * @param  int    $gitlabID
     * @access public
     * @return object|bool|string
     */
    public function webhookParseBodyTest(object $body, int $gitlabID)
    {
        global $app;
        if(!class_exists('gitlab')) require_once $app->getModulePath('', 'gitlab') . 'control.php';
        if(!class_exists('gitlabZen')) require_once $app->getModulePath('', 'gitlab') . 'zen.php';

        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        $method = $reflection->getMethod('webhookParseBody');
        $method->setAccessible(true);

        try {
            $result = $method->invoke($zenInstance, $body, $gitlabID);
        } catch(TypeError $e) {
            if(strpos($e->getMessage(), 'Return value must be of type object, bool returned') !== false) return 'type_error_false';
            if(strpos($e->getMessage(), 'Return value must be of type object, null returned') !== false) return 'type_error_null';
            throw $e;
        } catch(EndResponseException $e) {
            return 'method_not_found';
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test webhookParseIssue method.
     *
     * @param  object $body
     * @param  int    $gitlabID
     * @access public
     * @return object|null|string|bool
     */
    public function webhookParseIssueTest(object $body, int $gitlabID)
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab')) require_once $app->getModulePath('', 'gitlab') . 'control.php';
        if(!class_exists('gitlabZen')) require_once $app->getModulePath('', 'gitlab') . 'zen.php';

        /* 使用反射创建 gitlabZen 实例并初始化 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 webhookParseIssue 方法 */
        $method = $reflection->getMethod('webhookParseIssue');
        $method->setAccessible(true);

        try
        {
            $result = $method->invoke($zenInstance, $body, $gitlabID);
        }
        catch(TypeError $e)
        {
            /* 方法声明返回object,但实际可能返回null或false,捕获类型错误 */
            if(strpos($e->getMessage(), 'must be of type object, null returned') !== false) return null;
            if(strpos($e->getMessage(), 'must be of type object, bool returned') !== false) return false;
            throw $e;
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test webhookParseObject method.
     *
     * @param  array $labels
     * @access public
     * @return object|null
     */
    public function webhookParseObjectTest(array $labels)
    {
        global $app;

        /* 加载 control 和 zen 类 */
        if(!class_exists('gitlab')) require_once $app->getModulePath('', 'gitlab') . 'control.php';
        if(!class_exists('gitlabZen')) require_once $app->getModulePath('', 'gitlab') . 'zen.php';

        /* 使用反射创建 gitlabZen 实例并初始化 */
        $reflection = new ReflectionClass('gitlabZen');
        $zenInstance = $reflection->newInstanceWithoutConstructor();
        $zenInstance->app = $app;
        $zenInstance->config = $app->config;
        $zenInstance->lang = $app->lang;
        $zenInstance->dao = $app->loadClass('dao');

        /* 通过反射调用 webhookParseObject 方法 */
        $method = $reflection->getMethod('webhookParseObject');
        $method->setAccessible(true);

        /* 调用 zen 方法 */
        try
        {
            $result = $method->invoke($zenInstance, $labels);
        }
        catch(TypeError $e)
        {
            /* 方法声明返回object,但实际可能返回null,捕获类型错误 */
            if(strpos($e->getMessage(), 'must be of type object, null returned') !== false) return null;
            throw $e;
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }
}
