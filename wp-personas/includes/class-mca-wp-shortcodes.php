<?php
/**
 * Mca_Wp_Shortcodes class.
 *
 * Loads and manages MCA WP shortcodes.
 *
 * @class       Mca_Wp_Shortcodes
 * @package     Mca_Wp/Classes
 * @subpackage  Shortcodes
 * @category    Class
 * @author      Justin Carver / Adquisitions
 */
class Mca_Wp_Shortcodes {

    /**
     * Init shortcodes
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function __construct() {
        add_shortcode( 'mca_d', array( &$this, 'database_echo' ) );
        add_shortcode( 'mca_u', array( &$this, 'url_echo' ) );
    } // END __construct()

    /**
     * Echos a Persona detail from the database.
     * 
     * The first key in $atts is used as the Persona detail to be queried. 
     * The second key in $atts is uesd as the default Persona ID in the event
     * that one is not given one in the URL.
     * This method powers the mca_d shortcode and the mca_wp_d() funciton.
     *
     * @since 1.0.0
     * @access public
     * @param mixed $atts
     * @return string To be echoed via shortcode or external call.
     */
    public static function database_echo( $atts ) {
        // Determine Persona detail to be queried
        if ( isset( $atts[0] ) ) {
            $persona_detail = $atts[0];

            // Determine if we are querying a MCA WP default detail
            if ( mca_wp_is_string_int( $persona_detail )  
                 && in_array( (int) $persona_detail, range( 1, 3 ) ) ) {
                $persona_detail = 'mca_wp_default_persona_detail_' . $persona_detail;
            } // END if
        } else {
            $persona_detail = 0; // Last resort
        } // END if/else

        // Determine if a default Persona id is set
        if ( isset( $atts[1] ) ) {
            $default_persona = $atts[1];

            // Determine if default is a slug and check validity
            if ( !mca_wp_is_string_int( $default_persona ) 
                 && mca_wp_slug_to_id( $default_persona ) ) {
                $default_persona = mca_wp_slug_to_id( $default_persona );
            } // END if
        } else {
            $default_persona = 0; // Last resort
        } // END if/else

        // Determine Persona to be queried
        if ( isset( $_GET["pid"] ) ) {
            $persona = $_GET["pid"];
        } else {
            $persona = $default_persona;
        } // END if/else

        // Attempt to get detail and return
        $result = get_field( $persona_detail, $persona );

        if ( $result ) {
            return $result;
        } else {
            return ""; // Purposefully empty
        } // END if/else
    } // END database_echo()

    /**
     * Echos a $_GET variable from the URL.
     * 
     * The first key in $atts is used as the $_GET variable to be retrieved.
     * The second key in $atts is uesd as the default output in the event
     * that our variable is not present in the URL.
     * This method powers the mca_u shortcode and the mca_wp_u() funciton.
     * 
     * @since 1.0.0
     * @access public
     * @param mixed $atts
     * @return string To be echoed via shortcode or external call.
     */
    public static function url_echo( $atts ) {
        // Return requested $_GET variable if set
        if ( isset( $atts[0] ) ) {
            $url_field = $atts[0];

            // Prevent XSS and return $_GET variable
            if ( isset( $_GET[$url_field] ) ) {
                return filter_var( $_GET[$url_field], FILTER_SANITIZE_STRING );
            } // END if
        }  // END if
        if ( isset($atts[1]) ) {
            // Return defalut if set
            return $atts[1];
        } else {
            return ""; // Purposefully empty
        } // END if/else
    } // END url_echo()

} // END Mca_Wp_Shortcodes()
new Mca_Wp_Shortcodes();

/**
 * Function interface for Mca_Wp_Shortcodes::database_echo().
 *
 * This function serves as the primary way to integrate MCA WP's 
 * Persona functionality into themes, templates, and plugins.
 *
 * @since 1.0.0
 * @return string
 * @param string $persona_detail Persona detail to be echoed.
 * @param string $default_persona Optional. Persona to use if lacking a Persona ID.
 */
function mca_wp_d( $persona_detail, $default_persona = 0 ) {
    $atts = array( $persona_detail, $default_persona );
    echo Mca_Wp_Shortcodes::database_echo( $atts );
} // END mca_wp_d()

/**
 * Function interface for Mca_Wp_Shortcodes::url_echo().
 *
 * This function serves as the primary way to integrate MCA WP's 
 * URL functionality into themes, templates, and plugins.
 *
 * @since 1.0.0
 * @return string
 * @param string $url_detail Variable from URL to be echoed.
 * @param string $default_output Optional. Echoed should we need a fallback.
 */
function mca_wp_u( $url_detail, $default_output = '' ) {
    $atts = array( $url_detail, $default_output );
    echo Mca_Wp_Shortcodes::url_echo( $atts );
} // END mca_wp_u()
?>
