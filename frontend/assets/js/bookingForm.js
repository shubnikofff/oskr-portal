/*
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */
(function () {
    
    var dom = OSKR.dom,
        util = OSKR.util,

        superButton = document.getElementById('super-button'),
        countBorder = document.getElementById('count-board'),

        publisher = {

            count: 0,
            addCount: function () {
                this.count += 1;
                this.fire('countchange', this.count);
            }
        };

    countBorder.refresh = function (count) {
        this.innerHTML = 'Count: ' + count;
    };

    util.makePublisher(publisher);

    publisher.on('countchange', 'refresh', countBorder);

    dom.addListener(superButton, 'click', function () {
        publisher.addCount();
    });


    
})();