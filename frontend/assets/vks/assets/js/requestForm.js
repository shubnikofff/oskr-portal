/**
 * teleport
 * Created: 09.11.15 9:56 by Shubnikov Alexey <a.shubnikov@niaep.ru>
 * Copyright (c) 2015 OSKR NIAEP
 */

(function ($) {

    $.fn.requestForm = function (options) {

        teleport.requestForm.init(this, options);
        return this;
    };

    teleport.requestForm = {

        defaultSettings: {
            refreshParticipantsRoute: null,
            participantsContainerSelector: null,
            dateSelector: null,
            beginTimeSelector: null,
            endTimeSelector: null,
            dateTimeControlsSelector: null,
            modeSelector: null,
            audioRecordSelector: null,
            equipmentSelector: null,
            withVksMode: null,
            withoutVksMode: null
        },
        settings: {},
        eventDeferred: $.Deferred(),
        requestId: null,
        date: null,
        beginTime: null,
        endTime: null,
        $form: null,
        $participantsContainer: null,
        $date: null,
        $beginTime: null,
        $endTime: null,
        $dateTime: null,

        init: function ($form, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});

            this.$form = $form;
            this.$participantsContainer = $(settings.participantsContainerSelector);
            this.$date = $(settings.dateSelector, $form);
            this.$beginTime = $(settings.beginTimeSelector, $form);
            this.$endTime = $(settings.endTimeSelector, $form);
            this.$dateTime = $(settings.dateTimeControlsSelector, $form);

            this.$date.on('change', {that: this}, this.dateChange);
            this.$beginTime.on('change', {that: this}, this.beginTimeChange);
            this.$endTime.on('change', {that: this}, this.endTimeChange);
            $(settings.modeSelector, $form).on('change', {
                $audioRecord: $(settings.audioRecordSelector, $form),
                $equipment: $(settings.equipmentSelector, $form),
                settings: settings
            }, this.modeChange);

            this.requestId = settings.requestId;
            this.date = this.$date.val();
            this.beginTime = this.$beginTime.val();
            this.endTime = this.$endTime.val();
        },

        refreshParticipants: function () {

            var that = this;
            this.eventDeferred.reject();
            this.eventDeferred = $.Deferred(function (obj) {
                setTimeout(function () {
                    obj.resolveWith(that);
                }, 900);
            }).done(function () {

                if (!this.$dateTime.hasClass('has-error')) {
                    $.get(
                        this.settings.refreshParticipantsRoute,
                        this.$form.serialize(),
                        function (data) {
                            that.$participantsContainer.html(data);
                        },
                        'html'
                    );
                }
            });
        },

        dateChange: function (event) {
            var that = event.data.that;
            if (this.value !== that.date) {
                that.date = this.value;
                that.refreshParticipants();
            }
        },

        beginTimeChange: function (event) {
            var that = event.data.that;
            if (this.value !== that.beginTime) {
                that.beginTime = this.value;
                that.refreshParticipants();
            }
        },

        endTimeChange: function (event) {
            var that = event.data.that;
            if (this.value !== that.endTime) {
                that.endTime = this.value;
                that.refreshParticipants();
            }
        },

        modeChange: function (event) {
            var $audioRecord = event.data.$audioRecord;
            var $equipment = event.data.$equipment;
            var withVks = event.data.settings.withVksMode;
            var withoutVks = event.data.settings.withoutVksMode;

            if ($(this).val() == withVks) {
                $audioRecord.css('display', 'block');
                $equipment.css('display', 'none');
                $('input', $audioRecord).prop("disabled", false);
                $('input', $equipment).prop("disabled", true);

            } else if ($(this).val() == withoutVks) {
                $equipment.css('display', 'block');
                $audioRecord.css('display', 'none');
                $('input', $audioRecord).prop("disabled", true);
                $('input', $equipment).prop("disabled", false);
            }
        }
    };

    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    $.fn.participants = function (options) {

        teleport.participants.init(this, options);
        teleport.participants.$companyButtons.first().trigger('click');
        return this;
    };

    teleport.participants = {
        defaultSettings: {
            companyButtonsSelector: 'button.vks-company',
            vksRoomsSelector: '#vks-participants',
            checkBoxesSelector: 'input[type=checkbox]',
            checkedRoomsSelector: 'div.checked-room',
            uncheckButtonsSelector: 'button.btn-uncheck',
            checkedRoomsContainerSelector: '#checked-rooms-container',
            infoButtonsSelector: 'button.btn-room-info'
        },
        settings: {},
        $companyButtons: null,
        $vksRooms: null,
        $checkBoxes: null,
        $checkedRooms: null,
        $uncheckButtons: null,
        $checkedRoomsContainer: null,
        $infoButtons: null,
        $roomFilterInput: null,

        init: function ($container, options) {

            var settings = $.extend(this.settings, this.defaultSettings, options || {});

            this.$companyButtons = $(settings.companyButtonsSelector, $container);
            this.$vksRooms = $(settings.vksRoomsSelector, $container);
            this.$checkBoxes = $(settings.checkBoxesSelector, $container);
            this.$checkedRoomsContainer = $(settings.checkedRoomsContainerSelector, $container);
            this.$checkedRooms = $(settings.checkedRoomsSelector, $container);
            this.$infoButtons = $(settings.infoButtonsSelector, $container);
            this.$uncheckButtons = $(settings.uncheckButtonsSelector, $container);
            this.$roomFilterInput = $('#room-filter-input');

            this.$companyButtons.on('click', this.companyButtonClick);
            this.$checkBoxes.on('change', {that: this}, this.checkBoxChange);
            $container.on('click', settings.uncheckButtonsSelector, {that: this}, this.uncheckButtonClick);
            this.$roomFilterInput.on('keyup', this.searchRooms);
            $('#room-filter-reset').on('click', this.resetFilter);

            this.$infoButtons.popover({html: true});
            this.$companyButtons.tooltip();
        },

        companyButtonClick: function () {

            var $companyButtons = teleport.participants.$companyButtons,
                $vksRooms = teleport.participants.$vksRooms,
                $button = $(this);

            $companyButtons.removeClass('active');
            $button.addClass('active');
            $vksRooms.hide();
            $vksRooms.filter(function () {
                return $('input[type=checkbox]', this).data('companyId') == $button.data('id');
            }).fadeIn();
        },

        checkBoxChange: function (event) {

            var $checkBox = $(this);
            var that = event.data.that;

            if ($checkBox.is(':checked')) {
                var $checkedRoom = that.createCheckedRoom($checkBox.val(), $checkBox.data());
                that.$checkedRoomsContainer.append($checkedRoom);
            } else {
                var $removedRoom = that.$checkedRooms.filter(function () {
                    return this.getAttribute('data-room-id') === $checkBox.val();
                });
                that.removeCheckedRoom($removedRoom);
            }
        },

        uncheckButtonClick: function (event) {

            var $button = $(this);
            var that = event.data.that;

            that.$checkBoxes.filter(function () {
                return this.value === $button.parent().data('roomId');
            }).attr('checked', false);

            that.removeCheckedRoom($button.parent());
        },

        removeCheckedRoom: function ($checkedRoom) {

            var $infoButton = $(this.settings.infoButtonsSelector, $checkedRoom);
            var $uncheckButton = $(this.settings.uncheckButtonsSelector, $checkedRoom);

            $infoButton.popover('destroy');
            this.$infoButtons = this.$infoButtons.not($infoButton);
            this.$uncheckButtons = this.$uncheckButtons.not($uncheckButton);
            this.$checkedRooms = this.$checkedRooms.not($checkedRoom);
            $checkedRoom.remove();
        },

        createCheckedRoom: function (roomId, data) {

            var $container = $('<div>', {
                'class': 'btn-group checked-room',
                'data-room-id': roomId
            });

            var $infoButton = $('<button>', {
                'class': 'btn btn-default btn-room-info',
                'type': 'button'
            }).html(data['shortName']).popover({
                'html': true,
                'container': '#vks-participants',
                'placement': 'top',
                'content': '<dl><dt>Название</dt><dd>' + data['name'] + '</dd>' +
                '<dt>Организация</dt><dd>' + data['companyName'] + '</dd>' +
                '<dt>Технический специалист</dt><dd>' + data['contact'] + '</dd>' +
                '<dt>IP адрес</dt><dd>' + data['ipAddress'] + '</dd></dl>'
            });

            var $uncheckButton = $('<button>', {
                'class': 'btn btn-default btn-uncheck',
                'type': 'button'
            }).append($('<span>', {'class': 'glyphicon glyphicon-remove text-danger'}));

            $container.append($infoButton).append($uncheckButton);

            this.$checkedRooms = this.$checkedRooms.add($container);
            this.$infoButtons = this.$infoButtons.add($infoButton);
            this.$uncheckButtons = this.$uncheckButtons.add($uncheckButton);

            return $container;
        },

        searchRooms: function () {
            var value = this.value,
                prev = this.prev || null,
                result = {
                    $rooms: null,
                    $groups: null
                },
                groupIds = [],
                i, max, selector,
                $companyButtons = teleport.participants.$companyButtons;


            if (prev !== null) {
                prev.$rooms.removeClass('found-room');
                if (prev.$groups !== null) {
                    prev.$groups.show();
                }
            }

            if (value.length > 1) {
                result.$rooms = $("div.vks-room:contains('" + value + "')");

                result.$rooms.each(function () {
                    var id = $('input', this).data('company-id');
                    if ($.inArray(id, groupIds) === -1) {
                        groupIds.push(id);
                    }
                });

                max = groupIds.length;
                if (max > 0) {
                    for (i = 0; i < max; i += 1) {
                        selector = "button[data-id='" + groupIds[i] + "']";
                        result.$groups = result.$groups === null ? $companyButtons.not(selector) : result.$groups.not(selector);
                    }

                    result.$groups.hide();
                }

                result.$rooms.addClass('found-room');
                this.prev = result;
                $companyButtons.not(result.$groups).first().trigger('click');
            }
        },

        resetFilter: function () {
            var $roomFilterInput = teleport.participants.$roomFilterInput;
            $roomFilterInput.val('');
            $roomFilterInput.trigger('keyup');
        }
    };

})(jQuery);