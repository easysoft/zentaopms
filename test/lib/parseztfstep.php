<?php
/**
 * 生成用例步骤描述
 * Generate step desc.
 *
 * @param  string   $userStep
 * @param  string   $moduleName
 * @param  string   $methodName
 * @param  string   $methodParam
 * @param  string   $isGroup
 * @access public
 * @return string
 */
function genStepDesc($userStep, $moduleName, $methodName, $methodParam, $isGroup)
{
    if($userStep) return '- ' . $userStep . ($isGroup ? "\n" : '');

    if ($methodName === 0 && $methodParam === 0)
    {
        $stepDesc = "- 执行{$moduleName}" . ($isGroup ? "\n" : '');
    }
    elseif($methodParam)
    {
        $stepDesc = "- 执行{$moduleName}模块的{$methodName}方法，参数是{$methodParam}" . ($isGroup ? "\n" : ' ');
    }
    else
    {
        $stepDesc = "- 执行{$moduleName}模块的{$methodName}方法" . ($isGroup ? "\n" : ' ');
    }

    return $stepDesc;
}

/**
 * 用例步骤并输出（ztf使用）
 * Print steps by row.
 *
 * @param  string $key
 * @access public
 * @return void
 */
function printSteps()
{
    $debugInfo = debug_backtrace();
    if(empty($debugInfo)) return;

    $file     = $debugInfo[count($debugInfo)-1]['file'];
    $contents = file_get_contents($file);
    $rpeList  = genParamsByRPE($contents);
    $desc     = '';

    foreach($rpeList as $rpe)
    {
        list($moduleName, $methodName, $methodParam) = $rpe[0];

        $isGroup    = false;
        $pParam    = $rpe[2];
        $keys      = $pParam[0];
        $delimiter = $pParam[1] ? $pParam[1] : ',';

        $parts     = explode(';', $keys);
        $parts     = array_filter($parts);
        $userStep  = trim(str_replace('//', '', $rpe[3]));
        $expectStr = is_numeric($rpe[1]) ? $rpe[1] : trim($rpe[1], substr($rpe[1], 0, 1));

        $stepDesc = genStepDesc($userStep, $moduleName, $methodName, $methodParam, $isGroup);

        if(empty($keys)) $stepDesc .= " @{$expectStr}\n";

        if(str_contains($keys, ';') && str_contains($expectStr, ';'))
        {
            $expectList = explode(';', $expectStr);
            $expectList = array_map(function($item) use ($delimiter)
            {
                return explode($delimiter, $item);
            }, $expectList);
        }
        else
        {
            $expectList = explode($delimiter, $expectStr);
            $chunkCount = empty($parts) ? 1 : ceil(count($expectList)/count($parts));
            $expectList = array_chunk($expectList, $chunkCount);
        }

        $isGroup = count($expectList) > 1 || (!empty($expectList) && count($expectList[0]) > 1);
        $desc   .= $stepDesc;

        if($isGroup && substr($desc, -2) != "\n") $desc .= "\n";

        foreach($parts as $index => $part)
        {
            $desc .= genRowStep($part, $delimiter, $expectList[$index], $isGroup);
        }
    }

    echo trim($desc);
}

/**
 * 生成（;隔开的）每条数据的用例步骤.
 * Generate steps by row.
 *
 * @param  string $key
 * @access public
 * @return string
 */
function genRowStep($keys, $delimiter, $expects, $isGroup)
{
    $stepDesc = '';
    $rowIndex = -1;
    $pos      = strpos($keys, ':');

    if($pos)
    {
        $rowIndex = substr($keys, 0, $pos);
        $keys     = substr($keys, $pos + 1);
    }

    $keys = $keys === '' ? array() : explode($delimiter, $keys);

    foreach($keys as $index => $row)
    {
        $stepExpect = isset($expects[$index]) ? $expects[$index] : '';
        if(count($expects) == 1) $stepExpect = current($expects);

        if($rowIndex == -1)
        {
            if(RUN_MODE == 'uitest' && $row == 'message')
            {
                $stepDesc .= ($isGroup ? ' - ' : '') . ($row ? "测试结果" : '') . " @{$stepExpect}\n";
            }
            elseif(RUN_MODE == 'uitest' && $row == 'status')
            {
                $stepDesc .= ($isGroup ? ' - ' : '') . ($row ? "最终测试状态" : '') . " @{$stepExpect}\n";
            }
            else
            {
                $stepDesc .= ($isGroup ? ' - ' : '') . ($row ? "属性{$row}" : '') . " @{$stepExpect}\n";
            }
        }
        else
        {
            $stepDesc .= ($isGroup ? ' - ' : '') . "第{$rowIndex}条的{$row}属性 @{$stepExpect}\n";
        }
    }

    return $stepDesc;
}

