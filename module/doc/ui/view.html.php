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

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

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
        'id'     => $exportMethod,
        'icon'   => 'export',
        'target' => '_self',
        'class'  => 'ghost export',
        'text'   => $lang->export,
        'url'    => createLink('doc', $exportMethod, "libID={$libID}&moduleID={$moduleID}&docID={$doc->id}")
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
    $versionList[] = array('text' => "V$itemVersion", 'url' => createLink('doc', 'view', "docID={$docID}&version={$itemVersion}"), 'active' => $itemVersion == $version);
}

$star        = strpos($doc->collector, ',' . $app->user->account . ',') !== false ? 'star' : 'star-empty';
$collectLink = $this->createLink('doc', 'collect', "objectID=$doc->id");
$starBtn     = "<a data-url='$collectLink' title='{$lang->doc->collect}' class='ajax-submit btn btn-link'>" . html::image("static/svg/{$star}.svg", "class='$star'") . '</a>';

/* 导入资产库的按钮. */
$importLibItems = array();
if($config->vision == 'rnd' and ($config->edition == 'max' or $config->edition == 'ipd') and $app->tab == 'project')
{
    $canImportToPracticeLib  = (common::hasPriv('doc', 'importToPracticeLib')  and helper::hasFeature('practicelib'));
    $canImportToComponentLib = (common::hasPriv('doc', 'importToComponentLib') and helper::hasFeature('componentlib'));

    if($canImportToPracticeLib)  $importLibItems[] = array('text' => $lang->doc->importToPracticeLib,  'url' => '#importToPracticeLib',  'data-toggle' => 'modal', 'data-size' => 'sm');
    if($canImportToComponentLib) $importLibItems[] = array('text' => $lang->doc->importToComponentLib, 'url' => '#importToComponentLib', 'data-toggle' => 'modal', 'data-size' => 'sm');

    $importLibBtn = $importLibItems ? dropdown
    (
        btn
        (
            setClass('ghost btn square btn-default'),
            icon('diamond')
        ),
        set::items($importLibItems)
    ) : null;
}

$createInfo = $doc->status == 'draft' ? zget($users, $doc->addedBy) . " {$lang->hyphen} " . substr($doc->addedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->createAB : zget($users, $doc->releasedBy) . " {$lang->hyphen} " . substr($doc->releasedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->release;

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

    $editorGroup = count($items) > 0 ? dropdown
    (
        btn
        (
            setClass('ghost btn btn-default'),
            $editorInfo
        ),
        set::items($items)
    ) : btn
    (
        setClass('ghost btn btn-default'),
        $editorInfo
    );
}

$contentDom = div
(
    setClass('flex-auto'),
    setID('docPanel'),
    div
    (
        setClass('panel-heading pl-0'),
        div
        (
            setClass('flex-1 w-0'),
            div
            (
                setClass('title clip inline-flex'),
                set::title($doc->title),
                $doc->title
            ),
            $doc->status != 'draft' ? dropdown
            (
                btn
                (
                    setClass('ghost btn square btn-default selelct-version inline-flex ml-1'),
                    span(setClass('pl-1'), 'V' . ($version ? $version : $doc->version))
                ),
                set::items($versionList)
            ) : null
        ),
        $doc->deleted ? span(setClass('label danger'), $lang->doc->deleted) : null,
        div
        (
            setClass('panel-actions flex'),
            $editorGroup ? setClass('hasEditor') : null,
            div
            (
                setClass('toolbar'),
                btn
                (
                    setClass('btn ghost'),
                    icon('fullscreen'),
                    set::url('javascript:$("#docPanel").fullscreen()'),
                ),
                common::hasPriv('doc', 'collect') && !$doc->deleted ? html($starBtn) : null,
                ($config->vision == 'rnd' and ($config->edition == 'max' or $config->edition == 'ipd') and $app->tab == 'project') ? $importLibBtn : null,
                common::hasPriv('doc', 'edit') && !$doc->deleted ? btn
                (
                    set::url(createLink('doc', 'edit', "docID=$doc->id")),
                    $doc->type != 'text' ? setData('toggle', 'modal') : null,
                    setClass('btn ghost'),
                    icon('edit'),
                    setData('app', $app->tab)
                ) : null,
                common::hasPriv('doc', 'delete') && !$doc->deleted ? btn
                (
                    set::url(createLink('doc', 'delete', "docID=$doc->id")),
                    setClass('btn ghost ajax-submit'),
                    set('data-confirm', array('message' => $lang->doc->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x')),
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
                $editorGroup
            )
        )
    ),
    div
    (
        setClass('info mb-4'),
        div
        (
            setClass('user-time text-gray mr-2 inline-flex items-center'),
            icon
            (
                'contacts',
                setClass('mr-2')
            ),
            $createInfo
        ),
        div
        (
            setClass('user-time text-gray mr-2 inline-flex items-center'),
            icon
            (
                'star-empty',
                setClass('mr-1')
            ),
            $doc->collects ? $doc->collects : 0
        ),
        div
        (
            setClass('user-time text-gray inline-flex items-center'),
            icon
            (
                'eye',
                setClass('mr-1')
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
        setClass('detail-content article'),
        $doc->contentType == 'markdown' ? editor
        (
            set::size('full'),
            set::markdown(true),
            set::readonly(true),
            set::hideUI(true),
            html($doc->content)
        ) : html($doc->content)
    ),
    div
    (
        setClass('docFile'),
        $doc->files ? h::hr(setClass('mt-4')) : null,
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
        set::className('pl-4'),
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
    history(set::objectID($doc->id), set::objectType('doc'))
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

if($importLibItems)
{
    modal
    (
        setID('importToPracticeLib'),
        formPanel
        (
            set::title($lang->doc->importToPracticeLib),
            set::actions(array('submit')),
            set::submitBtnText($lang->import),
            set::url(createLink('doc', 'importToPracticeLib', "doc={$doc->id}")),
            set::formClass('mt-6'),
            formGroup
            (
                set::label($lang->doc->practiceLib),
                setID('practiceLib'),
                set::name('lib'),
                set::items($practiceLibs),
                set::required(true)
            ),
            !common::hasPriv('assetlib', 'approvePractice') && !common::hasPriv('assetlib', 'batchApprovePractice') ? formGroup
            (
                set::label($lang->doc->approver),
                picker
                (
                    setID('practiceApprover'),
                    set::name('assignedTo'),
                    set::items($practiceApprovers),
                    set::required(true)
                )
            ) : null
        )
    );

    modal
    (
        setID('importToComponentLib'),
        formPanel
        (
            set::title($lang->doc->importToComponentLib),
            set::actions(array('submit')),
            set::submitBtnText($lang->import),
            set::url(createLink('doc', 'importToComponentLib', "doc={$doc->id}")),
            set::formClass('mt-6'),
            formGroup
            (
                set::label($lang->doc->componentLib),
                setID('componentLib'),
                set::name('lib'),
                set::items($componentLibs),
                set::required(true)
            ),
            !common::hasPriv('assetlib', 'approveComponent') && !common::hasPriv('assetlib', 'batchApproveComponent') ? formGroup
            (
                set::label($lang->doc->approver),
                picker
                (
                    setID('componentApprover'),
                    set::name('assignedTo'),
                    set::items($componentApprovers),
                    set::required(true)
                )
            ) : null
        )
    );
}
