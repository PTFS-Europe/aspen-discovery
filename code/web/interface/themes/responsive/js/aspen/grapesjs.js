AspenDiscovery.GrapesJS = function () {
    //noinspection JSUnusedGlobalSymbols
    return {
        editors: [],

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