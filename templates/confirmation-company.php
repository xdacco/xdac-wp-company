<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<!-- complete active disabled-->
<div class="container">
    <div class="row bs-wizard">
        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum">Register a company</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum">Verify Email</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum">Initial Capital</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>

        <div class="col-xs-12 col-sm-3 bs-wizard-step active">
            <div class="text-center bs-wizard-stepnum">Confirmation</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <span class="bs-wizard-dot"></span>
        </div>
    </div>
</div>

<div class="wrapper-login-register">


    <p class="step-title"><?php _e('Company Succssfully Registered', 'xdac_wp_company'); ?></p>

    <div class="tabs-login-register">
        <div class="xdac-client-form">
            <?php if(!empty($_SESSION['error-confirmation-company'])):?>
                <p class="login-register-description"><?php _e($_SESSION['error-confirmation-company'], 'xdac_wp_company'); ?></p>
            <?php else:?>
                <?php global $company; ?>

                <p class="step-title__subtitle">Your company</p>
                <p class="step-title__company"><span><?php echo $company->name?></span>, xDAC</p>

                <p class="time-registered-company">
                    has been registered on
                    <span>
                        June 1,2018 at 18:00 UTC
                    </span>
                </p>
                <a href="<?php echo home_url('/company/' . $company->link . '/')?>" ><?php echo home_url('/company/' . $company->link . '/')?></a>


                <p class="xdac-register-terms xdac-company-register-terms">
                    <a href="javascript:void(0)"><?php _e(' Existing owners: ', 'xdac_wp_company'); ?></a><br /><br />
                    300 XDAC (75%): sd7fysdfyshd897fys9d87fyhsdf9sdf9s<br />
                    100 XDAC (25%): sd7fysdfyshd897fys9d87fy45g45gdf9s<br />
                </p>
            <?php endif;?>
        </div>
    </div>
</div>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
