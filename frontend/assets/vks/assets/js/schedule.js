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
                that.submitForm();
            }
        },

        submitForm: function () {
            this.$form.submit();
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
            currentTimeSelector: null,
            requestsGridSelector: null,
            requestContainerSelector: null,
            modalWidgetSelector: null,
            modalContentSelector: null,
            getProfilesURL: null
        },
        settings: {},
        $requestsGrid: null,
        $timeGrid: null,
        $currentTime: null,
        $requestContainer: null,
        $modalWidget: null,
        $modalContent: null,

        init: function ($container, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});

            this.$requestsGrid = $(settings.requestsGridSelector, $container);
            this.$timeGrid = $(settings.timeGridSelector, $container);
            this.$currentTime = $(settings.currentTimeSelector, $container);
            this.$requestContainer = $(settings.requestContainerSelector, $container);
            this.$modalWidget = $(settings.modalWidgetSelector);
            this.$modalContent = $(settings.modalContentSelector);

            this.$requestContainer.on('click', {
                $content: this.$modalContent,
                $widget: this.$modalWidget,
                $getProfilesURL: settings.getProfilesURL
            }, this.showModal);
        },

        print: function () {

            var timeGridWidth = this.$timeGrid.width();
            var timeColumnWidth = this.settings.timeColumnWidth;
            var requestGridWidth = timeGridWidth - timeColumnWidth;
            var requestContainerWidth = requestGridWidth / $('td', this.$requestsGrid).length - 1;

            this.$currentTime.width(timeGridWidth - 2).css('top', this.$currentTime.data('top'));
            this.$requestsGrid.css('margin-left', timeColumnWidth).width(requestGridWidth).height(this.$timeGrid.height());

            this.$requestContainer.each(function () {
                var $this = $(this);
                $this.height($this.data('height')).width(requestContainerWidth).offset({
                    top: $this.data('top'),
                    left: null
                });
            });

            this.$currentTime.fadeIn();
            this.$requestsGrid.fadeIn();
        },

        showModal: function (event) {

            event.data.$content.load($(this).data('href'), function () {

                $("#profile-id").depdrop({
                    depends: ['mcu-id'],
                    url: event.data.$getProfilesURL,
                    loadingText: 'Загружаю...',
                    placeholder: 'Не указан',
                    emptyMsg: 'Профили не найдены'
                });

                event.data.$widget.modal('show');
            });
        }
    };

})(jQuery);