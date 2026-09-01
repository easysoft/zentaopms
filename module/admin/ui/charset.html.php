<?php
declare(strict_types=1);
/**
 * The charset view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

$isMysql = $config->db->driver == 'mysql';
$count   = count($charsetTables);

jsVar('refresh', $lang->refresh);
jsVar('charsetTables', $charsetTables);
jsVar('charsetChanging', $lang->admin->charsetChanging);
jsVar('charsetSuccess', $lang->admin->charsetSuccess);
jsVar('charsetFailed', $lang->admin->charsetFailed);
jsVar('charsetFinished', $lang->admin->charsetFinished);

if(!$isMysql || empty($charsetTables))
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->charset),
        set::headingClass('border-b'),
        div
        (
            setClass('flex items-center text-success'),
            icon(setClass('mr-2'), 'check-circle'),
            $lang->admin->noNeedUpdate
        )
    );
}
else
{
    panel
    (
        setClass('m-auto w-2/3'),
        set::title($lang->admin->charset),
        set::headingClass('border-b'),
        to::headingActions
        (
            span(setID('charsetProgress'), '0 / ' . $count)
        ),
        div
        (
            setClass('mb-4'),
            sprintf($lang->admin->charsetHasDiff, $count)
        ),
        div
        (
            setID('charsetBox'),
            setClass('mb-4 overflow-y-auto overflow-x-hidden'),
            setStyle(['max-height' => 'calc(100vh - 16rem)'])
        ),
        div
        (
            setID('charsetAction'),
            setClass('center'),
            a
            (
                setID('startUpdate'),
                setClass('btn primary'),
                on::click('changeCharset()'),
                $lang->admin->startUpdate
            )
        )
    );
}

render();
