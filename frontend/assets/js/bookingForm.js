/*
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */
(function () {
    
    var dom = OSKR.dom,
        node = document.getElementById('super-button');

    dom.addListener(node, 'click', function () {
        alert('I am working!');
    })
    
})();