<?php
define('DS', DIRECTORY_SEPARATOR);

class apiCheckModel
{

    /**
     * Eg: ztf run api.php $path $entries.
     *
     * @access public
     * @return string
     */
    public function __construct()
    {
        global $argv;
        $path    = $argv[1];
        $entries = '';
        if(isset($argv[2])) $entries = $argv[2];
        $this->run($argv[1], $entries);
    }

    public $ztPath;

    /**
     * Run generate report.
     *
     * @param  string $path
     * @param  string $entries
     * @access public
     * @return array
     */
    public function run($path, $entries = '')
    {
        $independent    = array();
        $strIndependent = '';

        if($entries)
        {
            $results = $this->checkInput($path);
            echo $results ? 'true' : 'false' ;

            foreach($results as $result)
            {
                if(strpos($result['filePath'], $entries) != false) $independent[count($independent)] = $result;
            }

            $strIndependent .= '<?php' . "\n" . '$result = new stdclass;' . "\n";
            foreach($independent as $key => $array)
            {
                $strIndependent .= '$result->key' . $key . ' = new stdclass;' . ";\n";
                foreach($array as $field => $val)
                {
                    $strIndependent .= '$result->key' . $key . '->' . $field . " = ";
                    $strIndependent .= $val . ";\n";
                }
                $strIndependent .= "\n";
            }
            file_put_contents($entries . '.php', $strIndependent);

            return $independent;
        }
        else
        {
            $results = $this->checkInput($path);

            echo $results ? 'true' : 'false' ;

            $strResults  = '';
            $strResults .= '<?php' . "\n" . '$result = new stdclass;' . "\n";
            foreach($results as $key => $array)
            {
                $strResults .= '$result->key' . $key . ' = new stdclass;' . ";\n";
                foreach($array as $field => $val)
                {
                    $strResults .= '$result->key' . $key . '->' . $field . " = ";
                    $strResults .= $val . ";\n";
                }
                $strResults .= "\n";
            }
            file_put_contents('entries.php', $strResults);
            return $results;
        }
    }

    /*
     * Check user input.
     *
     * @param  string $path
     * @access public
     * @return array
     */

    public function checkInput($path = '')
    {
        if(empty($path)) return false;

        $path = $this->checkWebDir($path);
        if(!$path)  return false;

        $this->ztPath = $path;
        $openRes      = $this->checkOpen();
        return $openRes;
    }

    /*
     * Check zentao path.
     *
     * @param  string $path
     * @access public
     * @return void
     */
    public function checkWebDir($path = '')
    {
        $configPath = $path . DS . 'config' . DS . 'config.php';

        $info     = new SplFileInfo($configPath);
        $realPath = $info->getRealPath();

        if($realPath) return dirname(dirname($realPath));
        return '';
    }

    /**
     * Get files under a directory recursive.
     *
     * @param  string  $dir
     * @param  array   $exceptions
     * @access private
     * @return array
     */
    public function readDir($dir, $exceptions = array())
    {
        static $files = array();

        if(!is_dir($dir)) return $files;

        $dir     = realpath($dir) . DS;
        $entries = scandir($dir);

        foreach($entries as $entry)
        {
            if(in_array($entry, array('.', '..', '.svn', '.git'))) continue;
            if(in_array($entry, $exceptions)) continue;

            $fullEntry = $dir . $entry;
            if(is_file($fullEntry))
            {
                $files[] = $dir . $entry;
            }
            else
            {
                $nextDir = $dir . $entry;
                $this->readDir($nextDir);
            }
        }
        return $files;
    }

    /**
     * Matching control.
     *
     * @access public
     * @return bool|array
     */
    public function checkOpen()
    {
        $apiFiles = $this->readDir($this->ztPath . DS . 'api' . DS . 'v1' . DS . 'entries' . DS);
        $results  = array();
        foreach ($apiFiles as $key => $filePath)
        {
            $fileContent = file($filePath);
            $controls    = array();
            foreach ($fileContent as $line => $code)
            {
                preg_match('/\$control\s+=\s\$this->loadController\([\'"]([a-z]+)[\'"],\s[\'"]([a-zA-Z0-9]+)[\'"]\);/', $code, $controlNames);
                if(!empty($controlNames))
                {
                    $controls[] = $controlNames[1];
                    continue;
                }
                if(!preg_match('/\$control->([a-z0-9]+)\((\$[a-z0-9]+,\s|\$this->param\([\'\"][a-z0-9]+[\'\"],\s*[\'\"]?[a-z0-9-_\s]*[\'\"]?\)[,]?\s*|[\'\"]?[0-9a-z]+[\'\"]?,\s)+\)/i', $code, $controlMethod)) continue;

                $res = preg_match_all('/(\$[a-z0-9]+[,\)])|([0-9]+[,\)])|((?<!param\()[\'\"][a-z0-9-_]*[\'\"][,\)])|((?<!this)\-\>[a-z0-9]+\()/i', $code, $execControls, PREG_PATTERN_ORDER);
                if(!empty($execControls[0]))
                {
                    $params     = $execControls[0];
                    $pramsLen   = count($params) - 1;
                    $methodName = trim(trim($params[0], '->'), '(');
                    $module     = $controls[count($controls) - 1];
                    $checkRes   = $this->checkParamLen($module, $methodName, $pramsLen);

                    if(!is_bool($checkRes))
                    {
                        $results[] =  array(
                            'filePath'    => $filePath,
                            'line'        => ++$line,
                            'moduleName'  => $module,
                            'methodName'  => $methodName,
                            'apiCode'     => $controlMethod[0],
                            'controlCode' => $checkRes['lineCode'],
                            'controlFile' => $checkRes['controlFile'],
                            'status'      => 'fail'
                        );
                    }
                    elseif($checkRes)
                    {
                        $results[] =  array(
                            'filePath'    => $filePath,
                            'line'        => ++$line,
                            'moduleName'  => $module,
                            'methodName'  => $methodName,
                            'apiCode'     => $controlMethod[0],
                            'controlCode' => '',
                            'controlFile' => '',
                            'status'      => 'success'
                        );
                    }
                }
            }
        }
        return empty($results) ? true : $results;
    }

    /*
     * Check method params length.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $length
     * @access public
     * @return array|bool
     */
    public function checkParamLen($module, $method, $length)
    {
        $controlFile     = $this->ztPath . DS . 'module' . DS . $module . DS . 'control.php';
        $controlExtFile  = $this->ztPath . DS . 'extension/max/' . $module . '/control.php';
        $controlFuncFile = $this->ztPath . DS . 'extension/max/' . $module . '/ext/control/' . $method . '.php';

        $realFile       = file_exists($controlFuncFile) ? $controlFuncFile : (file_exists($controlExtFile) ? $controlExtFile : $controlFile);
        $controlContent = file_get_contents($realFile);
        preg_match('/public\sfunction\s' . $method . '\((\$[a-z0-9]+(\s=\s[\'\"]?[a-z0-9-_,]*[\'\"]?)?,?\s?)*\)/i', $controlContent, $matches);
        if(empty($matches)) return false;

        $paramLen = substr_count($matches[0], '$');
        if($paramLen != $length)
        {
            return array('lineCode' => $matches[0], 'controlFile' => $realFile);
        }
        else
        {
            return true;
        }
    }
}

$tester = new apiCheckModel();
