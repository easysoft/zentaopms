<?php
declare(strict_types=1);
/**
 * The api home file of api module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

$libColors    = array('var(--color-secondary-500)', 'var(--color-primary-500)', 'var(--color-warning-500)', 'var(--color-success-500)', 'var(--color-special-500)');
$isNolink     = $type == 'nolink';
$isEmpty      = $isNolink ? empty($libs) : empty($programs);
$canCreateLib = hasPriv('api', 'createLib');
$canEditLib   = hasPriv('api', 'editLib');
$canDeleteLib = hasPriv('api', 'deleteLib');
$showLibAction = $isNolink ? ($canEditLib || $canDeleteLib) : $canCreateLib;

/* Create load home command statement. */
$createCommand = function(array $args) use($pager, $type, $unclosed, $notempty)
{
    $args = array_merge(array('type' => $type, 'unclosed' => $unclosed, 'notempty' => $notempty, 'recPerPage' => $pager->recPerPage, 'pageID' => $pager->pageID), $args);
    $parts = array('loadHome');
    $parts[] = $args['type'];
    $params = array();
    if($args['notempty']) $params[] = 'notempty';
    if($args['unclosed']) $params[] = 'unclosed';
    $parts[] = implode('_', $params);
    $parts[] = $args['recPerPage'];
    $parts[] = $args['pageID'];
    return implode('/', $parts);
};

$buildLibItem = function(int $id, string $name, int $count = 0) use ($libColors, $type, $isNolink, $showLibAction, $lang)
{
    return div
    (
        setClass('doc-space-card-lib px-2 w-1/5 group'),
        setKey($id),
        div
        (
            setClass('canvas border rounded py-2 px-3 col gap-1 hover:shadow-lg hover:border-primary relative cursor-pointer'),
            set('zui-command', $isNolink ? "selectSpace/nolink/$id" : "selectSpace/$type.$id/_first"),
            icon($isNolink ? 'doclib' : $type, setClass('text-2xl'), $isNolink ? setStyle('color', $libColors[$id % count($libColors)]) : setClass('text-gray')),
            div(setClass('font-bold text-clip'), set::title($name), $name),
            div(setClass('text-gray text-sm'), $count ? sprintf($lang->api->apiTotalInfo, $count) : $lang->api->noApi),
            $showLibAction ? div
            (
                setClass('toolbar absolute top-1 right-1 opacity-0 group-hover:opacity-100'),
                btn
                (
                    setClass('doc-space-lib-btn'),
                    set('zui-command', "showHomeItemMenu/$type/$id"),
                    set::icon('ellipsis-v'),
                    set::size('sm'),
                    set::type('ghost')
                )
            ) : null
        )
    );
};

$buildProgramItem = function($program) use ($type, $buildLibItem)
{
    $items = array();
    if($type === 'product' && !empty($program->products))
    {
        foreach($program->products as $product) $items[] = $buildLibItem($product->id, $product->name, isset($product->apiCount) ? $product->apiCount : 0);
    }
    if($type === 'project' && !empty($program->projects))
    {
        foreach($program->projects as $project) $items[] = $buildLibItem($project->id, $project->name, isset($project->apiCount) ? $project->apiCount : 0);
    }
    return div
    (
        setClass('doc-space-card ring rounded surface-light'),
        setKey($program->id),
        div
        (
            setClass('row items-center justify-between gap-2 px-2.5 py-1 border-b'),
            div
            (
                setClass('row items-center gap-2 flex-none cursor-pointer hover:text-primary'),
                setClass('row items-center gap-2 flex-none cursor-pointer hover:text-primary'),
                icon('program', setClass('text-gray flex-none')),
                div(setClass('min-w-0 flex-auto'), strong($program->name)),
            )
        ),
        div
        (
            setClass('doc-space-card-libs py-3 px-1.5'),
            div
            (
                setClass('row'),
                $items
            )
        )
    );
};

$filterItems = array();
foreach($lang->api->homeFilterTypes as $key => $text)
{
    $item = array('text' => $text, 'zui-command' => $createCommand(array('type' => $key)));
    if($key === $type)
    {
        $count          = $isNolink ? count($libs) : count($programs);
        $item['active'] = true;
        $item['badge']  = wg(label(setClass('size-sm canvas ring-0 rounded-md'), $count));
    }
    $filterItems[] = $item;
}

div
(
    setKey('header'),
    setClass('doc-app-header flex-none surface-light row items-center border-b py-1 pl-1 h-10 pr-2 gap-3 flex-none'),
    nav
    (
        setClass('ml-2'),
        set::compact(),
        set::items($filterItems)
    ),
    checkbox(set::name('notempty'), set::text($lang->api->showNotEmpty), set::checked($notempty)),
    $type !== 'nolink' ? checkbox(set::name('showclosed'), set::text($lang->api->showClosed), set::checked(!$unclosed)) : null,
    div(setClass('flex-auto')),
    (!$isEmpty && $canCreateLib) ? btn
    (
        set::icon('plus'),
        set::type('primary'),
        set::size('md'),
        set::text($lang->api->createLib),
        set('zui-command', "createLib/$type")
    ) : null,
    on::change('[type=checkbox]')
        ->const('notempty', jsRaw('$this.closest(".doc-app-header").find("[name=notempty]:checked").length'))
        ->const('showclosed', jsRaw('$this.closest(".doc-app-header").find("[name=showclosed]:checked").length'))
        ->call('loadHome', $type, jsRaw('[notempty ? "notempty" : "", showclosed ? "" : "unclosed"].join("_")'))
);

$views = array();
if($isEmpty)
{
    $views = div
    (
        setKey('empty'),
        setClass('doc-app-empty h-full center gap-4'),
        div(setClass('text-gray'), $lang->api->noLib),
        $canCreateLib ? btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->api->createLib),
            set('zui-command', "createLib/$type")
        ) : null
    );
}
elseif($type === 'nolink')
{
    foreach($libs as $lib) $views[] = $buildLibItem($lib->id, $lib->name, isset($lib->apiCount) ? $lib->apiCount : 0);
    $views = div
    (
        setClass('doc-space-card-libs'),
        setStyle('margin', '0 -8px'),
        div(setClass('row'), $views)
    );
}
else
{
    foreach($programs as $program) $views[] = $buildProgramItem($program);
}

div
(
    setKey('body'),
    setClass('doc-app-body flex-auto min-h-0 col gap-4 p-4 items-stretch overflow-auto scrollbar-hover no-morph'),
    $views
);

if(!$isEmpty)
{
    div
    (
        setKey('footer'),
        setClass('doc-app-footer surface-light row items-center border-t py-1.5 px-2'),
        div(setClass('flex-auto')),
        pager
        (
            set(usePager()),
            set::useState(),
            set::onChangePageInfo(jsRaw('(info, event) => {event.preventDefault(); window.loadHome({pager: {recPerPage: info.recPerPage, page: info.page}});}'))
        )
    );
}
