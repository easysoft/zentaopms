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

$data = array('repo');

$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;
$json['link']       = sprintf($link, '{id}');
$json['itemType']   = 'product';

renderJson($json);
