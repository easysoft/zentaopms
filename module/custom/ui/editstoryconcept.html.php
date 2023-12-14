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

formPanel
(
    set::formClass('border-0'),
    set::title($lang->custom->editStoryConcept),
    formRow
    (
        $config->URAndSR ? formGroup
        (
            set::width('1/2'),
            setClass('justify-center mr-8'),
            span
            (
                setClass('text-md font-bold'),
                $lang->custom->URConcept
            )
        ) : null,
        formGroup
        (
            set::width('1/2'),
            setClass('justify-center'),
            span
            (
                setClass('text-md font-bold'),
                $lang->custom->SRConcept
            )
        )
    ),
    formRow
    (
        formGroup
        (
            setClass('mr-8'),
            setClass(!$this->config->URAndSR ? 'hidden' : ''),
            set::width('1/2'),
            set::name('URName'),
            set::value($URSR->URName)
        ),
        formGroup
        (
            set::width('1/2'),
            set::name('SRName'),
            set::value($URSR->SRName)
        )
    )
);

/* ====== Render page ====== */
render();
