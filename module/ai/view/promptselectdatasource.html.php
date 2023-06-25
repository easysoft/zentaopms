<?php
/**
 * The ai prompt data source selection view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<?php js::set('dataSource', $dataSource);?>
<?php js::set('dataSourceLang', $lang->ai->dataSource);?>

<style>
  .center-wrapper {display: flex; justify-content: center; height: 100%;}
  .center-content {width: 100%; height: 100%; display: flex; flex-direction: column;}
  #data-selector {display: flex; flex-direction: row; height: 100%; margin-bottom: 16px;}
  #data-sources {flex-grow: 2; display: flex; flex-direction: row; margin-right: 16px;}
  #data-selected {flex-grow: 1; display: flex; flex-direction: column;}
  .heading > * {display: inline-block; padding: 4px 0;}
  #data-category-select {flex-grow: 1; background-color: #fafafa; border: 1px solid #eee; border-right: none;}
  #data-category-select > ul {padding: 16px; margin: 0; list-style: none;}
  #data-category-select > ul > li > a.active {font-weight: bold;}
  #data-categories {flex-grow: 1; display: flex; flex-direction: column;}
  #data-properties {flex-grow: 3; display: flex; flex-direction: column;}
  #data-properties > .heading {padding: 4px 20px;}
  #data-properties > .heading > h4 {margin: 6px 0;}
  #data-property-select {flex-grow: 1; border: 1px solid #eee; border-left: none;}
  #data-property-selector {height: 100%; width: 100%;}
  #data-selected-items {flex-grow: 1; border: 1px solid #eee;}
  .obj-view {display: flex; flex-direction: column; padding: 20px;}
  .obj-view:not(:first-of-type) {border-top: 1px solid #eee;}
  .obj-view-body {display: grid; padding: 4px 20px; grid-template-columns: repeat(4, 1fr);}
  .obj-view-header.checkbox-inline, .obj-view-item.checkbox-inline {margin: 4px; cursor: unset;}
  .obj-view-item.checkbox-inline+.obj-view-item.checkbox-inline {margin-left: 4px; margin-top: 4px;}
  .checkbox-inline > label, .checkbox-inline > input {cursor: pointer;}
</style>

<script>
/* Store and sync value of datasource input, change by checkbox changes. */
class DataSourceStore
{
  value = {};
  listeners = [];

  /* Get store value. */
  get value()
  {
    return this.value;
  }

  /* Set store value. */
  set value(value)
  {
    this.value = value;
  }

  /* Reset store value. */
  reset()
  {
    this.value = {};

    /* Notify listeners. */
    this.listeners.forEach(l => (l(this.value)));
  }

  /* Sync value from form checkboxes. */
  sync()
  {
    const dataSources = document.querySelectorAll('#data-property-selector');
    const value = {};
    dataSources.forEach(dataSource =>
    {
      const group = dataSource.getAttribute('object-group');
      const items = dataSource.querySelectorAll('input[type="checkbox"]');
      items.forEach(item =>
      {
        const itemPropName = item.getAttribute('prop');
        if(item.checked && itemPropName.includes('.')) value[itemPropName] = true;
      });
    });
    this.value = value;
    document.querySelector('#datasource').value = JSON.stringify(this.value);

    /* Notify listeners. */
    this.listeners.forEach(l => (l(this.value)));
  }

  /* Subscribe for store value changes. */
  subscribe(listener)
  {
    this.listeners.push(listener);
  }

  /* Unsubscribe for store value changes. */
  unsubscribe(listener)
  {
    this.listeners = this.listeners.filter(l => l !== listener);
  }
}
window.dataSourceStore = new DataSourceStore(); // Create store instance.

