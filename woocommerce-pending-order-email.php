<?php
/*
 Plugin Name: WooCommerce Custom Pending Email
 Plugin URI:
 Description: Sends email on Pending status
Author: flyberson
Author URI: https://github.com/flyberson/
Version: 0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function add_pending_order_woocommerce_email( $email_classes ) {

	// include our custom email class
	require_once( 'includes/class-wc-pending-order-email.php' );

	// add the email class to the list of email classes that WooCommerce loads
	$email_classes['WC_Pending_Order_Email'] = new WC_Pending_Order_Email();

	return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'add_pending_order_woocommerce_email' );

//send mail på aventer betaling
add_action('woocommerce_order_status_changed', 'pending_status_custom_notification', 10, 4);
function pending_status_custom_notification( $order_id, $from_status, $to_status, $order ) {

	if( $order->has_status( 'pending' )) {

		// Getting all WC_emails objects
		$email_notifications = WC()->mailer()->get_emails();

		// Customizing Heading and subject In the WC_email processing Order object
		//$email_notifications['WC_Expedited_Order_Email']->heading = __('Aventer Betaling','woocommerce');
		//$email_notifications['WC_Expedited_Order_Email']->subject = 'Aventer betaling';

		// Sending the customized email
		$email_notifications['WC_Pending_Order_Email']->trigger( $order_id );
	}

}


add_filter( 'woocommerce_email_actions', 'filter_woocommerce_email_actions' );
function filter_woocommerce_email_actions( $actions ){
	$actions[] = 'woocommerce_order_status_pending';
	return $actions;
}