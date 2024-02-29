AspenDiscovery.GrapesJS = function () {
    //noinspection JSUnusedGlobalSymbols
    return {
        editors: [],
        getBlockValuesForSource: function () {
            var portalCellId = $("#id").val();
            var sourceType = $("#sourceTypeSelect").val();
            if (sourceType === 'grapesjs') {
				$('#propertyRowmarkdown').show();
				$('#propertyRowsourceInfo').show();
				$("#propertyRowsourceId").show();
				$('#propertyRowframeHeight').show();
				$('#propertyRowimageURL').hide();
				$('#propertyRowimgAction').hide();
				$('#propertyRowimgAlt').hide();
				$('#propertyRowpdfView').hide();
            } else if (sourceType === 'iframe') {
                $('#propertyRowmarkdown').hide();
				$('#propertyRowsourceInfo').show();
				$("#propertyRowsourceId").hide();
				$('#propertyRowframeHeight').show();
				$('#propertyRowimageURL').hide();
				$('#propertyRowimgAction').hide();
				$('#propertyRowimgAlt').hide();
				$('#propertyRowpdfView').hide();
            }
        },
        


        updateGrapesJSFields: function () {
            var requireLogin = $('#requireLogin');
            if(requireLogin.is(":checked")) {
                $("#propertyRowallowAccess").show();
                $("#propertyRowrequireLoginUnlessInLibrary").show();
            } else {
                $("#propertyRowallowAccess").hide();
                $("#propertyRowrequireLoginUnlessInLibrary").hide();
            }

            $($requireLogin).click(function() {
                if(requireLogin.is(":checked")){
                    $("#propertyRowallowAccess").show();
                    $("#propertyRowrequireLoginUnlessInLibrary").show();
                } else {
                    $("#propertyRowallowAccess").hide();
                    $("#propertyRowrequireLoginUnlessInLibrary").hide();
                }
            });
        },
    }
} (AspenDiscovery.GrapesJS || {});