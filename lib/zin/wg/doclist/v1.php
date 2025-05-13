<?php
declare(strict_types=1);
namespace zin;

class docList extends wg
{
    protected static array $defineProps = array(
        'data' => '?object',           // 对象数据。
        'mode' => '?string="edit"'     // view还是edit模式。
    );

    protected function build()
    {
        global $app, $lang;

        $app->loadLang('task');
        $data    = $this->prop('data');
        $mode    = $this->prop('mode');
        $oldDocs = $app->control->dao->select('id,title,version')->from(TABLE_DOC)->where('id')->in($data->docs)->fetchAll('id');
        $docList = $app->control->loadModel('doc')->getMySpaceDocs('all', 'bykeyword', '', 'id_desc', null, '', $data->docs);

        $docs = array();
        foreach($docList as $doc) $docs[] = array('text' => $doc->title, 'value' => $doc->id);

        if($mode == 'edit')
        {
            $oldDocVersions = $app->control->dao->select('doc,version')->from(TABLE_DOCCONTENT)->where('doc')->in($data->docs)->fetchGroup('doc', 'version');
            $oldDocVersions = \json_decode(json_encode($oldDocVersions), true);
            foreach($oldDocVersions as $docID => $versions)
            {
                foreach($versions as $versionID => $version) $oldDocVersions[$docID][$versionID] = "#{$version['version']}";
            }
        }

        if(is_string($data->docVersions)) $data->docVersions = \json_decode($data->docVersions, true);

        $docBox = array();
        if($data->docs)
        {
            foreach(explode(',', $data->docs) as $docID)
            {
                $link = $mode == 'view' && common::hasPriv('doc', 'view') ? a(set::href(helper::createLink('doc', 'view', "docID={$docID}&version={$data->docVersions[$docID]}")), $oldDocs[$docID]->title) : $oldDocs[$docID]->title;
                $docBox[] = div
                (
                    setClass('docItem flex items-center py-1'),
                    span(setClass('mr-4 p-1'), icon(setClass('mr-2'), 'file-text'), $link),
                    div(setClass('w-24'), $mode == 'edit' ? picker(set::required(true), set::name("docVersions[$docID]"), set::items($oldDocVersions[$docID]), set::value($data->docVersions[$docID])) : "#{$data->docVersions[$docID]}"),
                    $mode == 'edit' && $oldDocs[$docID]->version != $data->docVersions[$docID] ? label(setClass('ml-2 warning'), $lang->task->docSyncTips) : null,
                    $mode == 'edit' ? input(setClass('hidden'), set::name("oldDocs[$docID]"), set::value($docID)) : null,
                    $mode == 'edit' ? btn(setClass('ghost ml-2'), icon('trash'), setData(array('on' => 'click', 'call' => 'function(){$(event.target).closest(".docItem").empty();}', 'params' => 'event'))) : null
                );
            }
        }


        return div
        (
            setClass('form-group-wrapper picker-box'),
            $mode == 'edit' ? picker
            (
                set::name('docs'),
                set::items($docs),
                set::multiple(true),
                set::maxItemsCount(50),
                set::menu(array('checkbox' => true)),
                set::toolbar(true)
            ) : null,
            div(setClass('mt-2'), $docBox)
        );
    }
}
