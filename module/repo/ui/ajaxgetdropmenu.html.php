<?php
declare(strict_types=1);
/**
 * The head switcher view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('repo' => array_values($repoGroup));

$tabs = array();
$tabs[] = array('name' => 'repo', 'text' => $lang->repo->codeRepo);

$json = array();
$json['data']       = $data;
$json['tabs']       = $tabs;
$json['searchHint'] = $lang->searchAB;
$json['labelMap']   = array('product' => $lang->product->common);
$json['link']       = array('repo' => sprintf($link, '{id}'));
$json['itemType']   = 'repo';

renderJson($json);
