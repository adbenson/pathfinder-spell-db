
<?=form_fieldset(collapse().'Spell Selection', array('id' => 'selection'))?>
	<div class="content">
	
		<?=form_open('get_spells', array('id' => 'class_level'))?>
		
	    	<?=form_dropdown('class', $classes, $class_id)?>
	    	
	    	<?=form_dropdown('level', $levels, $level)?>
	    	
	    <?=form_close()?>
	    
	    <?=$this->load->view('options', array(
	    	'option'=>'sources', 'can_select_all'=>TRUE, 'option_name'=>'Source Texts', 'option_set'=>$sources
	    ))?>
	    
	    <?=$this->load->view('options', array(
	    	'option'=>'columns', 'can_select_all'=>FALSE, 'option_name'=>'Columns', 'option_set'=>$columns
	    ))?> 
	    
	    <?=$this->load->view('options', array(
	    	'option'=>'attributes', 'can_select_all'=>FALSE, 'option_name'=>'Spell Attributes', 'option_set'=>$attribs
	    ))?>  
	    
	</div>
<?=form_fieldset_close()?>


<div id="spell_table"></div>
<table id="spells"></table>