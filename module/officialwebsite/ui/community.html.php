<?php
declare(strict_types=1);
/**
 * The community view file of officialwebsite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     officialwebsite
 * @link        https://www.zentao.net
 */

namespace zin;

set::zui(true);

modalHeader(set::title('加入禅道社区'));

$checked = $agreeUX ? 'checked' : '';

panel
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        div(
            setClass('max-w-7xl h-40'),
            '禅道官网 ',
            br(),
            span(
                $bindCommunityMobile
            ),
            div(
                html("<button class='btn btn-wide btn-primary' id='unBind'>解绑</button>")
            )
        ),
        div(
            '加入',
            a
            (
                setID('experience-plan-show'),
                set('data-size', 'sm'),
                '《用户体验计划》',
                set::href(createLink('officialwebsite', 'planModal')),
                set('data-toggle', 'modal')
            ),
            '情况',
        ),
        div(
            html("<input type='checkbox' id='agreeUX' " . $checked . "/>")
        )
    )
);