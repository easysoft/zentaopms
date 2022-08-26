$(function()
{
    var distance = 0;

    /**
     * Arrow tab line.
     *
     * @param  int    $num
     * @access public
     * @return void
     */
    function arrow(num){
        var tabItemWidth = $('#fileTabs > .tabs-navbar > .nav-tabs')[0].clientWidth;
        var tabsWidth    = $('#fileTabs')[0].clientWidth;
        if(tabItemWidth < tabsWidth) return;

        distance += tabsWidth * num * 0.2;
        if(distance > 0) distance = 0;
        if((tabItemWidth + distance) < tabsWidth * 0.7) return;

        $('#fileTabs > .tabs-navbar > .tabs-nav')[0].style.transform = 'translateX('+ distance +'px)';
    }
    $('.btn-left').click(function()  {arrow(1);});
    $('.btn-right').click(function() {arrow(-1);});

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
        $('[data-path="' + filepath + '"]').closest('li').addClass('selected');
        var tabID = Base64.encode(filepath).replaceAll('=', '-');
        return {
            title: filename,
            id:    tabID,
            type:  'iframe',
            url:   createLink('repo', 'ajaxGetEditorContent', urlParams.replace('%s', Base64.encode(encodeURIComponent(filepath))))
        };
    }
    $('#fileTabs').tabs({tabs: [createTab(file['basename'], entry)]});

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
        var path  = $(this).data('path');
        var name  = $(this).text();
        var $tabs = $('#fileTabs').data('zui.tabs');
        if(openedFiles.indexOf(path) == -1) openedFiles.push(path);

        $tabs.open(createTab(name, path));
        setHeight();
    });

    /* Remove file path for opened files. */
    $('#fileTabs').on('onClose', function(event, tab) {
        var filepath = Base64.decode(tab.id.replaceAll('-', '='));
        var index    = openedFiles.indexOf(filepath);
        if(index > -1)
        {
            openedFiles.splice(index, 1)
            $('[data-path="' + filepath + '"]').closest('li').removeClass('selected');
        }
    });

    /* Append file path into the title. */
    $('#fileTabs').on('onLoad', function(event, tab) {
        var filepath = Base64.decode(tab.id.replaceAll('-', '='));
        $('#tab-nav-item-' + tab.id).attr('title', filepath);
    });

    var link  = createLink('repo', 'ajaxGetBranchesAndTags', 'repoID=' + repoID + '&oldRevision=' + branchID);
    $.get(link, function(data)
    {
        var result = $.parseJSON(data);
        $('#branchList').empty();
        $('#branchList').append(result.sourceHtml);
        $('#branchList #branchesAndTags').tree({initialState: 'expand'});
    });

    /**
     * Refresh files tree.
     *
     * @param  string branchOrTag
     * @access public
     * @return void
     */
    function refreshFiles(branchOrTag)
    {
        var link  = createLink('repo', 'ajaxGetFileTree', 'repoID=' + repoID + '&branch=' + branchOrTag);
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
