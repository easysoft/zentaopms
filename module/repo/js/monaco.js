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
    /* encode twice for resolving chinese character. */
    var tabID = Base64.encode(Base64.encode(filepath).replace(/=/g, '-')).replace(/=/g, '-');
    return {
        id:          tabID,
        url:         createLink('repo', 'ajaxGetEditorContent', urlParams.replace('%s', Base64.encode(encodeURIComponent(filepath)))),
        type:        'iframe',
        title:       filename,
        forbidClose: isonlybody
    };
}

$(function()
{
    $('.btn-left').click(function()  {arrowTabs('fileTabs', 1);});
    $('.btn-right').click(function() {arrowTabs('fileTabs', -2);});
    $('#fileTabs').tabs({tabs: [createTab(file['basename'], entry)]});
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
        if(openedFiles.indexOf(path) == -1) openedFiles.push(path);

        $tabs.open(createTab(name, path));
        setHeight();
        arrowTabs('fileTabs', -1);
    });

    /* Remove file path for opened files. */
    $('#fileTabs').on('onClose', function(event, tab) {
        /* encode twice for resolving chinese character. */
        var filepath = decodeURIComponent(Base64.decode(Base64.decode(tab.id.replace(/-/g, '=')).replace(/-/g, '=')));
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
        var filepath = Base64.decode(tab.id.replace(/-/g, '='));
        $('#tab-nav-item-' + tab.id).attr('title', decodeURIComponent(filepath));
    });

    if(['Git', 'Gitlab', 'Gogs', 'Gitea'].indexOf(repo.SCM) != -1)
    {
        var link  = createLink('repo', 'ajaxGetBranchesAndTags', 'repoID=' + repoID + '&oldRevision=' + branchID);
        $.get(link, function(data)
        {
            var result = $.parseJSON(data);
            $('#branchList').empty();
            $('#branchList').append(result.sourceHtml);
            $('#branchList #branchesAndTags').tree({initialState: 'expand'});
        });
    }
    else
    {
        $('#sourceSwapper').hide();
    }

    /**
     * Refresh files tree.
     *
     * @param  string branchOrTag
     * @access public
     * @return void
     */
    function refreshFiles(branchOrTag)
    {
        var link  = createLink('repo', 'ajaxGetFileTree', 'repoID=' + repoID + '&branch=' + Base64.encode(branchOrTag));
        $.get(link, function(data)
        {
            $('#modules').remove();
            $('#filesTree').append(data);
            $('#modules').tree();
            $('#filesTree').removeClass('loading');
        });
    }

    $(document).on('click', '.branch-or-tag', function()
    {
        var branchOrTag = $(this).text();
        if(branchOrTag != $.cookie('repoBranch'))
        {
            $('#filesTree').addClass('loading');
            $.cookie('repoBranch', branchOrTag);

            $('#fileTabs').data('zui.tabs').closeAll();
            refreshFiles(branchOrTag);

            $('.branch-or-tag').removeClass('selected');
            $(this).addClass('selected');
            $('.repo-select').attr('title', branchOrTag);
            $('.repo-select .version-name').text(branchOrTag);
        }
    })
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
