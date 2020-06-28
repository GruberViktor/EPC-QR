<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_epc_qr {

    public function __construct() {
		$this->id    = 'epc_qr';
		$this->label = __( 'EPC-QR code', 'epc-qr-settings-tab' );

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_tab' ), 50 );
        add_action( 'woocommerce_sections_' . $this->id, array( $this, 'get_sections' ) );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    public function add_tab( $settings_tabs ) {
            $settings_tabs[$this->id] = __( 'EPC-QR code', 'epc-qr-settings-tab' );
            return $settings_tabs;
        }
    
    public function get_sections( ) {
        $sections = array(
            '' => __( 'Thank you Page', 'epc-qr-settings-tab' ),
            'invoice'  => __( 'Invoice', 'epc-qr-settings-tab' )
        );
        
        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }


    /**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::output_fields( $settings );
    }
    
    /**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public function get_settings( $current_section = '' ) {
            $settings = array(
                'section_title' => array(
                    'name'     => __( 'EPC-QR code generator', 'epc-qr-settings-tab' ),
                    'type'     => 'title',
                    'desc'     => 'Please be aware that this plugin is for EUR transfers only. 
                                    If the base currency in your shop is Pounds for example, it will lead to problems, as your customer would send you 10€, not 10£.
                                    
                                    The QR-code is only shown if the payment method of the order is bank transfer (bacs).',
                    'id'       => 'wc_epc_qr_section_title'
                ),
                'enable_thank_you' => array(
                    'name' => __( 'Enable on the "Thank You"-page?', 'epc-qr-settings-tab' ),
                    'type' => 'checkbox',
                    'desc' => __( 'Enable', 'epc-qr-settings-tab' ),
                    'id'   => 'wc_epc_qr_enabled_thank_you'
                ),
                'name' => array(
                    'name' => __( 'Your Bank Details', 'epc-qr-settings-tab' ),
                    'type' => 'text',
                    'desc' => __( 'Name', 'epc-qr-settings-tab' ),
                    'id'   => 'wc_epc_qr_name'
                ),
                'bic' => array(
                    'name' => __( '', 'epc-qr-settings-tab' ),
                    'type' => 'text',
                    'desc' => __( 'BIC', 'epc-qr-settings-tab' ),
                    'id'   => 'wc_epc_qr_bic'
                ),
                'iban' => array(
                    'name' => __( '', 'epc-qr-settings-tab' ),
                    'type' => 'text',
                    'desc' => __( 'IBAN (without spaces)', 'epc-qr-settings-tab' ),
                    'id'   => 'wc_epc_qr_iban'
                ),
                'transfer_text' => array(
                    'name' => __( 'Transfer Text', 'epc-qr-settings-tab' ),
                    'type' => 'text',
                    'desc' => __( '[ordernumber] puts the order number into the text. Max. 140 characters incl. the number', 'epc-qr-settings-tab' ),
                    'default' => 'Order Nr. [ordernumber]',
                    'id'   => 'wc_epc_qr_text'
                ),
                'thank_you_title' => array(
                    'name' => __( 'Thank-you page title', 'epc-qr-settings-tab' ),
                    'type' => 'text',
                    'desc' => __( 'The title shown at the thank-you page'),
                    'default' => 'Thanks a lot for your order!',
                    'id'   => 'wc_epc_qr_thankyou_title'
                ),
                'explanation_text_above' => array(
                    'name' => __( 'Explanation Text above QR', 'epc-qr-settings-tab' ),
                    'type' => 'textarea',
                    'css'  => 'width: 600px; height: 100px',
                    'desc_tip' => __( 'The explanation your customer will see on the thank you page', 'epc-qr-settings-tab' ),
                    'default' => 'If you have a banking app, chances are it supports QR payment codes. Just scan this QR code with your app, and all the details for the bank transfer will be filled in automatically, you only need to confirm the payment.',
                    'id'   => 'wc_epc_qr_explanation_text_above'
                ),
                'explanation_text_below' => array(
                    'name' => __( 'Explanation Text below QR', 'epc-qr-settings-tab' ),
                    'type' => 'textarea',
                    'css'  => 'width: 600px; height: 100px',
                    'default' => 'If you prefer to enter the transfer manually, the necessary details are below.',
                    'id'   => 'wc_epc_qr_explanation_text_below'
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_epc_qr_section_end'
                ),

                'invoice_instructions' => array(
                    'name' => __('Invoice settings', 'epc-qr-settings-tab' ),
                    'type' => 'title',
                    'desc' => 'In order to put the code into your invoices, you will have to follow the instructions of your invoicing plugin regarding using your own templates.
                               Within this template (usually in the body.php file), you can insert the code "if (function_exists(EPC_QR_invoice)){ echo EPC_QR_invoice( $order ); }" - without the parantheses - wherever you want to, preferably after the notes. Check out <a href="'.plugins_url('/includes/screenshot.png', __FILE__).'">this screenshot</a> to get an idea.
                               ',
                    'id'   => 'wc_epc_qr_invoice_instructions'
                ),
                'invoice_text_right' => array(
                    'name' => __('Text to the right of the QR'),
                    'type' => 'textarea',
                    'css'  => 'width: 600px; height: 100px',
                    'default' => 'If you have a banking app, chances are it supports QR payment codes. Just scan this QR code with your app, and all the details for the bank transfer will be filled in automatically, you only need to confirm the payment.',
                    'id'   => 'wc_epc_qr_invoice_text_right'
                ),
                'section_end2' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_epc_qr_invoice_section_end'
                )
            );
        
        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
    }

}

return new WC_epc_qr;