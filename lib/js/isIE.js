/**
 * Script que desabilita a visualizacao do site no navegador internet explorer 6 ou inferior.
 * @JorootFramework(in JAVASCRIPT) 
 * 
 */
function isIE() {
    var browser = navigator.appName;
    var ver = navigator.appVersion;
    var thestart = parseFloat(ver.indexOf('MSIE')) + 1;
    var brow_ver = parseFloat(ver.substring(thestart + 4, thestart + 7));

    this.disableIE6 = function () {
        //verifica se o navegador for Internet Explorer versao 6 ou anterior
        if ((browser == 'Microsoft Internet Explorer') && (brow_ver < 7)) {
            //E apresenta uma messagem de erro!
            document.documentElement.getElementsByTagName('body')[0].innerHTML = '<div id="noIE6">:: ATEN&Ccedil;&Atilde;O : SEU NAVEGADOR EST&Aacute; DESATUALIZADO, PARA SUA SEGURAN&Ccedil;A BAIXE UMA VERS&Atilde;O MAIS RECENTE ::<br /><br /><a name="firefox" href="http://br.mozdev.org/firefox/download/">FIREFOX</a>&nbsp;|&nbsp;<a name="opera" href="http://www.opera.com/download/">OPERA</a>&nbsp;|&nbsp;<a name="chrome" href="https://www.google.com/intl/pt-BR/chrome/browser/?hl=pt-br">CHROME</a>&nbsp;|&nbsp;<a name="ie" href="http://www.internetexplorerbrasil.com/">INTERNET EXPLORER</a></div>';
            elmID('noIE6').style.position = 'relative';
            elmID('noIE6').style.top = '0';
            elmID('noIE6').style.width = '100%';
            elmID('noIE6').style.height = '1200px';
            elmID('noIE6').style.border = '2px solid #C8605F';
            elmID('noIE6').style.textAlign = 'center';
            elmID('noIE6').style.font = 'bold 14px arial';
            elmID('noIE6').style.color = '#CC3333';
            elmID('noIE6').style.padding = '20px 0px';
            elmID('noIE6').style.background = '#FCC2C4';
            var i = 0;
            for (i; i < 4; i++) {
                elmName('a').item(i).style.color = '#CC3333';
            }
        }
    };

    function elmID(id) {
        return document.getElementById(id);
    }

    function elmName(elm) {
        return document.getElementsByTagName(elm);
    }

    function elmTagName(elm, name) {
        return document.getElementsByTagName(elm).namedItem(name);
    }
}

var noIE6 = new isIE();
window.onload = noIE6.disableIE6;