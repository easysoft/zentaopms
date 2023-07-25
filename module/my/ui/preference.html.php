<?php
declare(strict_types=1);
/**
 * The preference view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->my->preference);

form
(
    set::labelWidth('140px'),
    formGroup
    (
        set::label($lang->my->storyConcept),
        picker
        (
            set::class('URSR'),
            set::name('URSR'),
            set::items($URSRList),
            set::value($URSR),
        )
    ),
    $this->config->systemMode == 'ALM' ? formGroup
    (
        set::label($lang->my->productLink),
        picker
        (
            set::class('programLink'),
            set::name('programLink'),
            set::items($lang->my->programLinkList),
            set::value($programLink)
        )
    ) : null,
    formGroup
    (
        set::label($lang->my->productLink),
        picker
        (
            set::class('productLink'),
            set::name('productLink'),
            set::items($lang->my->productLinkList),
            set::value($productLink)
        )
    ),
    formGroup
    (
        set::label($lang->my->projectLink),
        picker
        (
            set::class('projectLink'),
            set::name('projectLink'),
            set::items($lang->my->projectLinkList),
            set::value($projectLink)
        )
    ),
    formGroup
    (
        set::label($lang->my->executionLink),
        picker
        (
            set::class('executionLink'),
            set::name('executionLink'),
            set::items($lang->my->executionLinkList),
            set::value($executionLink)
        )
    ),
    set::actions(array('submit'))
);

render('modalDialog');
