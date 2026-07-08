<?php
declare(strict_types=1);
/**
 * The tip view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    setID('tipsModal'),
    set::title($lang->codescan->tips),
    set::headingActions(array(array('url' => createLink('codescan', 'plan', "repoID={$repoID}"), 'icon' => 'close', 'class' => 'ghost'))),
    setClass('m-auto'),
    div
    (
        set::className('flex items-center mt-2'),
        icon('check-circle text-success icon-2x mr-2'),
        span
        (
            set::className('text-md font-bold tip-title'),
            $lang->codescan->afterInfo
        )
    ),
    div
    (
        setClass('mt-5 mb-5'),
        btn
        (
            setID('tipBtn'),
            set::className('tipBtn'),
            $lang->codescan->goBackPlanList,
            set::target('_blank'),
            set::url(createLink('codescan', 'plan', "repoID={$repoID}"))
        )
    )
);
