this["LP"] = this["LP"] || {}; this["LP"]["profile"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile.js":
/*!*********************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile.js ***!
  \*********************************************************************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _profile_course_tab__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./profile/course-tab */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/course-tab.js");
/* harmony import */ var _profile_statistic__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./profile/statistic */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/statistic.js");


document.addEventListener('DOMContentLoaded', function (event) {
  Object(_profile_course_tab__WEBPACK_IMPORTED_MODULE_0__["default"])();
  Object(_profile_statistic__WEBPACK_IMPORTED_MODULE_1__["default"])();
});

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/course-tab.js":
/*!********************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/course-tab.js ***!
  \********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

 // Rest API load content course enrolled, created - Nhamdv.

var courseTab = function courseTab() {
  var elements = document.querySelectorAll('.learn-press-course-tab__filter__content');

  if (!elements.length) {
    return;
  }

  if ('IntersectionObserver' in window) {
    var eleObserver = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var ele = entry.target;
          var data = JSON.parse(ele.dataset.ajax);
          getResponse(ele, data);
          eleObserver.unobserve(ele);
        }
      });
    });

    _toConsumableArray(elements).map(function (ele) {
      return eleObserver.observe(ele);
    });
  }

  var changeFilter = function changeFilter() {
    var tabs = document.querySelectorAll('.learn-press-course-tab-filters');
    tabs.forEach(function (tab) {
      var filters = tab.querySelectorAll('.learn-press-filters a');
      filters.forEach(function (filter) {
        filter.addEventListener('click', function (e) {
          e.preventDefault();
          var tabName = filter.dataset.tab;

          _toConsumableArray(filters).map(function (ele) {
            ele.classList.remove('active');
          });

          filter.classList.add('active');

          _toConsumableArray(tab.querySelectorAll('.learn-press-course-tab__filter__content')).map(function (ele) {
            ele.style.display = 'none';

            if (ele.dataset.tab === tabName) {
              ele.style.display = '';
            }
          });
        });
      });
    });
  };

  changeFilter();

  var changeTab = function changeTab() {
    var tabUls = document.querySelectorAll('.learn-press-profile-course__tab__inner');
    tabUls.forEach(function (tabUl) {
      var tabs = tabUl.querySelectorAll('li> a');
      tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
          e.preventDefault();
          var tabName = tab.dataset.tab;

          _toConsumableArray(tabs).map(function (ele) {
            ele.classList.remove('active');
          });

          tab.classList.add('active');

          _toConsumableArray(document.querySelectorAll('.learn-press-course-tab-filters')).map(function (ele) {
            ele.style.display = 'none';

            if (ele.dataset.tab === tabName) {
              ele.style.display = '';
            }
          });
        });
      });
    });
  };

  changeTab();

  var getResponse = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(ele, dataset) {
      var append,
          viewMoreEle,
          response,
          skeleton,
          paged,
          numberPage,
          _paged,
          _numberPage,
          _args = arguments;

      return regeneratorRuntime.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              append = _args.length > 2 && _args[2] !== undefined ? _args[2] : false;
              viewMoreEle = _args.length > 3 && _args[3] !== undefined ? _args[3] : false;
              _context.prev = 2;
              _context.next = 5;
              return wp.apiFetch({
                path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])('lp/v1/profile/course-tab', dataset),
                method: 'GET'
              });

            case 5:
              response = _context.sent;

              if (response) {
                skeleton = ele.querySelector('.lp-skeleton-animation');
                skeleton && skeleton.remove();

                if (response.status === 'success' && response.data) {
                  if (append) {
                    ele.innerHTML += response.data;
                  } else {
                    ele.innerHTML = response.data;
                  }
                } else if (append) {
                  ele.innerHTML += "<div class=\"lp-ajax-message\" style=\"display:block\">".concat(response.message && response.message, "</div>");
                } else {
                  ele.innerHTML = "<div class=\"lp-ajax-message\" style=\"display:block\">".concat(response.message && response.message, "</div>");
                }

                if (viewMoreEle) {
                  viewMoreEle.classList.remove('loading');
                  paged = viewMoreEle.dataset.paged;
                  numberPage = viewMoreEle.dataset.number;

                  if (numberPage <= paged) {
                    viewMoreEle.remove();
                  }

                  viewMoreEle.dataset.paged = parseInt(paged) + 1;
                }

                viewMore(ele, dataset);
              }

              _context.next = 13;
              break;

            case 9:
              _context.prev = 9;
              _context.t0 = _context["catch"](2);

              if (append) {
                ele.innerHTML += "<div class=\"lp-ajax-message error\" style=\"display:block\">".concat(_context.t0.message && _context.t0.message, "</div>");
              } else {
                ele.innerHTML = "<div class=\"lp-ajax-message error\" style=\"display:block\">".concat(_context.t0.message && _context.t0.message, "</div>");
              }

              if (viewMoreEle) {
                viewMoreEle.classList.remove('loading');
                _paged = viewMoreEle.dataset.paged;
                _numberPage = viewMoreEle.dataset.number;

                if (_numberPage <= _paged) {
                  viewMoreEle.remove();
                }

                viewMoreEle.dataset.paged = parseInt(_paged) + 1;
              }

            case 13:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, null, [[2, 9]]);
    }));

    return function getResponse(_x, _x2) {
      return _ref.apply(this, arguments);
    };
  }();

  var viewMore = function viewMore(ele, dataset) {
    var viewMoreEle = ele.querySelector('button[data-paged]');

    if (viewMoreEle) {
      viewMoreEle.addEventListener('click', function (e) {
        e.preventDefault();
        var paged = viewMoreEle && viewMoreEle.dataset.paged;
        viewMoreEle.classList.add('loading');
        var element = dataset.layout === 'list' ? '.lp_profile_course_progress' : '.learn-press-courses';
        getResponse(ele.querySelector(element), _objectSpread(_objectSpread({}, dataset), {
          paged: paged
        }), true, viewMoreEle);
      });
    }
  };
};

/* harmony default export */ __webpack_exports__["default"] = (courseTab);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/statistic.js":
/*!*******************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/profile/statistic.js ***!
  \*******************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

 // Rest API load content course progress - Nhamdv.

var courseStatistics = function courseStatistics() {
  var elements = document.querySelectorAll('.learn-press-profile-course__statistic');

  if (!elements.length) {
    return;
  }

  if ('IntersectionObserver' in window) {
    var eleObserver = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var ele = entry.target;
          var data = JSON.parse(ele.dataset.ajax);
          getResponse(ele, data);
          eleObserver.unobserve(ele);
        }
      });
    });

    _toConsumableArray(elements).map(function (ele) {
      return eleObserver.observe(ele);
    });
  }

  var getResponse = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(ele, dataset) {
      var response;
      return regeneratorRuntime.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _context.next = 3;
              return wp.apiFetch({
                path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])('lp/v1/profile/statistic', dataset),
                method: 'GET'
              });

            case 3:
              response = _context.sent;

              if (response.status === 'success' && response.data) {
                ele.innerHTML = response.data;
              } else {
                ele.innerHTML = "<div class=\"lp-ajax-message error\" style=\"display:block\">".concat(response.message && response.message, "</div>");
              }

              _context.next = 10;
              break;

            case 7:
              _context.prev = 7;
              _context.t0 = _context["catch"](0);
              ele.innerHTML += "<div class=\"lp-ajax-message error\" style=\"display:block\">".concat(_context.t0.message && _context.t0.message, "</div>");

            case 10:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, null, [[0, 7]]);
    }));

    return function getResponse(_x, _x2) {
      return _ref.apply(this, arguments);
    };
  }();
};

/* harmony default export */ __webpack_exports__["default"] = (courseStatistics);

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["url"]; }());

/***/ })

/******/ });
//# sourceMappingURL=profile.js.map