<?php
declare(strict_types=1);
/**
 * The browse template home file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Song chenxuan<songchenxuan@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$scopeItems = array();
foreach($lang->docTemplate->scopes as $scopeID => $scopeName)
{
    $scopeDocs = array();
    $scopeItems[] = div
    (
        setClass('doc-space-card ring rounded surface-light'),
    );
}

div
(
    setClass('doc-home-body flex-auto min-h-0 col gap-4 p-4 items-stretch overflow-auto scrollbar-hover'),
    $scopeItems
);