/**
 * 从p()函数提取传入参数
 * Split function params.
 *
 * @param  array $params
 * @return array
 */
function splitParam($params)
{
    $newParams = array();
    foreach($params as $param)
    {
        $param          = trim($param);
        $firstSymbol    = substr($param, 0, 1);
        $paramList      = str_split($param);
        $delimiterIndex = -1;

        foreach($paramList as $i => $p)
        {
            if($i == 0) continue;
            if($p === $firstSymbol && (!isset($paramList[$i-1]) || '\\' != $paramList[$i-1]))
            {
                $delimiterIndex = $i + 1;
                break;
            }
        }

        if($delimiterIndex === -1)
        {
            $newParams[] = array($param, '');
            continue;
        }

        $firstParam  = substr($param, 0, $delimiterIndex);
        $firstParam  = trim(trim($firstParam), '\'"');
        $lastParam   = substr($param, $delimiterIndex + 1);
        $lastParam   = trim(trim($lastParam), '\'"');
        $newParams[] = array($firstParam, $lastParam);
    }

    return $newParams;
}

/**
 * 从r()函数提取调用的moduleName,methodName,methodParam
 * Generate module,method,param from r function.
 *
 * @param  array $rParams
 * @return array
 */
function genModuleAndMethod($rParams)
{
    $newParams = array();
    foreach($rParams as $param)
    {
        $param = trim($param, "'");
        if($param[0] != '$') $param = trim(strchr($param, '$'), ')');

        $objArrowCount        = substr_count($param, '->');
        $rParamsStructureList = explode('->', $param);

        if($objArrowCount == 1 && str_contains($rParamsStructureList[1], '('))
        {
            $moduleName   = substr($rParamsStructureList[0], 1);
            $method       = $rParamsStructureList[1];
            $parsedMethod = explode('(', $method);
            $methodName   = substr($parsedMethod[0], 0);
            $methodParam  = isset($parsedMethod[1]) ? trim(substr($parsedMethod[1], 0), "()") : 0;
        }
        elseif($objArrowCount == 2)
        {
            $moduleName   = $rParamsStructureList[1];
            $method       = $rParamsStructureList[2];
            $parsedMethod = explode('(', $method);
            $methodName   = $parsedMethod[0];
            $methodParam  = isset($parsedMethod[1]) ? trim(substr($parsedMethod[1], 0), "()") : 0;
        }
        else
        {
            $newParams[] = array($param, 0, 0);
            continue;
        }

        $methodParam = preg_replace("/,\s*/", ', ', $methodParam);
        $newParams[] = array($moduleName, $methodName, $methodParam);
    }

    return $newParams;
}

/**
 * 从RPE中获取模块，方法以及参数
 * Generate method,module,params from rpe.
 *
 * @param  string $rpe
 * @return array
 */
function genParamsByRPE($rpe)
{
    preg_match_all("/\nr\((.*?)\)\s*&&\s*p\((.*?)\)\s*&&\s*e\((.*?)\);(.*)/", $rpe, $matches);
    $rParams  = !empty($matches[1]) ? $matches[1] : array();
    $pParams  = !empty($matches[2]) ? $matches[2] : array();
    $eParams  = !empty($matches[3]) ? $matches[3] : array();
    $stepList = !empty($matches[4]) ? $matches[4] : array();
    $rParams  = is_array($rParams) ? $rParams : array($rParams);
    $pParams  = is_array($pParams) ? $pParams : array($pParams);
    $eParams  = is_array($eParams) ? $eParams : array($eParams);

    $pParamsList = splitParam($pParams);
    $rpeList     = array();
    $rParamList  = genModuleAndMethod($rParams);

    foreach($rParamList as $index => $param) $rpeList[] = array($param, $eParams[$index], $pParamsList[$index], $stepList[$index]);

    return $rpeList;
}

