<?php
/**
* @class FormFieldCheckbox
*
* This is a helper class to generate a checkbox form field.
*
* @author Leano Martinet <info@asterion-cms.com>
* @package Asterion
* @version 3.0.1
*/
class FormField_Select2 extends FormField_DefaultSelect {

    /**
    * The constructor of the object.
    */
    public function __construct($options) {
        parent::__construct($options);
        $this->options['class'] = 'select2';
    }

    /**
    * Render the element with an static function.
    */
    static public function create($options) {
        $options['class'] = 'select2';
        return FormField_DefaultSelect::create($options);
    }

}
?>