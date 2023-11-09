<?php
declare(strict_types=1);
/**
* The dynamic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$content = array();
if(empty($actions))
{
    $content[] = div
    (
        setClass('flex items-center justify-center h-64'),
        span
        (
            setClass('text-gray'),
            $lang->action->noDynamic
        ),
    );
}
else
{
    $content[] = div
    (
        setClass('flex-auto actions-box'),
        dynamic
        (
            set::dynamics($actions),
            set::users($users),
        )
    );
}

blockPanel(setClass('dynamic-block'), $content);

render();
