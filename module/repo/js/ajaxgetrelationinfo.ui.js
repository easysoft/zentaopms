$('#repoRelationInfo .linked-object').on('click', function()
{
    parent.parent.loadModal($(this).data('link'), null, {size: 'lg'});
});
