<?php
declare(strict_types=1);
/**
 * The moveLib view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::titleClass('text-root font-bold'), set::title($lang->doc->moveLibAction));

jsVar('targetSpace', $targetSpace);
jsVar('libID', $lib->id);
formPanel
(
    on::change('[name=space]', 'changeSpace'),
    formGroup
    (
        set::width('5/6'),
        set::name("space"),
        set::label($lang->doc->space),
        set::value($targetSpace),
        set::control("picker"),
        set::items($spaces),
        set::required(true)
    ),
);
