/**
 * teleport
 * Created: 16.11.15 13:57 by Shubnikov Alexey <a.shubnikov@niaep.ru>
 * Copyright (c) 2015 OSKR NIAEP
 */
(function ($) {

    $.fn.searchForm = function () {
        teleport.searchForm.init(this);

    };

    teleport.searchForm = {

        $form: null,
        formData: null,

        init: function ($form) {

            this.$form = $form;

            this.formData = $form.serialize();
            this.$form.on('change', {that: this}, this.formChange);
        },

        formChange: function (event) {
            var that = event.data.that;
            var formData = that.$form.serialize();
            if (formData !== that.formData) {
                that.formData = formData;
                that.$form.submit();
            }
        }
    };

    $.fn.schedule = function (options) {
        teleport.schedule.init(this, options);
        teleport.schedule.print();
    };

    teleport.schedule = {

        defaultSettings: {
            timeColumnWidth: null,
            timeGridSelector: null,
            requestsGridSelector: null
        },
        settings: {},
        $requestsGrid: null,
        $timeGrid: null,

        init: function ($container, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});
            this.$requestsGrid = $(settings.requestsGridSelector, $container);
            this.$timeGrid = $(settings.timeGridSelector, $container);
        },

        print : function() {

            var timeColumnWidth = this.settings.timeColumnWidth;
            this.$requestsGrid.css('margin-left', timeColumnWidth);
            this.$requestsGrid.width(this.$timeGrid.width() - timeColumnWidth);
            this.$requestsGrid.height(this.$timeGrid.height());
            this.$requestsGrid.fadeIn();
        }
    };

})(jQuery);