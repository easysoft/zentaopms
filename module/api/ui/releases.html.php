<?php
declare(strict_types=1);
/**
 * The releases view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->api->releases);

if(empty($releases))
{
    div
    (
        setClass('table-empty-tip flex justify-center items-center'),
        span
        (
            setClass('text-gray'),
            $lang->api->noRelease
        )
    );
}
else
{
    $tbody = array();
    foreach($releases as $release)
    {
        $tbody[] = h::tr
        (
            h::td(str_pad((string)$release->id, 3, '0', STR_PAD_LEFT)),
            h::td($release->version),
            h::td($release->desc),
            h::td(zget($users, $release->addedBy)),
            h::td(substr($release->addedDate, 0, 10)),
            h::td
            (
                common::hasPriv('api', 'deleteRelease') ? html(html::a(helper::createLink('api', 'deleteRelease', "libID=$libID&id=$release->id"), '<i class="icon-trash"></i>', '', "data-confirm={$lang->custom->notice->confirmDelete} class='ghost ajax-submit'")) : null
            )
        );
    }

    h::table
    (
        setClass('table condensed bordered'),
        h::tr
        (
            h::th
            (
                width('60px'),
                $lang->idAB
            ),
            h::th
            (
                width('160px'),
                $lang->api->version
            ),
            h::th
            (
                width('160px'),
                $lang->api->desc
            ),
            h::th
            (
                width('100px'),
                $lang->api->addedBy
            ),
            h::th
            (
                width('100px'),
                $lang->api->structAddedDate
            ),
            h::th
            (
                width('50px'),
                $lang->actions
            )
        ),
        $tbody
    );
}
