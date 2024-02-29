{strip}
<textarea name='{$propName}' id='{$propName}' {if !empty($property.rows)}rows='{$property.rows}'{/if} {if !empty($property.cols)}cols='{$property.cols}'{/if} {if !empty($property.description)}title='{$property.description}'{/if} class='form-control {if !empty($property.required) && $objectAction != 'edit'}required{/if}' {if !empty($property.readOnly)}readonly{/if} {if !empty($property.maxLength)}maxlength="{$property.maxLength}" {/if}>
{$propValue|escape}
</textarea>
{/strip}
{*{if !empty($property.readOnly)}*}
	{if $property.type == 'html' || $property.type == 'translatableTextBlock' || ($property.type == 'markdown' && $useHtmlEditorRatherThanMarkdown)}
		<script>
		{literal}
		tinymce.init({
			selector: '#{/literal}{$propName}{literal}',
			{/literal}
			{if !empty($property.readOnly)}
				plugins: '',
				toolbar1: '',
				toolbar2: '',
				toolbar3: '',
				toolbar: 'image',
			{else}
				plugins: 'anchor autolink autoresize autosave code codesample colorpicker contextmenu directionality fullscreen help hr image imagetools insertdatetime link lists media paste preview print save searchreplace spellchecker table textcolor textpattern toc visualblocks visualchars wordcount tinymceEmoji',
				toolbar1: 'code | cut copy paste pastetext spellchecker | undo redo searchreplace | image table hr codesample insertdatetime | link anchor | tinymceEmoji',
				toolbar2: 'bold italic underline strikethrough | formatselect fontselect fontsizeselect forecolor backcolor',
				toolbar3: 'numlist bullist toc | alignleft aligncenter alignright | preview visualblocks fullscreen help',
				toolbar: 'image',
			{/if}
			{literal}
			menubar:'',
			image_advtab: true,
			images_upload_url: '/WebBuilder/AJAX?method=uploadImageTinyMCE',
			convert_urls: false,
			theme: 'modern',
			valid_elements : '*[*]',
			extended_valid_elements : [
				'*[*]',
				'img[class=img-responsive|*]'
			],
			emoji_show_groups: false,
			emoji_show_subgroups: false,
			{/literal}
			readonly:{if !empty($property.readOnly)}1,{else}0,{/if}
			{literal}
		});
		{/literal}
{*		{if !empty($property.readOnly)}*}
{*			tinyMCE.get('#{$propName}').getBody().setAttribute('contenteditable', false);*}
{*		{/if}*}
		</script>
	{elseif $property.type == 'markdown'}
		<script type="text/javascript">
			$(document).ready(function(){ldelim}
				var characterLimit{$propName} = $("#{$propName}").prop('maxlength');
				if (characterLimit{$propName} === undefined) {
					characterLimit{$propName} = 0;
				}
				var simplemde{$propName} = new SimpleMDE({ldelim}
					element: $("#{$propName}")[0],
					{if empty($property.readOnly)}
						toolbar: ["heading-1", "heading-2", "heading-3", "heading-smaller", "heading-bigger", "|",
							"bold", "italic", "strikethrough", "|",
							"quote", "unordered-list", "ordered-list", "|",
							"link", "image", {ldelim}name:"uploadImage", action:function (){ldelim} return AspenDiscovery.WebBuilder.getUploadImageForm('{$propName}'){rdelim},className: "fa fa-upload",title: "Upload Image"{rdelim}, "|",
							"preview", "guide"],
						status: [ {ldelim}
							className: "chars",
							defaultValue: function(el) {ldelim}
								if (characterLimit{$propName} > 0) {ldelim}
									el.innerHTML = "0 / " + characterLimit{$propName};
								{rdelim}
							{rdelim},
							onUpdate: function(el) {ldelim}
								if (characterLimit{$propName} > 0) {ldelim}
									el.innerHTML = simplemde{$propName}.value().length + " / "+characterLimit{$propName};
									AspenDiscovery.limitMarkdownField(simplemde{$propName}, characterLimit{$propName});
								{rdelim}
							{rdelim}
						{rdelim}],
					{else}
						toolbar: false,
						status: false,
					{/if}
				{rdelim});
				AspenDiscovery.WebBuilder.editors['{$propName}'] = simplemde{$propName};
				if (characterLimit{$propName} > 0) {
					AspenDiscovery.limitMarkdownField(simplemde{$propName}, characterLimit{$propName});
				}
				{if !empty($property.readOnly)}
					AspenDiscovery.WebBuilder.editors['{$propName}'].codemirror.options.readOnly = true;
				{/if}
			{rdelim});
		</script>	
	{/if}
	{if $property.type == 'grapesjs'}
	{literal}
	<link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css">
	<script src="//unpkg.com/grapesjs"></script>    
	<script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms@2.0.5"></script>
    <script src="https://unpkg.com/grapesjs-component-countdown@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-plugin-export@1.0.11"></script>
    <script src="https://unpkg.com/grapesjs-tabs@1.0.6"></script>
    <script src="https://unpkg.com/grapesjs-custom-code@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-touch@0.1.1"></script>
    <script src="https://unpkg.com/grapesjs-parser-postcss@1.0.1"></script>
    <script src="https://unpkg.com/grapesjs-tooltip@0.1.7"></script>
    <script src="https://unpkg.com/grapesjs-tui-image-editor@0.1.3"></script>
    <script src="https://unpkg.com/grapesjs-typed@1.0.5"></script>
    <script src="https://unpkg.com/grapesjs-style-bg@2.0.1"></script>
	<script>
    $('#contents').replaceWith('<div id="contents"></div>');
	var editor  = grapesjs.init({
		width: 'auto',
        container : '#contents',
        fromElement: true,
        showOffsets: true,
        assetManager: {
          embedAsBase64: true,
        },
        selectorManager: { componentFirst: true },
        styleManager: {
          sectors: [{
              name: 'General',
              properties:[
                {
                  extend: 'float',
                  type: 'radio',
                  default: 'none',
                  options: [
                    { value: 'none', className: 'fa fa-times'},
                    { value: 'left', className: 'fa fa-align-left'},
                    { value: 'right', className: 'fa fa-align-right'}
                  ],
                },
                'display',
                { extend: 'position', type: 'select' },
                'top',
                'right',
                'left',
                'bottom',
              ],
            }, {
                name: 'Dimension',
                open: false,
                properties: [
                  'width',
                  {
                    id: 'flex-width',
                    type: 'integer',
                    name: 'Width',
                    units: ['px', '%'],
                    property: 'flex-basis',
                    toRequire: 1,
                  },
                  'height',
                  'max-width',
                  'min-height',
                  'margin',
                  'padding'
                ],
              },{
                name: 'Typography',
                open: false,
                properties: [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'letter-spacing',
                    'color',
                    'line-height',
                    {
                      extend: 'text-align',
                      options: [
                        { id : 'left',  label : 'Left',    className: 'fa fa-align-left'},
                        { id : 'center',  label : 'Center',  className: 'fa fa-align-center' },
                        { id : 'right',   label : 'Right',   className: 'fa fa-align-right'},
                        { id : 'justify', label : 'Justify',   className: 'fa fa-align-justify'}
                      ],
                    },
                    {
                      property: 'text-decoration',
                      type: 'radio',
                      default: 'none',
                      options: [
                        { id: 'none', label: 'None', className: 'fa fa-times'},
                        { id: 'underline', label: 'underline', className: 'fa fa-underline' },
                        { id: 'line-through', label: 'Line-through', className: 'fa fa-strikethrough'}
                      ],
                    },
                    'text-shadow'
                ],
              },{
                name: 'Decorations',
                open: false,
                properties: [
                  'opacity',
                  'border-radius',
                  'border',
                  'box-shadow',
                  'background', // { id: 'background-bg', property: 'background', type: 'bg' }
                ],
              },{
                name: 'Extra',
                open: false,
                buildProps: [
                  'transition',
                  'perspective',
                  'transform'
                ],
              },{
                name: 'Flex',
                open: false,
                properties: [{
                  name: 'Flex Container',
                  property: 'display',
                  type: 'select',
                  defaults: 'block',
                  list: [
                    { value: 'block', name: 'Disable'},
                    { value: 'flex', name: 'Enable'}
                  ],
                },{
                  name: 'Flex Parent',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Direction',
                  property: 'flex-direction',
                  type: 'radio',
                  defaults: 'row',
                  list: [{
                    value: 'row',
                    name: 'Row',
                    className: 'icons-flex icon-dir-row',
                    title: 'Row',
                  },{
                    value: 'row-reverse',
                    name: 'Row reverse',
                    className: 'icons-flex icon-dir-row-rev',
                    title: 'Row reverse',
                  },{
                    value: 'column',
                    name: 'Column',
                    title: 'Column',
                    className: 'icons-flex icon-dir-col',
                  },{
                    value: 'column-reverse',
                    name: 'Column reverse',
                    title: 'Column reverse',
                    className: 'icons-flex icon-dir-col-rev',
                  }],
                },{
                  name: 'Justify',
                  property: 'justify-content',
                  type: 'radio',
                  defaults: 'flex-start',
                  list: [{
                    value: 'flex-start',
                    className: 'icons-flex icon-just-start',
                    title: 'Start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-just-end',
                  },{
                    value: 'space-between',
                    title: 'Space between',
                    className: 'icons-flex icon-just-sp-bet',
                  },{
                    value: 'space-around',
                    title: 'Space around',
                    className: 'icons-flex icon-just-sp-ar',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-just-sp-cent',
                  }],
                },{
                  name: 'Align',
                  property: 'align-items',
                  type: 'radio',
                  defaults: 'center',
                  list: [{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value: 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                },{
                  name: 'Flex Children',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Order',
                  property: 'order',
                  type: 'integer',
                  defaults: 0,
                  min: 0
                },{
                  name: 'Flex',
                  property: 'flex',
                  type: 'composite',
                  properties  : [{
                    name: 'Grow',
                    property: 'flex-grow',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Shrink',
                    property: 'flex-shrink',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Basis',
                    property: 'flex-basis',
                    type: 'integer',
                    units: ['px','%',''],
                    unit: '',
                    defaults: 'auto',
                  }],
                },{
                  name: 'Align',
                  property: 'align-self',
                  type: 'radio',
                  defaults: 'auto',
                  list: [{
                    value: 'auto',
                    name: 'Auto',
                  },{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value   : 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value   : 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value   : 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                }]
              }
            ],
        },
        plugins: [
          'gjs-blocks-basic',
          'grapesjs-plugin-forms',
          'grapesjs-component-countdown',
          'grapesjs-plugin-export',
          'grapesjs-tabs',
          'grapesjs-custom-code',
          'grapesjs-touch',
          'grapesjs-parser-postcss',
          'grapesjs-tooltip',
          'grapesjs-tui-image-editor',
          'grapesjs-typed',
          'grapesjs-style-bg',
          'grapesjs-preset-webpage',
        ],
        pluginsOpts: {
          'gjs-blocks-basic': { flexGrid: true },
          'grapesjs-tui-image-editor': {
            script: [
              // 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js',
              'https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js',
              'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js',
              'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js'
            ],
            style: [
              'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css',
              'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css',
            ],
          },
          'grapesjs-tabs': {
            tabsBlock: { category: 'Extra' }
          },
          'grapesjs-typed': {
            block: {
              category: 'Extra',
              content: {
                type: 'typed',
                'type-speed': 40,
                strings: [
                  'Text row one',
                  'Text row two',
                  'Text row three',
                ],
              }
            }
          },
          'grapesjs-preset-webpage': {
            modalImportTitle: 'Import Template',
            modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
            modalImportContent: function(editor) {
              return editor.getHtml() + '<style>'+editor.getCss()+'</style>'
            },
          },
        },
      });

      editor.I18n.addMessages({
        en: {
          styleManager: {
            properties: {
              'background-repeat': 'Repeat',
              'background-position': 'Position',
              'background-attachment': 'Attachment',
              'background-size': 'Size',
            }
          },
        }
      });
	  var pn = editor.Panels;
      var modal = editor.Modal;
      var cmdm = editor.Commands;

      // Update canvas-clear command
      cmdm.add('canvas-clear', function() {
        if(confirm('Are you sure to clean the canvas?')) {
          editor.runCommand('core:canvas-clear')
          setTimeout(function(){ localStorage.clear()}, 0)
        }
      });

      // Add info command
      var mdlClass = 'gjs-mdl-dialog-sm';
      var infoContainer = document.getElementById('info-panel');

      cmdm.add('open-info', function() {
        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
        mdlDialog.className += ' ' + mdlClass;
        infoContainer.style.display = 'block';
        modal.setTitle('About this demo');
        modal.setContent(infoContainer);
        modal.open();
        modal.getModel().once('change:open', function() {
          mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
        })
      });

      pn.addButton('options', {
        id: 'open-info',
        className: 'fa fa-question-circle',
        command: function() { editor.runCommand('open-info') },
        attributes: {
          'title': 'About',
          'data-tooltip-pos': 'bottom',
        },
      });


      // Simple warn notifier
      var origWarn = console.warn;
      toastr.options = {
        closeButton: true,
        preventDuplicates: true,
        showDuration: 250,
        hideDuration: 150
      };
      console.warn = function (msg) {
        if (msg.indexOf('[undefined]') == -1) {
          toastr.warning(msg);
        }
        origWarn(msg);
      };


      // Add and beautify tooltips
      [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
       ['export-template', 'Export'], ['undo', 'Undo'], ['redo', 'Redo'],
       ['gjs-open-import-webpage', 'Import'], ['canvas-clear', 'Clear canvas']]
      .forEach(function(item) {
        pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
      });
      [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
      .forEach(function(item) {
        pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
      });
      var titles = document.querySelectorAll('*[title]');

      for (var i = 0; i < titles.length; i++) {
        var el = titles[i];
        var title = el.getAttribute('title');
        title = title ? title.trim(): '';
        if(!title)
          break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
      }
{/literal}

</script>

<style>
body {
	margin: 0;
	padding: 0;
	}
html {
	overflow: scroll;
	overflow-x: hidden;
	-ms-overflow-style: none;
	scrollbar-width: none;
	}

::-webkit-scrollbar {
	display: none;
	}
.gjs-block-label {
	font-size: 12px;
}
.gjs-title {
	font-size: 12px;
}
.gjs-traits-label {
	font-size: 12px;

}
.gjs-clm-label {
	font-size: 12px;
}
.gjs-sm-sector-label{
	font-size: 12px;
}
span {
	font-size: 12px;

}
</style>
	{/if}
{*{/if}*}