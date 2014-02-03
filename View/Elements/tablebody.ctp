<?php foreach ($results  as $result): ?>
				<tr>
					<td><?php echo h($result[$firstModel]['ID']); ?>&nbsp;</td>
					<td><?php echo h($result[$firstModel]['name']); ?>&nbsp;</td>
					<td><?php echo h($result[$firstModel]['user_id']); ?>&nbsp;</td>
					<td><?php echo h($result["$firstModel"]['created']); ?>&nbsp;</td>
					<td><?php echo h($result["$firstModel"]['modified']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link(__('View'), array('controller'=> $firstModel."s",'action' => 'view', $result[$firstModel]['ID'])); ?>
						<?php echo $this->Html->link(__('Edit'), array('controller'=> $firstModel."s",'action' => 'edit', $result[$firstModel]['ID'])); ?>
						<?php echo $this->Form->postLink(__('Delete'), array('controller'=> 'Links','action' => 'delete', $result['Link']['ID']), null, __('Are you sure you want to delete # %s?', $result[$firstModel]['ID'])); ?>
					</td>
					<td>
						<?php echo $this->element('tagrelationadd', Array('ulist' => $ulist,'idre'=>$idre,'ToID' => $result[$firstModel]['ID'])); ?>
						<?php foreach ($taghashes as $key => $hash): ?>
						<?php $b = 0; ?>
							<?php if($result['subtag'] != null) { ?>
								<?php foreach ($result['subtag'] as $subtag): ?>
									<?php if ($hash['ID'] == $subtag['Tag']['ID']){ ?></td>
										<td><?php echo $subtag['Link']['quant']; ?></td>
										<!--<?php echo $subtag['Tag']['name']; ?>-->
										<td>
										<?php echo $this->Form->create('tag',array('controller' => 'tags','action'=>'quant')); ?>
										<?php echo $this->Form->input('Link.quant',array('default'=>$subtag['Link']['quant'])); ?>
										<?php echo $this->Form->hidden('Link.ID', array('value'=>$subtag['Link']['ID'])); ?>
										<?php echo $this->Form->hidden('Link.user_id', array('value'=>$subtag['Link']['user_id'])); ?>
										<?php echo $this->Form->hidden('idre', array('value'=>$idre)); ?>
										<?php echo $this->Form->end('tag'); ?>
										<?php echo $this->Form->create('tag',array('controller' => 'tags','action'=>'tagdel')); ?>
										<?php echo $this->Form->hidden('Link.ID', array('value'=>$subtag['Link']['ID'])); ?>
										<?php echo $this->Form->hidden('Link.user_id', array('value'=>$subtag['Link']['user_id'])); ?>
											<?php echo $this->Form->hidden('idre', array('value'=>$idre)); ?>
										<?php echo $this->Form->end('tag'); ?>
										</td>
										<?php $b = 1; ?>
									<?php } ?>
								<?php endforeach; ?>
							<?php } ?>
							<?php if ($b == 0){ ?>
							<td></td><td></td>
						<?php } ?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>