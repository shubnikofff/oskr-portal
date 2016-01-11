/**
 * teleport
 * Created: 23.12.15 8:10 by Shubnikov Alexey <a.shubnikov@niaep.ru>
 * Copyright (c) 2015 OSKR NIAEP
 */
(function ($) {

    $.fn.deployServerForm = function (options) {
        teleport.deployServerForm.init(this, options);
        return this;
    };

    teleport.deployServerForm = {

        defaultSettings: {
            inputSelector: null
        },

        $form: null,
        $input: null,

        init: function ($form, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});
            this.$form = $form;
            this.$input = $(settings.inputSelector);

            this.$input.on('change', {that: this}, this.inputChange);
        },

        inputChange: function (event) {
            var that = event.data.that;
            $.post(that.$form.attr('action'), that.$form.serialize());
        }
    };

})(jQuery);