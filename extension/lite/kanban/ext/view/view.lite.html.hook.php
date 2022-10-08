<script>
<?php if(trim($config->visions, ',') == 'lite'): ?>
$('#headerActions').css("right", '110px');
<?php else: ?>
$('#headerActions').css("right", '205px');
<?php endif; ?>
</script>
