<?php 
namespace extension\ezadvancedautoload\classes\helpers {
	
	use extension\ezadvancedautoload\pv\classes\eZAutoloadGenerator; // FIXME: private is a reserved keyword
	use extension\ezadvancedautoload\classes\enums\autoloadGeneratorEnum;
	use extension\ezadvancedautoload\classes\exceptions\unexpectedModeException;
	use extension\ezextrafeatures\classes\helpers\Helper;
	
	// Start requiring classes. Needed if it's first autoload run
	if (!class_exists('extension\\ezadvancedautoload\\pv\\classes\\eZAutoloadGenerator')) {
	    require_once( __DIR__ . '/../../../../extension/ezadvancedautoload/private/classes/ezautoloadgenerator.php' );
	}
	if (!class_exists('extension\\ezadvancedautoload\\classes\\enums\\autoloadGeneratorEnum')) {
	    require_once( __DIR__ . '/../../../../extension/ezadvancedautoload/classes/enums/autoloadgeneratorenum.php' );
	}
	if (!class_exists('extension\\ezadvancedautoload\\classes\\exceptions\\unexpectedModeException')) {
	    require_once( __DIR__ . '/../../../../extension/ezadvancedautoload/classes/exceptions/unexpectedModeException.php' );
	}
	if (!class_exists('extension\\ezextrafeatures\\classes\\helpers\\Helper')) {
	    require_once( __DIR__ . '/../../../../extension/ezadvancedautoload/classes/helpers/helper.php' );
	}
	// End
	
	/**
	 * @brief Helper which provide help to correctly build autoload file
	 * @details Helper which provide help to correctly build autoload file
	 * 
	 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
	 * 
	 * @date 2012-03-01
	 * @version 1.0.0
	 * @since 1.0.0
	 * @copyright GNU Public License v.2
	 * 
	 * @package extension\ezadvancedautoload\classes\helpers
	 */
	abstract class templateAutoloadGeneratorHelper extends Helper {
		
		/**
		 * @brief Regenerate autoload for defined options (mode)
		 * @details Regenerate autoload for for defined options (mode)
		 * 
		 * @param int $mode Type of regeneration. You can use | (pipe) to define more than one type
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 * @throws unexpectedModeException
		 * 
		 * @example ./../../doc/examples/call_regenerate_helper.php
		 */
		public static function regenerate($mode, \eZTemplate $template = null ) {
			if (is_int($mode)) {
				switch ($mode) {
					case autoloadGeneratorEnum::KERNEL:
						static::regenerateKernel($template);
						break;
							
					case autoloadGeneratorEnum::KERNEL_OVERRIDE:
						static::regenerateKernelOverride($template);
						break;
				
					case autoloadGeneratorEnum::EXTENSION:
						static::regenerateExtension($template);
						break;
				
					case autoloadGeneratorEnum::TEST:
						static::regenerateTest($template);
						break;
				
					default:
						// Do nothing;
						break;
				}
			} else {
				throw new unexpectedModeException( $mode );
			}
		}
		
		/**
		 * @brief Regenerate autoload for kernel only (ezp_kernel)
		 * @details Regenerate autoload for kernel only (ezp_kernel)
		 * 
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 */
		public static function regenerateKernel( \eZTemplate $template = null ) {
			$autoloadOptions = new \ezpAutoloadGeneratorOptions();
			$autoloadOptions->searchKernelFiles = true;
			$autoloadOptions->searchKernelOverride = false;
			$autoloadOptions->searchExtensionFiles = false;
			$autoloadOptions->searchTestFiles = false;
			$autoloadOptions->excludeDirs = array();
			static::runGenerate($autoloadOptions, $template);
		}
		
		/**
		 * @brief Regenerate autoload for kernel override only (ezp_override)
		 * @details Regenerate autoload for kernel override only (ezp_override)
		 * 
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 */
		public static function regenerateKernelOverride( \eZTemplate $template = null ) {
			$autoloadOptions = new \ezpAutoloadGeneratorOptions();
			$autoloadOptions->searchKernelFiles = false;
			$autoloadOptions->searchKernelOverride = true;
			$autoloadOptions->searchExtensionFiles = false;
			$autoloadOptions->searchTestFiles = false;
			$autoloadOptions->excludeDirs = array();
			static::runGenerate($autoloadOptions, $template);
		}
		
		/**
		 * @brief Regenerate autoload for extension only (ezp_extension)
		 * @details Regenerate autoload for extension only (ezp_extension)
		 * 
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 */
		public static function regenerateExtension( \eZTemplate $template = null ) {
			$autoloadOptions = new \ezpAutoloadGeneratorOptions();
			$autoloadOptions->searchKernelFiles = false;
			$autoloadOptions->searchKernelOverride = false;
			$autoloadOptions->searchExtensionFiles = true;
			$autoloadOptions->searchTestFiles = false;
			$autoloadOptions->excludeDirs = array();
			static::runGenerate($autoloadOptions, $template);
		}
		
		/**
		 * @brief Regenerate autoload for tests only (ezp_tests)
		 * @details Regenerate autoload for tests only (ezp_tests)
		 * 
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 */
		public static function regenerateTest( \eZTemplate $template = null ) {
			$autoloadOptions = new \ezpAutoloadGeneratorOptions();
			$autoloadOptions->searchKernelFiles = false;
			$autoloadOptions->searchKernelOverride = false;
			$autoloadOptions->searchExtensionFiles = false;
			$autoloadOptions->searchTestFiles = true;
			$autoloadOptions->excludeDirs = array();
			static::runGenerate($autoloadOptions, $template);
		}
		
		/**
		 * @brief launch the regeneration
		 * @details launch the regeneration
		 * 
		 * @param \ezpAutoloadGeneratorOptions $autoloadOptions the options to generate autoload
		 * @param \eZTemplate $template the template to set the debug if we are in graphical mode
		 * @return void
		 */
		private static function runGenerate( \ezpAutoloadGeneratorOptions $autoloadOptions, \eZTemplate $template = null ) {
			$autoloadGenerator = new eZAutoloadGenerator($autoloadOptions);
				
			try {
				$autoloadGenerator->buildAutoloadArrays();
					
				$messages = $autoloadGenerator->getMessages();
				foreach($messages as $message) {
					\eZDebug::writeNotice($message, 'eZAutoloadGenerator');
				}
					
				$warnings = $autoloadGenerator->getWarnings();
				foreach ($warnings as &$warning) {
					\eZDebug::writeWarning($warning, 'eZAutoloadGenerator');
						
					// For web output we want to mark some of the important parts of
					// the message
					$pattern = '@^Class\s+(\w+)\s+.* file\s(.+\.php).*\n(.+\.php)\s@';
					preg_match($pattern, $warning, $m);
						
					$warning = str_replace($m[1], '<strong>'.$m[1].'</strong>', $warning);
					$warning = str_replace($m[2], '<em>'.$m[2].'</em>', $warning);
					$warning = str_replace($m[3], '<em>'.$m[3].'</em>', $warning);
				}
					
				if ($template !== null) {
					$template->setVariable('warning_messages', $warnings);
				}
			}
			catch (Exception $e) {
				\eZDebug::writeError($e->getMessage());
			}
		}
	
	}
	
}


?>