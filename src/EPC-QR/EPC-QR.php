<?php

defined( 'ABSPATH' ) or die( );

use chillerlan\QRCode\{QROptions, QRCode};
require_once("includes/qr/vendor/autoload.php");
require_once("settings-tab.php");


/*
Plugin Name: EPC-QR-codes for banktransfer
Plugin URI:  https://github.com/GruberViktor/EPC-QR
Description: Create EPC-Codes for money transfer
Version:     0.1
Author:      Viktor Gruber
Author URI:  -
License:     MIT
 
EPC-QR-code is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

function EPC_QR_in_thank_you_text( $text, $order) {

    if( ! is_wc_endpoint_url( 'order-received' ) )
        return; // Exit

    global $wp;
    // Get the order info
    $order_id  = absint( $wp->query_vars['order-received'] );
    $order = new WC_Order( $order_id );
    $ordernr = $order->get_order_number();	
    $payment_method = $order->get_payment_method();

	if ( $payment_method == 'bacs'){

        //QR-code Options
        $options = new QROptions([
            'version'       => QRCode::VERSION_AUTO,
            'outputType'    => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'      => QRCode::ECC_M,
            'addQuietzone'  => TRUE,
            'quietzoneSize' => 3,
            'scale'         => 5,
            'dataMode'      => 'Byte'
            ]);
        $QRCODE = new QRCode($options);
        
        $name = get_option ( 'wc_epc_qr_name' );
        $bic = get_option ( 'wc_epc_qr_bic' );
        $IBAN = get_option ( 'wc_epc_qr_iban' );
        $amount = 'EUR' . strval( $order->get_total() );
        $purpose = str_replace( '[ordernumber]', strval( $order->get_order_number() ), get_option( 'wc_epc_qr_text') );

        $data = "BCD\n001\n1\nSCT\n".$bic."\n".$name."\n".$IBAN."\n".$amount."\n\n\n".$purpose;
        
        $text  = '<center><h1>' . get_option( 'wc_epc_qr_thankyou_title' ) . '</h1></center>';
        $text .= '<p>' . get_option( 'wc_epc_qr_explanation_text_above' ) . '</p>';
        $text .= '<center><img class="qrcode" src="'.$QRCODE->render($data).'" /></center>';
        $text .= '<p>' . get_option( 'wc_epc_qr_explanation_text_below' ) . '</p>';
}

    return $text;
}

if ( get_option( 'wc_epc_qr_enabled_thank_you') == 'yes' ) {
    add_filter( 'woocommerce_thankyou_order_received_text', 'EPC_QR_in_thank_you_text', 10, 2);
}



function EPC_QR_invoice( $order ) {

    $ordernr = $order->get_order_number();	
    $payment_method = $order->get_payment_method();

	if ( $payment_method == 'bacs'){

        //QR-code Options
        $options = new QROptions([
            'version'       => QRCode::VERSION_AUTO,
            'outputType'    => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'      => QRCode::ECC_M,
            'addQuietzone'  => TRUE,
            'quietzoneSize' => 3,
            'scale'         => 2,
            'dataMode'      => 'Byte'
            ]);
        $QRCODE = new QRCode($options);
        
        $name = get_option ( 'wc_epc_qr_name' );
        $bic = get_option ( 'wc_epc_qr_bic' );
        $IBAN = get_option ( 'wc_epc_qr_iban' );
        $amount = 'EUR' . strval( $order->get_total() );
        $purpose = str_replace( '[ordernumber]', strval( $order->get_order_number() ), get_option( 'wc_epc_qr_text') );

        $data = "BCD\n001\n1\nSCT\n".$bic."\n".$name."\n".$IBAN."\n".$amount."\n\n\n".$purpose;
        
        $text = '<table>
                <tbody>
                <tr>
                    <td><img class="" src="'.$QRCODE->render($data).'" > </td>
                    <td style="vertical-align: middle;text-align: justify">'.get_option( 'wc_epc_qr_invoice_text_right' ).'</td>
                </tr>
                </tbody>
                </table>';
}

    return $text;
}