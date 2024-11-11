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

$scopeTemplates = $this->doc->getScopeTemplates();

$scopeItems = array();
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
                    'trailingIcon'  => 'angle-right',
                    'text'  => $lang->docTemplate->more,
                    'class' => 'ghost size-md text-primary',
                    'url'   => createLink('doc', 'browseTemplate', "libID=$scopeID")
                )))
            ),
        ),
        div
        (
            setClass('doc-space-card-libs py-3 px-1.5'),
            !empty($scopeDocs) ? div
            (
                setClass('row'),
                $scopeDocs
            ) : div
            (
                setClass('center gap-4 py-10'),
                div
                (
                    setClass('text-gray'),
                    $lang->docTemplate->emptyTip
                )
            )
        )
    );
}

div
(
    setClass('doc-home-body flex-auto min-h-0 col gap-4 p-4 items-stretch overflow-auto scrollbar-hover'),
    $scopeItems
);
