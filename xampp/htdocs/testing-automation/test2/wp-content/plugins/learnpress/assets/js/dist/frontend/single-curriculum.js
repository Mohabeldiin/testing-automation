this["LP"] = this["LP"] || {}; this["LP"]["singleCurriculum"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/show-lp-overlay-complete-item.js":
/*!*******************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/show-lp-overlay-complete-item.js ***!
  \*******************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/lp-modal-overlay */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/utils/lp-modal-overlay.js");
var $ = jQuery;

var lpModalOverlayCompleteItem = {
  elBtnFinishCourse: null,
  elBtnCompleteItem: null,
  init: function init() {
    if (!_utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].init()) {
      return;
    }

    if (undefined === lpGlobalSettings || 'yes' !== lpGlobalSettings.option_enable_popup_confirm_finish) {
      return;
    }

    this.elBtnFinishCourse = document.querySelectorAll('.lp-btn-finish-course');
    this.elBtnCompleteItem = document.querySelector('.lp-btn-complete-item');

    if (this.elBtnCompleteItem) {
      this.elBtnCompleteItem.addEventListener('click', function (e) {
        e.preventDefault();
        var form = e.target.closest('form');
        _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].elLPOverlay.show();
        _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].setTitleModal(form.dataset.title);
        _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].setContentModal('<div class="pd-2em">' + form.dataset.confirm + '</div>');

        _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].callBackYes = function () {
          form.submit();
        };
      });
    }

    if (this.elBtnFinishCourse) {
      this.elBtnFinishCourse.forEach(function (element) {
        return element.addEventListener('click', function (e) {
          e.preventDefault();
          var form = e.target.closest('form');
          _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].elLPOverlay.show();
          _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].setTitleModal(form.dataset.title);
          _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].setContentModal('<div class="pd-2em">' + form.dataset.confirm + '</div>');

          _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__["default"].callBackYes = function () {
            form.submit();
          };
        });
      });
    }
  }
};
/* harmony default export */ __webpack_exports__["default"] = (lpModalOverlayCompleteItem);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum.js":
/*!*******************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum.js ***!
  \*******************************************************************************************************************************************/
/*! exports provided: default, init */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
/* harmony import */ var _single_curriculum_index__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./single-curriculum/index */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/index.js");
/* harmony import */ var _show_lp_overlay_complete_item__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./show-lp-overlay-complete-item */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/show-lp-overlay-complete-item.js");
/* harmony import */ var _single_curriculum_scrolltoitem__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./single-curriculum/scrolltoitem */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/scrolltoitem.js");
var $ = jQuery;



/* harmony default export */ __webpack_exports__["default"] = (_single_curriculum_index__WEBPACK_IMPORTED_MODULE_0__["default"]);
var init = function init() {
  wp.element.render( /*#__PURE__*/React.createElement(_single_curriculum_index__WEBPACK_IMPORTED_MODULE_0__["default"], null), document.getElementById('learn-press-course-curriculum'));
};
document.addEventListener('DOMContentLoaded', function (event) {
  LP.Hook.doAction('course-ready');
  _show_lp_overlay_complete_item__WEBPACK_IMPORTED_MODULE_1__["default"].init();
  _single_curriculum_scrolltoitem__WEBPACK_IMPORTED_MODULE_2__["default"].init(); //init();
});

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/comment.js":
/*!**************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/comment.js ***!
  \**************************************************************************************************************************************************************/
/*! exports provided: commentForm */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "commentForm", function() { return commentForm; });
/**
 * Toogle form Comment for Lesson.
 *
 * @author Nhamdv.
 */
