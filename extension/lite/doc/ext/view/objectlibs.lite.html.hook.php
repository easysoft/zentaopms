<?php if($this->app->tab == 'project'):?>
<script>
$('#pageNav .btn-group #dropMenu .table-col .list-group a[href*="showFiles"]').remove();
$('#pageActions .btn-toolbar .btn-group:first').remove();
</script>
<?php endif;?>
<?php if($this->app->tab == 'doc'):?>
<script>
$('#pageNav .btn-group #dropMenu .table-col .list-group a[href*="showFiles"]').remove();
</script>
<?php endif;?>
