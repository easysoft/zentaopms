<script>
$(function()
{
    for(var index = 1; index <= 10; index++) $('#batchCreateForm').append(`<input type="hidden" name="vision[${index}]" id="vision[${index}]" value="lite">`);
});
</script>
