<?php
declare(strict_types=1);
/**
 * The create view file of client module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */

namespace zin;

if(!empty($error))
{
    div(html($error));
}
else
{
    $downloadLinks = [];
    foreach($lang->client->zipList as $os => $name) $downloadLinks[] = inputGroup(inputGroupAddon(width(28), $name), input(set::name("downloads[{$os}]")));

    formPanel
    (
        set::title($lang->client->create),
        set::submitBtnText($lang->save),
        formGroup
        (
            set::label($lang->client->version),
            set::required(true),
            input(set::name('version'))
        ),
        formGroup
        (
            set::label($lang->client->desc),
            input(set::name('desc'))
        ),
        formGroup
        (
            set::label($lang->client->changeLog),
            textarea(set::name('changeLog'), set::rows(5))
        ),
        formGroup
        (
            set::label($lang->client->strategy),
            radioList
            (
                set::name('strategy'),
                set::items($lang->client->strategies),
                set::value('optional'),
                set::inline(true)
            )
        ),
        formGroup
        (
            set::label($lang->client->links),
            col(setClass('gap-2 w-full'), $downloadLinks)
        ),
        formGroup
        (
            set::label($lang->client->releaseStatus),
            radioList
            (
                set::name('status'),
                set::items($lang->client->status),
                set::value('wait'),
                set::inline(true)
            ),
            span(setClass('text-warning ml-4 mt-1.5'), $lang->client->releaseTip)
        )
    );
}

render();
