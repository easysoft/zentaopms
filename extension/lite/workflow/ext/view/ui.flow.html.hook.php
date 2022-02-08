<script>
if(typeof(fieldDefaultOptions) == 'undefined') fieldDefaultOptions = '';
$(function()
{
    $('a.btn[href="#confirmReleaseModal"]').click(function()
    {
        saveFields(function(result){});
    })
})
</script>
