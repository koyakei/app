<?php if ($leftID == null) {
	$leftID = 0;
}
if ($leftKeyID == null) {
	$leftKeyID = 0;
} ?>
<?php foreach ($results  as $result): ?>
<?php if($firstModel == 'Tag'){$userCallAssosiation = 'TO';} else {$userCallAssosiation = 'AO';}?>
<div id="draggble">
				<tr>
						
					<td class="rowhandler"><div class="drag row">
						<?php echo h($result[$firstModel]['ID']); ?></div><br>
					<?php echo $firstModel; echo $this->Form->hidden('to.'.$firstModel.'.'.'.ID', array('value'=>$result[$firstModel]['ID'])); ?></td>
					<td><?php if($LR == "left"){ echo $this->Html->link($result[$firstModel]['name'], array('controller' => $firstModel."s", 'action' => 'transmitter', $result[$firstModel]['ID'],$leftKeyID,$rightID,$rightKeyID)); } else {
						echo $this->Html->link($result[$firstModel]['name'], array('controller' => $firstModel."s", 'action' => 'transmitter', $leftID,$leftKeyID,$result[$firstModel]['ID'],$rightKeyID));
						
					}

					 	?></td>
					<td><?php echo h($result[$userCallAssosiation]['username']); ?>&nbsp;</td>
					<td><?php echo h($result[$firstModel]['created']); ?>&nbsp;</td>
					<td><?php echo h($result[$firstModel]['modified']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Edit'), array('controller'=> $firstModel."s",'action' => 'edit', $result[$firstModel]['ID'])); ?>
						<?php 
						if ($result['Link']['ID'] =! null) {
						 echo $this->Form->postLink(__('Delete'), array('controller'=> 'Links','action' => 'delete', $result['Link']['ID']), null, __('Are you sure you want to delete # %s?', $result[$firstModel]['ID'])); 

						
						} ?>
					</td>
					
				</tr>
			</div>
			<?php endforeach; ?>