<script>
<?php if(trim($config->visions, ',') == 'lite'): ?>
$('#headerActions').css("right", '140px');
<?php else: ?>
$('#headerActions').css("right", '235px');
<?php endif; ?>
</script>
