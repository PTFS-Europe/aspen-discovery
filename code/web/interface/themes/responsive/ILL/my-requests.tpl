{*is in essence a copy of myMaterialRequests.tpl, is intended for use as proof of concept only *}

<div id="main-content">
	{if !empty($profile->_web_note)}
		<div class="row">
			<div id="web_note" class="alert alert-info text-center col-xs-12">{$profile->_web_note}</div>
		</div>
	{/if}
	{if !empty($accountMessages)}
		{include file='systemMessages.tpl' messages=$accountMessages}
	{/if}
	{if !empty($ilsMessages)}
		{include file='ilsMessages.tpl' messages=$ilsMessages}
	{/if}

	<h1>{translate text='My Materials Requests' isPublicFacing=true}</h1>

	{* MDN 7/26/2019 Do not allow access for linked users *}
	{*	{include file="MyAccount/switch-linked-user-form.tpl" label="Viewing Requests for" actionPath="/MyAccount/ReadingHistory"}*}
	
	{if !empty($error)}
		<div class="alert alert-danger">{$error}</div>
	{else}
		{if $user->canSuggestMaterials()}
			{if !empty($ILLRequests)} 
			{if count($ILLRequests) > 0}
				<form method="post" action="/MaterialsRequest/IlsRequests">
					<table id="requestedMaterials" class="table table-striped table-condensed tablesorter">
						<thead>
						<tr>
							{if !empty($allowDeletingILSRequests)}
								<th>&nbsp;</th>
							{/if}
							<th>{translate text="Summary" isPublicFacing=true}</th>
							<th>{translate text="Suggested On" isPublicFacing=true}</th>
							<th>{translate text="Staff Note" isPublicFacing=true}</th>
							<th>{translate text="Status" isPublicFacing=true}</th>
						</tr>
						</thead>
						<tbody>
						{foreach from=$ILLRequests item=request}
							<tr>
								{if !empty($allowDeletingILSRequests)}
									<td>
										<input type="checkbox" name="delete_field" value="{$request.id}" title="{translate text="Select Request" inAttribute=true isPublicFacing=true}" aria-label="{translate text="Select Request" inAttribute=true isPublicFacing=true}"/>
									</td>
								{/if}
								<td>{$request.summary}</td>
								<td>{$request.requested_date}</td>
								<td>{$request.staff_note}</td>
								<td>{$request.status}</td>
							</tr>
						{/foreach}
						</tbody>
					</table> 
					{if !empty($allowDeletingILSRequests)}
						<button type="submit" class="btn btn-sm btn-danger" name="submit">{translate text="Delete Selected" isPublicFacing=true}</button>
					{/if}
				</form>
				<br/>
			{else}
				<div class="alert alert-warning">{translate text='There are no materials requests that meet your criteria.' isPublicFacing=true}</div>
			{/if}
			{/if}
			<div id="createNewMaterialsRequest"><a href="/MaterialsRequest/NewRequestIls?patronId={$patronId}" class="btn btn-primary btn-sm">{translate text='Submit a New Materials Request' isPublicFacing=true}</a></div>
		{else}
			<div class="alert alert-warning">{translate text='You are not eligible to make Materials Requests at this time.' isPublicFacing=true}</div>
		{/if}
	{/if}
</div>
<script type="text/javascript">
	{literal}
	$("#requestedMaterials").tablesorter({cssAsc: 'sortAscHeader', cssDesc: 'sortDescHeader', cssHeader: 'unsortedHeader', headers: {0: { sorter: false}, 2: {sorter : 'date'}, 6: { sorter: false} } });
	{/literal}
</script>