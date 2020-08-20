<tr class="riskTR">
  <th><?php echo $lang->risk->name;?></th>
  <td>
    <?php echo html::input('name', $issue->title, "class='form-control'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->source;?></th>
  <td>
    <?php echo html::select('source', $lang->risk->sourceList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->category;?></th>
  <td>
    <?php echo html::select('category', $lang->risk->categoryList, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr class="riskTR">
  <th><?php echo $lang->risk->strategy;?></th>
  <td>
    <?php echo html::select('strategy', $lang->risk->strategyList, '', "class='form-control chosen'");?>
  </td>
</tr>
