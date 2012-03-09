<?php
// Start requiring classes. Needed if it's first autoload run
if (!class_exists('extension\\ezadvancedautoload\\classes\\helpers\\templateAutoloadGeneratorHelper')) {
	require_once('extension/ezadvancedautoload/classes/helpers/templateautoloadgeneratorhelper.php');
}
// End

use extension\ezadvancedautoload\classes\helpers\templateAutoloadGeneratorHelper as autoloadHelper;

$http = \eZHTTPTool::instance();
$tpl = \eZTemplate::factory();
$module = $Params['Module'];

$extensionDir = \eZExtension::baseDirectory();
$availableExtensionArray = \eZDir::findSubItems($extensionDir, 'dl');
$selectedExtensions = \eZExtension::activeExtensions();

// When the user clicks on "Apply changes" button in admin interface in the Extensions section
if($module->isCurrentAction('ActivateExtensions')) {
    $ini = eZINI::instance('module.ini');
    $oldModules = $ini->variable('ModuleSettings', 'ModuleList');
	
    $selectedExtensionArray = array();
    if($http->hasPostVariable('ActiveExtensionList')) {
        $selectedExtensionArray = $http->postVariable('ActiveExtensionList');
        if(!is_array($selectedExtensionArray)) {
        	$selectedExtensionArray = array($selectedExtensionArray);
        }
    }

    // The file settings/override/site.ini.append.php is updated like this:
    // - take the existing list of extensions from site.ini.append.php (to preserve their order)
    // - remove from the list the extensions that the user unchecked in the admin interface
    // - add to the list the extensions checked by the user in the admin interface, but to the end of the list
    $intersection = array_intersect($selectedExtensions, $selectedExtensionArray);
    $difference = array_diff($selectedExtensionArray, $selectedExtensions);
    $toSave = array_merge($intersection, $difference);
    $toSave = array_unique($toSave);

    // open settings/override/site.ini.append[.php] for writing
    $writeSiteINI = eZINI::instance('site.ini.append', 'settings/override', null, null, false, true);
    $writeSiteINI->setVariable('ExtensionSettings', 'ActiveExtensions', $toSave);
    $writeSiteINI->save('site.ini.append', '.php', false, false);
    eZCache::clearByTag('ini');

    eZSiteAccess::reInitialise();

    $ini = eZINI::instance('module.ini');
    $currentModules = $ini->variable('ModuleSettings', 'ModuleList');
    if($currentModules != $oldModules) {
        // ensure that evaluated policy wildcards in the user info cache
        // will be up to date with the currently activated modules
        eZCache::clearByID('user_info_cache');
    }

    autoloadHelper::regenerateExtension($tpl);
}

// Regenerate autoload extensions
elseif($module->isCurrentAction('GenerateAutoloadArrays')) {
    autoloadHelper::regenerateExtension($tpl);
}

// Regenerate autoload kernel overrides
else if($module->isCurrentAction('GenerateAutoloadOverrideArrays')) {
    autoloadHelper::regenerateKernelOverride($tpl);
}

$tpl->setVariable('available_extension_array', $availableExtensionArray);
$tpl->setVariable('selected_extension_array', $selectedExtensions);

$Result = array();
$Result['content'] = $tpl->fetch('design:setup/extensions.tpl');
$Result['path'] = array(
                        array(
                            'url' => false,
                            'text' => ezpI18n::tr('kernel/setup', 'Extension configuration')
                        )
                );

?>
