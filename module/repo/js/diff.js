/**
 *  Change code encoding.
 *
 * @param  string encoding
 * @access public
 * @return void
 */
function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}

/**
 *  Html code decode.
 *
 * @param  string str
 * @access public
 * @return string
 */
function htmlspecialchars_decode(str){
    str = str.replace(/&amp;/g, '&');
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, "''");
    str = str.replace(/&#039;/g, "'");
    return str;
}

/**
 * Get diffs by file name.
 *
 * @param  string fileName
 * @access public
 * @return object
 */
function getDiffs(fileName)
{
    if(fileName.indexOf('./') === 0) fileName = fileName.substring(2);

    var result = {
        'code': {'new': '', 'old': ''},
        'line': {'new': [], 'old': []}
    };
    $.each(diffs, function(i, diff)
    {
        if(diff.fileName == fileName)
        {
            if(!diff.contents || typeof diff.contents[0].lines != 'object') return result;

            $.each(diff.contents, function(k, content)
            {
                var lines = content.lines;
                $.each(lines, function(l, code)
                {
                    if(code.type == 'all' || code.type == 'new')
                    {
                        result.code.new += htmlspecialchars_decode(code.line.substring(1)) + "\n";
                        result.line.new.push(parseInt(code.newlc));
                    }

                    if(code.type == 'all' || code.type == 'old')
                    {
                        result.code.old += htmlspecialchars_decode(code.line.substring(1)) + "\n";
                        result.line.old.push(parseInt(code.oldlc));
                    }
                })
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
    var tabID = Base64.encode(filepath).replace(/=/g, '-');
    return {
        id:          tabID,
        url:         createLink('repo', 'ajaxGetDiffEditorContent', urlParams.replace('%s', Base64.encode(encodeURIComponent(filepath)))),
        type:        'iframe',
        title:       filename,
        forbidClose: isonlybody
    };
}

$(document).ready(function()
{
    var diffAppose = false;
    $('.dropdown #inline').hide();

    if(!browser || browser == 'ie')
    {
        $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
        $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
    }
    $(".label-exchange").click(function(){ $('#exchange').submit();});

    $('.btn-left').click(function()  {arrowTabs('fileTabs', 1);});
    $('.btn-right').click(function() {arrowTabs('fileTabs', -2);});
    if(file) $('#fileTabs').tabs({tabs: [createTab(file['basename'], entry)]});
    if(isonlybody) $('#fileTabs .tab-nav-item .tab-nav-close').hide();

    /**
     * Set pane height.
     *
     * @access public
     * @return void
     */
    function setHeight()
    {
        var paneHeight = $(window).height() - 120;
        if(edition != 'open') paneHeight -= 80;
        if(isonlybody) paneHeight = 500;
        $('#fileTabs .tab-pane').css('height', paneHeight + 'px')
        $('#filesTree').css('height', paneHeight + 35)
    }
    setHeight();
    $(window).resize(setHeight);

    $(document).on('click', '.repoFileName', function()
    {
        var path  = $(this).data('path');
        var name  = $(this).text();
        var $tabs = $('#fileTabs').data('zui.tabs');

        $tabs.open(createTab(name, path));
        setHeight();
        arrowTabs('fileTabs', -2);
    });

    /* Remove file path for opened files. */
    $('#fileTabs').on('onClose', function(event, tab) {
        var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
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
        var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
        $('#tab-nav-item-' + tab.id).attr('title', filepath);
        document.getElementById('tab-iframe-' + tab.id).contentWindow.updateEditorInline(diffAppose);

        if(openedFiles.indexOf(filepath) == -1) openedFiles.push(filepath);
    });

    $('#fileTabs').on('onOpen', function(event, tab) {
        var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
        var index    = openedFiles.indexOf(filepath);
        if(index > -1) document.getElementById('tab-iframe-' + tab.id).contentWindow.updateEditorInline(diffAppose);
    });

    $('.inline-appose').on('click', function()
    {
        $('.inline-appose').hide();
        diffAppose = !diffAppose;
        if(diffAppose)
        {
            $('.dropdown #inline').show();
        }
        else
        {
            $('.dropdown #appose').show();
        }
        var type   = $(this).attr('id');
        var tabID  = $('.tab-nav-item.active').data('id');
        document.getElementById('tab-iframe-' + tabID).contentWindow.updateEditorInline(diffAppose);
        return;
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
