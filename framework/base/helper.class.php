<?php
/**
 * ZenTaoPHP的baseHelper类。
 * The baseHelper class file of ZenTaoPHP framework.
 *
 * @package framework
 *
 * The author disclaims copyright to this source code. In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
class baseHelper
{
    /**
     * 设置一个对象的成员变量。
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
     * 生成一个模块方法的链接。control类的createLink实际上调用的是这个方法。
     * Create a link to a module's method, mapped in control class to call conveniently.
     *
     * <code>
     * <?php
     * helper::createLink('hello', 'index', 'var1=value1&var2=value2');
     * helper::createLink('hello', 'index', array('var1' => 'value1', 'var2' => 'value2');
     * ?>
     * </code>
     * @param string       $moduleName     module name, can pass appName like app.module.
     * @param string       $methodName     method name
     * @param string|array $vars           the params passed to the method, can be array('key' => 'value') or key1=value1&key2=value2) or key1=value1&key2=value2
     * @param string       $viewType       the view type
     * @param bool         $onlyBody       pass onlyBody=yes to the link thus the app can control the header and footer hide or show..
     * @static
     * @access public
     * @return string the link string.
     */
    static public function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = '', $onlyBody = false)
    {
        /* 设置$appName和$moduleName。Set appName and moduleName. */
        global $app, $config;

        if(strpos($moduleName, '.') !== false)
        {
            list($appName, $moduleName) = explode('.', $moduleName);
        }
        else
        {
            $appName = $app->getAppName();
        }
        if(!empty($appName)) $appName .= '/';

        /* 处理$viewType和$vars。Set $viewType and $vars. */
        if(empty($viewType)) $viewType = $app->getViewType();
        if(!is_array($vars))
        {
            /* Prevent + from converting to spaces. */
            $vars = str_replace('+', '%2B', $vars);
            parse_str($vars, $vars);
        }

        /* 生成url链接的开始部分。Set the begin parts of the link. */
        if($config->requestType == 'PATH_INFO')  $link = $config->webRoot . $appName;
        if($config->requestType != 'PATH_INFO')  $link = $config->webRoot . $appName . basename($_SERVER['SCRIPT_NAME']);
        if($config->requestType == 'PATH_INFO2') $link = '/';

        /**
         * #1: RequestType为GET。When the requestType is GET.
         * Input: moduleName=article&methodName=index&var1=value1. Output: ?m=article&f=index&var1=value1.
         *
         */
        if($config->requestType == 'GET')
        {
            $link .= "?{$config->moduleVar}=$moduleName&{$config->methodVar}=$methodName";
            if($viewType != 'html') $link .= "&{$config->viewVar}=" . $viewType;
            foreach($vars as $key => $value) $link .= "&$key=$value";

            return self::processOnlyBodyParam($link, $onlyBody);
        }

        /**
         * #2: 方法名不是默认值或者是默认值，但有传参。MethodName equals the default method or vars not empty.
         * Input: moduleName=article&methodName=view. Output: article-view.html
         * Input: moduleName=article&methodName=view. Output: article-index-abc.html
         *
         */
        if($methodName != $config->default->method or !empty($vars))
        {
            $link .= "$moduleName{$config->requestFix}$methodName";
            foreach($vars as $value) $link .= "{$config->requestFix}$value";
            $link .= '.' . $viewType;

            return self::processOnlyBodyParam($link, $onlyBody);
        }

        /**
         * #3: 方法名为默认值且没有传参且模块名为默认值。MethodName is the default and moduleName is default and vars empty.
         * Input: moduleName=index&methodName=index. Output: index.html
         *
         */
        if($moduleName == $config->default->module)
        {
            $link .= $config->default->method . '.' . $viewType;
            return self::processOnlyBodyParam($link, $onlyBody);
        }

        /**
         * #4: 方法名为默认值且没有传参且模块名不为默认值，viewType和app指定的相等。MethodName is default but moduleName not and viewType equal app's viewType..
         * Input: moduleName=article&methodName=index&viewType=html. Output: /article/
         *
         */
        if($viewType == $app->getViewType())
        {
            $link .= $moduleName . '.' . $viewType;
            return self::processOnlyBodyParam($link, $onlyBody);
        }

        /**
         * #5: 方法名为默认值且没有传参且模块名不为默认值，viewType有另外指定。MethodName is default but moduleName not and viewType no equls app's viewType.
         * Input: moduleName=article&methodName=index&viewType=json. Output: /article.json
         *
         */
        $link .= $moduleName . '.' . $viewType;
        return self::processOnlyBodyParam($link, $onlyBody);
    }

    /**
     * 处理onlyBody 参数。
     * Process the onlyBody param in url.
     *
     * 如果传参的时候设定了$onlyBody为真，或者当前页面请求中包含了onlybody=yes，在生成链接的时候继续追加。
     * If $onlyBody set to true or onlybody=yes in the url, append onlyBody param to the link.
     *
     * @param  string  $link
     * @param  bool    $onlyBody
     * @static
     * @access public
     * @return string
     */
    public static function processOnlyBodyParam($link, $onlyBody = false)
    {
        global $config;

        $sign = strpos($link, '?') === false ? "?" : "&";
        $appendString = '';
        if($onlyBody or self::inOnlyBodyMode()) $appendString = $sign . "onlybody=yes";
        if(self::isWithTID() and strpos($link, 'tid=') === false) $appendString .= empty($appendString) ? "{$sign}tid={$_GET['tid']}" : "&tid={$_GET['tid']}";
        return $link . $appendString;
    }

    /**
     * 检查是否是onlybody模式。
     * Check in only body mode or not.
     *
     * @access public
     * @return bool
     */
    public static function inOnlyBodyMode()
    {
        return (isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes');
    }

    /**
     * Is with tid.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function isWithTID()
    {
        global $config;
        return (!empty($config->tabSession) and isset($_GET['tid']));
    }

    /**
     * 使用helper::import()来引入文件，不要直接使用include或者require.
     * Using helper::import() to import a file, instead of include or require.
     *
     * @param string    $file   the file to be imported.
     * @static
     * @access public
     * @return bool
     */
    static public function import($file)
    {
        $file = realpath($file);
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
     * 使用helper::importControl()来引入Control文件，不要直接使用include或者require.
     * Using helper::importControl() to import a file, instead of include or require.
     *
     * @param string    $moduleName.
     * @static
     * @access public
     * @return bool
     */
    static public function importControl($moduleName)
    {
        global $app;
        return helper::import($app->getModulePath($moduleName) . 'control.php');
    }

    /**
     * 将数组或者列表转化成 IN( 'a', 'b') 的形式。
     * Convert a list to  IN('a', 'b') string.
     *
     * @param   string|array $idList   列表，可以是数组或者用逗号隔开的列表。The id lists, can be a array or a string joined with comma.
     * @static
     * @access  public
     * @return  string  the string like IN('a', 'b').
     */
    static public function dbIN($idList)
    {
        if(is_array($idList))
        {
            foreach($idList as $key => $value) $idList[$key] = empty($value) ? '' : addslashes($value);
            return "IN ('" . implode("','", $idList) . "')";
        }

        if(is_null($idList)) $idList = '';
        if(!is_string($idList)) $idList = json_encode($idList);
        $idList = addslashes($idList);
        return "IN ('" . str_replace(',', "','", str_replace(' ', '', $idList)) . "')";
    }

    /**
     * 安全的Base64编码，框架对'/'字符比较敏感，转换为'.'。
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
     * JSON编码，自动处理转义的问题。
     * JSON encode, process the slashes.
     *
     * @param   mixed  $data   the object to encode
     * @static
     * @access  public
     * @return  string  decoded string.
     */
    static public function jsonEncode($data)
    {
        return json_encode($data);
    }

    /**
     * Encrypt password.
     *
     * @param  string    $password
     * @static
     * @access public
     * @return string
     */
    public static function encryptPassword($password)
    {
        global $config;

        $encrypted = '';
        if(!empty($config->encryptSecret) and $password)
        {
            $secret = $config->encryptSecret;
            $iv     = str_repeat("\0", 8);
            if(function_exists('mcrypt_encrypt'))
            {
                $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_DES, substr($secret, 0, 8), $password, MCRYPT_MODE_CBC, $iv));
            }
            elseif(function_exists('openssl_encrypt'))
            {
                /* Set password length to multiple of 8. For compatible mcrypt_encrypt function. */
                $oversize = strlen($password) % 8;
                if($oversize != 0) $password .= str_repeat("\0", 8 - $oversize);

                $encrypted = openssl_encrypt($password, 'DES-CBC', substr($secret, 0, 8), OPENSSL_ZERO_PADDING, $iv);
            }
        }
        if(empty($encrypted)) $encrypted = $password;

        return $encrypted;
    }

    /**
     * Decrypt password.
     *
     * @param  string $password
     * @static
     * @access public
     * @return string
     */
    public static function decryptPassword($password)
    {
        global $config;

        $decryptedPassword = '';
        if(!empty($config->encryptSecret) and $password)
        {
            $secret = $config->encryptSecret;
            $iv     = str_repeat("\0", 8);
            if(function_exists('mcrypt_decrypt'))
            {
                $decryptedPassword = trim(mcrypt_decrypt(MCRYPT_DES, substr($secret, 0, 8), base64_decode($password), MCRYPT_MODE_CBC, $iv));
            }
            elseif(function_exists('openssl_decrypt'))
            {
                $decryptedPassword = trim(openssl_decrypt($password, 'DES-CBC', substr($secret, 0, 8), OPENSSL_ZERO_PADDING, $iv));
            }

            /* Check decrypted password. Judge whether there is garbled code. */
            $jsoned = json_encode($decryptedPassword);
            if($jsoned === 'null' or empty($jsoned)) $decryptedPassword = '';
        }
        if(empty($decryptedPassword)) $decryptedPassword = $password;

        $decryptedPassword = trim($decryptedPassword);
        return $decryptedPassword;
    }

    /**
     * 判断是否是utf8编码
     * Judge a string is utf-8 or not.
     *
     * @author hmdker@gmail.com
     * @param  string    $string
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
     * 去掉UTF-8 Bom头。
     * Remove UTF-8 Bom.
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
     * 增强substr方法：支持多字节语言，比如中文。
     * Enhanced substr version: support multibyte languages like Chinese.
     *
     * @param string    $string
     * @param int       $length
     * @param string    $append
     * @return string
     */
    public static function substr($string, $length, $append = '')
    {
        $rawString = $string;

        if(function_exists('mb_substr')) $string = mb_substr($string, 0, $length, 'utf-8');

        preg_match_all("/./su", $string, $data);
        $string = implode("", array_slice($data[0],  0, $length));

        return ($string != $rawString) ? $string . $append : $string;
    }

    /**
     * Get browser name and version.
     *
     * @access public
     * @return array
     */
    public static function getBrowser()
    {
        $browser = array('name'=>'unknown', 'version'=>'unknown');

        if(empty($_SERVER['HTTP_USER_AGENT'])) return $browser;

        $agent = $_SERVER["HTTP_USER_AGENT"];

        /* Chrome should check before safari.*/
        if(strpos($agent, 'Firefox') !== false) $browser['name'] = "firefox";
        if(strpos($agent, 'Opera') !== false)   $browser['name'] = 'opera';
        if(strpos($agent, 'Safari') !== false)  $browser['name'] = 'safari';
        if(strpos($agent, 'Chrome') !== false)  $browser['name'] = "chrome";

        // Check the name of browser
        if(strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) $browser['name'] = 'ie';
        if(strpos($agent, 'Edge') !== false) $browser['name'] = 'edge';

        // Check the version of browser
        if(preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs))       $browser['version'] = $regs[1];
        if(preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs))    $browser['version'] = $regs[1];
        if(preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)) $browser['version'] = $regs[1];
        if(preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs))     $browser['version'] = $regs[1];

        if((strpos($agent, 'Chrome') == false) && preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)) $browser['version'] = $regs[1];
        if(preg_match('/rv:(\d+)\..*/i', $agent, $regs)) $browser['version'] = $regs[1];
        if(preg_match('/Edge\/(\d+)\..*/i', $agent, $regs)) $browser['version'] = $regs[1];

        return $browser;
    }

    /**
     * Get client os from agent info.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getOS()
    {
        if(empty($_SERVER['HTTP_USER_AGENT'])) return 'unknow';

        $osList = array();
        $osList['/windows nt 10/i']      = 'Windows 10';
        $osList['/windows nt 6.3/i']     = 'Windows 8.1';
        $osList['/windows nt 6.2/i']     = 'Windows 8';
        $osList['/windows nt 6.1/i']     = 'Windows 7';
        $osList['/windows nt 6.0/i']     = 'Windows Vista';
        $osList['/windows nt 5.2/i']     = 'Windows Server 2003/XP x64';
        $osList['/windows nt 5.1/i']     = 'Windows XP';
        $osList['/windows xp/i']         = 'Windows XP';
        $osList['/windows nt 5.0/i']     = 'Windows 2000';
        $osList['/windows me/i']         = 'Windows ME';
        $osList['/win98/i']              = 'Windows 98';
        $osList['/win95/i']              = 'Windows 95';
        $osList['/win16/i']              = 'Windows 3.11';
        $osList['/macintosh|mac os x/i'] = 'Mac OS X';
        $osList['/mac_powerpc/i']        = 'Mac OS 9';
        $osList['/linux/i']              = 'Linux';
        $osList['/ubuntu/i']             = 'Ubuntu';
        $osList['/iphone/i']             = 'iPhone';
        $osList['/ipod/i']               = 'iPod';
        $osList['/ipad/i']               = 'iPad';
        $osList['/android/i']            = 'Android';
        $osList['/blackberry/i']         = 'BlackBerry';
        $osList['/webos/i']              = 'Mobile';

        foreach ($osList as $regex => $value)
        {
            if(preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) return $value;
        }

        return 'unknown';
    }

    /**
     *  计算两个日期相差的天数，取整。
     *  Compute the diff days of two date.
     *
     * @param   string $date1   the first date.
     * @param   string $date2   the sencond date.
     * @access  public
     * @return  int  the diff of the two days.
     */
    static public function diffDate($date1, $date2)
    {
        /* Get the timestamp in the current operating system. */
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $date1 = date_format($date1, "U");
        $date2 = date_format($date2, "U");
        return round(($date1 - $date2) / 86400, 0);
    }

    /**
     *  获取当前时间，使用common语言文件定义的DT_DATETIME1常量。
     *  Get now time use the DT_DATETIME1 constant defined in the lang file.
     *
     * @access  public
     * @return  string  now
     */
    static public function now()
    {
        return date(DT_DATETIME1);
    }

    /**
     *  获取当前日期，使用common语言文件定义的DT_DATE1常量。
     *  Get today according to the  DT_DATE1 constant defined in the lang file.
     *
     * @access  public
     * @return  string  today
     */
    static public function today()
    {
        return date(DT_DATE1);
    }

    /**
     *  获取当前日期，使用common语言文件定义的DT_TIME1常量。
     *  Get now time use the DT_TIME1 constant defined in the lang file.
     *
     * @access  public
     * @return  string  today
     */
    static public function time()
    {
        return date(DT_TIME1);
    }

    /**
     *  判断日期是不是零。
     *  Judge a date is zero or not.
     *
     * @access  public
     * @return  bool
     */
    static public function isZeroDate($date)
    {
        return (empty($date) or substr($date, 0, 4) <= '1970');
    }

    /**
     *  列出目录中符合该正则表达式的文件。
     *  Get files match the pattern under a directory.
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
     * 切换目录。第一次调用的时候记录当前的路径，再次调用的时候切换回之前的路径。
     * Change directory: first call, save the $cwd, secend call, change to $cwd.
     *
     * @param  string $path
     * @static
     * @access public
     * @return void
     */
    static public function cd($path = '')
    {
        static $cwd = '';
        if($path) $cwd = getcwd();
        !empty($path) ? chdir($path) : chdir($cwd);
    }

    /**
     * 通过域名获取站点代号。
     * Get siteCode for a domain.
     *
     * www.xirang.com => xirang
     * xirang.com     => xirang
     * xirang.com.cn  => xirang
     * xirang.cn      => xirang
     * xirang         => xirang
     *
     * @param  string $domain
     * @return string $siteCode
     **/
    public static function parseSiteCode($domain)
    {
        global $config;

        /* 去除域名中的端口部分。Remove the port part of the domain. */
        if(strpos($domain, ':') !== false) $domain = substr($domain, 0, strpos($domain, ':'));
        $domain = strtolower($domain);

        /* $config里面有定义或者是localhost，直接返回。 Return directly if defined in $config or is localhost. */
        if(isset($config->siteCodeList[$domain])) return $config->siteCodeList[$domain];
        if($domain == 'localhost') return $domain;

        /* 将域名中的-改为_。Replace '-' with '_' in the domain. */
        $domain = str_replace('-', '_', $domain);
        $items  = explode('.', $domain);

        /* 类似a.com的形式。 Domain like a.com. */
        $postfix = str_replace($items[0] . '.', '', $domain);
        if(isset($config->domainPostfix) and strpos($config->domainPostfix, "|$postfix|") !== false) return $items[0];

        /* 类似www.a.com的形式。 Domain like www.a.com. */
        $postfix = str_replace($items[0] . '.' . $items[1] . '.', '', $domain);
        if(isset($config->domainPostfix) and strpos($config->domainPostfix, "|$postfix|") !== false) return $items[1];

        /* 类似xxx.sub.a.com的形式。 Domain like xxx.sub.a.com. */
        $postfix = str_replace($items[0] . '.' . $items[1] . '.' . $items[2] . '.', '', $domain);
        if(isset($config->domainPostfix) and strpos($config->domainPostfix, "|$postfix|") !== false) return $items[0];

        return '';
    }

    /**
     * 检查是否是AJAX请求。
     * Check is ajax request.
     *
     * @param  ?string $type   zin|modal|fetch
     * @static
     * @access public
     * @return bool
     */
    public static function isAjaxRequest(?string $type = null): bool
    {
        $isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') || (isset($_GET['HTTP_X_REQUESTED_WITH']) && $_GET['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
        if($isAjax === false) return false;

        if($type === 'zin')   return array_key_exists('HTTP_X_ZIN_OPTIONS', $_SERVER);
        if($type === 'modal') return isset($_SERVER['HTTP_X_ZUI_MODAL']) && $_SERVER['HTTP_X_ZUI_MODAL'] == true;
        if($type === 'fetch') return !array_key_exists('HTTP_X_ZIN_OPTIONS', $_SERVER) && !(isset($_SERVER['HTTP_X_ZUI_MODAL']) && $_SERVER['HTTP_X_ZUI_MODAL'] == true);

        return $isAjax;
    }

    /**
     * 301跳转。
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
     * 获取远程IP。
     * Get remote ip.
     *
     * @param  bool  $proxy
     * @access public
     * @return string
     */
    public static function getRemoteIp($proxy = true)
    {
        $ip = '';
        if(!empty($_SERVER["REMOTE_ADDR"])) $ip = $_SERVER["REMOTE_ADDR"];

        if($proxy)
        {
            if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            if(!empty($_SERVER['HTTP_CLIENT_IP']))       $ip = $_SERVER['HTTP_CLIENT_IP'];
        }

        return $ip;
    }

    /**
     * Restart session.
     *
     * @param  string $sessionID
     * @static
     * @access public
     * @return void
     */
    public static function restartSession($sessionID = '')
    {
        if(empty($sessionID)) $sessionID = sha1(mt_rand());

        session_write_close();
        session_id($sessionID);
        if(ini_get('session.save_handler') == 'user' and isset($_GET['tid']))
        {
            $ztSessionHandler = new ztSessionHandler($_GET['tid']);
            session_set_save_handler(
                array($ztSessionHandler, "open"),
                array($ztSessionHandler, "close"),
                array($ztSessionHandler, "read"),
                array($ztSessionHandler, "write"),
                array($ztSessionHandler, "destroy"),
                array($ztSessionHandler, "gc")
            );
            register_shutdown_function('session_write_close');
        }
        session_start();
    }

    /**
     * Check DB to repair table.
     *
     * @param  object  $exception
     * @static
     * @access public
     * @return string
     */
    public static function checkDB2Repair($exception)
    {
        global $config, $lang;

        $repairCode = '|1034|1035|1194|1195|1459|';
        $errorInfo  = $exception->errorInfo;
        $errorCode  = $errorInfo[1];
        $errorMsg   = $errorInfo[2];
        $message    = $exception->getMessage();

        if(strpos($repairCode, "|$errorCode|") !== false or ($errorCode == '1016' and strpos($errorMsg, 'errno: 145') !== false) or strpos($message, 'repair') !== false)
        {
            if(isset($config->framework->autoRepairTable) and $config->framework->autoRepairTable)
            {
                header("location: " . $config->webRoot . 'checktable.php');
                exit;
            }
            return $lang->repairTable;
        }

        return null;
    }

    /**
     * Send a cookie.
     *
     * @param string      $name
     * @param string      $value
     * @param int|null    $expire
     * @param string|null $path
     * @param string      $domain
     * @param bool|null   $secure
     * @param bool        $httponly
     * @static
     * @access public
     * @return bool
     */
    public static function setcookie(string $name, string $value = '', int $expire = null, string $path = null, string $domain = '', bool $secure = null, bool $httponly = true)
    {
        global $config, $app;

        if($expire === null) $expire = $config->cookieLife;
        if($path   === null) $path   = $config->webRoot;
        if($secure === null) $secure = $config->cookieSecure;

        if(isset($app->worker))
        {
            $app->worker->response->setCookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }
        else
        {
            return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }
    }

    /**
     * 设置状态码。
     * Set status code.
     *
     * @param int $code
     * @static
     * @access public
     * @return void
     */
    static public function setStatus(int $code)
    {
        global $app;

        if(isset($app->worker))
        {
            $app->worker->response->setStatus($code);
        }
        else
        {
            $PHRASES = array(
                100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing',
                200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-status', 208 => 'Already Reported',
                300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => 'Switch Proxy', 307 => 'Temporary Redirect',
                400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large', 415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable', 417 => 'Expectation Failed', 418 => 'I\'m a teapot', 422 => 'Unprocessable Entity', 423 => 'Locked', 424 => 'Failed Dependency', 425 => 'Unordered Collection', 426 => 'Upgrade Required', 428 => 'Precondition Required', 429 => 'Too Many Requests', 431 => 'Request Header Fields Too Large', 451 => 'Unavailable For Legal Reasons',
                500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Time-out', 505 => 'HTTP Version not supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage', 508 => 'Loop Detected', 511 => 'Network Authentication Required',
            );
            header('HTTP/1.1 ' . (string)$code . ' ' . $PHRASES[$code], true, $code);
        }
    }

    /**
     * 发送HTTP头信息。
     * Send http header.
     *
     * @param string $key
     * @param string $value
     * @param bool   $replace
     * @param int    $response_code
     * @static
     * @access public
     * @return void
     */
    static public function header(string $key, string $value, bool $replace = true, int $response_code = 0)
    {
        global $app;

        if(isset($app->worker))
        {
            $key = trim(strtolower($key));
            $app->worker->response->setHeader($key, $value);

            if($key == 'location')
            {
                $app->worker->response->setStatus(302);
                helper::end();
            }
        }
        else
        {
            header($key . ': ' . $value, $replace, $response_code);
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
     * Generate rand string.
     *
     * @param  int    $length
     * @access public
     * @return string
     */
    static public function randStr($length = 4)
    {
        $seeds = str_shuffle('abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789');
        return substr($seeds, 0, $length);
    }

    /**
     * 获取二维数组中的某一列。
     * Get array column to array.
     *
     * @param  array           $input
     * @param  int|string|null $columnKey
     * @param  int|string|null $indexKey
     * @static
     * @access public
     * @return array
     */
    public static function arrayColumn(array $input, $columnKey, $indexKey = null): array
    {
        /* If php version greater than 7, calling system functions returns. */
        if(defined('PHP_VERSION_ID') && PHP_VERSION_ID >= 70000) return \array_column($input, $columnKey, $indexKey);

        $output = array();
        foreach($input as $row)
        {
            $key    = $value = null;
            $keySet = $valueSet = false;

            if($indexKey !== null && array_key_exists($indexKey, (array) $row))
            {
                $keySet = true;
                $key    = \is_object($row) ? (string) $row->$indexKey : (string) $row[$indexKey];
            }

            if(null === $columnKey)
            {
                $valueSet = true;
                $value    = $row;
            }
            elseif(\is_array($row) && \array_key_exists($columnKey, $row))
            {
                $valueSet = true;
                $value    = $row[$columnKey];
            }
            elseif(\is_object($row) && \property_exists($row, $columnKey))
            {
                $valueSet = true;
                $value    = $row->$columnKey;
            }

            if($valueSet)
            {
                if($keySet)
                {
                    $output[$key] = $value;
                }
                else
                {
                    $output[] = $value;
                }
            }
        }

        return $output;
    }

    /**
     * 在本地语言没有中文的环境下解析pathinfo.
     * pathinfo of utf-8.
     *
     * @param  string $filepath
     * @access public
     * @return array
     */
    public static function mbPathinfo($filepath)
    {
        $ret = array('dirname' => '', 'basename' => '', 'extension' => '', 'filename' => '');
        preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);

        $ret['dirname']   = !empty($m[1]) ? $m[1] : '';
        $ret['basename']  = !empty($m[2]) ? $m[2] : '';
        $ret['extension'] = !empty($m[5]) ? $m[5] : '';
        $ret['filename']  = !empty($m[3]) ? $m[3] : '';

        return $ret;
    }
}

