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
  #data-selector {display: flex; flex-direction: row; height: 100%; margin-bottom: 16px; max-height: calc(100% - 48px);}
  #data-sources {flex-grow: 2; display: flex; flex-direction: row; margin-right: 16px;}
  #data-selected {flex-grow: 1; display: flex; flex-direction: column;}
  .heading > * {display: inline-block; padding: 4px 0;}
  #data-category-select {flex-grow: 1; background-color: #f8f8f8; border: 1px solid #eee; border-right: none;}
  #data-category-select > ul {padding: 16px; margin: 0; list-style: none;}
  #data-category-select > ul > li > a.active {font-weight: bold;}
  #data-categories {flex-grow: 1; display: flex; flex-direction: column;}
  #data-properties {flex-grow: 3; display: flex; flex-direction: column; height: 100%;}
  #data-properties > .heading {padding: 4px 20px;}
  #data-properties > .heading > h4 {margin: 6px 0;}
  #data-property-select {flex-grow: 1; border: 1px solid #eee; border-left: none; height: calc(100% - 44px); overflow-y: auto;}
  #data-property-selector {height: 100%; width: 100%;}
  #data-selected-items {flex-grow: 1; border: 1px solid #eee; height: calc(100% - 44px); overflow-y: auto; scrollbar-gutter: stable;}
  .obj-view {display: flex; flex-direction: column; padding: 20px;}
  .obj-view:not(:first-of-type) {border-top: 1px solid #eee;}
  .obj-view-body {display: grid; padding: 4px 20px; grid-template-columns: repeat(4, 1fr);}
  .obj-view-header.checkbox-inline, .obj-view-item.checkbox-inline {margin: 4px; cursor: unset;}
  .obj-view-item.checkbox-inline+.obj-view-item.checkbox-inline {margin-left: 4px; margin-top: 4px;}
  .checkbox-inline > label, .checkbox-inline > input {cursor: pointer;}
  .checkbox-primary > label {padding-left: 0;}
  #selected-data-sorter .list-group {padding: 0; margin: 0; list-style: none; overflow: hidden;}
  #selected-data-sorter .list-group-item {padding: 8px 16px; display: flex; align-items: center; cursor: move; transition: background-color 0.2s ease-in-out;}
  #selected-data-sorter .list-group-item > span {flex-grow: 1;}
  #selected-data-sorter .list-group-item:hover {background-color: #eef5ff;}
  #selected-data-sorter .list-group-item .drag-icon {padding-right: 8px; opacity: 0; transition: opacity 0.2s ease-in-out;}
  #selected-data-sorter .list-group-item:hover .drag-icon {opacity: 1;}
  #selected-data-sorter .list-group-item::before {content: counter(list-item); counter-increment: list-item; display: inline-block; width: 2ch; padding-right: 28px;}
  #selected-data-sorter .list-group-item.drag-shadow::before {content: '';}
  #selected-data-sorter .list-group-item .remove-icon {cursor: pointer; padding: 2px;}

  @media (max-width: 1366px)
  {
    .obj-view-body {grid-template-columns: repeat(3, 1fr);}
  }
</style>

<script>
/* Store and sync value of datasource input, change by checkbox changes. */
class DataSourceStore
{
  value = [];
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
    this.value = [];

    /* Notify listeners. */
    this.listeners.forEach(l => (l(this.value)));
  }

  /* Sync value from form checkboxes. */
  sync()
  {
    const dataSources = document.querySelectorAll('#data-property-selector');
    const value = this.value;
    dataSources.forEach(dataSource =>
    {
      const group = dataSource.getAttribute('object-group');
      const items = dataSource.querySelectorAll('input[type="checkbox"]');
      items.forEach(item =>
      {
        const itemPropName = item.getAttribute('prop');
        if(itemPropName.includes('.'))
        {
          if(item.checked)
          {
            if(!value.includes(itemPropName)) value.push(itemPropName);
          }
          else if(value.includes(itemPropName)) value.splice(value.indexOf(itemPropName), 1);
        }
      });
    });
    this.value = value;
    document.querySelector('#datasource').value = value.join(',');

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

    this.classList.add('checkbox-primary', 'checkbox-inline');
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
    const count  = window.dataSourceStore.value.length;

    const args = [dataSourceLang[group].common, count];
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

/* Sortable selected data column list, dynamically render with current selection. */
class SelectedDataSorter extends HTMLDivElement
{
  /* Setup re-render trigger. */
  constructor()
  {
    super();
    window.dataSourceStore.subscribe(this.render.bind(this));
  }

  /* Render sortable list from current selection. */
  render()
  {
    this.innerHTML = '';

    const group = this.getAttribute('group');
    const props = window.dataSourceStore.value;

    const listView = document.createElement('ol');
    listView.classList.add('list-group');
    props.forEach(prop =>
    {
      const item = document.createElement('li');
      item.classList.add('list-group-item');
      item.setAttribute('prop', prop);

      const dragIcon = document.createElement('i');
      dragIcon.classList.add('icon', 'icon-move', 'text-gray', 'drag-icon');
      item.appendChild(dragIcon);

      const [source, propName] = prop.split('.');
      const itemText = document.createElement('span');
      itemText.appendChild(document.createTextNode(`${dataSourceLang[group][source].common} / ${dataSourceLang[group][source][propName]}`));
      item.appendChild(itemText);

      const itemRemove = document.createElement('i');
      itemRemove.classList.add('icon', 'icon-close', 'text-gray', 'remove-icon');
      itemRemove.addEventListener('click', (() =>
      {
        window.dataSourceStore.value = window.dataSourceStore.value.filter(p => p !== prop);
        document.querySelector(`.obj-view-item input[type="checkbox"][prop="${prop}"]`).click();
      }).bind(this));
      item.appendChild(itemRemove);

      listView.appendChild(item);
    });
    this.appendChild(listView);

    /* Setup sortable on this list. */
    $(listView).sortable(
    {
      handle: '.list-group-item',
      finish: () =>
      {
        const props = [];
        for(const item of listView.querySelectorAll('.list-group-item')) props.push(item.getAttribute('prop'));
        window.dataSourceStore.value = props;
        window.dataSourceStore.sync();
      }
    });
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
customElements.define('selected-data-sorter', SelectedDataSorter, {extends: 'div'});
</script>

<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form id="mainForm" onsubmit="return validateForm();" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
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
                      <a class='btn btn-block <?php echo $objectGroupKey == $activeDataSource ? ' active btn-info' : ' btn-link';?>' <?php echo $objectGroupKey == $activeDataSource ? 'style="background-color: #E6F0FF;"' : '';?>>
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
                <div id='data-property-selector' is='data-property-selector' object-group='<?php echo $activeDataSource;?>'></div>
              </div>
            </div>
          </div>
          <div id='data-selected'>
            <div class='heading'>
              <h4><span id='selected-title-text' is='selected-title-text' group='<?php echo $activeDataSource;?>' format='<?php echo $lang->ai->prompts->selectedFormat;?>'></span></h4>
            </div>
            <div id='data-selected-items'>
              <div id='selected-data-sorter' is='selected-data-sorter' group='<?php echo $activeDataSource;?>'></div>
            </div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <?php echo html::hidden('datasource', $prompt->source);?>
          <?php echo html::hidden('datagroup', $activeDataSource);?>
          <div style='display: flex; justify-content: center;'><?php echo html::submitButton($lang->ai->nextStep, 'disabled name="jumpToNext" value="1"');?></div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
function validateForm()
{
  let pass = true;
  const dataGroup = document.querySelector('input[name="datagroup"]')?.value;
  const dataSource = document.querySelector('input[name="datasource"]')?.value;
  if(!dataGroup || !dataSource)
  {
    $.zui.messager.danger('<?php echo sprintf($lang->ai->validate->noEmpty, $lang->ai->prompts->selectDataSource);?>');
    pass = false;
  }
  if(pass)
  {
    /* Disable checkboxes to prevent them getting posted as form data. */
    $('#data-property-selector input[type="checkbox"]').each(function() {$(this).attr('disabled', 'disabled');});
  }

  return pass;
}
$(function()
{
  /* Handle category switching. */
  $('#data-category-select > ul > li > a').click(function()
  {
    if($(this).hasClass('active')) return;

    /* Reset selected data. */
    window.dataSourceStore.reset();

    /* Update menu states. */
    $('#data-category-select > ul > li > a').removeClass('active btn-info').addClass('btn-link');
    $(this).addClass('active btn-info').removeClass('btn-link');

    var currentGroup = $(this).parent().attr('data-group-key');

    /* Update other component props. */
    $('#data-property-selector').attr('object-group', currentGroup);
    $('#selected-title-text').attr('group', currentGroup);
    $('#selected-data-sorter').attr('group', currentGroup);

    /* Update hidden form fields. */
    $('input[name="datagroup"]').val(currentGroup);
  });

  /* Toggle disabled submit button. */
  window.dataSourceStore.subscribe(function(value)
  {
    $('#submit').attr('disabled', !value.length);
  });

  <?php if($prompt->source):?>
    /* Init checkboxes states. */
    '<?php echo $prompt->source;?>'.split(',').filter(function(p) {return p;}).forEach(function(prop)
    {
      $('#data-property-selector input[type="checkbox"][prop="' + prop + '"]').click();
    });
  <?php endif;?>
});
</script>
<?php include '../../common/view/footer.html.php';?>
