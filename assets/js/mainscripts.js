if ("undefined" == typeof jQuery) throw new Error("jQuery plugins need to be before this file");
$(function () {
    "use strict";
    $.AdminsQuare.browser.activate(), $.AdminsQuare.leftSideBar.activate(), $.AdminsQuare.rightSideBar.activate(), $.AdminsQuare.rightchat.activate(), $.AdminsQuare.navbar.activate(), $.AdminsQuare.select.activate(), setTimeout(function () {
        $(".page-loader-wrapper").fadeOut()
    }, 60)
}), $.AdminsQuare = {}, $.AdminsQuare.options = {
    colors: {
        red: "#ec3b57",
        pink: "#E91E63",
        purple: "#ba3bd0",
        deepPurple: "#673AB7",
        indigo: "#3F51B5",
        blue: "#2196f3",
        lightBlue: "#03A9F4",
        cyan: "#00bcd4",
        green: "#4CAF50",
        lightGreen: "#8BC34A",
        yellow: "#ffe821",
        orange: "#FF9800",
        deepOrange: "#f83600",
        grey: "#9E9E9E",
        blueGrey: "#607D8B",
        black: "#000000",
        blush: "#dd5e89",
        white: "#ffffff"
    },
    leftSideBar: {
        scrollColor: "rgba(0,0,0,0.5)",
        scrollWidth: "4px",
        scrollAlwaysVisible: !1,
        scrollBorderRadius: "0",
        scrollRailBorderRadius: "0"
    },
    dropdownMenu: {
        effectIn: "fadeIn",
        effectOut: "fadeOut"
    }
}, $.AdminsQuare.leftSideBar = {
    activate: function () {
        var a = this,
            i = $("body"),
            s = $(".overlay");
        // $(window).on("click", function (e) {
        //     var t = $(e.target);
        //     "i" === e.target.nodeName.toLowerCase() && (t = $(e.target).parent()), !t.hasClass("bars") && a.isOpen() && 0 === t.parents("#leftsidebar").length && (t.hasClass("js-right-sidebar") || s.fadeOut(), i.removeClass("overlay-open"))
        // }),
         $.each($(".menu-toggle.toggled"), function (e, t) {
            $(t).next().slideToggle(0)
        }), $.each($(".menu .list li.active"), function (e, t) {
            var a = $(t).find("a:eq(0)");
            a.addClass("toggled"), a.next().show()
        }), $(".menu-toggle").on("click", function (e) {
            var t = $(this),
                a = t.next();
            if ($(t.parents("ul")[0]).hasClass("list")) {
                var i = $(e.target).hasClass("menu-toggle") ? e.target : $(e.target).parents(".menu-toggle");
                $.each($(".menu-toggle.toggled").not(i).next(), function (e, t) {
                    $(t).is(":visible") && ($(t).prev().toggleClass("toggled"), $(t).slideUp())
                })
            }
            t.toggleClass("toggled"), a.slideToggle(320)
        }), a.checkStatuForResize(!0), $(window).resize(function () {
            a.checkStatuForResize(!1)
        }), Waves.attach(".menu .list a", ["waves-block"]), Waves.init()
    },
    checkStatuForResize: function (e) {
        var t = $("body"),
            a = $(".navbar .navbar-header .bars"),
            i = t.width();
        e && t.find(".content, .sidebar").addClass("no-animate").delay(1e3).queue(function () {
            $(this).removeClass("no-animate").dequeue()
        }), i < 1170 ? (t.addClass("ls-closed"), a.fadeIn()) : (t.removeClass("ls-closed"), a.fadeOut())
    },
    isOpen: function () {
        return $("body").hasClass("overlay-open")
    }
}, $.AdminsQuare.rightSideBar = {
    activate: function () {
        var a = this,
            i = $("#rightsidebar"),
            s = $(".overlay");
        $(window).on("click", function (e) {
            var t = $(e.target);
            "i" === e.target.nodeName.toLowerCase() && (t = $(e.target).parent()), !t.hasClass("js-right-sidebar") && a.isOpen() && 0 === t.parents("#rightsidebar").length && (t.hasClass("bars") || s.fadeOut(), i.removeClass("open"))
        }), $(".js-right-sidebar").on("click", function () {
            i.toggleClass("open"), a.isOpen() ? s.fadeIn() : s.fadeOut()
        })
    },
    isOpen: function () {
        return $(".right-sidebar").hasClass("open")
    }
}, $.AdminsQuare.rightchat = {
    activate: function () {
        var a = this,
            i = $("#rightchat"),
            s = $(".overlay");
        $(window).on("click", function (e) {
            var t = $(e.target);
            "i" === e.target.nodeName.toLowerCase() && (t = $(e.target).parent()), !t.hasClass("js-right-chat") && a.isOpen() && 0 === t.parents("#rightchat").length && (t.hasClass("bars") || s.fadeOut(), i.removeClass("open"))
        }), $(".js-right-chat").on("click", function () {
            i.toggleClass("open"), a.isOpen() ? s.fadeIn() : s.fadeOut()
        })
    },
    isOpen: function () {
        return $(".right_chat").hasClass("open")
    }
}, $.AdminsQuare.navbar = {
    activate: function () {
        var e = $("body"),
            t = $(".overlay");
        $(".bars").on("click", function () {
            e.toggleClass("overlay-open"), e.hasClass("overlay-open") ? t.fadeIn() : t.fadeOut()
        }), $('.nav [data-close="true"]').on("click", function () {
            var e = $(".navbar-toggle").is(":visible"),
                t = $(".navbar-collapse");
            e && t.slideUp(function () {
                t.removeClass("in").removeAttr("style")
            })
        })
    }
}, $.AdminsQuare.select = {
    activate: function () {
        $.fn.selectpicker && $("select:not(.ms)").selectpicker()
    }
};
var edge = "Microsoft Edge",
    ie10 = "Internet Explorer 10",
    ie11 = "Internet Explorer 11",
    opera = "Opera",
    firefox = "Mozilla Firefox",
    chrome = "Google Chrome",
    safari = "Safari";

