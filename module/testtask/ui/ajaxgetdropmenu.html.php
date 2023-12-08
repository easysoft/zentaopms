<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu<liumengyi@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array();
foreach($testtasks as $testtask)
{
    if($objectType == 'project' && $testtask->project != $objectID) continue;
    if($objectType == 'execution' && $testtask->execution != $objectID) continue;

    $item = array();
    $item['id']        = $testtask->id;
    $item['name']      = $testtask->name;
    $item['text']      = $testtask->name;
    $item['title']     = $testtask->name;
    $item['active']    = $currentTaskID == $testtask->id;
    $item['keys']      = zget($testtasksPinyin, $testtask->id, '');
    $item['url']       = sprintf($link, $testtask->id);
    $item['type']      = 'item';
    $item['data-app']  = $app->tab;
    $data[] = $item;
}

$json = array();
$json['data'] = $data;

renderJson($json);
