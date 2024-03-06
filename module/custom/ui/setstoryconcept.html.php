<?php
declare(strict_types=1);
/**
 * The setStoryConcept view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

formBatchPanel
(
    set::formClass('border-0'),
    set::title($lang->custom->setStoryConcept),
    set::minRows(1),
    set::maxRows(1),
    $config->enableER ? formBatchItem
    (
        set::width('1/3'),
        set::label($lang->custom->ERConcept),
        set::name('ERName')
    ) : null,
    $config->URAndSR ? formBatchItem
    (
        set::width('1/3'),
        set::label($lang->custom->URConcept),
        set::name('URName')
    ) : null,
    formBatchItem
    (
        set::width('1/3'),
        set::label($lang->custom->SRConcept),
        set::name('SRName')
    )
);

/* ====== Render page ====== */
render();
