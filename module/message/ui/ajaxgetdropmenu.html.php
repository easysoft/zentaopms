<?php
declare(strict_types=1);
/**
 * The ajaxGetDropmenu view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

jsVar('unreadLangTempate', $lang->message->unread);
jsVar('noDataLang', $lang->noData);

tabs
(
    tabPane
    (
        set::key('unread-messages'),
        set::title(sprintf($lang->message->unread, $unreadCount)),
        set::active(true),
    ),
    tabPane
    (
        set::key('all-messages'),
        set::title($lang->message->all),
    )
);
