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
    set::submitBtnText($lang->confirm),
    set::actions(array('submit', array('text' => $lang->cancel, 'data-type' => 'submit', 'data-dismiss' => 'modal'))),
    span
    (
        setClass('p-4 font-semibold'),
        icon('exclamation-sign', setClass('text-warning icon-2x align-middle m-2')),
        $lang->instance->notices['confirmUpgrade'],
        empty($instance->latestVersion->change_log_url) ? null: a
        (
            setClass('ml-5'),
            set::target('_blank'),
            set::href($instance->latestVersion->change_log_url),
            setStyle('color', 'initial'),
            setStyle('font-weight', 'initial'),
            setStyle('text-decoration', 'underline'),
            $lang->instance->updateLog,
        ),
    ),
    div(setStyle('height', '20px')),
    input(set::type('hidden'), set::name('confirm'), set::value('yes'))
);
