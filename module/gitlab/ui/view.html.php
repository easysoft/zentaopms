<?php
declare(strict_types=1);
/**
 * The view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zengggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

detailHeader
(
    isAjaxRequest('modal') ? to::prefix() : '',
    to::title(
        entityLabel(
            set(array('entityID' => $gitlab->id, 'level' => 1, 'text' => $gitlab->name))
        )
    ),
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->gitlab->url),
            set::content("<a href='{$gitlab->url}' target='_blank'>{$gitlab->url}</a>"),
            set::useHtml(true)
        ),
    ),
    history(),
);

render();
