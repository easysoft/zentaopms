<?php
define('LIB_ROOT', dirname(dirname(__FILE__)) . '/lib/');

include LIB_ROOT . 'init.php';

$apiRoot  = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'api/v1/entries';
$apiFiles = scandir($apiRoot);
$apiFiles = array_splice($apiFiles, 2);

/**
 * Get api List from the file and database.
 *
 * @param  string $apiRoot
 * @param  string $apiFiles
 * @param  boole  $info
 * @access public
 * @return array
 */
function getApi($apiRoot, $apiFiles, $info = false)
{
    global $config, $dao;
    $routes = $config->routes;

    $apiMethods = array();
    foreach($apiFiles as $apiFile)
    {
        preg_match_all('/function\s(get|put|delete|post)\(/', file_get_contents($apiRoot . DS . $apiFile), $methods);
        if($methods)
        {
            $apiFileName = str_replace(strrchr($apiFile, "."),"",$apiFile);
            $apiMethods[$apiFileName] = $methods[1];
        }
    }

    $noApiFile = '';
    $nodata    = '';
    $api       = array();

    foreach($routes as $url => $apiName)
    {
        $apiName = strtolower($apiName);
        if(isset($apiMethods[$apiName]))
        {
            $api[$apiName]['url'] = $url;

            foreach($apiMethods[$apiName] as $apiMethod)
            {
                $apiData = $dao->select('params, paramsExample')->from(TABLE_API)->where('path')->eq($url)->andwhere('method')->eq(strtoupper($apiMethod))->fetch();

                if($apiData)
                {
                    $api[$apiName]['method'][$apiMethod]['params']  = $apiData->params;
                    $api[$apiName]['method'][$apiMethod]['example'] = $apiData->paramsExample;
                }
                else
                {
                    $nodata .= $url . '的' . $apiMethod . '方法没有在数据库维护' . PHP_EOL;

                    $api[$apiName]['method'][$apiMethod]['params']  = '';
                    $api[$apiName]['method'][$apiMethod]['example'] = '';
                }
            }
        }
        else
        {
            $noApiFile .= "没有api文件的routes：" . $apiName . PHP_EOL;
        }
    }

    if($info === true) echo '没有数据库数据：' . PHP_EOL . $nodata . PHP_EOL . '没有API文件：' . PHP_EOL . $noApiFile . PHP_EOL;
    return $api;
}

/**
 * Set the value of the parameter.
 *
 * @param  string  $values
 * @access public
 * @return string
 */
function setValue($values)
{
    $result = "array(";
    foreach($values as $field => $value)
    {
        if(is_array($value))
        {
            $result .= setValue($value);
        }
        else
        {
            $result .= "'$field' => '$value', ";
        }
    }
    $result = trim($result, ", ") . ')';
    return $result;
}

$apiList  = getApi($apiRoot, $apiFiles);
$caseRoot = dirname(__FILE__, 2) . '/checkapi';

/**
 * Batch create API cases.
 *
 * @param  string    $caseRoot
 * @param  string    $apiList
 * @access public
 * @return void
 */
function createCase($caseRoot, $apiList)
{
    if(!is_dir($caseRoot)) mkdir($caseRoot, 0777, true);

    foreach($apiList as $apiName => $api)
    {
        if(strpos($api['url'], ':'))
        {
            preg_match_all('/\/(:\w+)/', $api['url'], $matches);
            $apiUrl = str_replace($matches[1], '1', $api['url']);
        }
        else
        {
            $apiUrl = $api['url'];
        }

        foreach($api['method'] as $method => $methodValue)
        {
            $apiMethodUrl = $apiUrl;
            $caseFile     = $caseRoot . DS . $apiName . DS . $method;
            if($method === 'get')
            {
                if($methodValue['params'])
                {
                    $queryParams = json_decode($methodValue['params'])->query;

                    $querys = '?';
                    foreach($queryParams as $queryParam) $querys .= $queryParam->field . '=&';

                    $apiMethodUrl .= rtrim($querys, "&");
                }
                $testData = "array('token' => \$token->token)";
            }
            elseif($method === 'post' or $method === 'put')
            {
                if($methodValue['example'] !== '')
                {
                    $testData = "array(";
                    $examples = json_decode(str_replace('&quot;', '"', $methodValue['example']), true);

                    foreach($examples as $key => $value)
                    {
                        if(is_array($value))
                        {
                            $paramValue = setValue($value);
                        }
                        else
                        {
                            $paramValue = "'$value'";
                        }
                        $testData .= "'" . $key . "' => " . $paramValue . ", ";
                    }

                    if($apiMethodUrl == "/tokens")
                    {
                        $testData = rtrim($testData, ", ") . ")";
                    }
                    else
                    {
                        $testData = rtrim($testData, ", ") . "), array('token' => \$token->token)";
                    }
                }
                else
                {
                    $testData = "array('token' => \$token->token)";
                }
            }
            $testData = "array('token' => \$token->token)";

            $caseContent = <<<caseContent
            #!use/bin/env php
            <?php
            include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

            /**

            title:%s;
            cid=1
            pid=1

            通用api检查 >> 0

            */
            \$%s = \$rest->%s('%s', %s);

            r(\$%s) && c(200) && p() && e('0'); // 通用api检查
            caseContent;

            $caseContent = sprintf($caseContent, $apiName . "测试" . $method, $apiName, $method, $apiMethodUrl, $testData, $apiName);

            if(!is_dir(dirname($caseFile))) mkdir(dirname($caseFile), 0777, true);
            file_put_contents($caseFile, $caseContent);
        }
    }
}
createCase($caseRoot, $apiList);
