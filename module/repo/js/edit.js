$(function()
{
    scmChanged(scm);
});

function scmChanged(scm) {
    if(scm == 'Git') {
        $('.account-fields').addClass('hidden');
    } else {
        $('.account-fields').removeClass('hidden');
    }
}