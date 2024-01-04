window.searchTitle = function()
{
    const searchTitle = $('.actions-menu .searchBox input[name=title]').val();
    loadPage(searchLink.replace('%s', searchTitle));
}
$(document).on('keypress', '.actions-menu .searchBox #title', function(e)
{
    if(e.keyCode == 13) loadPage(searchLink.replace('%s', $(this).val()));
});

window.renderCell = function(result, {col, row})
{
    if(col.name == 'title')
    {
        let titleHtml = '';
        if(imageExtensionList.includes(row.data.extension))
        {
            titleHtml += `<div style='display: inline-block'><img width='16' src='${row.data.webPath}' data-extension='${row.data.extension}' data-id='${row.data.id}' data-width='${row.data.imageWidth}'/></div>`;
        }
        else
        {
            titleHtml += row.data.fileIcon;
        }

        titleHtml += `<div class='titleBox'><span class='ml-1'>${row.data.fileName}</span></div>`;

        result[0] = {html: titleHtml};
        return result;
    }

    if(col.name == 'objectID')
    {
        const sourceAttr = row.data.objectType != 'doc' ? " data-toggle='modal' data-size='lg'" : '';
        const objectLink = $.createLink(row.data.objectType == 'requirement' ? 'story' : row.data.objectType, 'view', `objectID=${row.data.objectID}`);
        const sourceHtml = `<span>${row.data.objectName}</span><a title='${row.data.sourceName}' href='${objectLink}' ${sourceAttr}> ${row.data.sourceName}</a>`;

        result[0] = {html: sourceHtml};
        return result;
    }

    if(col.name == 'size')
    {
        result[0] = {html: row.data.sizeText};
        return result;
    }

    return result;
}

window.downloadFile = function(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var fileTypes   = 'jpg,jpeg,gif,png,bmp';
    var windowWidth = $(window).width();

    var url = $.createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    url    += url.includes('?') ? '&' : '?';
    url    += `'${sessionString}'`;

    width = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    if(fileTypes.indexOf(extension) >= 0)
    {
        loadModal(url);
    }
    else
    {
        window.open(url, '_self');
    }

    return false;
}
