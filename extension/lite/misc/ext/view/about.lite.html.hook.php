<script>
$('#zentaoLinks > .row > .col-sm-2').removeClass('col-sm-2').addClass('col-sm-4');
$('#mainContent tbody > tr:first-child > td.text-center > h4').text('<?php printf($lang->misc->zentao->version, $config->liteVersion);?>');
</script>
