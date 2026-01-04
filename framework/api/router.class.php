<?php declare(strict_types=1);
/**
 * 禅道API的api类。
 * The api class file of ZenTao API.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include dirname(__FILE__, 2) . '/router.class.php';
class api extends router
{
    /**
     * 请求API的路径
     * The requested path of api.
     *
     * @var string
     * @access public
     */
    public $path;

    /**
     * 请求API的参数，包括键值
     * The requested params of api: key and value.
     *
     * @var array
     * @access public
     */
    public $params = array();

    /**
     * 请求API的参数名
     * The requested param names of api.
     *
     * @var array
     * @access public
     */
    public $paramNames = array();

    /**
     * 请求的资源名称
     * The requested entry point
     *
     * @var string
     * @access public
     */
    public $entry;

    /**
     * API资源的执行方法: get post put delete
     * The action of entry point: get post put delete
     *
     * @var string
     * @access public
     */
    public $action;

    /**
     * 选择性输出json数据
     * Extract json data
     *
     * @var string
     * @access public
     */
    public $responseExtractor = '*';

    /**
     * 构造方法, 设置请求路径，版本等
     *
     * The construct function.
     * Prepare all the paths, version and so on.
     *
     * @access public
     * @return void
     */
    public function __construct(string $appName = 'api', string $appRoot = '')
    {
        $this->path = trim(substr((string) $_SERVER['REQUEST_URI'], strpos((string) $_SERVER['REQUEST_URI'], 'api.php') + 7), '/');
        if(strpos($this->path, '?') > 0) $this->path = strstr($this->path, '?', true);

        $subPos = $this->path ? strpos($this->path, '/') : false;

        $this->apiVersion = $subPos !== false ? substr($this->path, 0, $subPos) : '';
        $this->path       = $subPos !== false ? substr($this->path, $subPos) : '';
        parent::__construct($appName, $appRoot);

        $this->viewType    = 'json';
        $this->httpMethod  = strtolower((string) $_SERVER['REQUEST_METHOD']);

        $this->loadApiLang();
    }

    /**
     * 解析请求路径，找到处理方法
     *
     * Parse request path, find entry and action.
     *
     * @param  array $routes
     * @access public
     * @return void
     */
    public function route(array $routes)
    {
        foreach($routes as $route => $target)
        {
            $patternAsRegex = preg_replace_callback(
                '#:([\w]+)\+?#',
                $this->matchesCallback(...),
                str_replace(')', ')?', $route)
            );
            if(str_ends_with($route, '/')) $patternAsRegex .= '?';

            /* Cache URL params' names and values if this route matches the current HTTP request. */
            if(!preg_match('#^' . $patternAsRegex . '$#', $this->path, $paramValues)) continue;

            /* Set module and action */
            $this->entry  = $target;
            $this->action = strtolower((string) $_SERVER['REQUEST_METHOD']);

            /* Set params */
            foreach($this->paramNames as $name)
            {
                if(!isset($paramValues[$name])) continue;

                $this->params[$name] = urldecode($paramValues[$name]);
            }
            return;
        }

        $this->entry  = 'error';
        $this->action = 'notFound';
    }

        /**
     * 复数转单数
     * Converting plural nouns to singular.
     *
     * @param  string $word
     * @access public
     * @return string
     */
    public function singular($word)
    {
        /* 特殊词处理 */
        $irregular = array(
            'children' => 'child',
            'men' => 'man',
            'women' => 'woman',
            'people' => 'person',
            'feet' => 'foot',
            'teeth' => 'tooth',
            'mice' => 'mouse',
            'geese' => 'goose',
            'oxen' => 'ox',
            'cacti' => 'cactus',
            'foci' => 'focus',
            'nuclei' => 'nucleus',
            'syllabi' => 'syllabus',
            'radii' => 'radius',
            'phenomena' => 'phenomenon',
            'criteria' => 'criterion',
            'data' => 'datum',
            'media' => 'medium',
            'lice' => 'louse',
            'selves' => 'self',
            'loaves' => 'loaf',
            'leaves' => 'leaf',
            'lives' => 'life',
            'wives' => 'wife',
            'knives' => 'knife',
            'wolves' => 'wolf',
            'elves' => 'elf',
            'halves' => 'half',
            'scarves' => 'scarf',
            'hooves' => 'hoof',
            'veterans' => 'veteran', // 特殊情况示例
        );

        if(isset($irregular[strtolower($word)])) {
            $lowerWord = strtolower($word);
            $singular = $irregular[$lowerWord];
            if (ctype_upper($word[0])) {
                $singular = ucfirst($singular);
            }
            return $singular;
        }

        $rules = [
            '/sses$/i' => 'ss',
            '/ies$/i' => 'y',
            '/ves$/i' => 'f',
            '/zes$/i' => 'z',
            '/ches$/i' => 'ch',
            '/shes$/i' => 'sh',
            '/men$/i' => 'man',
            '/s$/i' => '',
        ];

        foreach($rules as $pattern => $replacement)
        {
            if(preg_match($pattern, $word)) return preg_replace($pattern, $replacement, $word, 1);
        }

        return $word;
    }

    /**
     * 路由正则匹配
     * Match routes.
     *
     * @param  array $routes
     * @access private
     * @return array
     */
    private function matchRoutes($routes)
    {
        foreach($routes as $route => $info)
        {
            $patternAsRegex = preg_replace_callback(
                '#:([\w]+)\+?#',
                $this->matchesCallback(...),
                str_replace(')', ')?', $route)
            );
            if(str_ends_with($route, '/')) $patternAsRegex .= '?';

            /* Cache URL params' names and values if this route matches the current HTTP request. */
            if(!preg_match('#^' . $patternAsRegex . '$#', $this->path, $paramValues)) continue;

            return array($info, $paramValues);
        }

        return array(null, array());
    }

    /**
     * API2.0 根据路由表设置path和params
     * API2.0 Set path, params by routes.
     *
     * @param  array $routes
     * @access private
     * @return string
     */
    public function parseRouteV2($routes)
    {
        $methodName = '';

        list($info, $paramValues) = $this->matchRoutes($routes);

        if($info)
        {
            if(isset($info['method'])) $methodName = $info['method'];

            if(isset($info['redirect']))
            {
                foreach($paramValues as $key => $value)
                {
                    if(is_numeric($key)) continue;

                    $_GET[$key]       = $value;
                    $info['redirect'] = str_replace(':'.$key, $value, $info['redirect']);
                }
                if(isset($info['response'])) $this->responseExtractor = $info['response'];

                $url = parse_url($info['redirect']);
                $this->path = $url['path'];

                if(isset($url['query']))
                {
                    parse_str($url['query'], $params);
                    foreach($params as $key => $value) $_GET[$key] = $value;
                }

                list($info, $paramValues) = $this->matchRoutes($routes);
                if(isset($info['method'])) $methodName = $info['method'];
            }

            if(isset($info['response']) && $this->responseExtractor == '*') $this->responseExtractor = $info['response'];
        }

        foreach($paramValues as $key => $value)
        {
            if(is_numeric($key)) continue;
            $_GET[$key] = $value;
        }

        return $methodName;
    }

    /**
     * API2.0 路由
     * API2.0 routing.
     *
     * @param  array $routes
     * @access private
     * @return array
     */
    public function routeV2($routes)
    {
        $this->action = strtolower((string) $_SERVER['REQUEST_METHOD']);

        $methodName = '';
        if($this->action == 'get') $methodName = $this->parseRouteV2($routes);

        $pathItems  = explode('/', trim($this->path, '/'));
        $moduleName = $this->singular($pathItems[0]);

        $actionToMethod = array(
            'get'    => 'browse',
            'post'   => 'create',
            'put'    => 'edit',
            'delete' => 'delete'
        );

        if(isset($pathItems[1]))
        {
            if(is_numeric($pathItems[1]))
            {
                if($this->action == 'get')
                {
                    $methodName = 'view';
                }
                else
                {
                    $_GET[$moduleName.'ID'] = $pathItems[1];

                    /* Set default params and post data to delete.*/
                    if($this->action == 'delete') $_GET['confirm'] = 'yes';
                }
            }
            else
            {
                $methodName = $pathItems[1];
            }
        }

        if(isset($pathItems[2])) $methodName = $pathItems[2];
        if(!$methodName) $methodName = $actionToMethod[$this->action];

        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
        $this->setControlFile();
    }

    /**
     * 将路由路径参数转化为正则
     *
     * Parse params of route to regular expression.
     *
     * @param  array     $m
     * @access protected
     * @return string
     */
    protected function matchesCallback(array $m)
    {
        $this->paramNames[] = $m[1];
        return '(?P<' . $m[1] . '>[^/]+)';
    }

    /**
     * 解析访问请求
     *
     * Parse request.
     *
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        /* If version of api don't exists, call parent method. */
        if(!$this->apiVersion) return parent::parseRequest();

        global $routes;
        if($this->apiVersion == 'v1')
        {
            include $this->appRoot . "config/apiv1.php";
            if(isset($this->config->routes)) $routes = array_merge($routes, $this->config->routes);
            $this->route($routes);
        }
        else
        {
            include $this->appRoot . "config/apiv2.php";
            $this->routeV2($routes);
        }
    }

    /**
     * 检查传入的对象是否有权限访问
     *
     * Check object priv.
     *
     * @param  object $object
     * @param  string $table
     * @access public
     * @return bool
     */
    public function checkObjectPriv(object $object, string $table): bool
    {
        if($this->user->admin) true;

        $userView = $this->user->view;
        switch($table)
        {
            case TABLE_STORY:
            case TABLE_BUG:
            case TABLE_CASE:
            case TABLE_TICKET:
            case TABLE_FEEDBACK:
            case TABLE_PRODUCTPLAN:
                return (!$object->product || strpos(",{$userView->products},", ",$object->product,") !== false);
            case TABLE_PRODUCT:
                return (!$object->id || strpos(",{$userView->products},", ",$object->id,") !== false);
            case TABLE_PROJECT: // project,execution,program
                $projects = ",{$userView->sprints},{$userView->projects},{$userView->programs},";
                return (!$object->id || strpos($projects, ",$object->id,") !== false);
            case TABLE_BUILD:
            case TABLE_TASK:
                return (!$object->execution || strpos(",{$userView->sprints},", ",$object->execution,") !== false);
            default:
                return true;
        }

        return false;
    }

    /**
     * 检查传入的对象是否存在
     *
     * Check object exists.
     *
     * @access public
     * @return void
     */
    public function checkObjectExists()
    {
        $objectMap = [
            'program'       => TABLE_PROJECT,
            'programID'     => TABLE_PROJECT,
            'product'       => TABLE_PRODUCT,
            'productID'     => TABLE_PRODUCT,
            'project'       => TABLE_PROJECT,
            'projectID'     => TABLE_PROJECT,
            'productplan'   => TABLE_PRODUCTPLAN,
            'productplanID' => TABLE_PRODUCTPLAN,
            'plan'          => TABLE_PRODUCTPLAN,
            'planID'        => TABLE_PRODUCTPLAN,
            'execution'     => TABLE_PROJECT,
            'executionID'   => TABLE_PROJECT,
            'story'         => TABLE_STORY,
            'storyID'       => TABLE_STORY,
            'epic'          => TABLE_STORY,
            'epicID'        => TABLE_STORY,
            'requirement'   => TABLE_STORY,
            'requirementID' => TABLE_STORY,
            'task'          => TABLE_TASK,
            'taskID'        => TABLE_TASK,
            'bug'           => TABLE_BUG,
            'bugID'         => TABLE_BUG,
            'feedback'      => TABLE_FEEDBACK,
            'feedbackID'    => TABLE_FEEDBACK,
            'build'         => TABLE_BUILD,
            'buildID'       => TABLE_BUILD,
            'case'          => TABLE_CASE,
            'caseID'        => TABLE_CASE,
            'testcase'      => TABLE_CASE,
            'testcaseID'    => TABLE_CASE,
            'user'          => TABLE_USER,
            'userID'        => TABLE_USER,
            'ticket'        => TABLE_TICKET,
            'ticketID'      => TABLE_TICKET,
            'dept'          => TABLE_DEPT,
            'deptID'        => TABLE_DEPT,
        ];

        /* Check assignedTo. */
        if(isset($_POST['assignedTo']) && $_POST['assignedTo'])
        {
            $user = $this->dao->select('*')->from(TABLE_USER)
                ->where('account')->eq($_POST['assignedTo'])
                ->fetch();
            if(!$user) return $this->control->sendError('User does not exist.');
        }

        $params = array_merge($this->params, $_POST);
        foreach($params as $key => $value)
        {
            if(isset($objectMap[$key]) && $value)
            {
                $table  = $objectMap[$key];
                $object = $this->dao->select('*')->from($table)
                    ->where('id')->eq($value)
                    ->beginIF(!in_array($key, ['dept', 'deptID']))->andWhere('deleted')->eq('0')->fi()
                    ->fetch();
                if(!$object) return $this->control->sendError(ucfirst(str_replace('ID', '', $key)) . ' does not exist.');

                if(!$this->checkObjectPriv($object, $table)) return $this->control->sendError(ucfirst(str_replace('ID', '', $key)) . ' is not allowed.');
            }
        }
    }

    /**
     * 执行对应模块
     *
     * Load the running module.
     *
     * @access public
     * @return void
     */
    public function loadModule()
    {
        try
        {
            /* If the version of api don't exists, call parent method. */
            if($this->apiVersion == 'v2')
            {
                $this->setParams();
                if(in_array($this->action, array('post', 'put', 'delete')))
                {
                    $this->setFormData();
                }

                $this->checkObjectExists();

                return parent::loadModule();
            }
            elseif(!$this->apiVersion)
            {
                parent::setParams();
                return parent::loadModule();
            }

            /* api v1. */
            $entry    = strtolower($this->entry);
            $filename = $this->appRoot . "api/$this->apiVersion/entries/$entry.php";

            if(file_exists($filename)) include($filename);

            $entryName = $this->entry . 'Entry';

            if($entry == 'error' && !class_exists($entryName)) include($this->appRoot . "api/v1/entries/$entry.php");

            $entry = new $entryName();

            if($this->action == 'options') throw EndResponseException::create($entry->send(204));

            echo call_user_func_array(array($entry, $this->action), array_values($this->params));

            $this->outputXhprof();
        }
        catch(EndResponseException $endResponseException)
        {
            echo $endResponseException->getContent();
        }
    }

    /**
     * 设置form data。
     * Set form data.
     *
     * @access public
     * @return void
     */
    public function setFormData()
    {
        $requestBody = file_get_contents("php://input");

        $_POST = json_decode($requestBody, true);

        /* Avoid empty post body. */
        if(in_array($this->control->moduleName, ['feedback', 'ticket']))
        {
            $_POST['uid'] = '1';
        }
        else
        {
            $_POST['verifyPassword'] = '1';
        }


        /* 以POST的值为准。 Set GET value from POST data. */
        foreach($_POST as $key => $value)
        {
            if(isset($this->params[$key])) $this->params[$key] = $value;
        }

        /* 其他方法不需要从GET页面获取post data。Other request directly. */
        if(!in_array($this->methodName, ['create', 'edit'])) return;

        /* 更新操作的表单需要拼接原始的值。 Merge original values. */
        /* Get form data by get request. */
        $postData = $_POST;
        $_POST    = array();

        $this->control->viewType    = 'html';
        $this->control->getFormData = true;

        $zen = $this->control->moduleName . 'Zen';
        if(isset($this->control->$zen)) $this->control->$zen->getFormData = true;

        $control = $this->control;  // fetch method will change control.
        $method  = $this->control->methodName;
        call_user_func_array(array($this->control, $method), $this->params);

        /* Clean the output in get method. */
        ob_clean();

        $this->control->getFormData       = false;
        $this->control->viewType          = 'json';
        $this->control                    = $control;

        $_POST = $postData;
        foreach($this->control->formData as $key => $value)
        {
            if(!isset($_POST[$key])) $_POST[$key] = $value;
        }

        if(isset($this->control->$zen))
        {
            $this->control->$zen->getFormData = false;
            foreach($this->control->$zen->formData as $key => $value)
            {
                if(!isset($_POST[$key])) $_POST[$key] = $value;
            }
        }
    }

    /**
     * 设置要被调用方法的参数。
     * Set the params of method calling.
     *
     * @access public
     * @return void
     */
    public function setParams()
    {
        $defaultParams = $this->getDefaultParams();

        $this->params = array();

        /* POST/PUT/DELETE methods have no correct param name, use index. */
        if($this->action != 'get')
        {
            $values = array_values($_GET);
            $index  = 0;
            foreach($defaultParams as $key => $defaultItem)
            {
                if(isset($values[$index]))
                {
                    $value = $values[$index];
                    settype($value, $defaultItem['type']);
                    $_GET[$key] = $value;
                }
                $index++;
            }
        }

        foreach($defaultParams as $key => $defaultItem)
        {
            if(isset($_GET[$key]))
            {
                $this->params[$key] = helper::convertType(strip_tags((string) $_GET[$key]), $defaultItem['type']);
            }
            else
            {
                $this->params[$key] = $defaultItem['default'];
            }
        }

        if($this->config->framework->filterParam == 2)
        {
            $_GET    = validater::filterParam($_GET, 'get');
            $_COOKIE = validater::filterParam($_COOKIE, 'cookie');
        }

        $this->rawParams = $this->params;

        return true;
    }

    /**
     * 加载配置文件
     *
     * Load config file of api.
     *
     * @param  string $configPath
     * @access public
     * @return void
     */
    public function loadApiConfig(string $configPath)
    {
        global $config;
        include($this->appRoot . "api/$this->apiVersion/config/$configPath.php");
    }

    /**
     * 加载语言文件
     *
     * Load lang file of api.
     *
     * @access public
     * @return void
     */
    public function loadApiLang()
    {
        global $lang;
        $filename = $this->appRoot . "api/$this->apiVersion/lang/$this->clientLang.php";
        if($this->apiVersion && file_exists($filename)) include($filename);
    }

    /**
     * 格式化旧版本API响应数据
     *
     * Format old version data.
     *
     * @param  string
     * @access public
     * @return string
     */
    public function formatData(string $output)
    {
        /* If the version exists, return output directly. */
        if($this->apiVersion) return $output;

        $output = json_decode((string) $output);

        $data = new stdClass();
        $data->status = $output->status ?? $output->result;
        if(isset($output->message)) $data->message = $output->message;
        if(isset($output->data))    $data->data    = json_decode((string) $output->data);
        if(isset($output->id))      $data->id      = $output->id;
        $output = json_encode($data);

        unset($_SESSION['ENTRY_CODE']);
        unset($_SESSION['VALID_ENTRY']);

        return $output;
    }

    /**
     * 设置vision。
     * set Debug.
     *
     * @access public
     * @return void
     */
    public function setVision()
    {
        $account = isset($_SESSION['user']) ? $_SESSION['user']->account : '';
        if(empty($account) and isset($_POST['account'])) $account = $_POST['account'];
        if(empty($account) and isset($_GET['account']))  $account = $_GET['account'];

        $vision = 'rnd';
        if($this->config->installed and validater::checkAccount($account))
        {
            $sql     = new sql();
            $account = $sql->quote($account);

            $user = $this->dbh->query("SELECT * FROM " . TABLE_USER . " WHERE account = $account AND deleted = '0' LIMIT 1")->fetch();
            if(!empty($user->visions))
            {
                $userVisions = explode(',', $user->visions);
                if(!in_array($vision, $userVisions)) $vision = '';
                if(empty($vision)) list($vision) = $userVisions;
            }
        }

        list($defaultVision) = explode(',', trim($this->config->visions, ','));
        if($vision and strpos($this->config->visions, ",{$vision},") === false) $vision = $defaultVision;

        $this->config->vision = $vision ? $vision : $defaultVision;
    }

    /**
     * 设置超级变量。
     * Set the super vars.
     *
     * @access public
     * @return void
     */
    public function setSuperVars()
    {
        $this->config->framework->filterCSRF = false;

        parent::setSuperVars();
    }
}
