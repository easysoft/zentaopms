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
set::zui();

$lowerType = strtolower($type);
include "zentaolist.customsearch.html.php";
include "zentaolist.{$lowerType}.html.php";

$isSetting = $view == 'setting';

formPanel
(
    setID('zentaolist'),
    setClass('mb-0 pb-0', array('hidden' => !$isSetting)),
    set('data-type', $type),
    set('data-settings', $settings),
    set::title($title),
    set::actions(array()),
    to::titleSuffix
    (
        span
        (
            setClass('text-muted text-sm text-gray-600 font-light'),
            span
            (
                setClass('text-warning mr-1'),
                icon('help'),
            ),
            $lang->doc->insertTip
        )
    ),
    $fnGenerateFormRows(),
    to::footer
    (
        setClass('form-actions'),
        btn
        (
            setID('preview'),
            set::type('primary'),
            $lang->doc->preview
        )
    ),
    on::change('[name=product]', "changeProduct"),
    on::click('#preview', "preview"),
    on::change('[name=condition]', "changeCondition")
);

$cols = array_values($cols);
$data = array_values($data);

$actions = array();
$actions[] = array('icon' => 'menu-backend', 'text' => $lang->doc->zentaoAction['set'], 'onClick' => jsRaw('backToSet'));
$actions[] = array('icon' => 'trash', 'text' => $lang->doc->zentaoAction['delete']);

formPanel
(
    setID('previewForm'),
    set::bodyClass('p-0-important'),
    set::actions(array()),
    div
    (
        setClass('relative'),
        !$isSetting ? div
        (
            setClass('font-bold text-xl pb-2'),
            $lang->doc->zentaoList[$type] . $lang->doc->list
        ) : null,
        dtable
        (
            setID('previewTable'),
            $isSetting ? set::height(320) : null,
            set::bordered(true),
            set::cols($cols),
            set::data($data),
            set::emptyTip($lang->doc->previewTip),
            set::checkable($view === 'setting'),
            set::plugins(array('checkable')),
        ),
        !$isSetting ? div
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
        ) : null
    ),
    to::footer
    (
        setClass('form-actions', array('hidden' => !$isSetting)),
        setStyle(array('position' => 'relative')),
        btn
        (
            setID('insert'),
            set('data-tip', $lang->doc->insertTip),
            set::type('primary'),
            $lang->doc->insertText
        ),
        btn
        (
            setID('cancel'),
            $lang->cancel
        ),
        on::click('#insert', "insert"),
        on::click('#cancel', "cancel")
    )
);
render('pagebase');
