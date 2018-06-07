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
    </div>
    <div class="xdac-client-form">
        <form action="" method="POST">
            <input type="hidden" name="xdac_company_form" value="login-company"/>
            <div class="site-group <?php if( !empty($errors->errors['company_link']) ) echo 'error';?>" >
                https://xdac.co/company/
                <input type="text" name="company_link" value="<?php echo !empty($_POST['company_link']) ? $_POST['company_link'] : ''; ?>" placeholder="Company Link"  />
                <?php if ( !empty($errors->errors['company_link']) )  echo '<p class="xdac-client-errors">' . $errors->errors['company_link'][0] . '</p>'; ?>
            </div>
            <input class="xdac-submit-form" name="wp-submit" type="submit" value="<?php _e('LOG IN', 'xdac_wp_company'); ?>"/>
        </form>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>