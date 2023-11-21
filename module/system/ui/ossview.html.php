<?php
declare(strict_types=1);
/**
 * The ossview view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('copySuccess', $lang->system->copySuccess);

$apiURL = !empty($ossDomain->extra_hosts) ? zget($ossDomain->extra_hosts, 'api', '') : '';

panel
(
    set::size('lg'),
    set::title($lang->system->oss->common),
    tableData
    (
        item
        (
            set::name($lang->system->oss->appURL),
            a
            (
                setID('ossVisitUrl'),
                set::target('_blank'),
                $lang->system->visit
            )
        ),
        item
        (
            set::name($lang->system->oss->user),
            span
            (
                setID('ossAdmin')
            )
        ),
        item
        (
            set::name($lang->system->oss->password),
            h::input
            (
                setClass('hidden'),
                setID('ossPassword'),
                set::value(zget($ossAccount, 'password', ''))
            ),
            btn
            (
                $lang->system->copy,
                on::click('copyPassBtn')
            )
        ),
        item
        (
            set::name($lang->system->oss->apiURL),
            $apiURL
        ),
        item
        (
            set::name($lang->system->oss->accessKey),
            zget($ossAccount, 'username', '')
        ),
        item
        (
            set::name($lang->system->oss->secretKey),
            h::input
            (
                setClass('hidden'),
                setID('ossSK'),
                set::value(zget($ossAccount, 'password', ''))
            ),
            btn
            (
                $lang->system->copy,
                on::click('copySK')
            )
        )
    )
);

render();

