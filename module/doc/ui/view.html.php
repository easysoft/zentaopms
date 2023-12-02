<?php
declare(strict_types=1);
/**
 * The view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    li(backBtn(setClass('ghost'), set::icon('back'), $lang->goback)),
);

if($libID && common::hasPriv('doc', 'create')) include 'createbutton.html.php';
include 'lefttree.html.php';

toolbar
(
    $canExport ? item(set(array
    (
        'id'          => $exportMethod,
        'icon'        => 'export',
        'class'       => 'ghost export',
        'text'        => $lang->export,
        'url'         => $createLink('doc', $exportMethod, "libID={$libID}&moduleID={$moduleID}"),
        'data-toggle' => 'modal'
    ))) : null,
    common::hasPriv('doc', 'createLib') ? item(set(array
    (
        'icon'        => 'plus',
        'class'       => 'btn secondary',
        'text'        => $lang->doc->createLib,
        'url'         => createLink('doc', 'createLib', "type={$type}&objectID={$objectID}"),
        'data-toggle' => 'modal'
    ))) : null,
    $libID && common::hasPriv('doc', 'create') ? $createButton : null
);

$versionList = array();
for($itemVersion = $doc->version; $itemVersion > 0; $itemVersion--)
{
    $versionList[] = array('text' => "V$itemVersion", 'url' => createLink('doc', 'view', "docID={$docID}&version={$itemVersion}"));
}

$star        = strpos($doc->collector, ',' . $app->user->account . ',') !== false ? 'star' : 'star-empty';
$collectLink = $this->createLink('doc', 'collect', "objectID=$doc->id");
$starBtn     = "<a data-url='$collectLink' title='{$lang->doc->collect}' class='ajax-submit btn btn-link'>" . html::image("static/svg/{$star}.svg", "class='$star'") . '</a>';

/* 导入资产库的按钮. */
if($config->vision == 'rnd' and ($config->edition == 'max' or $config->edition == 'ipd') and $app->tab == 'project')
{
    $canImportToPracticeLib  = (common::hasPriv('doc', 'importToPracticeLib')  and helper::hasFeature('practicelib'));
    $canImportToComponentLib = (common::hasPriv('doc', 'importToComponentLib') and helper::hasFeature('componentlib'));

    if($canImportToPracticeLib)  $items[] = array('text' => $lang->doc->importToPracticeLib,  'url' => '#importToPracticeLib',  'data-toggle' => 'modal');
    if($canImportToComponentLib) $items[] = array('text' => $lang->doc->importToComponentLib, 'url' => '#importToComponentLib', 'data-toggle' => 'modal');

    $importLibBtn = $items ? dropdown
    (
        btn
        (
            setClass('ghost btn square btn-default'),
            icon('diamond')
        ),
        set::items($items)
    ) : null;
}

$createInfo = $doc->status == 'draft' ? zget($users, $doc->addedBy) . " {$lang->colon} " . substr($doc->addedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->createAB : zget($users, $doc->releasedBy) . " {$lang->colon} " . substr($doc->releasedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->release;

$keywordsLabel = array();
if($doc->keywords)
{
    foreach($doc->keywords as $keywords)
    {
        if($keywords)
        {
            $keywordsLabel[] = span
            (
                setClass('label secondary-outline ml-2'),
                $keywords
            );
        }
    }
}

/* Build editor group. */
$editorGroup = '';
if(!empty($editors))
{
    $space       = common::checkNotCN() ? ' ' : '';
    $firstEditor = current($editors);
    $editorInfo  = zget($users, $firstEditor->account) . ' ' . substr($firstEditor->date, 0, 10) . $space . $lang->doc->update;

    array_shift($editors);

    $items = array();
    foreach($editors as $editor)
    {
        $info = zget($users, $editor->account) . ' ' . substr($editor->date, 0, 10) . $space . $lang->doc->update;
        $items[] = array('text' => $info);
    }

    $editorGroup = dropdown
    (
        btn
        (
            setClass('ghost btn square btn-default'),
            $editorInfo
        ),
        set::items($items)
    );
}

$contentDom = div
(
    setClass('flex-auto'),
    setID('docPanel'),
    div
    (
        setClass('panel-heading'),
        div
        (
            setClass('flex-1 w-0'),
            div
            (
                setClass('title clip'),
                $doc->title
            )
        ),
        $doc->deleted ? span(setClass('label danger'), $lang->doc->deleted) : null,
        $doc->status != 'draft' ? dropdown
        (
            btn
            (
                setClass('ghost btn square btn-default selelct-version'),
                'V' . ($version ? $version : $doc->version)
            ),
            set::items($versionList)
        ) : null,
        div
        (
            setClass('panel-actions flex'),
            div
            (
                setClass('toolbar'),
                btn
                (
                    setClass('btn ghost'),
                    icon('fullscreen'),
                    on::click('fullScreen')
                ),
                common::hasPriv('doc', 'collect') ? html($starBtn) : null,
                ($config->vision == 'rnd' and ($config->edition == 'max' or $config->edition == 'ipd') and $app->tab == 'project') ? $importLibBtn : null,
                common::hasPriv('doc', 'edit') ? btn
                (
                    set::url(createLink('doc', 'edit', "docID=$doc->id")),
                    setClass('btn ghost'),
                    icon('edit')
                ) : null,
                common::hasPriv('doc', 'delete') ? btn
                (
                    set::url(createLink('doc', 'delete', "docID=$doc->id")),
                    setClass('btn ghost ajax-submit'),
                    set('data-confirm', $lang->doc->confirmDelete),
                    icon('trash')
                ) : null,
                btn
                (
                    set::id('hisTrigger'),
                    set::url('###)'),
                    setClass('btn ghost'),
                    icon('clock'),
                    on::click('showHistory')
                )
            ),
            div
            (
                set::id('editorBox'),
                setClass('flex'),
                $editorGroup
            )
        )
    ),
    div
    (
        setClass('info'),
        span
        (
            setClass('user-time text-gray mr-2'),
            icon
            (
                'contacts',
                setClass('mr-2')
            ),
            $createInfo
        ),
        span
        (
            setClass('user-time text-gray mr-2'),
            icon
            (
                'star',
                setClass('mr-2')
            ),
            $doc->collects ? $doc->collects : 0
        ),
        span
        (
            setClass('user-time text-gray'),
            icon
            (
                'eye',
                setClass('mr-2')
            ),
            $doc->views
        ),
        $keywordsLabel ? span
        (
            setClass('keywords'),
            $keywordsLabel
        ) : null
    ),
    div
    (
        setClass('detail-content article-content'),
        html($doc->content)
    ),
    div
    (
        setClass('docFile'),
        $doc->files ? h::hr() : null,
        $doc->files ? fileList
        (
            set::files($doc->files)
        ) : null
    )
);

$treeDom = isset($outlineTree) ? div
(
    setClass('mt-8 border-l of-auto'),
    setID('contentTree'),
    tree
    (
        set::items($outlineTree),
        set::defaultNestedShow(true)
    )
) : null;

$toggleTreeBtn = isset($outlineTree) ? btn
    (
        setID('outlineToggle'),
        setClass('btn ghost'),
        setStyle('background', '#FFF'),
        icon('menu-arrow-right'),
        on::click('toggleOutline')
    ) : null;

$historyDom = div
(
    set::id('history'),
    setClass('hidden border-l'),
    history(set::objectID($doc->id))
);

panel
(
    set::bodyClass('doc-content'),
    div
    (
        setClass('flex'),
        $contentDom,
        $treeDom,
        $toggleTreeBtn,
        $historyDom
    )
);
