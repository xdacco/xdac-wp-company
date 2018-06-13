<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/header.php' ); ?>

<div class="container">
    <h1>Edit company</h1>
    <form action="" method="POST">
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="company" value="<?= $company->wallet?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <textarea name="about" cols="30" rows="10" required><?= !empty($company->about) ? $company->about : '' ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn update-company-info">Update</button>
            </div>
        </div>
    </form>
</div>

<?php include_once( XDAC_COMPANY_ABSPATH.'/templates/footer.php' ); ?>
