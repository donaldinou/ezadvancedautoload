<?php
namespace extension\ezadvancedautoload\classes\helpers {

    use extension\ezextrafeatures\classes\helpers\Helper;

    // Start requiring classes. Needed if it's first autoload run
    if (!class_exists('extension\\ezextrafeatures\\classes\\helpers\\Helper')) {
        require_once( __DIR__ .'/../../../../extension/ezadvancedautoload/classes/helpers/helper.php' );
    }
    //

    /**
     * @brief Helper for advanced autoload extension
     * @details Helper for advanced autoload extension
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
    class advancedAutoloadHelper extends Helper {

        /**
         * @brief return true if finer filter is enable
         * @details return true if finer filter is enabled in autoload.ini
         *
         * @return boolean
         */
        public static function isFinerFilterEnabled() {
            $result = false;
            $ini = \eZINI::instance( 'autoload.ini' );
            if ( $ini instanceof \eZINI && $ini->hasVariable( 'Settings', 'FinerFilterAutoload' ) ) {
                $filter = $ini->variable( 'Settings', 'FinerFilterAutoload' );
                if ($filter == 'enabled') {
                    $result = true;
                } else {
                    $result = filter_var($filter, FILTER_VALIDATE_BOOLEAN);
                }
            }
            return $result;
        }

        /**
         * @brief Return the extension name to the path
         * @details Return the name of the ezpublish extension to the path
         *
         * @param string $path
         * @return string
         */
        public static function getExtensionName( $path ) {
            // BUFIX : because of fetchFiles methods
            $path = (DIRECTORY_SEPARATOR == '/') ? $path : strtr( $path, DIRECTORY_SEPARATOR, '/' );

            $arrayPath = explode( '/', $path );
            $arrayPathReversed = array_flip(array_reverse($arrayPath, true));
            $extensionName = null;
            if ( isset($arrayPathReversed['extension']) ) {
                $nextKey = ++$arrayPathReversed['extension'];
                $extensionName = (isset($arrayPath[$nextKey])) ? $arrayPath[$nextKey] : null;
            }
            return $extensionName;
        }

    }

}
