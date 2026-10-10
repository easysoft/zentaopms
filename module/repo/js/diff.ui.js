/**
 * DiffHub 模式下保留顶部基准/对比对象切换与比较逻辑。
 * Monaco/文件树渲染已由 DiffHub 控件替换，不再需要编辑器相关 JS。
 */

window.changeDiff = function()
{
    var source = $('#oldRevision').val();
    var target = $('#newRevision').val();
    if(source && target)
    {
        $('#oldRevision').val(target);
        $('#newRevision').val(source);

        var $source = $('#source');
        var $target = $('#target');
        if($source.is('input')) $source.val(target); else $source.text(target);
        if($target.is('input')) $target.val(source); else $target.text(source);

        window.goDiff();
    }
};

$('body').off('click', '.dropmenu-tree .dropmenu-item').on('click', '.dropmenu-tree .dropmenu-item', function()
{
    var branchOrTag = $(this).find('.listitem').data('value');
    var url         = $(this).find('.listitem').data('url');
    if(url != 'javascript:;') return;

    var domID = $('#source button.dropmenu-btn').hasClass('focus') ? 'oldRevision' : 'newRevision';
    $('#' + domID).val(branchOrTag);
    $('#isBranchOrTag').val('1');

    $('.pick-container').empty();
    if(domID == 'oldRevision')
    {
        $('#source button.dropmenu-btn').removeClass('focus');
        $('#source button.dropmenu-btn .text').text(branchOrTag);
    }
    else
    {
        $('#target button.dropmenu-btn').removeClass('focus');
        $('#target button.dropmenu-btn .text').text(branchOrTag);
    }
});

window.goDiff = function()
{
    var $source = $('#source');
    var $target = $('#target');
    if($source.is('input')) $('#oldRevision').val($source.val());
    if($target.is('input')) $('#newRevision').val($target.val());

    var oldRevision   = $('#oldRevision').val();
    var newRevision   = $('#newRevision').val();
    var isBranchOrTag = $('#isBranchOrTag').val();

    if(!oldRevision || !newRevision)
    {
        zui.Modal.alert(repoLang.error.needTwoVersion);
        return false;
    }
    if(oldRevision == newRevision)
    {
        zui.Modal.alert(repoLang.error.differentVersions);
        return false;
    }

    if(isBranchOrTag == '1')
    {
        oldRevision = btoa(encodeURIComponent(oldRevision));
        newRevision = btoa(encodeURIComponent(newRevision));
    }

    var url = $.createLink('repo', 'diff', 'repoID=' + repo.id + '&objectID=' + objectID + '&entry=&oldRevision=' + oldRevision + '&newRevision=' + newRevision + '&showBug=0&encoding=&isBranchOrTag=' + isBranchOrTag);
    openPage(url);
};
