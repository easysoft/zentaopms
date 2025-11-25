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

        if(in_array($feature, array('waterfall', 'waterfallplus')))
        {
            return !str_contains(",$config->disabledFeatures,", ",{$feature},"); // 轻量级模式排除瀑布、融合瀑布模型
        }

        if(str_contains($feature, '_'))
        {
            $code = explode('_', $feature);
            $code = $code[0] . ucfirst($code[1]);
            return !str_contains(",$config->disabledFeatures,", ",{$code},");
        }

        $hasFeature       = false;
        $canConfigFeature = false;
        foreach($config->featureGroup as $group => $modules)
        {
            foreach($modules as $module)
            {
                if($feature != $group && $feature != $module) continue;

                $canConfigFeature = true;
                $hasFeature |= helper::hasFeature("{$group}_{$module}");
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
     * @return string
     */
    public static function formatKB($traffic, $precision = 2)
    {
        if(!$traffic) return 0;
        $base     = log((float)$traffic, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

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

    /**
     * 检查是否启用APCu。
     * Check if APCu is enabled.
     *
     * @access public
     * @return bool
     */
    public static function isAPCuEnabled(): bool
    {
        return extension_loaded('apcu') && ini_get('apc.enabled') == '1';
    }

    /**
     * 检查条件是否成立。
     *
     * @param  mixed  $value1
     * @param  mixed  $value2
     * @param  string $operator
     * @static
     * @access public
     * @return bool
     */
    public static function checkCondition($value1, $value2, $operator): bool
    {
        $operatorList = array('=' => 'equal', '==' => 'equal', '!=' => 'notequal', '>' => 'gt', '>=' => 'ge', '<' => 'lt', '<=' => 'le');
        if(!isset($operatorList[$operator]) && !in_array($operator, $operatorList)) return false;

        $operator  = isset($operatorList[$operator]) ? zget($operatorList, $operator) : $operator;
        $checkFunc = 'check' . $operator;
        return validater::$checkFunc($value1, $value2);
    }

    /**
     * 替换 Emoji 为指定字符串。
     * Replace Emoji to a specified string.
     *
     * @param  string $subject
     * @param  string $replace
     * @access public
     * @return string
     */
    public static function replaceEmoji(string $subject, string $replace = '[Emoji]'): string
    {
        /* 匹配大部分常见 Emoji 范围（包括符号、旗帜、交通工具等）。 */
        $pattern = '/[\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}]/u';
        return preg_replace($pattern, $replace, $subject);
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
function initTableData(array $items, array &$fieldList, ?object $model = null): array
{
    if(!empty($_GET['orderBy']) && strpos($_GET['orderBy'], '-') !== false) list($orderField, $orderValue) = explode('_', $_GET['orderBy']);
    if(!empty($orderField) && !empty($orderValue) && !empty($fieldList[$orderField]))
    {
        $sortType = false;
        $col      = $fieldList[$orderField];
        if(empty($col['sortType']) && !empty($col['type']) && in_array($col['type'], array('id', 'title'))) $sortType = true;
        if(!empty($col['sortType'])) $sortType = $col['sortType'];
        if(is_bool($sortType)) $fieldList[$orderField]['sortType'] = $orderValue;
    }

    $items = setParent($items);
    if(empty($fieldList['actions'])) return $items;

    foreach($fieldList['actions']['menu'] as $actionMenu)
    {
        if(is_array($actionMenu))
        {
            foreach($actionMenu as $actionMenuKey => $actionName)
            {
                if($actionMenuKey === 'other')
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

    global $app, $lang;
    if(empty($model))
    {
        $module = $app->getModuleName();
        $model  = $app->control->loadModel($module);
    }

    $maxActionCount = 0;
    foreach($items as $item)
    {
        $item->actions = array();

        $actionList = zget($fieldList['actions'], 'list', array());
        foreach($fieldList['actions']['menu'] as $actionKey => $actionMenu)
        {
            if(isset($actionMenu['other']))
            {
                $currentActionMenu = $actionMenu[0];
                initItemActions($item, $currentActionMenu, $actionList, $model);

                $otherActionMenus = $actionMenu['other'];
                $otherAction      = '';
                foreach($otherActionMenus as $otherActionMenu)
                {
                    $otherActions = explode('|', $otherActionMenu);
                    foreach($otherActions as $otherActionName)
                    {
                        if(!checkOtherPriv(zget($actionList, $otherActionName, array()), $otherActionName, $item, $model)) continue;
                        if(in_array($otherActionName, array_column($item->actions, 'name'))) continue;

                        if(method_exists($model, 'isClickable') && !$model->isClickable($item, $otherActionName)) $otherAction .= '-';
                        $otherAction .= $otherActionName . ',';
                    }
                }
                if($otherAction) $item->actions[] = 'other:' . $otherAction;
            }
            elseif($actionKey === 'more')
            {
                $moreAction = '';
                foreach($actionMenu as $moreActionName)
                {
                    if(!checkOtherPriv(zget($actionList, $moreActionName, array()), $moreActionName, $item, $model)) continue;
                    if(method_exists($model, 'isClickable') && !$model->isClickable($item, $moreActionName)) $moreAction .= '-';
                    $moreAction .= $moreActionName . ',';
                }

               if($moreAction) $item->actions[] = 'more:' . $moreAction;
            }
            elseif(is_array($actionMenu))       // Two or more grups.
            {
                /*
                 * Menu可能会有多套，如果只有一套可以直接用一维数组。
                 * There are maybe two or more groups of action menus.
                 */
                $item->actions = array();
                $isClickable   = false;
                foreach($actionMenu as $actionName) $isClickable |= initItemActions($item, $actionName, zget($fieldList['actions'], 'list', array()), $model);

                if($isClickable) break;     // If the action is clickable, use this group.
            }
            else // Only one group of action menus.
            {
                initItemActions($item, $actionMenu, zget($fieldList['actions'], 'list', array()), $model);
            }
        }

        if(count($item->actions) > $maxActionCount) $maxActionCount = count($item->actions);
    }

    if(isset($fieldList['actions']))
    {
        $fieldList['actions']['minWidth'] = $maxActionCount * 24 + 24;
        if(empty($fieldList['actions']['title'])) $fieldList['actions']['title'] = $lang->actions;
    }
    if($fieldList['actions']['minWidth'] < 48) $fieldList['actions']['minWidth'] = 48;

    return array_values($items);
}

/**
 * Check other action priv.
 *
 * @param  array  $actionList
 * @param  string $actionName
 * @param  object $item
 * @param  object $model
 * @access public
 * @return bool
 */
function checkOtherPriv(array $actionConfig, string $action, object $item, object $model)
{
    global $app;

    $module = $model->getModuleName();
    if($module == 'flow') $module = $app->rawModule;
    if(!empty($actionConfig['url']['module']) && $module != $actionConfig['url']['module']) $module = $actionConfig['url']['module'];

    $method = $action;
    if(!empty($actionConfig['url']['method']) && $method != $actionConfig['url']['method']) $method = $actionConfig['url']['method'];
    return common::hasPriv($module, $method, $item);
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
        if(isset($item->isParent)) continue;

        /* Set parent attribute. */
        $item->isParent = false;
        if(isset($item->parent) && $item->parent == -1)
        {
            /* When the parent is -1, the hierarchical structure is displayed incorrectly. */
            $item->parent   = 0;
            $item->isParent = true;
        }

        if(!empty($item->parent) && isset($items[$item->parent]) && isset($item->type) && $item->type == 'stage') $items[$item->parent]->isParent = true;
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
        if(!empty($actionConfig['icon'])) $actionConfig['text'] = '';

        if(!empty($actionConfig['url']['module']) && !empty($actionConfig['url']['method']))
        {
            $module = $actionConfig['url']['module'];
            $method = $actionConfig['url']['method'];
            $params = !empty($actionConfig['url']['params']) ? $actionConfig['url']['params'] : array();

            $actionConfig['url'] = helper::createLink($module, $method, $params, '', !empty($actionConfig['url']['onlybody']));
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
    if($actionMenu == 'divider')
    {
        $item->actions[] = array('name' => 'divider', 'type' => 'divider');
        return true;
    }

    global $app;
    $module = $model->getModuleName();
    if($module == 'flow') $module = $app->rawModule;
    $method = '';

    $isClickable = false;
    $actions     = explode('|', $actionMenu);
    foreach($actions as $action)
    {
        if(!isset($actionList[$action])) continue;

        $actionConfig = $actionList[$action];
        $notLoadModel = !empty($actionConfig['notLoadModel']) ? $actionConfig['notLoadModel'] : false;
        if(!empty($actionConfig['url']['module']) && $module != $actionConfig['url']['module'])
        {
            $module = $actionConfig['url']['module'];
            if(!$notLoadModel)
            {
                $rawModule = $module == 'projectbuild' ? 'build' : $module;
                $model = $app->control->loadModel($rawModule);
            }
        }

        $method = $action;
        if(!empty($actionConfig['url']['method']) && $method != $actionConfig['url']['method']) $method = $actionConfig['url']['method'];

        if(!method_exists($model, 'isClickable') || $model->isClickable($item, !$notLoadModel ? $method : $action))
        {
            $isClickable = true;
            break;
        }
    }

    if(!$method || !common::hasPriv($module, $method, $item)) return $isClickable;

    /* Check flow conditions for this object. */
    if($model->config->edition != 'open')
    {
        static $flowActions = [];
        if(empty($flowActions[$module])) $flowActions[$module] = $model->loadModel('workflowaction')->getList($module);

        $model->loadModel('flow');
        foreach($flowActions[$module] as $flowAction)
        {
            if($flowAction->action == $method && $flowAction->extensionType != 'none' && $flowAction->status == 'enable' && !empty($flowAction->conditions))
            {
                $isClickable = $model->flow->checkConditions($flowAction->conditions, $item);
            }
        }
    }

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
 * Save debug log to the php log file which prefix with 'debug.<today>'.
 *
 * @param  mixed ...$messages
 * @return void
 */
function debug(mixed ...$messages): void
{
    if(count($messages) > 1)
    {
        foreach($messages as $message) debug($message);
        return;
    }

    $message = current($messages);

    static $times = [];
    $time     = microtime(true);
    $duration = $times ? $time - end($times) : 0;
    $times[]  = $time;

    static $counts = [];
    $count    = $counts ? end($counts) + 1 : 1;
    $counts[] = $count;

    $logFile  = dirname(__FILE__, 2) . '/tmp/log/debug.' . date('Ymd') . '.log.php';
    $uid      = $_SERVER['HTTP_X_ZIN_UID'] ?? '';
    $count    = sprintf('%04d', $count);
    $time     = date('H:i:s');
    $duration = round($duration, 3);
    if($duration < 0.001) $duration = 0.001;
    if(is_object($message) || is_array($message)) $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($logFile, $count . ($uid ? " uid=$uid" : '') . " time=$time duration={$duration}s message=$message\n", FILE_APPEND);
}

/**
 * 为数组使用字母排序。
 * Use alphabetical sorting for arrays.
 *
 * @param  array  $data
 * @param  string $fieldName
 * @param  string $suffix
 * @return void
 */
function addPrefixToField(&$data, $fieldName, $suffix = '. ')
{
    $key = 0;
    foreach($data as &$item)
    {
        $prefix = '';
        $index  = $key;
        while($index >= 0)
        {
            $prefix = chr(65 + ($index % 26)) . $prefix;
            $index  = floor($index / 26) - 1;
        }
        if(is_array($item))
        {
            $item[$fieldName] = $prefix . $suffix . $item[$fieldName];
        }
        else
        {
            $item->{$fieldName} = $prefix . $suffix . $item->{$fieldName};
        }
        $key++;
    }
}
