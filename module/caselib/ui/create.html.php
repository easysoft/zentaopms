<?php
declare(strict_types=1);
/**
 * The createlib view of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     caselib
 * @version     $Id: create.html.php 4728 2023-05-09 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */

namespace zin;
$nameGroup = formGroup
(
    set::name("name"),
    set::label($lang->caselib->name),
    set::required(true),
    set::control("text")
);
$descGroup = formGroup
(
    set::name("desc"),
    set::label($lang->caselib->desc),
    set::control("editor")
);

$formItems = array();
$formItems['name'] = $nameGroup;
$formItems['desc'] = $descGroup;

/* TODO:printExtendFields */
formPanel($formItems);

render();
