<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of jenkins module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     jenkins
 * @link        https://www.zentao.net
 */
namespace zin;

$data['pipeline'] = $tasks;

$tabs = array();
$tabs[] = array('name' => 'pipeline', 'text' => $lang->repo->pipeline);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('pipeline' => '###');
$json['itemType']   = 'pipeline';

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
