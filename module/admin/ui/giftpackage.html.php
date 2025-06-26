<?php
declare(strict_types=1);
/**
 * The plan modal view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

modalHeader(set::title('领取项目礼包'));

formPanel
(
    set::id('giftPackage'),
    formRow
    (
        formGroup
        (
            setClass('gift-package-group font-normal gift-package-nickname'),
            set::width('1/2'),
            set::label($lang->admin->community->giftPackageFormNickname),
            set::name('nickname'),
            set::required(true)
        ),
        formGroup
        (
            setClass('gift-package-group font-normal gift-package-position'),
            set::width('1/2'),
            set::label($lang->admin->community->giftPackageFormPosition),
            set::control('picker'),
            set::name('position'),
            set::items($lang->admin->community->positionList),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            setClass('gift-package-group font-normal gift-package-company'),
            set::label($lang->admin->community->giftPackageFormCompany),
            set::name('company'),
            set::required(true)
        )
    ),
    div
    (
        setClass('gift-package-question'),
        $lang->admin->community->giftPackageFormQuestion,
    ),
    formRow
    (
        formGroup
        (
            setClass('gift-package-group-solvedProblems font-normal'),
            set::name('solvedProblems'),
            set::control('checkList'),
            set::items($lang->admin->community->solvedProblems)
        )
    )
);
