<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-800px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->pasteText?></h4>
      </div>
      <div class="modal-body">
        <?php echo html::textarea('pasteText', '', "class='form-control mgb-10' rows='10' placeholder='$lang->pasteTextInfo'")?>
        <?php echo html::submitButton()?>
      </div>
    </div>
  </div>
</div>
<script>
$("button[data-toggle='myModal']").click(function(){$('#myModal').modal('show')})
$("#myModal button[type='submit']").click(function()
{
    var pasteText = $('#myModal #pasteText').val();

    $('#myModal').modal('hide')
    $('#myModal #pasteText').val('');

    var dataList = pasteText.split("\n");

    if(typeof(mainField) == 'undefined') mainField = 'title';
    var index = 0;
    for(i in dataList)
    {
        var data = dataList[i].replace(/(^\s*)|(\s*$)/g, "");;

        if(data.length == 0) continue;
        while(true)
        {
            var title = $('form tbody tr').eq(index).find("input[id*='" + mainField + "']");
            if($(title).size() == 0)
            {
                if(index == 0) break;
                cloneTr = $('#trTemp tbody').html();
                cloneTr = cloneTr.replace(/%s/g, index);
                $('form tbody tr').eq(index - 1).after(cloneTr);
                $('form tbody tr').eq(index).find('td:first').html(index + 1);
                $('form tbody tr').eq(index - 1).find('td').each(function()
            {
                if($(this).find('div.chosen-container').size() != 0)
                {
                    $('form tbody tr').eq(index).find("td").eq($(this).index()).find('select').chosen(defaultChosenOptions);
                }
            });
                title = $('form tbody tr').eq(index).find("input[id*='" + mainField + "']");
            }

            index++;

            if($(title).val() != '') continue;
            if($(title).val() == '')$(title).val(data);
            break;
        }
    }
});
</script>
