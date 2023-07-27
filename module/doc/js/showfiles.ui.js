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
            titleHtml += `<div style='display: inline-block'><img onload='setImageSize(19)' src='${row.data.webPath}' data-extension='${row.data.extension}' data-id='${row.data.id}' data-width='${row.data.imageWidth}'/></div>`;
        }
        else
        {
            titleHtml += row.data.fileIcon;
        }

        titleHtml += `<span class='ml-1'>${row.data.fileName}</span>`;

        result[0] = {html: titleHtml};
        return result;
    }

    if(col.name == 'objectID')
    {
        const sourceAttr = row.data.objectType != 'doc' ? " data-toggle='modal'" : '';
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


/**
 * Set the max with of image.
 *
 * @access public
 * @return void
 */
window.setImageSize = function(maxWidth, maxHeight)
{
    var $image = $(this);
    if($image.parent().prop('tagName').toLowerCase() == 'a') return;

    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }
    if(!maxHeight) maxHeight = $(top.window).height();

    setTimeout(function()
    {
        maxHeightStyle = $image.height() > 0 ? 'max-height:' + maxHeight + 'px' : '';
        if(!document.getElementsByClassName('xxc-embed').length && $image.width() > 0 && $image.width() > maxWidth) $image.attr('width', maxWidth);
        $image.wrap('<a href="javascript:;" style="display:inline-block;position:relative;overflow:hidden;' + maxHeightStyle + '" onclick="return downloadFile(' + $image.attr('data-id') + ",'" + $image.attr('data-extension') + "', " +  $image.attr('data-width') + ')"></a>');
        if($image.height() > 0 && $image.height() > maxHeight) $image.closest('a').append("<a href='###' class='showMoreImage' onclick='showMoreImage(this)'>" + lang.expand + " <i class='icon-angle-down'></i></a>");
    }, 50);
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
    if(fileTypes.includes(extension))
    {
        loadModal(url);
    }
    else
    {
        loadPage(url);
    }
    return false;
}
