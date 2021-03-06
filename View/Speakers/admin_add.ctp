<div class="speakers form">
<?php echo $this->Form->create('Speaker');?>
	<fieldset>
		<legend><?php echo __('Admin Add Speaker'); ?></legend>
	<?php
		echo $this->Form->input('user_id',array('empty'=>true));
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('bio');
		echo $this->Form->input('website');
		echo $this->Form->input('email');
		echo $this->Form->input('twitter');
		echo $this->Form->input('featured', array('label'=>__('Featured on Speakers page')));
		echo $this->Form->input('portrait_url', array('after'=>__('Leave blank to use a Gravatar using the email field.')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<?php
$this->append('sidebar');
?>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Speakers'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Talks'), array('controller' => 'talks', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Talk'), array('controller' => 'talks', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php
$this->end();
?>
