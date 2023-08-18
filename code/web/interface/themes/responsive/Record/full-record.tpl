{include file="GroupedWork/load-full-record-view-enrichment.tpl"}

{strip}
	<div class="col-xs-12">
		{* Search Navigation *}
		{include file="GroupedWork/search-results-navigation.tpl"}

		{if !empty($error) && !$recordDriver}
			<div class="row">
				<div class="alert alert-danger">
					{$error}
				</div>
			</div>
		{else}
			{* Display Title *}
			<h1>
				{*{$recordDriver->getTitle()|escape}*}{* // ever a case when the trailing punction is needed? *}
				{* Title includes the title section *}
				{$recordDriver->getTitle()|removeTrailingPunctuation}
				{if $recordDriver->getFormats()}
					<br>
					<small>
						({implode subject=$recordDriver->getFormats() glue=", " translate=true isPublicFacing=true})
						{if $recordDriver->isClosedCaptioned()}
							&nbsp;<i class="fas fa-closed-captioning"></i>
						{/if}
					</small>
				{/if}
			</h1>

			<div class="row">
				<div class="col-xs-4 col-sm-5 col-md-4 col-lg-3 text-center">
					{if $disableCoverArt != 1}
						<div id="recordCover" class="text-center row">
							<img alt="{translate text='Book Cover' isPublicFacing=true inAttribute=true}" class="img-thumbnail {$coverStyle}" src="{$recordDriver->getBookcoverUrl('medium')}">
						</div>
					{/if}
					{if !empty($showRatings)}
						{include file="GroupedWork/title-rating-full.tpl" showFavorites=0 ratingData=$recordDriver->getRatingData() showNotInterested=false hideReviewButton=true}
					{/if}
				</div>

				<div id="main-content" class="col-xs-8 col-sm-7 col-md-8 col-lg-9">
					{if !empty($error)}
						<div class="row">
							<div class="alert alert-danger">
								{$error}
							</div>
						</div>
					{/if}

					<div class="row">
						<div id="record-details-column" class="col-xs-12 col-sm-12 col-md-9">
							{include file="Record/view-title-details.tpl"}
						</div>

						{if !($recordDriver->hasMultipleVariations())}
							<div id="recordTools" class="col-xs-12 col-sm-6 col-md-3">
								{include file="Record/result-tools.tpl" showMoreInfo=false summShortId=$shortId module=$activeRecordProfileModule summId=$id summTitle=$recordDriver->getTitle()}
							</div>
						{else}
							<div id="multiple-variations-column" class="col-xs-12 col-sm-12 col-md-9">
								{include file="Record/multipleVariationDisplay.tpl" workId=$recordDriver->getPermanentId()}
							</div>
						{/if}
					</div>
					
					<button value="citeRecord" id="FavCite" class="btn btn-sm" onclick="{ldelim}$('#recordCitations').show(){rdelim}" style="background-color:#747474; color:white; border-color:#636363;" >{translate text='Generate Citations' isPublicFacing=true}</button>

					<div>
						 <div id="recordCitations" style="display:none">
							<button value="closeRecordCitation" class="btn btn-sm" onclick="{ldelim}$('#recordCitations').hide(){rdelim}" style="color:white; background-color:#747474; border-color:#636363"><i class="fas fa-times"></i></button>
							{if $citationCount <1}
								{translate text="No citations are available for this record" isPublicFacing=true}
							{else}
								<div style="text-align: left;">
									{if $ama}
										<b>{translate text="AMA Citation" isPublicFacing=true}</b>
										<p>{include file=$ama}</p>
									{/if}
								</div>
								<div style="text-align: left;">
									{if !empty($apa)}
										<b>{translate text="APA Citation, 7th Edition" isPublicFacing=true}</b>{if !empty($showCitationStyleGuides)}<span class="styleGuide"><a href="https://owl.purdue.edu/owl/research_and_citation/apa_style/apa_formatting_and_style_guide/general_format.html" target="_blank">({translate text="style guide" isPublicFacing=true})</a></span>{/if}
										<p>{include file=$apa}</p>
									{/if}
								</div>
								<div style="text-align: left;">
									{if !empty($chicagoauthdate)}
										<b>{translate text="Chicago / Turabian - Author Date Citation, 17th Edition" isPublicFacing=true}</b>{if !empty($showCitationStyleGuides)}<span class="styleGuide"><a href="https://www.chicagomanualofstyle.org/tools_citationguide/citation-guide-2.html" target="_blank">({translate text="style guide" isPublicFacing=true})</a></span>{/if}
										<p>{include file=$chicagoauthdate}</p>
									{/if}
								</div>
								<div style="text-align: left;">
									{if !empty($chicagohumanities)}
										<b>{translate text="Chicago / Turabian - Humanities (Notes and Bibliography) Citation, 17th Edition" isPublicFacing=true}</b>{if !empty($showCitationStyleGuides)}<span class="styleGuide"><a href="https://www.chicagomanualofstyle.org/tools_citationguide/citation-guide-1.html" target="_blank">({translate text="style guide" isPublicFacing=true})</a></span>{/if}
										<p>{include file=$chicagohumanities}</p>
									{/if}
								</div>
								<div style="text-align: left;">
									{if !empty($mla)}
										<b>{translate text="MLA Citation, 9th Edition" isPublicFacing=true}</b>{if !empty($showCitationStyleGuides)}<span class="styleGuide"><a href="https://owl.purdue.edu/owl/research_and_citation/mla_style/mla_formatting_and_style_guide/mla_general_format.html" target="_blank">({translate text="style guide" isPublicFacing=true})</a></span>{/if}
										<p>{include file=$mla}</p>
									{/if}
								</div>
							{/if}
							<div class="alert alert-warning" id="warning">
								<strong>{translate text="Note!" isPublicFacing=true}</strong> {translate text="Citations contain only title, author, edition, publisher, and year published. Citations should be used as a guideline and should be double checked for accuracy. Citation formats are based on standards as of August 2021." isPublicFacing=true}
							</div>
						</div>
					</div>
				
					<div class="row">
						<div class="col-xs-12">
							{include file='GroupedWork/result-tools-horizontal.tpl' ratingData=$recordDriver->getRatingData() recordUrl=$recordDriver->getLinkUrl() showMoreInfo=false showNotInterested=false}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				{include file=$moreDetailsTemplate}
			</div>
		{/if}
	</div>
{/strip}
