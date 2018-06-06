<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<div class="container">
    <div class="row bs-wizard">
        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum"><?php _e('Register a company', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum"><?php _e('Verify Email', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum"><?php _e('Initial Capital', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step active">
            <div class="text-center bs-wizard-stepnum"><?php _e('Confirmation', 'xdac_wp_company'); ?></div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>
    </div>
</div>

<div class="wrapper-login-register">


    <p class="step-title"><?php _e('Company Succssfully Registered', 'xdac_wp_company'); ?></p>

    <div class="tabs-login-register">
        <div class="xdac-client-form">
            <?php if(!empty($errors->errors)):?>
                <?php foreach ($errors->errors as $error):?>
                    <p class="login-register-description"><?php echo $error[0] ?></p>
                <?php endforeach; ?>
            <?php else:?>
                <?php global $company; ?>
                <?php global $owners; ?>

                <p class="step-title__subtitle"><?php _e('Your company', 'xdac_wp_company'); ?></p>
                <p class="step-title__company"><span><?php echo $company->name?></span>, xDAC</p>
                <p class="time-registered-company">
                    has been registered on
                    <span>
                        <?php echo  date('F j, Y \a\t H:i T', strtotime($company->created_at)) ?>
                    </span>
                </p>
                <a href="<?php echo home_url('/company/' . $company->link . '/')?>" ><?php echo home_url('/company/' . $company->link . '/')?></a>


                <p class="xdac-register-terms xdac-company-register-terms">
                    <a href="javascript:void(0)"><?php _e(' Existing owners: ', 'xdac_wp_company'); ?></a><br /><br />
                    <?php var_dump($owners); die; ?>
                    300 XDAC (75%): sd7fysdfyshd897fys9d87fyhsdf9sdf9s<br />
                    100 XDAC (25%): sd7fysdfyshd897fys9d87fy45g45gdf9s<br />
                </p>
            <?php endif;?>
        </div>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
