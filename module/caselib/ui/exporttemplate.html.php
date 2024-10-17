<?php
declare(strict_types=1);
/**
 * The export template view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->testcase->exportTemplate);

formPanel
(
    set::target('_self'),
    on::submit('setDownloading'),
    formGroup
    (
        set::label($lang->caselib->recordNum),
        set::name('num'),
        set::type('number'),
        set::value(10)
    ),
    formGroup
    (
        set::label($lang->caselib->templateType),
        set::name('encode'),
        set::required(true),
        set::value('gbk'),
        set::control(array('control' => 'picker', 'items' => $config->charsets[$this->cookie->lang]))
    ),
    set::actions(array('submit')),
    set::submitBtnText($lang->export)
);

render('modalDialog');
