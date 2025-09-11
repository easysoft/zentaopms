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
    on::init()->call('window.initAIConversations', $currentChatID, $params)
);
