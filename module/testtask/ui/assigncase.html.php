<?php
declare(strict_types=1);
/**
 * The assignTo ui file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->testtask->assignCase),
    set::entityID($run->case->id),
    set::entityText($run->case->title)
);

/* zin: Define the form in main content. */
