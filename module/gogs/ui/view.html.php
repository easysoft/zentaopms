<?php
declare(strict_types=1);
/**
 * The detail view file of gogs module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gogs
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $gogs->id, 'level' => 1, 'text' => $gogs->name))
        )
    ),
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->gogs->url),
            set::content("<a href='{$gogs->url}' target='_blank'>{$gogs->url}</a>"),
            set::useHtml(true)
        ),
    ),
    history(),
);

render();
