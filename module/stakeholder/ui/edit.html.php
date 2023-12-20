<?php
declare(strict_types=1);
/**
 * The edit view file of stakeholder module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      stakeholder <stakeholder@easycorp.ltd>
 * @package     stakeholder
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title($lang->stakeholder->edit),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->stakeholder->isKey),
        radioList(set::name('key'), set::items($lang->stakeholder->keyList), set::value($stakeholder->key), set::inline(true))
    ),
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->name),
        set::name('name'),
        set::value($stakeholder->name),
        set::required(true)
    ) : null,
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->phone),
        set::name('phone'),
        set::value($stakeholder->phone)
    ) : null,
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->qq),
        set::name('qq'),
        set::value($stakeholder->qq)
    ) : null,
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->weixin),
        set::name('weixin'),
        set::value($stakeholder->weixin)
    ) : null,
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->email),
        set::name('email'),
        set::value($stakeholder->email)
    ) : null,
    $stakeholder->from == 'outside' ? formGroup
    (
        set::width('1/3'),
        setClass('user-info'),
        set::label($lang->stakeholder->company),
        set::name('company'),
        set::control(array('type' => 'picker', 'items' => $companys)),
        set::value($stakeholder->company)
    ) : null,
    formGroup
    (
        set::width('full'),
        set::label($lang->stakeholder->nature),
        editor
        (
            set::name('nature'),
            html($stakeholder->nature)
        )
    ),
    formGroup
    (
        set::width('full'),
        set::label($lang->stakeholder->analysis),
        editor
        (
            set::name('analysis'),
            html($stakeholder->analysis)
        )
    ),
    formGroup
    (
        set::width('full'),
        set::label($lang->stakeholder->strategy),
        editor
        (
            set::name('strategy'),
            html($stakeholder->strategy)
        )
    )
);