var commentForm = function commentForm() {
  var btn = document.querySelector('.lp-lesson-comment-btn');

  if (!btn) {
    return;
  }

  var btnOpen = btn.textContent;
  var btnClose = btn.dataset.close;
  var hashComment = window.location.hash;

  if (hashComment.includes('comment')) {
    btn.parentNode.classList.add('open-comments');
  }

  var toogleText = function toogleText(btn, btnParent) {
    if (btnParent.classList.contains('open-comments')) {
      btn.textContent = btnClose;
    } else {
      btn.textContent = btnOpen;
    }
  };

  toogleText(btn, btn.parentNode);
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    btn.parentNode.classList.toggle('open-comments');
    toogleText(btn, btn.parentNode);
  }); // Use for Rest API.
  // const toogle = $( '#learn-press-item-comments-toggle' );
  // toogle.on( 'change', async function() {
  // 	if ( ! toogle[ 0 ].checked ) {
  // 		return;
  // 	}
  // 	const response = await wp.apiFetch( {
  // 		path: 'lp/v1/courses/339/item-comments/50',
  // 	} );
  // 	$( '.learn-press-comments' ).html( response.comments );
  // } );
};

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/compatible.js":
/*!*****************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/compatible.js ***!
  \*****************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/**
 * Compatible with Page Builder.
 *
 * @author nhamdv
 */
LP.Hook.addAction('lp-compatible-builder', function () {
  LP.Hook.removeAction('lp-compatible-builder');

  if (typeof elementorFrontend !== 'undefined') {
    _toConsumableArray(document.querySelectorAll('#popup-content'))[0].addEventListener('scroll', function () {
      Waypoint.refreshAll();
      window.dispatchEvent(new Event('resize'));
    });
  }

  if (typeof vc_js !== 'undefined' && typeof VcWaypoint !== 'undefined') {
    _toConsumableArray(document.querySelectorAll('#popup-content'))[0].addEventListener('scroll', function () {
      VcWaypoint.refreshAll();
    });
  }
});
LP.Hook.addAction('lp-quiz-compatible-builder', function () {
  LP.Hook.removeAction('lp-quiz-compatible-builder');
  LP.Hook.doAction('lp-compatible-builder');

  if (typeof elementorFrontend !== 'undefined') {
    return window.elementorFrontend.init();
  }

  if (typeof vc_js !== 'undefined') {
    if (typeof vc_round_charts !== 'undefined') {
      vc_round_charts();
    }

    if (typeof vc_pieChart !== 'undefined') {
      vc_pieChart();
    }

    if (typeof vc_line_charts !== 'undefined') {
      vc_line_charts();
    }

    return window.vc_js();
  }
});
LP.Hook.addAction('lp-question-compatible-builder', function () {
  LP.Hook.removeAction('lp-question-compatible-builder');
  LP.Hook.removeAction('lp-quiz-compatible-builder');
  LP.Hook.doAction('lp-compatible-builder');

  if (typeof elementorFrontend !== 'undefined') {
    return window.elementorFrontend.init();
  }

  if (typeof vc_js !== 'undefined') {
    if (typeof vc_round_charts !== 'undefined') {
      vc_round_charts();
    }

    if (typeof vc_pieChart !== 'undefined') {
      vc_pieChart();
    }

    if (typeof vc_line_charts !== 'undefined') {
      vc_line_charts();
    }

    return window.vc_js();
  }
});

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/items-progress.js":
/*!*********************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/items-progress.js ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: itemsProgress, getResponse */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "itemsProgress", function() { return itemsProgress; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getResponse", function() { return getResponse; });
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _show_lp_overlay_complete_item__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../show-lp-overlay-complete-item */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/show-lp-overlay-complete-item.js");
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

// Rest API load content course progress - Nhamdv.


var itemsProgress = function itemsProgress() {
  var elements = document.querySelectorAll('.popup-header__inner');

  if (!elements.length) {
    return;
  }

  if (document.querySelector('#learn-press-quiz-app div.quiz-result') !== null) {
    return;
  }

  if (elements[0].querySelectorAll('form.form-button-finish-course').length !== 0) {
    return;
  }

  if ('IntersectionObserver' in window) {
    var eleObserver = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var ele = entry.target;
          getResponse(ele);
          eleObserver.unobserve(ele);
        }
      });
    });

    _toConsumableArray(elements).map(function (ele) {
      return eleObserver.observe(ele);
    });
  }
};
var getResponse = /*#__PURE__*/function () {
  var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(ele) {
    var response, data;
    return regeneratorRuntime.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.next = 2;
            return wp.apiFetch({
              path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__["addQueryArgs"])('lp/v1/lazy-load/items-progress', {
                courseId: lpGlobalSettings.post_id || '',
                userId: lpGlobalSettings.user_id || ''
              }),
              method: 'GET'
            });

          case 2:
            response = _context.sent;
            data = response.data;
            ele.innerHTML += data;
            _show_lp_overlay_complete_item__WEBPACK_IMPORTED_MODULE_1__["default"].init();

          case 6:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));

  return function getResponse(_x) {
    return _ref.apply(this, arguments);
  };
}();

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/progress.js":
/*!***************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/progress.js ***!
  \***************************************************************************************************************************************************************/
