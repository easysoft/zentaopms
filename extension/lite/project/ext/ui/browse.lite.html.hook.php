<script>
window.waitDom('.switchButton', function(){this.closest('.btn-group').remove()});
window.waitDom('.create-project-btn', function(){this.attr('href', $.createLink('project', 'create', 'model=kanban')).removeAttr('data-toggle')});
window.waitDom('#table-project-browse .dtable-empty-tip .btn', function(){this.attr('href', $.createLink('project', 'create', 'model=kanban')).removeAttr('data-toggle')});
</script>
