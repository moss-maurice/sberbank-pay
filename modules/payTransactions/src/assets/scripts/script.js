let moduleObject;
let bufferObject;
let transactionsTabScripts;

let debug = true;


defer(function() {
    jQuery(document).ready(function($) {
        // Инициализация буфера
        bufferObject = new Buffer();
        // Перечисляем имена методов, которые нужно игнорировать при помещении в буфер
        bufferObject.methodsIgnored.push('update');

        // Инициализация модуля
        moduleObject = new Module();
        moduleObject.init();

        // Инициализация скриптов табов. Как правила, там только хуки
        transactionsTabScripts = new TransactionsTabScripts();
    });
});

function defer(method) {
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method);
        }, 50);
    }
}