/*! exports provided: progressBar */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "progressBar", function() { return progressBar; });
var $ = jQuery;
var progressBar = function progressBar() {
  $('.learn-press-progress').each(function () {
    var $progress = $(this);
    var $active = $progress.find('.learn-press-progress__active');
    var value = $active.data('value');

    if (value === undefined) {
      return;
    }

    $active.css('left', -(100 - parseInt(value)) + '%');
  });
};

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/search.js":
/*!*************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/search.js ***!
  \*************************************************************************************************************************************************************/
/*! exports provided: searchCourseContent */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "searchCourseContent", function() { return searchCourseContent; });
var searchCourseContent = function searchCourseContent() {
  var popup = document.querySelector('#popup-course');
  var list = document.querySelector('#learn-press-course-curriculum');

  if (popup && list) {
    var items = list.querySelector('.curriculum-sections');
    var form = popup.querySelector('.search-course');
    var search = popup.querySelector('.search-course input[type="text"]');

    if (!search || !items || !form) {
      return;
    }

    var sections = items.querySelectorAll('li.section');
    var dataItems = items.querySelectorAll('li.course-item');
    var dataSearch = [];
    dataItems.forEach(function (item) {
      var itemID = item.dataset.id;
      var name = item.querySelector('.item-name');
      dataSearch.push({
        id: itemID,
        name: name ? name.textContent.toLowerCase() : ''
      });
    });

    var submit = function submit(event) {
      event.preventDefault();
      var inputVal = search.value;
      form.classList.add('searching');

      if (!inputVal) {
        form.classList.remove('searching');
      }

      var outputs = [];
      dataSearch.forEach(function (i) {
        if (!inputVal || i.name.match(inputVal.toLowerCase())) {
          outputs.push(i.id);
          dataItems.forEach(function (c) {
            if (outputs.indexOf(c.dataset.id) !== -1) {
              c.classList.remove('hide-if-js');
            } else {
              c.classList.add('hide-if-js');
            }
          });
        }
      });
      sections.forEach(function (section) {
        var listItem = section.querySelectorAll('.course-item');
        var isTrue = [];
        listItem.forEach(function (a) {
          if (outputs.includes(a.dataset.id)) {
            isTrue.push(a.dataset.id);
          }
        });

        if (isTrue.length === 0) {
          section.classList.add('hide-if-js');
        } else {
          section.classList.remove('hide-if-js');
        }
      });
    };

    var clear = form.querySelector('.clear');

    if (clear) {
      clear.addEventListener('click', function (e) {
        e.preventDefault();
        search.value = '';
        submit(e);
      });
    }

    form.addEventListener('submit', submit);
    search.addEventListener('keyup', submit);
  }
};

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/sidebar.js":
/*!**************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/sidebar.js ***!
  \**************************************************************************************************************************************************************/
/*! exports provided: Sidebar */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Sidebar", function() { return Sidebar; });
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var $ = jQuery;
var _lodash = lodash,
    throttle = _lodash.throttle;
