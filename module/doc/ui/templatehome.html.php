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

$buildScopeCards = function($templates)
{
    $cardItems = array();
    foreach($templates as $template)
    {
        $cardItems[] = div
        (
            setClass('doc-space-card-lib px-2 w-1/5 group'),
            div
            (
                setClass('canvas border rounded py-2 px-3 col gap-1 hover:shadow-lg hover:border-primary relative cursor-pointer'),
                icon
                (
                    setClass('icon-doclib text-2xl')
                ),
                div
                (
                    setClass('font-bold text-clip'),
                    set::title($template->title),
                    $template->title
                )
            )
        );
    }

    return $cardItems;
};

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
                $buildScopeCards($scopeDocs)
            ) : div
            (
                setClass('center gap-4 py-10'),
                div
                (
                    setClass('text-gray'),
                    $lang->docTemplate->noTemplate
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
