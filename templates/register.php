<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

    <!-- complete active disabled-->
    <div class="container">
        <div class="row bs-wizard">
            <div class="col-xs-12 col-sm-3 bs-wizard-step active">
                <div class="text-center bs-wizard-stepnum">Register a company</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <a href="#" class="bs-wizard-dot"></a>
            </div>

            <div class="col-xs-12 col-sm-3 bs-wizard-step disabled">
                <div class="text-center bs-wizard-stepnum">Verify Email</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <span class="bs-wizard-dot"></span>
            </div>

            <div class="col-xs-12 col-sm-3 bs-wizard-step disabled">
                <div class="text-center bs-wizard-stepnum">Initial Capital</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <span class="bs-wizard-dot"></span>
            </div>

            <div class="col-xs-12 col-sm-3 bs-wizard-step disabled">
                <div class="text-center bs-wizard-stepnum">Confirmation</div>
                <div class="progress"><div class="progress-bar"></div></div>
                <span class="bs-wizard-dot"></span>
            </div>
        </div>
    </div>

    <div class="wrapper-login-register">

        <p class="step-title"><?php _e('Register your xDAC company', 'xdac_wp_company'); ?></p>
        <div class="tabs-login-register">
            <ul class="nav nav-pills nav-justified">
                <li role="presentation" class="active xdac-register"><a href="javascript:void(0);"><?php _e('New Company', 'xdac_wp_company'); ?></a></li>
                <li role="presentation" class="xdac-login"><a href="<?php echo home_url(XDAC_COMPANY_URL_LOGIN); ?>"><?php _e('Company Login', 'xdac_wp_company'); ?></a></li>
            </ul>
        </div>



        <div class="xdac-client-form">
            <form class="" action="" method="post">
                <input type="hidden" name="xdac_company_form" value="register-company"/>
                <div <?php if( !empty($reg_errors->errors['company_name']) ) echo 'class="error"'; ?>>
                    <?php if ( !empty($reg_errors->errors['company_name']) )  echo '<p class="xdac-client-errors">' . $reg_errors->errors['company_name'][0] . '</p>'; ?>
                    <input type="text" name="company_name" value="<?php echo !empty($_POST['company_name']) ? $_POST['company_name'] : ''; ?>" placeholder="Company Name *" required maxlength="12" />
                </div>

                <div class="site-group <?php if( !empty($reg_errors->errors['company_link']) ) echo 'error'; ?>" >
                    <?php if ( !empty($reg_errors->errors['company_link']) )  echo '<p class="xdac-client-errors">' . $reg_errors->errors['company_link'][0] . '</p>'; ?>
                    <span class="base-url">https://xdac.co/</span>
                    <input type="text" name="company_link" value="<?php echo !empty($_POST['company_link']) ? $_POST['company_link'] : ''; ?>" placeholder="Company Link *" required maxlength="255"/>
                </div>

                <div <?php if( !empty($reg_errors->errors['email']) ) echo 'class="error"'; ?>>
                    <?php if ( !empty($reg_errors->errors['email']) ) echo '<p class="xdac-client-errors">' . $reg_errors->errors['email'][0] . '</p>'; ?>
                    <input type="email" name="email" value="<?php echo !empty($_POST['email']) ? $_POST['email'] : ''; ?>" placeholder="Email *" required maxlength="100"/>
                </div>

                <input class="xdac-submit-form" type="submit" value="<?php _e('REGISTER', 'xdac_wp_company'); ?>"/>
                <p class="xdac-register-terms">
                    <?php _e('By registering you agree to ', 'xdac_wp_company'); ?>
                    <a href="https://www.xdac.co/terms/" target="_blank"><?php _e('Website Terms of Use', 'xdac_wp_company'); ?></a>
                    <?php _e(' and the ', 'xdac_wp_company'); ?>
                    <a href="https://xdac.co/docs/xDAC-Token-Sale-Terms.pdf" target="_blank"><?php _e('Token Sale Terms and Conditions', 'xdac_wp_company'); ?></a>
                    <?php _e(' as well as the ', 'xdac_wp_company'); ?>
                    <a href="https://www.xdac.co/privacy-policy/"  target="_blank"><?php _e('Privacy Policy', 'xdac_wp_company'); ?></a>
                </p>
            </form>
        </div>
    </div>

<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
