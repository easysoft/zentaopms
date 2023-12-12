<?php declare(strict_types=1);
/**
 * ZenTaoPHP的helper类。
 * The helper class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * 该类实现了一些常用的方法
 * The helper class, contains the tool functions.
 *
 * @package framework
 */
include __DIR__ . '/base/helper.class.php';
class helper extends baseHelper
{
    public static function getViewType(bool $source = false)
    {
        global $config, $app;
        if($config->requestType != 'GET')
        {
            $pathInfo = $app->getPathInfo();
            if(!empty($pathInfo))
            {
                $dotPos = strrpos((string) $pathInfo, '.');
                if($dotPos)
                {
                    $viewType = substr((string) $pathInfo, $dotPos + 1);
                }
                else
                {
                    $config->default->view = $config->default->view == 'mhtml' ? 'html' : $config->default->view;
                }
            }
        }
        elseif($config->requestType == 'GET')
        {
            if(isset($_GET[$config->viewVar]))
            {
                $viewType = $_GET[$config->viewVar];
            }
            else
            {
                /* Set default view when url has not module name. such as only domain. */
                $config->default->view = ($config->default->view == 'mhtml' and isset($_GET[$config->moduleVar])) ? 'html' : $config->default->view;
            }
        }
        if($source and isset($viewType)) return $viewType;

        if(isset($viewType) and !str_contains((string) $config->views, ',' . $viewType . ',')) $viewType = $config->default->view;
        return $viewType ?? $config->default->view;
    }

    /**
     * Encode json for $.parseJSON
     *
     * @param  array  $data
     * @param  int    $options
     * @static
     * @access public
     * @return string
     */
    public static function jsonEncode4Parse(array $data, int $options = 0)
    {
        $json = json_encode($data);
        if($options) $json = str_replace(array("'", '"'), array('\u0027', '\u0022'), $json);

        $escapers     = array("\\", "/", "\"", "'", "\n", "\r", "\t", "\x08", "\x0c", "\\\\u");
        $replacements = array("\\\\", "\\/", "\\\"", "\'", "\\n", "\\r", "\\t", "\\f", "\\b", "\\u");
        return str_replace($escapers, $replacements, $json);
    }

    /**
     * Verify that the system has opened on the feature.
     *
     * @param  string $feature    scrum_risk | risk | scrum
     * @static
     * @access public
     * @return bool
     */
    public static function hasFeature(string $feature): bool
    {
        global $config;

        if(str_contains($feature, '_'))
        {
            $code = explode('_', $feature);
            $code = $code[0] . ucfirst($code[1]);
            return !str_contains(",$config->disabledFeatures,", ",{$code},");
        }

        if(in_array($feature, array('scrum', 'waterfall', 'agileplus', 'waterfallplus'))) return !str_contains(",$config->disabledFeatures,", ",{$feature},");

        $hasFeature       = false;
        $canConfigFeature = false;
        foreach($config->featureGroup as $group => $modules)
        {
            foreach($modules as $module)
            {
                if($feature != $group && $feature != $module) continue;

                $canConfigFeature = true;
                if(in_array($group, array('scrum', 'waterfall', 'agileplus', 'waterfallplus')))
                {
                    $hasFeature |= (helper::hasFeature("{$group}") && helper::hasFeature("{$group}_{$module}"));
                }
                else
                {
                    $hasFeature |= helper::hasFeature("{$group}_{$module}");
                }
            }
        }
        return !$canConfigFeature || ($hasFeature && !str_contains(",$config->disabledFeatures,", ",{$feature},"));
    }

    /**
     * Convert encoding.
     *
     * @param  string $string
     * @param  string $fromEncoding
     * @param  string $toEncoding
     * @static
     * @access public
     * @return string
     */
    public static function convertEncoding(string $string, string $fromEncoding, string $toEncoding = 'utf-8'): string
    {
        $toEncoding = str_replace('utf8', 'utf-8', $toEncoding);
        if(function_exists('mb_convert_encoding'))
        {
            /* Remove like utf-8//TRANSLIT. */
            $position = strpos($toEncoding, '//');
            if($position !== false) $toEncoding = substr($toEncoding, 0, $position);

            /* Check string encoding. */
            $encodings = array_merge(array('GB2312', 'GBK', 'BIG5'), mb_list_encodings());
            $encoding  = strtolower(mb_detect_encoding($string, $encodings));
            if($encoding == $toEncoding) return $string;
            return mb_convert_encoding($string, $toEncoding, $encoding);
        }
        elseif(function_exists('iconv'))
        {
            if($fromEncoding == $toEncoding) return $string;

            $errorlevel    = error_reporting();
            error_reporting(0);
            $convertString = iconv($fromEncoding, $toEncoding, $string);
            error_reporting($errorlevel);

            /* iconv error then return original. */
            if(!$convertString) return $string;
            return $convertString;
        }

        return $string;
    }

