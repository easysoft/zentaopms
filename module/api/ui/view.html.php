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

include '../../doc/ui/lefttree.html.php';

$versionList = array();
for($itemVersion = $api->version; $itemVersion > 0; $itemVersion--)
{
    $link = createLink('api', 'index', "libID={$libID}&moduleID=$moduleID&apiID={$apiID}&version={$itemVersion}");
    $versionList[] = array(
        'text'     => "V$itemVersion",
        'onClick'  => jsRaw("() => {loadPage('$link', '#docContent')}")
    );
}

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
    setID('docContent'),
    setClass('flex flex-wrap content-start'),
    featureBar
    (
        li(backBtn(setClass('ghost'), set::icon('back'), $lang->goback))
    ),
    toolbar
    (
        $libID && common::hasPriv('api', 'struct') ? item(set(array
        (
            'icon'  => 'treemap',
            'class' => 'ghost',
            'text'  => $lang->api->struct,
            'url'   => createLink('api', 'struct', "libID={$libID}")
        ))) : null,
        $libID && common::hasPriv('api', 'releases') ? item(set(array
        (
            'icon'        => 'version',
            'class'       => 'ghost',
            'text'        => $lang->api->releases,
            'url'         => createLink('api', 'releases', "libID={$libID}"),
            'data-toggle' => 'modal'
        ))) : null,
        $libID && common::hasPriv('api', 'createRelease') ? item(set(array
        (
            'icon'        => 'publish',
            'class'       => 'ghost',
            'text'        => $lang->api->createRelease,
            'url'         => createLink('api', 'createRelease', "libID={$libID}"),
            'data-toggle' => 'modal'
        ))) : null,
        $libID && common::hasPriv('api', 'export') && $config->edition != 'open' ? item(set(array
        (
            'icon'        => 'export',
            'class'       => 'ghost export',
            'text'        => $lang->export,
            'url'         => createLink('api', 'export', "libID={$libID}&version={$version}&release={$release}&moduleID={$moduleID}"),
            'data-toggle' => 'modal'
        ))) : null,
        common::hasPriv('api', 'createLib') ? item(set(array
        (
            'icon'        => 'plus',
            'class'       => 'btn secondary',
            'text'        => $lang->api->createLib,
            'url'         => createLink('api', 'createLib', "type=" . ($objectType ? $objectType : 'nolink') . "&objectID=$objectID"),
            'data-toggle' => 'modal'
        ))) : null,
        $libID && common::hasPriv('api', 'create') ? item(set(array
        (
            'icon'        => 'plus',
            'class'       => 'btn primary ml-2',
            'text'        => $lang->api->createApi,
            'url'         => createLink('api', 'create', "libID={$libID}&moduleID={$moduleID}"),
            'data-size'   => 'lg',
            'data-toggle' => $spaceType != 'api' ? 'modal' : ''
        ))) : null
    ),
    div
    (
        setClass('doc-content mt-2 flex-initial w-full flex'),
        panel
        (
            setClass('flex-auto'),
            div
            (
                setClass('panel-heading'),
                div
                (
                    setClass('http-method label'),
                    $api->method
                ),
                div
                (
                    setClass('path'),
                    $api->path
                ),
                dropdown
                (
                    btn
                    (
                        setClass('ghost btn square btn-default'),
                        'V' . ($version ? $version : $api->version)
                    ),
                    set::items($versionList)
                ),
                div
                (
                    setClass('panel-actions'),
                    div
                    (
                        setClass('toolbar'),
                        btn
                        (
                            set::url('javascript:fullScreen()'),
                            setClass('btn ghost'),
                            icon('fullscreen')
                        ),
                        (!$isRelease && common::hasPriv('api', 'edit')) ? btn
                        (
                            set::url(createLink('api', 'edit', "apiID=$api->id")),
                            setClass('btn ghost'),
                            icon('edit')
                        ) : null,
                        (!$isRelease && common::hasPriv('api', 'delete')) ? btn
                        (
                            set::url(createLink('api', 'delete', "apiID=$api->id")),
                            setClass('btn ghost ajax-submit'),
                            setData(array('confirm' => $lang->api->confirmDelete)),
                            icon('trash')
                        ) : null,
                        btn
                        (
                            setID('hisTrigger'),
                            set::url('###)'),
                            setClass('btn ghost'),
                            icon('clock'),
                            set('data-on', 'click'),
                            set('data-call', 'showHistory')
                        )
                    )
                )
            ),
            div
            (
                setClass('panel-body'),
                setID('content'),
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
            )
        ),
        panel
        (
            setID('history'),
            setClass('hidden'),
            history
            (
                set::objectID($api->id)
            )
        )
    )
);
