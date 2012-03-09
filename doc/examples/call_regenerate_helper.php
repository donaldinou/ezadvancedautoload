<?php 
use extension\ezadvancedautoload\helpers\templateAutoloadGeneratorHelper as autoloadHelper;
use extension\ezadvancedautoload\classes\exceptions\unexpectedModeException;

$tpl = \eZTemplate::factory();
try {
	autoloadHelper::regenerate(eZAutoloadGenerator::KERNEL_OVERRIDE|eZAutoloadGenerator::EXTENSION, $tpl);
} catch (unexpectedModeException $ume) {
	eZDebug::writeError($ume->getMessage());
} catch (\Exception $e) {
	eZDebug::writeError($e->getMessage());
}

?>