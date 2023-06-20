<?php
declare(strict_types=1);
/**
 * The viewCommit view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

if(empty($design->commit))
{
    div
    (
        setClass('no-data-box'),
        span
        (
            setClass('text-gray'),
            $lang->design->noCommit,
        ),
        common::hasPriv('design', 'linkCommit') ? btn
        (
            setClass('ml-4 linkCommitBtn'),
            set::icon('plus'),
            set::text($lang->design->linkCommit),
            set::type('primary'),
            set('data-url', createLink('design', 'linkCommit', "designID=$design->id")),
        ) : '',
    );
}
else
{
}
/* ====== Render page ====== */
render('modalDialog');
