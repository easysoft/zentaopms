function searchUser()
{
    loadPage({method:'post', data: {keyword: $('#keyword').val()}, target: '#table-gitlab-browseuser>*'});
}
