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
include "zentaolist.{$lowerType}.html.php";

formPanel
(
    setClass('mb-0'),
    set::title($title),
    set::actions(array('submit')),
    set::submitBtnText($lang->doc->preview),
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
    $fnGenerateFormRows()
);

formPanel
(
    dtable
    (
        set::bordered(true),
        set::height('400px'),
        set::cols(array()),
        set::data(array()),
        set::emptyTip($lang->doc->previewTip)
    ),
    set::submitBtnText($lang->doc->insertText),
    set::cancelBtnText($lang->cancel)
);
render('pagebase');
