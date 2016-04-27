/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
OSKR.namespace('OSKR.dom');

OSKR.dom = (function () {

    var getNode,
        addListener,
        removeListener;

    if (typeof window.addEventListener === 'function') {
        addListener = function (node, type, handler) {
            node.addEventListener(type, handler, false);
        };
        removeListener = function (node, type, handler) {
            node.removeEventListener(type, handler, false);
        };

    } else if (typeof document.attachEvent === 'function') {
        addListener = function (node, type, handler) {
            node.attachEvent('on' + type, handler);
        };
        removeListener = function (node, type, handler) {
            node.detachEvent('on' + type, handler);
        };

    } else {
        addListener = function (node, type, handler) {
            node['on' + type] = handler;
        };
        removeListener = function (node, type) {
            node['on' + type] = null;
        };
    }
    
    return {
        getNode: getNode,
        addListener: addListener,
        removeListener: removeListener
    };

}());