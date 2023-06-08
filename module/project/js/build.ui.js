window.renderCell = function(result, {col, row})
{
    if(col.name == 'path')
    {
        result[0] = '';
        if(row.data.scmPath)
        {
            const colorStyle = row.data.scmPath.indexOf('http') === 0 ? "style='color:#2463c7;'" : '';
            let scmPathHtml  = '';
            scmPathHtml += "<div><i class='icon icon-file-code' " + colorStyle + "title='" +  scmPathTip + "'></i> ";
            scmPathHtml += "<span title='" + row.data.scmPath + "'>";
            scmPathHtml += colorStyle.length ? "<a target='_blank' " + colorStyle + " href='" + row.data.filePath + "'>" + row.data.scmPath + "</a>" : row.data.scmPath;
            scmPathHtml += '</span></div>';

            result[result.length] = {html: scmPathHtml};
        }

        if(row.data.filePath)
        {
            const colorStyle = row.data.filePath.indexOf('http') === 0 ? "style='color:#2463c7;'" : '';
            let filePathHtml  = '';
            filePathHtml += "<div><i class='icon icon-download' " + colorStyle + "title='" +  filePathTip + "'></i> ";
            filePathHtml += "<span title='" + row.data.filePath + "'>";
            filePathHtml += colorStyle.length ? "<a target='_blank' " + colorStyle + " href='" + row.data.filePath + "'>" + row.data.filePath + "</a>" : row.data.filePath;
            filePathHtml += '</span></div>';

            result[result.length] = {html: filePathHtml};
        }
        return result;
    }
    return result;
}

/**
 * 提示并删除版本。
 * Delete release with tips.
 *
 * @param  int    buildID
 * @access public
 * @return void
 */
window.confirmDelete = function(buildID)
{
    if(window.confirm(confirmDelete))
    {
        $.ajaxSubmit({url: $.createLink('projectbuild', 'delete', 'buildID=' + buildID)});
    }
}

/**
 * Change product.
 *
 * @access public
 * @return void
 */
window.changeProduct = function()
{
    const link = changeProductLink.replace('{productID}', $(this).val());
    loadPage(link);
}
