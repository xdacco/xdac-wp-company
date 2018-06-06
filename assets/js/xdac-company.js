(function ($) {
    $(document).ready(function ($) {

        const network = {
            blockchain:'eos',
            host:'localhost',
            port:8888
        };

        const requiredFields = {
            accounts:[
                {blockchain:'eos', host:'localhost', port:8888}
            ]
        };

        async function selectAccount(amount) {

            amount = amount.toString() + ' ' + currency;

            this.disabled=true;

            $('.xdac-client-errors').addClass('hidden');

            var memo = JSON.stringify({
                'action': $('[name=action]').val(),
                'wallet': $('[name=wallet]').val(),
            });

            const eos = window.scatter.eos( network, Eos.Localnet, {chainId: chainId} );
            await scatter.suggestNetwork(network);
            const identity = await scatter.getIdentity(requiredFields);
            const eosAccount = identity.accounts.find(account => account.blockchain === 'eos');
            eos.contract('eosio.token', {requiredFields}).then(contract => {
                contract.transfer(eosAccount.name, mainAccount, amount, memo).then(function (data) {

                    const formData = new FormData();
                    formData.append("xdac_company_form", 'send-xdac-company');
                    formData.append("response", JSON.stringify(data));

                    $.ajax({
                        url: window.location.href,
                        type: "POST",
                        data: {
                            "xdac_company_form": "send-xdac-company",
                            "amount": amount,
                            "response": JSON.stringify(data)
                        },
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

        //Btn pay
        $('#selectAccount').on('click', function () {

            var wrap = $('[name=icapital]').parent();
            wrap.removeClass('error');

            var amount = parseFloat($('[name=icapital]').val());
            amount = amount || 0;

            if(amount < minTransactionAmount){
                wrap.addClass('error');
                return false;
            }

            selectAccount(amount);

            return false;
        });

        //show/hide register info
        $('.register-company-info').click(function(event) {
            let block = $('.register-info'),
                value = block.css('right') !== '0px' ? '0px' : '-100%';
            block.animate({
                right: value
            });
        });
    });
})(jQuery);