//------------------------------- 常用函数。Some tool functions.-------------------------------//

/**
 *  helper::createLink()的别名，方便创建本模块方法的链接。
 *  The short alias of helper::createLink() method to create link to control method of current module.
 *
 * @param  string        $methodName  the method name
 * @param  string|array  $vars        the params passed to the method, can be array('key' => 'value') or key1=value1&key2=value2)
 * @param  string        $viewType
 * @return string the link string.
 */
function inLink($methodName = 'index', $vars = '', $viewType = '', $onlybody = false)
{
    global $app;
    return helper::createLink($app->getModuleName(), $methodName, $vars, $viewType, $onlybody);
}

/**
 *  通过一个静态游标，可以遍历数组。
 *  Static cycle a array.
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
 * 获取当前时间的Unix时间戳，精确到微妙。
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
 * @return bool
 */
function isLocalIP()
{
    global $config;
    if(isset($config->islocalIP)) return $config->isLocalIP;
    $serverIP = $_SERVER['SERVER_ADDR'];
    if($serverIP == '127.0.0.1' or $serverIP == '::1') return true;
    if(strpos($serverIP, '10.70') !== false) return false;
    return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

/**
 * 获取webRoot。
 * Get web root.
 *
 * @access public
 * @return string
 */
function getWebRoot($full = false)
{
    if(getenv('APP_WEB_ROOT')) return '/' . trim(getenv('APP_WEB_ROOT'), '/') . '/';

    $path = $_SERVER['SCRIPT_NAME'];

    if(PHP_SAPI == 'cli')
    {
        if(isset($_SERVER['argv'][1]))
        {
            $url  = parse_url($_SERVER['argv'][1]);
            $path = empty($url['path']) ? '/' : rtrim($url['path'], '/');
        }
        $path = empty($path) ? '/' : preg_replace('/\/www$/', '/www/', $path);
    }

    if($full)
    {
        $http = (isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) != 'off') ? 'https://' : 'http://';
        return $http . $_SERVER['HTTP_HOST'] . substr($path, 0, (strrpos($path, '/') + 1));
    }

    $path = substr($path, 0, (strrpos($path, '/') + 1));
    $path = str_replace('\\', '/', $path);
    return $path;
}

