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
            $('.xdac-client-errors').addClass('hidden');
            const eos = window.scatter.eos( network, Eos.Localnet, {chainId: chainId} );
            await scatter.suggestNetwork(network);
            const identity = await scatter.getIdentity(requiredFields);
            const eosAccount = identity.accounts.find(account => account.blockchain === 'eos');
            eos.contract('eosio.token', {requiredFields}).then(contract => {
                contract.transfer(eosAccount.name, mainAccount, amount, $('[name=wallet]').val()).then(function (data) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('trx_id', data.transaction_id);
                    if(partner){
                        url.searchParams.set('partner', partner);
                    }
                    window.location.href = url.href;
                }).catch(error => {
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
