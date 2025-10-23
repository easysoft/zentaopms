<?php
declare(strict_types=1);
/**
 * The edit view file of devopsspace module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     devopsspace
 * @link        https://www.zentao.net
 */
namespace zin;

$width = common::checkNotCN() ? '3/4' : '1/2';
formPanel
(
    set::id('editForm'),
    set::title($title),
    set::titleClass('text-lg gap-0'),
    set::submitBtnText($lang->save),
);
