<?php
/**
 * Plugin Name: STJ WooCommerce Shipped Status
 * Plugin URI: https://tenfen.com.br/
 * Description: Displays Shipped Status in WooCommerce.
 * Version: 0.1
 * Author: Silvio Tenfen Junior
 * Author URI: https://tenfen.com.br/
 * Text Domain: stj_woocommerceshippedstatus
 * Domain Path: /languages/
 *
 * @package STJ WooCommerce Shipped Status
 */

class STJ_WooCommerceShippedStatus {

	public function __construct() {
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        add_action( 'init', array( $this, 'register_shipped_order_status' ) );
        add_filter( 'wc_order_statuses', array( $this, 'add_shipped_to_order_statuses' ) );

        add_filter( 'woocommerce_email_classes', array( $this, 'add_shipped_order_woocommerce_email' ), 90, 1 );
        define( 'STJ_WCSS_PATH', plugin_dir_path( __FILE__ ) );

        add_shortcode( 'order_history', array( $this, 'order_history_shortcode' ) );
	}

    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'stj_woocommerceshippedstatus', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
    }

    public function register_shipped_order_status() {
        register_post_status( 'wc-shipped', array(
            'label'                     => __('Shipped', 'stj_woocommerceshippedstatus'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop(
                __('Waiting to ship <span class="count">(%s)</span>', 'stj_woocommerceshippedstatus'),
                __('Waiting to ship <span class="count">(%s)</span>', 'stj_woocommerceshippedstatus')
            )
        ) );
    }

    public function add_shipped_to_order_statuses( $order_statuses ) {
        $new_order_statuses = array();
        // add new order status after processing
        foreach ( $order_statuses as $key => $status ) {
            $new_order_statuses[ $key ] = $status;
            if ( 'wc-processing' === $key ) {
                $new_order_statuses['wc-shipped'] = __('Shipped', 'stj_woocommerceshippedstatus');
            }
        }
        return $new_order_statuses;
    }

    public function add_shipped_order_woocommerce_email( $emails ) {
        require_once 'emails/class-wc-customer-shipped-order.php';
        $emails['WC_Customer_Shipped_Order'] = new WC_Customer_Shipped_Order();
        return $emails;
    }

    public function order_history_shortcode() {

        $this->template_html  = 'order-history.php';
        $this->template_base  = STJ_WCSS_PATH . 'templates/';

        if ( strpos($_SERVER['REQUEST_URI'], '/view-order/') > 0 ) {
            $url = str_replace('/view-order/', ';', $_SERVER['REQUEST_URI']);
            $exp = explode(';', $url);
            $order_id = isset($exp[1]) ? (int) $exp[1] : 0;

            if ( !$this->order = wc_get_order( $order_id ) ) {
                return;
            }

            wp_enqueue_style( 'stj_woocommerceshippedstatus_css', plugin_dir_url(__FILE__) . 'templates/assets/css/styles.css', null, '0.1');
            
            return wc_get_template_html( $this->template_html, array(
                'order'         => $this->order
            ), '', $this->template_base );
        }
    }
}

new STJ_WooCommerceShippedStatus();
?>
