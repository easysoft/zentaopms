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

    if(col.name == 'name' && row.data.execution == 0)
    {
        result[result.length] = {html: "<span class='icon icon-code-fork text-gray' title='" + integratedTip + "'></span>"};
        return result;
    }

    if(col.name == 'execution')
    {
        if(row.data.execution == 0)
        {
            result[0] = '';
            let executionIdList = [];
            for(key in row.data.builds)
            {
                const build = row.data.builds[key];
                if(executionIdList.indexOf(build.execution) !== -1) continue;

                result[result.length] = {html: "<span title='" + build.executionName + "'>" + build.executionName + "</span>"};
                executionIdList.push(build.execution);
            }
        }
        else
        {
            let executionHtml = "<span title='" + row.data.executionName + "'>" + row.data.executionName + '</span>';
            if(row.data.executionDeleted == 1) executionHtml += " <span class='label label-danger'>" + deletedTip + '</span>';
            result[0] = {html: executionHtml};
        }
        return result;
    }

    return result;
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
