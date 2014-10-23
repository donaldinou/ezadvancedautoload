<?php
namespace extension\ezadvancedautoload\classes\exceptions {

    /**
     * @brief UnexpectedModeException class
     * @details UnexpectedModeException class for throwing exception when the mode for autoload generation is not correct
     *
     * @author Adrien Loyant <adrien.loyant@te-laval.fr>
     *
     * @date 2012-03-01
     * @version 1.0.0
     * @since 1.0.0
     * @copyright GNU Public License v.2
     *
     * @package extension\ezadvancedautoload\classes\exceptions
     */
    class unexpectedModeException extends \RuntimeException {

        /**
         * @brief the mode asked for autoload generation
         * @details the mode asked for autoload generation
         *
         * @var int
         */
        private $mode;

        /**
         * @brief unexpectedModeException constructor
         * @details unexpectedModeException constructor
         *
         * @param int $mode
         * @param int $code
         * @param \Exception $previous
         */
        public function __construct( $mode=null, $code=null, \Exception $previous=null ) {
            $message = 'An unexpected mode has been passed througth the object';
            $this->mode = $mode;
            if (!is_null($this->mode)) {
                $message .= ' [mode='.$this->mode.']';
            }
            parent::__construct( $message, $code, $previous );
        }

    }

}
