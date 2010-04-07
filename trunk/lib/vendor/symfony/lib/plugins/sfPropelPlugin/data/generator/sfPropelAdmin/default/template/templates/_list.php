<?php if ($this->getParameterValue('list.batch_actions')): ?>
[?php echo form_tag('<?php echo $this->getModuleName() ?>/batchAction') ?]
<?php endif; ?>
<table cellspacing="0" class="sf_admin_list">
<thead>
<tr>
<?php if ($this->getParameterValue('list.batch_actions')): ?>
  <th id="sf_admin_list_th_sf_batch_actions">[?php echo checkbox_tag('foo', 1, 0, array('onclick' => "boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = this.checked } return true;")) ?]</th>
<?php endif; ?>
[?php include_partial('list_th_<?php echo $this->getParameterValue('list.layout', 'tabular') ?>') ?]
<?php if ($this->getParameterValue('list.object_actions')): ?>
  <th id="sf_admin_list_th_sf_actions">[?php echo __('Actions') ?]</th>
<?php endif; ?>
</tr>
</thead>
<tfoot>
<tr><th colspan="<?php echo count($this->getColumns('list.display')) + ($this->getParameterValue('list.object_actions') ? 1 : 0) + ($this->getParameterValue('list.batch_actions') ? 1 : 0) ?>">
<div class="float-right">
[?php if ($pager->haveToPaginate()): ?]
  [?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/first.png', array('align' => 'absmiddle', 'alt' => __('First'), 'title' => __('First'))), '<?php echo $this->getModuleName() ?>/list?page=1') ?]
  [?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/previous.png', array('align' => 'absmiddle', 'alt' => __('Previous'), 'title' => __('Previous'))), '<?php echo $this->getModuleName() ?>/list?page='.$pager->getPreviousPage()) ?]

  [?php foreach ($pager->getLinks() as $page): ?]
    [?php echo link_to_unless($page == $pager->getPage(), $page, '<?php echo $this->getModuleName() ?>/list?page='.$page) ?]
  [?php endforeach; ?]

  [?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/next.png', array('align' => 'absmiddle', 'alt' => __('Next'), 'title' => __('Next'))), '<?php echo $this->getModuleName() ?>/list?page='.$pager->getNextPage()) ?]
  [?php echo link_to(image_tag(sfConfig::get('sf_admin_web_dir').'/images/last.png', array('align' => 'absmiddle', 'alt' => __('Last'), 'title' => __('Last'))), '<?php echo $this->getModuleName() ?>/list?page='.$pager->getLastPage()) ?]
[?php endif; ?]
</div>
[?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults()) ?]
</th></tr>
</tfoot>
<tbody>
[?php $i = 1; foreach ($pager->getResults() as $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ?]
<tr class="sf_admin_row_[?php echo $odd ?]">
[?php include_partial('list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
[?php include_partial('list_td_<?php echo $this->getParameterValue('list.layout', 'tabular') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
[?php include_partial('list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>)) ?]
</tr>
[?php endforeach; ?]
</tbody>
</table>
