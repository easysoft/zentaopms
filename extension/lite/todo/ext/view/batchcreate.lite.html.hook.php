<?php $html = '<div class="divider"></div>';?>
<script>
$(function()
{
    for(var index = 0; index <= 9; index++) $('#todoBatchAddForm').append(`<input type="hidden" name="vision[${index}]" id="vision[${index}]" value="lite">`);
    $('#mainContent .main-header .pull-right.btn-toolbar').append(<?php echo json_encode($html)?>);
});
</script>
