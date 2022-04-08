<script>
<?php if(trim($config->visions, ',') == 'lite'): ?>
$('#headerActions').css("right", '130px');
<?php else: ?>
$('#headerActions').css("right", '210px');
<?php endif; ?>
</script>