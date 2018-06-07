<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<div class="wrapper-login-register">
    <p class="step-title"><?php _e('Invest in company', 'xdac_wp_company'); ?></p>

    <div class="tabs-login-register">
        <?php if(!empty($errors->errors)):?>
            <?php foreach ($errors->errors as $error):?>
                <p class="login-register-description"><?php echo $error[0] ?></p>
            <?php endforeach; ?>
        <?php else:?>
            <?php global $company; ?>
            <div class="error">
                <p class="xdac-client-errors hidden"><?php _e('Oops! Something went wrong', 'xdac_wp_company')?></p>
            </div>

            <p class="step-title__subtitle"></p>
            <p class="step-title__company"><span><?php echo $company->name?></span>, xDAC</p>

            <div class="xdac-client-form">

                <?php if($trx_id): ?>
                    <p class="before-pay-btn"><?php _e('Please wait, your transaction will be processed', 'xdac_wp_company'); ?><br /></p>
                <?php endif;?>

                <p class="before-pay-btn"><?php _e('Send capital in XDAC tokens to your company account', 'xdac_wp_company'); ?><br /></p>

                <div>
                    <input type="hidden" name="wallet" value="<?php echo $company->wallet?>" />
                    <input type="text" name="icapital" value="" placeholder="Amount *" required maxlength="100"/>
                </div>

                <input class="xdac-submit-form" type="submit" value="PAY" id="selectAccount" />

                <button class="register-company-info register-company-info__btn" >?</button>

                <p class="cleos-block">You can use cleos to conduct transactions.
                    To do this, indicate the value of the fields From "<?php echo XDAC_COMPANY_MAIN_ACCOUNT ?>" and Memo
                    "<?php echo $company->wallet?>"</p>

                <p class="xdac-register-terms xdac-company-register-terms">
                    <a href="javascript:void(0)"><?php _e(' Existing owners: ', 'xdac_wp_company'); ?></a><br /><br />
                    <?php foreach ($owners as $owner):?>
                        <?php echo $owner->amount; ?> XDAC (<?php echo round($companyCapital*100/$owner->amount) ?>%): <?php echo $owner->user; ?><br />
                    <?php endforeach; ?>
                </p>

            </div>
        <?php endif;?>
    </div>

    <div class="register-info">
        <div class="register-info__content">
            <p class="register-info__title">
                <span><?php _e('REGISTER NEW COMPANY', 'xdac_wp_company'); ?></span>
            </p>
            <p><?php _e('You are about to register your xDAC company. The company will be created
                in our system and deployed to the network within 90 days from the day of
                your registration. In order to register a company, you need to set your initial
                capital which should be 100 XDAC or more and send it to address on the
                following page.', 'xdac_wp_company'); ?></p>
            <p class="register-info__mb">
                <?php _e('Initial capital represents your stake in the company. 1 XDAC is one vote in
                your company. If you have multiple partners, they can participate in your
                company by sending XDAC tokens from a different ETH wallet address in
                an amount that represents their stake.', 'xdac_wp_company'); ?></p>
            <p><?php _e('Example:', 'xdac_wp_company'); ?></p>
            <p><?php _e('The company created by single owner:', 'xdac_wp_company'); ?></p>
            <p><?php _e('Owner sends 100 XDAC.', 'xdac_wp_company'); ?></p>
            <p class="register-info__mb">
                <?php _e('100 XDAC = 100 votes which is 100% stake in the company.', 'xdac_wp_company'); ?>
            </p>
            <p><?php _e('The company created by 2 owners:', 'xdac_wp_company'); ?></p>
            <p><?php _e('Owner A sends 300 XDAC.', 'xdac_wp_company'); ?></p>
            <p><?php _e('Owner B sends 100 XDAC.', 'xdac_wp_company'); ?></p>
            <p class="register-info__mb">
                <?php _e('Owner A owns 75%, owner B owns 25% stake in the company.', 'xdac_wp_company'); ?>
            </p>
            <p class="register-info__mb">
                <?php _e('After the company is deployed, the initial investment will be available for
                transfer or company expenses.', 'xdac_wp_company'); ?></p>
            <p class="register-info__title">
                <span><?php _e('WHY SHOULD I REGISTER MY COMPANY NOW', 'xdac_wp_company'); ?></span>
            </p>
            <p class="register-info__mb">
                <?php _e('Lock your company name and date when your company will be available.
                Date of company creation will be the date when you registered your company.', 'xdac_wp_company'); ?></p>
        </div>
        <button class="register-info__btn register-company-info">
            <?php _e('Okay, I Understand', 'xdac_wp_company'); ?>
        </button>
    </div>
</div>

<script>
    var mainAccount = '<?php echo XDAC_COMPANY_MAIN_ACCOUNT ?>';
    var currency = '<?php echo XDAC_COMPANY_CURRENCY ?>';
    var minTransactionAmount = '0';
    var chainId = '<?php echo $chainId ?>';
    var partner = 'partner';
</script>
<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
