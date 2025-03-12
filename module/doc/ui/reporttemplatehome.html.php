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

$scopeTemplates = array(2 => array(), 3 => array());

$scopeItems = array();
unset($lang->docTemplate->scopes[1]);
unset($lang->docTemplate->scopes[4]);
foreach($lang->docTemplate->scopes as $scopeID => $scopeName)
{
    $scopeDocs = $scopeTemplates[$scopeID];
    $scopeItems[] = div
    (
        setClass('doc-space-card ring rounded surface-light'),
        div
        (
            setClass('row items-center justify-between gap-2 px-2.5 py-1 border-b'),
            div
            (
                setClass('row items-center ml-2 flex-none'),
                div
                (
                    setClass('min-w-0 flex-auto'),
                    strong($scopeName),
                    span(setClass('label ml-2 flex-none bg-white size-sm text-sm'), $lang->docTemplate->scopeLabel)
                ),
            ),
            toolbar
            (
                item(set(array
                (
                    'data-app' => $app->tab,
                    'type'     => 'ghost',
                    'caret'    => 'right',
                    'class'    => 'text-primary',
                    'text'     => $lang->more,
                    'url'      => createLink('doc', 'browseReportTemplate', "libID=$scopeID&type=all&docID=0&orderBy=id_desc&recTotal=&recPerPae=20&pageID=1&mode=list")
                )))
            ),
        ),
        div
        (
            setClass('doc-space-card-libs py-3 px-1.5'),
            div
            (
                setClass('center gap-4 py-10'),
                div
                (
                    setClass('text-gray'),
                    $lang->docTemplate->noReportTemplate
                ),
                hasPriv('doc', 'createReportTemplate') ? btn
                (
                    setClass('btn primary-pale'),
                    setData('app', $app->tab),
                    set::icon('plus'),
                    $lang->docTemplate->createReportTemplate,
                    set::url(createLink('doc', 'browseReportTemplate', "libID=$scopeID&type=all&docID=0&orderBy=&recTotal=&recPerPae=20&pageID=1&mode=create"))
                ) : null
            )
        )
    );
}

div
(
    setClass('doc-home-body flex-auto min-h-0 col gap-4 p-4 items-stretch overflow-auto scrollbar-hover'),
    $scopeItems
);
