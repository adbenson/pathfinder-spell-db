<table>
	<tbody>
		<?php foreach ($spells as $spell): ?>
			<tr data-spell_id="<?=$spell['id']?>">
				<?php foreach ($spell as $key => $value): ?>
				
					<?php if($key === 'id') continue; ?>
					
					<?php if ($key === 'description_formated'): ?>
						<td class='description button closed'>
							<?=collapse()?>
						</td>
					<?php else: ?>
						<td class="spell_<?=$key?>">
						
						<?php
							if (in_array($key, $booleans)) {
								echo "<div class='bool ".($value == 0)? "yes" : "no"."'>&nbsp;</div>";
							}
							else {
								echo $value;
							}
						?>
						
						</td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			
			<?php if (array_key_exists('description_formated', $spell)): ?>
				<tr>
					<td colspan="<?=count($spell)?>">
						<div class="description full closed">
							<?=$spell['description_formated']?>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			
		<?php endforeach; ?>
	</tbody>
</table>