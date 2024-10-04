<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    {if $canEdit}
      <div style="position:absolute;top:30px;right:10px"><button onclick="window.location.href='{$editPageUrl|escape: 'html'}'">{translate text="Edit Page" isPublicFacing=false}</button></div>
    {/if}
    <title>{$title|escape: 'html'}</title>
  </head>
  <body>
    {if $showTitleOnPage}
      <h1>{$title|escape: 'html'}</h1>
    {/if}
    <div id="content">
      {$templateContent}
    </div>
  </body>
</html>