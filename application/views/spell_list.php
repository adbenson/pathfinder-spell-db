
<?=form_fieldset('Spell Selection', array('id' => 'selection', 'class' => 'open'))?>
	<div class="content">
	
		<?=form_open('get_spells', array('id' => 'class_level'))?>
		
	    	<?=form_dropdown('class', $classes, $class_id)?>
	    	
	    	<?=form_dropdown('level', $levels, $level)?>
	    	
	    <?=form_close()?>
	    	
	    <?=form_open('spell_db/set_sources', array('id' => 'set_sources'))?>
	    	<?=form_fieldset('Sources', array('id' => 'sources', 'class' => 'closed'))?>
	    		<div class="content">
	    			<div class="options">
						<?php foreach ($sources as $code => $data):?>
							<div class="source">
			    			<?=form_checkbox($code, $code, $data['enabled'])?>
			    			<?=form_label($data['name'], $code)?>
			    			</div>
			    		<?php endforeach;?>
			    	</div>
			    	<div class="set">
	    				<?=form_submit('set_columns', "Set")?>
	    				<?=form_reset('reset_columns', "Reset")?>
	    			</div>
	    		</div>
	    	<?=form_fieldset_close()?>
	    <?=form_close()?>
	    	
	    <?=form_open('spell_db/set_columns', array('id' => 'set_columns'))?>
	    	<?=form_fieldset('Columns', array('id' => 'columns', 'class' => 'closed'))?>
	    		<div class="content">
	    			<div class="options">
						<?php foreach ($columns as $code => $data):?>
							<div class="column">
			    			<?=form_checkbox($code, $code, $data['enabled'])?>
			    			<?=form_label($data['name'], $code)?>
			    			</div>
			    		<?php endforeach;?>
			    	</div>
	    			<div class="set">
	    				<?=form_submit('set_columns', "Set")?>
	    				<?=form_reset('reset_columns', "Reset")?>
	    			</div>
	    		</div>
			<?=form_fieldset_close()?>
	    <?=form_close()?>
	</div>
<?=form_fieldset_close()?>


<div id="spell_table"></div>
<table id="spells"></table>