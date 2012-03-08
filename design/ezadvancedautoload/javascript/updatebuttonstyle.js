<script type="text/javascript">
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
</script>