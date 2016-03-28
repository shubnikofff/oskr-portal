/*
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */
(function ($) {

    var OptionGroup = function (groupSelector) {
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

    }

})(jQuery);