function search()
{
    loadPage({method:'post', data: {keyword: $('#keyword').val()}, target: '#table-sonarqube-browseproject>*'});
}
