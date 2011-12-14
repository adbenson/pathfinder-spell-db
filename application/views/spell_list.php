
<?=form_fieldset(collapse(false).'Spell Selection', array('id' => 'selection', 'class' => 'open'))?>
	<div class="content">
	
		<?=form_open('get_spells', array('id' => 'class_level'))?>
		
	    	<?=form_dropdown('class', $classes, $class_id)?>
	    	
	    	<?=form_dropdown('level', $levels, $level)?>
	    	
	    <?=form_close()?>
	    
	    <?=$this->load->view('options', array(
	    	'option'=>'source', 'option_name'=>'Source Texts', 'option_set'=>$sources
	    ))?>
	    
	    <?=$this->load->view('options', array(
	    	'option'=>'columns', 'option_name'=>'Columns', 'option_set'=>$columns
	    ))?>  
	    
	</div>
<?=form_fieldset_close()?>


<div id="spell_table"></div>
<table id="spells"></table>