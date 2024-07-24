<?php
declare(strict_types=1);
/**
 * The ajaxSetoneself view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->message->browserSetting->more),
    set::actions(array('submit')),
    formGroup
    (
        set::width('1/3'),
        setClass('content-center'),
        set::label($lang->message->browserSetting->show),
        switcher(set::name('show'), set::value(1), set::checked($config->message->browser->show)),
    ),
    formGroup
    (
        set::width('1/3'),
        setClass('content-center'),
        set::label($lang->message->browserSetting->count),
        switcher(set::name('count'), set::value(1), set::checked($config->message->browser->count)),
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->message->browserSetting->maxDays),
        inputControl
        (
            input(set::name('maxDays'), set::value($config->message->browser->maxDays)),
            set::suffixWidth('30'),
            set::suffix('天'),
        )
    )
);
