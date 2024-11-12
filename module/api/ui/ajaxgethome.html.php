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
