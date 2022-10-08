<?php if(common::checkNotCN()):?>
<script>
$(function()
{
    $('#searchForm .form-group #account').closest('.input-group').find('.input-group-addon').css('padding', '5px 32px');
    $('#searchForm .form-group #date').closest('.input-group').find('.input-group-addon').css('padding', '5px 32px');
})
</script>
<?php endif;?>
