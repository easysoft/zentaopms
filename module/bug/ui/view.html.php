<?php
declare(strict_types=1);
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

jsVar('bugID',     $bug->id);
jsVar('productID', $bug->product);
jsVar('branchID',  $bug->branch);
jsVar('errorNoExecution', $lang->bug->noExecution);
jsVar('errorNoProject',   $lang->bug->noProject);

$isInModal    = isInModal();
$canCreateBug = hasPriv('bug', 'create');
$canViewRepo  = hasPriv('repo', 'revision');
$canViewMR    = hasPriv('mr', 'view');
$canViewBug   = hasPriv('bug', 'view');
$operateList  = $this->loadModel('common')->buildOperateMenu($bug);

/* Handling special tags in bug descriptions. */
$tplStep   = strip_tags(trim($lang->bug->tplStep));
$steps     = str_replace('<p>' . $tplStep, '<p class="font-bold my-1">' . $tplStep . '</p><p>', $bug->steps);
$tplResult = strip_tags(trim($lang->bug->tplResult));
$steps     = str_replace('<p>' . $tplResult, '<p class="font-bold my-1">' . $tplResult . '</p><p>', $steps);
$tplExpect = strip_tags(trim($lang->bug->tplExpect));
$steps     = str_replace('<p>' . $tplExpect, '<p class="font-bold my-1">' . $tplExpect . '</p><p>', $steps);
$steps     = str_replace('<p></p>', '', $steps);

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && $canCreateBug)
{
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->bug->create,
        'url'  => createLink('bug', 'create', "productID={$product->id}")
    );
}

/* 初始化底部操作栏。Init bottom actions. */
$actions = array();
if(!$bug->deleted)
{
    /* Construct common actions for bug. */
    foreach($operateList['mainActions'] as $operate)
    {
        if(!empty($operate['id']) && $operate['id'] == 'toStory') $operate['data-url'] = createLink('story', 'create', "product={$bug->product}&branch={$bug->branch}&module={$bug->module}&story=0&execution=0&bugID={$bug->id}");
        $actions[] = $operate;
    }
    if(!empty($operateList['suffixActions'])) $actions = array_merge($actions, array(array('type' => 'divider')), $operateList['suffixActions']);
}

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->bug->legendSteps)
    ->control('html')
    ->content($steps);

if($bug->files)
{
    $sections[] = array
    (
        'title'      => $lang->files,
        'control'    => 'fileList',
        'files'      => $bug->files,
        'object'     => $bug
    );
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->bug->legendBasicInfo)
    ->control('bugBasicInfo')
    ->statusText($this->processStatus('bug', $bug));

/* 一生信息。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->bug->legendLife)
    ->control('bugLifeInfo');

$tabs[] = setting()
    ->group('related')
    ->title(!empty($project->multiple) ? $lang->bug->legendPRJExecStoryTask : $lang->bug->legendExecStoryTask)
    ->control('bugRelatedInfo');

$tabs[] = setting()
    ->group('related')
    ->title($lang->bug->legendMisc)
    ->control('bugRelatedList');

detail
(
    set::urlFormatter(array('{id}' => $bug->id, '{product}' => $bug->product, '{branch}' => $bug->branch, '{project}' => $bug->project, '{execution}' => $bug->execution)),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions($actions)
);

modal
(
    setID('toTask'),
    set::modalProps(array('title' => $lang->bug->selectProjects)),
    to::footer
    (
        div
        (
            setClass('toolbar gap-4 w-full justify-center'),
            btn($lang->bug->nextStep, setID('toTaskButton'), setClass('primary')),
            btn($lang->cancel, setID('cancelButton'), setData(array('dismiss' => 'modal')))
        )
    ),
    formPanel
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(''),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->selectProjects),
                set::required(true),
                set::control('picker'),
                set::name('taskProjects'),
                set::items($projects)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->bug->execution),
                set::required(true),
                inputGroup
                (
                    setID('executionBox'),
                    picker
                    (
                        set::name('execution'),
                        set::items($executions)
                    )
                )
            )
        )
    )
);
