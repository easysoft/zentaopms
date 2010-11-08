<?php
/**
 * The helper class file of ZenTaoPHP.
 *
 * ZenTaoPHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoPHP.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id: helper.class.php 138 2010-10-08 03:40:48Z wwccss $
 * @link        http://www.zentao.net
 */
/**
 * 工具类对象，存放着各种杂项的工具方法。
 *
 * @package ZenTaoPHP
 */
class helper
{
    /**
     * 为一个对象设置某一个属性，其中key可以是“father.child”的形式。
     * 
     * <code>
     * <?php
     * $lang->db->user = 'wwccss';
     * helper::setMember('lang', 'db.user', 'chunsheng.wang');
     * ?>
     * </code>
     * @param string    $objName    对象变量名。
     * @param string    $key        要设置的属性，可以是father.child的形式。
     * @param mixed     $value      要设置的值。
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
     * 生成某一个模块某个方法的链接。
     * 
     * 在control类中对此方法进行了封装，可以在control对象中直接调用createLink方法。
     * <code>
     * <?php
     * helper::createLink('hello', 'index', 'var1=value1&var2=value2');
     * helper::createLink('hello', 'index', array('var1' => 'value1', 'var2' => 'value2');
     * ?>
     * </code>
     * @param string    $moduleName     模块名。
     * @param string    $methodName     方法名。
     * @param mixed     $vars           要传递给method方法的各个参数，可以是数组，也可以是var1=value2&var2=value2的形式。
     * @param string    $viewType       扩展名方式。
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

        if(empty($viewType)) $viewType = $app->getViewType();

        /* 如果传递进来的vars不是数组，尝试将其解析成数组格式。*/
        if(!is_array($vars)) parse_str($vars, $vars);
        if($config->requestType == 'PATH_INFO')
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
            /* 如果访问的是/index/index.html，简化为/index.html。*/
            if($moduleName == $config->default->module and $methodName == $config->default->method) $link = $config->webRoot . 'index';
            $link .= '.' . $viewType;
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
     * 将一个数组转成对象格式。此函数只是返回语句，需要eval。
     * 
     * <code>
     * <?php
     * $config['user'] = 'wwccss';
     * eval(helper::array2Object($config, 'configobj');
     * print_r($configobj);
     * ?>
     * </code>
     * @param array     $array          要转换的数组。
     * @param string    $objName        要转换成的对象的名字。
     * @param string    $memberPath     成员变量路径，最开始为空，从根开始。
     * @param bool      $firstRun       是否是第一次运行。
     * @static
     * @access public
     * @return void
     */
    static public function array2Object($array, $objName, $memberPath = '', $firstRun = true)
    {
        if($firstRun)
        {
            if(!is_array($array) or empty($array)) return false;
        }    
        static $code = '';
        $keys = array_keys($array);
        foreach($keys as $keyNO => $key)
        {
            $value = $array[$key];
            if(is_int($key)) $key = 'item' . $key;
            $memberID = $memberPath . '->' . $key;
            if(!is_array($value))
            {
                $value = addslashes($value);
                $code .= "\$$objName$memberID='$value';\n";
            }
            else
            {
                helper::array2object($value, $objName, $memberID, $firstRun = false);
            }
        }
        return $code;
    }

    /**
     * 包含一个文件。router.class.php和control.class.php中包含文件都通过此函数来调用，这样保证文件不会重复加载。
     * 
     * @param string    $file   要包含的文件的路径。 
     * @static
     * @access public
     * @return void
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
     * 设置model文件。
     * 
     * @param   string      $moduleName     模块名字。
     * @access  private
     * @return void
     */
    static public function setModelFile($moduleName)
    {
        global $app;

        /* 设定主model文件和扩展路径，并获得所有的扩展文件。*/
        $mainModelFile = $app->getModulePath($moduleName) . 'model.php';
        $modelExtPath  = $app->getModuleExtPath($moduleName, 'model');
        $extFiles      = helper::ls($modelExtPath, '.php');

        /* 不存在扩展文件，返回主配置文件。*/
        if(empty($extFiles)) return $mainModelFile;

        /* 存在扩展文件，判断是否需要更新。*/
        $mergedModelFile = $app->getTmpRoot() . 'model' . $app->getPathFix() . $moduleName . '.php';
        $needUpdate      = false;
        $lastTime        = file_exists($mergedModelFile) ? filemtime($mergedModelFile) : 0;
        
        if(filemtime($mainModelFile) > $lastTime)
        {
            $needUpdate = true;
        }
        else
        {
            foreach($extFiles as $extFile)
            {
                if(filemtime($extFile) > $lastTime)
                {
                    $needUpdate = true;
                    break;
                }
            }
        }

        /* 如果不需要更新，则直接返回合并之后的model文件。*/
        if(!$needUpdate) return $mergedModelFile;

        if($needUpdate)
        {
            /* 加载主的model文件，并获得其方法列表。*/
            helper::import($mainModelFile);
            $modelMethods = get_class_methods($moduleName . 'model');
            foreach($modelMethods as $key => $modelMethod) $modelMethods[$key] = strtolower($modelMethod);

            /* 将主model文件读入数组。*/
            $modelLines = rtrim(file_get_contents($mainModelFile));
            $modelLines = rtrim($modelLines, '?>');
            $modelLines = rtrim($modelLines);
            $modelLines = explode("\n", $modelLines);
            $lines2Delete = array(count($modelLines) - 1);
            $lines2Append = array();

            /* 循环处理每个扩展方法文件。*/
            foreach($extFiles as $extFile)
            {
                $methodName = strtolower(basename($extFile, '.php'));
                if(in_array($methodName, $modelMethods))
                {
                    $method       = new ReflectionMethod($moduleName . 'model', $methodName);
                    $startLine    = $method->getStartLine() - 1;
                    $endLine      = $method->getEndLine() - 1;
                    $lines2Delete = array_merge($lines2Delete, range($startLine, $endLine));
                }
                $extLines     = explode("\n", ltrim(trim(file_get_contents($extFile)), '<?php'));
                $lines2Append = array_merge($lines2Append, $extLines);
            }

            /* 生成新的model文件。*/
            $lines2Append[] = '}';
            foreach($lines2Delete as $lineNO) unset($modelLines[$lineNO]);
            $modelLines = array_merge($modelLines, $lines2Append);
            if(!is_dir(dirname($mergedModelFile))) mkdir(dirname($mergedModelFile));
            $modelLines = join("\n", $modelLines);
            $modelLines = str_ireplace($moduleName . 'model', 'ext' . $moduleName . 'model', $modelLines); // 类名修改。
            file_put_contents($mergedModelFile, $modelLines);

            return $mergedModelFile;
        }
    }

    /**
     * 生成SQL查询中的IN(a,b,c)部分代码。
     * 
     * @param   misc    $ids   id列表，可以是数组，也可以是使用逗号隔开的字符串。 
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
     * 生成对框架安全的base64encode串。
     * 
     * @param   string  $string   要编码的字符串列表。
     * @static
     * @access  public
     * @return  string
     */
    static public function safe64Encode($string)
    {
        return strtr(base64_encode($string), '/', '.');
    }

    /**
     * 解码。
     * 
     * @param   string  $string   要解码的字符串列表。
     * @static
     * @access  public
     * @return  string
     */
    static public function safe64Decode($string)
    {
        return base64_decode(strtr($string, '.', '/'));
    }

    /**
     *  计算两个日期的差。
     * 
     * @param   date  $date1   第一个时间
     * @param   date  $date2   第二个时间
     * @access  public
     * @return  string
     */
    static public function diffDate($date1, $date2)
    {
        return round((strtotime($date1) - strtotime($date2)) / 86400, 0);
    }

    /* 获得当前的时间。*/
    static public function now()
    {
        return date(DT_DATETIME1);
    }

    /* 获得今天的日期。*/
    static public function today()
    {
        return date(DT_DATE1);
    }

    /* 判断是否0000-00-00格式的日期。*/
    static public function isZeroDate($date)
    {
        return substr($date, 0, 4) == '0000';
    }

    /* 获得某一个目录下面含有某个特征字符串的所有文件。*/
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

    /* 切换目录。*/
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
}

/* 别名函数，生成对内部方法的链接。 */
function inLink($methodName = 'index', $vars = '', $viewType = '')
{
    global $app;
    return helper::createLink($app->getModuleName(), $methodName, $vars, $viewType);
}

/* 循环一个数组。*/
function cycle($items)
{
    static $i = 0;
    if(!is_array($items)) $items = explode(',', $items);
    if(!isset($items[$i])) $i = 0;
    return $items[$i++];
}
