<table class="myTable" cellpadding="0" cellspacing="0">
        <?php echo $this->element('tablehead',
         Array('taghashes'=>$taghash)); ?>
    <tbody>
    	<?php echo $this->element('rsorttablebody',
    	 Array('results' => $tableresults,
//     	 		'articleparentres',
    	 'taghashes'=>$tableresults['taghash'],
    	 'firstModel' => 'Article',
    	 'currentUserID' => $currentUserID,
    	'srns_code_member'=>$tableresults['srns_code_member']
    	,$sorting_tags)); ?>
    		<?php echo $this->element('tablebody',
    	 Array('results' => $tableresults['tagparentres'],
    	 'taghashes'=>$tableresults['taghash'],
    	 'firstModel' => 'Tag',
    	 'currentUserID' => $currentUserID,
    	'srns_code_member'=>$tableresults['srns_code_member'])); ?>
	</tbody>
</table>

<!-- ボタンを押したら ajaxInput(this)
-->
<div onClick='toggleShow(this);' >
	add
	</div>
	<div id='HSfield' style='display: none;'>

	<div id="inputfield">
	<input type="buttun" value="Add Article" onClick="addArticle(this)">
	<!-- 下に　$user_id $name $target_ids array リンクする対象id配列
	これをどうにかして取り出して投げる
	-->
		<?php echo $this->element('Input',
    	 array('ulist' => $ulist,
    	 'currentUserID'=>$currentUserID,
    	 'model'=>'Article',
    	 )); ?>
		<fieldset>
        <?php
echo $this->AutoCompleteNoHidden->input(
    'or1.1',
    array(
        'autoCompletesUrl'=>$this->Html->url(
            array(
                'controller'=>'tagusers',
                'action'=>'auto_complete',
            )
        ),
        'autoCompleteRequestItem'=>'autoCompleteText',
    )
);
?>
<?php
echo $this->Form->hidden('add_tag_id.',array('value' => '','class' => 'tag_id','id' => 'tag_id'));
echo $this->Form->hidden('add_trikey_id.',
array('value' => $trikey_id,'class' => 'tag_id','id' => 'add_trikey_id'));
?>
</fieldset>
    	 <input type="buttun" value="Add Tag" onClick="add_single_tag(this)">
	</div>


	</div>