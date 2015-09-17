<?php
/*
 * Plugin Name: MCA WP
 * Plugin URI: http://brandreadycontent.com/wp-personas
 * Description: A small WordPress Persona marketing framework to extend Hubspot functionality.
 *
 * Version: 2.0.3
 * Author: Brand Ready
 * Author Email: brandready@gmail.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WP-Personas is distributed under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * WP-Personas is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP-Personas.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @package WP-Personas
 * @author  Brand Ready
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Plugin_Name' ) ) {

/**
 * Main MCA WP Class
 *
 * @since 1.0.0
 */
class Mca_Wp {

    /**
     * The single instance of the class.
     *
     * @since  1.0.0
     * @access protected
     * @var    object
     */
    protected static $_instance = null;

    /**
     * Slug.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $plugin_slug = 'mca_wp';

    /**
     * Plugin Name.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $name = "MCA WP";

    /**
     * MCA WP Version.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $version = "1.0.0";

    /**
     * The minimum required WordPress version. 
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $wp_version_min = "3.5.0";

    /**
     * Minimum Permissions Required to manage MCA WP.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $manage_plugin = "manage_options";

    /**
     * Rather or not Advanced Custom Fields (ACF) is already loaded.
     *
     * @since  1.0.0
     * @access public
     * @var    boolean
     */
    public $acf = false;

    /**
     * Main MCA WP Instance
     *
     * Ensures only one instance of MCA WP is loaded or can be loaded.
     *
     * @since  1.0.0
     * @access public static
     * @see    Mca_Wp()
     * @return MCA WP instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new Mca_Wp;
        }
        return self::$_instance;
    } // END instance()

    /**
     * Throw error on object clone
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __clone() {
        // Cloning instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mca-wp' ), $this->version );
    } // END __clone()

    /**
     * Disable unserializing of the class
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __wakeup() {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mca-wp' ), $this->version );
    } // END __wakeup()

    /**
     * Class constructor
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct() {
        // Define constants
        $this->define_constants();

        // Check plugin requirements
        $this->check_requirements();

        // Add init
        add_action( 'init', array( &$this, 'init_mca_wp' ) );
    } // END __construct()

    /**
     * Define Constants
     *
     * @since  1.0.0
     * @access private
     */
    private function define_constants() {
        if ( ! defined( 'MCA_WP' ) )                    define( 'MCA_WP', $this->name );
        if ( ! defined( 'MCA_WP_FILE' ) )               define( 'MCA_WP_FILE', __FILE__ );
        if ( ! defined( 'MCA_WP_VERSION' ) )            define( 'MCA_WP_VERSION', $this->version );
        if ( ! defined( 'MCA_WP_VERSION_REQUIRE' ) )    define( 'MCA_WP_VERSION_REQUIRE', $this->wp_version_min );
        if ( ! defined( 'MCA_WP_PAGE' ) )               define( 'MCA_WP_PAGE',                 str_replace('_', '-', $this->plugin_slug) );
        if ( ! defined( 'MCA_WP_SCREEN_ID' ) )          define( 'MCA_WP_SCREEN_ID', strtolower( str_replace( ' ', '-', MCA_WP_PAGE ) ) );
        if ( ! defined( 'MCA_WP_SLUG' ) )               define( 'MCA_WP_SLUG', $this->plugin_slug );
    } // END define_constants()

    /**
     * Checks that the WordPress setup meets the plugin requirements.
     *
     * @since  1.0.0
     * @access private
     * @global string $wp_version
     * @return bool
     */
    private function check_requirements() {
        global $wp_version;

        if ( ! version_compare( $wp_version, MCA_WP_VERSION_REQUIRE, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'display_req_notice' ) );
            return false;
        }

