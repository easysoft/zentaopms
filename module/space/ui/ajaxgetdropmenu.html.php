<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of jenkins module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('space' => $spaceGroup);

$tabs = array();
$tabs[] = array('name' => 'space', 'text' => $lang->space->common);

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['link']       = array('space' => sprintf($link, '{id}'));
$json['itemType']   = 'space';

renderJson($json);
