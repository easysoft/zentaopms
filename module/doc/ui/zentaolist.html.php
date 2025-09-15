<?php
declare(strict_types=1);
/**
 * The zentaoList view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xinzhi Qi <qixinzhi@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$noticeTip = $noSupport ? sprintf($lang->doc->noSupportList, $lang->doc->zentaoList[$type] . $lang->doc->list) : '';

if($type == 'gantt' && !empty($ganttData))
{
    $userList = array();
    foreach($users as $account => $realname) $userList[] = array('key' => $account, 'label' => $realname);
}

if(!$isTemplate)
{
    jsVar('blockType', $type);
    if(strpos(',productStory,ER,UR,planStory,projectStory,',",{$type},") !== false)
    {
        jsVar('gradeGroup', $gradeGroup);
        if($type != 'planStory' && $type != 'projectStory') jsVar('storyType', $storyType);
    }
}

if($type == 'productCase')
{
    jsVar('scene', $lang->testcase->sceneb);
    jsVar('automated', $lang->testcase->automated);
    jsVar('noCase', $lang->scene->noCase);
    jsVar('caseChanged', $lang->testcase->changed);
}

$actions = array();
$setText = (!$isTemplate && $fromTemplate && !$fromReport) ? $lang->doc->zentaoAction['setParams'] : $lang->doc->zentaoAction['set'];
if(!$noSupport && ($type != 'gantt' || !$isTemplate)) $actions[] = array('icon' => 'menu-backend', 'text' => $setText, 'data-toggle' => 'modal', 'url' => str_replace('{blockID}', "$blockID", $settings), 'data-size' => $isTemplate || $fromReport ? 'sm' : 'lg');
$actions[] = array('icon' => 'trash', 'text' => $lang->doc->zentaoAction['delete'], 'zui-on-click' => "deleteZentaoList($blockID)");

if($isTemplate || $fromTemplate)
{
    $blockTitle = '';
    if(!empty($lang->docTemplate->searchTabList[$type][$searchTab])) $blockTitle = $lang->docTemplate->searchTabList[$type][$searchTab] . $lang->docTemplate->of;
    if($type == 'bug' && $searchTab == 'overduebugs') $blockTitle = $lang->docTemplate->overdue . $lang->docTemplate->of;
    if(($type == 'productCase' || $type == 'projectCase') && !empty($caseStage)) $blockTitle = $blockTitle . $lang->testcase->stageList[$caseStage];
}

$emptyTip = $lang->doc->previewTip;
if(!$isTemplate && $fromTemplate) $emptyTip = $isSetted ? $lang->docTemplate->emptyTip : $lang->docTemplate->previewTip;
if($fromReport) $emptyTip = $lang->docTemplate->emptyDataTip;

$listText  = $type == 'gantt' ? $lang->docTemplate->zentaoList['gantt'] : $lang->doc->list;
$emptyText = $isTemplate ? sprintf($lang->docTemplate->configTip, $listText) : $emptyTip;

$pagerSetting = usePager();
unset($pagerSetting['linkCreator']);

$dataTable = null;
if(!$isTemplate && $type != 'gantt' && !$noSupport)
{
    $dataTable = dtable
        (
            set::cols(array_values($cols)),
            set::data(array_values($data)),
            set::userMap($users),
            set::emptyTip($emptyTip),
            set::checkable(false),
            set::colResize(true),
            set::customCols(false),
            set::onRenderCell(jsRaw('window.renderCell')),
            set::localPager(),
            set::footPager($pagerSetting),
            set::footer(array('flex', 'pager')),
            $type == 'productRelease' ? set::rowKey('rowID') : null,
            $type == 'productRelease' ? set::plugins(array('cellspan')) : null,
            $type == 'productRelease' ? set::getCellSpan(jsRaw('window.getCellSpan')) : null
        );
}

div
(
    set('data-id', $blockID),
    setClass('zentao-list my-3'),
    setCssVar('--affine-font-base', '13px!important'),
    setStyle('font-size', '13px'),
    css('.is-readonly .zentao-list-actions {display: none}'),
    div
    (
        setClass('zentao-list-heading row items-center gap-2 mb-1'),
        h2
        (
            setClass('font-bold text-xl'),
            (($isTemplate || $fromTemplate || $fromReport) ? $blockTitle . $lang->docTemplate->zentaoList[$type] : $lang->doc->zentaoList[$type]) . ($type == 'gantt' ? '' : $lang->doc->list)
        ),
        div
        (
            setClass('zentao-list-actions toolbar flex-auto justify-end'),
            dropdown
            (
                set::trigger('hover'),
                set::placement('bottom-end'),
                set::items($actions),
                btn(set::icon('ellipsis-v'), set::caret(false), set::type('ghost'))
            )
        )
    ),
    $isTemplate || ($type == 'gantt' && empty($ganttData)) ? div
    (
        setClass('canvas border rounded py-3 px-3'),
        div
        (
            setClass('config-tip text-center px-3 py-2'),
            $emptyText
        )
    ):null,
    $dataTable,
    $noSupport ? div
    (
        setClass('canvas border rounded py-3 px-3'),
        div
        (
            setClass('config-tip text-center px-3 py-2 text-gray-400'),
            div($noticeTip)
        )
    ) : null,
    $type == 'gantt' && !empty($ganttData) ? zui::gantt
    (
        set::onInit(jsRaw('window.onInitGantt')),
        set::data($ganttData['data']),
        set::links($ganttData['links']),
        set::ganttFields($ganttFields),
        set::showFields(explode(',', $showFields)),
        set::userList($userList),
        set::exts('zentao')
    ): null
);
