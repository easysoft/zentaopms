/**
 * Toggle check all repoes.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function checkAllRepoes(event)
{
    var checked = $(event.target).prop('checked');
    $('.check-list.repo-list').find('[name^="repoes"]').prop('checked', checked);
}

/**
 * Toggle check repo.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function checkRepo(event)
{
    var allRepos     = $('.check-list.repo-list').find('[name^="repoes"]');
    var checkedRepos = $('.check-list.repo-list').find('[name^="repoes"]:checked');
    $('#checkAllRepoes').prop('checked', allRepos.length == checkedRepos.length);
}
