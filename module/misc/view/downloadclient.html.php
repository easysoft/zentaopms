<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->downloadClient;?></h2>
  </div>
  <?php if($action == 'selectPackage'):?>
  <form method='post' target='hiddenwin'>
    <table class='w-p100'>
      <tr>
        <td>
          <?php echo html::radio('os', $lang->misc->client->osList, 'windows64', '', 'block');?>
        </td>
      </tr>
      <tr class='text-center'>
        <td>
          <?php echo html::submitButton($lang->select, '', 'btn btn-primary');?>
        </td>
      </tr>
    </table>
  </form>
  <?php endif;?>

  <?php if($action == 'getPackage'):?>
  <?php js::set('os', $os);?>
  <?php js::set('uid', $uid);?>
  <div class='main'>
    <ul>
      <li id='downloading'><?php echo $lang->misc->client->downloading;?><span>0</span>M</li>
      <li id='downloaded' class='hidden'><?php echo $lang->misc->client->downloaded;?></li>
      <li id='setConfig'  class='hidden'><?php echo $lang->misc->client->setConfig;?></li>
      <li id='hasError' class='hidden'><?php echo $lang->misc->client->downloaded;?></li>
    </ul>
  </div>
  <script>
  $(document).ready(function()
  {
      getClient();
      progress = setInterval("showProgress()", 2000);
  })
  
  function getClient()
  {
      var link = createLink('misc', 'ajaxGetClient', 'os=' + os + '&uid=' + uid);
      $.getJSON(link, function(response)
      {
          if(response.result == 'success')
          {
              clearInterval(progress);
              $('#downloading').addClass('hidden');
              $('#downloaded').removeClass('hidden');

              var link = createLink('misc', 'ajaxSetClientConfig', 'os=' + os + '&uid=' + uid);
              $.getJSON(link, function(response)
              {
                  if(response.result == 'success')
                  {
                      $('#setConfig').removeClass('hidden');
                      downloadClient();
                  }
                  else
                  {
                      $('#hasError').text(response.message);
                  }
              });
          }
          else
          {
              alert('fail');
          }
      });
  }

  function downloadClient()
  {
      var link = createLink('misc', 'downloadClient', "action=downloadPackage" + '&os=' + os + '&uid=' + uid);
      location.href = link;
  }

  function showProgress()
  {
      var link = createLink('misc', 'ajaxGetDownProgress', 'os=' + os + '&uid=' + uid);
      $.getJSON(link, function(response)
      {
          if(response.result == 'finished')
          {
          }
          else
          {
              $('#downloading span').text(response.size);
          }
      });
  }
  </script>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
