<?php
declare(strict_types=1);
/**
 * The selectcustomandmine view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song<songchenxuan@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('pageFrom', $from);

to::header
(
    entityLabel
    (
        set::level(1),
        set::text($lang->doc->create),
    ),
);

if(in_array($objectType, array('mine', 'custom'))) include './selectmineandcustom.html.php';
if($objectType == 'product') include './selectproduct.html.php';
if($objectType == 'project') include './selectproject.html.php';
if($objectType == 'api')     include './selectapi.html.php';

/* ====== Render page ====== */
render();
