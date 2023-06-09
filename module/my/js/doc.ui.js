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
        let html = "<a href='" + $.createLink('doc', 'view', 'docID=' + doc.id) + "' class='doc-title'><img src='static/svg/" + docIcon + ".svg' class='file-icon'/>" + doc.title + "</a>";
        html += "<a href='" + $.createLink('doc', 'collect', 'objectID=' + doc.id + '&objectType=doc') + "' class='btn btn-link ajax-submit star'><img src='static/svg/" + starIcon + ".svg'/></a>";
        result[0] = {html};
    }
    return result;
}
