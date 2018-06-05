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

        <div class="col-xs-12 col-sm-3 bs-wizard-step active">
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


    <p class="step-title"><?php _e('Company initial capital', 'xdac_wp_company'); ?></p>

    <div class="tabs-login-register">
        <?php if(!empty($_SESSION['error-send-xdac'])):?>
            <p class="login-register-description"><?php _e($_SESSION['error-send-xdac'], 'xdac_wp_company'); ?></p>
        <?php else:?>
            <?php global $company; ?>
            <div class="error">
                <p class="xdac-client-errors hidden"><?php _e('Oops! Something went wrong', 'xdac_wp_company')?></p>
            </div>

            <p class="step-title__subtitle"></p>
            <p class="step-title__company"><span><?php echo $company->name?></span>, xDAC</p>

            <div class="xdac-client-form">

                <p class="before-pay-btn"><?php _e('Send initial capital in XDAC tokens to your company account', 'xdac_wp_company'); ?><br /></p>
                <input class="xdac-submit-form" type="submit" value="PAY" id="selectAccount" />
                <p class="after-pay-btn"><?php _e('Minimum 100 XDAC to register a company', 'xdac_wp_company'); ?><br /></p>

                <button class="register-company-info register-company-info__btn" >?</button>

                <p class="xdac-register-terms xdac-company-register-terms">
                    <a href="javascript:void(0)"><?php _e(' Existing owners: ', 'xdac_wp_company'); ?></a><br /><br />
                    <?php _e('No owners yet', 'xdac_wp_company'); ?><br />
                    <?php _e('This company is avaliable to the public', 'xdac_wp_company'); ?><br />
                    <?php _e('Whoever deposits minimum initial capital first is the owner of this company.', 'xdac_wp_company'); ?>
                </p>
            </div>
        <?php endif;?>
    </div>


    <div class="register-info">

        <div class="register-info__content">

            <p class="register-info__title">
                <span>REGISTER NEW COMPANY</span>
            </p>
            <p>
                You are about to register your xDAC company. The company will be created
                in our system and deployed to the network within 90 days from the day of
                your registration. In order to register a company, you need to set your initial
                capital which should be 100 XDAC or more and send it to address on the
                following page.
            </p>
            <p class="register-info__mb">
                Initial capital represents your stake in the company. 1 XDAC is one vote in
                your company. If you have multiple partners, they can participate in your
                company by sending XDAC tokens from a different ETH wallet address in
                an amount that represents their stake.
            </p>

            <p>Example:</p>
            <p>The company created by single owner:</p>
            <p>Owner sends 100 XDAC.</p>
            <p class="register-info__mb">100 XDAC = 100 votes which is 100% stake in the company.</p>

            <p>The company created by 2 owners:</p>
            <p>Owner A sends 300 XDAC.</p>
            <p>Owner B sends 100 XDAC.</p>
            <p class="register-info__mb">Owner A owns 75%, owner B owns 25% stake in the company.</p>

            <p class="register-info__mb">
                After the company is deployed, the initial investment will be available for
                transfer or company expenses.
            </p>

            <p class="register-info__title">
                <span>WHY SHOULD I REGISTER MY COMPANY NOW</span>
            </p>

            <p class="register-info__mb">
                Lock your company name and date when your company will be available.
                Date of company creation will be the date when you registered your company.
            </p>

        </div>

        <button class="register-info__btn register-company-info">Okay, I Understand</button>
    </div>

</div>

<script>
    var mainAccount = '<?php echo XDAC_COMPANY_MAIN_ACCOUNT ?>';
    var amount = '<?php echo XDAC_COMPANY_AMOUNT ?>';
</script>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