    /**
     * Calculate two working days.
     *
     * @param string $begin
     * @param string $end
     */
    public static function workDays(string $begin, string $end): bool|float
    {
        $begin = strtotime($begin);
        $end   = strtotime($end);
        if($end < $begin) return false;

        $double = floor(($end - $begin) / (7 * 24 * 3600));
        $begin  = date('w', $begin);
        $end    = date('w', $end);
        $end    = $begin > $end ? $end + 5 : $end;
        return $double * 5 + $end - $begin;
    }

    /**
     * Unify string to standard chars.
     *
     * @param  string    $string
     * @param  string    $to
     * @static
     * @access public
     * @return string
     */
    public static function unify(string $string, string $to = ',')
    {
        $labels = array('_', '、', ' ', '-', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '，', '。');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }

    /**
     * Format version to semver formate.
     *
     * @param  string    $version
     * @static
     * @access public
     * @return string
     */
    public static function formatVersion(string $version)
    {
        return preg_replace_callback(
            '/([0-9]+)((?:\.[0-9]+)?)((?:\.[0-9]+)?)(?:[\s\-\+]?)((?:[a-z]+)?)((?:\.?[0-9]+)?)/i',
            function($matches)
            {
                $major      = $matches[1];
                $minor      = $matches[2];
                $patch      = $matches[3];
                $preRelease = $matches[4];
                $build      = $matches[5];

                $versionStrs = array($major, $minor ?: ".0", $patch ?: ".0");

                if($preRelease ?: $build) array_push($versionStrs, "-");
                if($preRelease) array_push($versionStrs, $preRelease);
                if($build)
                {
                    if(!$preRelease) array_push($versionStrs, "build");
                    if(mb_substr($build, 0, 1) !== ".") array_push($versionStrs, ".");

                    array_push($versionStrs, $build);
                }
                return implode('', $versionStrs);
            },
            $version
        );
    }

