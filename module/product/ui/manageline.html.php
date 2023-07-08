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

$lineMenuList = null;
$formRowList  = null;
foreach($lines as $line)
{
    $lineMenuList[] = div
    (
        set::class('ml-4 line-item flex items-center'),
        span($line->name),
        btn
        (
            icon('trash'),
            set::size('sm'),
            setClass('ghost text-gray ajax-submit'),
            set::url(createLink('product', 'ajaxDeleteLine', "lineID={$line->id}")),
            set('data-confirm', $lang->product->confirmDeleteLine),
        )
    );

    $formRowList[] = formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::name("modules[id$line->id]"),
            set::value($line->name),
        ),
        $config->systemMode == 'ALM' ? formGroup
        (
            set::width('1/3'),
            set::class('ml-4 required'),
            set::name("programs[id$line->id]"),
            set::items($programs),
            set::value($line->root),
        ) : null,
    );
}

for($i = 0; $i <= 5; $i ++)
{
    $formRowList[] = formRow
    (
        set::class('line-row-add'),
        formGroup(set::width('1/3'), set::name("modules[$i]")),
        $config->systemMode == 'ALM' ? formGroup
        (
            set::width('1/3'),
            set::class('ml-4 required'),
            set::name("programs[$i]"),
            set::items($programs),
        ) : null,
        formGroup
        (
            setClass('ml-2 pl-2 flex self-center'),
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
                on::click('removeLine'),
            ),
        )
    );
}

jsVar('+index', $i);

to::header
(
    span(set::class('article-h2 w-1/4'), $lang->product->line),
    span(set::class('article-h2'), $lang->product->manageLine),
);

div
(
    set::class('flex'),
    cell
    (
        set::width('1/3'),
        set::class('lineTree mr-1'),
        $lineMenuList
    ),
    cell
    (
        set::width('2/3'),
        form
        (
            set::submitBtnText($lang->save),
            set::actionsClass('justify-start'),
            set::class('border-b-0'),
            $formRowList
        )
    )
);

/* ====== Render page ====== */
render();
