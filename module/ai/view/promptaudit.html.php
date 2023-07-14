<?php
/**
 * The design step bar view file of AI module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianyu Chen <chenjianyu@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>

<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class="main-header">
      <p>
        <?php echo $this->lang->ai->audit->designPrompt;?>
        <strong><?php echo $object->{$prompt->module}->title ?></strong>
        <span class='label label-id'> <?php echo $object->{$prompt->module}->id;?></span>
      </p>
    </div>
    <div class="bg-gray-3" style="display: flex;">
      <div style="flex-basis: 50%; flex-grow: 1;padding: 5px 10px;  border-right: #E6EAF1 1px solid">
        <form method="post" class="main-form form-ajax" target="hiddenwin">
          <h4><?php echo $this->lang->ai->prompts->assignRole; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->role; ?></span></div>
            <div
              class='input'><?php echo html::input('role', $prompt->role, "class='form-control' placeholder='{$this->lang->ai->prompts->rolePlaceholder}'"); ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->characterization; ?></span></div>
            <div
              class='input'><?php echo html::textarea('characterization', $prompt->characterization, "class='form-control' rows='4' placeholder='{$this->lang->ai->prompts->charPlaceholder}'"); ?></div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->selectDataSource; ?></h4>
          <div class='content-row'>
            <div class='input-label text-gray'><span><?php echo $this->lang->ai->prompts->object; ?></span></div>
            <div class='input-label'
                 style="text-align: left;"><?php echo $this->lang->ai->dataSource[$prompt->module]['common']; ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label text-gray'><span><?php echo $this->lang->ai->prompts->field; ?></span></div>
            <div class='input-label' style="text-align: left; width: fit-content;">
              <?php
              $sources = explode(',', $prompt->source);
              $sources = array_filter($sources);
              end($sources);
              $lastKey = key($sources);
              foreach($sources as $key => $source)
              {
                $isLastElem = ($key === $lastKey);
                list($object, $field) = explode('.', $source);
                echo $this->lang->ai->dataSource[$prompt->module][$object][$field] . ($isLastElem ? '' : $this->lang->separater);
              }
              ?>
            </div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->setPurpose; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->ai->prompts->purpose; ?></span></div>
            <div
              class='input'><?php echo html::textarea('purpose', $prompt->purpose, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->purposeTip}' required"); ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->ai->prompts->elaboration; ?></span></div>
            <div
              class='input'><?php echo html::textarea('elaboration', $prompt->elaboration, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->elaborationTip}'"); ?></div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->setTargetForm; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->selectTargetForm; ?></span></div>
            <div class='input-label'
                 style="text-align: left;"><?php echo $this->lang->ai->targetForm[$prompt->module][explode('.', $prompt->targetForm)[1]]; ?></div>
          </div>
        </form>
      </div>
      <div style="flex-basis: 50%; flex-grow: 1; display: flex; flex-direction: column;">
        <div style="padding: 5px 10px; border-bottom: #E6EAF1 1px solid">
          <h4 style="margin-bottom: 24px;"><?php echo sprintf($this->lang->ai->models->promptFor, $this->lang->ai->models->typeList['openai-gpt35']);?></h4>
          <p class="text-gray"><?php echo $this->lang->ai->prompts->assignRole;?></p>
          <p>
            <?php
            echo $prompt->role;
            echo $prompt->characterization;
            ?>
          </p>
        </div>
        <div style="padding: 5px 10px; border-bottom: #E6EAF1 1px solid">
          <p class="text-gray"><?php echo $this->lang->ai->prompts->selectDataSource;?></p>
          <p style="word-break: break-all;"><?php echo $dataPrompt; ?></p>
        </div>
        <div style="padding: 5px 10px;">
          <p class="text-gray"><?php echo $this->lang->ai->prompts->setPurpose;?></p>
          <p><?php echo $prompt->purpose;?></p>
          <p><?php echo $prompt->elaboration;?></p>
        </div>
      </div>
    </div>
    <div style="display: flex; justify-content: center; margin-top: 10px;">
      <?php echo html::submitButton($this->lang->save, '', 'btn btn-primary disabled');?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
