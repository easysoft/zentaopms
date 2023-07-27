<?php
declare(strict_types=1);
/**
 * The setting view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('instanceSettingForm'),
    set::title(''),
    set::actions(array('submit')),
    set::submitBtnText($lang->instance->upgrade),
    h::p($desc),
    span
    (
        setClass('p-5'),
        $lang->instance->notices['confirmUpgrade']
    ),
    input(set::type('hidden'), set::name('confirm'), set::value('yes'))
);
