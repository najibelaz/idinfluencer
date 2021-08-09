function Main() {
    var self = this;
    var DataTable = false;
    var overplay = $(".loading-overplay");
    var _current_links = [];

    this.init = function () {
        //Call Function
        self.optionMain();
        self.optionPanelMode();
        self.optionAppMod();
        self.actionCaption();
        self.actionItem();
        self.postAll();
        self.actionMultiItem();
        self.actionForm();
        self.enableDatatable();
        self.emojioneArea();
        self.actionIP();
        self.statusWaittingChange();
        self.changeUser();
        self.actionGame();
    };

    this.optionMain = function () {
        $('[data-toggle="tooltip"]').tooltip({
            container: "body",
            trigger: 'hover'
        });

        if ($('.box-report').length > 0) {
            setTimeout(
                function () {
                    $(".box-report li:first-child .actionItem").trigger("click");
                }, 300
            );

            $(document).on("click", ".box-report li .actionItem", function () {
                $(this).parent().addClass("active").siblings().removeClass("active");
            });
        }

        if ($('.datetime').length > 0 || $('.date').length > 0) {

            console.log($("#js_lang").val());
            let jsLang = JSON.parse($("#js_lang").val());





            $('.datetime').datetimepicker({
                controlType: 'select',
                oneLine: true,
                timeFormat: 'HH:mm',
                dateFormat: 'dd/mm/yy',
                minDate: 0,
                minTime: 0,
                currentText: jsLang.btns.now,
                closeText: jsLang.btns.ok,
                timeText: jsLang.label.time,
                beforeShow: function (s, a) {
                    $('.ui-datepicker-wrap').addClass('active');

                },
                onClose: function () {
                    $('.ui-datepicker-wrap').removeClass('active');
                }
            });





            // $(".ui_tpicker_time_label").text(jsLang.label.time);
            // $(".ui-datepicker-close").text(jsLang.btns.ok);
            // $(".ui-datepicker-current").text(jsLang.btns.now);



            // $('.datetime').bootstrapMaterialDatePicker({
            //     weekStart: 0,
            //     format: 'DD/MM/YYYY HH:mm',
            //     time: true,
            //     lang: jsLang.lang
            // });

            $('.datetime').attr('autocomplete', 'off');
            if ($('.datetime').val() == "") {
                $('.datetime').val(moment().format('DD/MM/YYYY hh:mm'));
            }
            $('.date-end').bootstrapMaterialDatePicker({
                weekStart: 0,
                format: 'DD/MM/YYYY',
                time: false,
                lang: jsLang.lang
            })

            $('[id^="ui-datepicker-div"]').wrapAll('<div class="ui-datepicker-wrap"></div>');
            //$('.datetime').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', lang : 'en', weekStart : 1, currentDate: moment().format('DD/MM/YYYY HH:mm') });
            $('.date.nodays').bootstrapMaterialDatePicker({
                format: "MM/YYYY",
                weekStart: 0,
                time: false,
                lang: jsLang.lang
            }).on('change', function (e, date) {
                $('.nodaysform').submit();
            });

            $('.date').mousedown(function () {
                if ($(this).hasClass("nodays")) {
                    $(".dtp").addClass("hidedays");
                } else {
                    $(".dtp").removeClass("hidedays");

                }
            });

            $('.date').bootstrapMaterialDatePicker({
                format: "DD/MM/YYYY",
                weekStart: 0,
                time: false,
                lang: jsLang.lang
            }).on('change', function (e, date) {
                $('.date-end').bootstrapMaterialDatePicker('setMinDate', date);
            });



            $(".dtp .dtp-buttons .dtp-btn-clear").text(jsLang.btns.clear);
            $(".dtp .dtp-buttons .dtp-btn-cancel").text(jsLang.btns.cancel);
            $(".dtp .dtp-buttons .dtp-btn-ok").text(jsLang.btns.ok);

        }

        /*List account*/
        $(document).on("click", ".list-account .item", function () {
            _that = $(this);
            if (_that.hasClass("active")) {
                _that.removeClass("active");
                _that.find("input").removeAttr('checked');
            } else {
                _that.addClass("active");
                _that.find("input").attr('checked', 'checked');
            }
        });
        $(document).on("click", ".datasearchswitch", function () {
            _that = $(this);
            var date = _that.data('dateswitch');
            $('.date.nodays.form-control').val(date);
            $('#ttt form').submit();
        });

        /*Payment Tabs*/
        $(".payment-tabs .item:first-child a").trigger("click");
        $(document).on("click", ".payment-tabs a", function () {
            $(this).parents(".item").addClass("active").siblings().removeClass("active");
        });

        $(document).on("click", ".payment-plan a", function () {
            $(this).find("input").prop('checked', true);
            $(this).parents(".item").addClass("active").siblings().removeClass("active");
        });

        /*Select search*/
        if ($('select.selectpicker').length > 0 || $('.date').length > 0) {
            $('select.selectpicker').selectpicker();
        }

        /*Editor*/
        if ($(".texterea-editor").length > 0) {
            $('.texterea-editor').trumbowyg();
        }

        /*Select all*/
        $(document).on("change", ".checkAll", function () {
            _that = $(this);
            if ($('input:checkbox').hasClass("checkItem")) {
                if (!_that.hasClass("checked")) {
                    $('input.checkItem:checkbox').prop('checked', true);
                    _that.addClass('checked');
                } else {
                    $('input.checkItem:checkbox').prop('checked', false);
                    _that.removeClass('checked');
                }
            }
            return false;
        });

        /*Enable Schedule*/
        $(document).on("change", "#cb-schedule", function () {
            if ($("#cb-schedule").is(':checked')) {
                $("#schedule-option").removeClass("hide");
            } else {
                $("#schedule-option").addClass("hide");
            }
        });

        /*Ajax Load Modal*/
        $(document).on("click", ".ajaxModal", function () {
            var url = $(this).attr('href');
            $('#mainModal').load(url, function () {
                $('#mainModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#mainModal').modal('show');
            });
            return false;
        });

        /*Schedules*/
        if ($(".schedules").length > 0) {
            _he = $(window).height();
            $(".schedules").height(_he - 56);
            $(".sc-calendar").height(_he - 118);
            if ($(".sc-calendar").length > 0) {
                $('.sc-options').niceScroll({
                    cursorcolor: "#ddd",
                    cursorwidth: "10px"
                });
                $('.sc-calendar').niceScroll({
                    cursorcolor: "#ddd",
                    cursorwidth: "10px"
                });
            }

            $(window).resize(function () {
                _he = $(window).height();
                $(".schedules").height(_he - 56);
                $(".sc-calendar").height(_he - 118);
            });

            $(document).on("click", ".sc-toggle-open", function () {
                _options = $(".schedules .sc-options");
                if (!_options.hasClass("active")) {
                    $(this).addClass("active");
                    $(".schedules .sc-options").addClass("active");
                } else {
                    $(this).removeClass("active");
                    $(".schedules .sc-options").removeClass("active");
                }
            });

            $(document).on("change", ".sc-action", function () {
                _that = $(this);
                _that.parent().addClass("active").siblings().removeClass("active");
                _form = _that.closest("form");
                var _data = _form.serialize() + '&' + $.param({
                    token: token
                });
                self.load_schedules(_form, _data);

            });

            $(document).on("click", ".actionDeleteSchedules", function (event) {
                event.preventDefault();
                var _that = $(this);
                var _action = _that.attr("href");
                var _socials = $("[name='sc_delete_social']").val();
                var _type = $("[name='sc_delete_type']:checked").val();
                if (_socials.search("]") != -1) {
                    _socials = JSON.parse(_socials);
                    self.delete_schedules(_action, _type, _socials);

                } else {
                    self.delete_schedules(_action, _type, [_socials]);
                }
                return false;
            });

            _that = $(".schedules-form");
            var _data = _that.serialize() + '&' + $.param({
                token: token
            });
            self.load_schedules(_that, _data);
        }

        if ($(".schedules-list").length > 0) {
            var _page = 1;
            var _that = $(".schedules-list");
            var _action = _that.data("action")
            var _type = _that.find("[name='schedule_type']").val();
            var _account = _that.find("[name='schedule_account']").val();
            var _data = $.param({
                token: token,
                page: 0,
                type: _type,
                account: _account
            });
            self.ajax_post(_that, _action, _data, null);

            $(window).scroll(function () {
                _scrollbar_pos = $(window).scrollTop();
                _widown_height = $(document).height() - $(window).height();
                if (_scrollbar_pos >= _widown_height * 0.95) {

                    _processing = true;
                    if (_processing) {
                        _processing = false;
                        _id = _that.attr("data-id");
                        _data = $.param({
                            token: token,
                            page: _page,
                            id: _id
                        });
                        _return = self.ajax_post(_that, _action, _data, null);
                        if (!_return) {

                            _processing = true;
                            _page = _page + 1;
                            $(".schedules-list").attr("data-page", _page);

                        }
                    }

                }

            });
        }
        $('.download_all').click(function () {
            $('table tr').each(function () {
                if ($(this).find('input[type=checkbox]').prop('checked')) {

                    window.open(
                        $(this).find('input[type=checkbox]').val(),
                        '_blank' // <- This is what makes it open in a new window.
                    );

                }
            })
        });
        $('.participer-form #code_postal').keyup(function () {
            let l = "https://geo.api.gouv.fr/communes?codePostal=" + $(this).val();

            $.get(l, function (data) {
                console.log(data);
                $('.participer-form #ville').val(data[0].nom)
            });
        });


        $(document).on("click", ".addemailsucess", function () {
            if ($('.parrainageEmail .form-group').length < 6) {
                $('.parrainageEmail').append(
                    `<div class="form-group">
                        <div class="input-group">
                            <div class="placeholder-grp mb-3">
                                <input type="email" class="form-control" name="email[]"value="">
                                <label>
                                    ${$("input[type='email']~label").eq(1).text()}
                                </label>
                            </div>
                            <button class="btn text-danger removeemail">X</button>
                        </div>
                    </div>`
                );
            }
        });
        $(document).on("click", ".removeemail", function () {
            $(this).parents('.form-group').remove();
        });

        (function () {
            if ($(".placeholder-grp").length) {
                $(".placeholder-grp .form-control").each(function () {
                    if ($(this).val() != "") {
                        $(this).addClass("hasval")
                    }
                });

                $(".placeholder-grp .form-control").focusout(function (e) {
                    e.preventDefault();
                    console.log("her 0");
                    if ($(this).val() != "") {
                        $(this).addClass("hasval")
                    } else {
                        $(this).removeClass("hasval")
                    }
                });
                $('.parrain_form').validate({
                    rules: {
                        "email[]": "required"
                    },
                })
            }
        })();



    };

    this.optionAppMod = function () {
        if ($('.app-mod').length > 0) {
            $('.am-select-account').vtdropdown();

            // _h = $(window).height() - 56;
            // $('.am-sidebar').height(_h);
            // $('.app-mod').height(_h);

            // $(window).resize(function(){
            //     _h = $(window).height() - 56;
            //     $('.am-sidebar').height(_h);
            //     $('.app-mod').height(_h);
            // });

            if ($(window).width() < 768) {
                setTimeout(function () {
                    $('.am-scroll').getNiceScroll().remove();
                    $('.am-scroll').attr("style", "");
                }, 200);

                setInterval(function () {
                    $('.am-scroll').attr("style", "");
                }, 2000);
            } else {
                $('.am-scroll').niceScroll({
                    cursorcolor: "#ddd",
                    cursorwidth: "10px",
                    horizrailenabled: false
                });

                setInterval(function () {
                    $('.am-scroll').getNiceScroll().resize();
                }, 2000);
            }
        }
        $(document).on("keyup", ".box-search .pn-search", function () {
            _key = $(this).val().toLowerCase();
            $(".item-3.border").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search1 .pn-search", function () {
            _key = $(this).val().toLowerCase();
            $(".draggable-left .pn-group-item").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search2 .pn-search", function () {
            _key = $(this).val().toLowerCase();
            $(".draggable-right .pn-group-item").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search-pages .pn-search-pages", function () {
            _key = $(this).val().toLowerCase();
            $(".pn-group-pages").each(function () {
                _that = $(this);
                _name = _that.find("span").text().toLowerCase();
                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search-groupes .pn-search-groupes", function () {
            _key = $(this).val().toLowerCase();
            $(".pn-group-groupes").each(function () {
                _that = $(this);
                _name = _that.find("span").text().toLowerCase();
                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search3 .pn-search", function () {
            _key = $(this).val().toLowerCase();
            $(".draggable-left .pn-group-item").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".box-search4 .pn-search", function () {
            _key = $(this).val().toLowerCase();
            $(".draggable-right .pn-group-item").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("keyup", ".clients-search-body .form-control", function () {
            _key = $(this).val().toLowerCase();
            $(".clients-list .client-item").each(function () {
                _that = $(this);
                _name = _that.find(".name").text().toLowerCase();

                if (_name.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        $(document).on("click", ".am-mobile-menu a", function () {
            _that = $(this);
            _action = _that.data("am-open");
            $('.am-scroll').attr("style", "");
            switch (_action) {
                case "account":
                    $(".am-sidebar,.am-content,.am-preview").removeClass("active");
                    $(".am-sidebar").addClass("active");
                    break;

                case "content":
                    $(".am-sidebar,.am-content,.am-preview").removeClass("active");
                    $(".am-content").addClass("active");
                    break;

                case "preview":
                    $(".am-sidebar,.am-content,.am-preview").removeClass("active");
                    $(".ap-preview").addClass("active");
                    break;
            }

            $(".am-mobile-menu a").removeClass("active");
            _that.addClass("active");

            return false;
        });

        $(document).on('click', '.ap-select-all', function () {
            _that = $(this);
            if ($('.am-sidebar input:checkbox').hasClass('filled-in')) {
                if (!_that.hasClass('checked')) {
                    $('.am-sidebar .item input:checkbox').prop('checked', true);
                    $('.am-sidebar .item input:checkbox').parents('.item').addClass('active');
                    _that.removeClass('btn-primary btn-default').addClass('btn-primary checked');
                } else {
                    $('.am-sidebar .item input:checkbox').prop('checked', false);
                    $('.am-sidebar .item input:checkbox').parents('.item').removeClass('active');
                    _that.removeClass('btn-primary btn-default checked').addClass('btn-default');
                }
            }
            return false;
        });

        $(document).on("click", ".app-mod .am-sidebar .item a", function () {
            _that = $(this);
            _checkbox = _that.find("input");
            _type = _checkbox.attr("type");
            if (_checkbox.is(":checked")) {
                if (_type != "radio") {
                    _that.parents(".item").removeClass("active");
                    _checkbox.prop('checked', false);
                }
            } else {
                if (_type == "radio") {
                    _that.parents(".item").siblings().removeClass("active");
                }
                _that.parents(".item").addClass("active");
                _checkbox.prop('checked', true);
            }
            $('.ap-select-all').removeClass('btn-primary btn-default checked').addClass('btn-default');
        });

        $(document).on("click", ".actionGroups", function () {
            _that = $(this);
            _items = _that.data("item");
            $(".app-mod .am-sidebar .item").each(function () {
                _item = $(this).find("input").val();
                if (_item != undefined && _item != "") {
                    _item_array = _item.split("-");
                    if (_item_array.length > 0) {
                        if (_item_array.length == 1) {
                            _item = _item_array[0];
                        } else {
                            _item = _item_array[1];
                        }

                        $(this).parents(".item").removeClass("active");
                        $(this).find("input").prop('checked', false);

                        if (_items.indexOf(_item) != -1) {
                            $(this).parents(".item").addClass("active");
                            $(this).find("input").prop('checked', true);
                        }
                    }
                }
            });
            $('.ap-select-all').removeClass('btn-primary btn-default checked').addClass('btn-default');
        });

        $(".am-search").keyup(function () {
            _key = $(this).val().toLowerCase();
            $(".am-sidebar .box-list li").each(function () {
                _that = $(this);
                _name = _that.find(".title").text().toLowerCase();
                _type = _that.find(".desc").text().toLowerCase();

                if (_name.search(_key) != -1 || _type.search(_key) != -1) {
                    _that.show();
                } else {
                    _that.hide();
                }
            });
        });
        

        $(document).on("click", ".am-box-list .item a", function () {
            _that = $(this);
            _checkbox = _that.find("input");

            if (_checkbox.attr("type") == "radio") {
                _that.parents(".am-box-list").find("input").prop('checked', false);
                _that.parents(".am-box-list").find(".item").removeClass("active");
            }

            if (_checkbox.is(":checked")) {
                _that.parents(".item").removeClass("active");
                _checkbox.prop('checked', false);
            } else {
                _that.parents(".item").addClass("active");
                _checkbox.prop('checked', true);
            }
        });
    };

    this.optionPanelMode = function () {
        if ($('.pn-mode').length > 0) {
            // _h = $(window).height() - 56;
            // $('.pn-sidebar').height(_h);
            // $('.pn-content').height(_h);
            // $('.pn-mode').height(_h);

            // $(window).resize(function(){
            //     _h = $(window).height() - 56;
            //     $('.pn-sidebar').height(_h);
            //     $('.pn-content').height(_h);
            //     $('.pn-mode').height(_h);
            // });

            $('.pn-scroll').niceScroll({
                cursorcolor: "#ddd",
                cursorwidth: "10px",
                horizrailenabled: false
            });

            setInterval(function () {
                $('.pn-scroll').getNiceScroll().resize();
            }, 2000);

            $(".pn-search").keyup(function () {
                _key = $(this).val().toLowerCase();
                $(".pn-sidebar .item").each(function () {
                    _that = $(this);
                    _name = _that.find(".title").text().toLowerCase();
                    _type = _that.find(".desc").text().toLowerCase();

                    if (_name.search(_key) != -1 || _type.search(_key) != -1) {
                        _that.show();
                    } else {
                        _that.hide();
                    }
                });
            });

            $(document).on("click", ".pn-sidebar .item a", function () {
                _that = $(this);
                _checkbox = _that.parents(".item");

                _that.parents(".item").siblings().removeClass("active");
                _that.parents(".item").addClass("active");
                _checkbox.prop('checked', true);
            });

            $(document).on("click", ".pn-toggle-open", function () {
                _options = $(".pn-mode .pn-sidebar");
                if (!_options.hasClass("active")) {
                    $(this).addClass("active");
                    $(".pn-mode .pn-sidebar").addClass("active");
                } else {
                    $(this).removeClass("active");
                    $(".pn-mode .pn-sidebar").removeClass("active");
                }
            });
        }
    };

    this.delete_schedules = function (_action, _type, _socials) {
        var _social = _socials.shift();
        var _that = $(".schedules-form");
        var _data = $.param({
            token: token,
            sc_delete_type: _type,
            sc_delete_social: _social
        });
        self.ajax_post(_that, _action, _data, function (_result) {
            overplay.addClass("show");
            if (_socials.length > 0) {
                setTimeout(function () {
                    self.delete_schedules(_action, _type, _socials);
                }, 100);

            } else {
                if (_result.status == "success") {
                    setTimeout(function () {
                        var _data = _that.serialize() + '&' + $.param({
                            token: token
                        });
                        self.load_schedules(_that, _data);
                        overplay.removeClass("show");
                    }, 100);
                } else {
                    overplay.removeClass("show");
                }

            }


        });
    };

    this.load_schedules = function (_that, _data) {
        self.ajax_post(_that, PATH + "schedules/calendar", _data, function () {
            setTimeout(function () {
                $('.sc-calendar').getNiceScroll().resize();
            }, 1000);
        });
    };

    this.emojioneArea = function () {
        //Emoji texterea
        let jsLang = JSON.parse($("#js_lang").val());
        if ($('.post-message').length > 0) {
            el = $(".post-message").emojioneArea({
                hideSource: true,
                useSprite: false,
                pickerPosition: "bottom",
                filtersPosition: "top"
            });



            setTimeout(function () {
                $(".emojionearea-editor").niceScroll({
                    cursorcolor: "#ddd"
                });

                $(".emojionearea-search .search").attr("placeholder", jsLang.emojionearea.search);
                $(".emojionearea-button").attr("title", jsLang.emojionearea.emojiTitle);
            }, 1000);
        }
    };

    this.enableDatatable = function (table_full) {

        let jsLang = JSON.parse($("#js_lang").val());
        /*Reponsive table*/
        if ($('.table-datatable').length > 0 && $(".table_empty").length == 0) {
            let colexp = ':visible';

            if ($('.table-datatable').data("colexp")) {
                colexp = $('.table-datatable').data("colexp");
            }

            if (table_full == undefined) {
                $(".table-datatable").each(function () { 
                    let $title=$(this).data("title")!=undefined ? $(this).data("title") : $("title").text();
                    
                    $(this).DataTable({
                        responsive: false,
                        searching: true,
                        paging: true,
                        info: false,
                        scrollX: false,
                        autoWith: true,
                        bSort: true,
                        lengthChange: true,
                        language: jsLang.datatable.language,
                        dom: 'Bflirtp',
                        buttons: [{
                                extend: 'copyHtml5',
                                exportOptions: {
                                    columns: colexp
                                }
                            },
                            {
                                extend: 'csv',
                                text: 'CSV',
                                charset: 'utf-8',
                                extension: '.csv',
                                fieldSeparator: ';',
                                fieldBoundary: '',
                                bom: true,
                                title:$title,
                                exportOptions: {
                                    columns: colexp
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                title:$title,
                                exportOptions: {
                                    columns: colexp
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                title:$title,
                                exportOptions: {
                                    columns: colexp
                                }
                            }
                        ]
                    });
                });

            } else {
                var extensions = {
                    "sFilter": "dataTables_filter right form-group p15 mb0"
                }
                // Used when bJQueryUI is false
                $.extend($.fn.dataTableExt.oStdClasses, extensions);
                // Used when bJQueryUI is true
                $.extend($.fn.dataTableExt.oJUIClasses, extensions);

                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sFilterInput": "form-control lead mb0"
                });

                DataTable = $('.table-datatable').DataTable({
                    responsive: false,
                    searching: false,
                    paging: true,
                    info: false,
                    scrollX: false,
                    autoWith: true,
                    bSort: true,
                    lengthChange: true,
                    language: jsLang.datatable.language,
                    dom: 'Bflirtp',
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: colexp
                            }
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            charset: 'utf-8',
                            extension: '.csv',
                            fieldSeparator: ';',
                            fieldBoundary: '',
                            bom: true,
                            // title: 'Data export',
                            exportOptions: {
                                columns: colexp
                            }
                        },
                        {
                            // title: 'Data export',
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: colexp
                            }
                        },
                        {
                            // title: 'Data export',
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: colexp
                            }
                        }
                    ]
                });
                $('.dataTables_filter input').attr("placeholder", enter_keyword_to_search);
            }
        }
        $(".dataTables_wrapper .dt-buttons a.dt-button.buttons-copy").html(jsLang.btns.copy);
        $(".dataTables_wrapper .dt-buttons a.dt-button.buttons-print").html(jsLang.btns.print);
    };

    this.postAll = function () {
        if ($(".ap-scroll").length > 0) {
            // _wh = $(window).height();
            // $(".all-post").height(_wh - 56);
            // $(window).resize(function(){
            //     _wh = $(window).height();
            //     $(".all-post").height(_wh - 56);
            //     $('.ap-scroll').getNiceScroll().resize();
            // });

            self.actionPostPreview();

            if ($(window).width() < 768) {
                setTimeout(function () {
                    $('.ap-scroll').getNiceScroll().remove();
                    $('.ap-scroll').attr("style", "");
                }, 200);

                setInterval(function () {
                    $('.ap-scroll').attr("style", "");
                }, 2000);
            } else {
                $('.ap-scroll').niceScroll({
                    cursorcolor: "#ddd",
                    cursorwidth: "10px",
                    horizrailenabled: false
                });

                setInterval(function () {
                    $('.ap-scroll').getNiceScroll().resize();
                }, 2000);
            }

            $(".ap-search").keyup(function () {
                _key = $(this).val().toLowerCase();
                $(".ap-left .box-list li").each(function () {
                    _that = $(this);
                    _name = _that.find(".title").text().toLowerCase();
                    _type = _that.find(".desc").text().toLowerCase();

                    if (_name.search(_key) != -1 || _type.search(_key) != -1) {
                        _that.show();
                    } else {
                        _that.hide();
                    }
                });
            });

            $(document).on('click', '.ap-select-all', function () {
                _that = $(this);
                if ($('.ap-left input:checkbox').hasClass('filled-in')) {
                    if (!_that.hasClass('checked')) {
                        $('.ap-left .item input:checkbox').prop('checked', true);
                        $('.ap-left .item input:checkbox').parents('.item').addClass('active');
                        _that.removeClass('btn-primary btn-default').addClass('btn-primary checked');
                    } else {
                        $('.ap-left .item input:checkbox').prop('checked', false);
                        $('.ap-left .item input:checkbox').parents('.item').removeClass('active');
                        _that.removeClass('btn-primary btn-default checked').addClass('btn-default');
                    }
                }
                return false;
            });

            $(document).on("click", ".ap-mobile-menu a", function () {
                _that = $(this);
                _action = _that.data("ap-open");
                $('.ap-scroll').attr("style", "");
                switch (_action) {
                    case "account":
                        $(".ap-left,.ap-box-content,.ap-box-preview").removeClass("active");
                        $(".ap-left").addClass("active");
                        break;

                    case "content":
                        $(".ap-left,.ap-box-content,.ap-box-preview").removeClass("active");
                        $(".ap-box-content").addClass("active");
                        break;

                    case "preview":
                        $(".ap-left,.ap-box-content,.ap-box-preview").removeClass("active");
                        $(".ap-box-preview").addClass("active");
                        break;
                }

                $(".ap-mobile-menu a").removeClass("active");
                _that.addClass("active");

                return false;
            });
        }


        //Enable Schedule
        $(document).on("change", ".enable_post_all_schedule", function () {

            _that = $(this);
            if (_that.is(':checked')) {
                $('.time_post').removeAttr('disabled');
                $('.box-repeat').removeClass('hide');
                $('.btnPostNow,.btnGoNow').addClass("hide");
                $('.btnSchedulePost').removeClass("hide");
                _that.addClass('checked');
            } else {
                $('.time_post').attr('disabled', true);
                $('.box-repeat').addClass('hide');
                $('.btnPostNow,.btnGoNow').removeClass("hide");
                $('.btnSchedulePost').addClass("hide");
                _that.removeClass('checked');
            }
            return false;
        });

        enable_post_all_schedule = $(".enable_post_all_schedule");
        if (enable_post_all_schedule.is(':checked')) {
            $('.time_post').removeAttr('disabled');
            $('.box-repeat').removeClass('hide');
            $('.btnPostNow,.btnGoNow').addClass("hide");
            $('.btnSchedulePost').removeClass("hide");
            enable_post_all_schedule.addClass('checked');
        } else {
            $('.time_post').attr('disabled', true);
            $('.box-repeat').addClass('hide');
            $('.btnPostNow,.btnGoNow').removeClass("hide");
            $('.btnSchedulePost').addClass("hide");
            enable_post_all_schedule.removeClass('checked');
        }

        $(document).on("click", ".all-post .list-action li a", function () {
            _that = $(this);
            _li = _that.parents("li");
            _id = _that.attr("href");

            $(".add_link").val("").attr("data-result", "");
            $(".file-manager-list-images").removeClass("active");
            $(".file-manager-list-images .add-image").show();
            $(".file-manager-list-images .item").remove();
            $(_id).find(".file-manager-list-images").addClass("active");

        });

        $(document).on("click", ".all-post .ap-left .item a", function () {
            _that = $(this);
            _checkbox = _that.find("input");
            if (_checkbox.is(":checked")) {
                _that.parents(".item").removeClass("active");
                _checkbox.prop('checked', false);
            } else {
                _that.parents(".item").addClass("active");
                _checkbox.prop('checked', true);
            }
            $('.ap-select-all').removeClass('btn-primary btn-default checked').addClass('btn-default');
        });

        $(document).on("click", ".actionGroups", function () {
            _that = $(this);
            _items = _that.data("item");
            $(".all-post .item").each(function () {
                _item = $(this).find("input").val();
                if (_item != undefined && _item != "") {
                    _item_array = _item.split("-");
                    if (_item_array.length > 0) {
                        if (_item_array.length == 1) {
                            _item = _item_array[0];
                        } else {
                            _item = _item_array[1];
                        }

                        $(this).parents(".item").removeClass("active");
                        $(this).find("input").prop('checked', false);

                        if (_items.indexOf(_item) != -1) {
                            $(this).parents(".item").addClass("active");
                            $(this).find("input").prop('checked', true);
                        }
                    }
                }
            });
            $('.ap-select-all').removeClass('btn-primary btn-default checked').addClass('btn-default');
        });

        $(document).on("click", ".ap-schedule .action-type .action-select", function () {
            _that = $(this);
            _type = _that.attr("data-type");
            _that.next().prop('checked', true);
            //self.loadAllPreview();
        });

        $(document).on('submit', ".actionPostAllForm", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _button = $(this).find("button[type=submit]:focus");;
            var _button_name = _button.attr("name");
            var _button_value = _button.attr("value");
            var _action = _that.attr("action");
            var _data = _that.serialize();
            var _data = _data + '&' + $.param({
                token: token,
                action: _button_value
            });

            self.ajax_post(_that, _action, _data, function (_result) {

                switch (_result.status) {
                    case "success":
                        self.actionPostAll(_that);
                        break;

                    case "warning":
                        self.confirm(_result.errors);
                        break;
                }
            });

        });

        $(document).on('click', ".btnPostTry", function (event) {
            var _that = $(this);
            $('#modal-custom').iziModal('close');
            self.actionPostAll(_that);
            return false;
        });
    };

    this.actionPostAll = function (_that) {
        var _form = $(".btnSchedulePost").closest("form");
        var _action = _that.attr("href");
        var _params = _that.data("params");
        var _data = _form.serialize();
        var _data = _data + '&' + $.param({
            token: token
        });
        self.ajax_post(_that, _action, _data, null);
        return false;
    };

    this.actionPostPreview = function () {
        $(document).on("change", ".all-post .add_link", function () {
            var _that = $(this);
            var _action = PATH + "post/get_link_info";
            var _link = _that.val();
            var _data = $.param({
                token: token,
                link: _link
            });
            _that.prev().fadeIn();
            $.post(_action, _data, function (_result) {

                _that.attr("data-result", JSON.stringify(_result));

                //Save File URL
                if (_result.image != "") {
                    FileManager.saveFile(_result.image);
                }

                _that.prev().fadeOut();
            }, 'json');

        });
    };

    this.loadAllPreview = function () {
        var _form = $(".btnSchedulePost").closest("form");
        var _action = PATH + "post/previewer";
        var _data = _form.serialize();
        var _data = _data + '&' + $.param({
            token: token
        });
        $(".loading-box").fadeIn();
        $.post(_action, _data, function (_result) {
            $(".box-load-previewer").html(_result);
            $(".loading-box").fadeOut();
        });

        return false;
    };

    this.actionCaption = function () {
        var _wrap_caption = $(".load-caption");
        var _body_caption = $(".load-caption .caption-body");

        //Get Caption
        $(document).on("click", ".getCaption", function () {
            _that = $(this);
            _name = _that.parents(".form-caption").find("textarea").attr("name");
            _wrap_caption.attr("data-field", _name).fadeIn();

            self.statusCardOverplay("show");
            self.statusCardOverplay("hide");

            _data = {
                token: token
            };

            $.post(PATH + "caption/get_caption", _data, function (_result) {
                _body_caption.append(_result);
            });
            return false;
        });

        $(document).on("click", ".saveCaption", function () {
            _that = $(this);
            _caption = _that.parents(".form-caption").find("textarea").val();
            _data = {
                token: token,
                caption: _caption
            };

            if (_caption != "") {
                self.overplay();
                $.post(PATH + "caption/save_caption", _data, function (_result) {
                    //Message
                    if (_result.status != undefined) {
                        self.notify(_result.message, _result.status);
                    }

                    overplay.hide();
                }, 'json');
            }
            return false;
        });

        $(document).on("click", ".load-caption .item", function () {
            _that = $(this);
            _name = _wrap_caption.attr("data-field");
            _caption = _that.attr("data-content");

            var el = $("textarea[name='" + _name + "']").emojioneArea();
            el[0].emojioneArea.setText(_caption);

            setTimeout(function () {
                _body_caption.find(".scroll-content").html("");
            }, 300);
            _wrap_caption.fadeOut();
        });

        $(document).on("click", ".caption-load-more", function () {
            _that = $(this);
            _page = _that.attr("data-page");
            _body_caption = $(".load-caption .caption-body");

            $(".wrap-load-more").remove();
            $.post(PATH + "caption/get_caption/" + _page, _data, function (_result) {
                _body_caption.append(_result);
            });
            return false;
        });

        $(document).on("click", ".load-caption .caption-close", function () {
            _body_caption.find(".scroll-content").html("");
            _wrap_caption.fadeOut();
        });
    };
    this.actionGame = function () {
        var _wrap_Game = $(".load-Game");
        var _body_Game = $(".load-Game .Game-body.scroll-content");
        $(document).on("click", ".copyulink", function (e) {
            e.preventDefault();
            _that = $(this);
            var dummy = $('<input>').val(_that.attr('href')).appendTo('body').select();
            document.execCommand('copy');
            dummy.remove();
            self.notify('copier avec succès', 'success')
        });
        //Get Caption
        $(document).on("click", ".getGame", function () {
            _that = $(this);
            _wrap_Game.attr("data-field", 'Game').fadeIn();

            self.statusCardOverplay("show");
            self.statusCardOverplay("hide");

            _data = {
                token: token
            };

            $.post(PATH + "jeux_concours/get_Game", _data, function (_result) {
                _body_Game.append(_result);
            });
            return false;
        });

        $(document).on("click", ".load-Game .item", function (e) {
            e.preventDefault();
            _that = $(this);
            var dummy = $('<input>').val(_that.attr('href')).appendTo('body').select();
            document.execCommand('copy');
            dummy.remove();
            self.notify('Url copier avec succès', 'success')
        });

        $(document).on("click", ".Game-load-more", function () {
            _that = $(this);
            _page = _that.attr("data-page");
            _body_Game = $(".load-Game .Game-body");

            $(".wrap-load-more").remove();
            $.post(PATH + "jeux_concours/get_Game/" + _page, _data, function (_result) {
                _body_Game.append(_result);
            });
            return false;
        });

        $(document).on("click", ".load-Game .Game-close", function () {
            _body_Game.find(".scroll-content").html("");
            _wrap_Game.fadeOut();
        });
    };

    this.actionIP = function () {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://api.ip.sb/geoip",
            "dataType": "jsonp",
            "method": "GET",
            "headers": {
                "Access-Control-Allow-Origin": "*"
            }
        }

        $.ajax(settings).done(function (response) {
            timezone = response.timezone;
            $.post(PATH + "auth/timezone", {
                token: token,
                timezone: timezone
            }, function (_result) {});
            $(".auto-select-timezone").val(timezone);
        });
    };

    this.actionItem = function () {
        $(document).on('click', ".actionItem", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = _that.attr("href");
            var _id = _that.data("id");
            var _data = $.param({
                token: token,
                id: _id
            });

            self.ajax_post(_that, _action, _data, null);
            return false;
        });
        $(document).on('click', ".actionItemgagnant", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = _that.attr("href");
            var _id = _that.data("id");
            var _data = $.param({
                token: token,
                id: _id
            });

            self.ajax_post(_that, _action, _data, function (_result) {
                var winhref = $('.winnergame a').attr('href')
                $('.winnergame').removeClass('d-none').find('a').attr('href', winhref + "/" + _result.ids).text(_result.nameg);

            });
            return false;
        });
    };

    this.actionMultiItem = function () {
        $(document).on('click', ".actionMultiItem", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _form = _that.closest("form");
            var _action = _that.attr("href");
            var _params = _that.data("params");
            var _data = _form.serialize();
            var _data = _data + '&' + $.param({
                token: token
            }) + "&" + _params;
            self.ajax_post(_that, _action, _data, null);
            return false;
        });
    };

    this.actionForm = function () {
        $(document).on('submit', ".actionForm", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = _that.attr("action");
            var _data = _that.serialize();
            var _data = _data + '&' + $.param({
                token: token
            });

            self.ajax_post(_that, _action, _data, null);
        });
        $(document).on('submit', ".actionFormpart", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = _that.attr("action");
            var _data = _that.serialize();
            var _data = _data + '&' + $.param({
                token: token
            });

            self.ajax_post(_that, _action, _data, function (_result) {
                if (_result.ids) {
                    var redirect = _that.data('redirecte');
                    var id_game = _that.find('input[name="id_game"]').val();
                    window.location.href = redirect + '/' + _result.ids + '/' + id_game;
                }
            });
        });
        $(document).on('submit', ".actionFormadd", function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = _that.attr("action");
            var _data = _that.serialize();
            var _data = _data + '&' + $.param({
                token: token
            });

            self.ajax_post(_that, _action, _data, function (_result) {
                $('.actionForm input[name="ids"]').val(_result.id);
                $('.actionForm').submit();
            });
        });
    };

    this.ajax_post = function (_that, _action, _data, _function) {
        var _confirm = _that.data("confirm");
        var _transfer = _that.data("transfer");
        var _type_message = _that.data("type-message");
        var _rediect = _that.data("redirect");
        var _content = _that.data("content");
        var _append_content = _that.data("append_content");
        var _callback = _that.data("callback");
        var _hide_overplay = _that.data("hide-overplay");
        var _type = _that.data("result");
        var _object = false;
        if (_type == undefined) {
            _type = 'json';
        }

        if (_confirm != undefined) {
            if (!confirm(_confirm)) return false;
        }

        if (!_that.hasClass("disabled")) {
            if (_hide_overplay == undefined || _hide_overplay == 1) {
                self.overplay();
            }
            _that.addClass("disabled");
            $.post(_action, _data, function (_result) {

                //Check is object
                if (typeof _result != 'object') {
                    try {
                        _result = $.parseJSON(_result);
                        _object = true;
                    } catch (e) {
                        _object = false;
                    }
                } else {
                    _object = true;
                }

                //Run function
                if (_function != null) {
                    _function.apply(this, [_result]);
                }

                //Callback function
                if (_result.callback != undefined) {
                    self.callbacks(_result.callback);
                }

                //Callback
                if (_callback != undefined) {
                    var fn = window[_callback];
                    if (typeof fn === "function") fn(_result);
                }

                //Using for update
                if (_transfer != undefined) {
                    _that.removeClass("tag-success tag-danger").addClass(_result.tag).text(_result.text);
                }

                //Add content
                if (_content != undefined && _object == false) {
                    if (_append_content != undefined) {
                        $("." + _content).append(_result);
                    } else {
                        $("." + _content).html(_result);
                    }

                    //Enable DataTable
                    if (_result.search("table-datatable") != -1) {
                        self.enableDatatable(true);
                    }
                }

                //Hide Loading
                overplay.hide();
                _that.removeClass("disabled");

                //Redirect
                self.redirect(_rediect, _result.status);

                //Message
                if (_result.status != undefined) {
                    switch (_type_message) {
                        case "text":
                            self.notify(_result.message, _result.status);
                            break;

                        default:
                            self.notify(_result.message, _result.status);
                            break;
                    }
                }

            }, _type).fail(function () {
                _that.removeClass("disabled");
            });
        }

        return false;
    };

    this.callbacks = function (_function) {
        $("body").append(_function);
    };

    this.overplay = function () {
        overplay.show();
        if ($(".modal").hasClass("in")) {
            overplay.addClass("top");
        } else {
            overplay.removeClass("top");
        }
    };

    this.redirect = function (_rediect, _status) {
        if (_rediect != undefined && _status == "success") {
            setTimeout(function () {
                window.location.assign(_rediect);
            }, 500);
        }
    };

    this.confirm = function (_data) {
        $(".ap-data-errors").html(_data);

        $("#modal-custom").iziModal({
            group: 'grupo1',
            history: false,
            overlayClose: false,
            width: 600,
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeInDown',
            navigateCaption: true,
            navigateArrows: "false",
            onOpened: function () {
                //console.log('onOpened');
            },
            onClosed: function () {
                //console.log('onClosed');  
            }
        });
        $('#modal-custom').iziModal('open');
    };

    this.notify = function (_message, _type) {
        if (_message != undefined && _message != "") {
            switch (_type) {
                case "success":
                    backgroundColor = "#33c15d";
                    break;

                case "error":
                    backgroundColor = "#f4282d";
                    break;

                default:
                    backgroundColor = "#CCD5DB";
                    break;
            }

            iziToast.show({
                theme: 'dark',
                icon: 'ft-bell',
                title: '',
                position: 'bottomCenter',
                message: _message,
                backgroundColor: backgroundColor,
                progressBarColor: 'rgb(255, 255, 255, 0.5)',
            });
        }
    };

    this.statusOverplay = function (_status) {
        if (_status == undefined || _status == "show") {
            $(".hide-overplay").addClass("loading-overplay").removeClass("hide-overplay");
        } else {
            $(".loading-overplay").addClass("hide-overplay").removeClass("loading-overplay");
        }
    };

    this.statusCardOverplay = function (_status) {
        if (_status == undefined || _status == "show") {
            $(".card-overplay").fadeIn();
        } else {
            $(".card-overplay").fadeOut();
        }
    };

    this.changeUser = function (_status) {
        $('.btn-mang').click(function (event) {
            event.preventDefault();
            var _that = $(this);
            var _action = $(this).attr('href');
            var _data = $.param({
                token: token,
            });
            self.ajax_post(_that, _action, _data, null);

        });
        $(document).on("click", ".actionDeleteModele", function (event) {
            event.preventDefault();
            var id = $(this).data('mid');
            var _that = $(this);
            var _action = $(this).attr('href');
            var _data = $.param({
                token: token,
                id: id,
            });
            self.ajax_post(_that, _action, _data, null);
        });

    };

    this.statusWaittingChange = function () {
        $('.validPost').click(function () {

            var social = $(this).data('social');
            var pid = $(this).data('pid');
            var _that = $(this);
            var _action = PATH + "waiting/ajax_update_status_valid";
            var _data = $.param({
                token: token,
                pid: pid,
                social: social
            });
            self.ajax_post(_that, _action, _data, null);

        });
        $('form .form-control').change(function () {
            if ($(this).val() != "") {
                $(this).addClass('hasval');
            } else {
                $(this).removeClass('hasval');
            }
        })
        $('form .form-control').each(function () {
            if ($(this).val() != "") {
                $(this).addClass('hasval');
            } else {
                $(this).removeClass('hasval');
            }
        })
        $('.invalidPost').click(function () {

            var social = $(this).data('social');
            var reason = $(this).parents('.reason-box').find('textarea').val();
            var pid = $(this).data('pid');
            var _that = $(this);
            var _action = PATH + "waiting/ajax_update_status_invalid";
            var _data = $.param({
                token: token,
                pid: pid,
                social: social,
                reason: reason
            });
            if (reason != "") {
                self.ajax_post(_that, _action, _data, null);
            } else {
                $('.reason-box .body').append('<label class="error">champs obligatoire</label>')
            }

        });
    };

    this.removeParam = function (key, sourceURL) {
        var rtn = "",
            param,
            params_arr = [],
            queryString = sourceURL.split("?")[0];
        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }
            rtn = params_arr.join("&");
        }
        return rtn;
    };
    this.showAjaxLoaderMessage = function (title, text, code) {
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, code);
    }
}
Main = new Main();
$(function () {
    Main.init();
});


function executeFunctionByName(functionName, context /*, args */ ) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for (var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    return context[func].apply(context, args);
}