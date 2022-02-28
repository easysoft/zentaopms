<script>
$(function()
{
    $('.side .has-active-item').addClass('open');
    <?php if($company):?>
    $('#company').addClass('active');
    <?php else:?>
    $('#department').addClass('active');
    <?php endif;?>
})
</script>
