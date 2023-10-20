<?php
declare(strict_types=1);
/**
 * The showCreateTable view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <xx@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('dbFinish', $lang->install->dbFinish);
div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        panel
        (
            setClass('py-2'),
            set::title($lang->install->dbProgress),
            form
            (
                h::pre(setID('progress'), setClass('progress')),
                set::actions(array(array('disabled' => true, 'text' => $lang->install->next, 'class' => 'primary next'))),
            ),
        ),
    ),
);

render('pagebase');
