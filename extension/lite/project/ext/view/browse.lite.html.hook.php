<script>
$('#sidebarHeader').remove();
$('#sidebar').remove();
$('.panel-actions').remove();
$('.project-name').removeClass('has-prefix');
$('.project-type-label').remove();
$('.icon-project-manageProducts.icon-link').parent().remove();
$('.create-project-btn').attr('href', createLink('project', 'create', 'model=kanban')).removeAttr('data-toggle');
$('.table-empty-tip .btn').attr('href', createLink('project', 'create', 'model=kanban')).removeAttr('data-toggle');
</script>
