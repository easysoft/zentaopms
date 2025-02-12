<?php
declare(strict_types=1);
/**
 * The comment view file of action module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     action
 * @link        https://www.zentao.net
 */
namespace zin;

$actions = array();
$actions[] = 'submit';
$actions[] = isInModal() ? array('data-dismiss' => 'modal', 'text' => $lang->close) : 'cancel';

set::title($title);

form
(
    set::url('action', 'editComment', "actionID=$actionID"),
    setClass('comment-form is-edit'),
    editor
    (
        set::name('lastComment'),
        html(html_entity_decode($comment))
    ),
    $objectType != 'story' ? fileSelector(set::defaultFiles(array_values($files))) : null,
    set::actions($actions)
);
