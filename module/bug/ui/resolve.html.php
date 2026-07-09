<?php
declare(strict_types=1);
/**
 * The resolve view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('bugID',          $bug->id);
jsVar('productID',      $bug->product);
jsVar('page',           'resolve');
jsVar('released',       $lang->build->released);
jsVar('requiredFields', $config->bug->resolve->requiredFields);

$fields = useFields('bug.resolve');

modalHeader();
formPanel
(
    set::fields($fields)
);

hr();
history();