/* Data property selector component, declared as custom element. */
class DataPropertySelector extends HTMLDivElement
{
  /* Render with given options. */
  render(options)
  {
    this.innerHTML = '';

    /* Get options. */
    const {group} = options;

    /* Get data source. */
    const dataSource     = window.dataSource[group];
    const dataSourceLang = window.dataSourceLang[group];

    /* Render data source. */
    for(const [source, props] of Object.entries(dataSource))
    {
      const objView = document.createElement('div');
      objView.classList.add('obj-view');

      const headerItem = new DataPropertyCheckbox();
      headerItem.classList.add('obj-view-header');
      headerItem.setAttribute('source', source);
      headerItem.setAttribute('prop', group);
      objView.appendChild(headerItem);

      const objViewBody = document.createElement('div');
      objViewBody.classList.add('obj-view-body');
      props.forEach(prop =>
      {
        const item = new DataPropertyCheckbox();
        item.classList.add('obj-view-item');
        item.setAttribute('source', source);
        item.setAttribute('group', group);
        item.setAttribute('prop', prop);
        objViewBody.appendChild(item);
      });
      objView.appendChild(objViewBody);

      this.appendChild(objView);
    }
  }

  /* Handle attr change, rerender. */
  attributeChangedCallback(name, oldValue, newValue)
  {
    if(name === 'object-group' && oldValue !== newValue) this.render({group: newValue});
  }

  /* Define observed attr. */
  static get observedAttributes()
  {
    return ['object-group'];
  }
}
customElements.define('data-property-selector', DataPropertySelector, {extends: 'div'});

/*
 * Checkbox with label for data selection, declared as custom element.
 *
 * @prop {string} group  - Data source group name (selected from left sidebar).
 * @prop {string} source - Data source category (sub-group).
 * @prop {string} prop   - Data source property name.
 */
class DataPropertyCheckbox extends HTMLDivElement
{
  /* Render checkbox with label, listen for events. */
  render()
  {
    this.innerHTML = '';

    const prop   = this.getAttribute('prop');
    const source = this.getAttribute('source');
    const group  = this.getAttribute('group');

    this.classList.add('checkbox-inline');
    const itemOption = document.createElement('input');
    itemOption.setAttribute('type', 'checkbox');
    itemOption.setAttribute('prop', !!group ? `${source}.${prop}` : source);
    this.appendChild(itemOption);
    const itemLabel = document.createElement('label');
    itemLabel.appendChild(document.createTextNode(!!group ? dataSourceLang[group][source][prop] : dataSourceLang[prop][source].common));

    /* Setup event listeners. */
    itemLabel.addEventListener('click', (e =>
    {
      itemOption.checked = !itemOption.checked;
      this.handleCheck(itemOption.checked);
    }).bind(this));
    itemOption.addEventListener('change', (e =>
    {
      this.handleCheck(itemOption.checked);
    }).bind(this));

    this.appendChild(itemLabel);
  }

  /* Handle check all for checkbox group, bidirectionally. And push selections into datasource input. */
  handleCheck(newValue)
  {
    if(this.classList.contains('obj-view-header'))
    {
      const objViewBody = this.parentElement.querySelector('.obj-view-body');
      for(const item of objViewBody.querySelectorAll('.obj-view-item input[type="checkbox"]')) item.checked = newValue;
    }
    else
    {
      const objViewBody = this.parentElement;
      const headerItem  = objViewBody.parentElement.querySelector('.obj-view-header input[type="checkbox"]');
      const groupItems  = objViewBody.querySelectorAll('.obj-view-item input[type="checkbox"]');
      let   allChecked  = true;
      for(const item of groupItems)
      {
        if(!item.checked) allChecked = false;
      }
      headerItem.checked = allChecked;
    }
    window.dataSourceStore.sync();
  }

  /* Handle attr change, rerender. */
  attributeChangedCallback()
  {
    /* Skip rendering if props not complete. */
    if(!this.hasAttribute('prop') || !this.hasAttribute('source')) return;
    this.render();
  }

  /* Define observed attr. */
  static get observedAttributes()
  {
    return ['group', 'source', 'prop'];
  }
}
customElements.define('data-property-checkbox', DataPropertyCheckbox, {extends: 'div'});

