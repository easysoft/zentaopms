<?php
declare(strict_types=1);
/**
 * The editor view file of dev module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     dev
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setClass('bg-white text-center p-10'),
    $lang->dev->noteEditor,
    common::hasPriv('editor', 'turnon') ? btn(set::url($this->createLink('editor', 'turnon', 'status=1')), setClass('btn primary ajax-submit'), $lang->dev->switchList[1]) : null
);
