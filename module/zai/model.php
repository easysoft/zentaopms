<?php
/**
 * The model file of zai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      tenghuaian <tenghuaian@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
class zaiModel extends model
{
    /**
     * 获取当前用户ZAI Authorization Token。
     * Get ZAI Authorization Token of current user.
     *
     * @access public
     * @param object|null $zaiConfig
     * @param bool $admin
     * @return array
     */
    public function getToken($zaiConfig = null, $admin = false)
    {
        $zaiConfig = $zaiConfig ? $zaiConfig : $this->getSetting($admin);
        if(!isset($zaiConfig->token) || !isset($zaiConfig->appID) || ($admin && !isset($zaiConfig->adminToken)))
        {
            return array(
                'result'  => 'fail',
                'message' => $this->lang->zai->configurationUnavailable
            );
        }

        if(!$this->loadModel('user')->isLogon())
        {
            return array(
                'result' => 'fail',
                'message' => $this->lang->zai->illegalZentaoUser
            );
        }

        $token       = $admin ? $zaiConfig->adminToken : $zaiConfig->token;
        $userID      = $this->app->user->account;
        $zaiTokenTTL = isset($zaiConfig->zaiTokenTTL) ? $zaiConfig->zaiTokenTTL : 1200;
        $expiredTime = time() + $zaiTokenTTL;
        $origin      = $token . $zaiConfig->appID . $userID . $expiredTime;
        $hash        = md5($origin);

        return array(
            'result' => 'success',
            'data'   => array(
                'hash'        => $hash,
                'expiredTime' => $expiredTime,
                'appID'       => $zaiConfig->appID,
                'userID'      => $userID
            )
        );
    }

    /**
     * 格式化旧的ZAI设置。
     * Format old ZAI settings.
     *
     * @access public
     * @param object|null $setting
     * @return object|null
     */
    public function formatOldSetting($setting)
    {
        if(empty($setting)) return null;
        if(empty($setting->host) && !empty($setting->apiBaseUrl))
        {
            $apiBaseUrl = str_replace('///', '', $setting->apiBaseUrl);
            $apiBaseUrl = str_replace('http://', '', $apiBaseUrl);
            $apiBaseUrl = str_replace('https://', '', $apiBaseUrl);
            $apiBaseUrl = str_replace('/v1', '', $apiBaseUrl);
            $urlParts = explode(':', $apiBaseUrl);
            $setting->host = $urlParts[0];
            $setting->port = isset($urlParts[1]) ? $urlParts[1] : 0;
        }
        if(empty($setting->token) && !empty($setting->appToken))
        {
            $setting->token = $setting->appToken;
            unset($setting->appToken);
        }
        return $setting;
    }

    /**
     * 获取ZAI设置。
     * Get ZAI settings.
     *
     * @access public
     * @param bool $includeAdmin
     * @return object|null
     */
    public function getSetting($includeAdmin = false)
    {
        $settingJson =  $this->loadModel('setting')->getItem("owner=system&module=zai&section=global&key=setting");
        $setting = json_decode($settingJson);
        if(!is_object($setting) && isset($this->config->zai))
        {
            $setting = $this->formatOldSetting($this->config->zai);
        }

        if(empty($setting) || empty($setting->host) || empty($setting->appID) || empty($setting->token))
        {
            return null;
        }

        $vectorizedInfo = $this->getVectorizedInfo();
        if(!empty($vectorizedInfo->key))
        {
            $setting->globalMemory = $vectorizedInfo->key;
        }

        if(!$includeAdmin) unset($setting->adminToken);

        return $setting;
    }

    /**
     * 设置ZAI设置。
     * Set ZAI settings.
     *
     * @access public
     * @param object|null $setting
     */
    public function setSetting($setting)
    {
        if(!is_object($setting))
        {
            $this->loadModel('setting')->setItem('system.zai.global.setting', '');
            return;
        }
        $this->loadModel('setting')->setItem('system.zai.global.setting', empty($setting) ? '' : json_encode($setting));
    }

    /**
     * 获取禅道数据向量化信息。
     * Get information of vectorized data of ZenTao.
     *
     * @access public
     * @return object
     */
    public function getVectorizedInfo()
    {
        $infoJson = $this->loadModel('setting')->getItem("owner=system&module=zai&section=kb&key=systemVectorization");
        $info     = json_decode($infoJson);
        if(!$info)
        {
            $info = new stdClass();
            $info->key             = '';
            $info->status          = 'disabled';
            $info->syncedTime      = 0;
            $info->syncedCount     = 0;
            $info->syncFailedCount = 0;
            $info->syncTime        = 0;
            $info->syncingType     = zaiModel::getNextSyncType();
            $info->syncingID       = 0;
            $info->syncDetails     = new stdClass();
            $info->createdAt       = time();
            $info->createdBy       = $this->app->user->account;
        }
        return $info;
    }

    /**
     * 设置禅道数据向量化信息。
     * Set information of vectorized data of ZenTao.
     *
     * @access public
     * @param object $info  - The information of vectorized data. 禅道数据向量化信息对象。
     */
    public function setVectorizedInfo($info)
    {
        if(!is_string($info)) $info = json_encode($info);
        $this->loadModel('setting')->setItem('system.zai.kb.systemVectorization', $info);
    }

    /**
     * 调用 ZAI API。
     * Call ZAI API.
     *
     * @access public
     * @param string $path
     * @param string $method
     * @param array|null $params
     * @param array|null $postData
     * @param bool $admin
     * @return array
     */
    public function callAPI($path, $method = 'POST', $params = null, $postData = null, $admin = false)
    {
        $setting = $this->getSetting($admin);
        if(empty($setting)) return array('result' => 'failed', 'message' => $this->lang->zai->configurationUnavailable);

        $tokenInfo = $this->getToken($setting, $admin);
        if($tokenInfo['result'] != 'success') return $tokenInfo;

        $tokenData = array('hash' => $tokenInfo['data']['hash'], 'expired_time' => $tokenInfo['data']['expiredTime'], 'app_id' => $tokenInfo['data']['appID'], 'user_id' => $tokenInfo['data']['userID']);
        $token = ($admin ? 'ak-' : 'ek-') . base64_encode(json_encode($tokenData));

        /* Check if the request is HTTPS. */
        if($path[0] != '/') $path = '/' . $path;
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        if(!$isHttps) $isHttps = !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';
        if(!$isHttps) $isHttps = !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443;

        $protocol = $isHttps ? 'https://' : 'http://';
        $host     = $setting->host;
        $port     = $setting->port;
        $url      = $protocol . $host . ($port ? ":$port" : '') . $path;
        if($params) $url = $url . '?' . http_build_query($params);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if($method === 'GET')
        {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        }
        else
        {
            curl_setopt($curl, CURLOPT_POST, true);
            if($method !== 'POST')
            {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            }
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_UNICODE));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ));

        $response = curl_exec($curl);
        $error    = '';
        $data     = null;
        $info     = curl_getinfo($curl);
        $code     = isset($info['http_code']) ? $info['http_code'] : curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl))
        {
            $error = curl_error($curl);
        }
        else
        {
            $headerSize   = isset($info['header_size']) ? $info['header_size'] : curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $data = substr($response, $headerSize);
        }

        curl_close($curl);

        if($code == 404) return array('result' => 'failed', 'data' => null, 'message' => $this->lang->notFound, 'code' => $code);
        if($code == 401) return array('result' => 'failed', 'data' => null, 'message' => $this->lang->zai->authenticationFailed, 'code' => $code);

        if($error || $code != 200)
        {
            return array('result' => 'failed', 'data' => $data, 'code' => $code, 'postData' => $postData, 'message' => sprintf($this->lang->zai->callZaiAPIFailed, $url, ($this->app->config->debug ? $error : '') . "(code: $code, response: $response)"));
        }

        if(empty($data))     $data = $response;
        if(is_string($data)) $data = json_decode($data, true);
        return array('result' => 'success', 'data' => $data);
    }

    /**
     * 调用ZAI管理API。
     * Call ZAI admin API.
     *
     * @access public
     * @param string $path
     * @param string $method
     * @param array|null $params
     * @param array|null $postData
     * @return array
     */
    public function callAdminAPI($path, $method = 'POST', $params = null, $postData = null)
    {
        return $this->callAPI($path, $method, $params, $postData, true);
    }

    /**
     * 启用数据向量化。
     * Enable data vectorization.
     *
     * @access public
     * @return array
     */
    public function enableVectorization($force = false)
    {
        $info = $this->getVectorizedInfo();
        if($info->status != 'disabled' && !$force) return array('result' => 'failed', 'message' => $this->lang->zai->vectorizedAlreadyEnabled, 'info' => $info);

        $suffix = '_' . time();
        $postData = array('name' => 'zentao' . $suffix, 'description' => $this->app->lang->zai->zentaoVectorization);

        $result = $this->callAdminAPI('/v8/memories', 'POST', null, $postData);

        if($result['result'] != 'success') return $result;

        if(empty($result['data']['id'])) return array('result' => 'failed', 'message' => $this->lang->zai->vectorizedFailed);

        $info->status          = 'wait';
        $info->key             = $result['data']['id'];
        $info->syncedTime      = 0;
        $info->syncedCount     = 0;
        $info->syncFailedCount = 0;
        $info->syncTime        = 0;
        $info->syncingType     = zaiModel::getNextSyncType();
        $info->syncingID       = 0;
        $info->syncDetails     = new stdClass();
        $this->setVectorizedInfo($info);

        return array('result' => 'success', 'info' => $info, 'data' => $result['data'], 'message' => $this->lang->zai->vectorizedEnabled);
    }

    /**
     * 获取下一个指定类型的对象。
     * 从数据库查询对应 $syncingType 的下一个待同步的对象，即 ID 大于 $syncingID 的且没有被删除的下一个对象。如果未查询到，则返回 null。
     *
     * @access public
     * @param string $type
     * @param int $id
     * @return object|null
     */
    public function getNextTarget($type, $id)
    {
        if(!isset(static::$syncTables[$type])) return null;

        $table = static::$syncTables[$type];
        $target = $this->dao->select('*')->from($table)->where('id')->ge($id)->andWhere('deleted')->eq(0)->orderby('id_asc')->fetch();
        if(!$target) return null;

        return $target;
    }

    /**
     * 同步下一个目标。
     * Sync next target.
     *
     * @access public
     * @param string $memoryID 知识库 ID。The ID of knowledge base.
     * @param string $type
     * @param int $id
     * @return array|null
     */
    public function syncNextTarget($memoryID, $type, $id)
    {
        $target = $this->getNextTarget($type, $id);
        if(!$target) return null;

        $markdownData = static::convertTargetToMarkdown($type, $target);
        $syncData     = array();

        $syncData['content']      = $markdownData['content'];
        $syncData['content_type'] = 'markdown';
        $syncData['key']          = "$type-$target->id";
        $syncData['attrs']        = array('objectType' => $type, 'objectID' => $target->id);

        if(isset($markdownData['attrs'])) $syncData['attrs'] = array_merge($syncData['attrs'], $markdownData['attrs']);

        $result = $this->callAdminAPI("/v8/memories/$memoryID/contents", 'POST', null, $syncData);
        if($result['result'] != 'success')
        {
            $code = isset($result['code']) ? $result['code'] : 0;

            /* 当指定 memoryID 的知识库不存在时创建一个新的。 */
            /* If kb with the given memoryID not exists, then create a new  one. */
            if($code == 412)
            {
                $result['message'] = $this->lang->zai->cannotFindMemoryInZai;
                $result['code']    = 'cannotFindMemoryInZai';
            }

            if($code !== 100 && ($code >= 400 || $code <= 500)) $result['fatal'] = true;
            return $result;
        }

        return array('result' => 'success', 'target' => $target, 'id' => $target->id, 'data' => $result['data']);
    }

    /**
     * 搜索知识库。
     * Search knowledge base.
     */
    public function searchKnowledges(string $query, string $collection, array $filter, int $limit = 20, float $minSimilarity = 0.8): array
    {
        $postData = array();
        $postData['query']          = $query;
        $postData['limit']          = $limit;
        $postData['min_similarity'] = $minSimilarity;
        $postData['content_filter'] = $filter;

        $result = $this->callAdminAPI('/v8/memories/' . $collection . '/embeddings-search-contents', 'POST', null, $postData);

        if($result['result'] != 'success') return array();
        return $result['data'];
    }

    /**
     * 判断用户是否可以查看对象。
     * Check if user can view object.
     */
    public function isCanViewObject(string $objectType, int $objectID, ?array $attrs = null): bool
    {
        if(isset(static::$objectViews[$objectType][$objectID])) return static::$objectViews[$objectType][$objectID];

        if(!isset(static::$objectViews[$objectType])) static::$objectViews[$objectType] = array();

        if($attrs === null) $attrs = array();
        $canView = false;
        if($objectType === 'story' || $objectType === 'demand')
        {
            $table   = $objectType === 'story' ? TABLE_STORY : TABLE_DEMAND;
            $product = isset($attrs['product']) ? $attrs['product'] : 0;
            if(!$product) $product = $this->dao->select('product')->from($table)->where('id')->eq($objectID)->fetch('product');
            $canView = strpos(',' . $this->app->user->view->products . ',', ",$product,") !== false;
        }
        elseif($objectType === 'bug' || $objectType === 'case')
        {
            $table   = $objectType === 'bug' ? TABLE_BUG : TABLE_CASE;
            $product = isset($attrs['product']) ? $attrs['product'] : 0;
            if(!$product) $product = $this->dao->select('product')->from($table)->where('id')->eq($objectID)->fetch('product');
            $canView = strpos(',' . $this->app->user->view->products . ',', ",$product,") !== false;
            if(!$canView)
            {
                $project = isset($attrs['project']) ? $attrs['project'] : 0;
                if(!$project) $project = $this->dao->select('project')->from($table)->where('id')->eq($objectID)->fetch('project');
                $canView = strpos(',' . $this->app->user->view->projects . ',', ",$project,") !== false;
            }
        }
        elseif($objectType === 'task')
        {
            $project = isset($attrs['project']) ? $attrs['project'] : 0;
            if(!$project) $project = $this->dao->select('project')->from(TABLE_TASK)->where('id')->eq($objectID)->fetch('project');
            $canView = strpos(',' . $this->app->user->view->projects . ',', ",$project,") !== false;
        }
        elseif($objectType === 'feedback')
        {
            $product = isset($attrs['product']) ? $attrs['product'] : 0;
            if(!$product) $product = $this->dao->select('product')->from(TABLE_FEEDBACK)->where('id')->eq($objectID)->fetch('product');
            $canView = strpos(',' . $this->app->user->view->products . ',', ",$product,") !== false;
        }
        elseif($objectType === 'doc')
        {
            $doc = $this->loadModel('doc')->getByID($objectID);
            $canView = $this->loadModel('doc')->checkPrivDoc($doc);
        }

        static::$objectViews[$objectType][$objectID] = $canView;
        return $canView;
    }

    /**
     * 用户对象可查看缓存配置。
     * User object view cache configuration.
     *
     * @access public
     * @var array
     */
    static $objectViews = array();

    /**
     * 同步表。
     * Sync tables.
     *
     * @access public
     * @var array
     */
    static $syncTables = array
    (
        'story'    => TABLE_STORY,
        'demand'   => TABLE_DEMAND,
        'bug'      => TABLE_BUG,
        'doc'      => TABLE_DOC,
        'design'   => TABLE_DESIGN,
        'feedback' => TABLE_FEEDBACK
    );

    /**
     * 获取可同步的类型。
     * Get syncable types.
     *
     * @access public
     * @return array
     */
    public static function getSyncTypes()
    {
        global $app, $config;
        $types = $app->lang->zai->syncingTypeList;
        if($config->edition == 'open') unset($types['feedback']);
        if($config->edition != 'ipd')  unset($types['demand']);
        return $types;
    }

    /**
     * 获取下一个同步类型。
     * Get next sync type.
     *
     * @access public
     * @param string $currentType
     * @return string 下一个同步类型。The next sync type.
     */
    public static function getNextSyncType($currentType = '')
    {
        global $app;
        $types = array_keys(zaiModel::getSyncTypes());
        if(empty($currentType)) return $types[0];

        $currentIndex = array_search($currentType, $types);
        if($currentIndex === false) return $types[0];

        return isset($types[$currentIndex + 1]) ? $types[$currentIndex + 1] : null;
    }


    /**
     * 将目标对象转换为 Markdown 格式。
     * Convert target object to Markdown format.
     *
     * @access public
     * @param string $type
     * @param object $target
     * @return array
     */
    public static function convertTargetToMarkdown($type, $target)
    {
        global $app;

        $funcName = 'convert' . ucfirst($type) . 'ToMarkdown';
        if(method_exists(static::class, $funcName))
        {
            $markdown = static::$funcName($target);
        }
        else
        {
            $markdown = array('content' => json_encode($target), 'id' => $target->id, 'title' => '');
            $typeName = zget($app->lang->zai->syncingTypeList, $type, $type);
            $title    = '';

            if(isset($target->title))     $title = $target->title;
            elseif(isset($target->name))  $title = $target->name;

            $markdown['title'] = "$typeName #$target->id $title";
        }

        if(!isset($markdown['attrs']))               $markdown['attrs'] = array();
        if(!isset($markdown['attrs']['objectType'])) $markdown['attrs']['objectType'] = $type;
        if(!isset($markdown['attrs']['objectID']))   $markdown['attrs']['objectID'] = $target->id;
        if(!isset($markdown['attrs']['objectKey']))  $markdown['attrs']['objectKey'] = $type . '-' . $target->id;

        return $markdown;
    }

    /**
     * 将 STORY 对象转换为 Markdown 格式。
     * Convert story object to Markdown format.
     *
     * @access public
     * @param object $story
     * @return array
     */
    public static function convertStoryToMarkdown($story)
    {
        global $app;

        $app->loadLang('story');
        $lang = $app->lang;

        $spec = $app->dao->select('title,spec,verify,files,docs,docVersions')->from(TABLE_STORYSPEC)->where('story')->eq($story->id)->andWhere('version')->eq($story->version)->fetch();
        if(empty($spec)) $spec = (object)array('title' => $story->title, 'spec' => '', 'verify' => '', 'files' => '', 'docs' => '', 'docVersions' => '');

        $markdown = array('id' => $story->id, 'title' => "$lang->SRCommon #$story->id $spec->title");
        $content  = array();
        $content[] = "# {$lang->SRCommon} #$story->id $story->title\n";
        $content[] = "## {$lang->story->legendBasicInfo}\n";
        $content[] = "* {$lang->story->status}: " . zget($lang->story->statusList, $story->status);
        $content[] = "* {$lang->story->stage}: " . zget($lang->story->stageList, $story->stage);
        $content[] = "* {$lang->story->pri}: " . zget($lang->story->priList, $story->pri);
        $content[] = "* {$lang->story->version}: $story->version";
        $content[] = "* {$lang->story->category}: " . zget($lang->story->categoryList, $story->category);
        $content[] = "* {$lang->story->source}: " . zget($lang->story->sourceList, $story->source);
        $content[] = "* {$lang->story->estimate}: $story->estimate";
        $content[] = "* {$lang->story->product}: $story->product";
        $content[] = "* {$lang->story->plan}: $story->plan";
        $content[] = "* {$lang->story->branch}: $story->branch";
        $content[] = "* {$lang->story->parent}: $story->parent";
        $content[] = "* {$lang->story->module}: $story->module";
        $content[] = "* {$lang->story->keywords}: $story->keywords";
        $content[] = "* {$lang->story->assign}: $story->assignedTo";
        $content[] = "* {$lang->story->assignedDate}: $story->assignedDate";
        $content[] = "* {$lang->story->reviewedDate}: $story->reviewedDate";
        $content[] = "* {$lang->story->reviewedBy}: $story->reviewedBy";
        $content[] = "* {$lang->story->openedBy}: $story->openedBy";
        $content[] = "* {$lang->story->openedDate}: $story->openedDate";
        $content[] = "* {$lang->story->stagedBy}: $story->stagedBy";
        $content[] = "\n## {$lang->story->spec}\n";
        $content[] = strip_tags($spec->spec) . "\n";
        $content[] = "## {$lang->story->verify}\n";
        $content[] = strip_tags($spec->verify) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $story->product, 'parentStory' => $story->parent, 'productModule' => $story->module, 'productBranch' => $story->branch, 'productPlan' => $story->plan, 'status' => $story->status, 'stage' => $story->stage);
        return $markdown;
    }

    /**
     * 将 DEMAND 对象转换为 Markdown 格式。
     * Convert demand object to Markdown format.
     *
     * @access public
     * @param object $demand
     * @return array
     */
    public static function convertDemandToMarkdown($demand)
    {
        global $app;

        $app->loadLang('demand');
        $lang = $app->lang;

        $spec = $app->dao->select('title,spec,verify')->from(TABLE_DEMANDSPEC)->where('demand')->eq($demand->id)->andWhere('version')->eq($demand->version)->fetch();
        if(empty($spec)) $spec = (object)array('title' => $demand->title, 'spec' => '', 'verify' => '');

        $markdown = array('id' => $demand->id, 'title' => "$lang->SRCommon #$demand->id $spec->title");
        $content  = array();
        $content[] = "# {$lang->demand->common} #$demand->id $demand->title\n";
        $content[] = "## {$lang->demand->legendBasicInfo}\n";
        $content[] = "* {$lang->demand->status}: " . zget($lang->demand->statusList, $demand->status);
        $content[] = "* {$lang->demand->stage}: " . zget($lang->demand->stageList, $demand->stage);
        $content[] = "* {$lang->demand->pri}: " . zget($lang->demand->priList, $demand->pri);
        $content[] = "* {$lang->demand->version}: $demand->version";
        $content[] = "* {$lang->demand->category}: " . zget($lang->demand->categoryList, $demand->category);
        $content[] = "* {$lang->demand->source}: " . zget($lang->demand->sourceList, $demand->source);
        $content[] = "* {$lang->demand->product}: $demand->product";
        $content[] = "* {$lang->demand->parent}: $demand->parent";
        $content[] = "* {$lang->demand->module}: $demand->module";
        $content[] = "* {$lang->demand->keywords}: $demand->keywords";
        $content[] = "* {$lang->demand->assignedTo}: $demand->assignedTo";
        $content[] = "* {$lang->demand->assignedDate}: $demand->assignedDate";
        $content[] = "* {$lang->demand->createdBy}: $demand->createdBy";
        $content[] = "* {$lang->demand->createdDate}: $demand->createdDate";
        $content[] = "* {$lang->demand->changedBy}: $demand->changedBy";
        $content[] = "* {$lang->demand->changedDate}: $demand->changedDate";
        $content[] = "* {$lang->demand->closedBy}: $demand->closedBy";
        $content[] = "* {$lang->demand->closedDate}: $demand->closedDate";
        $content[] = "* {$lang->demand->closedReason}: $demand->closedReason";
        $content[] = "* {$lang->demand->submitedBy}: $demand->submitedBy";
        $content[] = "* {$lang->demand->distributedBy}: $demand->distributedBy";
        $content[] = "* {$lang->demand->distributedDate}: $demand->distributedDate";
        $content[] = "## {$lang->demand->spec}\n";
        $content[] = strip_tags($spec->spec) . "\n";
        $content[] = "## {$lang->demand->verify}\n";
        $content[] = strip_tags($spec->verify) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $demand->product, 'parentDemand' => $demand->parent, 'productModule' => $demand->module, 'status' => $demand->status, 'stage' => $demand->stage);
        return $markdown;
    }

    /**
     * 将 BUG 对象转换为 Markdown 格式。
     * Convert bug object to markdown format.
     *
     * @access public
     * @param object $bug
     * @return array
     */
    public static function convertBugToMarkdown($bug)
    {
        global $app;

        $app->loadLang('bug');
        $lang = $app->lang;

        $markdown = array('id' => $bug->id, 'title' => "{$lang->bug->common} #$bug->id $bug->title");
        $content  = array();

        $content[] = "# {$lang->bug->common} #$bug->id $bug->title\n";
        $content[] = "## {$lang->bug->legendBasicInfo}\n";
        $content[] = "* {$lang->bug->pri}: " . zget($lang->bug->priList, $bug->pri);
        $content[] = "* {$lang->bug->severity}: " . zget($lang->bug->severityList, $bug->severity);
        $content[] = "* {$lang->bug->status}: " . zget($lang->bug->statusList, $bug->status);
        $content[] = "* {$lang->bug->resolution}: " . zget($lang->bug->resolutionList, $bug->resolution);
        $content[] = "* {$lang->bug->type}: " . zget($lang->bug->typeList, $bug->type);
        $content[] = "* {$lang->bug->product}: $bug->product";
        $content[] = "* {$lang->bug->project}: $bug->project";
        $content[] = "* {$lang->bug->execution}: $bug->execution";
        $content[] = "* {$lang->bug->module}: $bug->module";
        $content[] = "* {$lang->bug->branch}: $bug->branch";
        $content[] = "* {$lang->bug->plan}: $bug->plan";
        $content[] = "* {$lang->bug->story}: $bug->story";
        $content[] = "* {$lang->bug->relatedBug}: $bug->relatedBug";
        $content[] = "* {$lang->bug->keywords}: $bug->keywords";
        $content[] = "* {$lang->bug->resolvedBy}: $bug->resolvedBy";
        $content[] = "* {$lang->bug->resolvedDate}: $bug->resolvedDate";
        $content[] = "* {$lang->bug->resolvedBuild}: $bug->resolvedBuild";
        $content[] = "* {$lang->bug->openedBy}: $bug->openedBy";
        $content[] = "* {$lang->bug->openedDate}: $bug->openedDate";
        $content[] = "* {$lang->bug->openedBuild}: $bug->openedBuild";
        $content[] = "* {$lang->bug->assignedTo}: $bug->assignedTo";
        $content[] = "* {$lang->bug->assignedDate}: $bug->assignedDate";
        $content[] = "* {$lang->bug->closedBy}: $bug->closedBy";
        $content[] = "* {$lang->bug->closedDate}: $bug->closedDate";
        $content[] = "* {$lang->bug->feedbackBy}: $bug->feedbackBy";
        $content[] = "* {$lang->bug->activatedDate}: $bug->activatedDate";
        $content[] = "\n## {$lang->bug->steps}\n";
        $content[] = strip_tags($bug->steps) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $bug->product, 'module' => $bug->module, 'branch' => $bug->branch, 'plan' => $bug->plan, 'relatedBug' => $bug->relatedBug, 'story' => $bug->story, 'task' => $bug->task);
        return $markdown;
    }

    /**
     * 将 DOC 对象转换为 Markdown 格式。
     * Convert doc object to markdown format.
     *
     * @access public
     * @param object $doc
     * @return array
     */
    public static function convertDocToMarkdown($doc)
    {
        global $app;

        $app->loadLang('doc');
        $lang = $app->lang;

        $docContent = $app->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($doc->version)->fetch();
        if(empty($docContent))
        {
            $docContent = new stdClass();
            $docContent->title   = $doc->title;
            $docContent->content = isset($doc->content) ? $doc->content : '';
        }

        $markdown = array('id' => $doc->id, 'title' => "{$lang->doc->common} #$doc->id $docContent->title");
        $content  = array();

        $content[] = "# {$lang->doc->common} #$doc->id $docContent->title\n";
        $content[] = "## {$lang->doc->basicInfo}\n";
        $content[] = "* {$lang->doc->title}: $docContent->title";
        $content[] = "* {$lang->doc->type}: " . zget($lang->doc->typeList, $doc->type);
        $content[] = "* {$lang->doc->product}: $doc->product";
        $content[] = "* {$lang->doc->project}: $doc->project";
        $content[] = "* {$lang->doc->execution}: $doc->execution";
        $content[] = "* {$lang->doc->version}: $doc->version";
        $content[] = "* {$lang->doc->lib}: $doc->lib";
        $content[] = "* {$lang->doc->module}: $doc->module";

        $content[] = "\n---\n";
        $content[] = strip_tags($docContent->content) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $doc->product, 'lib' => $doc->lib, 'module' => $doc->module, 'project' => $doc->project, 'execution' => $doc->execution, 'type' => $doc->type, 'version' => $doc->version);
        return $markdown;
    }

    /**
     * 将 DESIGN 对象转换为 Markdown 格式。
     * Convert design object to markdown format.
     *
     * @access public
     * @param object $design
     * @return array
     */
    public static function convertDesignToMarkdown($design)
    {
        global $app;

        $app->loadLang('design');
        $lang = $app->lang;

        $designSpec = $app->dao->select('*')->from(TABLE_DESIGNSPEC)->where('design')->eq($design->id)->andWhere('version')->eq($design->version)->fetch();
        if(empty($designSpec)) $designSpec = (object)array('name' => $design->name, 'desc' => '');
        $markdown = array('id' => $design->id, 'title' => "{$lang->design->common} #$design->id $designSpec->name");
        $content  = array();

        $content[] = "# {$lang->design->common} #$design->id $designSpec->name\n";
        $content[] = "## {$lang->design->basicInfo}\n";
        $content[] = "* {$lang->design->type}: " . zget($lang->design->typeList, $design->type);
        $content[] = "* {$lang->design->product}: $design->product";
        $content[] = "* {$lang->design->project}: $design->project";
        $content[] = "* {$lang->design->story}: $design->story";
        $content[] = "* {$lang->design->version}: $design->version";
        $content[] = "* {$lang->design->assignedTo}: $design->assignedTo";
        $content[] = "* {$lang->design->createdBy}: $design->createdBy";
        $content[] = "* {$lang->design->createdDate}: $design->createdDate";

        $content[] = "\n## {$lang->design->desc}\n";
        $content[] = strip_tags($designSpec->desc) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $design->product, 'story' => $design->story, 'project' => $design->project, 'execution' => $design->execution, 'type' => $design->type);
        return $markdown;
    }

    /**
     * 将 FEEDBACK 对象转换为 Markdown 格式。
     * Convert feedback object to markdown format.
     *
     * @access public
     * @param object $feedback
     * @return array
     */
    public static function convertFeedbackToMarkdown($feedback)
    {
        global $app;

        $app->loadLang('feedback');
        $lang = $app->lang;

        $markdown = array('id' => $feedback->id, 'title' => "{$lang->feedback->common} #$feedback->id $feedback->title");
        $content  = array();

        $content[] = "# {$lang->feedback->common} #$feedback->id $feedback->title\n";
        $content[] = "## {$lang->feedback->labelBasic}\n";
        $content[] = "* {$lang->feedback->feedbackBy}: $feedback->feedbackBy";
        $content[] = "* {$lang->feedback->type}: " . zget($lang->feedback->typeList, $feedback->type);
        $content[] = "* {$lang->feedback->pri}: " . zget($lang->feedback->priList, $feedback->pri);
        $content[] = "* {$lang->feedback->status}: " . zget($lang->feedback->statusList, $feedback->status);
        $content[] = "* {$lang->feedback->solution}: " . zget($lang->feedback->solutionList, $feedback->solution);
        $content[] = "* {$lang->feedback->product}: $feedback->product";
        $content[] = "* {$lang->feedback->module}: $feedback->module";
        $content[] = "* {$lang->feedback->openedBy}: $feedback->openedBy";
        $content[] = "* {$lang->feedback->openedDate}: $feedback->openedDate";
        $content[] = "* {$lang->feedback->assignedTo}: $feedback->assignedTo";
        $content[] = "* {$lang->feedback->assignedDate}: $feedback->assignedDate";
        $content[] = "* {$lang->feedback->reviewedBy}: $feedback->reviewedBy";
        $content[] = "* {$lang->feedback->reviewedDate}: $feedback->reviewedDate";
        $content[] = "* {$lang->feedback->closedBy}: $feedback->closedBy";
        $content[] = "* {$lang->feedback->closedDate}: $feedback->closedDate";
        $content[] = "* {$lang->feedback->closedReason}: " . zget($lang->feedback->closedReasonList, $feedback->closedReason);
        $content[] = "* {$lang->feedback->processedBy}: $feedback->processedBy";
        $content[] = "* {$lang->feedback->processedDate}: $feedback->processedDate";
        $content[] = "* {$lang->feedback->source}: $feedback->source";
        $content[] = "* {$lang->feedback->result}: $feedback->result";
        $content[] = "* {$lang->feedback->keywords}: $feedback->keywords";
        $content[] = "* {$lang->feedback->faq}: $feedback->faq";

        $content[] = "\n## {$lang->feedback->desc}\n";
        $content[] = strip_tags($feedback->desc) . "\n";

        $markdown['content'] = implode("\n", $content);

        $markdown['attrs'] = array('product' => $feedback->product, 'module' => $feedback->module, 'type' => $feedback->type, 'status' => $feedback->status, 'pri' => $feedback->pri);
        return $markdown;
    }
}
