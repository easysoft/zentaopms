<?php
/**
 * The admin view file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
namespace zin;

formPanel(
    set::title($lang->conference->common),
    set::labelWidth('140px'),
    set::actions(
        array(
            array('text' => $lang->save, 'id' => 'save-button', 'class' => 'btn primary', 'btnType' => 'submit'),
            array('text' => $lang->cancel, 'id' => 'cancel-button', 'class' => 'btn secondary'),
            array('text' => $lang->edit, 'id' => 'edit-button', 'class' => 'btn primary')
        )
    ),
    formGroup(
        set::label($lang->conference->enabled),
        set::id('enabledRow'),
        checkbox(
            set::name('enabled'),
            set::disabled(true),
            set::checked($enabled),
            $lang->conference->enabledTip
        )
    ),
    formGroup(
        set::label($lang->conference->domain),
        input(
            set::name('domain'),
            width('1/4'),
            set::disabled(true),
            set::value(empty($domain) ? '' : $domain),
            set::placeholder($lang->conference->notset)

        )
    )
);

render();