        return true;
    } // END check_requirements()

    /**
     * Display the requirement notice.
     *
     * @since 1.0.0
     * @access static
     */
    static function display_req_notice() {
        echo '<div id="message" class="error"><p><strong>';
        echo sprintf( __('Sorry, %s requires WordPress ' . MCA_WP_VERSION_REQUIRE . ' or higher.    Please upgrade your WordPress setup', 'mca-wp'), MCA_WP );
        echo '</strong></p></div>';
    } // END display_req_notice()

    /**
     * Runs when the plugin is initialized.
     *
     * @since  1.0.0
     * @access public
     */
    public function init_mca_wp() {
        // Determine if ACF is already loaded
        $this->acf = class_exists( 'acf' );

        // Load local copy of ACF if needed
        if ( !$this->acf ) { 
            include_once( plugin_dir_path( __FILE__ ) . 'includes/acf/acf.php' );
            $this->register_scripts_and_styles();
        } 

        // Register Persona post type
        $this->register_persona_post_type();

        // Load core functions for the front and back end
        include_once( 'includes/mca-wp-core-functions.php' ); 

        // Load admin-only components
        if ( is_admin() ) {
            // Register default Persona field group
            include_once( 'includes/groups/mca-wp-default-group.php' ); 
            add_action( 'mca_wp_add_persona_group', 
                        'mca_wp_register_default_group' );

            // Add field groups to Persona post type
            do_action( 'mca_wp_add_persona_group' );
    
            // Register custom edit / update messages for Persona post type
            add_filter( 'post_updated_messages', 
                        array( &$this, 'persona_updated_messages' ) );

        // Load frontend-only components
        } else {
            // Register shortcodes
            include_once( 'includes/class-mca-wp-shortcodes.php' ); 
        } // END if/else
    } // END init_mca_wp()

    /**
     * Get the plugin url.
     *
     * @access public
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    } // END plugin_url()

    /**
     * Get the plugin path.
     *
     * @access public
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    } // END plugin_path()

    /**
     * Registers Persona post type in WordPress.
     *
     * @since  1.0.0
     * @access public
     */
    public function register_persona_post_type() {
        $labels = array(
            'name'                => _x( 'Personas', 'Post Type General Name', 'text_domain' ),
            'singular_name'       => _x( 'Persona', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'           => __( 'MCA WP Personas', 'text_domain' ),
            'name_admin_bar'      => __( 'Persona', 'text_domain' ),
            'parent_item_colon'   => __( 'Parent Persona:', 'text_domain' ),
            'all_items'           => __( 'All Personas', 'text_domain' ),
            'add_new_item'        => __( 'Add New Persona', 'text_domain' ),
            'add_new'             => __( 'Add New', 'text_domain' ),
            'new_item'            => __( 'New Persona', 'text_domain' ),
            'edit_item'           => __( 'Edit Persona', 'text_domain' ),
            'update_item'         => __( 'Update Persona', 'text_domain' ),
            'view_item'           => __( 'View Persona', 'text_domain' ),
            'search_items'        => __( 'Search Personas', 'text_domain' ),
            'not_found'           => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
        );
        $args = array(
            'label'               => __( 'persona', 'text_domain' ),
            'description'         => __( 'Post Type Description', 'text_domain' ),
            'labels'              => $labels,
            'supports'            => array( 'title' ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-groups',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
        );
        register_post_type( 'persona', $args );
    } // END register_persona_post_type()

    /**
     * Sets post edit / update messages for Persona post type in WordPress.
     *
     * @since  1.0.0
     * @access public
     * @global $post
     * @return array Messages to be used upon edit or update of a Persona.
     */
    public function persona_updated_messages( $messages ) {
        global $post;
        $post_id = $post->id;

        $messages['persona'] = array(
            0 => '', // Unused. Messages start at index 1
            1 => __( 'Persona updated.' ),
            2 => __( 'Custom field updated.' ),
            3 => __( 'Custom field deleted.' ),
            4 => __( 'Persona updated.' ),
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Persona restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => __( 'Persona published.' ),
            7 => __( 'Persona saved.' ),
            8 => __( 'Persona submitted.' ),
            9 => sprintf( __( 'Persona scheduled for: <strong>%1$s</strong>.' ),
              date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
            10 => __( 'Persona draft updated.' ),
        );

        return $messages;
    } // END persona_updated_messages()

    /**
     * Registers and enqueues stylesheets and javascripts
     * for the administration panel and the front of the site.
     *
     * @since  1.0.0
     * @access private
     */
    private function register_scripts_and_styles() {
        // Load ACF input stylesheets if needed
        if ( is_admin() ) {
            $this->load_file( $this->plugin_slug . 'admin-style-input',
                              '/includes/acf/css/input.css' );
        }
    } // END register_scripts_and_styles()
    
    /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @since  1.0.0
     * @access private
     * @param  string  $name        The ID to register with WordPress.
     * @param  string  $file_path   The path to the actual file.
     * @param  bool    $is_script Optional, argument for if the incoming file_path is a JavaScript source   file.
     * @param  array   $support   Optional, for requiring other javascripts for the source file you are     calling.
     * @param  string  $version   Optional, can match the version of the plugin or version of the source    file.
     * @global string  $wp_version
     */
    private function load_file( $name, $file_path, $is_script = false, $support = array(), $version = '' ) {
        global $wp_version;

        $url  = $this->plugin_url() . $file_path;
        $file = $this->plugin_path() . $file_path;

        if ( file_exists( $file ) ) {
            if ( $is_script ) {
                wp_register_script( $name, $url, $support, $version );
                wp_enqueue_script( $name );
            } else {
                wp_register_style( $name, $url );
                wp_enqueue_style( $name );
            } // END if/else
        } // END if

        // wp_enqueue_style( 'wp-color-picker' );
        if ( is_admin() && $wp_version >= '3.8' ) {
            wp_enqueue_style( 'dashicons' ); // Loads only in WordPress 3.8 and up.
        } 
    } // END load_file()
  
} // END Mca_Wp()

} // END class_exists('Mca_Wp')

/**
 * Returns the instance of Mca_Wp to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Plugin Name
 */
function Mca_Wp() {
    return Mca_Wp::instance();
} // END Mca_Wp()

// Global for backwards compatibility.
$GLOBALS['mca_wp'] = Mca_Wp();
/* shouts to MCA, The Sangha, Gautama Buddha */ ?>
