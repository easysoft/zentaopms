function reload(toProject, fromProject)
{
    link = createLink('execution','importtask','toProject='+toProject + '&fromProject='+fromProject);
    location.href = link;
}