var Sidebar = function Sidebar() {
  // Tungnx - Show/hide sidebar curriculumn
  var elSidebarToggle = document.querySelector('#sidebar-toggle'); // For style of theme

  var toggleSidebar = function toggleSidebar(toggle) {
    $('body').removeClass('lp-sidebar-toggle__open');
    $('body').removeClass('lp-sidebar-toggle__close');

    if (toggle) {
      $('body').addClass('lp-sidebar-toggle__close');
    } else {
      $('body').addClass('lp-sidebar-toggle__open');
    }
  }; // For lp and theme


  if (elSidebarToggle) {
    if ($(window).innerWidth() <= 768) {
      elSidebarToggle.setAttribute('checked', 'checked');
    } else if (LP.Cookies.get('sidebar-toggle')) {
      elSidebarToggle.setAttribute('checked', 'checked');
    } else {
      elSidebarToggle.removeAttribute('checked');
    }

    document.querySelector('#popup-course').addEventListener('click', function (e) {
      if (e.target.id === 'sidebar-toggle') {
        LP.Cookies.set('sidebar-toggle', e.target.checked ? true : false);
        toggleSidebar(LP.Cookies.get('sidebar-toggle'));
      }
    });
  } // End editor by tungnx


  var $curriculum = $('#learn-press-course-curriculum');
  $curriculum.find('.section-desc').each(function (i, el) {
    var a = $('<span class="show-desc"></span>').on('click', function () {
      b.toggleClass('c');
    });
    var b = $(el).siblings('.section-title').append(a);
  });
  $('.section').each(function () {
    var $section = $(this),
        $toggle = $section.find('.section-left');
    $toggle.on('click', function () {
      var isClose = $section.toggleClass('closed').hasClass('closed');
      var sections = LP.Cookies.get('closed-section-' + lpGlobalSettings.post_id) || [];
      var sectionId = parseInt($section.data('section-id'));
      var at = sections.findIndex(function (id) {
        return id == sectionId;
      });

      if (isClose) {
        sections.push(parseInt($section.data('section-id')));
      } else {
        sections.splice(at, 1);
      }

      LP.Cookies.remove('closed-section-(.*)');
      LP.Cookies.set('closed-section-' + lpGlobalSettings.post_id, _toConsumableArray(new Set(sections)));
    });
  });
};

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/index.js":
/*!*************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/index.js ***!
  \*************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_search__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/search */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/search.js");
/* harmony import */ var _components_sidebar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/sidebar */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/sidebar.js");
/* harmony import */ var _components_progress__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/progress */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/progress.js");
/* harmony import */ var _components_comment__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/comment */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/comment.js");
/* harmony import */ var _components_items_progress__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/items-progress */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/items-progress.js");
/* harmony import */ var _components_compatible__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/compatible */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/compatible.js");
/* harmony import */ var _components_compatible__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_components_compatible__WEBPACK_IMPORTED_MODULE_6__);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var $ = jQuery;








