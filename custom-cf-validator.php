<?php
/**
 * Plugin Name: Custom Caldera Forms Field Validator For Merchant Account Application
 */
add_filter('caldera_forms_get_form_processors', 'fms_cf_validator_processor');

/**
 * Add a custom processor for field validation
 *
 * @uses 'my_custom_cf_validator_processor'
 *
 * @param array $processors Processor configs
 *
 * @return array
 */
function fms_cf_validator_processor($processors){
    $processors['fms_cf_validator'] = array(
        'name' => __('Custom Validator', 'my-text-domain' ),
        'description' => '',
        'pre_processor' => 'fms_validator',
        'template' => dirname(__FILE__) . '/config.php'

    );

    return $processors;
}

/**
 * Run field validation
 *
 * @param array $config Processor config
 * @param array $form Form config
 *
 * @return array|void Error array if needed, else void.
 */
function fms_validator( array $config, array $form ){

    //Processor data object
    $data = new Caldera_Forms_Processor_Get_Data( $config, $form, fms_cf_validator_fields() );

    //Value of field to be validated
    $value = $data->get_value( 'fld_4177753' );

    //if not valid, return an error
    if( false == fms_cf_validator_is_valid( $value ) ){

        //get ID of field to put error on
        $fields = $data->get_fields();
        $field_id = $fields[ 'DBAName' ][ 'config_field' ];

        //Get label of field to use in error message above form
        $field = $form[ 'fields' ][ $field_id ];
        $label = $field[ 'label' ];

        //this is error data to send back
        return array(
            'type' => 'error',
            //this message will be shown above form
            'note' => sprintf( 'Please Correct %s', $label ),
            //Add error messages for any form field
            'fields' => array(
                //This error message will be shown below the field that we are validating
                $field_id => __( 'This field is invalid', 'text-domain' )
            )
        );
    }

    //If everything is good, don't return anything!

}


/**
 * Check if value is valid
 *
 * UPDATE THIS! Use your array of values, or query the database here.
 *
 * @return bool
 */
function fms_cf_validator_is_valid( $value ){
    return in_array( $value, array(
        'Han Solo',
        'Chewbacca',
        'Rey'
    ) );
}

/**
 * Processor fields
 *
 * @return array
 */
function fms_cf_validator_fields(){
    return array(
        array(
            'id' => 'fld_4177753',
            'type' => 'text',
            'required' => true,
            'magic' => true,
            'label' => __( 'Magic tag for field to validate.', 'my-text-domain' )
        ),
    );
}