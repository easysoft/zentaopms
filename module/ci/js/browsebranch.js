$(function()
{
    $('#' + module + 'Tab').addClass('btn-active-text');
})

function watch(ths){
    var elem = $(ths);
    var newVal = elem.prop('checked');

    var repoID = elem.attr('data-repo');
    var branch = elem.attr('data-branch');

    var link = createLink('ci', 'watchBranch', "repoID=" + repoID + "&branch=" + branch + "&status=" + newVal);
    console.log(link);
    $.get(link, function(data)
    {
        elem.prop(newVal);
    });
}