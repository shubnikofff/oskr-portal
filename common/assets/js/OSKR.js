/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
if (typeof OSKR === "undefined") {
    var OSKR = {};
}

OSKR.namespace = function (ns_string) {
    var parts = ns_string.split('.'),
        parent = OSKR,
        i;

    if (parts[0] === "OSKR") {
        parts = parts.slice(1);
    }

    for (i = 0; i < parts.length; i += 1) {
        if (typeof parent[parts[i]] === "undefined") {
            parent[parts[i]] = {};
        }
        parent = parent[parts[i]];
    }

    return parent;
};

