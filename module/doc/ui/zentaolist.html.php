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
set::zui(true);

$cols = array_values($cols);
$data = array_values($data);

$actions = array();
$actions[] = array('icon' => 'menu-backend', 'text' => $lang->doc->zentaoAction['set'], 'onClick' => jsRaw('backToSet'));
$actions[] = array('icon' => 'trash', 'text' => $lang->doc->zentaoAction['delete'], 'onClick' => jsRaw('cancel'));

formPanel
(
    setID('previewForm'),
    setClass('mb-0-important'),
    set('data-settings', $settings),
    set::bodyClass('p-0-important'),
    set::actions(array()),
    div
    (
        setClass('relative'),
        div
        (
            setClass('font-bold text-xl pb-2'),
            $lang->doc->zentaoList[$type] . $lang->doc->list
        ),
        dtable
        (
            setID('previewTable'),
            set::bordered(true),
            set::cols($cols),
            set::data($data),
            set::userMap($users),
            set::emptyTip($lang->doc->previewTip),
            set::checkable(false),
            /* set::afterRender(jsRaw('toggleCheckRows')) */
        ),
        div
        (
            setClass('absolute right-0 top-0'),
            dropdown
            (
                btn
                (
                    set::type('ghost'),
                    set::icon('ellipsis-v'),
                    set::caret(false),
                    on::click()->prevent()->stop()
                ),
                set::items($actions),
                set::flip(true),
                set::strategy('absolute'),
                set::hasIcons(false),
                set::trigger('hover')
            )
        )
    )
);

render('pagebase');
