$(function()
{
    scmChanged(scm);
});

function scmChanged(scm) {
    if(scm == 'Git') {
        $('.account-fields').addClass('hidden');

        $('.tips-git').removeClass('hidden');
        $('.tips-svn').addClass('hidden');
    } else {
        $('.account-fields').removeClass('hidden');

        $('.tips-git').addClass('hidden');
        $('.tips-svn').removeClass('hidden');
    }
}