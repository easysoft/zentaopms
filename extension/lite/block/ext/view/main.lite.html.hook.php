<script>
$('.block-project table > thead > tr > th:last-child').remove();
$('.block-project table > tbody > tr > td:last-child').remove();
$('.block-scrumtest').remove();
</script>
<?php if($code == 'list' && $module == 'todo'):?>
<script>
$('form[action*="todo"] button.btn-primary').parent().append('<input type="hidden" name="vision" id="vision" value="lite">');
</script>
<?php endif;?>
