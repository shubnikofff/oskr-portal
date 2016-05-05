/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
OSKR.namespace('OSKR.dom');

OSKR.dom = (function () {

    var stop,
        addListener,
        removeListener;

    if (document.addEventListener) {
        addListener = function (node, type, handler) {
            node.addEventListener(type, handler, false);
        };
        removeListener = function (node, type, handler) {
            node.removeEventListener(type, handler, false);
        };

    } else if (document.attachEvent) {
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
    //TODO create xhr same as listeners
    stop = function (e, preventDefault, stopPropagation) {
        preventDefault = preventDefault || true;
        stopPropagation = stopPropagation || true;

        if(preventDefault === true && e.preventDefault() === "function") {
            e.preventDefault()
        }

        if(stopPropagation) {

        }
    };
    
    return {
        addListener: addListener,
        removeListener: removeListener
    };

}());