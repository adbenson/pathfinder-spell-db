<table>
	<thead>
		<tr>
			<?php foreach ($headings as $heading): ?>
				<th>
					<?=$heading?>
				</th>
			<?php endforeach; ?>
			<th>
				<div class='description all closed'>
					<?=collapse()?>
					Full Spell
				</div>
			</th>
		</tr>
		<tr>
			<td class="header_border" colspan="<?=count($spells[0])?>">
		</tr>
	</thead>
	<tbody>
		<?php foreach ($spells as $spell): ?>
			<tr class="spell_data_row" data-spell_id="<?=$spell['id']?>">
				<?php foreach ($spell as $key => $value): ?>
				
					<?php if($key === 'id') continue; ?>
					
					<td class="spell_<?=$key?>">
					
						<?php if (in_array($key, $booleans)) {
								$class = ($value == 0)? "bool no" : "bool yes";
								$value = "<div class='".$class."'>&nbsp;</div>";
						} ?>
						
						<?=$value?>
					
					</td>
				<?php endforeach; ?>
				
				<td class='description button closed'>
					<?=collapse()?>
				</td>
			</tr>
			
			<tr>
				<td class="description row" colspan="<?=count($spell)?>">
					<div class="description full closed">
						Loading...
					</div>
				</td>
			</tr>
			
		<?php endforeach; ?>
	</tbody>
</table>