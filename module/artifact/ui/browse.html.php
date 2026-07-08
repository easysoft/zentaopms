<?php
declare(strict_types=1);
/**
 * The browse view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;

$linkRepoID = empty($repo) ? 0 : $repo->id;
if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    unset($lang->artifact->featureBar);
}
else
{
    featureBar
    (
        set::current($type),
        set::link($this->createLink('artifact', 'browse', "spaceID={$spaceID}&repoID={$linkRepoID}&type={key}")),
    );
}

$canCreate = hasPriv('artifact', 'create');
$canEdit   = hasPriv('artifact', 'edit');
$canDelete = hasPriv('artifact', 'delete');

$createItem = array
(
    'text'        => $lang->artifact->create,
    'url'         => inLink('create', "spaceID={$spaceID}&repoID={$repoID}&type={$type}"),
    'class'       => 'primary',
    'icon'        => 'plus',
    'data-size'   => 'sm',
    'data-toggle' => 'modal'
);

$setItems     = array();
$childActions = array();
if(!empty($artifactLibList))
{
    foreach($artifactLibList as $artifactLib)
    {
        $repoName = (!empty($artifactLib->repoID) && empty($linkRepoID)) ? zget($repoPairs, $artifactLib->repoID, '') : '';
        $childActions = array
        (
            $canEdit ? array
            (
                'icon'         => 'edit',
                'url'          => inLink('edit', "id={$artifactLib->id}"),
                'text'         => $lang->artifact->edit,
                'data-toggle'  => 'modal'
            ) : null,
            $canDelete ? array
            (
                'icon'         => 'trash',
                'url'          => inLink('delete', "id={$artifactLib->id}"),
                'text'         => $lang->artifact->delete,
                'innerClass'   => 'ajax-submit',
                'data-confirm' => $lang->artifact->notice->deleteConfirm
            ) : null
        );

        $setItems[] = div
            (
                setClass('doc-space-card-lib p-1.5 w-1/5 group', set::style(array('min-width' => '118px'))),
                col
                (
                    setClass('canvas border rounded py-2 px-3 col gap-1 hover:shadow-lg hover:border-primary relative cursor-pointer', 'open-url'),
                    set('data-id', $artifactLib->id),
                    set('data-url', inLink('view', "artifactID={$artifactLib->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}")),
                    div
                    (
                        setClass('flex justify-between items-center'),
                        $artifactLib->type == 'file' ? icon('doclib text-2xl', set::style(array('color' => 'var(--color-warning-500)'))) :
                        img
                        (
                            setClass('inline pr-1'),
                            set::style(array('color' => 'var(--color-secondary-100)')),
                            set('src', "static/svg/brand-docker.svg")
                        ),
                        $canEdit || $canDelete ? dropdown
                        (
                            setClass('size-sm ghost w-4 flex-none'),
                            btn
                            (
                                on::click()->prevent(),
                                setClass('btn dropdown-toggle ghost'),
                                set::icon('ellipsis-v'),
                                set::caret(false)
                            ),
                            set::items($childActions)
                        ) : null
                    ),
                    div
                    (
                        setClass('items-center pb-6'),
                        div
                        (
                            setClass('flex flex-wrap items-center gap-2 my-2'),
                            span
                            (
                                setClass('clip font-bold text-md max-w-full'),
                                set::title($artifactLib->name),
                                $artifactLib->name
                            ),
                            !empty($repoName) ? div
                            (
                                setClass('absolute bottom-2 ml-0'),
                                label
                                (
                                    setClass('ghost flex clip text-sm'),
                                    set::title($repoName),
                                    $lang->artifact->repo . ' : ' . $repoName
                                )
                            ) : null
                        ),
                    )
                )
            );
    }
}

featureBar();
toolBar($canCreate ? item(set($createItem)) : null);

panel
(
    empty($setItems) ? div(setClass('w-full h-36 text-center pt-10'), $lang->artifact->notice->noArtifact) : div
    (
        setClass('flex flex-wrap artifact-block'),
        $setItems
    ),
    empty($setItems) ? null : pager
    (
        set::_className('flex justify-end items-center mt-4'),
        set(usePager('pager'))
    )
);
