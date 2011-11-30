
<?=form_open('get_levels')?>
    <?=form_fieldset('Spell Selection')?>
    
    	<?=form_dropdown('class', $classes)?>
    	
    	<?=form_dropdown('level', $levels)?>

    <?=form_fieldset_close()?>
<?=form_close();?>