<?php
declare(strict_types=1);
/**
 * The index view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

$apiHeader = $apiQuery = $apiParams = $apiResponse = array();
$parseTree = function($data, $typeList, $level = 0) use(&$parseTree)
{
    global $lang;

    $field = '';
    for($i = 0; $i < $level; $i++) $field .= '&nbsp;&nbsp;'. ($i == $level-1 ? '∟' : '&nbsp;') . '&nbsp;&nbsp;';
    $field .= $data['field'];

    $tbody[] = h::tr
    (
        h::td(html($field)),
        h::td(zget($typeList, (string)$data['paramsType'], '')),
        h::td(zget($lang->api->boolList, (string)$data['required'], '')),
        h::td($data['desc'])
    );

    if(isset($data['children']) && count($data['children']) > 0)
    {
        $level++;
        foreach($data['children'] as $item) $tbody[] = $parseTree($item, $typeList, $level);
    }

    return $tbody;
};

if(!empty($api->params['header']))
{
    $tbody = array();
    foreach($api->params['header'] as $param)
    {
        $tbody[] = h::tr
        (
            h::td($param['field']),
            h::td('String'),
            h::td(zget($lang->api->boolList, (string)$param['required'])),
            h::td($param['desc'])
        );
    }

    $apiHeader[] = h3($lang->api->header);
    $apiHeader[] = h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th($lang->api->req->name),
            h::th($lang->api->req->type),
            h::th($lang->api->req->required),
            h::th($lang->api->req->desc)
        ),
        $tbody
    );
}

if(!empty($api->params['query']))
{
    $tbody = array();
    foreach($api->params['query'] as $param)
    {
        $tbody[] = h::tr
        (
            h::td($param['field']),
            h::td('String'),
            h::td(zget($lang->api->boolList, (string)$param['required'])),
            h::td($param['desc'])
        );
    }

    $apiQuery[] = h3($lang->api->query);
    $apiQuery[] = h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th($lang->api->req->name),
            h::th($lang->api->req->type),
            h::th($lang->api->req->required),
            h::th($lang->api->req->desc)
        ),
        $tbody
    );
}

if(!empty($api->params['params']))
{
    $tbody = array();
    foreach($api->params['params'] as $param) $tbody = array_merge($tbody, $parseTree($param, $typeList));

    $apiParams[] = h3($lang->api->params);
    $apiParams[] = h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th($lang->api->req->name),
            h::th($lang->api->req->type),
            h::th($lang->api->req->required),
            h::th($lang->api->req->desc)
        ),
        $tbody
    );
}

if($api->response)
{
    $tbody = array();
    foreach($api->response as $response) $tbody = array_merge($tbody, $parseTree($response, $typeList));

    $apiResponse[] = h3($lang->api->response);
    $apiResponse[] = h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th($lang->api->req->name),
            h::th($lang->api->req->type),
            h::th($lang->api->req->required),
            h::th($lang->api->req->desc)
        ),
        $tbody
    );
}


div
(
    setID('api-content'),
    setClass('article'),
    div
    (
        setClass("api-list-item row items-center mb-1 gap-2 flex-auto is-$api->method rounded"),
        div(setClass('font-mono w-14 text-center api-method py-1 rounded rounded-r-none'), $api->method),
        div(setClass('font-mono font-bold text-md api-path'), $api->path)
    ),
    h2($api->title),
    div(setClass('desc'), html($api->desc)),
    $apiHeader,
    $apiQuery,
    $apiParams,
    $api->paramsExample ? h3($lang->api->paramsExample) : null,
    $api->paramsExample ? html("<pre><code>" . $api->paramsExample . "</code></pre>") : null,
    $apiResponse,
    $api->responseExample ? h3($lang->api->responseExample) : null,
    $api->responseExample ? html("<pre><code>" . $api->responseExample . "</code></pre>") : null
);
