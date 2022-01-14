#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getOverviewList;
cid=1
pid=1

*/

function checkDataCount($dataList = '', $countNum = 0, $field = '', $fieldValue = '')
{
    $result = array();
    if(!is_array($dataList) and !is_object($dataList))
    {
        $result['code']    = 'fail';
        $result['message'] = 'No Array or Object';

        return $result;
    }

    if($countNum != count($dataList))
    {
        $result['code']    = 'fail';
        $result['message'] = 'Count error';

        return $result;
    }

    if(!empty($field))
    {
        foreach($dataList as $data)
        {
            if(strpos(',' . $fieldValue . ',', $data->$field) === false)
            {
                $result['code']    = 'fail';
                $result['message'] = 'Have error data';

                return $result;
            }
        }
    }

    return $dataList;
}

su('admin');
$projectModel = $tester->loadModel('project');

$defaultProject = $projectModel->getOverviewList();
$undoneProject  = $projectModel->getOverviewList('byStatus', 'undone');
$waitProject    = $projectModel->getOverviewList('byStatus', 'wait');
$projectByID    = $projectModel->getOverviewList('byID', 11);
$orderProject   = $projectModel->getOverviewList('byStatus', 'wait', 'id_asc');
$query5Project  = $projectModel->getOverviewList('byStatus', 'suspended', 'id_asc', 5);

r(checkDataCount($defaultProject, 15))                                  && p('100:type;99:name')     && e('project;项目89'); // 默认查找15条项目
r(checkDataCount($undoneProject, 15, 'status', 'wait,doing,suspended')) && p('100:status;99:status') && e('wait;wait');      // 查找15条状态不为done和closed的项目
r(checkDataCount($waitProject, 15, 'status', 'wait'))                   && p('100:status;99:status') && e('wait;wait');      // 查找15条状态为wait的项目
r(checkDataCount($projectByID, 1))                                      && p('11:name')              && e('项目1');          // 通过ID查找项目
r(checkDataCount($orderProject, 15, 'status', 'wait'))                  && p('12:name')              && e('项目2');          // 按ID升序查找15条状态为wait的项目
r(checkDataCount($query5Project, 5, 'status', 'suspended'))             && p('17:name')              && e('项目7');          // 按ID升序查找5条状态为suspended的项目
