<?php
/*
Plugin Name: xDAC Company Registration / Login Forms
Description: xDAC Company Registration / Login Forms
Author: Dmytro Stepanenko
Version: 0.1
License: GPL
Text Domain: xdac-wp-company
*/
/*  Copyright 2018 Dmytro Stepanenko (email: dmytro.stepanenko.dev@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

add_action('admin_init', 'create_xdac_company_tables');
function create_xdac_company_tables() {
    global $wpdb;
    $query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( "xdac_companies" ) );
    /**
     * If this table doesn't exist, then it should be created
     */
    if ( $wpdb->get_var( $query ) != "xdac_companies" ) {
        $wpdb->query("CREATE TABLE IF NOT EXISTS `xdac_companies` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `link` VARCHAR(50) NOT NULL,
                `icapital` decimal(30,2) DEFAULT NULL,
                `email` VARCHAR(100) NOT NULL,
                `wallet` VARCHAR(255) NOT NULL,
                `key` VARCHAR(255) NOT NULL,
                `is_verity` VARCHAR(100) NULL DEFAULT NULL,
                `created_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY  (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    }

    $query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( "xdac_company_owners" ) );
    /**
     * If this table doesn't exist, then it should be created
     */
    if ( $wpdb->get_var( $query ) != "xdac_company_owners" ) {
        $wpdb->query("CREATE TABLE IF NOT EXISTS `xdac_company_owners` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
                `company_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
                `wallet` VARCHAR(255) NOT NULL,
                `contribution` decimal(30,2) DEFAULT NULL,
                `stake` tinyint(20) UNSIGNED NOT NULL DEFAULT '0',
                `created_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY  (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    }


    /**
     * If this table doesn't exist, then it should be created
     */
    if ( $wpdb->get_var( $query ) != "xdac_company_logs" ) {
        $wpdb->query("CREATE TABLE IF NOT EXISTS `xdac_company_logs` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
                `response` TEXT NOT NULL DEFAULT '',
                `created_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY  (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    }
}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if( !class_exists('XdacCompany') ):


    class XdacCompany {

        const PAGE_LOGIN_COMPANY = 'login-company';
        const PAGE_REGISTER_COMPANY = 'register-company';
        const PAGE_VERITY_EMAIL_COMPANY = 'verify-email-company';
        const PAGE_SEND_XDAC_COMPANY = 'send-xdac-company';
        const PAGE_CONFIRMATION_COMPANY = 'confirmation-company';

        /**
         * Complete data transfer version.
         *
         * @var string
         */
        public $version = '0.1';

        public $mainAccount = 'inita';

        public $amount = '100 XDAC';

        /**
         * The single instance of the class.
         *
         * @var XdacCompany
         * @since 0.1
         */
        protected static $_instance = null;

        /**
         * Notices (array)
         * @var array
         */
        public $notices = array();

        /**
         * Main XdacClient Instance.
         *
         * Ensures only one instance of XdacCompany is loaded or can be loaded.
         *
         * @static
         * @see cdt()
         * @return XdacCompany - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            $this->define_constants();
            $this->init_hooks();

            do_action( 'xdac_company_loaded' );

            add_action( 'init', array( $this, 'process_post') );


        }

        private function createAccount($link){

            global $wpdb;

            $message = 'Company successfully created';
            try{

                $url = 'http://localhost:3000/create-account/';

                $client = new Client([
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ]
                ]);

                $response = $client->post($url,
                    ['form_params' => ['company' => $link]]
                );

                $body = $response->getBody();
                $res = json_decode((string) $body);

                if(isset($res->error)) {
                    foreach ($res->error->details as $error) {
                        if(!empty($error->message)){
                            $message = $error->message . '<br />';
                        }
                    }
                } else {
                    $wpdb->query($wpdb->prepare("UPDATE `xdac_companies` SET `created_at`='" .date('Y-m-d H:i:s'). "' WHERE `link`='" . $link."'"));
                    $wpdb->query($wpdb->prepare("INSERT INTO `xdac_company_logs` (`user_id`, `response`, `created_at`)  VALUES(".get_current_user_id().", '".$_POST['response']."', '".date('Y-m-d H:i:s')."')"));
                }

            } catch (Exception $e) {
                $message = $e->getMessage();
            }



            return $message;
        }

        public function process_post(){
            if(!empty($_POST['xdac_company_form'])){
                switch ($_POST['xdac_company_form']){
                    case 'login-company':
                        $this->login();
                        break;
                    case 'register-company':
                        $this->registration();
                        break;
                    case 'verify-email-company':
                        $this->verifyEmail();
                        break;
                    case self::PAGE_SEND_XDAC_COMPANY:
                        $this->sendXdacCompany();
                        break;
                }
            }
        }

        public function login(){

        }

        public function sendXdacCompany(){


            header('Content-Type: application/json');

            $data = [];

            global $wpdb;

            $link = $_GET['link'];

            if(empty($link)) {
                $data['message'] = __("Company not found", 'xdac_wp_company');
                $data['status'] = 'error';
                $data['link'] =  (home_url('/' . self::PAGE_REGISTER_COMPANY));
                wp_send_json($data); wp_die();
            }

            $company = $wpdb->get_row( "SELECT * FROM `xdac_company_owners` LEFT JOIN `xdac_companies` ON `xdac_companies`.`id` = `xdac_company_owners`.`company_id` WHERE `user_id` = ".get_current_user_id()." AND `link`='{$link}'");

            if(!empty($company)) {
                if(is_null($company->created_at)) {
                    $data['message'] = $this->createAccount($company->link);
                    $data['status'] = 'successful';
                } else {
                    $data['status'] = 'error';
                    $data['message'] = __("Company already was created", 'xdac_wp_company');
                }
                $data['link'] =  (home_url('/' . self::PAGE_CONFIRMATION_COMPANY) . '?link=' . $link);
            } else {
                $data['message'] = __("Company not found", 'xdac_wp_company');
                $data['status'] = 'error';
                $data['link'] =  (home_url('/' . self::PAGE_REGISTER_COMPANY));
            }

            wp_send_json($data); wp_die();
        }

        public function registration() {
            global $wpdb;

            $companyName =   sanitize_text_field( $_POST['company_name'] );
            $companyLink  =   strtolower(sanitize_text_field( $_POST['company_link'] ));
            $email      =   sanitize_email( $_POST['email'] );

            if($this->registration_validation($companyName, $companyLink, $email)) {

                $token = md5(uniqid($email, true));

                $company = $wpdb->insert('xdac_companies', array(
                    'name' => $companyName,
                    'link' => $companyLink,
                    'icapital' => 0,
                    'email' => $email,
                    'wallet' => '',
                    'key' => '',
                    'is_verity' => $token
                ));

                if($company) {
                    $companyId = $wpdb->insert_id;

                    $ownwer = $wpdb->insert('xdac_company_owners', array(
                        'user_id' => get_current_user_id(),
                        'company_id' => $companyId,
                        'wallet' => '',
                        'contribution' => 0,
                        'stake' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ));

                    $this->xdac_registration_email($companyName, $companyLink, $email, $token);

                    wp_redirect(home_url(self::PAGE_VERITY_EMAIL_COMPANY) . '?link=' . $companyLink);
                    exit;
                }
            }
        }

        public function registration_validation($companyName, $companyLink, $email){

            global $wpdb;
            global $reg_errors;
            $reg_errors = new WP_Error();


            if ( empty($companyName) ) {
                $reg_errors->add( 'company_name', __('Company Name required', 'xdac_wp_company'));
            } elseif(strlen($companyName) > 255 ) {
                $reg_errors->add( 'company_name', __('Company Name must be less than 255', 'xdac_wp_company'));
            }

            if ( empty($companyLink) ) {
                $reg_errors->add( 'company_link', __('Company Link required', 'xdac_wp_company'));
            } else {
                if(strlen($companyLink) > 12) {
                    $reg_errors->add( 'company_link', __('Company Link must be less than 12', 'xdac_wp_company'));
                }
                if( !preg_match('/^[a-zA-Z1-9_-]*$/', $companyLink) ) {
                    $reg_errors->add( 'company_link', __('Invalid company link', 'xdac_wp_company'));
                } else {
                    if( $wpdb->get_var( "SELECT COUNT(*) FROM xdac_companies WHERE link = '$companyLink'") ) {
                        $reg_errors->add( 'company_link', __('This name already used', 'xdac_wp_company'));
                    }
                }
            }

            if ( !is_email( $email ) ) {
                $reg_errors->add( 'email', __('Email is not valid', 'xdac_wp_company'));
            }elseif ( $wpdb->get_var( "SELECT COUNT(*) FROM xdac_companies WHERE `email` = '$email'") ) {
                $reg_errors->add( 'email', __('Email already used', 'xdac_wp_company'));
            }

            return count($reg_errors->get_error_messages()) == 0;
        }

        private function xdac_registration_email($companyName, $companyLink, $email, $token){

            $message = __('
	            <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
			   <head>
				  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				  <meta
					 name="viewport" content="width=device-width, initial-scale=1.0">
				  <title>xDAC: Registration</title>
				  <style type="text/css">/* Client-specific Styles */
					 #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
					 body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; background-color: #f5f7fb;}
					 /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
					 .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
					 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
					 #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
					 img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
					 a img {border:none;}
					 .image_fix {display:block;}
					 p {margin: 0px 0px !important;}
					 table td {border-collapse: collapse;word-break: break-word;}
					 table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
					 a {color: #1155cc;text-decoration: none;text-decoration:none!important;}
					 /*STYLES*/
					 table[class=full] { width: 100%; clear: both; }
					 /*################################################*/
					 /*IPAD STYLES*/
					 /*################################################*/
					 @media only screen and (max-width: 640px) {
					 a[href^="tel"], a[href^="sms"] {
					 text-decoration: none;
					 color: #ffffff; /* or whatever your want */
					 pointer-events: none;
					 cursor: default;
					 }
					 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					 text-decoration: default;
					 color: #ffffff !important;
					 pointer-events: auto;
					 cursor: default;
					 }
					 table[class=devicewidth] {width: 440px!important;text-align:center!important;}
					 table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
					 table[class="sthide"]{display: none!important;}
					 img[class="bigimage"]{width: 420px!important;height:219px!important;}
					 img[class="col2img"]{width: 420px!important;height:258px!important;}
					 img[class="image-banner"]{width: 440px!important;height:106px!important;}
					 td[class="menu"]{text-align:center !important; padding: 0 0 10px 0 !important;}
					 td[class="logo"]{padding:10px 0 5px 0!important;margin: 0 auto !important;}
					 img[class="logo"]{padding:0!important;margin: 0 auto !important;}
					 }
					 /*##############################################*/
					 /*IPHONE STYLES*/
					 /*##############################################*/
					 @media only screen and (max-width: 480px) {
					 a[href^="tel"], a[href^="sms"] {
					 text-decoration: none;
					 color: #ffffff; /* or whatever your want */
					 pointer-events: none;
					 cursor: default;
					 }
					 .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					 text-decoration: default;
					 color: #ffffff !important;
					 pointer-events: auto;
					 cursor: default;
					 }
					 table[class=devicewidth] {width: 280px!important;text-align:center!important;}
					 table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
					 table[class="sthide"]{display: none!important;}
					 img[class="bigimage"]{width: 260px!important;height:136px!important;}
					 img[class="col2img"]{width: 260px!important;height:160px!important;}
					 img[class="image-banner"]{width: 280px!important;height:68px!important;}
					 }
				  </style>
			   </head>
			   <body style="background-color: #f5f7fb;">
				  <div
					 class="block">
					 <table
						width="100%" bgcolor="#f5f7fb" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
						<tbody>
						   <tr>
							  <td>
								 <table
									width="960" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" hlitebg="edit" shadow="edit">
									<tbody>
									   <tr>
										  <td align="center" bgcolor="#292c3b" width="100%" height="20"><img src="https://www.xdac.co/wp-content/uploads/2018/03/xDAC-logo_800x300.png" alt="xDAC-logo_800x300" width="156" /></td>
									   </tr>
									   <tr>
										  <td>
											 <table
												width="450" cellpadding="0" cellspacing="0" border="0" align="right" class="devicewidth">
												<tbody>
												   <tr>
													  <td
														 width="450" valign="middle" style="font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;line-height: 24px; padding: 10px 0;" align="right" class="menu" st-content="menu"></td>
													  <td
														 width="20"></td>
												   </tr>
												</tbody>
											 </table>
										  </td>
									   </tr>
									</tbody>
								 </table>
							  </td>
						   </tr>
						</tbody>
					 </table>
				  </div>
				  <div
					 class="block">
					 <table
						width="100%" bgcolor="#f5f7fb" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="bigimage">
						<tbody>
						   <tr>
							  <td>
								 <table
									bgcolor="#ffffff" width="960" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
									<tbody>
									   <tr>
										  <td>
											 <table
												width="920" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
												<tbody>
												   <tr>
													  <td
														 style="font-family: Helvetica, arial, sans-serif; font-size: 13px; color: #95a5a6; text-align:left;line-height: 24px;" st-content="rightimage-paragraph">
														 <table
															border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#292c3b">
														 </table>
														 <h1 style="text-align: center;"><span
															style="color: #000000;"><strong>Welcome to xDAC</strong></span></h1>
														 <h2 style="text-align: center;"><span
															style="color: #000000;"><strong>'.$companyName.'</strong></span></h2>
														 <p
															style="text-align: center;">&nbsp;</p>
														 <p
															style="text-align: center;"><strong><img
															src="https://www.xdac.co/wp-content/uploads/2018/03/User-check.png" alt="" width="87" height="87" /></strong></p>
														 <p
															style="text-align: left;">&nbsp;</p>
																							 <p
															style="text-align: left;">Thank you for your registration at xDAC.co.</p>
														 <p
															style="text-align: left;">Your company name:&nbsp;'.$companyName.'</p>
														 <p
															style="text-align: left;">To activate your company and verify your email address, please click the following link <a
															href="https://www.xdac.co/login/">'.home_url(self::PAGE_VERITY_EMAIL_COMPANY).'?token='.$token.'&link='.$companyLink.'</a></p>
														 <p
															style="text-align: left;">&nbsp;</p>
														 <p
															style="text-align: left;">If you have any questions you can contact us at <a
															href="mailto:support@xdac.co">support@xdac.co</a>.</p>
														 <p
															style="text-align: left;">Thank you and we look forward to helping you build your decentralized company.</p>
														 <p
															style="text-align: left;">The xDAC Team</p>
														 <p
															style="text-align: left;">&nbsp;</p>
														 <p
															style="text-align: left;">&nbsp;</p>
														 <p
															style="text-align: left;">&nbsp;</p>
														 <p
															style="text-align: center;"><a
															href="https://www.xdac.co/"><img
															src="https://www.xdac.co/wp-content/plugins/mailpoet/assets/img/newsletter_editor/social-icons/03-circles/Website.png?mailpoet_version=3.5.1" alt="website" width="32" height="32" /></a>&nbsp;<a
															href="mailto:support@xdac.co"><img
															src="https://www.xdac.co/wp-content/plugins/mailpoet/assets/img/newsletter_editor/social-icons/03-circles/Email.png?mailpoet_version=3.5.1" alt="email" width="32" height="32" /></a>&nbsp;<a
															href="https://t.me/xdacco"><img
															src="https://www.xdac.co/wp-content/uploads/2018/03/Telegram.png" alt="custom" width="32" height="32" /></a>&nbsp;<a
															href="https://twitter.com/xdacco"><img
															src="https://www.xdac.co/wp-content/plugins/mailpoet/assets/img/newsletter_editor/social-icons/03-circles/Twitter.png?mailpoet_version=3.5.1" alt="twitter" width="32" height="32" /></a>&nbsp;<a
															href="https://medium.com/xdac"><img
															src="https://www.xdac.co/wp-content/uploads/2018/03/Medium.png" alt="custom" width="32" height="32" /></a>&nbsp;<a
															href="https://www.reddit.com/user/xdacco"><img
															src="https://www.xdac.co/wp-content/uploads/2018/03/Reddit.png" alt="custom" width="32" height="32" /></a></p>
														 <hr
															/>
														 <p
															style="text-align: center;">Copyright &copy; 2018 xDAC, All rights reserved.</p>
														 <p
															style="text-align: center;"><a
															href="https://www.xdac.co/"><img
															src="https://www.xdac.co/wp-content/uploads/2018/03/xDAC-icon-mono-512x512.png" alt="xDAC-icon-mono-512x512" width="32" /></a></p>
													  </td>
												   </tr>
												   <tr>
													  <td
														 width="100%" height="20"></td>
												   </tr>
												</tbody>
											 </table>
										  </td>
									   </tr>
									</tbody>
								 </table>
							  </td>
						   </tr>
						</tbody>
					 </table>
				  </div>
				  <div
					 class="block">
					 <table
						width="100%" bgcolor="#f5f7fb" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
						<tbody>
						   <tr>
							  <td
								 width="100%">
								 <table
									width="960" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
									<tbody>
									   <tr>
										  <td
											 width="100%" height="5"></td>
									   </tr>
									   <tr>
										  <td
											 align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #999999" st-content="preheader">
											 You are receiving this email because you have registered with xDAC.
										  </td>
									   </tr>
									   <tr>
										  <td
											 width="100%" height="5"></td>
									   </tr>
									</tbody>
								 </table>
							  </td>
						   </tr>
						</tbody>
					 </table>
				  </div>
			   </body>
			</html>
			', 'xdac_wp_client');

            $subject = __("Welcome to xDAC", 'xdac_wp_company');
            $headers = array();

            add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
            $headers[] = __('From: ', 'xdac_wp_company').get_bloginfo( 'name').' <info@xdac.co>'."\r\n";
            wp_mail( $email, $subject, $message, $headers);
            wp_mail( 'info@xdac.co', $subject, $message, $headers);
            // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
            remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
        }

        /**
         * Hook into actions and filters.
         * @since  1.0.0
         */
        private function init_hooks() {
            add_action('init', array( $this, 'xdac_session_start' ), 1);
            add_action('plugins_loaded', array($this, 'init'), 1);
            add_filter( 'page_template', array($this, 'xdac_page_template'), 1);
        }

        public function xdac_page_template( $page_template )
        {

            $this->checkIsLogined();

            if ( is_page( self::PAGE_REGISTER_COMPANY ) ) {
                $page_template = XDAC_COMPANY_ABSPATH.'/templates/register.php';
            } elseif ( is_page( self::PAGE_LOGIN_COMPANY ) ) {
                $page_template = XDAC_COMPANY_ABSPATH.'/templates/login.php';
            } elseif ( is_page( self::PAGE_VERITY_EMAIL_COMPANY ) ) {

                $_SESSION['verify-email-company'] = null;

                global $wpdb;

                $link = $_GET['link'];
                $token  = $_GET['token'];

                if(empty($link)) {
                    wp_redirect(home_url(self::PAGE_REGISTER_COMPANY));
                    exit;
                }

                $company = $wpdb->get_row( "SELECT * FROM `xdac_company_owners` LEFT JOIN `xdac_companies` ON `xdac_companies`.`id` = `xdac_company_owners`.`company_id` WHERE `user_id` = ".get_current_user_id()." AND `link`='{$link}'");

                if(!empty($company)) {

                    if( empty($company->is_verity) ) {
                        wp_redirect(home_url(self::PAGE_SEND_XDAC_COMPANY) . '?link=' . $link);
                        exit;
                    }

                    if($token) {
                        if( !empty($token) && $company->is_verity == $token ) {
                            if( $wpdb->query($wpdb->prepare("UPDATE `xdac_companies` SET `is_verity`=NULL WHERE `id`=" . $company->id)) ) {
                                wp_redirect(home_url(self::PAGE_SEND_XDAC_COMPANY) . '?link=' . $link);
                                exit;
                            }
                        } else {
                            $_SESSION['verify-email-company'] = __("Invalid token", 'xdac_wp_company');
                        }
                    }


                } else {
                    $_SESSION['verify-email-company'] = __("Company not found", 'xdac_wp_company');

                }

                $page_template = XDAC_COMPANY_ABSPATH.'/templates/verify-email.php';
            } elseif ( is_page( self::PAGE_SEND_XDAC_COMPANY ) ) {

                $_SESSION['error-send-xdac'] = null;

                global $wpdb;

                global $company;

                $link = $_GET['link'];

                if(empty($link)) {
                    wp_redirect(home_url(self::PAGE_REGISTER_COMPANY)); exit;
                }

                $company = $wpdb->get_row( "SELECT * FROM `xdac_company_owners` LEFT JOIN `xdac_companies` ON `xdac_companies`.`id` = `xdac_company_owners`.`company_id` WHERE `user_id` = ".get_current_user_id()." AND `link`='{$link}'");

                if(!empty($company)) {

                    if(!is_null($company->created_at)) {
                        wp_redirect(home_url(self::PAGE_CONFIRMATION_COMPANY) . '?link=' . $link); exit;
                    }

                } else {
                    $_SESSION['error-send-xdac'] = __("Company not found", 'xdac_wp_company');
                }

                $page_template = XDAC_COMPANY_ABSPATH.'/templates/send-xdac.php';

            } elseif ( is_page( self::PAGE_CONFIRMATION_COMPANY ) ) {

                $_SESSION['error-confirmation-company'] = null;

                global $wpdb;

                global $company;

                $link = $_GET['link'];

                $company = $wpdb->get_row( "SELECT * FROM `xdac_company_owners` LEFT JOIN `xdac_companies` ON `xdac_companies`.`id` = `xdac_company_owners`.`company_id` WHERE `user_id` = ".get_current_user_id()." AND `link`='{$link}'");

                if(empty($company)) {
                    $_SESSION['error-confirmation-company'] = __("Company not found", 'xdac_wp_company');
                } elseif($company->created_at == null ) {
                    wp_redirect(home_url(self::PAGE_SEND_XDAC_COMPANY) . '?link=' . $link); exit;
                } else {

                }

                $page_template = XDAC_COMPANY_ABSPATH.'/templates/confirmation-company.php';
            }
            return $page_template;
        }

        private function checkIsLogined(){
            if ( ! is_user_logged_in() ) {
                wp_redirect(home_url('/login'));
                exit;
            }
        }

        /**
         * Init plugin
         */
        public function init() {}

        public function xdac_session_start(){
            if(!session_id()) {
                session_start();
            }
        }

        /**
         * Define CDT Constants.
         */
        private function define_constants() {
            $this->define( 'XDAC_COMPANY_PLUGIN_FILE', __FILE__ );
            $this->define( 'XDAC_COMPANY_ABSPATH', dirname( __FILE__ ));
            $this->define( 'XDAC_COMPANY_PLUGIN_BASENAME', plugin_basename( __FILE__ ));
            $this->define( 'XDAC_COMPANY_PLUGIN_URL', plugin_dir_url( __FILE__ ));
            $this->define( 'XDAC_COMPANY_VERSION', $this->version );
            $this->define( 'XDAC_COMPANY_MAIN_ACCOUNT', $this->mainAccount );
            $this->define( 'XDAC_COMPANY_AMOUNT', $this->amount );

            $this->define( 'XDAC_COMPANY_URL_LOGIN', self::PAGE_LOGIN_COMPANY );
            $this->define( 'XDAC_COMPANY_URL_REGISTER', self::PAGE_REGISTER_COMPANY );
            $this->define( 'XDAC_COMPANY_URL_VERITY_EMAIL', self::PAGE_VERITY_EMAIL_COMPANY );
            $this->define( 'XDAC_COMPANY_URL_SEND_XDAC', self::PAGE_SEND_XDAC_COMPANY );
            $this->define( 'XDAC_COMPANY_URL_CONFIRMATION', self::PAGE_CONFIRMATION_COMPANY );
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }
    }

endif;

/**
 * Main instance of XdacCompany.
 *
 * Returns the main instance of XdacCompany to prevent the need to use globals.
 *
 * @since  1.0
 * @return XdacCompany
 */
function XdacCompany() {
    return XdacCompany::instance();
}

// Global for backwards compatibility.
$GLOBALS['XdacCompany'] = XdacCompany();