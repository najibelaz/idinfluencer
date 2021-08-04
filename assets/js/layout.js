function Layout() {
    var self = this;
    var chart_color = ["rgba(10,187,135,1)", "rgba(255,117,136, 0.7)", "rgba(255,168,125,0.7)", "rgba(156,39,176,0.7)", "rgba(28,188,216,0.7)", "rgba(64,78,103,0.7)"];
    this.init = function () {
        //Callback
        self.optionLayout();
        self.sidebar();
    };

    this.optionLayout = function () {
        $('.webuiPopover').webuiPopover({
            content: 'Content',
            width: 250,
            trigger: 'hover'
        });
        if ($(window).width() > 786) {
            $('a.menuPopover').webuiPopover({
                container: "#menucontentwebuiPopover",
                placement: 'right-bottom',
                trigger: 'hover',
                padding: false,
                animation: "fade", //pop
                delay: {
                    show: 200,
                    hide: 100
                },
                content: function (data) {
                    setTimeout(function () {
                        if ($(".webui-popover.in .menu-scroll-content").length > 0) {
                            const ps_menu = new PerfectScrollbar('.menu-scroll-content', {
                                wheelSpeed: 2,
                                wheelPropagation: true,
                                minScrollbarLength: 20,
                                suppressScrollX: true
                            });
                            ps_menu.update();
                        }
                    }, 500);
                    var html = "<ul class='menu-content menu-scroll-content'>" + $(this).next().html() + "</ul>";
                    return html;
                }
            });
        } else {
            $(document).on('click', '.sidebar .menuPopover', function () {
                _that = $(this);
                $(".nav-item").removeClass("active");
                $(".nav-item .menu-content").slideUp();
                _that.parents("li").find(".menu-content").slideDown();
            });
        }


        if ($(".scrollbar").length > 0) {
            $('.scrollbar').scrollbar({
                "autoUpdate": true
            });
        }

        if ($("select.custom").length > 0) {
            $("select.custom").each(function () {
                var sb = new SelectBox({
                    selectbox: $(this),
                    height: 150,
                    width: 200
                });
            });
        }
    };

    this.pieChart = function (element, lables, data, colors) {
        if (colors != undefined) {
            chart_color = colors;
        } else {
            chart_color = ["rgba(10,187,135,0.9)", "rgba(253,57,122, 0.9)", "rgba(54,163,247,0.9)", "rgba(156,39,176,0.7)", "rgba(28,188,216,0.7)", "rgba(64,78,103,0.7)"];
        }

        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: chart_color,
                    label: 'Dataset 1'
                }],
                labels: lables
            },
            options: {
                responsive: true
            }
        };

        var ctx = document.getElementById(element).getContext("2d");
        window.myPie = new Chart(ctx, config);
    };

    this.lineChart = function (element, label, data, name, type, colors) {
        if (colors != undefined) {
            chart_color = colors;
        } else {
            chart_color = ["rgba(22,211,154,0.7)", "rgba(255,117,136, 0.7)", "rgba(255,168,125,0.7)", "rgba(156,39,176,0.7)", "rgba(28,188,216,0.7)", "rgba(64,78,103,0.7)"];
        }
        var ctx2 = document.getElementById(element).getContext("2d");

        // Chart Options
        var userPageVisitOptions = {
            responsive: true,
            maintainAspectRatio: false,
            pointDotStrokeWidth: 2,
            legend: {
                display: true,
                labels: {
                    fontColor: '#404e67',
                    boxWidth: 10,
                },
                position: 'bottom',
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10,
                caretPadding: 0,
                displayColors: false,
                titleFontColor: '#ffffff',
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            },
            scales: {
                xAxes: [{
                    categoryPercentage: 0.35,
                    barPercentage: 0.70,
                    display: false,
                    gridLines: false,
                    ticks: {
                        display: true,
                        display: true,
                        beginAtZero: true,
                        fontSize: 13,
                        padding: 10
                    },
                }],
                yAxes: [{
                    categoryPercentage: 0.35,
                    barPercentage: 0.70,
                    display: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.07)",
                        drawTicks: false,
                        drawBorder: false,
                        offsetGridLines: false,
                        borderDash: [3, 4],
                        zeroLineWidth: 1,
                        zeroLineColor: "rgba(0,0,0,0.09)",
                        zeroLineBorderDash: [3, 4]
                    },
                    ticks: {
                        display: true,
                        maxTicksLimit: 5,
                        beginAtZero: true,
                        fontSize: 13,
                        padding: 10,
                        userCallback: function (label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        },
                    },
                }]
            },
            title: {
                display: false,
                text: 'Report last 30 days'
            },
        };

        data_set = [];
        var count_data = data.length;

        for (var i = 0; i < count_data; i++) {
            if (type == "line") {
                data_set.push({
                    label: name[i],
                    data: data[i],
                    backgroundColor: "transparent",
                    borderColor: chart_color[i],
                    pointBorderColor: chart_color[i],
                    pointRadius: 2,
                    pointBorderWidth: 2,
                    pointHoverBorderWidth: 2,
                });
            } else {
                data_set.push({
                    label: name[i],
                    data: data[i],
                    backgroundColor: chart_color[i],
                    borderColor: "transparent",
                    pointBorderColor: "transparent",
                    pointRadius: 1,
                    pointBorderWidth: 2,
                    pointHoverBorderWidth: 2,
                });
            }
        }

        // Chart Data
        var userPageVisitData = {
            labels: label,
            datasets: data_set
        };

        var userPageVisitConfig = {
            type: 'line',
            // Chart Options
            options: userPageVisitOptions,
            // Chart Data
            data: userPageVisitData
        };

        // Create the chart
        var stackedAreaChart = new Chart(ctx2, userPageVisitConfig);
    };

    this.sidebar = function () {
        //Menu
        var bodyMain = document.getElementById('body-main'),
            showLeft = document.getElementById('menu-toggle'),
            body = document.body;
        if (showLeft) {

            showLeft.onclick = function () {
                classie.toggle(this, 'active');
                classie.toggle(bodyMain, 'body-collapsed');
                classie.toggle(showLeft, 'disabled');
            };
        }

        if ($(".menu-scroll").length > 0) {
            const ps1 = new PerfectScrollbar('.menu-scroll', {
                wheelSpeed: 2,
                wheelPropagation: true,
                minScrollbarLength: 20,
                suppressScrollX: true
            });
        }

        $("body.menu-full .sidebar .nav-item .menuPopover").hover(function () {
            $('.menuPopover').webuiPopover('destroy');
            $('#menucontentwebuiPopover').remove();
        });

        $(document).on("click", "body.menu-full .sidebar .nav-item a", function () {
            _that = $(this);
            if (_that.next(".menu-content").hasClass("open")) {
                _that.next(".menu-content").slideUp(300).removeClass("open");

            } else {
                _that.next(".menu-content").slideDown(300).addClass("open");

            }

        });
    };


    // ---- UPDATE -------

    (function () {
        let tabsocial = $(".ap-box-preview .ap-preview-header .tab-social .item");
        let tabs = $(".social-box .tab-content>.tab-pane");
        tabsocial.click(function (e) {
            e.preventDefault();
            let tabActive = $($(this).find("a").attr("href"));

            tabs.removeClass("active show");
            $(tabActive).addClass("active show");
            tabsocial.removeClass("active");
            $(this).addClass("active")
        });
    })();

    (function () {
        let tabBtns = $(".ap-schedule .form-caption .list-action .action-type li");
        let tabs = $(".ap-schedule .ap-action-option .item-option-action");
        tabBtns.click(function (e) {
            e.preventDefault();
            let tabActive = $($(this).find("a").attr("href"));

            tabs.removeClass("active show");
            $(tabActive).addClass("active show");
            tabBtns.removeClass("active");
            $(this).addClass("active")
        });
    })();



    (function () {
        let tabBtns = $(".ap-mobile-menu li a");
        let tabs = $(".ap-mbile-menu-tab");

        tabBtns.click(function (e) {
            e.preventDefault();
            let tabActive = $(".ap-box-" + $(this).attr("data-ap-open"));

            tabs.removeClass("active");
            $(tabActive).addClass("active");
            tabBtns.removeClass("active");
            $(this).addClass("active")
        });
    })();



    (function () {
        let tabBtns = $(".am-mobile-menu li a");
        let tabs = $(".am-mbile-menu-tab");

        tabBtns.click(function (e) {
            e.preventDefault();

            let tabActive = $(".am-" + $(this).attr("data-am-open"));

            tabs.removeClass("active");
            $(tabActive).addClass("active");
            tabBtns.removeClass("active");
            $(this).addClass("active")
        });
    })();


    (function () {
        let btnInvalidate = $(".card-waiting .fotter .btn-invalidate");
        let btnCancel = $(".card-waiting .reason-box .btn-cancel");
        let resonBoxs = $('.card-waiting .reason-box');

        btnInvalidate.click(function (e) {
            e.preventDefault();
            event.stopPropagation();
            let resonBox = $(this).parents('.card-waiting').find('.reason-box');
            resonBoxs.not(resonBox).slideUp(300);

            $(resonBox).slideToggle(300);

        });

        btnCancel.click(function (e) {
            e.preventDefault();
            resonBoxs.slideUp(300);

            let resonBox = $(this).parents('.reason-box');
            $(resonBox).slideUp(300);
        });

        // $(window).click(function (e) {
        //     //Hide the menus if visible

        //     if (!$(e.target).is('.btn-invalidate') && !$(e.target).is('.card-waiting .reason-box')) {
        //         // hide menu
        //         resonBoxs.slideUp(300);
        //     }
        // });

    })();


    (function () {
        let tabBtns = $(".pn-box-sidebar .body .item a");
        let tabItems = $(".pn-box-sidebar .body .item");

        tabBtns.click(function (e) {
            e.preventDefault();

            // let target = $(this).find("a");

            // if (!$(e.target).is(target)) {
            //     return;
            // }

            tabItems.removeClass("active");
            $(this).parents(".item").addClass("active");
        });

    })();

    // datatable

    (function () {


        let jsLang = JSON.parse($("#js_lang").val());
        $('.sq-datatable').DataTable({
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            scrollX: false,
            autoWith: false,
            bSort: true,
            language: jsLang.datatable.language,
        });
    })();

    // btn savr draft 
    (function () {
        $('.btn-save-model').click(function (e) {
            e.preventDefault();
            $(".saveCaption").click();
        });
    })();

    // btn slide left
    (function () {
        let btnSlide = $('#btn-slide');
        let btnOrgin = $(".pn-toggle-open");
        let boxSlide = $(".pn-box-sidebar");
        btnSlide.html(btnOrgin.html());

        btnSlide.click(function (e) {
            e.preventDefault();
            boxSlide.toggleClass("show")
        });
    })();

    // waiting popup
    (function () {
        $(".read-more").click(function (e) {
            e.preventDefault();
            let text = $(this).data("text");
            $("#read-more-info").html(text);
            $("#mainModal").html($("#read-more-box").html())
        });
    })();

    (function () {

        $(".overlay").click(function (e) {
            e.preventDefault();
            $("body").removeClass("overlay-open");
            $(this).fadeOut(300);
        });

    })();

    //------- schedules popup---------------//
    (function () {

        $(".calander-item .popup").click(function (e) {
            e.preventDefault();
            let popup = $(this).find(".popup-box .card");
            $("#read-more-info").html("");
            popup.clone().prependTo("#read-more-info");
            $("#mainModal").html("");
            $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");

            // $("#mainModal").html($("#read-more-box").html())
        });

    })();

    //------- schedules admin popup---------------//
    (function () {

        $(".ecalendar .d-info .show-all").click(function (e) {
            e.preventDefault();
            let popup = $(this).parents(".d-info").find(".post-preview");
            $("#read-more-info").html("");
            popup.clone().prependTo("#read-more-info");
            $("#mainModal").html("");
            $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");

        });

        $(".ecalendar .d-info .posts .post").click(function (e) {
            e.preventDefault();
            let popup = $(this).find(".post-preview");
            $("#read-more-info").html("");
            popup.clone().prependTo("#read-more-info");
            $("#mainModal").html("");
            $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");

        });


    })();

    //------- responsable add popup---------------//
    (function () {

        $(".responsable_popup").click(function (e) {
            e.preventDefault();
            $("#mainModal").html("");
            var clone = $("#read-more-box .modal-dialog").clone().prependTo("#mainModal");
            clone.find('.dropdown-toggle').remove();
            clone.find('select').selectpicker();
            console.log($("#responsable_cancel"));

            $("#responsable_cancel").click(function (e) {
                e.preventDefault();
                console.log("heyyyyyyy");

            });
        });



    })();




    (function () {

        if ($(".clients-search").length) {
            $(".fix-footer").append($(".clients-search").get(0).outerHTML);

            $(".fix-footer .clients-search .clients-search-header,.fix-footer .btn-toggle-show").click(function (e) {
                e.preventDefault();
                $(".fix-footer").toggleClass("show")
            });
        
        }else {
            $(".fix-footer").remove()
        }


    })();

    (function () {

        $(".btn-slide").click(function (e) {
            e.preventDefault();
            $(this).siblings(".list-slide").slideToggle(300);
            $(this).toggleClass("open");
        });

    })();


    // input file
    (function () {

        $(".file-group .file-name").click(function (e) {
            e.preventDefault();
            $(this).siblings("input[type='file']").click()
        });

        $(".file-group input[type='file']").change(function (e) {
            e.preventDefault();
            let fileName = $(this).val().split(/(\\|\/)/g).pop();

            $(this).siblings(".file-name").text(fileName)
        });


    })();
    (function () {
        $("button").click(function () {
            $(".check-icon").hide();
            setTimeout(function () {
                $(".check-icon").show();
            }, 10);
        });

    })();

    (function () {
        $(".sidebar a").on("show.bs.tooltip", function (e) {
            if (!$("body").hasClass("menu_sm")) {
                return false;
            }
        });
        if ($(window).width() > 1169) {
            $("body").addClass("menu_sm")
        }
    })();

    function isScrolledIntoView(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    function showSidBarBtns() {
        if ($(window).width() > 1169) {
            $(".slimScrollDiv .list > li.plus").show()
            let listLI = $(".slimScrollDiv .list > li:not(.plus)");
            $(".slimScrollDiv .list > li.plus > .ml-menu").html("");
            let startIndex = listLI.length;
            listLI.show();
            listLI.each(function (index) {

                if (!isScrolledIntoView($(this))) {
                    startIndex = index - 1;
                    return false;
                }
            });

            for (let i = startIndex; i < listLI.length; i++) {
                listLI.eq(i).hide()
                $(".slimScrollDiv .list > li.plus > .ml-menu")
                    .append(
                        '<div class="plus-eles">' + listLI.eq(i).html() + '</div>'
                    )
            }
            if (startIndex == listLI.length) {
                $(".slimScrollDiv .list > li.plus").hide()
            }
            // $(".slimScrollDiv .list > li.plus > .ml-menu")
            //     .append(listLI.eq(listLI.length - 1).html());
        }
    }

    (function () {
        $(document).ready(function () {
            showSidBarBtns();

        });
        $(window).resize(function () {
            if ($("body").hasClass("menu_sm")) {
                showSidBarBtns()
            }
        });
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            if ($("body").hasClass("menu_sm")) {

                $(".slimScrollDiv .list > li.plus").hide()
                $(".slimScrollDiv .list > li:not(.plus)").show();
            } else {
                setTimeout(showSidBarBtns, 100)
            }
        });

        $(".sidebar *").scroll(function () {
            console.log($(this).attr("class"));
        });

        // $(".slimScrollDiv, .list,.menu")
        //     .off("scroll")

    })();

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


    //participer-form validation

    // (function () {
    //     let jsLang = JSON.parse($("#js_lang").val());
    //     console.log(jsLang);

    //     jQuery.extend(jQuery.validator.messages, {
    //         required: jsLang.form_validation.required_field,
    //     });
    //     let participerForm = $(".participer-form");
    //     participerForm.validate();
    // })();
    /*
     * Translated default messages for the jQuery validation plugin.
     * Locale: FR (French; français)
     */
    // if (typeof validate === "function") {
        $.extend($.validator.messages, {
            required: "Ce champ est obligatoire.",
            remote: "Veuillez corriger ce champ.",
            email: "Veuillez fournir une adresse électronique valide.",
            url: "Veuillez fournir une adresse URL valide.",
            date: "Veuillez fournir une date valide.",
            dateISO: "Veuillez fournir une date valide (ISO).",
            number: "Veuillez fournir un numéro valide.",
            digits: "Veuillez fournir seulement des chiffres.",
            creditcard: "Veuillez fournir un numéro de carte de crédit valide.",
            equalTo: "Veuillez fournir encore la même valeur.",
            notEqualTo: "Veuillez fournir une valeur différente, les valeurs ne doivent pas être identiques.",
            extension: "Veuillez fournir une valeur avec une extension valide.",
            maxlength: $.validator.format("Veuillez fournir au plus {0} caractères."),
            minlength: $.validator.format("Veuillez fournir au moins {0} caractères."),
            rangelength: $.validator.format("Veuillez fournir une valeur qui contient entre {0} et {1} caractères."),
            range: $.validator.format("Veuillez fournir une valeur entre {0} et {1}."),
            max: $.validator.format("Veuillez fournir une valeur inférieure ou égale à {0}."),
            min: $.validator.format("Veuillez fournir une valeur supérieure ou égale à {0}."),
            step: $.validator.format("Veuillez fournir une valeur multiple de {0}."),
            maxWords: $.validator.format("Veuillez fournir au plus {0} mots."),
            minWords: $.validator.format("Veuillez fournir au moins {0} mots."),
            rangeWords: $.validator.format("Veuillez fournir entre {0} et {1} mots."),
            letterswithbasicpunc: "Veuillez fournir seulement des lettres et des signes de ponctuation.",
            alphanumeric: "Veuillez fournir seulement des lettres, nombres, espaces et soulignages.",
            lettersonly: "Veuillez fournir seulement des lettres.",
            nowhitespace: "Veuillez ne pas inscrire d'espaces blancs.",
            ziprange: "Veuillez fournir un code postal entre 902xx-xxxx et 905-xx-xxxx.",
            integer: "Veuillez fournir un nombre non décimal qui est positif ou négatif.",
            vinUS: "Veuillez fournir un numéro d'identification du véhicule (VIN).",
            dateITA: "Veuillez fournir une date valide.",
            time: "Veuillez fournir une heure valide entre 00:00 et 23:59.",
            phoneUS: "Veuillez fournir un numéro de téléphone valide.",
            phoneUK: "Veuillez fournir un numéro de téléphone valide.",
            mobileUK: "Veuillez fournir un numéro de téléphone mobile valide.",
            strippedminlength: $.validator.format("Veuillez fournir au moins {0} caractères."),
            email2: "Veuillez fournir une adresse électronique valide.",
            url2: "Veuillez fournir une adresse URL valide.",
            creditcardtypes: "Veuillez fournir un numéro de carte de crédit valide.",
            ipv4: "Veuillez fournir une adresse IP v4 valide.",
            ipv6: "Veuillez fournir une adresse IP v6 valide.",
            require_from_group: $.validator.format("Veuillez fournir au moins {0} de ces champs."),
            nifES: "Veuillez fournir un numéro NIF valide.",
            nieES: "Veuillez fournir un numéro NIE valide.",
            cifES: "Veuillez fournir un numéro CIF valide.",
            postalCodeCA: "Veuillez fournir un code postal valide."
        });
        $.validator.addMethod('customemail', function (value, element) {
            var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
            return re.test(value);
        }, $.validator.messages.email);
        $.validator.addMethod('customphone', function (value, element) {
            return /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{6,7}?$/.test(value);
        }, $.validator.messages.number);
        $('.participer-form form').validate({
            rules: {
                "etat_civil": "required",
                "prenom": "required",
                "nom": "required",
                "email": {
                    required: true,
                    customemail: true,
                },
                "address": "required",
                "reglement": "required",
                "code_postal": {
                    required: true,
                    digits: true,
                    minlength: 5,
                    maxlength: 5
                },
                "phone": {
                    required: true,
                    customphone: true,
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr('id') == 'reglement') {
                    error.insertAfter($(element).parents('.pure-checkbox').find('label')); //So i putted it after the .form-group so it will not include to your append/prepend group.
                } else {
                    error.insertAfter(element);
                }
            },
        })

    // }

}
Layout = new Layout();
$(function () {
    Layout.init();
});