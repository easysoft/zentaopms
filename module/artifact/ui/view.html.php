<?php
declare(strict_types=1);
/**
 * The view file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      OpenAI
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('copyMessage', $lang->artifact->copied);
jsVar('copyFail', $lang->artifact->copyFail);
if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    unset($lang->artifact->featureBar);
}
if($artifact->type == 'file')
{
    unset($config->artifact->dtable->fieldList['version']);
    unset($config->artifact->dtable->fieldList['sysArch']);
    unset($config->artifact->dtable->fieldList['package']);
}
else
{
    unset($config->artifact->dtable->fieldList['path']);
}

$breadCrumbsBox = array();
if(!empty($breadCrumbs))
{
    if(count($breadCrumbs) < 5)
    {
        foreach($breadCrumbs as $pathName => $pathItems)
        {
            $breadCrumbsBox[] = span('>', setStyle('margin', '5px'));
            $breadCrumbsBox[] = picker
                (
                    setClass('picker-btn state'),
                    setStyle('box-shadow', 'none'),
                    set::items($pathItems),
                    set::search(false),
                    set::required(true),
                    set::menu(jsRaw('{searchBox: true, search: undefined}')),
                    set::display(jsRaw("(value, selections) => {
                    return {html: `<div style='max-width: 100px'>\${selections.map(x => x.text).join(',')}</div><style>.picker-btn .caret{display:none}</style><button type='button' class='picker-btn-trigger btn size-xs square text-primary'><i class='icon icon-exchange'></i></button>`, className: 'flex justify-between gap-2 p-px'};}")),
                    set::value(helper::safe64Encode($pathName))
                );
        }
    }
    else
    {
        $breadCrumbs = array_keys($breadCrumbs);
        $count = 0;
        $last  = end($breadCrumbs);
        $breadCrumb      = '';
        foreach($breadCrumbs as $pathName)
        {
            if($count < 3)
            {
                $breadCrumb .= '>' . basename($pathName);
            }

            if($pathName == $last)
            {
                $breadCrumb .= '>...>' . basename($pathName);
            }
            $count++;
        }
        $breadCrumbsBox[] = span(ltrim($breadCrumb, '>'), set::title($last), setStyle('margin', '5px'), setStyle('color', 'var(--color-text)'));
    }
}

$data              = initTableData($assetList, $config->artifact->dtable->fieldList);
$viewLink          = createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$selectPath}&leaf={$leaf}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
$canDeleteArtifact = hasPriv('artifact', 'deleteArtifact');
$canCreateDir      = hasPriv('artifact', 'createDir') && $artifact->type == 'file' && !$leaf;
$canUploadArtifact = hasPriv('artifact', 'uploadArtifact') && !empty($selectPath) && $artifact->type == 'file' && !$leaf;

$gitfoxURL = str_replace(array('http://', 'https://'), '', $config->devops->gitfoxURL);
if($config->devops->gitfoxPort) $gitfoxURL .= ":{$config->devops->gitfoxPort}";
$typeCode  = '';
if($artifact->scope == 'repo')
{
    $typeCode = 'repo' . $artifact->repoID;
}
else
{
    $typeCode = zget($space, 'code');
}

$imageNoticeDom   = array();
$imageCommands    = array();
$maxCommandLength = 0;
if($artifact->type == 'container')
{
    foreach($lang->artifact->pushImageTip as $content)
    {
        $command           = str_replace(array('GITFOXURL', 'TYPECODE', 'LIBCODE', 'IMAGE'), array($gitfoxURL, $typeCode, zget($artifact, 'code')), $content['content']);
        $imageCommands[]   = $command;
        $maxCommandLength  = max($maxCommandLength, strlen($command));
    }

    foreach($imageCommands as $tipKey => $command)
    {
        $content = $lang->artifact->pushImageTip[$tipKey];
        $imageNoticeDom[] = div
        (
            setClass('mb-2'),
            p(setClass('mb-1 font-bold'), $content['title']),
            div
            (
                setClass('flex space-between'),
                div
                (
                    setClass('flex-1'),
                    input
                    (
                        set::type('text'),
                        set::value($command),
                        set::size($maxCommandLength + 1),
                        setStyle('width', 'max-content'),
                        set::readOnly(true),
                        set::title($command)
                    )
                ),
                div
                (
                    set::width('50px'),
                    btn
                    (
                        set::className('copy-btn'),
                        set::icon('copy')
                    )
                )
            )
        );
    }
}

div
(
    setClass('surface-light row flex justify-between items-center border-l border-t border-r py-1.5 pl-1 pr-2'),
    div
    (
        setClass('row'),
        btn
        (
            setClass('ghost text-primary square'),
            set::title('home'),
            set::icon('back'),
            set::url($browseLink)
        ),
        picker
        (
            setClass('picker-btn state'),
            setStyle('box-shadow', 'none'),
            set::items($artifactLibList),
            set::search(false),
            set::required(true),
            set::menu(jsRaw('{searchBox: true, search: undefined}')),
            set::display(jsRaw("(value, selections) => {
            return {html: `<div>{$lang->artifact->common}: \${selections.map(x => x.text).join(',')}</div><style>.picker-btn .caret{display:none}</style><button type='button' class='picker-btn-trigger btn size-xs square text-primary'><i class='icon icon-exchange'></i></button>`, className: 'flex justify-between gap-2 p-px'};}")),
            set::value($artifact->id)
        ),
        empty($breadCrumbsBox) ? null : $breadCrumbsBox,
    ),
    !$canUploadArtifact ? null : div
    (
        btn
        (
            set
            (
                array
                (
                    'class'       => 'primary',
                    'icon'        => 'export',
                    'data-toggle' => 'modal',
                    'url'         => helper::createLink('artifact', 'uploadArtifact', "artifactID={$artifact->id}&path={$selectPath}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"),
                    'text'        => $lang->artifact->uploadArtifact
                )
            )
        )
    ),
    $artifact->type == 'container' ? div
    (
        dropdown
        (
            set::staticMenu(true),
            set::triggerProps(array('notHideOnClick' => '.dropdown-menu')),
            set::menuClass('artifact-image-tip-menu'),
            btn(setClass('ghost text-primary'), set::icon('help'), $lang->artifact->pushImageNotice),
            to::items
            (
                div
                (
                    $imageNoticeDom,
                    on::click('.copy-btn')->call('copyCommand', jsRaw('this'))
                )
            )
        )
    ) : null
);

div
(
    setClass('flex min-h-0 flex-1 row border h-auto items-stretch'),
    setStyle('min-height', 'calc(100vh - 120px)'),
    sidebar
    (
        set::side('left'),
        setClass('repo-sidebar canvas min-h-0 border-r self-stretch'),
        setStyle('min-height', 'calc(100vh - 120px)'),
        set::width(280),
        set::preserve(false),
        div
        (
            setID('artifactViewTreeBlock'),
            setClass('p-2 relative min-h-full'),
            tree
            (
                setClass('filesTree'),
                set::_props(array('data-refresh-url' => $viewLink)),
                set::items($treeItems),
                set::collapsedIcon('folder text-warning'),
                set::expandedIcon('folder-open text-warning'),
                set::normalIcon('stack'),
                set::preserve(false),
                set::hover(true),
                set::defaultNestedShow($selectNode),
            ),
            div(setClass('h-12 shrink-0')),
            div
            (
                setID('artifactViewToolbar'),
                setClass('flex justify-end gap-2 px-3 py-2'),
                setStyle('position', 'absolute'),
                setStyle('right', '0'),
                setStyle('bottom', '0'),
                //btn
                //(
                //    setID('artifactViewToggleAll'),
                //    setClass('btn ghost size-sm'),
                //    set::title($lang->artifact->expandAll),
                //    set::icon('icon-list-collapse'),
                //    on::click()->call('window.isExpand', jsRaw('$this'))
                //),
                $canCreateDir ? btn
                (
                    set
                    (
                        array
                        (
                            'text'        => $lang->artifact->addDirectory,
                            'class'       => 'ghost',
                            'icon'        => 'plus',
                            'data-toggle' => 'modal',
                            'url'         => helper::createLink('artifact', 'createDir', "artifactID={$artifact->id}&path={$selectPath}&isSubDir=1"),
                        )
                    ),
                ) : null
            )
        )
    ),
    panel
    (
        setID('artifactViewPage'),
        setClass('flex-1 min-w-0 min-h-0 flex flex-col overflow-hidden self-stretch'),
        setStyle('min-height', 'calc(100vh - 120px)'),
        set::bodyClass('w-full'),
        dtable
        (
            setID('artifactAssetsTable'),
            set::cols($config->artifact->dtable->fieldList),
            set::data($data),
            set::userMap($users),
            set::orderBy($orderBy),
            set::checkable($canDeleteArtifact),
            $canDeleteArtifact ? set::onCheckChange(jsRaw('window.toggleArtifactBatchDelete')) : null,
            $canDeleteArtifact ? set::footToolbar(array
            (
                'type'  => 'btn-group',
                'items' => array(array
                (
                    'text'         => $lang->artifact->batchDeleteArtifact,
                    'btnType'      => 'secondary',
                    'className'    => 'batch-btn artifact-batch-delete hidden',
                    'data-on'      => 'click',
                    'data-call'    => 'batchDeleteArtifact',
                    'data-params'  => 'event',
                    'data-url'     => helper::createLink('artifact', 'ajaxBatchDeleteArtifact', "artifactID={$artifact->id}"),
                    'data-confirm' => $lang->artifact->notice->confirmDelete
                ))
            )) : null,
            set::sortLink(createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$selectPath}&leaf={$leaf}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
            set::footPager(usePager()),
            set::emptyTip($lang->artifact->notice->emptyAsset)
        )
    )
);
