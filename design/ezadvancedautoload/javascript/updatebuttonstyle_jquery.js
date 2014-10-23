/**
 * Manage update button for activeExtension in eZPublish BackOffice
 *
 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
 *
 * @date 2012-03-01
 * @version 1.0.0
 * @since 1.0.0
 * @copyright GNU Public License v.2
 *
 * @package extension\ezadvancedautoload\design\ezadvancedautoload\javascript
 */
(function( $ ) {
    jQuery(document).ready(function($) {
        var initialExtensionSettings = {};
        var extensionChecks = jQuery('[name=extensionform] :checkbox');

        function styleUpdateButton() {
            var b = jQuery('[name=ActivateExtensionsButton]:first');
            jQuery(extensionChecks).each( function(){
                if (initialExtensionSettings[this.value] !== this.checked) {
                    b.removeClass('button').addClass('defaultbutton');
                    return false;
                } else {
                    b.removeClass('defaultbutton').addClass('button');
                }
            });
        }

        jQuery(extensionChecks).each( function(){
            initialExtensionSettings[this.value] = this.checked;
        }).change(function(){styleUpdateButton();});
    });
})(jQuery);