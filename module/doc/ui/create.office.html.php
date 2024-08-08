<?php
declare(strict_types=1);
/**
 * The create view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->doc->create);
if($this->config->edition != 'open')
{
    $setOfficeLink = common::hasPriv('custom', 'libreoffice') ? $this->createLink('custom', 'libreoffice') : '###';
    $officeNotice  = sprintf($lang->doc->notSetOffice, zget($lang->doc->typeList, $docType), $setOfficeLink);
    if(!empty($config->file->libreOfficeTurnon) and $config->file->convertType != 'collabora') $officeNotice = sprintf($lang->doc->notSetCollabora, zget($lang->doc->typeList, $docType), $setOfficeLink);
    if($config->requestType != 'PATH_INFO') $officeNotice = $lang->doc->requestTypeError;
    div
    (
        setClass('alert warning-pale bd bd-warning'),
        html($officeNotice)
    );
}
else
{
    div
    (
        setClass('alert warning-pale bd bd-warning'),
        html(sprintf($lang->doc->cannotCreateOffice, zget($lang->doc->typeList, $docType)))
    );
}
