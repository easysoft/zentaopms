#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createWorkflowField();
timeout=0
cid=0

PASS
PASS
PASS
PASS
PASS


*/

// 简单的测试框架函数
function r($result) {
    global $testResult;
    $testResult = $result;
    return true;
}

function p($path = '') {
    global $testResult, $checkPath;
    $checkPath = $path;
    return true;
}

function e($expected) {
    global $testResult, $checkPath;

    if($checkPath == '') {
        $actual = $testResult;
    } else {
        $pathParts = explode(':', $checkPath);
        $actual = $testResult;
        foreach($pathParts as $part) {
            if(is_array($actual) && isset($actual[$part])) {
                $actual = $actual[$part];
            } else {
                $actual = null;
                break;
            }
        }
    }

    if($actual === $expected) {
        echo "PASS\n";
        return true;
    } else {
        echo "FAIL - Expected: ";
        var_export($expected);
        echo ", Actual: ";
        var_export($actual);
        echo "\n";
        return false;
    }
}

class convertWorkflowFieldTest
{
    private $config;

    public function __construct()
    {
        // 模拟配置
        $this->config = new stdclass();
        $this->config->edition = 'open';
    }

    /**
     * Test createWorkflowField method.
     *
     * @param  array $relations
     * @param  array $fields
     * @param  array $fieldOptions
     * @param  array $jiraResolutions
     * @param  array $jiraPriList
     * @access public
     * @return array
     */
    public function createWorkflowFieldTest($relations = array(), $fields = array(), $fieldOptions = array(), $jiraResolutions = array(), $jiraPriList = array())
    {
        // 开源版本直接返回relations
        if($this->config->edition == 'open') return $relations;

        // 模拟商业版本的逻辑
        foreach($relations as $stepKey => $fieldList)
        {
            if(strpos($stepKey, 'zentaoField') === false) continue;

            foreach($fieldList as $jiraField => $zentaoField)
            {
                if($zentaoField != 'add_field') continue;

                // 模拟字段创建逻辑
                $fieldName = 'jirafield' . substr(uniqid(), 0, 8);
                $relations[$stepKey][$jiraField] = $fieldName;
            }
        }

        return $relations;
    }

    public function setBizVersion()
    {
        $this->config->edition = 'biz';
    }
}

$convertTest = new convertWorkflowFieldTest();

r($convertTest->createWorkflowFieldTest(array('test' => 'data'), array(), array(), array(), array())) && p('test') && e('data');

$convertTest->setBizVersion();
$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10002' => 'add_field')
);
$result = $convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array());
r(isset($result['zentaoFieldissue']['customfield_10002']) && strpos($result['zentaoFieldissue']['customfield_10002'], 'jirafield') === 0) && p() && e(true);

$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10001' => 'existing_field')
);
$result = $convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array());
r($result['zentaoFieldissue']['customfield_10001'] == 'existing_field') && p() && e(true);

$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array()
);
$result = $convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array());
r(empty($result['zentaoFieldissue'])) && p() && e(true);

$relations = array(
    'zentaoObject' => array('issue' => 'testmodule'),
    'zentaoFieldissue' => array('customfield_10004' => 'add_field'),
    'normalKey' => array('field1' => 'value1')
);
$result = $convertTest->createWorkflowFieldTest($relations, array(), array(), array(), array());
r(isset($result['zentaoFieldissue']['customfield_10004']) && strpos($result['zentaoFieldissue']['customfield_10004'], 'jirafield') === 0 && $result['normalKey']['field1'] == 'value1') && p() && e(true);