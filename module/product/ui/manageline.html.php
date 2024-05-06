<?php
declare(strict_types=1);
/**
 * The manageLine view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('changeProgramTip', $lang->product->lineChangeProgram);

$tree         = array();
$formRowList  = null;
$formRowList[] = formRow
(
    cell
    (
        setClass('flex flex-1'),
        formGroup
        (
            set::width(in_array($this->config->systemMode, array('ALM', 'PLM')) ? '1/2' : 'full'),
            set::label($lang->product->lineName),
            set::labelClass('font-bold')
        ),
        in_array($this->config->systemMode, array('ALM', 'PLM')) ? formGroup
        (
            set::width('1/2'),
            set::className('ml-4'),
            set::label($lang->product->program),
            set::labelClass('font-bold required')
        ) : null
    ),
    cell
    (
        set::width('100px'),
        formGroup
        (
            span(setClass('form-label font-bold'), set::style(array('justify-content' =>'flex-start')), $lang->actions)
        )
    )
);

foreach($lines as $line)
{
    $line->actions['items'][] = array('key' => 'delete', 'icon' => 'trash', 'className' => 'btn ghost toolbar-item square size-sm rounded ajax-submit', 'data-confirm' => $lang->product->confirmDeleteLine, 'url' => createLink('product', 'ajaxDeleteLine', 'lineID=' . $line->id));

    $formRowList[] = formRow
    (
        cell
        (
            setClass('flex flex-1'),
            formGroup
            (
                set::width(in_array($this->config->systemMode, array('ALM', 'PLM')) ? '1/2' : 'full'),
                set::control(array('control' => 'text', 'id' => "modules_id{$line->id}")),
                set::name("modules[id$line->id]"),
                set::value($line->name)
            ),
            in_array($this->config->systemMode, array('ALM', 'PLM')) ? formGroup
            (
                set::width('1/2'),
                set::className('ml-4'),
                set::control(array('control' => 'picker', 'id' => "programs_id{$line->id}", 'required' => true)),
                set::name("programs[id$line->id]"),
                set::items($programs),
                set::value($line->root),
                on::change('isProductLineEmpty')
            ) : null
        ),
        cell(set::width('100px'))
    );
}

for($i = 0; $i <= 5; $i ++)
{
    $formRowList[] = formRow
    (
        set::className('line-row-add'),
        cell
        (
            setClass('flex flex-1'),
            formGroup
            (
                set::width(in_array($this->config->systemMode, array('ALM', 'PLM')) ? '1/2' : 'full'),
                set::control(array('control' => 'text', 'id' => "modules_{$i}")),
                set::name("modules[$i]")
            ),
            in_array($this->config->systemMode, array('ALM', 'PLM')) ? formGroup
            (
                set::width('1/2'),
                set::className('ml-4'),
                set::control(array('control' => 'picker', 'id' => "programs_{$i}", 'required'=> true)),
                set::name("programs[$i]"),
                set::items($programs),
                set::value('0')
            ) : null
        ),
        cell
        (
            set::width('100px'),
            formGroup
            (
                setClass('ml-5 pl-2 flex self-center'),
                btn
                (
                    setClass('btn btn-link text-gray addLine'),
                    icon('plus'),
                    on::click('addNewLine')
                ),
                btn
                (
                    setClass('btn btn-link text-gray removeLine'),
                    icon('trash'),
                    on::click('removeLine')
                )
            )
        )
    );
}

jsVar('+index', $i);

modalHeader(set::title($lang->product->manageLine), set::titleClass('text-lg font-bold'));
div
(
    set::className('flex'),
    cell
    (
        set::width('1/3'),
        set::className('lineTree mr-1'),
        panel
        (
            set::title($lang->product->line),
            treeEditor
            (
                set::type('line'),
                set::items($lines),
                set::canEdit(false),
                set::canSplit(false),
                set::canDelete(false),
                set::sortable(),
                set::onSort(jsRaw('window.updateOrder'))
            )
        )
    ),
    cell
    (
        set::width('2/3'),
        form
        (
            set::submitBtnText($lang->save),
            set::className('border-b-0'),
            $formRowList
        )
    )
);

/* ====== Render page ====== */
render();
