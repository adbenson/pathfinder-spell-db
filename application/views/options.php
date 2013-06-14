
    <?=form_open('spell_db/set_'.$option, array('id' => 'set_'.$option))?>
    
    	<?=form_fieldset(collapse().$option_name, array('id' => $option, 'class' => 'closed'))?>
    	
    		<div class="content">
    			<div class="options">
    			
					<?php foreach ($option_set as $code => $data):?>
						<div class="<?=$option?>>">
						
		    				<?=form_checkbox($code, $code, $data['enabled'])?>
		    				<?=form_label($data['name'], $code)?>
		    				
		    			</div>
		    		<?php endforeach;?>
		    		
		    	</div>
		    	<div class="set">
		    	
    				<?=form_submit('set_columns', "Set")?>
    				<?=form_reset('reset_columns', "Reset")?>
    				<?php if ($can_select_all):?>
    					<?=form_button('select_all', "Select All")?>
    					<?=form_button('unselect_all', "Unselect All")?>
    				<?php endif;?>
    				
    			</div>
    		</div>
    		
    	<?=form_fieldset_close()?>
    	
    <?=form_close()?>