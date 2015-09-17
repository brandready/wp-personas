<?php
/**
 * WP MCA Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author      Justin Carver / Adquisitions Inc.
 * @category    Core
 * @package     Mca_Wp/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Determine if string represents an integer value, return true or false.
 *
 * @since 1.0.0
 * @access public
 * @param string $string
 * @return boolean
 */
function mca_wp_is_string_int( $string ) {
    if ( (string)(int) $string === (string) $string ) {
        return true; 
    } else {
        return false;
    }
} // END mca_wp_is_string_int()

/**
 * Returns a slug's post ID.
 *
 * @since 1.0.0
 * @access public
 * @param string $slug
 * @return int Slug's post ID.
 * @return null If slug is invalid.
 */
function mca_wp_slug_to_id( $slug ) {
    $persona = get_page_by_path( $slug, OBJECT, 'persona' );

    if ( $persona ) {
        return $persona->ID;
    } else {
        return null;
    }
} // END mca_wp_slug_to_id()
?>
