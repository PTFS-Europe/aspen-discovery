{strip}
	{if !empty($listError)}<p class="error">{translate text=$listError isPublicFacing=true}</p>{/if}
	<form method="post" action="" name="listForm" class="form form-horizontal" id="addListForm">
		<div class="form-group">
			<label for="listTitle" class="col-sm-3 control-label">{translate text="List" isPublicFacing=true}</label>
			<div class="col-sm-9">
				{if empty($validListNames)}
					<input type="text" id="listTitle" name="title" value="" size="50" class="form-control">
				{else}
					<select id="listTitle" name="titleSelect" class="form-control">
						{foreach from=$validListNames item=listName key=listNameIndex}
							<option value="{$listNameIndex}">{$listName}</option>
						{/foreach}
					</select>
				{/if}
			</div>
		</div>
		{if !empty($enableListDescriptions)}
			<div class="form-group">
			  <label for="listDesc" class="col-sm-3 control-label">{translate text="Description" isPublicFacing=true}</label>
				<div class="col-sm-9">
			    <textarea name="desc" id="listDesc" rows="3" cols="50" class="form-control"></textarea>
				</div>
			</div>
		{/if}
		<div class="form-group">
			<label for="public" class="col-sm-3 control-label">{translate text="Access" isPublicFacing=true}</label>
			<div class="col-sm-9">
				<input type='checkbox' name='public' id='public' data-on-text="{translate text="Public" isPublicFacing=true}" data-off-text="{translate text="Private" isPublicFacing=true}" {if in_array('Include Lists In Search Results', $userPermissions)}onchange="if($(this).prop('checked') === true){ldelim}$('#searchableRow').show();$('#displayListAuthorRow').show(){rdelim}else{ldelim}$('#searchableRow').hide();$('#displayListAuthorRow').hide(){rdelim}"{/if}/>
				<div class="form-text text-muted">
					<small>{translate text="Public lists can be shared with other people by copying the URL of the list or using the Email List button when viewing the list." isPublicFacing=true}</small>
				</div>
			</div>
		</div>
		{if !empty($userPermissions)}
			{if in_array('Include Lists In Search Results', $userPermissions)}
				<div class="form-group" id="searchableRow" style="display: none">
					<label for="searchable" class="col-sm-3 control-label">{translate text="Show in search results" isPublicFacing=true}</label>
					<div class="col-sm-9">
						<input type='checkbox' name='searchable' id='searchable' data-on-text="{translate text="Yes" isPublicFacing=true}" data-off-text="{translate text="No" isPublicFacing=true}" checked/>
						<div class="form-text text-muted">
							<small>{translate text="If enabled, this list can be found by searching user lists. It must have at least 3 titles to be shown." isPublicFacing=true}</small>
						</div>
					</div>
				</div>
			{/if}
		{/if}
		{if !empty($userPermissions)}
		{if in_array('Include Lists In Search Results', $userPermissions)}
			<div class="form-group" id="displayListAuthorRow" style="display: none">
				<label for="displayListAuthor" class="col-sm-3 control-label">{translate text="Show list author in search results" isPublicFacing=true}</label>
				<div class="col-sm-9">
					<input type='checkbox' name='displayListAuthor' id='displayListAuthor' data-on-text="{translate text="Yes" isPublicFacing=true}" data-off-text="{translate text="No" isPublicFacing=true}" checked/>
					<div class="form-text text-muted">
						<small>{translate text="If enabled, your name will be displayed as the author of public lists." isPublicFacing=true}</small>
					</div>
				</div>
			</div>	
		{/if}
		{/if}
	<input type="hidden" name="source" value="{if !empty($source)}{$source}{/if}">
		<input type="hidden" name="sourceId" value="{if !empty($sourceId)}{$sourceId}{/if}">
	</form>
	<br/>
{/strip}
<script type="text/javascript">
{literal}
	$(document).ready(function(){
		var publicSwitch = $('#public').bootstrapSwitch();
		var searchableSwitch = $('#searchable').bootstrapSwitch();
		var displayListAuthorSwitch = $('#displayListAuthor').bootstrapSwitch();
	});
{/literal}</script>