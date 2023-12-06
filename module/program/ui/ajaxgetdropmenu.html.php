<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

$topItem = array();
$topItem['id']    = 0;
$topItem['type']  = 'program';
$topItem['text']  = $lang->program->common;
$topItem['url']   = false;
$topItem['label'] = false;
$topItem['items'] = $programTree;

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = array($topItem);
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
