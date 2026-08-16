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
include_once dirname(__FILE__, 2) . '/router.class.php';
class api extends router
{
    /**
     * The cached default params of the real target control method.
     *
     * @var array|null
     * @access protected
     */
    protected $resolvedDefaultParams = null;

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
     * APIV2当前命中的原始路由信息。
     * The matched route info of APIV2.
     *
     * @var array
     * @access public
     */
    public $routeInfo = array();

    /**
     * APIV2 当前命中的原始路由信息。
     * The original matched route info of APIV2.
     *
     * @var array
     * @access public
     */
    public $originRouteInfo = array();

    /**
     * APIV2 redirect 后的真实路由信息。
     * The redirected route info of APIV2.
     *
     * @var array
     * @access public
     */
    public $realRouteInfo = array();

    /**
     * APIV2 过滤前的原始 GET 参数。
     * The original GET params before filtering in APIV2.
     *
     * @var array
     * @access public
     */
    public $rawGet = array();

    /**
     * APIV2 路由中显式声明并写入 POST/PUT 请求体的数据。
     * The route-declared payload merged into POST/PUT requests in APIV2.
     *
     * @var array
     * @access public
     */
    public $routeData = array();

    /**
     * Workflow create actions need save step only for the final dispatch.
     *
     * @var bool
     * @access public
     */
    public $workflowSaveStep = false;

    /**
     * 内部执行 API 时注入的原始请求体。
     * Raw request body injected when executing API internally.
     *
     * @var string|null
     * @access public
     */
    public $requestBody = null;

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
     * 设置内部执行时的请求体。
     * Set raw request body for internal API execution.
     *
     * @param  string|null $requestBody
     * @access public
     * @return void
     */
    public function setRequestBody(?string $requestBody): void
    {
        $this->requestBody = $requestBody;
    }

