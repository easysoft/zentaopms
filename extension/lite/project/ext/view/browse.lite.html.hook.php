<script>
$('#sidebarHeader').remove();
$('#sidebar').remove();
$('.panel-actions').remove();
$('.icon-project-manageProducts.icon-link').parent().remove();
$(function()
{
    $('.create-project-btn').attr('href', createLink('project', 'create'));
    $('.table-empty-tip .btn').attr('href', createLink('project', 'create'));
});
</script>
