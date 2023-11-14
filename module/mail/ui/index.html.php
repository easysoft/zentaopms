<?php
declare(strict_types=1);
/**
 * The index view file of mail module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->mail->selectMTA),
    set::size('sm'),
    set::bodyClass('text-center'),
    set::actions(array
    (
        common::hasPriv('mail', 'detect') ? array('url' => inLink('detect'), 'text' => $lang->mail->smtp) : null
    ))
);

render();
