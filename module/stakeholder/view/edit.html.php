<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->stakeholder->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->stakeholder->isKey;?></th>
            <td><?php echo html::radio('key', $lang->stakeholder->keyList, $stakeholder->key);?></td>
          </tr>
          <?php if($stakeholder->type == 'outside'):?>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->name;?></th>
            <td><?php echo html::input('name', $stakeholder->name, "class='form-control'");?></td>
          </tr>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->phone;?></th>
            <td><?php echo html::input('phone', $stakeholder->phone, "class='form-control'");?></td>
          </tr>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->qq;?></th>
            <td><?php echo html::input('qq', $stakeholder->qq, "class='form-control'");?></td>
          </tr>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->weixin;?></th>
            <td><?php echo html::input('weixin', $stakeholder->weixin, "class='form-control'");?></td>
          </tr>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->email;?></th>
            <td><?php echo html::input('email', $stakeholder->email, "class='form-control'");?></td>
          </tr>
          <tr class="user-info">
            <th><?php echo $lang->stakeholder->company;?></th>
            <td><?php echo html::select('company', $companys, $stakeholder->company, "class='form-control chosen'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->stakeholder->nature;?></th>
            <td colspan='3'><?php echo html::textarea('nature', $stakeholder->nature, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->analysis;?></th>
            <td colspan='3'><?php echo html::textarea('analysis', $stakeholder->analysis, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->strategy;?></th>
            <td colspan='3'><?php echo html::textarea('strategy', $stakeholder->strategy, "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='4' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('user', $stakeholder->user);?>
<?php include '../../common/view/footer.html.php';?>
