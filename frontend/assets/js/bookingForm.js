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

    var roomgroupbutton = function (selector) {

        var that = control(selector || 'button.room-group'), current;

        var click = function () {
            if (current !== undefined) {
                current.hide();
            }
            current = roomgroupcontainer('#' + this.getAttribute('group-id'));
            current.show();
        };

        that.on('click', function (event) {
            event.preventDefault();
            click.apply(this);
        });

        that.click = click;

        return that;
    };

    var roomgroupcontainer = function (selector) {

        var that = control(selector || 'div.room-group');

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

    var roomfinder = function () {

        var that = control('#room-finder'), timeoutId, $found;

        var search = function () {

            if ($found !== undefined) {
                $found.removeClass('found');
            }

            if(this.value.length > 0) {
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

        return that;
    };
    
    roomgroupbutton().get_set(0).click();
    roomfinder();

    /*$.fn.bookingForm = function (selectors) {
     //$(selectors.options.vksOption).optionActivator(selectors.options.vksGroup, selectors.options.equipmentGroup);


     var buttongroup = roomgroupbutton(selectors.roomGroupButtons);


     };*/

    /*var OptionGroup = function (groupSelector) {
     this.container = $(groupSelector);
     this.input = $("input[type=checkbox]", this.container);
     };

     OptionGroup.prototype = {
     show: function () {
     this.container.show();
     this.input.prop('disabled', false);
     },

     hide: function () {
     this.container.hide();
     this.input.prop('disabled', true);
     }
     };

     $.fn.bookingForm = function (selectors) {
     $(selectors.options.vksOption).optionActivator(selectors.options.vksGroup, selectors.options.equipmentGroup);
     };

     $.fn.optionActivator = function (vksOptSelector, equipmentOptSelector) {

     var vksOpt = new OptionGroup(vksOptSelector);
     var equipmentOpt = new OptionGroup(equipmentOptSelector);

     this.on('change', function () {
     if (this.checked) {
     vksOpt.show();
     equipmentOpt.hide();
     } else {
     vksOpt.hide();
     equipmentOpt.show();
     }
     });

     this.trigger('change');

     };*/

})(jQuery);