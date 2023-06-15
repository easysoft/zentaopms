<?php
declare(strict_types=1);
/**
 * The activate view file of gitea module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gitea
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->gitea->url),
            set::content("<a href='{$gitea->url}' target='_blank'>{$gitea->url}</a>"),
            set::useHtml(true)
        ),
    ),
    history(),
);

render('modalDialog');
