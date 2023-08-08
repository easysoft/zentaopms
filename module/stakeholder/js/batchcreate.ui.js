window.setDeptUsers = function(e)
{
    dept = $(e.target).zui('picker').$.value; //Get dept ID.
    link = $.createLink('stakeholder', 'batchCreate', 'projectID=' + projectID + '&dept=' + dept);
    loadPage(link);
}