    /**
     * 获取请求体。内部执行时优先使用注入值，否则读取 php://input。
     * Get request body. Use injected value for internal execution first, otherwise read php://input.
     *
     * @access protected
     * @return string
     */
    protected function getRequestBody(): string
    {
        if($this->requestBody !== null) return $this->requestBody;

        $requestBody = file_get_contents('php://input');
        return $requestBody === false ? '' : $requestBody;
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

            $httpActions = array('get', 'post', 'put', 'delete', 'options');
            $routeActions = is_array($info) ? array_intersect($httpActions, array_keys($info)) : array();

            /*
             * Routes without explicit method declarations are treated as GET-only.
             * This avoids POST/PUT/DELETE requests being swallowed by generic resource redirects.
             */
            if(empty($routeActions) && $this->action != 'get') continue;

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
        $this->routeData = array();

        list($info, $paramValues) = $this->matchRoutes($routes);
        $originParamValues = $paramValues;
        $httpActions = array('get', 'post', 'put', 'delete', 'options');
        if($info && array_intersect($httpActions, array_keys($info))) $info = zget($info, $this->action, array());

        $this->originRouteInfo = $info ?: array();
        $this->routeInfo       = $this->originRouteInfo;
        $this->realRouteInfo   = $this->originRouteInfo;
        if($info && isset($info['data'])) $this->routeData = $this->parseRouteData((string) $info['data'], $originParamValues);

        if($info)
        {
            if(isset($info['method'])) $methodName = $info['method'];

            if(isset($info['redirect']))
            {
                foreach($paramValues as $key => $value)
                {
                    if(is_numeric($key)) continue;

                    $_GET[$key] = $value;
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

                $this->realRouteInfo = $info ?: array();
            }
            else
            {
                $this->realRouteInfo = $info ?: array();
            }

            if(isset($info['response']) && $this->responseExtractor == '*') $this->responseExtractor = $info['response'];
        }

        foreach($paramValues as $key => $value)
        {
            if(is_numeric($key)) continue;
            $_GET[$key] = $value;
        }

        $this->resolvedDefaultParams = null;

        return $methodName;
    }

    /**
     * APIV2请求是否带有搜索条件。
     * Whether the APIV2 request contains search filters.
     *
     * @access protected
     * @return bool
     */
    protected function hasSearchFilters(): bool
    {
        return isset($_GET['filters']);
    }

    /**
     * 返回APIV2错误响应。
     * Send APIV2 error response.
     *
     * @param  string $message
     * @access protected
     * @return void
     */
    protected function sendV2Error(string $message): void
    {
        header('Content-Type: application/json');
        throw EndResponseException::create(helper::removeUTF8Bom(json_encode(array('status' => 'fail', 'message' => $message), JSON_UNESCAPED_UNICODE)));
    }

    /**
     * 获取 APIV2 原始路由的搜索元数据。
     * Resolve search metadata for origin APIV2 route.
     *
     * @access protected
     * @return array
     */
    protected function getOriginRouteSearch(): array
    {
        $search = zget($this->originRouteInfo, 'search', array());
        if(empty($search['enabled'])) return array();

        return $search;
    }

    /**
     * 解析真实落点的搜索模块。
     * Resolve search module for real handler.
     *
     * @param  array $routeSearch
     * @access protected
     * @return string
     */
    protected function resolveSearchModule(array $routeSearch): string
    {
        return zget($routeSearch, 'searchModule', $this->moduleName);
    }

    /**
     * 解析真实落点的搜索 session key。
     * Resolve query session key for real handler.
     *
     * @param  array  $routeSearch
     * @access protected
     * @return string
     */
    protected function resolveQuerySessionKey(array $routeSearch): string
    {
        $searchModule = $this->resolveSearchModule($routeSearch);
        return zget($routeSearch, 'querySessionKey', $searchModule);
    }
    /**
     * 从已准备的搜索参数构造搜索配置。
     * Build search config from prepared search params.
     *
     * @param  string      $searchModule
     * @param  string      $querySessionKey
     * @param  string|null $configModule
     * @access protected
     * @return array
     */
    protected function buildPreparedSearchConfig(string $searchModule, string $querySessionKey, ?string $configModule = null): array
    {
        $configModule = $configModule ?: $searchModule;

        $this->loadConfig('search');
        $this->loadLang('search');
        $this->loadLang($configModule);
        $this->loadConfig($configModule);

        /*
         * 不使用 session 中的搜索参数：同一模块下不同列表（如 product 产品列表与需求列表）
         * 会复用同一个 searchParams 键名，导致搜索字段被上一个列表的配置覆盖。
         * 以当前路由对应模块的静态配置为准，控制器执行时仍会重新构建并覆盖 session。
         * Do not reuse session search params: different lists of the same module share one key,
         * so stale params from a previous list would override the current route's search fields.
         */
        $moduleConfig = zget($this->config, $configModule, null);
        $searchParams = $moduleConfig->search ?? array();
        if(empty($searchParams)) $this->sendV2Error('Search config is not available for this route.');

        $searchParams['module']  = $querySessionKey;
        $searchParams['queryID'] = 0;

        return $searchParams;
    }

    /**
     * 规范化搜索条件。
     * Normalize search filters.
     *
     * @param  mixed  $filters
     * @param  array  $searchFields
     * @access protected
     * @return array
     */
    protected function normalizeSearchFilters(mixed $filters, array $searchFields): array
    {
        if(is_array($filters) && isset($filters['field'])) $filters = array($filters);
        if(!is_array($filters) || empty($filters)) $this->sendV2Error('Filters must be a non-empty array.');

        $operators  = array_keys($this->lang->search->operators);
        $groupItems = max(1, (int)zget($this->config->search, 'groupItems', 3));
        $maxGroups  = 2;
        $maxTotal   = $groupItems * $maxGroups;
        $groupCount = array(1 => 0, 2 => 0);
        $normalized = array();

        foreach($filters as $filter)
        {
            if(!is_array($filter)) $this->sendV2Error('Each filter must be an object-like array.');

            $field    = (string)zget($filter, 'field', '');
            $operator = (string)zget($filter, 'operator', '');
            $join     = strtolower((string)zget($filter, 'join', 'and'));
            $group    = (int)zget($filter, 'group', 1);

            if($field === '' || !isset($searchFields[$field])) $this->sendV2Error("Unsupported search field: {$field}.");
            if(!in_array($operator, $operators))               $this->sendV2Error("Unsupported search operator: {$operator}.");
            if(!in_array($join, array('and', 'or')))           $this->sendV2Error("Unsupported search join: {$join}.");
            if($group < 1 || $group > $maxGroups)              $this->sendV2Error('Search group must be 1 or 2.');
            if(!array_key_exists('value', $filter))            $this->sendV2Error("Search filter {$field} is missing value.");

            $value = $filter['value'];
            if(is_array($value))
            {
                $value = implode(',', array_map('strval', $value));
            }
            elseif(is_bool($value))
            {
                $value = $value ? '1' : '0';
            }
            elseif(is_scalar($value) || $value === null)
            {
                $value = $value === null ? '' : (string)$value;
            }
            else
            {
                $this->sendV2Error("Search filter {$field} has invalid value.");
            }

            $groupCount[$group]++;
            if($groupCount[$group] > $groupItems) $this->sendV2Error("Search group {$group} exceeds the limit of {$groupItems} filters.");

            $normalized[] = array(
                'field'    => $field,
                'operator' => $operator,
                'join'     => $join,
                'group'    => $group,
                'value'    => $value
            );
        }

        if(count($normalized) > $maxTotal) $this->sendV2Error("Filters exceed the limit of {$maxTotal} conditions.");

        return $normalized;
    }

    /**
     * 组装搜索表单参数。
     * Build search form payload.
     *
     * @param  array  $filters
     * @param  string $querySessionKey
     * @access protected
     * @return array
     */
    protected function buildSearchFormPayload(array $filters, string $querySessionKey): array
    {
        $groupItems = max(1, (int)zget($this->config->search, 'groupItems', 3));
        $groupJoin  = strtolower((string)zget($_GET, 'groupJoin', 'and'));
        if(!in_array($groupJoin, array('and', 'or'))) $this->sendV2Error("Unsupported group join: {$groupJoin}.");

        $payload = array('module' => $querySessionKey, 'groupAndOr' => $groupJoin);
        $indexes = array(1 => 1, 2 => $groupItems + 1);

        foreach($filters as $filter)
        {
            $index = $indexes[$filter['group']]++;
            $payload["field{$index}"]    = $filter['field'];
            $payload["operator{$index}"] = $filter['operator'];
            $payload["value{$index}"]    = $filter['value'];
            $payload["andOr{$index}"]    = $filter['join'];
        }

        return $payload;
    }

    /**
     * 为APIV2列表请求准备搜索session和兼容参数。
     * Prepare search session and internal params for APIV2 list requests.
     *
     * @access protected
     * @return void
     */
    protected function prepareV2Search(): void
    {
        if($this->action != 'get' || !$this->hasSearchFilters()) return;

        $routeSearch = $this->getOriginRouteSearch();
        if(empty($routeSearch)) $this->sendV2Error('Filters are not supported for this route.');

        global $lang;
        if(!isset($lang->search) || !isset($lang->search->operators))
        {
            $searchLangFile = $this->appRoot . 'module/search/lang/zh-cn.php';
            if(is_file($searchLangFile)) include $searchLangFile;
        }
        $this->loadConfig('search');

        $searchConfig      = $this->prepareSearchContext($routeSearch);
        $filters           = $this->normalizeSearchFilters($_GET['filters'], $searchConfig['fields'] ?? array());
        $searchFormPayload = $this->buildSearchFormPayload($filters, $searchConfig['module']);

        if(!isset($this->control)) $this->resolveDefaultParams();
        $searchModel = $this->control->loadModel('search');
        $_POST = $searchFormPayload;
        $searchModel->setSearchParams($searchConfig);
        $searchModel->buildQuery();

        $_GET['browseType'] = 'bysearch';
        $_GET['param']      = 0;
        if($this->moduleName == 'company' && $this->methodName == 'browse') $_GET['type'] = 'bysearch';
    }

    /**
     * 轻量解析搜索上下文，只确定 session key 和搜索字段来源。
     * Lightweight search context resolution without duplicating controller logic.
     *
     * @param  array $routeSearch
     * @access protected
     * @return array
     */
    protected function prepareSearchContext(array $routeSearch): array
    {
        $searchModule    = $this->resolveSearchModule($routeSearch);
        $querySessionKey = $this->resolveQuerySessionKey($routeSearch);
        $rawModule       = zget($this->originRouteInfo, 'rawModule', $this->moduleName);
        $rawMethod       = zget($this->originRouteInfo, 'rawMethod', $this->methodName);
        $configModule    = $this->resolveSearchConfigModule($searchModule, $rawModule, $rawMethod);

        /* 产品需求/业务需求/用户需求列表的搜索 session 使用 storyType。 */
        if($this->moduleName == 'product' && $this->methodName == 'browse')
        {
            $querySessionKey = (string)zget($_GET, 'storyType', 'story');
        }

        global $lang;
        if(!isset($lang->{$configModule}) || !is_object($lang->{$configModule})) $lang->{$configModule} = new stdclass();
        $langFiles = array(
            $this->appRoot . "module/{$configModule}/lang/{$this->clientLang}.php",
            $this->appRoot . "extension/max/{$configModule}/lang/{$this->clientLang}.php",
            $this->appRoot . "extension/ipd/{$configModule}/lang/{$this->clientLang}.php",
        );
        foreach($langFiles as $langFile)
        {
            if(is_file($langFile)) include $langFile;
        }
        foreach(array('max', 'ipd') as $edition)
        {
            $extLangFiles = glob($this->appRoot . "extension/{$edition}/{$configModule}/ext/lang/{$this->clientLang}/*.php");
            if($extLangFiles) foreach($extLangFiles as $extLangFile) include $extLangFile;
        }

        $this->loadConfig($configModule);

        if($configModule == 'company')
        {
            $searchConfig = $this->config->company->browse->search ?? array();
            if(empty($searchConfig)) $this->sendV2Error('Search config is not available for this route.');
            $searchConfig['module']  = $querySessionKey;
            $searchConfig['queryID'] = 0;
            if(empty($searchConfig['actionURL'])) $searchConfig['actionURL'] = helper::createLink($configModule, 'browse', 'browseType=bysearch&param=myQueryID');
            return $searchConfig;
        }

        $searchConfig = $this->buildPreparedSearchConfig($searchModule, $querySessionKey, $configModule);
        if(empty($searchConfig['actionURL'])) $searchConfig['actionURL'] = helper::createLink($configModule, 'browse', 'browseType=bysearch&param=myQueryID');
        return $searchConfig;
    }

    /**
     * 根据路由/模块映射搜索配置来源模块。
     * Resolve the module that provides search config for current route.
     *
     * @param  string $searchModule
     * @param  string $rawModule
     * @param  string $rawMethod
     * @access protected
     * @return string
     */
    protected function resolveSearchConfigModule(string $searchModule, string $rawModule, string $rawMethod): string
    {
        if($rawModule == 'my' && in_array($rawMethod, array('work', 'contribute')))
        {
            $modeMap = array(
                'task'        => 'execution',
                'bug'         => 'bug',
                'story'       => 'product',
                'epic'        => 'product',
                'requirement' => 'product',
                'testcase'    => 'testcase',
                'risk'        => 'risk',
                'reviewissue' => 'reviewissue',
                'feedback'    => 'feedback',
                'ticket'      => 'ticket',
            );
            $mode = (string)zget($this->originRouteInfo, 'mode', zget($_GET, 'mode', 'task'));
            return $modeMap[$mode] ?? $searchModule;
        }

        if($this->moduleName == 'product' && $this->methodName == 'browse') return 'product';
        if($this->moduleName == 'projectstory' || ($this->moduleName == 'execution' && $this->methodName == 'story')) return 'product';
        if($this->moduleName == 'company' && $this->methodName == 'browse') return 'company';

        $aliasMap = array(
            'projectBug'     => 'bug',
            'executionBug'   => 'bug',
            'projectBuild'   => 'build',
            'executionBuild' => 'build',
            'projectstory'   => 'product',
            'executionStory' => 'product',
            'projectrelease' => 'release',
            'executionCase'  => 'testcase',
        );

        return $aliasMap[$searchModule] ?? ($searchModule ?: $this->moduleName);
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

        $methodName = $this->parseRouteV2($routes);

        $pathItems  = explode('/', trim($this->path, '/'));

        /* Workflow apis. */
        $isWorkflowRequest = $this->originRouteInfo == array() && isset($pathItems[0]) && $pathItems[0] == 'workflow';
        if($isWorkflowRequest) return $this->handleWorkflowNamespaceRoute($pathItems);

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
                    $_GET[$moduleName . 'ID'] = $pathItems[1];
                }
            }
            else
            {
                $methodName = $pathItems[1];
            }
        }

        if(isset($pathItems[2])) $methodName = $pathItems[2];
        if(!$methodName) $methodName = $actionToMethod[$this->action];

        /* File is special. */
        if($moduleName == 'file' && $this->action == 'post')
        {
            $methodName         = 'ajaxUpload';
            $_GET['field']      = 'file';
            $_GET['objectType'] = zget($_POST, 'objectType', '');
            $_GET['objectID']   = zget($_POST, 'objectID', '');
        }

        if(isset($this->originRouteInfo['rawModule'])) $this->rawModule = (string)$this->originRouteInfo['rawModule'];
        if(isset($this->originRouteInfo['rawMethod'])) $this->rawMethod = (string)$this->originRouteInfo['rawMethod'];

        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
        $this->setControlFile();
        $this->prepareRedirectParams();

        $this->prepareV2Search();

        $this->prepareDeleteConfirmParam();
    }

    /**
     * 解析 workflow 命名空间路径。
     * Parse the workflow namespace path.
     *
     * workflow 命名空间下的模块编码应保持原样，不经过 singular() 转换。
     *
     * @param  array $pathItems
     * @access protected
     * @return array
     */
    protected function parseWorkflowNamespaceRoute(array $pathItems): array
    {
        $moduleName = zget($pathItems, 1, '');
        $actionMap  = array('get' => 'browse', 'post' => 'create', 'put' => 'edit', 'delete' => 'delete');
        $methodName = zget($actionMap, $this->action, 'browse');

        if(isset($pathItems[2]))
        {
            if(is_numeric($pathItems[2]))
            {
                $_GET['dataID'] = (int)$pathItems[2];
                if($this->action == 'get') $methodName = 'view';
            }
            else
            {
                $methodName = $pathItems[2];
            }
        }

        if(isset($pathItems[3])) $methodName = $pathItems[3];

        return array($moduleName, $methodName);
    }

    /**
     * 处理 workflow 命名空间路由。
     * Handle workflow namespace route.
     *
     * @param  array $pathItems
     * @access protected
     * @return void
     */
    protected function handleWorkflowNamespaceRoute(array $pathItems): void
    {
        list($moduleName, $methodName) = $this->parseWorkflowNamespaceRoute($pathItems);

        $this->rawModule = $moduleName;
        $this->rawMethod = $methodName;

        /* 工作流必须传step参数才能提交，setFormData不能提交，所以先标记一下，在最终真实POST时注入。 */
        if($this->action == 'post' && in_array($methodName, array('create', 'batchcreate'))) $this->workflowSaveStep = true;

        if($this->action == 'get' && $methodName == 'browse')
        {
            $this->responseExtractor = 'dataList(array),pager';
        }
        elseif($methodName == 'view')
        {
            $this->responseExtractor = 'data';
        }

        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
        $this->setControlFile();

        $this->prepareDeleteConfirmParam();
    }

    /**
     * Set confirm param for delete request when target method supports it.
     *
     * @access protected
     * @return void
     */
    protected function prepareDeleteConfirmParam(): void
    {
        if($this->action != 'delete') return;

        $defaultParams = $this->resolveDefaultParams();
        if(isset($defaultParams['confirm'])) $_GET['confirm'] = 'yes';
    }

    /**
     * Normalize redirect params according to the target control method signature.
     *
     * @access protected
     * @return void
     */
    protected function prepareRedirectParams(): void
    {
        if(empty($this->originRouteInfo['redirect'])) return;

        $defaultParams = $this->resolveDefaultParams();
        $typedParams   = $this->normalizeGetParams($defaultParams, $_GET);

        foreach($typedParams as $key => $value) $_GET[$key] = $value;
    }

    /**
     * Resolve and cache default params of current target control method.
     *
     * @access protected
     * @return array
     */
    protected function resolveDefaultParams(): array
    {
        if($this->resolvedDefaultParams !== null) return $this->resolvedDefaultParams;
        return $this->resolvedDefaultParams = $this->getDefaultParams();
    }

    /**
     * Validate that all required params declared by the target method are present.
     *
     * @param  array $defaultParams
     * @param  array $sourceParams
     * @access protected
     * @return void
     */
    protected function validateRequiredParams(array $defaultParams, array $sourceParams): void
    {
        foreach($defaultParams as $key => $defaultItem)
        {
            if($defaultItem['default'] !== '_NOT_SET') continue;
            if(array_key_exists($key, $sourceParams)) continue;

            $this->sendV2Error("Missing required parameter: {$key}.");
        }
    }

    /**
     * Normalize GET params according to current target control method signature.
     *
     * @param  array $defaultParams
     * @param  array $sourceParams
     * @access protected
     * @return array
     */
    protected function normalizeGetParams(array $defaultParams, array $sourceParams): array
    {
        $params = array();
        foreach($defaultParams as $key => $defaultItem)
        {
            if(isset($sourceParams[$key]))
            {
                $params[$key] = helper::convertType(strip_tags((string) $sourceParams[$key]), $defaultItem['type']);
            }
            else
            {
                $params[$key] = ($key == 'browseType' && $this->methodName == 'browse') ? 'all' : $defaultItem['default'];
            }
        }

        return $params;
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
     * Parse one route data template into a payload array.
     *
     * @param  string $data
     * @param  array  $paramValues
     * @access protected
     * @return array
     */
    protected function parseRouteData(string $data, array $paramValues): array
    {
        foreach($paramValues as $key => $value)
        {
            if(is_numeric($key)) continue;
            $data = str_replace(':' . $key, (string) $value, $data);
        }

        parse_str($data, $payload);
        return $payload;
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
     * 检查 APIV2 请求的权限。路由重定向改变控制器方法后，使用原始路由方法进行权限校验。
     * Check APIV2 privilege using the route's original method when a redirect changes the control method.
     *
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        [$module, $method] = $this->resolvePrivTarget();
        global $common;
        if($module and $method and !$common->isOpenMethod($module, $method) and !commonModel::hasPriv($module, $method))
        {
            throw EndResponseException::create(helper::response(array('error' => 'Access not allowed'), 403));
        }
    }

    /**
     * 解析权限校验目标。路由重定向后的控制方法用于实际执行，原始方法用于权限判断。
     * Resolve the privilege target before redirects change the control method.
     *
     * @access protected
     * @return array
     */
    protected function resolvePrivTarget(): array
    {
        $module = $this->getModuleName();
        $method = $this->getMethodName();

        if($module == 'my' && !empty($this->rawMethod) && $method != $this->rawMethod)
        {
            $method = $this->rawMethod;
        }

        return array($module, $method);
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
        if($this->user->admin) return true;

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
            case TABLE_TESTTASK:
                $projects = ",{$userView->sprints},{$userView->projects},";
                return (!$object->product || strpos(",{$userView->products},", ",$object->product,") !== false)
                    && (!$object->project || strpos($projects, ",$object->project,") !== false)
                    && (!$object->execution || strpos(",{$userView->sprints},", ",$object->execution,") !== false);
            default:
                return true;
        }

        return false;
    }

    /**
     * 检查传入的对象是否可以访问
     *
     * Check access.
     *
     * @access public
     * @return void
     */
    public function checkAccess()
    {
        $objectMap = [
            'program'       => TABLE_PROJECT,
            'programID'     => TABLE_PROJECT,
            'product'       => TABLE_PRODUCT,
            'products'      => TABLE_PRODUCT,
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
            'testtask'      => TABLE_TESTTASK,
            'testtaskID'    => TABLE_TESTTASK,
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
            if(!isset($objectMap[$key]) || !$value) continue;

            $table  = $objectMap[$key];
            $result = $this->checkObjectExists($table, $value);

            if($result === false) return $this->control->sendError(ucfirst(str_replace('ID', '', $key)) . ' does not exist.');

            foreach($result as $object)
            {
                if(!$this->checkObjectPriv($object, $table)) return $this->control->sendError(ucfirst(str_replace('ID', '', $key)) . ' is not allowed.');
            }
        }
    }

    /**
     * 检查对象是否存在
     *
     * Check object exists.
     *
     * @param  string $table
     * @param  int    $objectID
     * @access public
     * @return array|false
     */
    public function checkObjectExists($table, $objectIDList)
    {
        if(!is_array($objectIDList)) $objectIDList = [$objectIDList];

        $objects = [];

        foreach($objectIDList as $objectID)
        {
            $object = $this->dao->select('*')->from($table)
                ->where('id')->eq($objectID)
                ->beginIF(!in_array($table, [TABLE_DEPT]))->andWhere('deleted')->eq('0')->fi()
                ->fetch();
            if(!$object) return false;

            $objects[] = $object;
        }

        return $objects;
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
                return $this->prepareV2Module();
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
     * 直接执行 APIv2 模块，不捕获 EndResponseException。
     * Execute APIv2 module directly without catching EndResponseException.
     *
     * @access public
     * @return void
     */
    public function loadModuleForInternal()
    {
        return $this->prepareV2Module();
    }

    /**
     * 准备并执行 APIv2 模块。
     * Prepare and execute APIv2 module.
     *
     * @access protected
     * @return mixed
     */
    protected function prepareV2Module()
    {
        $this->setParams();

        if(in_array($this->action, array('post', 'put', 'delete')))
        {
            $this->setFormData();
        }
        else
        {
            $this->checkAccess();
        }

        if($this->workflowSaveStep && in_array($this->methodName, array('create', 'batchcreate'))) $this->params['step'] = 'save';

        return parent::loadModule();
    }

    /**
     * 加载模型，供 APIv2 搜索上下文使用。
     * Load model for APIv2 search context.
     *
     * @param  string $moduleName
     * @param  string $appName
     * @access public
     * @return object|bool
     */
    public function loadModel(string $moduleName, string $appName = ''): object|bool
    {
        if(!isset($this->control)) $this->resolveDefaultParams();

        $model = $this->control->loadModel($moduleName, $appName);
        if($appName === '' && $model) $this->{$moduleName} = $model;

        return $model;
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
        $requestBody = $this->getRequestBody();
        $postData = json_decode($requestBody, true);
        $_POST    = is_array($postData) ? $postData : array();
        $this->normalizeBatchPostData();

        /* Avoid empty post body. */
        $_POST['uid'] = '1';

        /*
         * API 没有真实密码，只有 user/my 等密码相关逻辑需要 verifyPassword。
         * API has no real password; only password-related logic such as user/my needs verifyPassword.
         */
        if(in_array($this->control->moduleName, ['user', 'my']))
        {
            $_POST['verifyPassword'] = '1';
        }

        $this->mergeRouteParamsToPost();

        /* 以POST的值为准。 Set GET value from POST data. */
        foreach($_POST as $key => $value)
        {
            if(isset($this->params[$key])) $this->params[$key] = $value;
        }
        $this->normalizeRouteParamsAfterPostMerge();

        $this->checkAccess();

        /* 其他方法不需要从GET页面获取post data。Other request directly. */
        if(!in_array($this->methodName, ['create', 'edit', 'change'])) return;

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
        $this->mergeRouteParamsToPost();
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

        $this->mergeWorkflowFields();
    }

    /**
     * 为工作流更新请求补齐未提交的字段值。
     * Merge missing workflow fields from current record for partial update requests.
     *
     * @access protected
     * @return void
     */
    protected function mergeWorkflowFields(): void
    {
        if($this->apiVersion != 'v2') return;
        if($this->action != 'put') return;
        if(empty($this->rawModule) || empty($this->rawMethod)) return;
        if($this->methodName != 'edit') return;
        if(!isset($_GET['dataID']) || !is_numeric($_GET['dataID'])) return;
        if($this->config->edition == 'open') return;

        $flow = $this->loadModel('workflow', 'flow')->getByModule($this->rawModule);
        if(empty($flow) || empty($flow->table)) return;

        $action = $this->loadModel('workflowaction', 'flow')->getByModuleAndAction($flow->module, $this->rawMethod);
        if(empty($action) || $action->extensionType != 'override') return;

        $fieldControls = $this->loadModel('workflowfield', 'flow')->getControlPairs($flow->module);
        if(empty($fieldControls)) return;

        $currentData = $this->loadModel('flow', 'flow')->getDataByID($flow, (int)$_GET['dataID']);
        if(empty($currentData)) return;

        $_POST = $this->mergeWorkflowMissingFields($_POST, $currentData, $fieldControls);
    }

    /**
     * 用当前记录值回填工作流请求中缺失的字段。
     * Merge workflow missing fields with current data.
     *
     * @param  array  $postData
     * @param  object $currentData
     * @param  array  $fieldControls
     * @access protected
     * @return array
     */
    protected function mergeWorkflowMissingFields(array $postData, object $currentData, array $fieldControls): array
    {
        foreach($fieldControls as $field => $control)
        {
            if(array_key_exists($field, $postData)) continue;
            if(!isset($currentData->$field)) continue;
            if(in_array($control, array('file'))) continue;

            $value = $currentData->$field;
            if(($control == 'multi-select' || $control == 'checkbox') && !is_array($value))
            {
                $value = strlen((string)$value) ? explode(',', (string)$value) : array();
            }

            $postData[$field] = $value;
        }

        return $postData;
    }

    /**
     * 合并 APIV2 路由和 redirect 参数到 POST。
     * Merge APIV2 route and redirect params into POST.
     *
     * @access protected
     * @return void
     */
    protected function mergeRouteParamsToPost(): void
    {
        if(in_array($this->action, array('post', 'put')))
        {
            foreach($this->routeData as $key => $value)
            {
                if(!isset($_POST[$key])) $_POST[$key] = $value;
            }
        }
    }

    /**
     * 将按行组织的批量 JSON 请求体标准化为按列组织的表单数组。
     *
     * @access protected
     * @return void
     */
    protected function normalizeBatchPostData(): void
    {
        if(empty($_POST) || !array_is_list($_POST)) return;

        $formConfig = $this->getCurrentFormConfig();
        if(empty($formConfig) || !is_array($formConfig)) return;

        $baseFields = array_filter($formConfig, function($config)
        {
            return is_array($config) && !empty($config['base']);
        });
        if(empty($baseFields)) return;

        $_POST = $this->transposeBatchPostRows($_POST);
    }

    /**
     * 将按行组织的批量 JSON 数据转置为按字段组织的列式数组。
     *
     * @param  array $rows
     * @access protected
     * @return array
     */
    protected function transposeBatchPostRows(array $rows): array
    {
        $fieldNames = array();
        foreach($rows as $row)
        {
            if(is_object($row)) $row = (array)$row;
            if(!is_array($row)) return $rows;
            $fieldNames = array_unique(array_merge($fieldNames, array_keys($row)));
        }

        $columns = array();
        foreach($rows as $rowIndex => $row)
        {
            $row = (array)$row;
            foreach($fieldNames as $field)
            {
                $columns[$field][$rowIndex] = array_key_exists($field, $row) ? $row[$field] : null;
            }
        }

        return $columns;
    }

    /**
     * 按当前模块和方法获取表单配置，方法名匹配时忽略大小写。
     *
     * @access protected
     * @return array|null
     */
    protected function getCurrentFormConfig(): ?array
    {
        $moduleConfig = zget($this->config, $this->moduleName, null);
        if(empty($moduleConfig) || empty($moduleConfig->form) || !is_object($moduleConfig->form)) return null;

        if(isset($moduleConfig->form->{$this->methodName})) return $moduleConfig->form->{$this->methodName};

        foreach(get_object_vars($moduleConfig->form) as $methodName => $formConfig)
        {
            if(strtolower($methodName) !== $this->methodName) continue;
            return $formConfig;
        }

        return null;
    }

    /**
     * 在 POST 数据覆盖路由参数后，按目标方法签名重新标准化当前参数类型。
     *
     * @access protected
     * @return void
     */
    protected function normalizeRouteParamsAfterPostMerge(): void
    {
        $defaultParams = $this->resolveDefaultParams();
        foreach($defaultParams as $key => $defaultItem)
        {
            if(!array_key_exists($key, $this->params)) continue;
            $this->params[$key] = helper::convertType($this->params[$key], $defaultItem['type']);
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
        $defaultParams = $this->resolveDefaultParams();
        $this->rawGet = $_GET;
        $sourceParams = $_GET;

        /* APIV2 POST/PUT requests may provide route parameters in the JSON body. */
        if(in_array($this->action, array('post', 'put')))
        {
            $requestBody = json_decode($this->getRequestBody(), true);
            if(is_array($requestBody)) $sourceParams = array_merge($sourceParams, $requestBody);
        }

        $this->validateRequiredParams($defaultParams, $sourceParams);
        $this->params = $this->normalizeGetParams($defaultParams, $sourceParams);

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