/**
 * 当数组/对象变量$var存在$key项时，返回存在的对应值或设定值，否则返回$key或不存在的设定值。
 * When the $var has the $key, return it, else result one default value.
 *
 * @param  array|object    $var
 * @param  string|int      $key
 * @param  mixed           $valueWhenNone     value when the key not exits.
 * @param  mixed           $valueWhenExists   value when the key exits.
 * @access public
 * @return mixed
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
 * Is https.
 *
 * @access public
 * @return bool
 */
function isHttps()
{
    if(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') return true;
    if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') return true;
    if(!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') return true;
    return false;
}

/**
 * Compatibility for htmlspecialchars.
 *
 * @param  string $string
 * @param  int    $flags
 * @param  string $encoding
 * @access public
 * @return string
 */
function htmlSpecialString($string, $flags = '', $encoding = 'UTF-8')
{
    if(!$flags) $flags = defined('ENT_SUBSTITUTE') ? ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 : ENT_QUOTES;
    return htmlspecialchars($string, $flags, $encoding);
}

if(!function_exists('array_column'))
{
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $output = array();

        foreach($input as $row)
        {
            $key    = $value = null;
            $keySet = $valueSet = false;

            if(null !== $indexKey && array_key_exists($indexKey, $row))
            {
                $keySet = true;
                $key    = (string) $row[$indexKey];
            }

            if(null === $columnKey)
            {
                $valueSet = true;
                $value    = $row;
            }
            elseif(\is_array($row) && \array_key_exists($columnKey, $row))
            {
                $valueSet = true;
                $value    = $row[$columnKey];
            }
            elseif(\is_object($row) && \property_exists($row, $columnKey))
            {
                $valueSet = true;
                $value    = $row->$columnKey;
            }

            if($valueSet)
            {
                if($keySet)
                {
                    $output[$key] = $value;
                }
                else
                {
                    $output[] = $value;
                }
            }
        }

        return $output;
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
