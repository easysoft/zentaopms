<?php
/**
 * The helper class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The helper class, contains the tool functions.
 *
 * @package framework
 */
class helper
{
    /**
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
     * Create a link to a module's method.
     * 
     * This method also mapped in control class to call conveniently.
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
     * @static
     * @access public
     * @return string the link string.
     */
    static public function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = '', $onlybody = false)
    {
        global $app, $config;
        $link = $config->requestType == 'PATH_INFO' ? $config->webRoot : $_SERVER['PHP_SELF'];

        /* Set the view type and vars. */
        if(empty($viewType)) $viewType = $app->getViewType();
        if(!is_array($vars)) parse_str($vars, $vars);

        /* The PATH_INFO type. */
        if($config->requestType == 'PATH_INFO')
        {
            /* If the method equal the default method defined in the config file and the vars is empty, convert the link. */
            if($methodName == $config->default->method and empty($vars))
            {
                /* If the module also equal the default module, change index-index to index.html. */
                if($moduleName == $config->default->module)
                {
                    $link .= 'index.' . $viewType;
                }
                else
                {
                    $link .= $moduleName . '/';
                }
            }
            else
            {
                $link .= "$moduleName{$config->requestFix}$methodName";
                if($config->pathType == 'full')
                {
                    foreach($vars as $key => $value) $link .= "{$config->requestFix}$key{$config->requestFix}$value";
                }
                else
                {
                    foreach($vars as $value) $link .= "{$config->requestFix}$value";
                }    
                $link .= '.' . $viewType;
            }
        }
        elseif($config->requestType == 'GET')
        {
            $link .= "?{$config->moduleVar}=$moduleName&{$config->methodVar}=$methodName";
            if($viewType != 'html') $link .= "&{$config->viewVar}=" . $viewType;
            foreach($vars as $key => $value) $link .= "&$key=$value";
        }

        /* if page has onlybody param then add this param in all link. the param hide header and footer. */
        if($onlybody or isonlybody())
        {
            $onlybody = $config->requestType == 'PATH_INFO' ? "?onlybody=yes" : "&onlybody=yes";
            $link .= $onlybody;
        }
        return $link;
    }

    /**
     * Import a file instend of include or requie.
     * 
     * @param string    $file   the file to be imported.
     * @static
     * @access public
     * @return bool
     */
    static public function import($file)
    {
        static $includedFiles = array();
        if(!isset($includedFiles[$file]))
        {
            $return = include $file;
            if(!$return) return false;
            $includedFiles[$file] = true;
            return true;
        }
        return true;
    }

    /**
     * Set the model file of one module. If there's an extension file, merge it with the main model file.
     * 
     * @param   string $moduleName the module name
     * @static
     * @access  public
     * @return  string the model file
     */
    static public function setModelFile($moduleName)
    {
        global $app;

        /* Set the main model file and extension and hook pathes and files. */
        $mainModelFile = $app->getModulePath($moduleName) . 'model.php';
        $modelExtPath  = $app->getModuleExtPath($moduleName, 'model');
        $modelHookPath = $modelExtPath . 'hook/';
        $extFiles      = helper::ls($modelExtPath, '.php');
        $hookFiles     = helper::ls($modelHookPath, '.php');

        /* If no extension files and no hook files, return the main file directly. */
        if(empty($extFiles) and empty($hookFiles)) return $mainModelFile;

        /* Else, judge whether needed update or not .*/
        $needUpdate      = false;
        $mergedModelFile = $app->getTmpRoot() . 'model' . $app->getPathFix() . $moduleName . '.php';
        $lastTime        = file_exists($mergedModelFile) ? filemtime($mergedModelFile) : 0;

        while(!$needUpdate)
        {
            foreach($extFiles  as $extFile) if(filemtime($extFile)  > $lastTime) break 2;
            foreach($hookFiles as $hookFile) if(filemtime($hookFile) > $lastTime) break 2;

            if(is_dir($modelExtPath ) and filemtime($modelExtPath)  > $lastTime) break;
            if(is_dir($modelHookPath) and filemtime($modelHookPath) > $lastTime) break;

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
        $modelLines      .= "helper::import('$mainModelFile');\n";
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
        if(!@file_put_contents($mergedModelFile, $modelLines))
        {
            die("ERROR: $mergedModelFile not writable, please make sure the " . dirname($mergedModelFile) . ' directory exists and writable');
        }
        if(!class_exists($extTmpModelClass))include $mergedModelFile;

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
        $mergedModelCodes = file($mergedModelFile);
        foreach($hookedMethods as $method)
        {
            /* Reflection the hooked method to get it's defined position. */
            $methodRelfection = new reflectionMethod($extTmpModelClass, $method);
            $definedFile = $methodRelfection->getFileName();
            $startLine   = $methodRelfection->getStartLine() . ' ';
            $endLine     = $methodRelfection->getEndLine() . ' ';

            /* Merge hook codes. */
            $oldCodes = $definedFile == $mergedModelFile ? $mergedModelCodes : $mainModelCodes;
            $oldCodes = join("", array_slice($oldCodes, $startLine - 1, $endLine - $startLine + 1));
            $openBrace = strpos($oldCodes, '{');
            $newCodes = substr($oldCodes, 0, $openBrace + 1) . "\n" . join("\n", $hookCodes[$method]) . substr($oldCodes, $openBrace + 1);

            /* Replace it. */
            if($definedFile == $mergedModelFile)
            {
                $modelLines = str_replace($oldCodes, $newCodes, $modelLines);
            }
            else
            {
                $modelLines = str_replace($replaceMark, $newCodes . "\n$replaceMark", $modelLines);
            }
        }
        
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
     * Create the in('a', 'b') string.
     * 
     * @param   string|array $ids   the id lists, can be a array or a string with ids joined with comma.
     * @static
     * @access  public
     * @return  string  the string like IN('a', 'b').
     */
    static public function dbIN($ids)
    {
        if(is_array($ids)) return "IN ('" . join("','", $ids) . "')";
        return "IN ('" . str_replace(',', "','", str_replace(' ', '',$ids)) . "')";
    }

    /**
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
     *  Compute the diff days of two date.
     * 
     * @param   date  $date1   the first date.
     * @param   date  $date2   the sencode date.
     * @access  public
     * @return  int  the diff of the two days.
     */
    static public function diffDate($date1, $date2)
    {
        return round((strtotime($date1) - strtotime($date2)) / 86400, 0);
    }

    /**
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
     *  Get files match the pattern under one directory.
     * 
     * @access  public
     * @return  array   the files match the pattern
     */
    static public function ls($dir, $pattern = '')
    {
        $files = array();
        $dir = realpath($dir);
        if(is_dir($dir)) $files = glob($dir . DIRECTORY_SEPARATOR . '*' . $pattern);
        return empty($files) ? array() : $files;
    }

    /**
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
        if($path)
        {
            $cwd = getcwd();
            chdir($path);
        }
        else
        {
            chdir($cwd);
        }
    }

    /**
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
     * Set viewType.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function setViewType()
    {
        global $config, $app;
        if($config->requestType == 'PATH_INFO')
        {
            $pathInfo = $app->getPathInfo('PATH_INFO');
            if(empty($pathInfo)) $pathInfo = $app->getPathInfo('ORIG_PATH_INFO');
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
}

/**
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
 * Get current microtime.
 * 
 * @access protected
 * @return float current time.
 */
function getTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
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
 * When the $var has the $key, return it, esle result one default value.
 * 
 * @param  array|object    $var 
 * @param  string|int      $key 
 * @param  mixed           $valueWhenNone     value when the key not exits.
 * @param  mixed           $valueWhenExists   value when the key exits.
 * @access public
 * @return void
 */
function zget($var, $key, $valueWhenNone = '', $valueWhenExists = '')
{
    $var = (array)$var;
    if(isset($var[$key]))
    {
        if($valueWhenExists) return $valueWhenExists;
        return $var[$key];
    }
    return $valueWhenNone;
}

/**
 * Judge the server ip is local or not.
 *
 * @access public
 * @return void
 */
function isLocalIP()
{
    $serverIP = $_SERVER['SERVER_ADDR'];
    if($serverIP == '127.0.0.1') return true;
    return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

/**
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
 * Check exist onlybody param.
 * 
 * @access public
 * @return void
 */
function isonlybody()
{
    return (isset($_GET['onlybody']) and $_GET['onlybody'] == 'yes');
}
