/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
OSKR.namespace('OSKR.util');

OSKR.util = (function () {

    var publisher = (function () {

        var ACTION_PUBLISH = 'publish',
            ACTION_UNSUBSCRIBE = 'unsubscribe',

            visitSubscribers = function (action, type, arg, context) {
                var subscribers = this.subscribers[type || 'any'],
                    i,
                    max = subscribers ? subscribers.length : 0;

                for (i = 0; i < max; i += 1) {
                    if (action === ACTION_PUBLISH) {
                        subscribers[i].fn.call(subscribers[i].context, arg);
                    } else {
                        if (subscribers[i].fn === arg && subscribers[i].context === context) {
                            subscribers.splice(i, 1);
                        }
                    }
                }
            };

        return {
            
            subscribers: {any: []},

            on: function (type, fn, context) {
                type = type || 'any';
                fn = fn instanceof Function ? fn : context[fn];

                if (typeof this.subscribers[type] === "undefined") {
                    this.subscribers[type] = [];
                }
                this.subscribers[type].push({fn: fn, context: context || this});
            },

            remove: function (type, fn, context) {
                visitSubscribers.call(this, ACTION_UNSUBSCRIBE, type, fn, context);
            },

            fire: function (type, publication) {
                visitSubscribers.call(this, ACTION_PUBLISH, type, publication);
            }
        };

    }());

    function makePublisher(o) {

        var i;

        for (i in publisher) {
            if (publisher.hasOwnProperty(i) && publisher[i] instanceof Function) {
                o[i] = publisher[i];
            }
        }
        o.subscribers = {any: []};
    }

    return {
        makePublisher: makePublisher
    }

}());