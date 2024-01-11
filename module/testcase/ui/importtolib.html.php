<?php
declare(strict_types=1);
/**
 * The import to lib view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($title));

formPanel
(
    formGroup
    (
        set::label($lang->testcase->selectLibAB),
        set::required(true),
        picker
        (
            set::name('lib'),
            set::items($libraries)
        )
    )
);

render();
