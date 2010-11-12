<?php
/**
 * The helper class file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: helper.class.php 111 2010-05-11 08:17:32Z wwccss $
 * @link        http://www.zentao.net
 */
/**
 * The helper class, contains the tool functions.
 *
 * @package ZenTaoPMS
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
     * @return void
     */
    static public function setMember($objName, $key, $value)
    {
        global $$objName;
        if(!is_object($$objName) or empty($key)) return false;
        $key   = str_replace('.', '->', $key);
        $value = serialize($value);
        $code  = ("\$${objName}->{$key}=unserialize(<<<EOT\n$value\nEOT\n);");
        eval($code);
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
     * @param string    $moduleName     module name
     * @param string    $methodName     method name
     * @param mixed     $vars           the params passed to the method, can be array('key' => 'value') or key1=value1&key2=value2) or key1=value1&key2=value2
     * @param string    $viewType       the view type
     * @static
     * @access public
     * @return string
     */
    static public function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = '')
    {
        global $app, $config;

        $link = $config->webRoot;
        if($config->requestType == 'GET')
        {
            if(strpos($_SERVER['SCRIPT_NAME'], 'index.php') === false)
            {
                $link = $_SERVER['SCRIPT_NAME'];
            }
        }

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
        if(!file_exists($file)) return false;
        static $includedFiles = array();
        if(!isset($includedFiles[$file]))
        {
            include $file;
            $includedFiles[$file] = true;
            return true;
        }
        return false;
    }

    /**
     * Set the model file of one module. If there's an extension file, merge it with the main model file.
     * 
     * @param   string      $moduleName     the module name
     * @static
     * @access  public
     * @return  string      the model file
     */
    static public function setModelFile($moduleName)
    {
        global $app;

        /* Set the main model file and extension path and files. */
        $mainModelFile = $app->getModulePath($moduleName) . 'model.php';
        $modelExtPath  = $app->getModuleExtPath($moduleName, 'model');
        $extFiles      = helper::ls($modelExtPath, '.php');

        /* If no extension file, return the main file directly. */
        if(empty($extFiles)) return $mainModelFile;

        /* Else, judge whether needed update or not .*/
        $mergedModelFile = $app->getTmpRoot() . 'model' . $app->getPathFix() . $moduleName . '.php';
        $needUpdate      = false;
        $lastTime        = file_exists($mergedModelFile) ? filemtime($mergedModelFile) : 0;
        foreach($extFiles as $extFile)
        {
            if(filemtime($extFile) > $lastTime)
            {
                $needUpdate = true;
                break;
            }
        }
        if(filemtime($mainModelFile) > $lastTime) $needUpdate = true;

        /* If need'nt update, return the cache file. */
        if(!$needUpdate) return $mergedModelFile;

        /* Update the cache file. */
        if($needUpdate)
        {
            $modelClass    = $moduleName . 'Model';
            $extModelClass = 'ext' . $modelClass;
            $modelLines    = trim(file_get_contents($mainModelFile));
            $modelLines    = rtrim($modelLines, '?>');     // To make sure the last end tag is removed.
            $modelLines   .= "class $extModelClass extends $modelClass {\n";

            /* Cycle all the extension files. */
            foreach($extFiles as $extFile)
            {
                $extLines = trim(file_get_contents($extFile));
                if(strpos($extLines, '<?php') !== false) $extLines = ltrim($extLines, '<?php');
                if(strpos($extLines, '?>')    !== false) $extLines = rtrim($extLines, '?>');
                $modelLines .= $extLines . "\n";
            }

            /* Create the merged model file. */
            $modelLines .= "}";
            file_put_contents($mergedModelFile, $modelLines);

            return $mergedModelFile;
        }
    }

    /**
     * Create the in('a', 'b') string.
     * 
     * @param   misc    $ids   the id lists, can be a array or a string with ids joined with comma.
     * @static
     * @access  public
     * @return  string
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
     * @return  string
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
     * @return  string
     */
    static public function safe64Decode($string)
    {
        return base64_decode(strtr($string, '.', '/'));
    }

    /**
     *  Compute the diff days of two date.
     * 
     * @param   date  $date1   the first date.
     * @param   date  $date2   the sencode date.
     * @access  public
     * @return  string  the diff of the two days.
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
     * @return  date  today
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
        if(is_dir($dir))
        {
            if($dh = opendir($dir))
            {
                while(($file = readdir($dh)) !== false) 
                {
                    if(strpos($file, $pattern) !== false) $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                }
                closedir($dh);
            }
        }
        return $files;
    }
}

/**
 *  The short alias of helper::createLink() method. 
 *
 * @param string $methodName  the method name
 * @param mixed  $vars        the vars to passed
 * @param string $viewType    
 * @return string
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
 * @return float        current time.
 */
function getTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Save the sql.
 * 
 * @access protected
 * @return void
 */
function saveSQL()
{
    if(!class_exists('dao')) return;
    global $app;
    $sqlLog = $app->getLogRoot() . 'sql.' . date('Ymd') . '.log';
    $fh = @fopen($sqlLog, 'a');
    if(!$fh) return false;
    fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . "\n");
    foreach(dao::$querys as $query) fwrite($fh, "  $query\n");
    fwrite($fh, "\n");
    fclose($fh);
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
