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

$topItem = array();
$topItem['id']   = 0;
$topItem['type'] = 'program';
$topItem['text'] = $lang->program->common;

$data[0] = $topItem;

$data[0]['items'] = $this->program->buildTree($programs);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('program' => $link);
$json['labelMap']   = array('program' => $lang->program->common);
$json['expandName'] = 'closed';
$json['itemType']   = 'program';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
