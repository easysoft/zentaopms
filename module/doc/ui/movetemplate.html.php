<?php
declare(strict_types=1);
/**
 * The moveDoc view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->docTemplate->moveDocTemplate), set::entityText($doc->title), set::entityID($docID));

unset($modules[0]);
formPanel
(
    on::change('[name=lib]')->call('loadLibModules', jsRaw("event, 'docTemplate'")),
    formGroup
    (
        set::label($lang->docTemplate->scope),
        set::required(true),
        picker(set::name('lib'), set::items($lang->docTemplate->scopes), set::value($doc->lib), set::required(true))
    ),
    formGroup
    (
        set::label($lang->docTemplate->module),
        set::required(true),
        picker(set::name('module'), set::items($modules), set::value($doc->module), set::required(true))
    ),
);
