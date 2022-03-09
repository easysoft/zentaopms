<script>
$(function()
{
    for(var index = 0; index <= 9; index++) $('#todoBatchAddForm').append(`<input type="hidden" name="vision[${index}]" id="vision[${index}]" value="lite">`);
});
</script>
