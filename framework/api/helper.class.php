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
        $pos  = strpos((string) $link, '.php');

        /* The requestTypes are: GET, PATH_INFO2, PATH_INFO */
        if($config->requestType == 'GET')
        {
            $link = $config->webRoot . 'index' . substr((string) $link, $pos);
        }
        elseif($config->requestType == 'PATH_INFO2')
        {
            $link = substr((string) $link, $pos + 4);
        }
        return common::getSysURL() . $link;
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
