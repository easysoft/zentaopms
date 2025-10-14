<?php
declare(strict_types=1);
/**
 * The conversation view file of aiapp module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@easycorp.ltd>
 * @package     aiapp
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setClass('ai-conversations'),
    div
    (
        setID('noZaiConfigTip'),
        setClass('center hidden'),
        setStyle('height', 'calc(100vh - 80px)'),
        div
        (
            setClass('row items-center gap-2'),
            icon('lightbulb text-warning'),
            div(html(str_replace('{zaiConfigUrl}', createLink('zai', 'setting'), $lang->aiapp->langData->zaiConfigNotValid)))
        )
    ),
    on::init()->call('window.initAIConversations', $currentChatID, $params)
);
