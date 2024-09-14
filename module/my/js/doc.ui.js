/**
 * 对标题列进行重定义。
 * Redefine the title column.
 *
 * @param  array  result
 * @param  array  info
 * @access public
 * @return string|array
 */
window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result[0])
    {
        const doc      = info.row.data;
        const docIcon  = doc.type == 'text' ? 'wiki-file' : doc.type;
        const starIcon = doc.collector.includes(account) ? 'star' : 'star-empty';
        let html = "<img src='static/svg/" + docIcon + ".svg' class='file-icon'/>";
        result.unshift({html});
        if(doc.status == 'draft')
        {
            html = "<span class='label special-pale rounded-full draft'>" + draftLabel + '</span>';
            result.push({html});
        }
        if(canCollect)
        {
            html = "<a href='" + $.createLink('doc', 'collect', 'objectID=' + doc.id + '&objectType=doc') + "' class='btn btn-link ajax-submit star'><img src='static/svg/" + starIcon + ".svg'/></a>";
            result.push({html});
        }
        if(result[1]['props']) result[1]['props']['class'] = 'text-ellipsis';
    }
    if(info.col.name == 'objectName' && result[0])
    {
        const doc  = info.row.data;
        const icon = objectIconList[doc.objectType];
        const html = "<i class='icon " + icon + "'></i>";
        result.unshift({html});
    }
    if(info.col.name == 'actions')
    {
        if(info.col.setting.list.edit && info.row.data.type != 'text' && typeof result[0]['props'] != 'undefined' && typeof result[0]['props']['items'] != 'undefined')
        {
            result[0]['props']['items'][0]['data-toggle'] = 'modal';
            return result;
        }
    }
    return result;
}
