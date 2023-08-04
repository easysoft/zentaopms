function toggleStepsView(event)
{
    var isTable = $(event.target).hasClass('icon-table-large');
    loadPage($.createLink('testcase', 'view', viewParams + (isTable ? 'table' : 'mindmap')), '.steps-section');
}
