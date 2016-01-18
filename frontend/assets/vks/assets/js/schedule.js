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
        teleport.schedule.printItems();
    };

    teleport.schedule = {

        defaultSettings: {
            timeLine: null,
            itemsSelector: null,
            modalWidgetSelector: null,
            modalContainerSelector: null
        },
        settings: {},
        tableWidth: null,
        $table: null,
        $items: null,
        //$modalWidget: null,

        init: function ($table, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});

            this.$table = $table;
            this.$items = $(settings.itemsSelector, $table);
            this.tableWidth = $table.width();

            $table.css('min-width', $table.css('width'));

            this.$items.on('click', {that: this}, this.itemClick);
        },

        printItems: function () {

            var timeLine = this.settings.timeLine;
            var minuteLength = this.tableWidth / timeLine.length;

            this.$items.each(function () {

                var $item = $(this);
                var left = ($item.data('begintime') - timeLine.start) * minuteLength;
                var width = ($item.data('endtime') - $item.data('begintime')) * minuteLength;

                $item.css('width', width + 'px');
                $item.offset({top: 0, left: left});
            });

            this.$items.fadeIn();
        },

        itemClick: function (event) {
            var that = event.data.that;
            $.get(this.getAttribute('data-url'), function (data) {
                $(that.settings.modalContainerSelector).html(data);
                $(that.settings.modalWidgetSelector).modal('show');
            });
        }
    };

})(jQuery);