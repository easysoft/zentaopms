var blames = null;
var codeHeight = 0;

window.onMouseDown = function(obj)
{
    showBlameAndRelation(obj.target.position.lineNumber);
}

/**
 * 显示关联信息弹窗。
 * Show blame info and relations.
 *
 * @param  int    line
 * @access public
 * @return void
 */
function showBlameAndRelation(line)
{
    if(!blames) return;

    if(pageType == 'diff') line = diffContent.line.new[line -1];

    var blame = blames[line];

    if(!blame) return;

    var p_line = parseInt(line);
    while(!blame.revision)
    {
        p_line--;
        blame = blames[p_line];
    }
    if($('#log').data('line') == line) return;

    var time    = blame.time != 'unknown' ? blame.time : '';
    var user    = blame.committer != 'unknown' ? blame.committer : '';
    var version = blame.revision.toString().substring(0, 10);
    var content = blameTmpl.replace('%line', line).replace('%time', time).replace('%name', user).replace('%version', version).replace('%comment', blame.message);
    $('.history').html(content);
    $('#log').data('line', line);
    $('#log').css('display', 'flex');
    getRelation(blame.revision);
}

/**
 * 获取关联信息。
 * Get relation by commit.
 *
 * @param  string $commit
 * @access public
 * @return void
 */
window.getRelation = function(commit)
{
    $('.table-empty-tip').show();
    $('#codeContainer').css('height', codeHeight / 5 * 3);
    var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 10;
    $('#related').css('height', relatedHeight);
    setTimeout(() => {
        var tabsHeight = $('#relationTabs .nav-tabs').height();
        if(!tabsHeight) tabsHeight = 32;
        $('#relationTabs .tab-content').css('height', relatedHeight - tabsHeight - 8);
    }, 500);
    $('#relationTabs ul li').remove();
    $('#relationTabs .tab-content .tab-pane').remove();

    $.post($.createLink('repo', 'ajaxGetCommitRelation', 'repoID=' + repoID + '&commit=' + commit), function(data)
    {
        var titleList = JSON.parse(data).titleList;
        $.each(titleList, function(i, titleObj)
        {
            openTab(titleObj);
        });

        if(titleList.length > 0)
        {
            $('.table-empty-tip').hide();
            $('#related button').show();
            $('#relationTabs ul li a').first().trigger('click');
        }
        else
        {
            if(!canLinkStory && !canLinkBug && !canLinkTask) $('#related button').hide();
        }

        arrowTabs('relationTabs', 1);
    });

    globalCommit  = commit;
    var linkStory = $.createLink('repo', 'linkStory', 'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkBug   = $.createLink('repo', 'linkBug',   'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkTask  = $.createLink('repo', 'linkTask',  'repoID=' + repoID + '&commit=' + commit, '', true);
    $('#linkStory').data('link', linkStory);
    $('#linkBug').data('link', linkBug);
    $('#linkTask').data('link', linkTask);
    $('#related').show();
}

/**
 * 打开关联信息tab。
 * Open tab.
 *
 * @param  object $titleObj
 * @access public
 * @return void
 */
function openTab(titleObj)
{
    var tabTemplate = `<li class="nav-item"><a class="font-medium" href="{href}" data-toggle="tab"><span><i class="icon icon-{prefixIcon}"></i>{title}</span></a><a title="{unlinkTitle}" class="unlinks" data-link="{unlinkHref}"><i class="icon icon-unlink"></i></a></li>`;

    var eleId      = titleObj.type + '-' + titleObj.id;
    var prefixIcon = titleObj.type == 'story' ? 'lightbulb' : (titleObj.type == 'task' ? 'check-sign' : 'bug');
    var unlinkHref = canUnlinkObject ? $.createLink('repo', 'unlink',  'repoID=' + repoID + '&commit=' + globalCommit + '&objectID=' + titleObj.type + '&objectType=' + titleObj.id) : '';
    var tabHtml    = tabTemplate.replace('{title}', titleObj.title)
    .replace('{unlinkHref}', unlinkHref)
    .replace('{unlinkTitle}', unlinkTitle)
    .replace('{href}', '#related-' + eleId)
    .replace('{prefixIcon}', prefixIcon);

    $('#relationTabs>ul').append(tabHtml);

    var height = getRelationTabHeight();

    $('#relationTabs .tab-content').append("<div id='related-" + eleId + "' class='tab-pane active in'><iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetRelationInfo', 'objectID=' + titleObj.id + '&objectType=' + titleObj.type) + "' width='100%' height='" + height + "'></iframe></div>")
}

/**
 * 获取关联信息弹窗高度。
 * Get relation tab height.
 *
 * @param  object $titleObj
 * @access public
 * @return void
 */
function getRelationTabHeight()
{
    var relatedHeight = $('#related').height();
    relatedHeight     = parseInt(relatedHeight) ? parseInt(relatedHeight) : 0;

    return relatedHeight - 52;
}

/**
 * 修改比对差异方式。
 * Update diff editor inline style.
 *
 * @param  bool   display
 * @access public
 * @return void
 */
window.updateEditorInline = function(display)
{
    modifiedEditor.updateOptions({renderSideBySide: display});
}

/**
 * 获取提交信息。
 * Show commit info.
 *
 * @access public
 * @return void
 */
function showCommitInfo()
{
    var link = $.createLink('repo', 'ajaxGetCommitInfo');
    var data = {
        repoID        : repoID,
        entry         : encodePath,
        revision      : revision,
        sourceRevision: sourceRevision,
        line          : 0,
        returnType    : 'json'
    };

    $.post(link, data, function(res)
    {
        res    = JSON.parse(res);
        blames = res.blames;
    })
}

/* 初始化数据 */
$(function()
{
    setTimeout(() => {
        initPage();
    }, 200);
});

/**
 * 初始化页面。
 * Init page.
 *
 * @access public
 * @return void
 */
function initPage()
{
    codeHeight = $.cookie.get('codeContainerHeight');
    $('#codeContainer').css('height', $.cookie.get('codeContainerHeight'));

    $('.btn-left').on('click',  function() {arrowTabs('relationTabs', 1);});
    $('.btn-right').on('click', function() {arrowTabs('relationTabs', -2);});

    $('#linkStory, #linkBug, #linkTask').on('click', function()
    {
        var link = $(this).data('link');
        parent.loadLinkPage(link);
    });

    $('#relationTabs').off('click', '.unlinks').on('click', '.unlinks', function()
    {
        var link = $(this).data('link');
        $.post(link, function(data)
        {
            data = JSON.parse(data);
            if(data.result)
            {
                getRelation(data.revision);
            }
            else
            {
                alert(data.message);
            }
        })
    })

    $('#relationTabs').on('onOpen', function(event, tab)
    {
        $('#tab-nav-item-' + tab.id).attr('title', tab.title);

        var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 45;
        $('#relationTabs iframe').css('height', relatedHeight);
    });

    /* Get file commits. */
    showCommitInfo();
}
