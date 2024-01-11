<?php
declare(strict_types=1);
/**
 * The step6 view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$joinZentao = ($installFileDeleted ? $lang->install->successLabel : $lang->install->successNoticeLabel) . $lang->install->joinZentao;

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        panel
        (
            setClass('p-2'),
            set::title($lang->install->success),
            cell
            (
                setClass('flex mb-4'),
                icon
                (
                    setClass('text-success mx-4'),
                    set::size('3x'),
                    'check-circle'
                ),
                cell(html(nl2br(sprintf($joinZentao, $config->version, $this->createLink('admin', 'register'), $this->createLink('admin', 'bind'), inlink('step6')))))
            ),
            cell
            (
                setClass('flex justify-center'),
                btn
                (
                    setClass('px-4'),
                    set::url($lang->install->officeDomain),
                    set::target('_blank'),
                    set::type('success'),
                    $lang->install->register
                ),
                cell
                (
                    setClass('flex items-center text-gray px-2'),
                    $lang->install->or
                ),
                btn
                (
                    setClass('px-4'),
                    set::target('_self'),
                    set::url('index.php'),
                    set::type('primary'),
                    $lang->install->login
                )
            )
        )
    )
);

render('pagebase');
