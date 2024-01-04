<?php
declare(strict_types=1);
/**
 * The showfiles view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('imageExtensionList', $config->file->imageExtensions);
jsVar('sessionString', session_name() . '=' . session_id());
jsVar('+searchLink', createLink('doc', 'showFiles', "type={$type}&objectID={$objectID}&viewType={$viewType}&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&searchTitle=%s"));

$filesBody = null;
if(!empty($files))
{
    $linkTpl = array('linkCreator' => helper::createLink('doc', $app->rawMethod, "type={$type}&objectID={$objectID}&viewType={$viewType}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&searchTitle={$searchTitle}"));
    if($viewType == 'list')
    {
        $tableData = initTableData($files, $config->doc->showfiles->dtable->fieldList);
        $filesBody = dtable
            (
                set::userMap($users),
                set::cols($config->doc->showfiles->dtable->fieldList),
                set::data($tableData),
                set::onRenderCell(jsRaw('window.renderCell')),
                set::footPager(usePager($linkTpl))
            );
    }
    else
    {
        $cardsBox = null;
        foreach($files as $file)
        {
            $url  = helper::createLink('file', 'download', "fileID={$file->id}");
            $url .= strpos($url, '?') === false ? '?' : '&';
            $url .= session_name() . '=' . session_id();

            $downloadLink = $this->createLink('file', 'download', "fileID={$file->id}&mouse=left");
            $cardsBox[] = div
                (
                    setClass('col'),
                    div
                    (
                        setClass('lib-file'),
                        div
                        (
                            setClass('file'),
                            a
                            (
                                set::href($url),
                                set::title($file->title),
                                set::target('_blank'),
                                set('onclick', "return downloadFile({$file->id}, '{$file->extension}', {$file->imageWidth})"),
                                in_array($file->extension, $config->file->imageExtensions) ? div
                                (
                                    setClass('img-holder'),
                                    set('style', "background-image: url({$file->webPath})"),
                                    img
                                    (
                                        setClass(empty($file->imageWidth) ? 'not-exist' : ''),
                                        set('src', $file->webPath)
                                    )
                                ) : html($file->fileIcon)
                            ),
                            div
                            (
                                setClass('file-name'),
                                set::title($file->title),
                                $file->title
                            ),
                            div
                            (
                                setClass('file-name text-gray'),
                                $file->objectName,
                                a
                                (
                                    set::href(createLink(($file->objectType == 'requirement' ? 'story' : $file->objectType), 'view', "objectID={$file->objectID}")),
                                    set::title($file->sourceName),
                                    $file->sourceName,
                                    $file->objectType != 'doc' ? set(array('data-toggle' => 'modal', 'data-size' => 'lg')) : null
                                )
                            )
                        )
                    )
                );
        }

        $filesBody = panel
            (
                setClass('block-files'),
                div
                (
                    setClass('row row-grid files-grid'),
                    set('data-size', 300),
                    $cardsBox
                ),
                pager(
                    set::_className('flex justify-end items-center'),
                    set(usePager($linkTpl))
                )
            );
    }
}
else
{
    $filesBody = div
        (
            setClass('table-empty-tip flex justify-center items-center'),
            span
            (
                setClass('text-gray'),
                $lang->pager->noRecord
            )
        );
}

include 'lefttree.html.php';
featureBar
(
    div
    (
        setClass('searchBox'),
        inputControl
        (
            input
            (
                set::name('title'),
                set::value($searchTitle),
                set::placeholder($lang->doc->fileTitle)
            ),
            span
            (
                setClass('input-control-suffix'),
                btn(set(array('icon' => 'search', 'class' => 'ghost', 'onclick' => 'searchTitle()')))
            )
        )
    )
);
toolbar
(
    div
    (
        setClass('flex'),
        div
        (
            setClass('btn-group'),
            a
            (
                icon('bars'),
                setClass('btn switchBtn'),
                setClass($viewType == 'list' ? ' text-primary' : ''),
                set::href(inlink('showFiles', "type=$type&objectID=$objectID&viewType=list&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle={$searchTitle}")),
                set('data-app', $app->tab)
            ),
            a
            (
                icon('cards-view'),
                setClass('btn switchBtn'),
                setClass($viewType != 'list' ? ' text-primary' : ''),
                set::href(inlink('showFiles', "type=$type&objectID=$objectID&viewType=card&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&searchTitle=$searchTitle")),
                set('data-app', $app->tab)
            )
        ),
        common::hasPriv('doc', 'createLib') ? btn
        (
            setClass('ml-4 btn secondary'),
            set::text($lang->doc->createLib),
            set::icon('plus'),
            set::url(createLink('doc', 'createLib', "type={$type}&objectID={$objectID}")),
            set('data-toggle', 'modal')
        ) : null
    )
);
div
(
    div
    (
        setClass('mt-2'),
        $filesBody
    )
);
/* ====== Render page ====== */
render();
