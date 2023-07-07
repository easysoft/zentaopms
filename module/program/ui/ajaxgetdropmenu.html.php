<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

function buildTree($programs, $parentID = 0)
{
    $result = array();
    foreach($programs as $program)
    {
        if($program->type != 'program') continue;

        if($program->parent == $parentID)
        {
            $itemArray = array
            (
                'id'    => $program->id,
                'text'  => $program->name,
                'keys'  => zget(common::convert2Pinyin(array($program->name)), $program->name, ''),
                'items' => buildTree($programs, $program->id)
            );

            if(empty($itemArray['items'])) unset($itemArray['items']);
            $result[] = $itemArray;
        }
    }
    return $result;
}

$data[0]['items'] = buildTree($programs);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;
$json['link']       = sprintf($link, '{id}');
$json['labelMap']   = array('program' => $lang->program->common);
$json['expandName'] = 'closed';
$json['itemType']   = 'program';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
