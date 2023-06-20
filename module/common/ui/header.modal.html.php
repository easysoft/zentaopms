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

if(empty($title)) $title  = \initPageTitle();
if(empty($entity) && !empty(${$this->moduleName})) $entity = \initPageEntity(${$this->moduleName});

to::header
(
    span
    (
        $title,
        set::class('pl-3'),
    ),
    !empty($entity) ? entityLabel
    (
        set::level(1),
        !empty($entity['title']) ? set::text($entity['title']) : null,
        !empty($entity['id']) ? set::entityID($entity['id']) : null,
        set::reverse(true),
    ) : null
);
