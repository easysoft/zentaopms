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

jsVar('docID', $docID);

if(!isInModal()) include 'lefttree.html.php';

$versionList = array();
for($itemVersion = $doc->version; $itemVersion > 0; $itemVersion--)
{
    $versionList[] = array('text' => "V$itemVersion", 'url' => createLink('doc', 'view', "docID={$docID}&version={$itemVersion}"), 'key' => $itemVersion, 'active' => $itemVersion == $version);
}

$versionMenuOptions = array();
if($config->edition != 'open' && common::hasPriv('doc', 'diff'))
{
    $versionMenuOptions['header']       = jsRaw('window.getVersionHeader');
    $versionMenuOptions['footer']       = jsRaw('window.getVersionFooter');
    $versionMenuOptions['getItem']      = jsRaw('window.getDropdownItem');
    $versionMenuOptions['onClickItem']  = jsRaw('window.onClickDropdownItem');
    $versionMenuOptions['width']        = 200;
    $versionMenuOptions['checkOnClick'] = '.has-checkbox .item';
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
        btn(setClass('ghost btn square btn-default'), icon('diamond')),
        set::items($importLibItems)
    ) : null;
}

$createInfo = $doc->status == 'draft' ? zget($users, $doc->addedBy) . " {$lang->hyphen} " . substr($doc->addedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->createAB : zget($users, $doc->releasedBy) . " {$lang->hyphen} " . substr($doc->releasedDate, 0, 10) . (common::checkNotCN() ? ' ' : '') . $lang->doc->release;

$keywordsLabel = array();
if($doc->keywords)
{
    foreach($doc->keywords as $keywords)
    {
        if(!$keywords) continue;
        $keywordsLabel[] = span(setClass('label secondary-outline'), $keywords);
    }
}

$docMoreActions = array();
if(hasPriv('doc', 'delete') && !$doc->deleted)
{
    $docMoreActions[] = array
    (
        'class'        => 'ajax-submit',
        'url'          => createLink('doc', 'delete', "docID=$doc->id"),
        'icon'         => 'trash',
        'text'         => $lang->delete,
        'data-confirm' => array('message' => $lang->doc->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x'),
    );
}
if(!empty($docMoreActions)) $docMoreActions[] = array('type' => 'divider');
$docMoreActions[] = array
(
    'id'      => 'hisTrigger',
    'icon'    => 'clock',
    'text'    => $lang->history,
    'onClick' => jsRaw('() => $("#docPanel").toggleClass("show-history")')
);

/* Build editor group. */
$editorGroup = '';
if(!empty($editors))
{
    $space = common::checkNotCN() ? ' ' : '';
    $items = array();
    foreach($editors as $editor)
    {
        $info = zget($users, $editor->account) . ' ' . substr($editor->date, 0, 10) . $space . $lang->doc->update;
        $items[] = array('text' => $info);
    }

    $docMoreActions[] = array
    (
        'icon'  => 'info',
        'text'  => $lang->doc->updateInfo,
        'items' => $items
    );
}

$docHeader = div
(
    setID('docHeader'),
    setClass('w-full row items-center gap-4 sticky top-0 z-10 bg-canvas py-3 pl-4 pr-2'),
    div
    (
        setClass('flex-auto row items-center min-w-0 gap-2'),
        setStyle('max-width', 'calc(100% - 200px)'),
        div
        (
            setClass('title clip inline-flex text-xl font-bold'),
            set::title($doc->title),
            $doc->title
        ),
        $doc->status != 'draft' ? dropdown
        (
            btn
            (
                set::type('gray-pale'),
                setClass('rounded-full size-xs gap-1'),
                'V' . ($version ? $version : $doc->version)
            ),
            set::items($versionList),
            set::menu($versionMenuOptions)
        ) : null,
        $doc->deleted ? span(setClass('label danger size-sm'), $lang->doc->deleted) : null
    ),
    div
    (
        setClass('flex-none flex items-center'),
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
                set::type('ghost'),
                set::url(createLink('doc', 'edit', "docID=$doc->id")),
                $doc->type != 'text' ? setData('toggle', 'modal') : null,
                set::icon('edit text-primary'),
                set::text($lang->edit)
            ) : null,
            dropdown
            (
                btn
                (
                    set::type('ghost'),
                    set::caret(false),
                    span(setClass('more-vert text-primary')),
                    span($lang->more),
                ),
                set::items($docMoreActions)
            )
        )
    )
);

$contentDom = div
(
    setID('docContent'),
    setClass('flex-auto min-w-0 px-4'),
    div
    (
        setClass('row items-center py-1 gap-3'),
        div
        (
            setClass('text-gray inline-flex items-center gap-2'),
            icon('contacts'),
            $createInfo
        ),
        div
        (
            setClass('text-gray inline-flex items-center gap-2'),
            icon('star-empty'),
            $doc->collects ? $doc->collects : 0
        ),
        div
        (
            setClass('text-gray inline-flex items-center gap-2'),
            icon('eye'),
            $doc->views
        )
    ),
    $keywordsLabel ? div
    (
        setClass('row items-center gap-2 mt-1 pl-px'),
        $keywordsLabel,
    ) : null,
    div
    (
        setID('docEditor'),
        setClass('detail-content article'),
        editor
        (
            set::resizable(false),
            set::markdown($doc->contentType == 'markdown'),
            set::readonly(true),
            set::hideUI(true),
            set::size('auto'),
            html($doc->content)
        )
    ),
    div
    (
        setClass('docFile'),
        $doc->files ? h::hr(setClass('mt-4')) : null,
        $doc->files ? fileList
        (
            set::objectType('doc'),
            set::objectID($doc->id),
            set::files($doc->files)
        ) : null
    )
);

$treeDom = isset($outlineTree) ? div
(
    setID('docOutline'),
    setStyle('max-height', 'calc(100vh - 121px)'),
    setStyle('top', '48px'),
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
    setClass('canvas ring-0 absolute right-2 top-0'),
    icon('menu-arrow-left'),
    on::click()->do('$("#docPanel").toggleClass("show-outline")'),
) : null;

$historyDom = div
(
    setId('docHistory'),
    setClass('relative'),
    btn
    (
        setID('closeBtn'),
        setClass('canvas ring-0 absolute right-2 top-0'),
        set::type('ghost'),
        set::icon('close'),
        on::click()->do('$("#docPanel").removeClass("show-history")')
    ),
    history(set::objectID($doc->id), set::objectType('doc'))
);

panel
(
    setID('docPanel'),
    setClass('ring scrollbar-hover overflow-y-auto'),
    set::bodyClass('w-full p-0'),
    set::bodyProps(array('id' => 'docBody')),
    $docHeader,
    div
    (
        setClass('flex-auto w-full row relative'),
        $contentDom,
        div
        (
            setID('docSidebar'),
            setClass('flex-none overflow-y-auto scrollbar-hover sticky border-l'),
            setStyle('width', 'var(--doc-sidebar-width)'),
            $treeDom,
            $historyDom,
        ),
        $toggleTreeBtn
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
