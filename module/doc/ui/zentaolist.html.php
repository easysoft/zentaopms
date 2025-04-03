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

if(!$isTemplate)
{
    jsVar('blockType', $type);
    if(strpos(',productStory,ER,UR,planStory,projectStory,',",{$type},") !== false)
    {
        jsVar('gradeGroup', $gradeGroup);
        if($type != 'planStory' && $type != 'projectStory') jsVar('storyType', $storyType);
    }
}

$actions = array();
$actions[] = array('icon' => 'menu-backend', 'text' => $lang->doc->zentaoAction['set'], 'data-toggle' => 'modal', 'url' => str_replace('{blockID}', "$blockID", $settings), 'data-size' => $isTemplate ? 'sm' : 'lg');
$actions[] = array('icon' => 'trash', 'text' => $lang->doc->zentaoAction['delete'], 'zui-on-click' => "deleteZentaoList($blockID)");

if($isTemplate)
{
    $blockTitle = $lang->docTemplate->searchTabList[$type][$searchTab] . $lang->docTemplate->of;
    if($type == 'bug' && $searchTab == 'overduebugs') $blockTitle = $lang->docTemplate->overdue . $lang->docTemplate->of;
    if($type == 'case' && !empty($caseStage)) $blockTitle = $blockTitle . $lang->testcase->stageList[$caseStage];
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
            ($isTemplate ? $blockTitle . $lang->docTemplate->zentaoList[$type] : $lang->doc->zentaoList[$type]) . $lang->doc->list
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
    $isTemplate ? div
    (
        setClass('canvas border rounded py-3 px-3'),
        div
        (
            setClass('config-tip text-center px-3 py-2'),
            $lang->docTemplate->configTip
        )
    ):null,
    !$isTemplate ? dtable
    (
        set::cols(array_values($cols)),
        set::data(array_values($data)),
        set::userMap($users),
        set::emptyTip($lang->doc->previewTip),
        set::checkable(false),
        set::colResize(true),
        set::customCols(false),
        set::onRenderCell(jsRaw('window.renderCell')),
        $type == 'productRelease' ? set::plugins(array('cellspan')) : null,
        $type == 'productRelease' ? set::getCellSpan(jsRaw('window.getCellSpan')) : null
    ) : null
);
