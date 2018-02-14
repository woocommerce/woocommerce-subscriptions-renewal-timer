<?php
/**
 * Plugin Name: WooCommerce Subscriptions Renewal Timer
 * Plugin URI: https://github.com/prospress/woocommerce-subscriptions-renewal-timer
 * Description: Log the beginning and end of subscription renewal events. To view the log file: Go to WooCommerce > System Status > Logs and select the log file with the `'wcs-renewal'` prefix.
 * Author: Prospress Inc.
 * Author URI: http://prospress.com/
 * Version: 1.0
 *
 * Copyright 2017 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class WCS_Renewal_Timer {

	private $logger;

	private $process_id;

	private $counter = 1;

	protected $start_time = 0;

	public function __construct() {

		$this->process_id = getmypid();

		add_action( 'woocommerce_loaded', array( $this, 'load_logger' ) );

		add_action( 'woocommerce_scheduled_subscription_payment', array( $this, 'start_timer_on_first_renewal' ), -100 );

		add_action( 'woocommerce_scheduled_subscription_payment', array( $this, 'log_renewal_start' ), 0 );

		add_action( 'woocommerce_scheduled_subscription_payment', array( $this, 'log_renewal_order_created' ), 2 );

		add_action( 'woocommerce_scheduled_subscription_payment', array( $this, 'log_renewal_payment_processed' ), 11 );

		// Offer a generic hook for anything to log a message (by calling do_action( 'woocommerce_renewal_log', $message ); )
		add_action( 'woocommerce_renewal_log', array( $this, 'log' ), 10, 1 );
	}

	public function start_timer_on_first_renewal() {
		if ( 0 === $this->start_time ) {
			$this->start_timer();
		}
	}

	public function log_renewal_start( $subscription_id ) {
		$this->log( sprintf( 'Subscription %d renewal begins.', $subscription_id ) );
	}

	public function log_renewal_order_created( $subscription_id ) {
		$this->log( sprintf( 'Subscription %d renewal order creation completed.', $subscription_id ) );
	}

	public function log_renewal_payment_processed( $subscription_id ) {
		$this->log( sprintf( 'Subscription %d renewal payment gateway hook completed.', $subscription_id ) );
		$this->counter++;
	}

	/**
	 * Wrapper function around WC_Logger->log to always use our special log file name
	 * and prefix all log entries with the process ID to match up different queues of
	 * renewals being processed.
	 *
	 * @param string $message Message to log
	 */
	public function log( $message ) {
		$log_message = sprintf( 'PID %d: Renewal %d. %s (MEMORY USAGE: %s. TIME ELAPSED: %d seconds)', $this->process_id, $this->counter, $message, $this->get_memory_usage(), $this->get_seconds_elapsed() );
		$this->logger->add( 'wcs-renewal', $log_message );
		error_log( $log_message );
	}

	/**
	 * Return a string display of the memory usage.
	 */
	protected function get_memory_usage() {

		$size = memory_get_usage();
		$unit = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );

		return @round( $size / pow( 1024,( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $unit[ $i ];
	}

	/**
	 * Return a string display of the memory usage.
	 */
	protected function start_timer() {
		$this->start_time = microtime( true );
	}

	/**
	 * Return a string display of the memory usage.
	 */
	protected function get_seconds_elapsed() {
		return microtime( true ) - $this->start_time;
	}

	/**
	 * Sets up the logging class
	 */
	public function load_logger() {
		$this->logger = new WC_Logger();
	}
}
new WCS_Renewal_Timer();