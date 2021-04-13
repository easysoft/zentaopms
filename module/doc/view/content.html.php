<?php js::set('confirmDelete', $lang->doc->confirmDelete);?>
<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail no-padding">
        <div class="detail-title no-padding doc-title">
          <div class="title"><?php echo $doc->title;?></div>
          <div class="info">
            <div class="version">
              <div class='btn-group'>
              <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;">
                #<?php echo $version ? $version : $doc->version;?>
                <span class="caret"></span>
              </a>
                <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
                <?php
                for($version = $doc->version; $version > 0; $version--)
                {
                    echo "<li>" . html::a($this->createLink('doc', 'objectLibs', "type=$objectType&objectID=$object->id&libID=$libID&docID=$doc->id&version=$version"), '#' . $version, '', "data-app='{$this->app->openApp}'") . "</li>";
                }
                ?>
                </ul>
              </div>
            </div>
            <div class="user"></div>
            <div class="time"></div>
          </div>
          <div class="actions">
            <?php
            if(common::hasPriv('doc', 'edit')) echo html::a(inlink('edit', "docID=$doc->id&comment=false&objectType=$objectType&objectID=$object->id&libID=$libID"), '<i class="icon-edit"></i>', '', "title='{$lang->doc->edit}' class='btn btn-link' data-app='{$this->app->openApp}'");
            if(common::hasPriv('doc', 'delete'))
            {
                $deleteURL = $this->createLink('doc', 'delete', "docID=$doc->id&confirm=yes&from=lib");
                echo html::a("javascript:ajaxDeleteDoc(\"$deleteURL\", \"docList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->doc->delete}' class='btn btn-link'");
            }
            ?>
            <?php if(common::hasPriv('doc', 'collect')):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $lang->doc->collect;?>" class='ajaxCollect btn btn-link'><i class='icon <?php echo $star;?>'></i></a>
            <?php endif;?>
          </div>
        </div>
        <div class="detail-content article-content">
          <?php
          if($doc->type == 'url')
          {
              $url = $doc->content;
              if(!preg_match('/^https?:\/\//', $doc->content)) $url = 'http://' . $url;
              $urlIsHttps = strpos($url, 'https://') === 0;
              $serverIsHttps = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https'));
              if(($urlIsHttps and $serverIsHttps) or (!$urlIsHttps and !$serverIsHttps))
              {
                  echo "<iframe width='100%' id='urlIframe' src='$url'></iframe>";
              }
              else
              {
                  $parsedUrl = parse_url($url);
                  $urlDomain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

                  $title    = '';
                  $response = common::http($url);
                  preg_match_all('/<title>(.*)<\/title>/Ui', $response, $out);
                  if(isset($out[1][0])) $title = $out[1][0];

                  echo "<div id='urlCard'>";
                  echo "<div class='url-icon'><img src='{$urlDomain}/favicon.ico' width='45' height='45' /></div>";
                  echo "<div class='url-content'>";
                  echo "<div class='url-title'>{$title}</div>";
                  echo "<div class='url-href'>" . html::a($url, $url, '_target') . "</div>";
                  echo "</div></div>";
              }
          }
          else
          {
              echo $doc->content;
          }
          ?>
          <?php foreach($doc->files as $file):?>
          <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
          <div class='file-image'>
            <a href="<?php echo $file->webPath?>" target="_blank">
              <img onload="setImageSize(this, 0)" src="<?php echo $this->createLink('file', 'read', "fileID={$file->id}");?>" alt="<?php echo $file->title?>" title="<?php echo $file->title;?>">
            </a>
            <span class='right-icon'>
              <?php if(common::hasPriv('file', 'download')) echo html::a($this->createLink('file', 'download', 'fileID=' . $file->id) . $sessionString, "<i class='icon icon-export'></i>", '', "class='btn-icon' style='margin-right: 10px;' title=\"{$lang->doc->download}\"");?>
              <?php if(common::hasPriv('doc', 'deleteFile')) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='btn-icon' title=\"{$lang->doc->deleteFile}\" onclick='deleteFile($file->id)'");?>
            </span>
          </div>
          <?php unset($doc->files[$file->id]);?>
          <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true', 'object' => $doc));?>
    </div>
    <div class='cell'>
      <?php
      $canBeChanged = common::canBeChanged('doc', $doc);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=doc&objectID=$doc->id");
      ?>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class="side-col col-4" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-right"></i></div>
    <?php if(!empty($doc->digest)):?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->digest;?></summary>
        <div class="detail-content">
          <?php echo !empty($doc->digest) ? $doc->digest : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </details>
    </div>
    <?php endif;?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->keywords;?></summary>
        <div class="detail-content">
          <?php echo !empty($doc->keywords) ? $doc->keywords : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </details>
    </div>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->basicInfo;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <?php if($doc->productName):?>
              <tr>
                <th class='w-90px'><?php echo $lang->doc->product;?></th>
                <td><?php echo $doc->productName;?></td>
              </tr>
              <?php endif;?>
              <?php if($doc->executionName):?>
              <tr>
                <th class='w-80px'><?php echo $lang->doc->execution;?></th>
                <td><?php echo $doc->executionName;?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='w-80px'><?php echo $lang->doc->lib;?></th>
                <td><?php echo $lib->name;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->module;?></th>
                <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->addedDate;?></th>
                <td><?php echo $doc->addedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedBy;?></th>
                <td><?php echo zget($users, $doc->editedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedDate;?></th>
                <td><?php echo $doc->editedDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </details>
    </div>
  </div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
