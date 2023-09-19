<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array();
foreach($libraries as $currentLib => $libName)
{
    $item = array();
    $item['id']     = $currentLib;
    $item['name']   = $libName;
    $item['text']   = $libName;
    $item['title']  = $libName;
    $item['active'] = $libID == $currentLib;
    $item['keys']   = zget($librariesPinyin, $libName, '');
    $item['url']    = sprintf($link, $currentLib);
    $item['type']   = 'item';
    $data[] = $item;
}

$json = array();
$json['data']       = $data;
$json['searchHint'] = $lang->searchAB;

renderJson($json);

