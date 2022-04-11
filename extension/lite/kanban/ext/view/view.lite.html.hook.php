<script>
<?php if(trim($config->visions, ',') == 'lite'): ?>
$('#headerActions').css("right", '110px');
<?php else: ?>
$('#headerActions').css("right", '190px');
<?php endif; ?>
</script>