var SingleCurriculums = /*#__PURE__*/function (_Component) {
  _inherits(SingleCurriculums, _Component);

  var _super = _createSuper(SingleCurriculums);

  function SingleCurriculums() {
    _classCallCheck(this, SingleCurriculums);

    return _super.apply(this, arguments);
  }

  _createClass(SingleCurriculums, [{
    key: "checkCourseDurationExpire",
    value: function checkCourseDurationExpire() {
      var elCourseItemIsBlockeds = document.getElementsByName('lp-course-timestamp-remaining');

      if (elCourseItemIsBlockeds.length) {
        var elCourseItemIsBlocked = elCourseItemIsBlockeds[0];
        var timeDuration = elCourseItemIsBlocked.value; // value is second

        if (timeDuration < 60 * 60 * 24) {
          // compare with 1 day
          setTimeout(function () {
            window.location.reload(true);
          }, timeDuration * 1000);
        }
      }
    }
  }, {
    key: "render",
    value: function render() {
      return /*#__PURE__*/React.createElement("div", null);
    }
  }]);

  return SingleCurriculums;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (SingleCurriculums);
document.addEventListener('DOMContentLoaded', function () {
  LP.Hook.doAction('lp-compatible-builder');
  Object(_components_search__WEBPACK_IMPORTED_MODULE_1__["searchCourseContent"])();
  Object(_components_sidebar__WEBPACK_IMPORTED_MODULE_2__["Sidebar"])();
  Object(_components_progress__WEBPACK_IMPORTED_MODULE_3__["progressBar"])(); //commentForm();

  Object(_components_items_progress__WEBPACK_IMPORTED_MODULE_5__["itemsProgress"])(); // Check duration expire of course

  var singleCurriculums = new SingleCurriculums();
  singleCurriculums.checkCourseDurationExpire();
});

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/scrolltoitem.js":
/*!********************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/scrolltoitem.js ***!
  \********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utils_lp_modal_overlay__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utils/lp-modal-overlay */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/utils/lp-modal-overlay.js");

var $ = jQuery;
var scrollToItemCurrent = {
  init: function init() {
    this.scrollToItemViewing = function () {
      var elItemViewing = $('.viewing-course-item');

      if (elItemViewing.length) {
        var elCourseCurriculumn = $('#learn-press-course-curriculum');
        var heightCourseItemContentHeader = $('#popup-sidebar').outerHeight();
        var heightSectionTitle = $('.section-title').outerHeight();
        var heightSectionHeader = $('.section-header').outerHeight();
        var regex = new RegExp('^viewing-course-item-([0-9].*)');
        var classList = elItemViewing.attr('class');
        var classArr = classList.split(/\s+/);
        var idItem = 0;
        $.each(classArr, function (i, className) {
          var compare = regex.exec(className);

          if (compare) {
            idItem = compare[1];
            return false;
          }
        });

        if (0 === idItem) {
          return;
        }

        var elItemCurrent = $('.course-item-' + idItem);
        var offSetTop = elItemCurrent.offset().top;
        var offset = elItemCurrent.offset().top - elCourseCurriculumn.offset().top + elCourseCurriculumn.scrollTop();
        elCourseCurriculumn.animate({
          scrollTop: offset - heightSectionHeader
        }, 800);
      }
    };

    this.scrollToItemViewing();
  }
};
/* harmony default export */ __webpack_exports__["default"] = (scrollToItemCurrent);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/utils/lp-modal-overlay.js":
/*!***************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/utils/lp-modal-overlay.js ***!
  \***************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var $ = jQuery;
var elLPOverlay = null;
var lpModalOverlay = {
  elLPOverlay: null,
  elMainContent: null,
  elTitle: null,
  elBtnYes: null,
  elBtnNo: null,
  elFooter: null,
  elCalledModal: null,
  callBackYes: null,
  instance: null,
  init: function init() {
    if (this.instance) {
      return true;
    }

    this.elLPOverlay = $('.lp-overlay');

    if (!this.elLPOverlay.length) {
      return false;
    }

    elLPOverlay = this.elLPOverlay;
    this.elMainContent = elLPOverlay.find('.main-content');
    this.elTitle = elLPOverlay.find('.modal-title');
    this.elBtnYes = elLPOverlay.find('.btn-yes');
    this.elBtnNo = elLPOverlay.find('.btn-no');
    this.elFooter = elLPOverlay.find('.lp-modal-footer');
    $(document).on('click', '.close, .btn-no', function () {
      elLPOverlay.hide();
    });
    $(document).on('click', '.btn-yes', function (e) {
      e.preventDefault();
      e.stopPropagation();

      if ('function' === typeof lpModalOverlay.callBackYes) {
        lpModalOverlay.callBackYes();
      }
    });
    this.instance = this;
    return true;
  },
  setElCalledModal: function setElCalledModal(elCalledModal) {
    this.elCalledModal = elCalledModal;
  },
  setContentModal: function setContentModal(content, event) {
    this.elMainContent.html(content);

    if ('function' === typeof event) {
      event();
    }
  },
  setTitleModal: function setTitleModal(content) {
    this.elTitle.html(content);
  }
};
/* harmony default export */ __webpack_exports__["default"] = (lpModalOverlay);

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

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
//# sourceMappingURL=single-curriculum.js.map