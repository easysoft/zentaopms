<?php
declare(strict_types=1);
/**
 * The api view file of dev module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     dev
 * @link        https://www.zentao.net
 */
namespace zin;

$featureBarItems = array();
foreach($lang->dev->featureBar['api'] as $key => $label)
{
    $featureBarItems[] = array
    (
        'text'   => $label,
        'active' => ($selectedModule == $key || ($key == 'index' and $selectedModule != 'restapi')),
        'url'    => inlink('api', "module=$key")
    );
}

$parseTree = function($data, $typeList, $level = 0) use (&$parseTree)
{
    global $lang;

    $trList = array();
    $tdList = array();

    $field = '';
    for($i = 0; $i < $level; $i++) $field .= '  '. ($i == $level - 1 ? '∟' : ' ') . '  ';
    $field .= $data['field'];

    $tdList[] = h::td($field);
    $tdList[] = h::td(zget($typeList, $data['paramsType'], ''));
    $tdList[] = h::td(setClass('text-center'), zget($lang->api->boolList, $data['required'], ''));
    $tdList[] = h::td(html($data['desc']));
    $trList[] = h::tr($tdList);
    if(isset($data['children']) && count($data['children']) > 0)
    {
        $level++;
        foreach($data['children'] as $item) $trList = array_merge($trList, $parseTree($item, $typeList, $level));
    }
    return $trList;
};

$fnGetHeaderContent = function($api)
{
    if(empty($api->params['header'])) return null;

    global $lang;
    $headerTr = array();
    foreach($api->params['header'] as $param)
    {
        $headerTr[] = h::tr
        (
            h::td($param['field']),
            h::td('String'),
            h::td($lang->api->boolList[$param['required']]),
            h::td(html($param['desc']))
        );
    }

    $content = array();
    $content[] = h3(setClass('title'), $lang->api->header);
    $content[] = h::table
    (
        setClass('table bordered paramsTable'),
        h::thead
        (
            h::tr
            (
                h::th($lang->api->req->name),
                h::th($lang->api->req->type),
                h::th($lang->api->req->required),
                h::th($lang->api->req->desc)
            )
        ),
        h::tbody($headerTr)
    );
    return $content;
};

$fnGetQueryContent = function($api)
{
    if(empty($api->params['query'])) return null;

    global $lang;
    $queryTr = array();
    foreach($api->params['query'] as $param)
    {
        $queryTr[] = h::tr
        (
            h::td($param['field']),
            h::td('String'),
            h::td($lang->api->boolList[$param['required']]),
            h::td(html($param['desc']))
        );
    }

    $content = array();
    $content[] = h3(setClass('title'), $lang->api->query);
    $content[] = h::table
    (
        setClass('table bordered paramsTable'),
        h::thead
        (
            h::tr
            (
                h::th($lang->api->req->name),
                h::th($lang->api->req->type),
                h::th($lang->api->req->required),
                h::th($lang->api->req->desc)
            )
        ),
        h::tbody($queryTr)
    );
    return $content;
};

$fnGetParamsContent = function($api) use(&$parseTree, $typeList)
{
    if(empty($api->params['params'])) return null;

    global $lang;
    $paramsTr = array();
    foreach($api->params['params'] as $item) $paramsTr = array_merge($paramsTr, $parseTree($item, $typeList));

    $content = array();
    $content[] = h3(setClass('title'), $lang->api->params);
    $content[] = h::table
    (
        setClass('table bordered paramsTable'),
        h::thead
        (
            h::tr
            (
                h::th($lang->api->req->name),
                h::th($lang->api->req->type),
                h::th($lang->api->req->required),
                h::th($lang->api->req->desc)
            )
        ),
        h::tbody($paramsTr)
    );
    if(!empty($api->paramsExample))
    {
        $content[] = h3(setClass('title'), $lang->api->paramsExample);
        $content[] = h::pre(h::code(html($api->paramsExample)));
    }
    return $content;
};

$fnGetResponseContent = function($api) use($parseTree, $typeList)
{
    if(empty($api->response)) return null;

    global $lang;
    $responseTr = array();
    foreach($api->response as $item) $responseTr = array_merge($responseTr, $parseTree($item, $typeList));

    $content = array();
    $content[] = h3(setClass('title'), $lang->api->response);
    $content[] = h::table
    (
        setClass('table bordered paramsTable'),
        h::thead
        (
            h::tr
            (
                h::th($lang->api->req->name),
                h::th($lang->api->req->type),
                h::th($lang->api->req->required),
                h::th($lang->api->req->desc)
            )
        ),
        h::tbody($responseTr)
    );
    if(!empty($api->responseExample))
    {
        $content[] = h3(setClass('title'), $lang->api->responseExample);
        $content[] = h::pre(h::code(html($api->responseExample)));
    }
    return $content;
};

$fnBuildAPIContent = function() use($api, $fnGetHeaderContent, $fnGetQueryContent, $fnGetParamsContent, $fnGetResponseContent)
{
    $content   = array();
    $content[] = div
    (
        setClass('panel-heading'),
        div(setClass('http-method label'), $api->method),
        div(setClass('path'), set::title($api->path), $api->path)
    );
    $content[] = div
    (
        setClass('panel-body'),
        h2(setClass('title'), set::title($api->title), $api->title),
        div(setClass('desc'), html($api->desc)),
        div
        (
            $fnGetHeaderContent($api),
            $fnGetQueryContent($api),
            $fnGetParamsContent($api),
            $fnGetResponseContent($api)
        )
    );
    return $content;
};

$activeGroup = '';
foreach($moduleTree as $module)
{
    if($module->active) $activeGroup = $module->id;
}

h::css("
.sidebar .tree [data-level=\"0\"][id=\"{$activeGroup}\"] {color: var(--color-primary-600); font-weight:bolder}
.sidebar .tree [data-level=\"1\"][id=\"{$apiID}\"] {color: var(--color-primary-600); font-weight:bolder}
");

featureBar
(
    set::items($featureBarItems),
    icon(set('title', $lang->dev->apiTips), 'help')
);

sidebar
(
    setClass('bg-white'),
    set::style(array('width' => '180px')),
    h::header
    (
        setClass('h-10 flex items-center pl-4 flex-none gap-3'),
        span(setClass('text-lg font-semibold'), icon(setClass('pr-2'), 'list'), $lang->dev->moduleList)
    ),
    treeEditor(set(array('className' => 'pl-3', 'items' => $moduleTree, 'canEdit' => false, 'canDelete' => false, 'canSplit' => false)))
);

div
(
    setClass('bg-white p-3 panel'),
    $selectedModule ? $fnBuildAPIContent() : null
);
