(function ($) {
    $(document).ready(function ($) {

        let buttonPay = document.getElementById("selectAccount");
        let btnCompanyDashboard = document.getElementById("companyDashboard");

        if(buttonPay){
            buttonPay.addEventListener("click", selectAccount);
        }

        const network = {
            blockchain:'eos',
            host:'localhost', // ( or null if endorsed chainId )
            port:888 // ( or null if defaulting to 80 )
        };

        const requiredFields = {
            accounts:[
                {blockchain:'eos', host:'localhost', port:888}
            ]
        };

        async function selectAccount() {


            this.disabled=true;

            $('.xdac-client-errors').addClass('hidden');

            const eos = window.scatter.eos( network, Eos.Localnet, {} );
            await scatter.suggestNetwork(network);
            const identity = await scatter.getIdentity(requiredFields);
            const eosAccount = identity.accounts.find(account => account.blockchain === 'eos');
            eos.contract('eosio.token', {requiredFields}).then(contract => {
                contract.transfer(eosAccount.name, mainAccount, amount, '').then(function (data) {

                    const formData = new FormData();
                    formData.append("xdac_company_form", 'send-xdac-company');
                    formData.append("response", JSON.stringify(data));

                    $.ajax({
                        url: window.location.href,
                        type: "POST",
                        data: {"xdac_company_form": "send-xdac-company", "response": JSON.stringify(data)},
                        dataType: 'json',
                        success: function (response) {
                            if(response.status == 'successful') {
                                window.location.href = response.link;
                            }
                        },
                        error: function(jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                        }
                    });

                }).catch(error => {
                    this.disabled=false;
                    $('.xdac-client-errors').removeClass('hidden');
                });

            });
        };

        if(btnCompanyDashboard){
            btnCompanyDashboard.addEventListener("click", function (e) {
                e.preventDefault();
                window.location.href = this.dataset.href;
            });
        }



        //show/hide register info
        $('.register-company-info').click(function(event) {
            value = $('.register-info').css('right') !== '0px' ? '0px' : '-100%';
            $('.register-info').animate({
                right: value
            });
        });

    });
})(jQuery);
