<?php
declare(strict_types=1);
/**
 * The flow view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('flowForm'),
    set::actions(array('submit')),
    set::actionsClass('w-1/3'),
    span
    (
        setClass('text-md font-bold'),
        $lang->custom->flow
    ),
    formGroup
    (
        set::label('1.'),
        setClass('items-center'),
        span($lang->custom->conceptQuestions['overview'])
    ),
    formGroup
    (
        set::label(''),
        radioList
        (
            set::name('sprintConcept'),
            set::items($lang->custom->sprintConceptList),
            set::value(zget($this->config->custom, 'sprintConcept', '0')),
            set::inline(true)
        )
    ),
    formGroup
    (
        set::label('2.'),
        setClass('items-center'),
        span($lang->custom->conceptQuestions['storypoint'])
    ),
    formGroup
    (
        set::label(''),
        radioList
        (
            set::name('hourPoint'),
            set::items($lang->custom->conceptOptions->hourPoint),
            set::value(zget($this->config->custom, 'hourPoint')),
            set::inline(true)
        )
    )
);

/* ====== Render page ====== */
render();
