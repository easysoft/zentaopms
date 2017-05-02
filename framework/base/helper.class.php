<?php
/**
 * ZenTaoPHP的baseHelper类。
 * The baseHelper class file of ZenTaoPHP framework.
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
 * The baseHelper class, contains the tool functions.
 *
 * @package framework
 */
class baseHelper
{
    /**
     * 设置一个对象的成员变量
     * Set the member's value of one object.
     * <code>
     * <?php
     * $lang->db->user = 'wwccss';
     * helper::setMember('lang', 'db.user', 'chunsheng.wang');
     * ?>
     * </code>
     * @param string    $objName    the var name of the object.
     * @param string    $key        the key of the member, can be parent.child.
     * @param mixed     $value      the value to be set.
     * @static
     * @access public
     * @return bool
     */
    static public function setMember($objName, $key, $value)
    {
        global $$objName;
        if(!is_object($$objName) or empty($key)) return false;
        $key   = str_replace('.', '->', $key);
        $value = serialize($value);
        $code  = ("\$${objName}->{$key}=unserialize(<<<EOT\n$value\nEOT\n);");
        eval($code);
        return true;
    }

    /**
     * 创建一个模块方法的链接
     * control类的createLink实际上调用的是这个方法
     *
     * Create a link to a module's method.
     * This method also mapped in control class to call conveniently.
     *
     * <code>
     * <?php
     * helper::createLink('hello', 'index', 'var1=value1&var2=value2');
     * helper::createLink('hello', 'index', array('var1' => 'value1', 'var2' => 'value2');
     * ?>
     * </code>
     * @param string       $moduleName     module name
     * @param string       $methodName     method name
     * @param string|array $vars           the params passed to the method, can be array('key' => 'value') or key1=value1&key2=value2) or key1=value1&key2=value2
     * @param string       $viewType       the view type
     * @param bool         $onlybody       whether onlybody
     * @static
     * @access public
     * @return string the link string.
     */
    static public function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = '', $onlybody = false)
    {
        global $app, $config;
        $appName = $app->getAppName();
        if(strpos($moduleName, '.') !== false) list($appName, $moduleName) = explode('.', $moduleName);
        $appName = empty($appName) ? '' : $appName . '/';

        $link = $config->requestType == 'PATH_INFO' ? $config->webRoot . $appName : $config->webRoot . $appName . basename($_SERVER['SCRIPT_NAME']);
        if($config->requestType == 'PATH_INFO2') $link .= '/';

        /* 设置视图类型和变量。 */
        /* Set the view type and vars. */
        if(empty($viewType)) $viewType = $app->getViewType();
        if(!is_array($vars)) parse_str($vars, $vars);

        /* PATH_INFO方式。 */
        /* The PATH_INFO and PATH_INFO2 type. */
        if($config->requestType != 'GET')
        {
            /* 如果方法名与默认方法相等，并且参数是空的，转换为友好的链接地址。 */
            /* If the method equal the default method defined in the config file and the vars is empty, convert the link. */
            if($methodName == $config->default->method and empty($vars))
            {
                /* 如果模块名与默认模块名相等，转换为index.html。*/
                /* If the module also equal the default module, change index-index to index.html. */
                if($moduleName == $config->default->module)
                {
                    $link .= 'index.' . $viewType;
                }
                elseif($viewType == $app->getViewType())
                {
                    $link .= $moduleName . '/';
                }
                else
                {
                    $link .= $moduleName . '.' . $viewType;
                }
            }
            else
            {
                $link .= "$moduleName{$config->requestFix}$methodName";
                foreach($vars as $value) $link .= "{$config->requestFix}$value";
                $link .= '.' . $viewType;
            }
        }
        else
        {
            $link .= "?{$config->moduleVar}=$moduleName&{$config->methodVar}=$methodName";
            if($viewType != 'html') $link .= "&{$config->viewVar}=" . $viewType;
            foreach($vars as $key => $value) $link .= "&$key=$value";
        }

        /* if page has onlybody param then add this param in all link. the param hide header and footer. */
        if($onlybody or isonlybody())
        {
            $onlybody = $config->requestType != 'GET' ? "?onlybody=yes" : "&onlybody=yes";
            $link .= $onlybody;
        }
        return $link;
    }

    /**
     * 引用一个文件，替换内置的include及require方法
     * Import a file instend of include or require.
     *
     * @param string    $file   the file to be imported.
     * @static
     * @access public
     * @return bool
     */
    static public function import($file)
    {
        if(!is_file($file)) return false;
        static $includedFiles = array();
        if(!isset($includedFiles[$file]))
        {
            include $file;
            $includedFiles[$file] = true;
            return true;
        }
        return true;
    }

    /**
     * 设置一个模块的model文件，如果存在model扩展，一起合并
     * Set the model file of one module. If there's an extension file, merge it with the main model file.
     * 
     * @param   string $moduleName the module name
     * @param   string $appName the app name
     * @static
     * @access  public
     * @return  string the model file
     */
    static public function setModelFile($moduleName, $appName = '')
    {
        global $app;
        if($appName == '') $appName = $app->getAppName();

        /* 设置主model文件，扩展文件和路径。 */
        /* Set the main model file, extension path and files. */
        $mainModelFile = $app->getModulePath($appName, $moduleName) . 'model.php';
        $modelExtPaths = $app->getModuleExtPath($appName, $moduleName, 'model');

        $hookFiles = array();
        $extFiles  = array();
        foreach($modelExtPaths as $modelExtPath)
        {
            if(empty($modelExtPath)) continue;
            $hookFiles = array_merge($hookFiles, helper::ls($modelExtPath . 'hook/', '.php'));
            $extFiles  = array_merge($extFiles, helper::ls($modelExtPath, '.php'));
        }

        /* Get ext's app name from realname. */
        if($appName) $extAppName = basename(dirname(dirname(dirname($modelExtPath))));

        /* 如果没有扩展文件，返回主文件目录。 */
        /* If no extension file, return the main file directly. */
        if(empty($extFiles) and empty($hookFiles)) return $mainModelFile;

        /* 通过对比合并后的缓存文件和扩展文件的修改时间，确定是否要重新生成缓存 */
        /* Else, judge whether needed update or not .*/
        $extModelPrefix  = empty($app->siteCode) ? '' : $app->siteCode{0} . DS . $app->siteCode . DS;
        $mergedModelDir  = $app->getTmpRoot() . 'model' . DS . $extModelPrefix;
        $mergedModelFile = $mergedModelDir . (empty($app->siteCode) ? '' : $app->siteCode . '.') . $moduleName . '.php';
        $needUpdate      = false;
        $lastTime        = file_exists($mergedModelFile) ? filemtime($mergedModelFile) : 0;
        if(!is_dir($mergedModelDir)) mkdir($mergedModelDir, 0755, true);

        while(!$needUpdate)
        {
            foreach($extFiles  as $extFile) if(filemtime($extFile)  > $lastTime) break 2;
            foreach($hookFiles as $hookFile) if(filemtime($hookFile) > $lastTime) break 2;

            $modelExtPath  = $modelExtPaths['common']; 
            $modelHookPath = $modelExtPaths['common'] . 'hook/';
            if(is_dir($modelExtPath ) and filemtime($modelExtPath)  > $lastTime) break;
            if(is_dir($modelHookPath) and filemtime($modelHookPath) > $lastTime) break;
            if($modelExtPaths['site'])
            {
                $modelExtPath  = $modelExtPaths['site']; 
                $modelHookPath = $modelExtPaths['site'] . 'hook/';
                if(is_dir($modelExtPath ) and filemtime($modelExtPath)  > $lastTime) break;
                if(is_dir($modelHookPath) and filemtime($modelHookPath) > $lastTime) break;
            }

            if(filemtime($mainModelFile) > $lastTime) break;

            return $mergedModelFile;
        }

        /* If loaded zend opcache module, turn off cache when create tmp model file to avoid the conflics. */
        if(extension_loaded('Zend OPcache')) ini_set('opcache.enable', 0);

        /* Update the cache file. */
        $modelClass       = $moduleName . 'Model';
        $extModelClass    = 'ext' . $modelClass;
        $extTmpModelClass = 'tmpExt' . $modelClass;
        $modelLines       = "<?php\n";
        $modelLines      .= "global \$app;\n";
        $modelLines      .= "helper::cd(\$app->getBasePath());\n";
        $modelLines      .= "helper::import('" . str_replace($app->getBasePath(), '.' . DS, $mainModelFile) . "');\n";
        $modelLines      .= "helper::cd();\n";
        $modelLines      .= "class $extTmpModelClass extends $modelClass \n{\n";

        /* Cycle all the extension files. */
        foreach($extFiles as $extFile)
        {
            $extLines = self::removeTagsOfPHP($extFile);
            $modelLines .= $extLines . "\n";
        }

        /* Create the merged model file and import it. */
        $replaceMark = '//**//';    // This mark is for replacing code using.
        $modelLines .= "$replaceMark\n}";

        $tmpMergedModelFile = $mergedModelDir . 'tmp.' . (empty($app->siteCode) ? '' : $app->siteCode . '.') . $moduleName . '.php';
        if(!@file_put_contents($tmpMergedModelFile, $modelLines))
        {
            die("ERROR: $tmpMergedModelFile not writable, please make sure the " . dirname($tmpMergedModelFile) . ' directory exists and writable');
        }
        if(!class_exists($extTmpModelClass)) include $tmpMergedModelFile;

        /* Get hook codes need to merge. */
        $hookCodes = array();
        foreach($hookFiles as $hookFile)
        {
            $fileName = baseName($hookFile);
            list($method) = explode('.', $fileName);
            $hookCodes[$method][] = self::removeTagsOfPHP($hookFile);
        }

        /* Cycle the hook methods and merge hook codes. */
        $hookedMethods    = array_keys($hookCodes);
        $mainModelCodes   = file($mainModelFile);
        $mergedModelCodes = file($tmpMergedModelFile);
        foreach($hookedMethods as $method)
        {
            /* Reflection the hooked method to get it's defined position. */
            $methodRelfection = new reflectionMethod($extTmpModelClass, $method);
            $definedFile = $methodRelfection->getFileName();
            $startLine   = $methodRelfection->getStartLine() . ' ';
            $endLine     = $methodRelfection->getEndLine() . ' ';

            /* Merge hook codes. */
            $oldCodes = $definedFile == $tmpMergedModelFile ? $mergedModelCodes : $mainModelCodes;
            $oldCodes = join("", array_slice($oldCodes, $startLine - 1, $endLine - $startLine + 1));
            $openBrace = strpos($oldCodes, '{');
            $newCodes = substr($oldCodes, 0, $openBrace + 1) . "\n" . join("\n", $hookCodes[$method]) . substr($oldCodes, $openBrace + 1);

            /* Replace it. */
            if($definedFile == $tmpMergedModelFile)
            {
                $modelLines = str_replace($oldCodes, $newCodes, $modelLines);
            }
            else
            {
                $modelLines = str_replace($replaceMark, $newCodes . "\n$replaceMark", $modelLines);
            }
        }
        unlink($tmpMergedModelFile);
        
        /* Save it. */
        $modelLines = str_replace($extTmpModelClass, $extModelClass, $modelLines);
        file_put_contents($mergedModelFile, $modelLines);

        return $mergedModelFile;
    }

    /**
     * Remove tags of PHP 
     * 
     * @param  string    $fileName 
     * @static
     * @access public
     * @return string
     */
    static public function removeTagsOfPHP($fileName)
    {
        $code = trim(file_get_contents($fileName));
        if(strpos($code, '<?php') === 0)     $code = ltrim($code, '<?php');
        if(strrpos($code, '?>')   !== false) $code = rtrim($code, '?>');
        return trim($code);
    }

    /**
     * 将数组转化成 IN( 'a', 'b') 的形式，用于数据库字符串拼接
     * Create the in('a', 'b') string.
     * 
     * @param   string|array $ids   the id lists, can be a array or a string with ids joined with comma.
     * @static
     * @access  public
     * @return  string  the string like IN('a', 'b').
     */
    static public function dbIN($ids)
    {
        if(is_array($ids)) 
        {
            if(!function_exists('get_magic_quotes_gpc') or !get_magic_quotes_gpc())
            {
                foreach ($ids as $key=>$value)  $ids[$key] = addslashes($value); 
            }
            return "IN ('" . join("','", $ids) . "')";
        }

        if(!function_exists('get_magic_quotes_gpc') or !get_magic_quotes_gpc()) $ids = addslashes($ids);
        return "IN ('" . str_replace(',', "','", str_replace(' ', '', $ids)) . "')";
    }

    /**
     * base64编码，框架对'/'字符比较敏感，转换为'.'
     * Create safe base64 encoded string for the framework.
     * 
     * @param   string  $string   the string to encode.
     * @static
     * @access  public
     * @return  string  encoded string.
     */
    static public function safe64Encode($string)
    {
        return strtr(base64_encode($string), '/', '.');
    }

    /**
     * 解码base64，先将之前的'.' 转换回'/'
     * Decode the string encoded by safe64Encode.
     * 
     * @param   string  $string   the string to decode
     * @static
     * @access  public
     * @return  string  decoded string.
     */
    static public function safe64Decode($string)
    {
        return base64_decode(strtr($string, '.', '/'));
    }

    /**
     * Json encode and addslashe if magic_quotes_gpc is on. 
     * 
     * @param   mixed  $data   the object to encode
     * @static
     * @access  public
     * @return  string  decoded string.
     */
    static public function jsonEncode($data)
    {
        return (version_compare(phpversion(), '5.4', '<') and function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) ? addslashes(json_encode($data)) : json_encode($data);
    }

    /**
     * 判断是否是utf8编码
     * Judge a string is utf-8 or not.
     * 
     * @param  string    $string 
     * @author hmdker@gmail.com
     * @see    http://php.net/manual/en/function.mb-detect-encoding.php
     * @static
     * @access public
     * @return bool
     */
    static public function isUTF8($string)
    {
        $c    = 0; 
        $b    = 0;
        $bits = 0;
        $len  = strlen($string);
        for($i=0; $i<$len; $i++)
        {
            $c = ord($string[$i]);
            if($c > 128)
            {
                if(($c >= 254)) return false;
                elseif($c >= 252) $bits=6;
                elseif($c >= 248) $bits=5;
                elseif($c >= 240) $bits=4;
                elseif($c >= 224) $bits=3;
                elseif($c >= 192) $bits=2;
                else return false;
                if(($i+$bits) > $len) return false;
                while($bits > 1)
                {
                    $i++;
                    $b=ord($string[$i]);
                    if($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    /**
     *  计算两个日期相差的天数，取整
     *  Compute the diff days of two date.
     * 
     * @param   string $date1   the first date.
     * @param   string $date2   the sencode date.
     * @access  public
     * @return  int  the diff of the two days.
     */
    static public function diffDate($date1, $date2)
    {
        return round((strtotime($date1) - strtotime($date2)) / 86400, 0);
    }

    /**
     *  获取当前时间，使用common语言文件定义的DT_DATETIME1常量
     *  Get now time use the DT_DATETIME1 constant defined in the lang file.
     * 
     * @access  public
     * @return  datetime  now
     */
    static public function now()
    {
        return date(DT_DATETIME1);
    }

    /**
     *  获取当前日期，使用common语言文件定义的DT_DATE1常量
     *  Get today according to the  DT_DATE1 constant defined in the lang file.
     *
     * @access  public
     * @return  date  today
     */
    static public function today()
    {
        return date(DT_DATE1);
    }

    /**
     *  获取当前日期，使用common语言文件定义的DT_DATE1常量
     *  Get now time use the DT_TIME1 constant defined in the lang file.
     * 
     * @access  public
     * @return  date  today
     */
    static public function time()
    {
        return date(DT_TIME1);
    }

    /**
     *  判断日期是不是零
     *  Judge a date is zero or not.
     * 
     * @access  public
     * @return  bool
     */
    static public function isZeroDate($date)
    {
        return substr($date, 0, 4) == '0000';
    }

    /**
     *  列出目录中符合该正则表达式的文件
     *  Get files match the pattern under one directory.
     * 
     * @access  public
     * @return  array   the files match the pattern
     */
    static public function ls($dir, $pattern = '')
    {
        if(empty($dir)) return array();

        $files = array();
        $dir   = realpath($dir);
        if(is_dir($dir)) $files = glob($dir . DIRECTORY_SEPARATOR . '*' . $pattern);
        return empty($files) ? array() : $files;
    }

    /**
     * 切换目录
     * Change directory.
     * 
     * @param  string $path 
     * @static
     * @access public
     * @return void
     */
    static function cd($path = '')
    {
        static $cwd = '';
        if($path) $cwd = getcwd();
        !empty($path) ? chdir($path) : chdir($cwd);
    }

    /**
     * 去掉UTF8 Bom头
     * Remove UTF8 Bom 
     * 
     * @param  string    $string
     * @access public
     * @return string
     */
    public static function removeUTF8Bom($string)
    {
        if(substr($string, 0, 3) == pack('CCC', 239, 187, 191)) return substr($string, 3);
        return $string;
    }

    /**
     * 通过域名获取站点代号。
     * Get siteCode from domain.
     * @param  string $domain
     * @return string $siteCode
     **/ 
    public static function getSiteCode($domain)
    {
        global $config;

        if(strpos($domain, ':') !== false) $domain = substr($domain, 0, strpos($domain, ':')); // Remove port from domain.
        $domain = strtolower($domain);

        if(isset($config->siteCode[$domain])) return $config->siteCode[$domain];
        if($domain == 'localhost') return $domain;

        $domain  = str_replace('-', '_', $domain);    // Replace '-' by '_'.
        $items   = explode('.', $domain);
        $postfix = str_replace($items[0] . '.', '', $domain);
        if(isset($config->chanzhi->node->domain) and $postfix == $config->chanzhi->node->domain) return $items[0];
        if(isset($config->domainPostfix) and strpos($config->domainPostfix, "|$postfix|") !== false) return $items[0];

        $postfix = str_replace($items[0] . '.' . $items[1] . '.', '', $domain);
        if(isset($config->domainPostfix) and strpos($config->domainPostfix, "|$postfix|") !== false) return $items[1];

        return null;
    }

    /**
     * 增强substr方法：支持多字节语言，比如中文。
     * Enhanced substr version: support multibyte languages like Chinese.
     *
     * @param string $string
     * @param int $length 
     * @param string $append 
     * @return string 
     **/
    public static function substr($string, $length, $append = '')
    {
        if (strlen($string) <= $length ) $append = '';
        if(function_exists('mb_substr')) return mb_substr($string, 0, $length, 'utf-8') . $append;

        preg_match_all("/./su", $string, $data);
        return join("", array_slice($data[0],  0, $length)) . $append;
    }

    /**
     * 检查是否是SEO模式
     * Check in seo mode or not.
     *
     * return bool
     */
    public static function inSeoMode()
    {
        global $config;
        return (!empty($config->seoMode) and ($config->requestType != 'GET'));
    }

    /**
     * 检查是否是AJAX请求
     * Check is ajax request.
     * 
     * @static
     * @access public
     * @return bool
     */
    public static function isAjaxRequest()
    {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if(!$isAjax) $isAjax = (isset($_GET['HTTP_X_REQUESTED_WITH']) && $_GET['HTTP_X_REQUESTED_WITH'] == 'true');
        return $isAjax;
    }

    /**
     * 301跳转
     * Header 301 Moved Permanently.
     * 
     * @param  string    $locate 
     * @access public
     * @return void
     */
    public static function header301($locate)
    {
        header('HTTP/1.1 301 Moved Permanently');
        die(header('Location:' . $locate));
    }

    /**
     * 获取浏览器类型。
     * Get browser.
     * 
     * @access public
     * @return string
     */
    public static function getBrowser()
    {
        if(empty($_SERVER['HTTP_USER_AGENT'])) return 'unknow';

        $agent = $_SERVER["HTTP_USER_AGENT"];
        if(strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) 
        {
            return "ie";
        }
        else if(strpos($agent, 'Firefox') !== false)
        {
            return "firefox";
        }
        else if(strpos($agent, 'Chrome') !== false)
        {
            return "chrome";
        }
        else if(strpos($agent, 'Opera') !== false)
        {
            return 'opera';
        }
        else if((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false)
        {
            return 'safari';
        }
        else
        {
            return 'unknown';
        }
    }

    /**
     * 获取浏览器版本
     * Get browser version. 
     * 
     * @access public
     * @return string
     */
    public static function getBrowserVersion()
    {
        if(empty($_SERVER['HTTP_USER_AGENT'])) return 'unknow';

        $agent = $_SERVER['HTTP_USER_AGENT'];   
        if(preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs))
        {
            return $regs[1];
        }
        else if(preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs))
        {
            return $regs[1];
        }
        else if(preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs))
        {
            return $regs[1];
        }
        else if(preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs))
        {
            return $regs[1];
        }
        else if((strpos($agent,'Chrome') == false) && preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs))
        {
            return $regs[1];
        }
        else if(preg_match('/rv:(\d+)\..*/i', $agent, $regs))
        {
            return $regs[1];
        }
        else
        {
            return 'unknow';
        }
    }

    /**
     * 获取客户端操作系统
     * Get client os from agent info. 
     * 
     * @static
     * @access public
     * @return string
     */
    public static function getOS()
    {
        if(empty($_SERVER['HTTP_USER_AGENT'])) return 'unknow';

        $osList = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($osList as $regex => $value)
        { 
            if(preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) return $value; 
        }   

        return 'unknown';
    }

    /**
     * 设置$viewType，html还是mhtml或其他。
     * Set viewType.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function setViewType()
    {
        global $config, $app;
        if($config->requestType != 'GET')
        {
            $pathInfo = $app->getPathInfo();
            if(!empty($pathInfo))
            {
                $dotPos = strrpos($pathInfo, '.');
                if($dotPos)
                {
                    $viewType = substr($pathInfo, $dotPos + 1);
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

        if(isset($viewType) and strpos($config->views, ',' . $viewType . ',') === false) $viewType = $config->default->view;
        $app->viewType = isset($viewType) ? $viewType : $config->default->view;
    }

    /**
     * 数据配置合并到主配置
     * Merge config items in database and config files.
     * 
     * @param  array  $dbConfig 
     * @param  string $moduleName 
     * @static
     * @access public
     * @return void
     */
    public static function mergeConfig($dbConfig, $moduleName = 'common')
    {
        global $config;

        $config2Merge = $config;
        if($moduleName != 'common') $config2Merge = $config->$moduleName;

        foreach($dbConfig as $item)
        {
            foreach($item as $record)
            {
                if(!is_object($record))
                {
                    if($item->section and !isset($config2Merge->{$item->section})) $config2Merge->{$item->section} = new stdclass();
                    $configItem = $item->section ? $config2Merge->{$item->section} : $config2Merge;
                    if($item->key) $configItem->{$item->key} = $item->value;
                    break;
                }

                if($record->section and !isset($config2Merge->{$record->section})) $config2Merge->{$record->section} = new stdclass();
                $configItem = $record->section ? $config2Merge->{$record->section} : $config2Merge;
                if($record->key) $configItem->{$record->key} = $record->value;
            }
        }
    }

    /**
     * 将字符串中的字符统一到标准字符。
     * Unify string to standard chars.
     * 
     * @param  string    $string 
     * @param  string    $to 
     * @static
     * @access public
     * @return string
     */
    public static function unify($string, $to = ',')
    {
        $labels = array('_', '、', ' ', '-', '?', '@', '&', '%', '~', '`', '+', '*', '/', '\\', '，', '。');
        $string = str_replace($labels, $to, $string);
        return preg_replace("/[{$to}]+/", $to, trim($string, $to));
    }

    /** 
     * 获取远程IP。
     * Get remote ip. 
     * 
     * @access public
     * @return string
     */
    public static function getRemoteIp()
    {
        $ip = '';
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"]))
        {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $ip;
    }

    /**
     * 检查IP是否在给定的IP范围内。
     * check ip is in network.  
     * 
     * @param  string $ip 
     * @param  string $network 
     * @access public
     * @return void
     */
    public static function checkIpScope($ip, $network)
    {
        if(strpos($network, '/') === false) return $ip == $network;

        $ip = (double) (sprintf("%u", ip2long($ip)));
        $s  = explode('/', $network);
        $networkStart = (double) (sprintf("%u", ip2long($s[0])));
        $networkLen = pow(2, 32 - $s[1]);
        $networkEnd = $networkStart + $networkLen - 1;

        if ($ip >= $networkStart && $ip <= $networkEnd)
        {
            return true;
        }
        return false;
    }

    /**
     * 检查IP是否合法。
     * Check ip avaliable.  
     * 
     * @param  string $ip 
     * @access public
     * @return bool
     */
    public static function checkIP($ip)
    {
        $ip = trim($ip);
        if(strpos($ip, '/') !== false)
        {
            $s = explode('/', $ip);
            preg_match('/^(((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3})$/', $s[0], $matches);
            if(!empty($matches) and $s[1] > 0 and $s[1] < 36) return true;
        }
        else
        {
            preg_match('/^(((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3})$/', $ip, $matches);
            if(!empty($matches)) return true;
        }
        return false;
    }

    /**
     * 创建随机的字符串。
     * Create random string. 
     * 
     * @param  int    $length 
     * @param  string $skip A-Z|a-z|0-9
     * @static
     * @access public
     * @return void
     */
    public static function createRandomStr($length, $skip = '')
    {
        $str  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $skip = str_replace('A-Z', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $skip);
        $skip = str_replace('a-z', 'abcdefghijklmnopqrstuvwxyz', $skip);
        $skip = str_replace('0-9', '0123456789', $skip);
        for($i = 0; $i < strlen($skip); $i++)
        {
            $str = str_replace($skip[$i], '', $str);
        }

        $strlen = strlen($str);
        while($length > strlen($str)) $str .= $str;

        $str = str_shuffle($str); 
        return substr($str,0,$length); 
    }

    /**
     * 获取设备类型。
     * Get device.
     * 
     * @access public
     * @return void
     */
    public static function getClientDevice()
    {
        global $config;

        if(isset($_COOKIE['visualDevice'])) return $_COOKIE['visualDevice'];

        /* Detect mobile. */
        $mobile = new mobile();
        if(!$mobile->isTablet() and $mobile->isMobile()) return 'mobile';
        return 'desktop';
    }
}

/**
 *  helper::createLink()的别名，方便创建本模块的链接
 *  The short alias of helper::createLink() method. 
 *
 * @param  string        $methodName  the method name
 * @param  string|array  $vars        the params passed to the method, can be array('key' => 'value') or key1=value1&key2=value2)
 * @param  string        $viewType    
 * @return string the link string.
 */
function inLink($methodName = 'index', $vars = '', $viewType = '')
{
    global $app;
    return helper::createLink($app->getModuleName(), $methodName, $vars, $viewType);
}

/**
 *  通过一个静态游标，可以遍历数组
 *  Static cycle a array
 *
 * @param array  $items     the array to be cycled.
 * @return mixed
 */
function cycle($items)
{
    static $i = 0;
    if(!is_array($items)) $items = explode(',', $items);
    if(!isset($items[$i])) $i = 0;
    return $items[$i++];
}

/**
 * 获取当前时间的Unix时间戳，精确到微妙
 * Get current microtime.
 * 
 * @access public
 * @return float current time.
 */
function getTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * 打印变量的信息
 * dump a var.
 * 
 * @param mixed $var 
 * @access public
 * @return void
 */
function a($var)
{
    echo "<xmp class='a-left'>";
    print_r($var);
    echo "</xmp>";
}

/**
 * 判断是否内外IP。
 * Judge the server ip is local or not.
 *
 * @access public
 * @return void
 */
function isLocalIP()
{
    $serverIP = $_SERVER['SERVER_ADDR'];
    if($serverIP == '127.0.0.1') return true;
    if(strpos($serverIP, '10.60') !== false) return false;
    return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

/**
 * 获取webRoot。
 * Get web root. 
 * 
 * @access public
 * @return string 
 */
function getWebRoot()
{
    $path = $_SERVER['SCRIPT_NAME'];
    if(PHP_SAPI == 'cli')
    {
        $url  = parse_url($_SERVER['argv'][1]);
        $path = empty($url['path']) ? '/' : rtrim($url['path'], '/');
        $path = empty($path) ? '/' : preg_replace('/\/www$/', '/www/', $path);
    }

    return substr($path, 0, (strrpos($path, '/') + 1));
}

/**
 * 检查是否是onlybody模式。
 * Check exist onlybody param.
 * 
 * @access public
 * @return void
 */
function isonlybody()
{
    return (isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes');
}

/**
 * 格式化钱。
 * Format money.
 * 
 * @param  float    $money 
 * @access public
 * @return string
 */
function formatMoney($money)
{
    return trim(preg_replace('/\.0*$/', '', number_format($money, 2)));
}

/**
 * 格式化时间
 * Format time.
 * 
 * @param  int    $time 
 * @param  string $format 
 * @access public
 * @return void
 */
function formatTime($time, $format = '')
{
    $time = str_replace('0000-00-00', '', $time);
    $time = str_replace('00:00:00', '', $time);
    if(trim($time) == '') return ;
    if($format) return date($format, strtotime($time));
    return trim($time);
}

/**
 * 检查可用curl ssl。
 * Check curl ssl enabled.
 * 
 * @access public
 * @return void
 */
function checkCurlSSL()
{
    $version = curl_version();
    return ($version['features'] & CURL_VERSION_SSL);
}

/**
 * 当数组/对象变量$var存在$key项时，返回存在的对应值或设定值，否则返回$key或不存在的设定值。
 * When the $var has the $key, return it, esle result one default value.
 * 
 * @param  array|object    $var 
 * @param  string|int      $key 
 * @param  mixed           $valueWhenNone     value when the key not exits.
 * @param  mixed           $valueWhenExists   value when the key exits.
 * @access public
 * @return string
 */
function zget($var, $key, $valueWhenNone = false, $valueWhenExists = false)
{
    if(!is_array($var) and !is_object($var)) return false;
    $type = is_array($var) ? 'array' : 'object';
    $checkExists = $type == 'array' ? isset($var[$key]) : isset($var->$key);
    if($checkExists)
    {
        if($valueWhenExists !== false) return $valueWhenExists;
        return $type == 'array' ? $var[$key] : $var->$key;
    }
    if($valueWhenNone !== false) return $valueWhenNone;
    return $key;
}

/**
 * 301跳转。
 * Header lcoation 301. 
 * 
 * @param  string    $url 
 * @access public
 * @return void
 */
function header301($url)
{
    header('HTTP/1.1 301 Moved Permanently');
    die(header('Location:' . $url));
}

/**
 * 处理恶意参数.
 * Process evil params.
 * 
 * @param  string    $value 
 * @access public
 * @return void
 */
function processEvil($value)
{
    global $config;
    if(strpos(htmlspecialchars_decode($value), '<?') !== false)
    {
        $value       = (string) $value;
        $evils       = array('eval', 'exec', 'passthru', 'proc_open', 'shell_exec', 'system', '$$', 'include', 'require', 'assert');
        $gibbedEvils = array('e v a l', 'e x e c', ' p a s s t h r u', ' p r o c _ o p e n', 's h e l l _ e x e c', 's y s t e m', '$ $', 'i n c l u d e', 'r e q u i r e', 'a s s e r t');
        $value       = str_ireplace($evils, $gibbedEvils, $value);
    }
    if(isset($config->framework->stripXSS) and $config->framework->stripXSS)
    {
        if(stripos($value, '<script') !== false)
        {
            $value       = (string) $value;
            $evils       = array('appendchild(', 'createElement(', 'xss.re', 'onfocus', 'onclick', 'innerHTML', 'replaceChild(', 'html(', 'append(', 'appendTo(', 'prepend(', 'prependTo(', 'after(', 'before(', 'replaceWith(');
            $gibbedEvils = array('a p p e n d c h i l d (', 'c r e a t e E l e  m e n t (', 'x s s . r e', 'o n f o c u s', 'o n c l i c k', 'i n n e r H T M L', 'r e p l a c e C h i l d (', 'h t m l (', 'a p p e n d (', 'a p p e n d T o (', 'p r e p e n d (', 'p r e p e n d T o (', 'a f t e r (', 'b e f o r e (', 'r e p l a c e W i t h (');
            $value       = str_ireplace($evils, $gibbedEvils, $value);
        }
        /* Process like 'javascript:' */
        $value = preg_replace('/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\s*:/Ui', 'j a v a s c r i p t :', $value);
    }
    return $value;
}

/**
 * 批量处理恶意参数.
 * Process array evils.
 * 
 * @param  array    $params 
 * @access public
 * @return array
 */
function processArrayEvils($params)
{
    $params = (array) $params;
    foreach($params as $item => $values)
    {
        if(!is_array($values))
        {
            $params[$item] = processEvil($values);
            if(processEvil($item) != $item) unset($params[$item]);
        }
        else
        {
            foreach($values as $key => $value)
            {
                if(is_array($value)) continue;
                $params[$item][$key] = processEvil($value);
                if(processEvil($key) != $key) unset($params[$item][$key]);
            }
        }
    }
    return $params;
}

/**
 * 获取主机地址。
 * Get host URL.
 * 
 * @access public
 * @return bool
 */
function getHostURL()
{
    return ((isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) != 'off') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
}

/**
 * 判断requestType是否是GET类型。
 * Check current request is GET.
 * 
 * @access public
 * @return void
 */
function isGetUrl()
{
    $webRoot = getWebRoot();
    if(strpos($_SERVER['REQUEST_URI'], "{$webRoot}?") === 0) return true;
    if(strpos($_SERVER['REQUEST_URI'], "{$webRoot}index.php?") === 0) return true;
    if(strpos($_SERVER['REQUEST_URI'], "{$webRoot}index.php/?") === 0) return true;
    return false;
}

/**
 * 获取文件mime。
 * Get file mime type.
 * 
 * @param  int    $file 
 * @access public
 * @return void
 */
function getFileMimeType($file)
{
    if(function_exists('mime_content_type')) return mime_content_type($file);
    if(function_exists('finfo_open'))
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $file); 
    }
    return false;
}
