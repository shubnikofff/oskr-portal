/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
(function ($) {

    var control = function (selector) {

        var that = {}, $set = $(selector);

        that.on = function (type, handler, params) {
            $set.on(type, handler, params);
        };

        that.get_set = function (index) {
            return index !== undefined ? $set[index] : $set;
        };

        return that;
    };

    var container = function (selector) {

        var that = control(selector);

        var set_visible = function () {
            that.get_set().fadeIn();
        };

        var set_hidden = function () {
            that.get_set().hide();
        };

        return {
            show: set_visible,
            hide: set_hidden
        };
    };

    /* VKS Option */
    (function () {
        var that = control('input[value="vks"]'),
            change = function () {

                container(this.checked ? 'div.option-vks' : 'div.option-equipment').show();
                container(this.checked ? 'div.option-equipment' : 'div.option-vks').hide();
            };

        that.on('change',change);

        change();

    })();

    /* Room Finder */
    (function () {

        var that = control('#room-finder'), timeoutId, $found;

        var search = function () {

            if ($found !== undefined) {
                $found.removeClass('found');
            }

            if (this.value.length > 0) {
                $found = $("div.room:contains('" + this.value + "')");
                $found.parent().each(function () {
                    $found = $found.add("button[group-id='" + this.id + "']");
                });

                $found.addClass('found');
            }
        };

        that.on('keyup', function () {
            clearTimeout(timeoutId);
            var that = this;
            timeoutId = setTimeout(function () {
                search.apply(that)
            }, 800);
        });

    })();

    /* Room Group Button */
    (function () {

        var that = control('button.room-group'), current;

        var click = function () {
            if (current !== undefined) {
                current.hide();
            }
            current = container('#' + this.getAttribute('group-id'));
            current.show();
        };

        that.on('click', function (event) {
            event.preventDefault();
            click.apply(this);
        });

        click.apply(that.get_set(0));

    })();

    /* For :contain case insensitive */
    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });


})(jQuery);