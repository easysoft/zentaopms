<script>
$('#sidebarHeader').remove();
$('#sidebar').remove();
$('.panel-actions').remove();
$('.icon-project-manageProducts.icon-link').parent().remove();
$('.create-project-btn').attr('href', createLink('project', 'create')).removeAttr('data-toggle');
$('.table-empty-tip .btn').attr('href', createLink('project', 'create')).removeAttr('data-toggle');
</script>
