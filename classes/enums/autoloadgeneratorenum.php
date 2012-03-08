<?php
namespace extension\ezadvancedautoload\classes\enums {

	/**
	 * @brief Enumeration class for autoload generator
	 * @details Classe witch define all enumeration use for build autoload
	 * This class will need to extends <a href="http://php.net/manual/fr/book.spl-types.php">SplEnum</a> soon
	 * It's a final class because there is no need to extends it; 
	 * It should manage all available parameter for autoload generation with eZPublish
	 * 
	 * @author Adrien Loyant <adrien.loyant@te-laval.fr>
	 * 
	 * @date 2012-03-01
	 * @version 1.0.0
	 * @since 1.0.0
	 * @copyright GNU Public License v.2
	 * 
	 * @package extension\ezadvancedautoload\classes\enums
	 */
	final class autoloadGeneratorEnum {
		
		/**
		 * @brief default value
		 * @details this is the default value of this enumeration
		 * @var int
		 */
		const __default = self::EXTENSION;
		
		/**
		 * @brief Kernel constant enumeration
		 * @details Kernel constant enumeration. Use for build kernel autoload only
		 * 
		 * @var int
		 */
		const KERNEL = 1;
		
		/**
		 * @brief Kernel override constant enumeration
		 * @details Kernel override constant enumeration. Use for build kernel override autoload only
		 * 
		 * @var int
		 */
		const KERNEL_OVERRIDE = 2;
		/**
		 * @brief Extension constant enumeration
		 * @details Extension constant enumeration. Use for build extension autoload only
		 * 
		 * @var int
		 */
		const EXTENSION = 3;
		/**
		 * @brief Test constant enumeration
		 * @details Test constant enumeration. Use for build test autoload only
		 * 
		 * @var int
		 */
		const TEST = 4;
		
	}
	
}
?>