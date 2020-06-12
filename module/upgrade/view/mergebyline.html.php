<table class='table table-form'>
  <thead>
    <tr>
      <th><?php echo $lang->upgrade->line;?></th>
      <th><?php echo $lang->upgrade->product;?></th>
      <th><?php echo $lang->upgrade->project;?></th>
      <th><?php echo $lang->upgrade->program;?></th>
      <th class='w-150px'><?php echo $lang->upgrade->pgmAdmin;?></th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0;?>
    <?php foreach($productlines as $line):?>
    <?php if(!isset($lineGroups[$line->id])) continue;?>
    <tr>
      <td class='text-top'>
        <?php echo html::checkBox("lines", array($line->id => "{$line->name}"), $i == 0 ? $line->id : 0, "data-lineid='{$line->id}'");?>
      </td>
      <td class='text-top'>
        <?php $projectHtml = '';?>
        <?php foreach($lineGroups[$line->id] as $productID => $product):?>
        <?php echo html::checkBox("products[$line->id]", array($productID => "{$lang->productCommon} #{$product->id} {$product->name}"), $i == 0 ? $product->id : 0, "data-productid='{$product->id}' data-line='{$line->id}'");?>
        <?php echo html::hidden("productIdList[$line->id][$productID]", $productID);?>
        <?php if(isset($productGroups[$productID])):?>
        <?php foreach($productGroups[$productID] as $project):?>
        <?php $projectHtml .= html::checkBox("projects[$line->id][$productID]", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $i == 0 ? $project->id : 0, "data-product='{$product->id}' data-line='{$line->id}'");?>
        <?php $projectHtml .= html::hidden("projectIdList[$line->id][$productID][$project->id]", $project->id);?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </td>
      <td><?php echo $projectHtml;?></td>
      <?php if($i == 0):?>
      <td class='text-top' rowspan='<?php echo count($productlines);?>'>
        <div class='input-group'>
          <?php if($programs) echo html::select("programs", $programs, '', "class='form-control chosen'");?>
          <?php echo html::input("programName", $line->name, "class='form-control'");?>
          <?php if($programs):?>
          <span class='input-group-addon'>
            <div class="checkbox-primary">
              <input type="checkbox" name="newProgram" value="0" checked onchange="toggleProgram(this)" id="newProgram0" />
              <label for="newProgram0"><?php echo $lang->upgrade->newProgram;?></label>
            </div>
          </span>
          <?php endif;?>
        </div>
      </td>
      <td class='text-top' rowspan='<?php echo count($productlines);?>'><?php echo html::select("pgmAdmin", $users, '', "class='form-control chosen'");?></td>
      <?php endif;?>
    </tr>
    <?php $i ++;?>
    <?php endforeach;?>
  </tbody>
</table>
