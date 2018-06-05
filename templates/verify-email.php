<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<!-- complete active disabled-->
<div class="container">
    <div class="row bs-wizard">
        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum">Register a company</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step active">
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


    <p class="step-title"><?php _e('Verify Email', 'xdac_wp_company'); ?></p>
    <div class="tabs-login-register">
        <br><br>
        <?php if(!empty($_SESSION['verify-email-company'])):?>
            <p class="login-register-description"><?php _e($_SESSION['verify-email-company'], 'xdac_wp_company'); ?></p>
        <?php else:?>
            <p class="login-register-description"><?php _e('Please confirm your mail', 'xdac_wp_company'); ?></p>
        <?php endif;?>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
