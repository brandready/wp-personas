<?php
/**
 * MCA WP Default Persona Group
 *
 * Constructs and registers default Persona group. 
 *
 * @author      Justin Carver / Adquisitions
 * @category    Core
 * @package     Mca_Wp/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Construct and register default Persona group.
 *
 * Constructs and registers the default group with three fields. 
 * Note that this function / file serves as a good base for introducing
 * more persona groups. Persona groups are actually ACF field groups,
 * so there are many options / possibilities available.
 *
 * @since 1.0.0
 * @access public
 */
function mca_wp_register_default_group() {
    $title = "Persona Details";
    if ( function_exists( "register_field_group" ) ) {
        register_field_group(array (
            'id' => 'acf_default-persona-details',
            'title' => 'Default Persona Details',
            'fields' => array (
                array (
                    'key' => 'mca_wp_default_persona_group_desc',
                    'label' => 'Persona Description',
                    'name' => 'persona_description',
                    'type' => 'textarea',
                    'instructions' => 'Enter a description of your Persona here.',
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '3',
                    'formatting' => 'br',
                ),
                array (
                    'key' => 'mca_wp_default_persona_detail_1',
                    'label' => 'Persona Detail 1',
                    'name' => 'mca_wp_default_persona_detail_1',
                    'type' => 'text',
                    'instructions' => '<input class="code" type="text" onfocus="this.select();" readonly="readonly" value="[mca_d 1]"> <input class="code" type="text" onfocus="this.select();" readonly="readonly" value="<?php mca_wp_d( \'1\' ); ?>">',
                    'formatting' => 'html',
                ),
                array (
                    'key' => 'mca_wp_default_persona_detail_2',
                    'label' => 'Persona Detail 2',
                    'name' => 'mca_wp_default_persona_detail_2',
                    'type' => 'text',
                    'instructions' => '<input class="code" type="text" onfocus="this.select();" readonly="readonly" value="[mca_d 2]"> <input class="code" type="text" onfocus="this.select();" readonly="readonly" value="<?php mca_wp_d( \'2\' ); ?>">',
                    'formatting' => 'html',
                ),
                array (
                    'key' => 'mca_wp_default_persona_detail_3',
                    'label' => 'Persona Detail 3',
                    'name' => 'mca_wp_default_persona_detail_3',
                    'type' => 'text',
                    'instructions' => '<input class="code" type="text" onfocus="this.select();" readonly="readonly" value="[mca_d 3]"> <input class="code" type="text" onfocus="this.select();" readonly="readonly" value="<?php mca_wp_d( \'3\' ); ?>">',
                    'formatting' => 'html',
                ),
            ),
           'location' => array (
                array (
                    array (
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'persona',
                        'order_no' => 0,
                        'group_no' => 0,
                    ),
                ),
            ),
            'options' => array (
                'position' => 'normal',
                'layout' => 'box',
                'hide_on_screen' => array (),
            ),
            'menu_order' => 0,
        ));
    } // END if
} // END mca_wp_register_default_group()
?>
