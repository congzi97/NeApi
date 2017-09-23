/**
 * RSA加密
 * @param value
 * @returns {*}
 */
var enCrypt = function (value) {
    var key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQD0Fq7gPuVj/u6cpnHMNWLIfj50AaOHP+Rb6U/2nNMWxo+EuKSWxFPI9F8DpodCRFOCwmPlOCyjo9nZ8D9NmVT34k2iM1De/b6vILDoqj1LnakseGdYItIH/9GrMdmTzQfowsK9ApATuJckVKU5CQZGxQtlGatgsQ/1heN+wi3BYQIDAQAB';
    var obj = new JSEncrypt();
    obj.setPublicKey(key);
    return obj.encrypt(value);
};
