<?php
declare(strict_types=1);
/**
 * The create view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::id('systemCreateForm'),
    set::title($lang->system->create),
    set::submitBtnText($lang->save),
);