    /**
     * Process traffic.
     *
     * @param  float  $traffic
     * @param  int    $precision
     * @access public
     * @return float
     */
    public static function formatKB($traffic, $precision = 2)
    {
        if(!$traffic) return 0;
        $base     = log($traffic, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

	/**
	 * Trim version to xuanxuan version format.
	 *
	 * @param  string    $version
	 * @access public
	 * @return string
	 */
	public function trimVersion(string $version)
	{
		return preg_replace_callback(
			'/([0-9]+)((?:\.[0-9]+)?)((?:\.[0-9]+)?)(?:[\s\-\+]?)((?:[a-z]+)?)((?:\.?[0-9]+)?)/i',
			function($matches)
			{
				$major      = $matches[1];
				$minor      = $matches[2];
				$patch      = $matches[3];
				$preRelease = $matches[4];
				$build      = $matches[5];

				$versionStrs = array($major, $minor ?: ".0");

				if($patch && $patch !== ".0" && $patch !== "0") array_push($versionStrs, $patch);
				if($preRelease ?: $build) array_push($versionStrs, " ");
				if($preRelease) array_push($versionStrs, $preRelease);
				if($build)
				{
					if(!$preRelease) array_push($versionStrs, "build");
					array_push($versionStrs, mb_substr($build, 0, 1) === "." ? substr($build, 1) : $build);
				}
				return implode('', $versionStrs);
			},
			$version
		);
	}

    /**
     * Request API.
     *
     * @param  string    $url
     * @static
     * @access public
     * @return string
     */
    public static function requestAPI(string $url)
    {
        global $config;

        $url .= (str_contains($url, '?') ? '&' : '?') . $config->sessionVar . '=' . session_id();
        if(isset($_SESSION['user'])) $url .= '&account=' . $_SESSION['user']->account;
        $response = common::http($url);
        $jsonDecode = json_decode((string) $response);
        if(empty($jsonDecode)) return $response;
        return $jsonDecode;
    }

    /**
     * 代替 die、exit 函数终止并输出。
     * Instead of die, exit function to terminate and output.
     *
     * @param string $content
     * @return never
     * @throws EndResponseException
     */
    public static function end(string $content = ''): never
    {
        throw EndResponseException::create($content);
    }

    /**
     * Get date interval.
     *
     * @param  string     $format  %Y-%m-%d %H:%i:%s
     * @static
     * @access public
     */
    public static function getDateInterval(string|int $begin, string|int $end = '', string $format = ''): object|string
    {
        if(empty($end))    $end   = time();
        if(is_int($begin)) $begin = date('Y-m-d H:i:s', $begin);
        if(is_int($end))   $end   = date('Y-m-d H:i:s', $end);

        $begin    = date_create($begin);
        $end      = date_create($end);
        $interval = date_diff($begin, $end);

        if($format)
        {
            $dateInterval = $interval->format($format);
        }
        else
        {
            $dateInterval = new stdClass();
            $dateInterval->year    = $interval->format('%y');
            $dateInterval->month   = $interval->format('%m');
            $dateInterval->day     = $interval->format('%d');
            $dateInterval->hour    = $interval->format('%H');
            $dateInterval->minute  = $interval->format('%i');
            $dateInterval->secound = $interval->format('%s');
            $dateInterval->year    = $dateInterval->year == '00' ? 0 : ltrim($dateInterval->year, '0');
            $dateInterval->month   = $dateInterval->month == '00' ? 0 : ltrim($dateInterval->month, '0');
            $dateInterval->day     = $dateInterval->day == '00' ? 0 : ltrim($dateInterval->day, '0');
            $dateInterval->hour    = $dateInterval->hour == '00' ? 0 : ltrim($dateInterval->hour, '0');
            $dateInterval->minute  = $dateInterval->minute == '00' ? 0 : ltrim($dateInterval->minute, '0');
            $dateInterval->secound = $dateInterval->secound == '00' ? 0 : ltrim($dateInterval->secound, '0');
        }
        return $dateInterval;
    }

    /**
     * 转换类型。
     * Convert the type.
     *
     * @param mixed  $value
     * @param string $type
     * @static
     * @access public
     * @return array|bool|float|int|object|string
     */
    public static function convertType($value, $type)
    {
        switch($type)
        {
            case 'int':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'bool':
                return (bool)$value;
            case 'array':
                return (array)$value;
            case 'object':
                return (object)$value;
            case 'datetime':
            case 'date':
                return $value ? trim((string)$value) : null;
            case 'string':
            default:
                return trim((string)$value);
        }
    }

    /**
     * 是否是内网。
     * Check is intranet.
     *
     * @return bool
     */
    public static function isIntranet()
    {
        return !defined('USE_INTRANET') ? false : USE_INTRANET;
    }

    /**
     * 检查是否启用缓存。
     * Check is enable cache.
     *
     * @return bool
     */
    public static function isCacheEnabled()
    {
        if(isset($_GET['_nocache']) || isset($_SERVER['HTTP_X_ZT_REFRESH'])) return false;
        global $config;
        return $config->cache->enable;
    }
}

/**
 * 检查是否是onlybody模式。
 * Check exist onlybody param.
 *
 * @access public
 * @return bool
 */
function isonlybody(): bool
{
    return helper::inOnlyBodyMode();
}

/**
 * 检查页面是否是弹窗中。
 * Check page is modal.
 *
 * @access public
 * @return bool
 */
function isInModal(): bool
{
    return helper::isAjaxRequest('modal');
}

/**
 * Format time.
 *
 * @param  string|null $time
 * @param  string      $format
 * @access public
 * @return string
 */
function formatTime(string|null $time, string $format = ''): string
{
    if($time === null) return '';
    $time = str_replace('0000-00-00', '', $time);
    $time = str_replace('00:00:00', '', $time);
    if(trim($time) == '') return '';
    if($format) return date($format, strtotime($time));
    return trim($time);
}

/**
 * Init page title based on the module name and the method name.
 *
 * @access public
 * @return string
 */
function initPageTitle(): string
{
    global $app, $lang;
    $module = $app->rawModule;
    $method = $app->rawMethod;

    if(empty($lang->$module)) $app->loadLang($module);

    if(!empty($lang->$module->{$method . 'Action'})) return $lang->$module->{$method . 'Action'};
    if(!empty($lang->$module->$method)) return $lang->$module->$method;
    return zget($lang, $method);
}

/**
 * Init page entity based on configuration of objectNameFields.
 *
 * @param  object $object
 * @access public
 * @return array
 */
function initPageEntity(object $object): array
{
    if(empty($object)) return array();

    global $app, $config;
    $app->loadModuleConfig('action');

    $module     = $app->getModuleName();
    $idField    = isset($config->action->objectIdFields[$module])   ? $config->action->objectIdFields[$module]   : 'id';
    $titleField = isset($config->action->objectNameFields[$module]) ? $config->action->objectNameFields[$module] : 'title';

    return array(zget($object, $titleField, ''), zget($object, $idField, 0));
}

/**
 * Init table data of zin.
 *
 * @param  array  $items
 * @param  array  $fieldList
 * @param  object $model
 * @access public
 * @return array
 */
function initTableData(array $items, array &$fieldList, object $model = null): array
{
    if(!empty($_GET['orderBy']) && strpos($_GET['orderBy'], '-') !== false) list($orderField, $orderVaule) = explode('_', $_GET['orderBy']);
    if(!empty($orderField) && !empty($orderVaule) && !empty($fieldList[$orderField]))
    {
        $sortType = false;
        $col      = $fieldList[$orderField];
        if(empty($col['sortType']) && !empty($col['type']) && in_array($col['type'], array('id', 'title'))) $sortType = true;
        if(!empty($col['sortType'])) $sortType = $col['sortType'];
        if(is_bool($sortType)) $fieldList[$orderField]['sortType'] = $orderVaule;
    }

    $items = setParent($items);
    if(empty($fieldList['actions'])) return $items;

    foreach($fieldList['actions']['menu'] as $actionMenu)
    {
        if(is_array($actionMenu))
        {
            foreach($actionMenu as $actionMenuKey => $actionName)
            {
                if($actionMenuKey == 'other')
                {
                    foreach($actionName as $otherActionName) initTableActions($fieldList, $otherActionName);
                }
                else
                {
                    initTableActions($fieldList, $actionName);
                }
            }
        }
        else
        {
            initTableActions($fieldList, $actionMenu);
        }
    }

    global $app;
    if(empty($model))
    {
        $module = $app->getModuleName();
        $model  = $app->control->loadModel($module);
    }

    $maxActionCount = 0;
    foreach($items as $item)
    {
        $item->actions = array();
        foreach($fieldList['actions']['menu'] as $actionKey => $actionMenu)
        {
            if(isset($actionMenu['other']))
            {
                $currentActionMenu = $actionMenu[0];
                initItemActions($item, $currentActionMenu, $fieldList['actions']['list'], $model);

                $otherActionMenus = $actionMenu['other'];
                $otherAction      = '';
                foreach($otherActionMenus as $otherActionMenu)
                {
                    $otherActions = explode('|', $otherActionMenu);
                    foreach($otherActions as $otherActionName)
                    {
                        if(in_array($otherActionName, array_column($item->actions, 'name'))) continue;

                        if(method_exists($model, 'isClickable') && !$model->isClickable($item, $otherActionName)) $otherAction .= '-';
                        $otherAction .= $otherActionName . ',';
                    }
                }
                $item->actions[] = 'other:' . $otherAction;
            }
            elseif($actionKey === 'more')
            {
                $moreAction = '';
                foreach($actionMenu as $moreActionName)
                {
                    if(method_exists($model, 'isClickable') && !$model->isClickable($item, $moreActionName)) $moreAction .= '-';
                    $moreAction .= $moreActionName . ',';
                }

                $item->actions[] = 'more:' . $moreAction;
            }
            elseif(is_array($actionMenu))       // Two or more grups.
            {
                /*
                 * Menu可能会有多套，如果只有一套可以直接用一维数组。
                 * There are maybe two or more groups of action menus.
                 */
                $item->actions = array();
                $isClickable   = false;
                foreach($actionMenu as $actionName) $isClickable |= initItemActions($item, $actionName, $fieldList['actions']['list'], $model);

                if($isClickable) break;     // If the action is clickable, use this group.
            }
            else // Only one group of action menus.
            {
                initItemActions($item, $actionMenu, $fieldList['actions']['list'], $model);
            }
        }

        if(count($item->actions) > $maxActionCount) $maxActionCount = count($item->actions);
    }

    if(isset($fieldList['actions'])) $fieldList['actions']['minWidth'] = $maxActionCount * 24 + 24;
    if($fieldList['actions']['minWidth'] < 48) $fieldList['actions']['minWidth'] = 48;

    return array_values($items);
}

/**
 * Set the parent property of the data.
 *
 * @param  array  $items
 * @access public
 * @return array
 */
function setParent(array $items)
{
    foreach($items as $item)
    {
        /* Set parent attribute. */
        $item->isParent = false;
        if(isset($item->parent) && $item->parent == -1)
        {
            /* When the parent is -1, the hierarchical structure is displayed incorrectly. */
            $item->parent   = 0;
            $item->isParent = true;
        }

        if(!empty($item->parent) && isset($items[$item->parent])) $items[$item->parent]->isParent = true;
    }
    return $items;
}

/**
 * Init column actions of a table.
 *
 * @param  array  $fieldList
 * @param  string $actionMenu
 * @access public
 * @return void
 */
function initTableActions(array &$fieldList, string $actionMenu): void
{
    $actions = explode('|', $actionMenu);
    foreach($actions as $action)
    {
        if(!isset($fieldList['actions']['list'][$action])) continue;

        $actionConfig = $fieldList['actions']['list'][$action];
        $actionConfig['text'] = '';

        if(!empty($actionConfig['url']['module']) && !empty($actionConfig['url']['method']))
        {
            $module = $actionConfig['url']['module'];
            $method = $actionConfig['url']['method'];
            $params = !empty($actionConfig['url']['params']) ? $actionConfig['url']['params'] : array();

            $actionConfig['url'] = helper::createLink($module, $method, $params);
        }

        $fieldList['actions']['actionsMap'][$action] = $actionConfig;
    }
}

/**
 * Init row actions of a item.
 *
 * @param  object $item
 * @param  string $actionMenu
 * @param  array  $actionList
 * @param  object $model
 * @access public
 * @return bool
 */
function initItemActions(object &$item, string $actionMenu, array $actionList, object $model): bool
{
    global $app;
    $module = $model->getModuleName();
    $method = '';

    $isClickable = false;
    $actions     = explode('|', $actionMenu);
    foreach($actions as $action)
    {
        if(!isset($actionList[$action])) continue;

        $actionConfig = $actionList[$action];
        if(!empty($actionConfig['url']['module']) && $module != $actionConfig['url']['module'])
        {
            $module = $actionConfig['url']['module'];
            $model  = $app->control->loadModel($module);
        }

        $method = $action;
        if(!empty($actionConfig['url']['method']) && $method != $actionConfig['url']['method']) $method = $actionConfig['url']['method'];

        if(!method_exists($model, 'isClickable') || $model->isClickable($item, $method))
        {
            $isClickable = true;
            break;
        }
    }

    if(!$method || !common::hasPriv($module, $method)) return $isClickable;

    $item->actions[] = array('name' => $action, 'disabled' => !$isClickable);

    return $isClickable;
}

/**
 * 生成随机数。
 * Generate random number.
 *
 * @access public
 * @return int
 */
function updateSessionRandom(): int
{
    $random = mt_rand();
    $_SESSION['rand'] = $random;
    return $random;
}

/**
 * 获取可用的界面列表。
 * Get available vision list.
 *
 * @access public
 * @return array
 */
function getVisions(): array
{
    global $config, $lang;
    $visions    = array_flip(array_unique(array_filter(explode(',', trim($config->visions, ',')))));
    $visionList = $lang->visionList;
    return array_intersect_key($visionList, $visions);
}

/**
 * 对数据进行HTML实体转为字符。
 * Un HTML array.
 *
 * @param  mixed  $data
 * @access public
 * @return mixed
 */
function unHTMLArray($data, $processed = array())
{
    if(is_string($data)) return htmlspecialchars_decode($data);

    if(is_array($data) || is_object($data))
    {
        if(in_array($data, $processed, true)) {
            return $data;
        }

        $processed[] = $data;
        foreach($data as &$value) $value = unHTMLArray($value, $processed);
    }
    return $data;
}
