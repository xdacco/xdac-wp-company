<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<div class="container">
    <div class="row bs-wizard">
        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum"><?php _e('Register a company', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step active">
            <div class="text-center bs-wizard-stepnum"><?php _e('Verify Email', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step disabled">
            <div class="text-center bs-wizard-stepnum"><?php _e('Initial Capital', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step disabled">
            <div class="text-center bs-wizard-stepnum"><?php _e('Confirmation', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>
    </div>
</div>

<div class="wrapper-login-register">
    <p class="step-title"><?php _e('Verify Email', 'xdac_wp_company'); ?></p>
    <div class="tabs-login-register">
        <br><br>
        <?php if(!empty($errors->errors)):?>
            <?php foreach ($errors->errors as $error):?>
                <p class="login-register-description"><?php echo $error[0] ?></p>
            <?php endforeach; ?>
        <?php else:?>
            <p class="login-register-description"><?php _e('Please confirm your mail', 'xdac_wp_company'); ?></p>
        <?php endif;?>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