/* Selected data column title, dynamically render with current group and selection. */
class SelectedTitleText extends HTMLSpanElement
{
  /* Setup re-render trigger. */
  constructor()
  {
    super();
    window.dataSourceStore.subscribe(this.render.bind(this));
  }

  /* Render span from string format. */
  render()
  {
    this.innerHTML = '';

    const format = this.getAttribute('format');
    const group  = this.getAttribute('group');
    const count  = Object.keys(window.dataSourceStore.value).length;

    const args = [dataSourceLang[group].common, count]
    this.innerHTML = format.replace(/{(\d+)}/g, ((match, argIndex) =>
    {
      return typeof args[argIndex] !== 'undefined' ? args[argIndex] : match;
    }));
  }

  /* Handle attr change, rerender. */
  attributeChangedCallback(name, oldValue, newValue)
  {
    if(name === 'group' && oldValue !== newValue) this.render();
  }

  /* Define observed attr. */
  static get observedAttributes()
  {
    return ['group'];
  }
}
customElements.define('selected-title-text', SelectedTitleText, {extends: 'span'});
</script>

<div id='mainMenu' class='clearfix' style='display: flex; flex-direction: row;'>
  <?php echo html::backButton("<i class='icon icon-back icon-sm'></i> $lang->goback", '', 'btn btn-info');?>
  <?php include 'promptdesignprogressbar.html.php';?>
  <?php echo html::submitButton("<i class='icon icon-save icon-sm'></i> $lang->save", '', 'btn btn-primary');?>
</div>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div id='data-selector'>
          <div id='data-sources'>
            <div id='data-categories'>
              <div class='heading text-center'>
                <h4><?php echo $lang->ai->prompts->selectDataSource;?></h4>
              </div>
              <div id='data-category-select'>
                <ul>
                  <?php foreach(array_keys($dataSource) as $index => $objectGroupKey):?>
                    <li data-group-key='<?php echo $objectGroupKey;?>'>
                      <a class='btn btn-block <?php echo $index == 0 ? ' active btn-info' : ' btn-link';?>'>
                        <?php echo $lang->ai->dataSource[$objectGroupKey]['common'];?>
                      </a>
                    </li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
            <div id='data-properties'>
              <div class='heading'>
                <h4><?php echo $lang->ai->prompts->selectData;?></h4>
                <small class='text-gray'><?php echo $lang->ai->prompts->selectDataTip;?></small>
              </div>
              <div id='data-property-select'>
                <div id='data-property-selector' is='data-property-selector' object-group='story'></div>
              </div>
            </div>
          </div>
          <div id='data-selected'>
            <div class='heading'>
              <h4><span id='selected-title-text' is='selected-title-text' group='story' format='<?php echo $lang->ai->prompts->selectedFormat;?>'></span></h4>
            </div>
            <div id='data-selected-items'>
              <?php echo html::hidden('datasource');?>
            </div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <div style='display: flex; justify-content: center;'><?php echo html::submitButton($lang->ai->nextStep, 'disabled');?></div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
$(function()
{
  /* Handle category switching. */
  $('#data-category-select > ul > li > a').click(function()
  {
    if($(this).hasClass('active')) return;

    /* Update menu states. */
    $('#data-category-select > ul > li > a').removeClass('active btn-info').addClass('btn-link');
    $(this).addClass('active btn-info').removeClass('btn-link');

    /* Update other component props. */
    $('#data-property-selector').attr('object-group', $(this).parent().attr('data-group-key'));
    $('#selected-title-text').attr('group', $(this).parent().attr('data-group-key'));

    /* Reset selected data. */
    window.dataSourceStore.reset();
  });

  /* Disable checkboxes to prevent them getting posted as form data. */
  $('form').submit(function()
  {
    $('#data-property-selector input[type="checkbox"]').each(function() {$(this).attr('disabled', 'disabled');});
  });
});
</script>
<?php include '../../common/view/footer.html.php';?>
