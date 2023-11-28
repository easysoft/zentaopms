<?php
declare(strict_types=1);
/**
 * The create release view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->api->createRelease);

formPanel
(

    formGroup
    (
        set::label($lang->api->version),
        set::name('version')
    ),
    formGroup
    (
        set::label($lang->api->desc),
        textarea
        (
            set::name('desc')
        )
    )
);
