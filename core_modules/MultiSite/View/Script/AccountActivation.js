/**
 * MultiSite
 * @author: Thomas Däppen <thomas.daeppen@comvation.com>
 * @version: 1.0
 * @package: contrexx
 * @subpackage: coremodules_multisite
 */

/**
 * Position the account-activate-bar
 */
cx.ready(function() {
    // fetch the current vertical position of the body
    var toolbarOffset = parseInt(cx.jQuery("body").css("padding-top"));
    if (!toolbarOffset) {
        toolbarOffset = 0;
    }

    // position the body and the account-activation-bar
    cx.jQuery("body").css("padding-top", (parseInt(cx.jQuery("#MultiSiteAccountActivation").outerHeight()) + toolbarOffset) + "px");
    cx.jQuery("#MultiSiteAccountActivation").css({
        top: toolbarOffset + "px"
    });

    cx.jQuery('.AccountActivation').click(function() {
        domainUrl = cx.variables.get('cadminPath', 'contrexx') + "index.php?cmd=JsonData&object=MultiSite&act=sendAccountActivation";
        cx.jQuery.ajax({
            url: domainUrl,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                cx.jQuery('#MultiSiteAccountActivationMessage').html('<strong>' + response.data.message + '</strong>');
                
            }
        });
    });

});
