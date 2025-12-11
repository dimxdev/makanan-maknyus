$(function() {
    "use strict";
    // Preloader fade out
    $(".preloader").fadeOut();

    // Prevent mega-dropdown from closing
    $(document).on("click", ".mega-dropdown", function(i) { i.stopPropagation(); });

    // Adjust sidebar on window resize
    var adjustSidebar = function() {
        var body = $("body");
        var navbarBrand = $(".navbar-brand span");
        var pageWrapper = $(".page-wrapper");
        if (window.innerWidth < 1170) {
            body.addClass("mini-sidebar");
            navbarBrand.hide();
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $(".sidebartoggler i").addClass("ti-menu");
        } else {
            body.removeClass("mini-sidebar");
            navbarBrand.show();
        }
        var height = Math.max(1, window.innerHeight - 71);
        pageWrapper.css("min-height", height + "px");
    };
    
    $(window).on("resize load", adjustSidebar);
    $(".sidebartoggler").on("click", function() {
        var body = $("body");
        body.toggleClass("mini-sidebar");
        $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", body.hasClass("mini-sidebar") ? "visible" : "hidden").parent().css("overflow", "visible");
        $(".navbar-brand span").toggle(!body.hasClass("mini-sidebar"));
    });

    // Stick header in place
    $(".fix-header .header").stick_in_parent();

    // Toggle sidebar visibility
    $(".nav-toggler").click(function() {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("mdi mdi-menu mdi mdi-close");
    });

    // Toggle search box visibility
    $(".search-box a, .search-box .app-search .srh-btn").on("click", function() {
        $(".app-search").slideToggle(200);
    });

    // Floating labels form control focus
    $(".floating-labels .form-control").on("focus blur", function(e) {
        $(this).parents(".form-group").toggleClass("focused", e.type === "focus" || this.value.length > 0);
    }).trigger("blur");

    // Highlight active sidebar link
    var currentUrl = window.location.href;
    $("ul#sidebarnav a").filter(function() {
        return this.href === currentUrl;
    }).addClass("active").parent().addClass("active").parents("li").addClass("in active");

    // Initialize MetisMenu
    $("#sidebarnav").metisMenu();

    // Slimscroll initialization
    $(".scroll-sidebar, .message-center, .aboutscroll, .message-scroll, .chat-box, .slimscrollright").slimScroll({
        position: "left", size: "5px", color: "#dcdcdc", height: "100%"
    });

    // Task label click toggle
    $(".list-task li label").click(function() { $(this).toggleClass("task-done"); });

    // Toggle login/recover form
    $("#to-recover").on("click", function() { $("#loginform").slideUp(); $("#recoverform").fadeIn(); });

    // Card collapse, expand, close actions
    $('a[data-action="collapse"]').on("click", function(e) {
        e.preventDefault();
        var card = $(this).closest(".card");
        card.find('[data-action="collapse"] i').toggleClass("ti-minus ti-plus");
        card.children(".card-body").collapse("toggle");
    });

    $('a[data-action="expand"]').on("click", function(e) {
        e.preventDefault();
        var card = $(this).closest(".card");
        card.find('[data-action="expand"] i').toggleClass("mdi-arrow-expand mdi-arrow-compress");
        card.toggleClass("card-fullscreen");
    });

    $('a[data-action="close"]').on("click", function() { $(this).closest(".card").removeClass().slideUp("fast"); });
});
