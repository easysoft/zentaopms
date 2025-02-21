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

$buildScopeCards = function($templates) use ($lang)
{
    $cardItems = array();
    foreach($templates as $template)
    {
        $cardDesc   = $template->templateDesc ? $template->templateDesc : $lang->docTemplate->noDesc;
        $viewLink   = createLink('doc', 'view', "docID=$template->id");
        $editLink   = createLink('doc', 'browsetemplate', "libID=$template->lib&type=all&docID=$template->id&orderBy=id_desc&recTotal=0&recPerPage=20&page=1&mode=edit");
        $deleteLink = createLink('doc', 'deleteTemplate', "templateID=$template->id");

        $actions = array();
        if(hasPriv('doc', 'editTemplate'))   $actions[] = array('icon' => 'edit', 'text' => $this->lang->docTemplate->edit, 'url' => $editLink);
        if(hasPriv('doc', 'deleteTemplate')) $actions[] = array('icon' => 'trash', 'text' => $this->lang->docTemplate->delete, 'url' => $deleteLink, 'data-confirm' => $this->lang->docTemplate->confirmDelete);

        $cardItems[] = div
        (
            hasPriv('doc', 'viewTemplate') ? on::click()->do("clickTemplateCard(event, '$viewLink')") : null,
            setClass('doc-space-card-lib px-2 w-1/5 group'),
            div
            (
                setClass('canvas border rounded py-2 px-3 col gap-1 hover:shadow-lg hover:border-primary relative cursor-pointer'),
                div
                (
                    setClass('flex gap-2 items-center py-2'),
                    icon
                    (
                        setClass('icon-file-archive text-2xl')
                    ),
                    div
                    (
                        setClass('font-bold text-clip'),
                        set::title($template->title),
                        $template->title
                    )
                ),
                div
                (
                    setClass('text-gray text-clip text-sm py-1'),
                    set::title($cardDesc),
                    $cardDesc
                ),
                div
                (
                    setClass('toolbar absolute top-1 right-1 opacity-0 group-hover:opacity-100'),
                    !empty($actions) ? dropdown
                    (
                        btn
                        (
                            setClass('size-sm dropdown'),
                            set::type('ghost'),
                            set::icon('ellipsis-v'),
                            set::caret(false),
                        ),
                        set::items($actions),
                        set::flip(true),
                        set::placement('bottom-center'),
                        set::strategy('absolute'),
                        set::hasIcons(false)
                    ) : null
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
                    'type'  => 'ghost',
                    'caret' => 'right',
                    'class' => 'text-primary',
                    'text'  => $lang->more,
                    'url'   => createLink('doc', 'browseTemplate', "libID=$scopeID&type=all&docID=0&orderBy=id_desc&recTotal=&recPerPae=20&pageID=1&mode=list")
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
                ),
                hasPriv('doc', 'createTemplate') ? btn
                (
                    setClass('btn primary-pale'),
                    set::icon('plus'),
                    $lang->doc->createTemplate,
                    set::url(createLink('doc', 'browseTemplate', "libID=$scopeID&type=all&docID=0&orderBy=&recTotal=&recPerPae=20&pageID=1&mode=create"))
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