function initSparkline() {
    $(".sparkline").each(function () {
        var e = $(this);
        e.sparkline("html", e.data())
    })
}

function initCounters() {
    $(".count-to").countTo()
}

function skinChanger() {
    $(".right-sidebar .choose-skin li").on("click", function () {
        var e = $("body"),
            t = $(this),
            a = $(".right-sidebar .choose-skin li.active").data("theme");
        $(".right-sidebar .choose-skin li").removeClass("active"), e.removeClass("theme-" + a), t.addClass("active"), e.addClass("theme-" + t.data("theme"))
    })
}

function CustomScrollbar() {
    $(".sidebar .menu .list").slimscroll({
        height: "calc(100vh - 65px)",
        color: "rgba(0,0,0,0.2)",
        position: "left",
        size: "2px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "0"
    }), $(".navbar-left .dropdown-menu .body .menu").slimscroll({
        height: "300px",
        color: "rgba(0,0,0,0.2)",
        size: "3px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "0"
    }), $(".cwidget-scroll").slimscroll({
        height: "306px",
        color: "rgba(0,0,0,0.4)",
        size: "2px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "2px"
    }), $(".right_chat .chat_body .chat-widget").slimscroll({
        height: "calc(100vh - 145px)",
        color: "rgba(0,0,0,0.1)",
        size: "2px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "2px",
        position: "left"
    }), $(".right-sidebar .slim_scroll").slimscroll({
        height: "calc(100vh - 60px)",
        color: "rgba(0,0,0,0.4)",
        size: "2px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "0"
    }), $(".sidebar .clients-search .clients-search-body .clients-list").slimscroll({
        height: "160px",
        color: "rgba(0,0,0,0.4)",
        size: "2px",
        alwaysVisible: !1,
        borderRadius: "3px",
        railBorderRadius: "0"
    })
}

function CustomPageJS() {
    $(".boxs-close").on("click", function () {
        $(this).parents(".card").addClass("closed").fadeOut()
    }), $(".theme-light-dark .t-light").on("click", function () {
        $("body").removeClass("menu_dark")
    }), $(".theme-light-dark .t-dark").on("click", function () {
        $("body").addClass("menu_dark")
    }), $(".menu-sm").on("click", function () {
        $("body").toggleClass("menu_sm")
    }), $(document).ready(function () {
        $(".btn_overlay").on("click", function () {
            $(".overlay_menu").fadeToggle(200), $(this).toggleClass("btn-open").toggleClass("btn-close")
        })
    }), $(".overlay_menu").on("click", function () {
        $(".overlay_menu").fadeToggle(200), $(".overlay_menu button.btn").toggleClass("btn-open").toggleClass("btn-close"), open = !1
    }), $(".form-control").on("focus", function () {
        $(this).parent(".input-group").addClass("input-group-focus")
    }).on("blur", function () {
        $(this).parent(".input-group").removeClass("input-group-focus")
    })
}
$.AdminsQuare.browser = {
    activate: function () {
        "" !== this.getClassName() && $("html").addClass(this.getClassName())
    },
    getBrowser: function () {
        var e = navigator.userAgent.toLowerCase();
        return /edge/i.test(e) ? edge : /rv:11/i.test(e) ? ie11 : /msie 10/i.test(e) ? ie10 : /opr/i.test(e) ? opera : /chrome/i.test(e) ? chrome : /firefox/i.test(e) ? firefox : navigator.userAgent.match(/Version\/[\d\.]+.*Safari/) ? safari : void 0
    },
    getClassName: function () {
        var e = this.getBrowser();
        return e === edge ? "edge" : e === ie11 ? "ie11" : e === ie10 ? "ie10" : e === opera ? "opera" : e === chrome ? "chrome" : e === firefox ? "firefox" : e === safari ? "safari" : ""
    }
}, $(function () {
    "use strict";
    skinChanger(), CustomScrollbar(), initSparkline(), initCounters(), CustomPageJS()
}), $(function () {
    "use strict";
    if ($("#supported").text("Supported/allowed: " + !!screenfull.enabled), !screenfull.enabled) return !1;
    $("#request").on("click", function () {
        screenfull.request($("#container")[0])
    }), $("#exit").on("click", function () {
        screenfull.exit()
    }), $('[data-provide~="boxfull"]').on("click", function () {
        screenfull.toggle($(".box")[0])
    }), $('[data-provide~="fullscreen"]').on("click", function () {
        screenfull.toggle($("#container")[0])
    });
    var e = '[data-provide~="fullscreen"]';

    function t() {
        var e = screenfull.element;
        $("#status").text("Is fullscreen: " + screenfull.isFullscreen), e && $("#element").text("Element: " + e.localName + (e.id ? "#" + e.id : "")), screenfull.isFullscreen || ($("#external-iframe").remove(), document.body.style.overflow = "auto")
    }
    $(e).each(function () {
        $(this).data("fullscreen-default-html", $(this).html())
    }), document.addEventListener(screenfull.raw.fullscreenchange, function () {
        screenfull.isFullscreen ? $(e).each(function () {
            $(this).addClass("is-fullscreen")
        }) : $(e).each(function () {
            $(this).removeClass("is-fullscreen")
        })
    }), screenfull.on("change", t), t()
});