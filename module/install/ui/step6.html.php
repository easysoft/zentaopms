<?php
declare(strict_types=1);
/**
 * The step6 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$successLabel = ($installFileDeleted ? $lang->install->successLabel : $lang->install->successNoticeLabel);

jsVar('sendEventLink', $sendEventLink);


div
(
setClass('install-logo'),
    img(
        set::src('static/images/install-logo.png')
    )
);
div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        panel
        (
            setClass('p-2'),
            cell
            (
                setClass('flex mb-4'),
                img(
                    setClass('check-img'),
                    set::src('static/images/install-success.png')
                ),
            ),
            cell(
                cell
                (
                    setClass('flex justify-center success-label'),
                    $lang->install->congratulations
                ),
                cell(
                    setClass('flex'),
                    html(nl2br(sprintf($successLabel, $config->version)))
                ),
            ),
            cell
            (
                setClass('next-btn'),
                setClass('flex justify-center'),
                btn
                (
                    setClass('px-4'),
                    set::url($adminRegisterLink),
                    set::type('primary'),
                    $lang->install->next
                )
            )
        )
    )
);

render('pagebase');
