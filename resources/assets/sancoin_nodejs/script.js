//Call function ethereum
var Web3 = require('web3');
var web3 = new Web3();
var util = require('ethereumjs-util');
var Tx = require('ethereumjs-tx');
var lightwallet = require('eth-lightwallet');
var txutils = lightwallet.txutils;

function printOutput(contentJson) {
    console.log("<SANCOIN>" + contentJson + "</SANCOIN>");
}

function generateAccount(password) {
    // generate a new BIP32 12-word seed
    var secretSeed = lightwallet.keystore.generateRandomSeed();
    // the seed is stored encrypted by a user-defined password
    //var password = "password";
    lightwallet.keystore.deriveKeyFromPassword(password, function (err, pwDerivedKey) {
        var ks = new lightwallet.keystore(secretSeed, pwDerivedKey);
        // generate five new address/private key pairs
        // the corresponding private keys are also encrypted
        ks.generateNewAddress(pwDerivedKey, 1);
        var addr = ks.getAddresses();
        for(i in addr) {
            var address = addr[i];
            var privatekey = ks.exportPrivateKey(address, pwDerivedKey);
            //console.reset();
            printOutput(JSON.stringify({'address': address, 'private_key':privatekey}));
        }
    });
}

function sendETH(fromAddress, toAddress, amount, privateKey, ethereum_nonce) {
    if (!ethereum_nonce) {
        ethereum_nonce = '0x0';
    }

    var rawTransaction = {
            "from": fromAddress,
            "gas": web3.toHex(100000),
            "gasPrice": web3.toHex(30000),
            "to": toAddress,
            "value": web3.toHex(web3.toWei(amount, 'ether')),
            "nonce": ethereum_nonce,
            "data": "",
    };

    var privKey = new Buffer.from(privateKey, 'hex');
    var tx = new Tx(rawTransaction);
    tx.sign(privKey);
    var serializedTx = tx.serialize();
    printOutput('0x' + serializedTx.toString('hex'));
}

if (process.argv[2] == 'generateAccount') {
	generateAccount(process.argv[3]);
}

if (process.argv[2] == 'sendETH') {
    sendETH(process.argv[3], process.argv[4], process.argv[5], process.argv[6], process.argv[7]);
}

