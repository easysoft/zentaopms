<?php
declare(strict_types=1);
/**
 * The ajaxgetdropmenu view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array('nolink' => array(), 'product' => array(), 'project' => array());

/* 处理独立接口库。Process independent libs. */
foreach($nolinkLibs as $lib) $data['nolink'][] = array('id' => "lib.$lib->id", 'text' => $lib->name, 'active' => $lib->id == $libID, 'type' => 'item', 'url' => createLink('api', 'index', "libID=$lib->id"));

/* 处理产品和项目数据。Process product and project data. */
foreach($products as $product)
{
    $programID = $product->program;
    $item      = array('type' => 'product', 'id' => "product.$product->id", 'text' => $product->name, 'active' => $objectType == 'product' && $product->id == $objectID, 'url' => createLink('api', 'index', 'libID=' . (isset($product->firstLib) ? $product->firstLib : 0)));
    if(isset($programs[$programID]))
    {
        $programKey = "program.$programID";
        $program    = $programs[$programID];
        if(!isset($data['product'][$programKey])) $data['product'][$programKey] = array('id' => $programKey, 'text' => $program->name, 'type' => 'program', 'items' => array());
        $data['product'][$programKey]['items'][] = $item;
    }
    else
    {
        $data['product'][$product->id] = $item;
    }
}
foreach($projects as $project)
{
    $programID = $project->parent;
    $item      = array('type' => 'project', 'id' => "project.$project->id", 'text' => $project->name, 'active' => $objectType == 'project' && $project->id == $objectID, 'url' => createLink('api', 'index', 'libID=' . (isset($project->firstLib) ? $project->firstLib : 0)));
    if(isset($programs[$programID]))
    {
        $programKey = "program.$programID";
        $program    = $programs[$programID];
        if(!isset($data['project'][$programKey])) $data['project'][$programKey] = array('id' => $programKey, 'text' => $program->name, 'type' => 'program', 'items' => array());
        $data['project'][$programKey]['items'][] = $item;
    }
    else
    {
        $data['project'][$project->id] = $item;
    }
}
$data['product'] = array_values($data['product']);
$data['project'] = array_values($data['project']);

/**
 * 定义最终的 JSON 数据。
 * Define the final json data.
 */
$link = $this->createLink('api', 'ajaxGetList', "objectID={id}&objectType={type}");

$tabs = array();
$tabs[] = array('name' => 'nolink',  'text' => $lang->api->libTypeList['nolink']);
$tabs[] = array('name' => 'product', 'text' => $lang->api->libTypeList['product']);
$tabs[] = array('name' => 'project', 'text' => $lang->api->libTypeList['project']);

$json = array();
$json['link']       = $link;
$json['data']        = $data;
$json['tabs']        = $tabs;
$json['searchHint']  = $lang->searchAB;
$json['labelMap']    = array('program' => $lang->program->common);
$json['itemType']    = 'lib';
$json['typeIconMap'] = array('lib' => 'doclib');
$json['debug']       = array('libID' => $libID, 'objectType' => $objectType, 'objectID' => $objectID);

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render json data to string and send to client.
 */
renderJson($json);
