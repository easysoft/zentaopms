<?php
declare(strict_types=1);
/**
 * The export view file of transfer module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    set::style('--tw-shadow', 'none'),
    set::title($title),
    form
    (
        setClass('mt-4 mr-5 ml-5'),
        set::enctype('multipart/form-data'),
        set::target('_self'),
        input(set::type('file'), set::name('file')),
        span(setClass('label secondary'), $lang->transfer->importNotice)
    )
);
h::js('$.cookie.set("maxImport", 0, {expires:config.cookieLife, path:config.webRoot});');
