function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}

function getDiffs(fileName)
{
    var result = {'new': '', 'old': ''};
    $.each(diffs, function(i, diff)
    {
        if(diff.fileName == fileName)
        {
            if(typeof diff.contents[0].lines != 'object') return result;

            var lines = diff.contents[0].lines;
            $.each(lines, function(l, code)
            {
                if(code.type == 'new')
                {
                    result.new += code.line.substring(2) + "\n";
                }
                else
                {
                    result.old += code.line.substring(2) + "\n";
                }
            })
            return result;
        }
    });

    return result;
}

/**
 * Create file tab.
 *
 * @param  string filename
 * @param  string filepath
 * @access public
 * @return object
 */
function createTab(filename, filepath)
{
    $('[data-path="' + decodeURIComponent(filepath) + '"]').closest('li').addClass('selected');
    var tabID = Base64.encode(filepath).replaceAll('=', '-');
    return {
        title: filename,
        id:    tabID,
        type:  'iframe',
        url:   createLink('repo', 'ajaxGetDiffEditorContent', urlParams.replace('%s', Base64.encode(encodeURIComponent(filepath))))
    };
}

$(document).ready(function()
{
    $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
    $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
    $(".label-exchange").click(function(){ $('#exchange').submit();});

    $('.btn-left').click(function()  {arrowTabs('fileTabs', 1);});
    $('.btn-right').click(function() {arrowTabs('fileTabs', -2);});
    if(file) $('#fileTabs').tabs({tabs: [createTab(file['basename'], entry)]});

    /**
     * Set pane height.
     *
     * @access public
     * @return void
     */
    function setHeight()
    {
        var paneHeight = $(window).height() - 120;
        $('#fileTabs .tab-pane').css('height', paneHeight + 'px')
        $('#filesTree').css('height', paneHeight + 45)
    }
    setHeight();

    $(document).on('click', '.repoFileName', function()
    {
        var path  = encodeURIComponent($(this).data('path'));
        var name  = $(this).text();
        var $tabs = $('#fileTabs').data('zui.tabs');
        if(openedFiles.indexOf(path) == -1) openedFiles.push(path);

        $tabs.open(createTab(name, path));
        setHeight();
        arrowTabs('fileTabs', -2);
    });

    /* Remove file path for opened files. */
    $('#fileTabs').on('onClose', function(event, tab) {
        var filepath = decodeURIComponent(Base64.decode(tab.id.replaceAll('-', '=')));
        var index    = openedFiles.indexOf(filepath);
        if(index > -1)
        {
            openedFiles.splice(index, 1)
            $('[data-path="' + filepath + '"]').closest('li').removeClass('selected');
        }

        if(index == openedFiles.length) arrowTabs('fileTabs', -2);
    });

    /* Append file path into the title. */
    $('#fileTabs').on('onLoad', function(event, tab) {
        var filepath = Base64.decode(tab.id.replaceAll('-', '='));
        $('#tab-nav-item-' + tab.id).attr('title', decodeURIComponent(filepath));
    });
});

/**
 * Load link object page.
 *
 * @param  string $link
 * @access public
 * @return void
 */
function loadLinkPage(link)
{
    $('#linkObject').attr('href', link);
    $('#linkObject').click()
}
