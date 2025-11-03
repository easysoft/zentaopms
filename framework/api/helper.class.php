<?php declare(strict_types=1);
/**
 * ZenTaoAPI的helper类。
 * The helper class file of ZenTao API.
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
include dirname(__FILE__, 2) . '/base/helper.class.php';
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
     * Verify that the system has opened on the feature.
     *
     * @param  string    $feature    scrum_risk | risk | scrum
     * @static
     * @access public
     * @return bool
     */
    public static function hasFeature(string $feature)
    {
        global $config;

        if(str_contains($feature, '_'))
        {
            $code = explode('_', $feature);
            $code = $code[0] . ucfirst($code[1]);
            return !str_contains(",$config->disabledFeatures,", ",{$code},");
        }
        else
        {
            if(in_array($feature, array('scrum', 'waterfall', 'agileplus', 'waterfallplus'))) return strpos(",$config->disabledFeatures,", ",{$feature},") === false;

            $hasFeature       = false;
            $canConfigFeature = false;
            foreach($config->featureGroup as $group => $modules)
            {
                foreach($modules as $module)
                {
                    if($feature == $group or $feature == $module)
                    {
                        $canConfigFeature = true;
                        if(in_array($group, array('scrum', 'waterfall', 'agileplus', 'waterfallplus')))
                        {
                            if(helper::hasFeature("{$group}") and helper::hasFeature("{$group}_{$module}")) $hasFeature = true;
                        }
                        else
                        {
                            if(helper::hasFeature("{$group}_{$module}")) $hasFeature = true;
                        }
                    }
                }
            }
            return !$canConfigFeature or ($hasFeature && strpos(",$config->disabledFeatures,", ",{$feature},") === false);
        }
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
     * Convert encoding.
     *
     * @param  string $string
     * @param  string $fromEncoding
     * @param  string $toEncoding
     * @static
     * @access public
     * @return string
     */
    public static function convertEncoding(string $string, string $fromEncoding, string $toEncoding = 'utf-8')
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
            $convertString = @iconv($fromEncoding, $toEncoding, $string);
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
     * Create url of issue.
     *
     * @param  string       $moduleName
     * @param  string       $methodName
     * @param  string|array $vars
     * @param  string       $viewType
     * @param  bool         $onlyBody
     * @static
     * @access public
     * @return string
     */
    public static function createLink(string $moduleName, string $methodName = 'index', string|array $vars = '', string $viewType = 'json', bool $onlyBody = false)
    {
        global $config;
        $link = parent::createLink($moduleName, $methodName, $vars, $viewType);

        /* The requestTypes are: GET, PATH_INFO2, PATH_INFO */
        if($config->requestType == 'GET')
        {
            $link = $config->webRoot . (string) substr($link, 2);
        }
        elseif($config->requestType == 'PATH_INFO2')
        {
            $link = substr((string) $link, $pos + 4);
        }
        return $link;
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
                return $value ? (string)$value : null;
            case 'string':
            default:
                return (string)$value;
        }
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
     * 发送请求的响应数据
     * Send response data
     *
     * @param  mixed  $data
     * @param  int    $code
     * @access public
     * @return string
     */
    static public function send(mixed $data = '', int $code = 200)
    {
        self::end(self::response($data, $code));
    }

    /**
     * 发送请求的响应数据
     * Send response data
     *
     * @param  mixed  $data
     * @param  int    $code
     * @access public
     * @return string
     */
    static public function response(mixed $data = '', int $code = 200)
    {
        $statusCode = array(
            100 => "100 Continue",
            101 => "101 Switching Protocols",
            102 => "102 Processing",

            200 => "200 OK",
            201 => "201 Created",
            202 => "202 Accepted",
            203 => "203 Non-Authoritative Information",
            204 => "204 No Content",
            205 => "205 Reset Content",
            206 => "206 Partial Content",
            207 => "207 Multi-Status",

            300 => "300 Multiple Choices",
            301 => "301 Moved Permanently",
            302 => "302 Found",
            303 => "303 See Other",
            304 => "304 Not Modified",
            305 => "305 Use Proxy",
            307 => "307 Temporary Redirect",

            400 => "400 Bad Request",
            401 => "401 Authorization Required",
            402 => "402 Payment Required",
            403 => "403 Forbidden",
            404 => "404 Not Found",
            405 => "405 Method Not Allowed",
            406 => "406 Not Acceptable",
            407 => "407 Proxy Authentication Required",
            408 => "408 Request Time-out",
            409 => "409 Conflict",
            410 => "410 Gone",
            411 => "411 Length Required",
            412 => "412 Precondition Failed",
            413 => "413 Request Entity Too Large",
            414 => "414 Request-URI Too Large",
            415 => "415 Unsupported Media Type",
            416 => "416 Requested Range Not Satisfiable",
            417 => "417 Expectation Failed",
            422 => "422 Unprocessable Entity",
            423 => "423 Locked",
            424 => "424 Failed Dependency",
            426 => "426 Upgrade Required",
        );

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,Authorization,Token,Referer,User-Agent");
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
        header("Content-type: application/json");
        header("HTTP/1.1 {$statusCode[$code]}");

        return !empty($data) ? json_encode($data, JSON_HEX_TAG) : '';
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
 * Fix for session error.
 *
 * @param  string    $class
 * @access protected
 * @return void
 */
function autoloader(string $class)
{
    if(!class_exists($class))
    {
        if($class == 'post_max_size' or $class == 'max_input_vars') eval('class ' . $class . ' {};');
    }
}

spl_autoload_register('autoloader');
