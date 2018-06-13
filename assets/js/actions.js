const Buffer = require('buffer').Buffer;
const ipfsAPI = require('ipfs-api');
const ipfs = ipfsAPI('ipfs.infura.io', '5001', {protocol: 'https'});
const EosApi = require('eosjs');
const eos = EosApi({
    keyProvider: '5KQwrPbwdL6PhXujxW37FSSQZ1JiwsST4cqQzDeyXtP79zkvFD3',
    chainId: 'cf057bbfb72640471fd910bcb67639c22df9f92470936cddc1ade0e2f2e7dc4f'
});
const contracActions = 'actions.xdac';
const accountActions = 'xdac';
const signedTransaction = 'xdac';
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

(function ($) {
    $(document).ready(function ($) {
        $('.update-company-info').on('click', function () {

            let promise = new Promise((resolve, reject) => {
                getAccount().then( user => {
                    let data = JSON.stringify({
                        "company": $('[name=company]').val(),
                        "user": user,
                        "data": {
                            "about": $('[name=about]').val(),
                        }
                    });
                    const files = [
                        {
                            content: new Buffer.from(data)
                        }
                    ];
                    ipfs.files.add(files, function (err, files) {
                        if(err || !files) reject(err);
                        resolve(files[0].hash);
                    });
                });
            }).then( ipfs => {

                eos.contract(contracActions).then(actions => {
                    console.log(ipfs);
                    actions.execute(accountActions, "change_company_info", ipfs, {authorization: signedTransaction}).then( data => {
                        console.log('success');
                    }).catch( e => console.log(e));
                }).catch( e => console.log(e));

            }).catch( e => console.log(e) );

            return false;
        });
    });

    async function getAccount() {
        await scatter.suggestNetwork(network);
        const identity = await scatter.getIdentity(requiredFields);
        const eosAccount = identity.accounts.find(account => account.blockchain === 'eos');
        return eosAccount.name;
    }

})(jQuery);