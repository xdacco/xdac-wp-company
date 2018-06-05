<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>
<div class="wrapper-login-register">

    <div class="logo-block">
        <a href="<?php echo get_site_url(); ?>"><img src="<?php echo XDAC_COMPANY_PLUGIN_URL . 'assets/images/xDAC-logo.png'; ?>"></a>
    </div>
    <div class="tabs-login-register">
        <ul class="nav nav-pills nav-justified">
            <li role="presentation" class="xdac-register"><a href="<?php echo home_url(XDAC_COMPANY_URL_REGISTER); ?>"><?php _e('New Company', 'xdac_wp_company'); ?></a></li>
            <li role="presentation" class="active xdac-login"><a href="javascript:void(0);"><?php _e('Login Company', 'xdac_wp_company'); ?></a></li>
        </ul>

        <p class="login-register-description"><?php _e('Log in to purchase XDAC Tokens', 'xdac_wp_company'); ?></p>
    </div>
    <div class="xdac-client-form">

        <div class="block-xdac-client-errors">
            <?php global $login_errors, $old_email; ?>

            <!-- Show errors if there are any -->
            <?php if ( count( $login_errors ) > 0 ) : ?>
                <?php foreach ( $login_errors as $error ) : ?>
                    <p class="xdac-client-errors">
                        <?php echo $error; ?>
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form name="loginform" id="loginform" action="<?php echo home_url('/wp-login.php'); ?>" method="post">
            <input type="hidden" name="xdac_client_form" value="login"/>
            <div>
                <input type="email" name="log" value="<?php echo !empty($old_email) ? $old_email : ''; ?>" placeholder="Email"/>
            </div>

            <div class="site-group" >
                https://xdac.co/
                <input type="text" name="lname" value="<?php echo !empty($_POST['company_link']) ? $_POST['company_link'] : ''; ?>" placeholder="Company Link"  />
            </div>

            <input class="xdac-submit-form" name="wp-submit" type="submit" value="<?php _e('LOG IN', 'xdac_wp_company'); ?>"/>
            <input type="hidden" name="redirect_to" value="<?php echo home_url('/account'); ?>">
            <input type="hidden" name="wppb_redirect_check" value="true">
        </form>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>