<?php
declare(strict_types=1);
/**
 * The activate view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

function setCommonProps(object $bug, string $title = ''): array
{
    if(empty($title))
    {
        global $app, $lang;
        $module = $app->getModuleName();
        $method = $app->getMethodName();
        $title  = !empty($lang->$module->$method) ? $lang->$module->$method : zget($lang, $method);
    }

    $items[] = set::shadow(false);
    $items[] = set::title($title);
    $items[] = set::headingClass('modal-heading');
    $items[] = set::class('border-b');
    $items[] = set::actions(array('submit'));
    $items[] = set::submitBtnText($title);
    $items[] = to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($bug->title),
            set::entityID($bug->id),
            set::reverse(true),
        )
    );

    return $items;
}
