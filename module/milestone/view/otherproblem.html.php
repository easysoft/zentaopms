<form target="hiddenwin" method="post" action="<?php echo $this->createLink('milestone','saveOtherProblem');?>">
  <table class="table table-bordered" id="otherproblems">
    <thead>
      <tr>
        <th colspan='5'><?php echo $lang->milestone->otherproblem;?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td rowspan='<?php echo 2 + count($otherproblems);?>' id='problemTd'><?php echo $lang->milestone->problemandsuggest;?></td>
        <td><?php echo $lang->milestone->prodescr;?></td>
        <td><?php echo $lang->milestone->needhelp;?></td>
        <td><?php echo $lang->milestone->suggest;?></td>
        <td><?php echo $lang->milestone->options;?></td>
      </tr>
      <?php if(empty($otherproblems)): ?>
      <tr>
        <td><input type="text" value="" name="contents[]" class="form-control"/></td>
        <td><input type="text" value="" name="support[]" class="form-control"/></td>
        <td><input type="text" value="" name="measures[]" class="form-control"/></td>
        <td>
          <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItems($(this))">
          <i class="icon icon-plus"></i></button>
          <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItems($(this))">
          <i class="icon icon-close"></i></button>
          <?php echo html::submitButton('', '', 'btn btn-primary');?>
        </td>
      </tr>
      <?php endif;?>
      <?php foreach($otherproblems as $value):?>
      <tr>
        <td><input type="text" value="<?php echo $value->contents;?>" name="contents[]" class="form-control"/></td>
        <td><input type="text" value="<?php echo $value->support;?>" name="support[]" class="form-control"/></td>
        <td><input type="text" value="<?php echo $value->measures;?>" name="measures[]" class="form-control"/></td>
        <td>
        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItems($(this))">
        <i class="icon icon-plus"></i></button>
        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItems($(this))">
        <i class="icon icon-close"></i></button>
        <?php echo html::submitButton('', '', 'btn btn-primary');?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
<?php echo html::hidden('programID', $programID);?>
<?php echo html::hidden('projectID', $projectID);?>
</form>
<?php js::set('save', $lang->milestone->save);?>
<script>
function addItems(obj)
{
    var items = '<tr>\
                <td><input type="text" value="" name="contents[]" class="form-control"/></td>\
                <td><input type="text" value="" name="support[]" class="form-control"/></td>\
                <td><input type="text" value="" name="measures[]" class="form-control"/></td>\
                <td>\
                <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItems($(this))">\
                <i class="icon icon-plus"></i></button>\
                <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItems($(this))">\
                <i class="icon icon-close"></i></button>\
                <button type="submit" id="submit" class="btn btn-primary">' + save +'</button>\
                </td></tr>';
    obj.parent('td').parent('tr').after(items);
    $('#problemTd').attr('rowspan', $('#problemTd').attr('rowspan') + 1)
}

function deleteItems(obj)
{
    var len = $('#otherproblems tbody tr').length;
    if(len > 2) obj.parent('td').parent('tr').remove();
}
</script>
