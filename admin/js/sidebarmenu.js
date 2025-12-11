(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'));
  } else {
    var mod = { exports: {} };
    factory(global.jquery);
    global.metisMenu = mod.exports;
  }
})(this, function ($) {
  'use strict';

  var Util = (function () {
    var transition = false;
    var transitionEndEvent = { WebkitTransition: 'webkitTransitionEnd', MozTransition: 'transitionend', OTransition: 'oTransitionEnd otransitionend', transition: 'transitionend' };

    function setTransitionSupport() {
      var el = document.createElement('mm');
      for (var name in transitionEndEvent) {
        if (el.style[name] !== undefined) {
          transition = { end: transitionEndEvent[name] };
          $.fn.emulateTransitionEnd = function (duration) {
            var called = false;
            $(this).one(Util.TRANSITION_END, function () { called = true; });
            setTimeout(function () { if (!called) Util.triggerTransitionEnd(this); }, duration);
            return this;
          };
          $.event.special[Util.TRANSITION_END] = { bindType: transition.end, delegateType: transition.end, handle: function (e) { if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments); } };
          break;
        }
      }
    }

    return {
      TRANSITION_END: 'mmTransitionEnd',
      triggerTransitionEnd: function (el) { $(el).trigger(transition.end); },
      supportsTransitionEnd: function () { return Boolean(transition); },
      setTransitionSupport: setTransitionSupport
    };
  })();

  Util.setTransitionSupport();

  var MetisMenu = function ($) {
    var Default = {
      toggle: true, preventDefault: true, activeClass: 'active', collapseClass: 'collapse', collapseInClass: 'in', collapsingClass: 'collapsing', triggerElement: 'a', parentTrigger: 'li', subMenu: 'ul'
    };

    var Event = { SHOW: 'show', SHOWN: 'shown', HIDE: 'hide', HIDDEN: 'hidden', CLICK_DATA_API: 'click' };

    function MetisMenu(element, config) {
      this._element = element;
      this._config = $.extend({}, Default, config);
      this._transitioning = null;
      this.init();
    }

    MetisMenu.prototype.init = function () {
      var self = this;
      $(this._element).find(`${this._config.parentTrigger}.${this._config.activeClass}`).has(this._config.subMenu).children(this._config.subMenu).attr('aria-expanded', true).addClass(`${this._config.collapseClass} ${this._config.collapseInClass}`);
      $(this._element).find(this._config.parentTrigger).not(`.${this._config.activeClass}`).has(this._config.subMenu).children(this._config.subMenu).attr('aria-expanded', false).addClass(this._config.collapseClass);
      $(this._element).find(this._config.parentTrigger).has(this._config.subMenu).children(this._config.triggerElement).on(Event.CLICK_DATA_API, function (e) {
        var _this = $(this), _parent = _this.parent(self._config.parentTrigger), _list = _parent.children(self._config.subMenu);
        if (self._config.preventDefault) e.preventDefault();
        if (_this.attr('aria-disabled') === 'true') return;
        if (_parent.hasClass(self._config.activeClass)) {
          _this.attr('aria-expanded', false); self._hide(_list);
        } else {
          self._show(_list); _this.attr('aria-expanded', true);
          if (self._config.toggle) _parent.siblings(self._config.parentTrigger).children(self._config.triggerElement).attr('aria-expanded', false);
        }
      });
    };

    MetisMenu.prototype._show = function (element) {
      if (this._transitioning || $(element).hasClass(this._config.collapsingClass)) return;
      var _el = $(element), startEvent = $.Event(Event.SHOW);
      _el.trigger(startEvent);
      if (startEvent.isDefaultPrevented()) return;
      _el.parent(this._config.parentTrigger).addClass(this._config.activeClass);
      if (this._config.toggle) this._hide(_el.parent(this._config.parentTrigger).siblings().children(`${this._config.subMenu}.${this._config.collapseInClass}`).attr('aria-expanded', false));
      _el.removeClass(this._config.collapseClass).addClass(this._config.collapsingClass).height(0);
      this.setTransitioning(true);
      var complete = function () {
        _el.removeClass(this._config.collapsingClass).addClass(`${this._config.collapseClass} ${this._config.collapseInClass}`).height('').attr('aria-expanded', true);
        this.setTransitioning(false);
        _el.trigger(Event.SHOWN);
      }.bind(this);
      if (!Util.supportsTransitionEnd()) { complete(); return; }
      _el.height(_el[0].scrollHeight).one(Util.TRANSITION_END, complete).emulateTransitionEnd(350);
    };

    MetisMenu.prototype._hide = function (element) {
      if (this._transitioning || !$(element).hasClass(this._config.collapseInClass)) return;
      var _el = $(element), startEvent = $.Event(Event.HIDE);
      _el.trigger(startEvent);
      if (startEvent.isDefaultPrevented()) return;
      _el.parent(this._config.parentTrigger).removeClass(this._config.activeClass);
      _el.height(_el.height())[0].offsetHeight;
      _el.addClass(this._config.collapsingClass).removeClass(`${this._config.collapseClass} ${this._config.collapseInClass}`);
      this.setTransitioning(true);
      var complete = function () {
        this.setTransitioning(false);
        _el.trigger(Event.HIDDEN);
        _el.removeClass(this._config.collapsingClass).addClass(this._config.collapseClass).attr('aria-expanded', false);
      }.bind(this);
      if (!Util.supportsTransitionEnd()) { complete(); return; }
      _el.height() === 0 || _el.css('display') === 'none' ? complete() : _el.height(0).one(Util.TRANSITION_END, complete).emulateTransitionEnd(350);
    };

    MetisMenu.prototype.setTransitioning = function (isTransitioning) { this._transitioning = isTransitioning; };
    MetisMenu.prototype.dispose = function () { $.removeData(this._element, 'metisMenu'); $(this._element).find(this._config.parentTrigger).has(this._config.subMenu).children(this._config.triggerElement).off('click'); };

    MetisMenu._jQueryInterface = function (config) {
      return this.each(function () {
        var $this = $(this), data = $this.data('metisMenu'), _config = $.extend({}, Default, $this.data(), config);
        if (!data && /dispose/.test(config)) { this.dispose(); }
        if (!data) { data = new MetisMenu(this, _config); $this.data('metisMenu', data); }
        if (typeof config === 'string') { data[config](); }
      });
    };

    return MetisMenu;
  }(jQuery);

  $.fn.metisMenu = MetisMenu._jQueryInterface;
  $.fn.metisMenu.Constructor = MetisMenu;
  $.fn.metisMenu.noConflict = function () { $.fn.metisMenu = JQUERY_NO_CONFLICT; return MetisMenu._jQueryInterface; };
});