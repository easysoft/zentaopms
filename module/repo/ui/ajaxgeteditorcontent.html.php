<?php
declare(strict_types=1);
/**
 * The ajaxgeteditorcontent view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */

namespace zin;

$canLinkStory    = common::hasPriv('repo', 'linkStory');
$canLinkBug      = common::hasPriv('repo', 'linkBug');
$canLinkTask     = common::hasPriv('repo', 'linkTask');
$canUnlinkObject = common::hasPriv('repo', 'unlink');

jsVar('unlinkTitle', $this->lang->repo->unlink);
jsVar('canUnlinkObject', $canUnlinkObject);
jsVar('fileExt', $this->config->repo->fileExt);
jsVar('file', $pathInfo);
jsVar('filePath', $entry);
jsVar('blameTmpl', $lang->repo->blameTmpl);
jsVar('repoID', $repoID);
jsVar('showEditor', $showEditor);
jsVar('canLinkStory', $canLinkStory);
jsVar('canLinkBug', $canLinkBug);
jsVar('canLinkTask', $canLinkTask);
jsVar('objectID', 0);
jsVar('objectType', 'story');
jsVar('pageType', $type);
jsVar('revision', $revision);
jsVar('sourceRevision', $oldRevision);
jsVar('encodePath', $this->repo->encodePath($entry));
if($showEditor) jsVar('codeContent', $content);

$lang = 'php';
foreach($this->config->repo->fileExt as $langName => $exts)
{
    foreach($exts as $ext) if(isset($pathInfo['extension']) && str_contains('.' . $pathInfo['extension'], $ext)) $lang = $langName;
}

if(strpos($config->repo->images, "|$suffix|") !== false)
{
    $wg = div
    (
        set::className('image'),
        img(set::src('data:image/' . $suffix . ';base64,' . $content))
    );
}
elseif($suffix == 'binary')
{
    $wg = div
    (
        set::className('binary'),
        a
        (
            set('data-link', $this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevision=$revision")),
            icon('download'),
            on::click('downloadCode'),
            set::title($this->lang->repo->download)
        )
    );
}
else
{
    $options = array(
        'value'                => $content,
        'language'             => $lang,
        'readOnly'             => true,
        'autoIndent'           => true,
        'contextmenu'          => true,
        'automaticLayout'      => true,
        'EditorMinimapOptions' => array('enabled' => false)
    );
    if($type == 'diff') $options = array(
        'language'             => $lang,
        'readOnly'             => true,
        'autoIndent'           => true,
        'contextmenu'          => true,
        'automaticLayout'      => true,
        'renderSideBySide'     => false,
        'EditorMinimapOptions' => array('enabled' => false)
    );
    $wg = monaco
    (
        set::id('codeContainer'),
        set::options($options),
        set::action($type == 'diff' ? 'diff' : 'create'),
        $type == 'diff' ? set::diffContent(jsRaw('parent.getDiffs(filePath)')) : null,
        set::onMouseDown('window.onMouseDown')
    );
}

$dropMenus = array();
if($canLinkStory) $dropMenus[] = array('id' => 'linkStory', 'text' => $this->lang->repo->linkStory, 'icon' => 'lightbulb');
if($canLinkBug)   $dropMenus[] = array('id' => 'linkBug',   'text' => $this->lang->repo->linkBug,   'icon' => 'bug');
if($canLinkTask)  $dropMenus[] = array('id' => 'linkTask',  'text' => $this->lang->repo->linkTask,  'icon' => 'todo');

$logWg = div
(
    set::id('log'),
    div(set::className('history')),
    div
    (
        set::className('action-btn pull-right'),
        div(set::className('btn btn-close pull-right ghost text-black bg-light bg-opacity-50'), icon('close')),
        !empty($dropMenus) ? dropdown
        (
            set::arrow(false),
            set::staticMenu(true),
            btn
            (
                setClass('ghost text-black bg-light bg-opacity-50'),
                set::icon('ellipsis-v rotate-90')
            ),
            set::items
            (
                $dropMenus
            )
        ) : ''
    )
);

$relatedWg = div
(
    set::id('related'),
    div(set::className('btn btn-left pull-left'), icon('chevron-left')),
    div(set::className('btn btn-right pull-right'), icon('chevron-right')),
    div
    (
        set::className('panel-title'),
        tabs
        (
            set::id('relationTabs'),
            tabPane
            (
                set::key('tab1'),
                set::title(''),
                tableData()
            )
        )
    ),
    div(set::className('table-empty-tip'), p($this->lang->repo->notRelated))
);

div
(
    set::id('monacoEditor'),
    set::className('repoCode'),
    $wg,
    $logWg,
    $relatedWg
);

set::zui(true);
render('pageBase');
