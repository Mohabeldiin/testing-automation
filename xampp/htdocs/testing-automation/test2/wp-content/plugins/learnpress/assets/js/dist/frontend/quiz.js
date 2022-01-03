this["LP"] = this["LP"] || {}; this["LP"]["quiz"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz.js":
/*!******************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz.js ***!
  \******************************************************************************************************************************/
/*! exports provided: default, init */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "init", function() { return init; });
/* harmony import */ var _quiz_index__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./quiz/index */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/index.js");
/* harmony import */ var _single_curriculum_components_compatible__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./single-curriculum/components/compatible */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/compatible.js");
/* harmony import */ var _single_curriculum_components_compatible__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_single_curriculum_components_compatible__WEBPACK_IMPORTED_MODULE_1__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



var _LP = LP,
    Modal = _LP.modal["default"];
/* harmony default export */ __webpack_exports__["default"] = (_quiz_index__WEBPACK_IMPORTED_MODULE_0__["default"]);
var init = function init(elem, settings) {
  wp.element.render( /*#__PURE__*/React.createElement(Modal, null, /*#__PURE__*/React.createElement(_quiz_index__WEBPACK_IMPORTED_MODULE_0__["default"], {
    settings: settings
  })), _toConsumableArray(document.querySelectorAll(elem))[0]);
  LP.Hook.doAction('lp-quiz-compatible-builder');
};

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/attempts/index.js":
/*!********************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/attempts/index.js ***!
  \********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _duration__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../duration */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/duration/index.js");



/**
 * Displays list of all attempt from a quiz.
 */

var Attempts = function Attempts() {
  var attempts = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('learnpress/quiz').getData('attempts') || [];
  var hasAttempts = attempts && !!attempts.length;
  return !hasAttempts ? false : /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    className: "quiz-attempts"
  }, /*#__PURE__*/React.createElement("h4", {
    className: "attempts-heading"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Last Attempted', 'learnpress')), hasAttempts && /*#__PURE__*/React.createElement("table", null, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("th", {
    className: "quiz-attempts__questions"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Questions', 'learnpress')), /*#__PURE__*/React.createElement("th", {
    className: "quiz-attempts__spend"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Time spend', 'learnpress')), /*#__PURE__*/React.createElement("th", {
    className: "quiz-attempts__marks"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Marks', 'learnpress')), /*#__PURE__*/React.createElement("th", {
    className: "quiz-attempts__grade"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Passing grade', 'learnpress')), /*#__PURE__*/React.createElement("th", {
    className: "quiz-attempts__result"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Result', 'learnpress')))), /*#__PURE__*/React.createElement("tbody", null, attempts.map(function (row, key) {
    // Re-write value to attempts.timeSpend
    if (lpQuizSettings.checkNorequizenroll == '1') {
      var timespendStart = window.localStorage.getItem('quiz_start_' + lpQuizSettings.id),
          timespendEnd = window.localStorage.getItem('quiz_end_' + lpQuizSettings.id);

      if (timespendStart && timespendEnd) {
        row.timeSpend = timeDifference(timespendStart, timespendEnd).duration;
      }
    }

    return /*#__PURE__*/React.createElement("tr", {
      key: "attempt-".concat(key)
    }, /*#__PURE__*/React.createElement("td", {
      className: "quiz-attempts__questions"
    }, "".concat(row.questionCorrect, " / ").concat(row.questionCount)), /*#__PURE__*/React.createElement("td", {
      className: "quiz-attempts__spend"
    }, row.timeSpend || '--'), /*#__PURE__*/React.createElement("td", {
      className: "quiz-attempts__marks"
    }, "".concat(row.userMark, " / ").concat(row.mark)), /*#__PURE__*/React.createElement("td", {
      className: "quiz-attempts__grade"
    }, row.passingGrade || '-'), /*#__PURE__*/React.createElement("td", {
      className: "quiz-attempts__result"
    }, "".concat(parseFloat(row.result).toFixed(2), "%"), " ", /*#__PURE__*/React.createElement("span", null, row.graduationText)));
  })))));
};

function timeDifference(earlierDate, laterDate) {
  var oDiff = new Object(); //  Calculate Differences
  //  -------------------------------------------------------------------  //

  var nTotalDiff = laterDate - earlierDate;
  oDiff.days = Math.floor(nTotalDiff / 1000 / 60 / 60 / 24);
  nTotalDiff -= oDiff.days * 1000 * 60 * 60 * 24;
  oDiff.hours = Math.floor(nTotalDiff / 1000 / 60 / 60);
  nTotalDiff -= oDiff.hours * 1000 * 60 * 60;
  oDiff.minutes = Math.floor(nTotalDiff / 1000 / 60);
  nTotalDiff -= oDiff.minutes * 1000 * 60;
  oDiff.seconds = Math.floor(nTotalDiff / 1000); //  -------------------------------------------------------------------  //
  //  Format Duration
  //  -------------------------------------------------------------------  //
  //  Format Hours

  var hourtext = '00';

  if (oDiff.days > 0) {
    hourtext = String(oDiff.days);
  }

  if (hourtext.length == 1) {
    hourtext = '0' + hourtext;
  } //  Format Minutes


  var mintext = '00';

  if (oDiff.minutes > 0) {
    mintext = String(oDiff.minutes);
  }

  if (mintext.length == 1) {
    mintext = '0' + mintext;
  } //  Format Seconds


  var sectext = '00';

  if (oDiff.seconds > 0) {
    sectext = String(oDiff.seconds);
  }

  if (sectext.length == 1) {
    sectext = '0' + sectext;
  } //  Set Duration


  var sDuration = hourtext + ':' + mintext + ':' + sectext;
  oDiff.duration = sDuration; //  -------------------------------------------------------------------  //

  return oDiff;
}

/* harmony default export */ __webpack_exports__["default"] = (Attempts);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-check.js":
/*!**************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-check.js ***!
  \**************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! classnames */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }







var ButtonCheck = /*#__PURE__*/function (_Component) {
  _inherits(ButtonCheck, _Component);

  var _super = _createSuper(ButtonCheck);

  function ButtonCheck() {
    var _this;

    _classCallCheck(this, ButtonCheck);

    _this = _super.apply(this, arguments);

    _defineProperty(_assertThisInitialized(_this), "checkAnswer", function () {
      var _this$props = _this.props,
          checkAnswer = _this$props.checkAnswer,
          question = _this$props.question,
          answered = _this$props.answered;

      if (answered) {
        checkAnswer(question.id);

        _this.setState({
          loading: true
        });
      }
    });

    _this.state = {
      loading: false
    };
    return _this;
  }

  _createClass(ButtonCheck, [{
    key: "render",
    value: function render() {
      var answered = this.props.answered;
      return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("button", {
        className: classnames__WEBPACK_IMPORTED_MODULE_0___default()('lp-button', 'instant-check', {
          loading: this.state.loading,
          disable: !answered
        }),
        onClick: this.checkAnswer
      }, /*#__PURE__*/React.createElement("span", {
        className: "instant-check__icon"
      }), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('Check answer', 'learnpress'), !answered && /*#__PURE__*/React.createElement("div", {
        className: "instant-check__info",
        dangerouslySetInnerHTML: {
          __html: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__["__"])('You need to answer the question before check answer.', 'learnpress')
        }
      })));
    }
  }]);

  return ButtonCheck;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withSelect"])(function (select, _ref) {
  var id = _ref.question.id;

  var _select = select('learnpress/quiz'),
      getQuestionAnswered = _select.getQuestionAnswered;

  return {
    answered: getQuestionAnswered(id)
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withDispatch"])(function (dispatch, _ref2) {
  var id = _ref2.id;

  var _dispatch = dispatch('learnpress/quiz'),
      _checkAnswer = _dispatch.checkAnswer;

  return {
    checkAnswer: function checkAnswer(id) {
      _checkAnswer(id);
    }
  };
}))(ButtonCheck));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-hint.js":
/*!*************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-hint.js ***!
  \*************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }






var ButtonHint = /*#__PURE__*/function (_Component) {
  _inherits(ButtonHint, _Component);

  var _super = _createSuper(ButtonHint);

  function ButtonHint() {
    var _this;

    _classCallCheck(this, ButtonHint);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    _defineProperty(_assertThisInitialized(_this), "showHint", function () {
      var _this$props = _this.props,
          showHint = _this$props.showHint,
          question = _this$props.question;
      showHint(question.id, !question.showHint);
    });

    return _this;
  }

  _createClass(ButtonHint, [{
    key: "render",
    value: function render() {
      var question = this.props.question;
      return question.hint ? /*#__PURE__*/React.createElement("button", {
        className: "btn-show-hint",
        onClick: this.showHint
      }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Hint', 'learnpress'))) : '';
    }
  }]);

  return ButtonHint;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withDispatch"])(function (dispatch, _ref) {
  var id = _ref.id;

  var _dispatch = dispatch('learnpress/quiz'),
      _showHint = _dispatch.showHint;

  return {
    showHint: function showHint(id, show) {
      _showHint(id, show);
    }
  };
}))(ButtonHint));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/index.js":
/*!*******************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/index.js ***!
  \*******************************************************************************************************************************************************/
/*! exports provided: MaybeShowButton, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MaybeShowButton", function() { return MaybeShowButton; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }






var Buttons = /*#__PURE__*/function (_Component) {
  _inherits(Buttons, _Component);

  var _super = _createSuper(Buttons);

  function Buttons() {
    var _this;

    _classCallCheck(this, Buttons);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this = _super.call.apply(_super, [this].concat(args));

    _defineProperty(_assertThisInitialized(_this), "startQuiz", function (event) {
      event && event.preventDefault();
      var btn = document.querySelector('.lp-button.start');
      btn && btn.setAttribute('disabled', 'disabled');
      var _this$props = _this.props,
          startQuiz = _this$props.startQuiz,
          status = _this$props.status;

      if (status === 'completed') {
        var _select = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/modal'),
            confirm = _select.confirm,
            isOpen = _select.isOpen;

        if ('no' === confirm(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Are you sure you want to retake quiz?', 'learnpress'), _this.startQuiz)) {
          !isOpen() && btn && btn.removeAttribute('disabled');
          return;
        }
      }

      if (lpQuizSettings.checkNorequizenroll == '1') {
        // remove & set start_time to local.storage
        window.localStorage.removeItem('quiz_start_' + lpQuizSettings.id);
        window.localStorage.setItem('quiz_start_' + lpQuizSettings.id, Date.now()); // Set retake to local.storage

        var retakenNumber = window.localStorage.getItem('quiz_retake_' + lpQuizSettings.id);

        if (retakenNumber >= 1) {
          window.localStorage.setItem('quiz_retake_' + lpQuizSettings.id, parseInt(retakenNumber) + 1);
        } else {
          window.localStorage.setItem('quiz_retake_' + lpQuizSettings.id, 1);
        } // Reset User Data


        window.localStorage.removeItem('quiz_userdata_' + lpQuizSettings.id);
      }

      startQuiz();
    });

    _defineProperty(_assertThisInitialized(_this), "nav", function (to) {
      return function (event) {
        var _this$props2 = _this.props,
            questionNav = _this$props2.questionNav,
            currentPage = _this$props2.currentPage,
            numPages = _this$props2.numPages,
            setCurrentPage = _this$props2.setCurrentPage;

        switch (to) {
          case 'prev':
            if (currentPage > 1) {
              currentPage = currentPage - 1;
            } else if (questionNav === 'infinity') {
              currentPage = numPages;
            } else {
              currentPage = 1;
            }

            break;

          default:
            if (currentPage < numPages) {
              currentPage = currentPage + 1;
            } else if (questionNav === 'infinity') {
              currentPage = 1;
            } else {
              currentPage = numPages;
            }

        }

        setCurrentPage(currentPage);
      };
    });

    _defineProperty(_assertThisInitialized(_this), "moveTo", function (pageNum) {
      return function (event) {
        event.preventDefault();
        var _this$props3 = _this.props,
            numPages = _this$props3.numPages,
            setCurrentPage = _this$props3.setCurrentPage;

        if (pageNum < 1 || pageNum > numPages) {
          return;
        }

        setCurrentPage(pageNum);
      };
    });

    _defineProperty(_assertThisInitialized(_this), "isLast", function () {
      var _this$props4 = _this.props,
          currentPage = _this$props4.currentPage,
          numPages = _this$props4.numPages;
      return currentPage === numPages;
    });

    _defineProperty(_assertThisInitialized(_this), "isFirst", function () {
      var currentPage = _this.props.currentPage;
      return currentPage === 1;
    });

    _defineProperty(_assertThisInitialized(_this), "submit", function () {
      var submitQuiz = _this.props.submitQuiz;

      var _select2 = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/modal'),
          confirm = _select2.confirm;

      if ('no' === confirm(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Are you sure to submit quiz?', 'learnpress'), _this.submit)) {
        return;
      }

      submitQuiz();
    });

    _defineProperty(_assertThisInitialized(_this), "setQuizMode", function (mode) {
      return function () {
        var setQuizMode = _this.props.setQuizMode;
        setQuizMode(mode);
      };
    });

    _defineProperty(_assertThisInitialized(_this), "isReviewing", function () {
      var isReviewing = _this.props.isReviewing;
      return isReviewing;
    });

    return _this;
  }

  _createClass(Buttons, [{
    key: "pageNumbers",
    value: function pageNumbers(args) {
      var _this2 = this;

      var _this$props5 = this.props,
          numPages = _this$props5.numPages,
          currentPage = _this$props5.currentPage;

      if (numPages < 2) {
        return '';
      }

      args = _objectSpread({
        numPages: numPages,
        currentPage: currentPage,
        midSize: 1,
        endSize: 1,
        prevNext: true
      }, args || {});

      if (args.endSize < 1) {
        args.endSize = 1;
      }

      if (args.midSize < 0) {
        args.midSize = 1;
      }

      var numbers = _toConsumableArray(Array(numPages).keys());

      var dots = false;
      return /*#__PURE__*/React.createElement("div", {
        className: "nav-links"
      }, args.prevNext && !this.isFirst() && /*#__PURE__*/React.createElement("button", {
        className: "page-numbers prev",
        "data-type": "question-navx",
        onClick: this.nav('prev')
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Prev', 'learnpress')), numbers.map(function (number) {
        number = number + 1;

        if (number === args.currentPage) {
          dots = true;
          return /*#__PURE__*/React.createElement("span", {
            key: "page-number-".concat(number),
            className: "page-numbers current"
          }, number);
        }

        if (number <= args.endSize || args.currentPage && number >= args.currentPage - args.midSize && number <= args.currentPage + args.midSize || number > args.numPages - args.endSize) {
          dots = true;
          return /*#__PURE__*/React.createElement("button", {
            key: "page-number-".concat(number),
            className: "page-numbers",
            onClick: _this2.moveTo(number)
          }, number);
        } else if (dots) {
          dots = false;
          return /*#__PURE__*/React.createElement("span", {
            key: "page-number-".concat(number),
            className: "page-numbers dots"
          }, "\u2026");
        }

        return '';
      }), args.prevNext && !this.isLast() && /*#__PURE__*/React.createElement("button", {
        className: "page-numbers next",
        "data-type": "question-navx",
        onClick: this.nav('next')
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Next', 'learnpress')));
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props6 = this.props,
          status = _this$props6.status,
          questionNav = _this$props6.questionNav,
          isReviewing = _this$props6.isReviewing,
          showReview = _this$props6.showReview,
          numPages = _this$props6.numPages,
          question = _this$props6.question,
          questionsPerPage = _this$props6.questionsPerPage,
          canRetry = _this$props6.canRetry,
          retakeNumber = _this$props6.retakeNumber;
      var classNames = ['quiz-buttons'];

      if (status === 'started' || isReviewing) {
        classNames.push('align-center');
      }

      if (questionNav === 'questionNav') {
        classNames.push('infinity');
      }

      if (this.isFirst()) {
        classNames.push('is-first');
      }

      if (this.isLast()) {
        classNames.push('is-last');
      }

      var popupSidebar = document.querySelector('#popup-sidebar');
      var quizzApp = document.querySelector('#learn-press-quiz-app');
      var styles = '';

      if (status === 'started' || isReviewing) {
        styles = {
          marginLeft: popupSidebar && popupSidebar.offsetWidth / 2,
          width: quizzApp && quizzApp.offsetWidth
        };
      } else {
        styles = null;
      }

      var navPositionClass = ' fixed';

      if (lpQuizSettings.navigationPosition == 'no') {
        navPositionClass = ' nav-center';
      }

      return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
        className: classNames.join(' ')
      }, /*#__PURE__*/React.createElement("div", {
        className: "button-left" + (status === 'started' || isReviewing ? navPositionClass : ''),
        style: styles
      }, (status === 'completed' && canRetry || -1 !== ['', 'viewed'].indexOf(status)) && !isReviewing && /*#__PURE__*/React.createElement("button", {
        className: "lp-button start",
        onClick: this.startQuiz
      }, status === 'completed' ? "".concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Retake', 'learnpress')).concat(retakeNumber ? " (".concat(retakeNumber, ")") : '') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Start', 'learnpress')), ('started' === status || isReviewing) && numPages > 1 && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
        className: "questions-pagination"
      }, this.pageNumbers()))), /*#__PURE__*/React.createElement("div", {
        className: "button-right"
      }, 'started' === status && /*#__PURE__*/React.createElement(React.Fragment, null, ('infinity' === questionNav || this.isLast()) && !isReviewing && /*#__PURE__*/React.createElement("button", {
        className: "lp-button submit-quiz",
        onClick: this.submit
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Finish Quiz', 'learnpress'))), isReviewing && showReview && /*#__PURE__*/React.createElement("button", {
        className: "lp-button back-quiz",
        onClick: this.setQuizMode('')
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Result', 'learnpress')), 'completed' === status && showReview && !isReviewing && /*#__PURE__*/React.createElement("button", {
        className: "lp-button review-quiz",
        onClick: this.setQuizMode('reviewing')
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Review', 'learnpress')))), this.props.message && this.props.success !== true && /*#__PURE__*/React.createElement("div", {
        className: "learn-press-message error"
      }, this.props.message));
    }
  }]);

  return Buttons;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);
/**
 * Helper function to check a button should be show or not.
 *
 * Buttons [hint, check]
 */


var MaybeShowButton = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withSelect"])(function (select) {
  var _select3 = select('learnpress/quiz'),
      getData = _select3.getData;

  return {
    status: getData('status'),
    showCheck: getData('instantCheck'),
    checkedQuestions: getData('checkedQuestions'),
    hintedQuestions: getData('hintedQuestions'),
    questionsPerPage: getData('questionsPerPage')
  };
}))(function (props) {
  var showCheck = props.showCheck,
      checkedQuestions = props.checkedQuestions,
      hintedQuestions = props.hintedQuestions,
      question = props.question,
      status = props.status,
      type = props.type,
      Button = props.Button;

  if (status !== 'started') {
    return false;
  }

  var theButton = /*#__PURE__*/React.createElement(Button, {
    question: question
  });

  switch (type) {
    case 'hint':
      if (!hintedQuestions) {
        return theButton;
      }

      if (!question.hasHint) {
        return false;
      }

      return hintedQuestions.indexOf(question.id) === -1 && theButton;

    case 'check':
      if (!showCheck) {
        return false;
      }

      if (!checkedQuestions) {
        return theButton;
      }

      return checkedQuestions.indexOf(question.id) === -1 && theButton;
  }
});
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withSelect"])(function (select) {
  var _select4 = select('learnpress/quiz'),
      getData = _select4.getData,
      getCurrentQuestion = _select4.getCurrentQuestion;

  var data = {
    id: getData('id'),
    status: getData('status'),
    questionIds: getData('questionIds'),
    questionNav: getData('questionNav'),
    isReviewing: getData('reviewQuestions') && getData('mode') === 'reviewing',
    showReview: getData('reviewQuestions'),
    showCheck: getData('instantCheck'),
    checkedQuestions: getData('checkedQuestions'),
    hintedQuestions: getData('hintedQuestions'),
    numPages: getData('numPages'),
    pages: getData('pages'),
    currentPage: getData('currentPage'),
    questionsPerPage: getData('questionsPerPage'),
    pageNumbers: getData('pageNumbers'),
    keyPressed: getData('keyPressed'),
    canRetry: getData('retakeCount') > 0 && getData('retaken') < getData('retakeCount'),
    retakeNumber: getData('retakeCount') > 0 && getData('retaken') < getData('retakeCount') ? getData('retakeCount') - getData('retaken') : null,
    message: getData('messageResponse') || false,
    success: getData('successResponse') !== undefined ? getData('successResponse') : true
  };

  if (data.questionsPerPage === 1) {
    data.question = getCurrentQuestion('object');
  }

  if (lpQuizSettings.checkNorequizenroll == '1') {
    var retakenCurrent = window.localStorage.getItem('quiz_retake_' + lpQuizSettings.id);

    if (getData('retakeCount') > retakenCurrent) {
      data.retakeNumber = getData('retakeCount') - retakenCurrent;
      data.canRetry = true;
    } else {
      data.canRetry = false;
    }
  }

  return data;
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withDispatch"])(function (dispatch, _ref) {
  var id = _ref.id;

  var _dispatch = dispatch('learnpress/quiz'),
      startQuiz = _dispatch.startQuiz,
      setCurrentQuestion = _dispatch.setCurrentQuestion,
      _submitQuiz = _dispatch.submitQuiz,
      setQuizMode = _dispatch.setQuizMode,
      _showHint = _dispatch.showHint,
      _checkAnswer = _dispatch.checkAnswer,
      setCurrentPage = _dispatch.setCurrentPage;

  return {
    startQuiz: startQuiz,
    setCurrentQuestion: setCurrentQuestion,
    setQuizMode: setQuizMode,
    setCurrentPage: setCurrentPage,
    submitQuiz: function submitQuiz(id) {
      _submitQuiz(id);
    },
    showHint: function showHint(id) {
      _showHint(id);
    },
    checkAnswer: function checkAnswer(id) {
      _checkAnswer(id);
    }
  };
})])(Buttons));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/content/index.js":
/*!*******************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/content/index.js ***!
  \*******************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Quizz Content.
 * Edit: Use React hook.
 *
 * @author nhamdv - ThimPress
 */


var Content = function Content() {
  var content = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('learnpress/quiz').getData('content');
  return /*#__PURE__*/React.createElement("div", {
    className: "quiz-content",
    dangerouslySetInnerHTML: {
      __html: content
    }
  });
};

/* harmony default export */ __webpack_exports__["default"] = (Content);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/duration/index.js":
/*!********************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/duration/index.js ***!
  \********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var formatDuration = function formatDuration(seconds) {
  var d;
  var dayInSeconds = 3600 * 24;

  if (seconds > dayInSeconds) {
    d = (seconds - seconds % dayInSeconds) / dayInSeconds;
    seconds = seconds % dayInSeconds;
  } else if (seconds == dayInSeconds) {
    return '24:00';
  }

  var x = new Date(seconds * 1000).toUTCString().match(/\d{2}:\d{2}:\d{2}/)[0].split(':');

  if (d) {
    x[0] = parseInt(x[0]) + d * 24;
  }

  var html = x.join(':');
  return html;
};

/* harmony default export */ __webpack_exports__["default"] = (formatDuration);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/index.js":
/*!***********************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/index.js ***!
  \***********************************************************************************************************************************************/
/*! exports provided: Title, Content, Meta, Buttons, Questions, Attempts, Timer, Result, Status */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _title__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./title */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/title/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Title", function() { return _title__WEBPACK_IMPORTED_MODULE_0__["default"]; });

/* harmony import */ var _content__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./content */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/content/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Content", function() { return _content__WEBPACK_IMPORTED_MODULE_1__["default"]; });

/* harmony import */ var _meta__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./meta */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/meta/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Meta", function() { return _meta__WEBPACK_IMPORTED_MODULE_2__["default"]; });

/* harmony import */ var _buttons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./buttons */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Buttons", function() { return _buttons__WEBPACK_IMPORTED_MODULE_3__["default"]; });

/* harmony import */ var _questions__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./questions */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Questions", function() { return _questions__WEBPACK_IMPORTED_MODULE_4__["default"]; });

/* harmony import */ var _attempts__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./attempts */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/attempts/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Attempts", function() { return _attempts__WEBPACK_IMPORTED_MODULE_5__["default"]; });

/* harmony import */ var _timer__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./timer */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/timer/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Timer", function() { return _timer__WEBPACK_IMPORTED_MODULE_6__["default"]; });

/* harmony import */ var _result__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./result */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/result/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Result", function() { return _result__WEBPACK_IMPORTED_MODULE_7__["default"]; });

/* harmony import */ var _status__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./status */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/status/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "Status", function() { return _status__WEBPACK_IMPORTED_MODULE_8__["default"]; });











/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/meta/index.js":
/*!****************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/meta/index.js ***!
  \****************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _duration__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../duration */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/duration/index.js");
/**
 * Quiz Meta.
 * Edit: Use React Hook.
 *
 * @author Nhamdv - ThimPress
 */



var _LP = LP,
    Hook = _LP.Hook;

var Meta = function Meta() {
  var getData = function getData(attr) {
    return Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('learnpress/quiz').getData(attr);
  };

  var metaFields = Hook.applyFilters('quiz-meta-fields', {
    duration: {
      title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Duration:', 'learnpress'),
      name: 'duration',
      content: Object(_duration__WEBPACK_IMPORTED_MODULE_2__["default"])(getData('duration')) || '--'
    },
    passingGrade: {
      title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Passing grade:', 'learnpress'),
      name: 'passing-grade',
      content: getData('passingGrade') || '--'
    },
    questionsCount: {
      title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Questions:', 'learnpress'),
      name: 'questions-count',
      content: getData('questionIds') ? getData('questionIds').length : 0
    }
  });
  return metaFields && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("ul", {
    className: "quiz-intro"
  }, Object.values(metaFields).map(function (field, i) {
    var id = field.name || i;
    return /*#__PURE__*/React.createElement("li", {
      key: "quiz-intro-field-".concat(i),
      className: "quiz-intro-item quiz-intro-item--".concat(id)
    }, /*#__PURE__*/React.createElement("div", {
      className: "quiz-intro-item__title",
      dangerouslySetInnerHTML: {
        __html: field.title
      }
    }), /*#__PURE__*/React.createElement("span", {
      className: "quiz-intro-item__content",
      dangerouslySetInnerHTML: {
        __html: field.content
      }
    }));
  })));
};

/* harmony default export */ __webpack_exports__["default"] = (Meta);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/buttons.js":
/*!***********************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/buttons.js ***!
  \***********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _buttons_button_hint__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../buttons/button-hint */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-hint.js");
/* harmony import */ var _buttons_button_check__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../buttons/button-check */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-check.js");
/* harmony import */ var _buttons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../buttons */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/index.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);





var Buttons = function Buttons(props) {
  var question = props.question;
  var buttons = {
    'instant-check': function instantCheck() {
      return /*#__PURE__*/React.createElement(_buttons__WEBPACK_IMPORTED_MODULE_2__["MaybeShowButton"], {
        type: "check",
        Button: _buttons_button_check__WEBPACK_IMPORTED_MODULE_1__["default"],
        question: question
      });
    },
    hint: function hint() {
      return /*#__PURE__*/React.createElement(_buttons__WEBPACK_IMPORTED_MODULE_2__["MaybeShowButton"], {
        type: "hint",
        Button: _buttons_button_hint__WEBPACK_IMPORTED_MODULE_0__["default"],
        question: question
      });
    }
  };
  return /*#__PURE__*/React.createElement(React.Fragment, null, LP.config.questionFooterButtons().map(function (name) {
    return /*#__PURE__*/React.createElement(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["Fragment"], {
      key: "button-".concat(name)
    }, buttons[name] && buttons[name]());
  }));
};

/* harmony default export */ __webpack_exports__["default"] = (Buttons);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/index.js":
/*!*********************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/index.js ***!
  \*********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _question__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./question */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/question.js");
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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }







var Questions = /*#__PURE__*/function (_Component) {
  _inherits(Questions, _Component);

  var _super = _createSuper(Questions);

  function Questions(props) {
    var _this;

    _classCallCheck(this, Questions);

    _this = _super.apply(this, arguments);

    _defineProperty(_assertThisInitialized(_this), "startQuiz", function (event) {
      event.preventDefault();
      var startQuiz = _this.props.startQuiz;
      startQuiz();
    });

    _defineProperty(_assertThisInitialized(_this), "isInVisibleRange", function (id, index) {
      var _this$props = _this.props,
          currentPage = _this$props.currentPage,
          questionsPerPage = _this$props.questionsPerPage;
      return currentPage === Math.ceil(index / questionsPerPage);
    });

    _defineProperty(_assertThisInitialized(_this), "nav", function (event) {
      var sendKey = _this.props.sendKey;

      switch (event.keyCode) {
        case 37:
          return sendKey('left');

        case 38:
          return;

        case 39:
          return sendKey('right');

        case 40:
          return;

        default:
          if (event.keyCode >= 49 && event.keyCode <= 57) {
            sendKey(event.keyCode - 48);
          }

      }
    });

    _this.needToTop = false;
    _this.state = {
      isReviewing: null,
      currentPage: 0,
      self: _assertThisInitialized(_this)
    };
    return _this;
  }

  _createClass(Questions, [{
    key: "componentDidUpdate",
    value: // componentWillReceiveProps(nextProps){
    //     const checkProps = ['isReviewing', 'currentPage'];
    //
    //     for(let i = 0; i < checkProps.length; i++){
    //         if(this.props[checkProps[i]] !== nextProps[checkProps[i]]){
    //             this.needToTop = true;
    //             return;
    //         }
    //     }
    //
    // }
    // componentWillUpdate() {
    //     this.needToTop = this.state.needToTop;
    //     this.setState({needToTop: false});
    // }
    function componentDidUpdate() {
      if (this.needToTop) {
        jQuery('#popup-content').animate({
          scrollTop: 0
        }).find('.content-item-scrollable:last').animate({
          scrollTop: 0
        });
        this.needToTop = false;
      }
    }
  }, {
    key: "render",
    value: function render() {
      var _this2 = this;

      var _this$props2 = this.props,
          status = _this$props2.status,
          currentQuestion = _this$props2.currentQuestion,
          questions = _this$props2.questions,
          questionsRendered = _this$props2.questionsRendered,
          isReviewing = _this$props2.isReviewing,
          questionsPerPage = _this$props2.questionsPerPage;
      var isShow = true;

      if (status === 'completed' && !isReviewing) {
        isShow = false;
      }

      return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
        tabIndex: 100,
        onKeyUp: this.nav
      }, /*#__PURE__*/React.createElement("div", {
        className: "quiz-questions",
        style: {
          display: isShow ? '' : 'none'
        }
      }, questions.map(function (question, index) {
        var isCurrent = questionsPerPage ? false : currentQuestion === question.id;
        var isRendered = questionsRendered && questionsRendered.indexOf(question.id) !== -1;

        var isVisible = _this2.isInVisibleRange(question.id, index + 1);

        return isRendered || !isRendered || isVisible ? /*#__PURE__*/React.createElement(_question__WEBPACK_IMPORTED_MODULE_4__["default"], {
          key: "loop-question-".concat(question.id),
          isCurrent: isCurrent,
          isShow: isVisible,
          isShowIndex: questionsPerPage ? index + 1 : false,
          questionsPerPage: questionsPerPage,
          question: question
        }) : '';
      }))));
    }
  }], [{
    key: "getDerivedStateFromProps",
    value: function getDerivedStateFromProps(props, state) {
      var checkProps = ['isReviewing', 'currentPage'];
      var changedProps = {};

      for (var i = 0; i < checkProps.length; i++) {
        if (props[checkProps[i]] !== state[checkProps[i]]) {
          changedProps[checkProps[i]] = props[checkProps[i]];
        }
      }

      if (Object.values(changedProps).length) {
        state.self.needToTop = true;
        return changedProps;
      }

      return null;
    }
  }]);

  return Questions;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withSelect"])(function (select, a, b) {
  var _select = select('learnpress/quiz'),
      getData = _select.getData,
      getQuestions = _select.getQuestions;

  return {
    status: getData('status'),
    currentQuestion: getData('currentQuestion'),
    questions: getQuestions(),
    questionsRendered: getData('questionsRendered'),
    isReviewing: getData('mode') === 'reviewing',
    numPages: getData('numPages'),
    currentPage: getData('currentPage'),
    questionsPerPage: getData('questionsPerPage') || 1
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withDispatch"])(function (dispatch) {
  var _dispatch = dispatch('learnpress/quiz'),
      startQuiz = _dispatch.startQuiz,
      sendKey = _dispatch.sendKey;

  return {
    startQuiz: startQuiz,
    sendKey: sendKey
  };
}))(Questions));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/question.js":
/*!************************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/question.js ***!
  \************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _buttons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./buttons */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/questions/buttons.js");
/* harmony import */ var _buttons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../buttons */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/index.js");
/* harmony import */ var _buttons_button_check__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../buttons/button-check */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-check.js");
/* harmony import */ var _buttons_button_hint__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../buttons/button-hint */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/buttons/button-hint.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }









var $ = window.jQuery;
var _lodash = lodash,
    uniqueId = _lodash.uniqueId,
    isArray = _lodash.isArray,
    isNumber = _lodash.isNumber,
    bind = _lodash.bind;

var Question = /*#__PURE__*/function (_Component) {
  _inherits(Question, _Component);

  var _super = _createSuper(Question);

  function Question() {
    var _this;

    _classCallCheck(this, Question);

    _this = _super.apply(this, arguments);

    _defineProperty(_assertThisInitialized(_this), "setRef", function (el) {
      _this.$wrap = $(el);
    });

    _defineProperty(_assertThisInitialized(_this), "parseOptions", function (options) {
      if (options) {
        options = !isArray(options) ? JSON.parse(CryptoJS.AES.decrypt(options.data, options.key, {
          format: CryptoJSAesJson
        }).toString(CryptoJS.enc.Utf8)) : options;
        options = !isArray(options) ? JSON.parse(options) : options;
      }

      return options || [];
    });

    _defineProperty(_assertThisInitialized(_this), "getWrapperClass", function () {
      var _this$props = _this.props,
          question = _this$props.question,
          answered = _this$props.answered;
      var classes = ['question', 'question-' + question.type];

      var options = _this.parseOptions(question.options);

      if (options.length && options[0].isTrue !== undefined) {
        classes.push('question-answered');
      }

      return classes;
    });

    _defineProperty(_assertThisInitialized(_this), "getEditLink", function () {
      var _this$props2 = _this.props,
          question = _this$props2.question,
          editPermalink = _this$props2.editPermalink;
      return editPermalink ? editPermalink.replace(/post=(.*[0-9])/, "post=".concat(question.id)) : '';
    });

    _defineProperty(_assertThisInitialized(_this), "editPermalink", function (editPermalink) {
      return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])('<a href="%s">%s</a>', editPermalink, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Edit', 'learnpress'));
    });

    _this.state = {
      time: null,
      showHint: false
    };
    _this.$wrap = null;
    return _this;
  }

  _createClass(Question, [{
    key: "componentDidMount",
    value: function componentDidMount(a) {
      var _this$props3 = this.props,
          question = _this$props3.question,
          isCurrent = _this$props3.isCurrent,
          markQuestionRendered = _this$props3.markQuestionRendered;

      if (isCurrent) {
        markQuestionRendered(question.id);
      }

      if (!this.state.time) {
        this.setState({
          time: new Date()
        });
      }

      LP.Hook.doAction('lp-question-compatible-builder');

      if (typeof MathJax !== 'undefined') {
        MathJax.Hub.Queue(['Typeset', MathJax.Hub]);
      }

      return a;
    }
  }, {
    key: "render",
    value: function render() {
      var _this2 = this;

      var _this$props4 = this.props,
          question = _this$props4.question,
          isShow = _this$props4.isShow,
          isShowIndex = _this$props4.isShowIndex,
          isShowHint = _this$props4.isShowHint,
          status = _this$props4.status;
      var QuestionTypes = LP.questionTypes["default"];

      var _editPermalink = this.getEditLink();

      if (_editPermalink) {
        jQuery('#wp-admin-bar-edit-lp_question').find('.ab-item').attr('href', _editPermalink);
      }

      var titleParts = {
        index: function index() {
          return isShowIndex ? /*#__PURE__*/React.createElement("span", {
            className: "question-index"
          }, isShowIndex, ".") : '';
        },
        title: function title() {
          return /*#__PURE__*/React.createElement("span", {
            dangerouslySetInnerHTML: {
              __html: question.title
            }
          });
        },
        hint: function hint() {
          return /*#__PURE__*/React.createElement(_buttons_button_hint__WEBPACK_IMPORTED_MODULE_7__["default"], {
            question: question
          });
        },
        'edit-permalink': function editPermalink() {
          return _editPermalink && /*#__PURE__*/React.createElement("span", {
            dangerouslySetInnerHTML: {
              __html: _this2.editPermalink(_editPermalink)
            },
            className: "edit-link"
          });
        }
      };
      var blocks = {
        title: function title() {
          return /*#__PURE__*/React.createElement("h4", {
            className: "question-title"
          }, LP.config.questionTitleParts().map(function (name) {
            return /*#__PURE__*/React.createElement(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], {
              key: "title-part-".concat(name)
            }, titleParts[name] && titleParts[name]());
          }));
        },
        content: function content() {
          return /*#__PURE__*/React.createElement("div", {
            className: "question-content",
            dangerouslySetInnerHTML: {
              __html: question.content
            }
          });
        },
        'answer-options': function answerOptions() {
          return _this2.$wrap && /*#__PURE__*/React.createElement(QuestionTypes, _objectSpread(_objectSpread({}, _this2.props), {}, {
            $wrap: _this2.$wrap
          }));
        },
        explanation: function explanation() {
          return question.explanation && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
            className: "question-explanation-content"
          }, /*#__PURE__*/React.createElement("strong", {
            className: "explanation-title"
          }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Explanation', 'learnpress'), ":"), /*#__PURE__*/React.createElement("div", {
            dangerouslySetInnerHTML: {
              __html: question.explanation
            }
          })));
        },
        hint: function hint() {
          return question.hint && !question.explanation && question.showHint && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
            className: "question-hint-content"
          }, /*#__PURE__*/React.createElement("strong", {
            className: "hint-title"
          }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Hint', 'learnpress'), ":"), /*#__PURE__*/React.createElement("div", {
            dangerouslySetInnerHTML: {
              __html: question.hint
            }
          })));
        },
        buttons: function buttons() {
          return 'started' === status && /*#__PURE__*/React.createElement(_buttons__WEBPACK_IMPORTED_MODULE_4__["default"], {
            question: question
          });
        }
      };
      var configBlocks = LP.config.questionBlocks();
      return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
        className: this.getWrapperClass().join(' '),
        style: {
          display: isShow ? '' : 'none'
        },
        "data-id": question.id,
        ref: this.setRef
      }, configBlocks.map(function (name) {
        return /*#__PURE__*/React.createElement(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], {
          key: "block-".concat(name)
        }, blocks[name] ? blocks[name]() : '');
      })));
    }
  }]);

  return Question;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withSelect"])(function (select, _ref) {
  var id = _ref.question.id;

  var _select = select('learnpress/quiz'),
      getData = _select.getData,
      getQuestionAnswered = _select.getQuestionAnswered,
      getQuestionMark = _select.getQuestionMark;

  return {
    status: getData('status'),
    questions: getData('question'),
    answered: getQuestionAnswered(id),
    questionsRendered: getData('questionsRendered'),
    editPermalink: getData('editPermalink'),
    numPages: getData('numPages'),
    mark: getQuestionMark(id) || ''
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["withDispatch"])(function (dispatch) {
  var _dispatch = dispatch('learnpress/quiz'),
      updateUserQuestionAnswers = _dispatch.updateUserQuestionAnswers,
      markQuestionRendered = _dispatch.markQuestionRendered;

  return {
    markQuestionRendered: markQuestionRendered,
    updateUserQuestionAnswers: updateUserQuestionAnswers
  };
})])(Question));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/result/index.js":
/*!******************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/result/index.js ***!
  \******************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _single_curriculum_components_items_progress__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../single-curriculum/components/items-progress */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/single-curriculum/components/items-progress.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * Quizz Result.
 * Edit: Use React hook.
 *
 * @author Nhamdv - ThimPress
 */




var _lodash = lodash,
    debounce = _lodash.debounce;

var Result = function Result() {
  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(0),
      _useState2 = _slicedToArray(_useState, 2),
      percentage = _useState2[0],
      setPercentage = _useState2[1];

  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(false),
      _useState4 = _slicedToArray(_useState3, 2),
      done = _useState4[0],
      setDone = _useState4[1];

  var QuizID = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('learnpress/quiz').getData('id');
  }, []);
  var results = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('learnpress/quiz').getData('results');
  }, []);
  var passingGrade = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('learnpress/quiz').getData('passingGrade');
  }, []);
  var submitting = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    return select('learnpress/quiz').getData('submitting');
  }, []);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(function () {
    animate();
    var graduation = '';

    if (results.graduation) {
      graduation = results.graduation;
    } else if (results.result >= passingGradeValue.replace(/[^0-9\.]+/g, '')) {
      graduation = 'passed';
    } else {
      graduation = 'failed';
    }

    if (graduation) {
      var ele = document.querySelector(".course-curriculum .course-item.course-item-".concat(QuizID));

      if (ele) {
        ele.classList.remove('failed', 'passed');
        ele.classList.add('has-status', 'status-completed', graduation);
      }
    }

    var item = _toConsumableArray(document.querySelectorAll('#popup-header .items-progress'))[0];

    if (item) {
      var itemCompleted = item.querySelector('.items-completed');

      if (itemCompleted) {
        var number = parseInt(itemCompleted.textContent);
        var allItemCompleted = document.querySelectorAll('#popup-sidebar .course-curriculum .course-item.status-completed');
        itemCompleted.textContent = parseInt(allItemCompleted.length);
      }
    }
  }, [results]);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(function () {
    if (submitting !== undefined) {
      updateItemsProgress();
    }
  }, [submitting]);

  var updateItemsProgress = function updateItemsProgress() {
    var elements = document.querySelectorAll('.popup-header__inner');

    if (elements.length > 0 && elements[0].querySelectorAll('form.form-button-finish-course').length === 0) {
      Object(_single_curriculum_components_items_progress__WEBPACK_IMPORTED_MODULE_3__["getResponse"])(elements[0]);
    }
  };

  var animate = function animate() {
    setPercentage(0);
    setDone(false);

    jQuery.easing._customEasing = function (e, f, a, h, g) {
      return h * Math.sqrt(1 - (f = f / g - 1) * f) + a;
    };

    debounce(function () {
      var $el = jQuery('<span />').css({
        width: 1,
        height: 1
      }).appendTo(document.body);
      $el.css('left', 0).animate({
        left: results.result
      }, {
        duration: 1500,
        step: function step(now, fx) {
          setPercentage(now);
        },
        done: function done() {
          setDone(true);
          $el.remove();
          jQuery('#quizResultGrade').css({
            transform: 'scale(1.3)',
            transition: 'all 0.25s'
          });
          debounce(function () {
            jQuery('#quizResultGrade').css({
              transform: 'scale(1)'
            });
          }, 500)();
        },
        easing: '_customEasing'
      });
    }, results.result > 0 ? 1000 : 10)();
  };
  /**
   * Render HTML elements.
   *
   */


  var percentResult = percentage;

  if (!Number.isInteger(percentage)) {
    percentResult = parseFloat(percentage).toFixed(2);
  }

  var border = 10;
  var width = 200;
  var radius = width / 2;
  var r = (width - border) / 2;
  var circumference = r * 2 * Math.PI;
  var offset = circumference - percentResult / 100 * circumference;
  var styles = {
    strokeDasharray: "".concat(circumference, " ").concat(circumference),
    strokeDashoffset: offset
  };
  var passingGradeValue = results.passingGrade || passingGrade;
  var graduation = '';

  if (results.graduation) {
    graduation = results.graduation;
  } else if (percentResult >= passingGradeValue.replace(/[^0-9\.]+/g, '')) {
    graduation = 'passed';
  } else {
    graduation = 'failed';
  }

  var message = '';

  if (results.graduationText) {
    message = results.graduationText;
  } else if (graduation === 'passed') {
    message = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Passed', 'learnpress');
  } else {
    message = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Failed', 'learnpress');
  }

  var classNames = ['quiz-result', graduation]; //Re-write value to results.timeSpend

  if (lpQuizSettings.checkNorequizenroll == '1') {
    var timespendStart = window.localStorage.getItem('quiz_start_' + QuizID),
        timespendEnd = window.localStorage.getItem('quiz_end_' + QuizID);

    if (timespendStart && timespendEnd) {
      results.timeSpend = timeDifference(timespendStart, timespendEnd).duration;
    }
  }

  return /*#__PURE__*/React.createElement("div", {
    className: classNames.join(' ')
  }, /*#__PURE__*/React.createElement("h3", {
    className: "result-heading"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Your Result', 'learnpress')), /*#__PURE__*/React.createElement("div", {
    id: "quizResultGrade",
    className: "result-grade"
  }, /*#__PURE__*/React.createElement("svg", {
    className: "circle-progress-bar",
    width: width,
    height: width
  }, /*#__PURE__*/React.createElement("circle", {
    className: "circle-progress-bar__circle",
    stroke: "",
    strokeWidth: border,
    style: styles,
    fill: "transparent",
    r: r,
    cx: radius,
    cy: radius
  })), /*#__PURE__*/React.createElement("span", {
    className: "result-achieved"
  }, "".concat(percentResult, "%")), /*#__PURE__*/React.createElement("span", {
    className: "result-require"
  }, passingGradeValue || '-')), done && /*#__PURE__*/React.createElement("p", {
    className: "result-message"
  }, message), /*#__PURE__*/React.createElement("ul", {
    className: "result-statistic"
  }, /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-time-spend"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Time spend', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.timeSpend)), /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-point"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Point', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.userMark, " / ", results.mark)), /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-questions"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Questions', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.questionCount)), /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-questions-correct"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Correct', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.questionCorrect)), /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-questions-wrong"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Wrong', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.questionWrong)), /*#__PURE__*/React.createElement("li", {
    className: "result-statistic-field result-questions-skipped"
  }, /*#__PURE__*/React.createElement("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Skipped', 'learnpress')), /*#__PURE__*/React.createElement("p", null, results.questionEmpty))));

  function timeDifference(earlierDate, laterDate) {
    var oDiff = new Object(); //  Calculate Differences
    //  -------------------------------------------------------------------  //

    var nTotalDiff = laterDate - earlierDate;
    oDiff.days = Math.floor(nTotalDiff / 1000 / 60 / 60 / 24);
    nTotalDiff -= oDiff.days * 1000 * 60 * 60 * 24;
    oDiff.hours = Math.floor(nTotalDiff / 1000 / 60 / 60);
    nTotalDiff -= oDiff.hours * 1000 * 60 * 60;
    oDiff.minutes = Math.floor(nTotalDiff / 1000 / 60);
    nTotalDiff -= oDiff.minutes * 1000 * 60;
    oDiff.seconds = Math.floor(nTotalDiff / 1000); //  -------------------------------------------------------------------  //
    //  Format Duration
    //  -------------------------------------------------------------------  //
    //  Format Hours

    var hourtext = '00';

    if (oDiff.days > 0) {
      hourtext = String(oDiff.days);
    }

    if (hourtext.length == 1) {
      hourtext = '0' + hourtext;
    } //  Format Minutes


    var mintext = '00';

    if (oDiff.minutes > 0) {
      mintext = String(oDiff.minutes);
    }

    if (mintext.length == 1) {
      mintext = '0' + mintext;
    } //  Format Seconds


    var sectext = '00';

    if (oDiff.seconds > 0) {
      sectext = String(oDiff.seconds);
    }

    if (sectext.length == 1) {
      sectext = '0' + sectext;
    } //  Set Duration


    var sDuration = hourtext + ':' + mintext + ':' + sectext;
    oDiff.duration = sDuration; //  -------------------------------------------------------------------  //

    return oDiff;
  }
};

/* harmony default export */ __webpack_exports__["default"] = (Result);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/status/index.js":
/*!******************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/status/index.js ***!
  \******************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _timer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../timer */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/timer/index.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);




var $ = jQuery;
var _lodash = lodash,
    debounce = _lodash.debounce;

var Status = function Status() {
  var _dispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])('learnpress/quiz'),
      submitQuiz = _dispatch.submitQuiz;

  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(function () {
    var $pc = $('#popup-content');

    if (!$pc.length) {
      return;
    }

    var $sc = $pc.find('.content-item-scrollable:eq(1)');
    var $ciw = $pc.find('.content-item-wrap');
    var $qs = $pc.find('.quiz-status');
    var pcTop = $qs.offset().top - 92;
    var isFixed = false;
    var marginLeft = '-' + $ciw.css('margin-left');
    $(window).on('resize.refresh-quiz-stauts-bar', debounce(function () {
      marginLeft = '-' + $ciw.css('margin-left');
      $qs.css({
        'margin-left': marginLeft,
        'margin-right': marginLeft
      });
    }, 100)).trigger('resize.refresh-quiz-stauts-bar');
    /**
     * Check when status bar is stopped in the top
     * to add new class into html
     */

    $sc.on('scroll', function () {
      if ($sc.scrollTop() >= pcTop) {
        if (isFixed) {
          return;
        }

        isFixed = true;
      } else {
        if (!isFixed) {
          return;
        }

        isFixed = false;
      }

      if (isFixed) {
        $pc.addClass('fixed-quiz-status');
      } else {
        $pc.removeClass('fixed-quiz-status');
      }
    });
  }, []);
  /**
   * Submit question to record results.
   */

  var submit = function submit() {
    var _select = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/modal'),
        confirm = _select.confirm;

    if ('no' === confirm(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Are you sure to submit quiz?', 'learnpress'), submit)) {
      return;
    }

    submitQuiz();
  };

  var getMark = function getMark() {
    var answered = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz').getData('answered');
    return Object.values(answered).reduce(function (m, r) {
      return m + r.mark;
    }, 0);
  };

  var _select2 = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz'),
      getData = _select2.getData,
      getUserMark = _select2.getUserMark;

  var currentPage = getData('currentPage');
  var questionsPerPage = getData('questionsPerPage');
  var questionsCount = getData('questionIds').length;
  var submitting = getData('submitting');
  var totalTime = getData('totalTime');
  var duration = getData('duration');
  var userMark = getUserMark();
  var classNames = ['quiz-status'];
  var start = (currentPage - 1) * questionsPerPage + 1;
  var end = start + questionsPerPage - 1;
  var indexHtml = '';
  end = Math.min(end, questionsCount);

  if (submitting) {
    classNames.push('submitting');
  }

  if (end < questionsCount) {
    if (questionsPerPage > 1) {
      indexHtml = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Question <span>%d to %d of %d</span>', 'learnpress'), start, end, questionsCount);
    } else {
      indexHtml = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Question <span>%d of %d</span>', 'learnpress'), start, questionsCount);
    }
  } else {
    indexHtml = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Question <span>%d to %d</span>', 'learnpress'), start, end);
  }

  return /*#__PURE__*/React.createElement("div", {
    className: classNames.join(' ')
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    className: "questions-index",
    dangerouslySetInnerHTML: {
      __html: indexHtml
    }
  }), /*#__PURE__*/React.createElement("div", {
    className: "current-point"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["sprintf"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Earned Point: %s', 'learnpress'), userMark)), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    className: "submit-quiz"
  }, /*#__PURE__*/React.createElement("button", {
    className: "lp-button",
    id: "button-submit-quiz",
    onClick: submit
  }, !submitting ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Finish Quiz', 'learnpress') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__["__"])('Submitting...', 'learnpress'))), totalTime && duration && /*#__PURE__*/React.createElement(_timer__WEBPACK_IMPORTED_MODULE_2__["default"], null))));
};

/* harmony default export */ __webpack_exports__["default"] = (Status);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/timer/index.js":
/*!*****************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/timer/index.js ***!
  \*****************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * Edit: React hook.
 *
 * @author Nhamdv - ThimPress
 */



var Timer = function Timer() {
  var _select = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz'),
      getData = _select.getData;

  var _dispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])('learnpress/quiz'),
      submitQuiz = _dispatch.submitQuiz;

  var totalTime = getData('totalTime') ? getData('totalTime') : getData('duration');
  var endTime = getData('endTime');
  var d1 = new Date(endTime.replace(/-/g, '/'));
  var d2 = new Date();
  var tz = new Date().getTimezoneOffset();
  var t = parseInt(d1.getTime() / 1000 - (d2.getTime() / 1000 + tz * 60));

  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(t > 0 ? t : 0),
      _useState2 = _slicedToArray(_useState, 2),
      seconds = _useState2[0],
      setSeconds = _useState2[1];

  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(function () {
    var myInterval = setInterval(function () {
      var remainSeconds = seconds;
      remainSeconds -= 1;

      if (remainSeconds > 0) {
        setSeconds(remainSeconds);
      } else {
        clearInterval(myInterval);
        submitQuiz();
      }
    }, 1000);
    return function () {
      return clearInterval(myInterval);
    };
  }, [seconds]);

  var formatTime = function formatTime() {
    var separator = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ':';
    var t = [];
    var m;

    if (totalTime < 3600) {
      t.push((seconds - seconds % 60) / 60);
      t.push(seconds % 60);
    } else if (totalTime) {
      t.push((seconds - seconds % 3600) / 3600);
      m = seconds % 3600;
      t.push((m - m % 60) / 60);
      t.push(m % 60);
    }

    return t.map(function (a) {
      return a < 10 ? "0".concat(a) : a;
    }).join(separator);
  };

  return /*#__PURE__*/React.createElement("div", {
    className: "countdown"
  }, /*#__PURE__*/React.createElement("i", {
    className: "fas fa-stopwatch"
  }), /*#__PURE__*/React.createElement("span", null, formatTime()));
};

/* harmony default export */ __webpack_exports__["default"] = (Timer);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/title/index.js":
/*!*****************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/title/index.js ***!
  \*****************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var Title = function Title() {
  return /*#__PURE__*/React.createElement("h3", null, "The title");
};

/* harmony default export */ __webpack_exports__["default"] = (Title);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/index.js":
/*!************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/index.js ***!
  \************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/components/index.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./store */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/index.js");
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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }






var _lodash = lodash,
    chunk = _lodash.chunk;

var Quiz = /*#__PURE__*/function (_Component) {
  _inherits(Quiz, _Component);

  var _super = _createSuper(Quiz);

  function Quiz(props) {
    var _this;

    _classCallCheck(this, Quiz);

    _this = _super.apply(this, arguments);

    _defineProperty(_assertThisInitialized(_this), "startQuiz", function (event) {
      _this.props.startQuiz();
    });

    _this.state = {
      currentPage: 1,
      numPages: 0,
      pages: []
    };
    return _this;
  }

  _createClass(Quiz, [{
    key: "componentDidMount",
    value: function componentDidMount() {
      var _this$props = this.props,
          settings = _this$props.settings,
          setQuizData = _this$props.setQuizData;
      var question_ids = settings.question_ids,
          questions_per_page = settings.questions_per_page;
      var chunks = chunk(question_ids, questions_per_page);
      settings.currentPage = 1;
      settings.numPages = chunks.length;
      settings.pages = chunks;
      var answered = settings.id ? localStorage.getItem("LP_Quiz_".concat(settings.id, "_Answered")) : false;

      if (answered) {
        settings.answered = JSON.parse(answered);
      }

      setQuizData(settings);
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props2 = this.props,
          status = _this$props2.status,
          isReviewing = _this$props2.isReviewing;
      var isA = -1 !== ['', 'completed', 'viewed'].indexOf(status) || !status;
      var notStarted = -1 !== ['', 'viewed', undefined].indexOf(status) || !status; // Just render content if status !== undefined (meant all data loaded)

      return undefined !== status && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", null, !isReviewing && 'completed' === status && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Result"], null), !isReviewing && notStarted && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Meta"], null), !isReviewing && notStarted && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Content"], null), 'started' === status && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Status"], null), (-1 !== ['completed', 'started'].indexOf(status) || isReviewing) && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Questions"], null), /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Buttons"], null), isA && !isReviewing && /*#__PURE__*/React.createElement(_components__WEBPACK_IMPORTED_MODULE_3__["Attempts"], null)));
    }
  }]);

  return Quiz;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withSelect"])(function (select) {
  var _select = select('learnpress/quiz'),
      getQuestions = _select.getQuestions,
      getData = _select.getData;

  return {
    questions: getQuestions(),
    status: getData('status'),
    store: getData(),
    answered: getData('answered'),
    isReviewing: getData('mode') === 'reviewing',
    questionIds: getData('questionIds'),
    checkCount: getData('instantCheck'),
    questionsPerPage: getData('questionsPerPage') || 1
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["withDispatch"])(function (dispatch) {
  var _dispatch = dispatch('learnpress/quiz'),
      setQuizData = _dispatch.setQuizData,
      startQuiz = _dispatch.startQuiz;

  return {
    setQuizData: setQuizData,
    startQuiz: startQuiz
  };
})])(Quiz));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/actions.js":
/*!********************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/actions.js ***!
  \********************************************************************************************************************************************/
/*! exports provided: setQuizData, setCurrentQuestion, setCurrentPage, __requestBeforeStartQuiz, __requestStartQuizSuccess, startQuiz, __requestSubmitQuiz, __requestSubmitQuizSuccess, submitQuiz, updateUserQuestionAnswers, __requestShowHintSuccess, showHint, __requestCheckAnswerSuccess, checkAnswer, markQuestionRendered, setQuizMode, sendKey */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setQuizData", function() { return setQuizData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setCurrentQuestion", function() { return setCurrentQuestion; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setCurrentPage", function() { return setCurrentPage; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestBeforeStartQuiz", function() { return __requestBeforeStartQuiz; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestStartQuizSuccess", function() { return __requestStartQuizSuccess; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "startQuiz", function() { return startQuiz; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestSubmitQuiz", function() { return __requestSubmitQuiz; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestSubmitQuizSuccess", function() { return __requestSubmitQuizSuccess; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "submitQuiz", function() { return submitQuiz; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateUserQuestionAnswers", function() { return updateUserQuestionAnswers; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestShowHintSuccess", function() { return __requestShowHintSuccess; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "showHint", function() { return showHint; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "__requestCheckAnswerSuccess", function() { return __requestCheckAnswerSuccess; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "checkAnswer", function() { return checkAnswer; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "markQuestionRendered", function() { return markQuestionRendered; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setQuizMode", function() { return setQuizMode; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendKey", function() { return sendKey; });
/* harmony import */ var _learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @learnpress/data-controls */ "@learnpress/data-controls");
/* harmony import */ var _learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

var _marked = /*#__PURE__*/regeneratorRuntime.mark(submitQuiz),
    _marked2 = /*#__PURE__*/regeneratorRuntime.mark(showHint),
    _marked3 = /*#__PURE__*/regeneratorRuntime.mark(checkAnswer);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }





function _dispatch() {
  var args = [].slice.call(arguments, 2);
  var d = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])(arguments[0]);
  var f = arguments[1];
  d[f].apply(d, _toConsumableArray(args));
}

var _LP = LP,
    camelCaseDashObjectKeys = _LP.camelCaseDashObjectKeys,
    Hook = _LP.Hook;
/**
 * Set user data for app.
 *
 * @param key
 * @param data
 * @return {{type: string, data: *}}
 */

function setQuizData(key, data) {
  if (typeof key === 'string') {
    data = _defineProperty({}, key, data);
  } else {
    data = key;
  } // Save all data for no required enroll available


  if (lpQuizSettings.checkNorequizenroll == '1' && window.localStorage.getItem('quiz_userdata_' + lpQuizSettings.id) !== null) {
    data = JSON.parse(window.localStorage.getItem('quiz_userdata_' + lpQuizSettings.id));
  }

  return {
    type: 'SET_QUIZ_DATA',
    data: camelCaseDashObjectKeys(data)
  };
}
/**
 * Set question will display.
 *
 * @param questionId
 * @return {{type: string, data: *}}
 */

function setCurrentQuestion(questionId) {
  return {
    type: 'SET_CURRENT_QUESTION',
    questionId: questionId
  };
}
function setCurrentPage(currentPage) {
  return {
    type: 'SET_CURRENT_PAGE',
    currentPage: currentPage
  };
}
function __requestBeforeStartQuiz(quizId, courseId, userId) {
  return {
    type: 'BEFORE_START_QUIZ'
  };
}
function __requestStartQuizSuccess(results, quizId, courseId, userId) {
  Hook.doAction('quiz-started', results, quizId, courseId, userId);
  return {
    type: 'START_QUIZ_SUCCESS',
    quizId: quizId,
    courseId: courseId,
    userId: userId,
    results: results
  };
}
/**
 * Request to api for starting a quiz.
 */

var startQuiz = /*#__PURE__*/regeneratorRuntime.mark(function startQuiz() {
  var _wpSelect$getDefaultR, itemId, courseId, doStart, response;

  return regeneratorRuntime.wrap(function startQuiz$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _wpSelect$getDefaultR = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz').getDefaultRestArgs(), itemId = _wpSelect$getDefaultR.itemId, courseId = _wpSelect$getDefaultR.courseId;
          doStart = Hook.applyFilters('before-start-quiz', true, itemId, courseId);

          if (!(true !== doStart)) {
            _context.next = 4;
            break;
          }

          return _context.abrupt("return");

        case 4:
          _context.next = 6;
          return Object(_learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["apiFetch"])({
            path: 'lp/v1/users/start-quiz',
            method: 'POST',
            data: {
              item_id: itemId,
              course_id: courseId
            }
          });

        case 6:
          response = _context.sent;
          response = Hook.applyFilters('request-start-quiz-response', response, itemId, courseId);
          _context.next = 10;
          return _dispatch('learnpress/quiz', '__requestStartQuizSuccess', camelCaseDashObjectKeys(response), itemId, courseId);

        case 10:
        case "end":
          return _context.stop();
      }
    }
  }, startQuiz);
});

function __requestSubmitQuiz() {
  return {
    type: 'SUBMIT_QUIZ'
  };
}
function __requestSubmitQuizSuccess(results, quizId, courseId) {
  Hook.doAction('quiz-submitted', results, quizId, courseId);
  return {
    type: 'SUBMIT_QUIZ_SUCCESS',
    results: results
  };
}
function submitQuiz() {
  var _wpSelect, getDefaultRestArgs, getQuestionsSelectedAnswers, _getDefaultRestArgs, itemId, courseId, doSubmit, answered, response;

  return regeneratorRuntime.wrap(function submitQuiz$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          _wpSelect = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz'), getDefaultRestArgs = _wpSelect.getDefaultRestArgs, getQuestionsSelectedAnswers = _wpSelect.getQuestionsSelectedAnswers;
          _getDefaultRestArgs = getDefaultRestArgs(), itemId = _getDefaultRestArgs.itemId, courseId = _getDefaultRestArgs.courseId;
          doSubmit = Hook.applyFilters('before-submit-quiz', true);

          if (!(true !== doSubmit)) {
            _context2.next = 5;
            break;
          }

          return _context2.abrupt("return");

        case 5:
          answered = getQuestionsSelectedAnswers();
          _context2.next = 8;
          return Object(_learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["apiFetch"])({
            path: 'lp/v1/users/submit-quiz',
            method: 'POST',
            data: {
              item_id: itemId,
              course_id: courseId,
              answered: answered
            }
          });

        case 8:
          response = _context2.sent;

          if (lpQuizSettings.checkNorequizenroll == '1') {
            // Remove & set storage end_time
            window.localStorage.removeItem('quiz_end_' + lpQuizSettings.id);
            window.localStorage.setItem('quiz_end_' + lpQuizSettings.id, Date.now());
          }

          response = Hook.applyFilters('request-submit-quiz-response', response, itemId, courseId);

          if (!response.success) {
            _context2.next = 14;
            break;
          }

          _context2.next = 14;
          return _dispatch('learnpress/quiz', '__requestSubmitQuizSuccess', camelCaseDashObjectKeys(response.results), itemId, courseId);

        case 14:
          if (lpQuizSettings.checkNorequizenroll == '1') {
            localStorage.setItem('quiz_userdata_' + lpQuizSettings.id, JSON.stringify(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz').getData()));
          }

        case 15:
        case "end":
          return _context2.stop();
      }
    }
  }, _marked);
}
function updateUserQuestionAnswers(questionId, answers, quizId) {
  var courseId = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
  var userId = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 0;
  return {
    type: 'UPDATE_USER_QUESTION_ANSWERS',
    questionId: questionId,
    answers: answers
  };
}
function __requestShowHintSuccess(id, showHint) {
  return {
    type: 'SET_QUESTION_HINT',
    questionId: id,
    showHint: showHint
  };
}
function showHint(id, showHint) {
  return regeneratorRuntime.wrap(function showHint$(_context3) {
    while (1) {
      switch (_context3.prev = _context3.next) {
        case 0:
          _context3.next = 2;
          return _dispatch('learnpress/quiz', '__requestShowHintSuccess', id, showHint);

        case 2:
        case "end":
          return _context3.stop();
      }
    }
  }, _marked2);
}
function __requestCheckAnswerSuccess(id, result) {
  return _objectSpread({
    type: 'CHECK_ANSWER',
    questionId: id
  }, result);
}
function checkAnswer(id) {
  var _wpSelect2, getDefaultRestArgs, getQuestionAnswered, _getDefaultRestArgs2, itemId, courseId, result;

  return regeneratorRuntime.wrap(function checkAnswer$(_context4) {
    while (1) {
      switch (_context4.prev = _context4.next) {
        case 0:
          _wpSelect2 = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["select"])('learnpress/quiz'), getDefaultRestArgs = _wpSelect2.getDefaultRestArgs, getQuestionAnswered = _wpSelect2.getQuestionAnswered;
          _getDefaultRestArgs2 = getDefaultRestArgs(), itemId = _getDefaultRestArgs2.itemId, courseId = _getDefaultRestArgs2.courseId;
          _context4.next = 4;
          return Object(_learnpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["apiFetch"])({
            path: 'lp/v1/users/check-answer',
            method: 'POST',
            data: {
              item_id: itemId,
              course_id: courseId,
              question_id: id,
              answered: getQuestionAnswered(id) || ''
            }
          });

        case 4:
          result = _context4.sent;
          _context4.next = 7;
          return _dispatch('learnpress/quiz', '__requestCheckAnswerSuccess', id, camelCaseDashObjectKeys(result));

        case 7:
        case "end":
          return _context4.stop();
      }
    }
  }, _marked3);
}
function markQuestionRendered(questionId) {
  return {
    type: 'MARK_QUESTION_RENDERED',
    questionId: questionId
  };
}
function setQuizMode(mode) {
  return {
    type: 'SET_QUIZ_MODE',
    mode: mode
  };
}
function sendKey(keyPressed) {
  setTimeout(function () {
    _dispatch('learnpress/quiz', 'sendKey', '');
  }, 300);
  return {
    type: 'SEND_KEY',
    keyPressed: keyPressed
  };
}

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/index.js":
/*!******************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/index.js ***!
  \******************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reducer */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/reducer.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./actions */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/actions.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/selectors.js");
/* harmony import */ var _middlewares__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./middlewares */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/middlewares.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }






var dataControls = LP.dataControls.controls;
var store = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["registerStore"])('learnpress/quiz', {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_1__["default"],
  selectors: _selectors__WEBPACK_IMPORTED_MODULE_3__,
  actions: _actions__WEBPACK_IMPORTED_MODULE_2__,
  controls: _objectSpread({}, dataControls)
});
Object(_middlewares__WEBPACK_IMPORTED_MODULE_4__["default"])(store);
/* harmony default export */ __webpack_exports__["default"] = (store);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/middlewares.js":
/*!************************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/middlewares.js ***!
  \************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var refx__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! refx */ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/refx/refx.js");
/* harmony import */ var refx__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(refx__WEBPACK_IMPORTED_MODULE_0__);
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */
//import effects from './effects';

var effects = {
  ENROLL_COURSE_X: function ENROLL_COURSE_X(action, store) {
    enrollCourse: (function (action, store) {
      var dispatch = store.dispatch; //dispatch()
    });
  }
};
/**
 * Applies the custom middlewares used specifically in the editor module.
 *
 * @param {Object} store Store Object.
 *
 * @return {Object} Update Store Object.
 */

function applyMiddlewares(store) {
  var enhancedDispatch = function enhancedDispatch() {
    throw new Error('Dispatching while constructing your middleware is not allowed. ' + 'Other middleware would not be applied to this dispatch.');
  };

  var middlewareAPI = {
    getState: store.getState,
    dispatch: function dispatch() {
      return enhancedDispatch.apply(void 0, arguments);
    }
  };
  enhancedDispatch = refx__WEBPACK_IMPORTED_MODULE_0___default()(effects)(middlewareAPI)(store.dispatch);
  store.dispatch = enhancedDispatch;
  return store;
}

/* harmony default export */ __webpack_exports__["default"] = (applyMiddlewares);

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/reducer.js":
/*!********************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/reducer.js ***!
  \********************************************************************************************************************************************/
/*! exports provided: setItemStatus, userQuiz, blocks, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setItemStatus", function() { return setItemStatus; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "userQuiz", function() { return userQuiz; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "blocks", function() { return blocks; });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


var _lodash = lodash,
    omit = _lodash.omit,
    flow = _lodash.flow,
    isArray = _lodash.isArray,
    chunk = _lodash.chunk;
var _LP = LP,
    camelCaseDashObjectKeys = _LP.camelCaseDashObjectKeys;
var _LP$localStorage = LP.localStorage,
    storageGet = _LP$localStorage.get,
    storageSet = _LP$localStorage.set;
var STORE_DATA = {};
var setItemStatus = function setItemStatus(item, status) {
  var userSettings = _objectSpread(_objectSpread({}, item.userSettings), {}, {
    status: status
  });

  return _objectSpread(_objectSpread({}, item), {}, {
    userSettings: userSettings
  });
};

var updateUserQuestionAnswer = function updateUserQuestionAnswer(state, action) {
  var answered = state.answered,
      id = state.id;

  var newAnswer = _objectSpread(_objectSpread({}, answered[action.questionId] || {}), {}, {
    answered: action.answers,
    temp: true
  });

  if (id) {
    localStorage.setItem("LP_Quiz_".concat(id, "_Answered"), JSON.stringify(_objectSpread(_objectSpread({}, state.answered), {}, _defineProperty({}, action.questionId, newAnswer))));
  }

  return _objectSpread(_objectSpread({}, state), {}, {
    answered: _objectSpread(_objectSpread({}, state.answered), {}, _defineProperty({}, action.questionId, newAnswer))
  });
};

var markQuestionRendered = function markQuestionRendered(state, action) {
  var questionsRendered = state.questionsRendered;

  if (isArray(questionsRendered)) {
    questionsRendered.push(action.questionId);
    return _objectSpread(_objectSpread({}, state), {}, {
      questionsRendered: _toConsumableArray(questionsRendered)
    });
  }

  return _objectSpread(_objectSpread({}, state), {}, {
    questionsRendered: [action.questionId]
  });
};

var resetCurrentPage = function resetCurrentPage(state, args) {
  if (args.currentPage) {
    storageSet("Q".concat(state.id, ".currentPage"), args.currentPage);
  }

  return _objectSpread(_objectSpread({}, state), args);
};

var setQuestionHint = function setQuestionHint(state, action) {
  var questions = state.questions.map(function (question) {
    return question.id == action.questionId ? _objectSpread(_objectSpread({}, question), {}, {
      showHint: action.showHint
    }) : question;
  });
  return _objectSpread(_objectSpread({}, state), {}, {
    questions: _toConsumableArray(questions)
  });
};

var checkAnswer = function checkAnswer(state, action) {
  var questions = state.questions.map(function (question) {
    if (question.id !== action.questionId) {
      return question;
    }

    var newArgs = {
      explanation: action.explanation
    };

    if (action.options) {
      newArgs.options = action.options;
    }

    return _objectSpread(_objectSpread({}, question), newArgs);
  });
  return _objectSpread(_objectSpread({}, state), {}, {
    questions: _toConsumableArray(questions),
    answered: _objectSpread(_objectSpread({}, state.answered), {}, _defineProperty({}, action.questionId, action.result)),
    checkedQuestions: [].concat(_toConsumableArray(state.checkedQuestions), [action.questionId])
  });
};

var submitQuiz = function submitQuiz(state, action) {
  localStorage.removeItem("LP_Quiz_".concat(state.id, "_Answered"));
  var questions = state.questions.map(function (question) {
    var newArgs = {};

    if (state.reviewQuestions) {
      if (action.results.questions[question.id].explanation) {
        newArgs.explanation = action.results.questions[question.id].explanation;
      }

      if (action.results.questions[question.id].options) {
        newArgs.options = action.results.questions[question.id].options;
      }
    }

    return _objectSpread(_objectSpread({}, question), newArgs);
  });
  return resetCurrentPage(state, _objectSpread(_objectSpread({
    submitting: false,
    currentPage: 1
  }, action.results), {}, {
    questions: _toConsumableArray(questions)
  }));
};

var startQuizz = function startQuizz(state, action) {
  var successResponse = action.results.success !== undefined ? action.results.success : false;
  var messageResponse = action.results.message || false;
  return resetCurrentPage(state, _objectSpread(_objectSpread({
    checkedQuestions: [],
    hintedQuestions: [],
    mode: '',
    currentPage: 1
  }, action.results.results), {}, {
    successResponse: successResponse,
    messageResponse: messageResponse
  }));
};

var userQuiz = function userQuiz() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : STORE_DATA;
  var action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_QUIZ_DATA':
      if (1 > action.data.questionsPerPage) {
        action.data.questionsPerPage = 1;
      }

      var chunks = chunk(state.questionIds || action.data.questionIds, action.data.questionsPerPage);
      action.data.numPages = chunks.length;
      action.data.pages = chunks;
      return _objectSpread(_objectSpread(_objectSpread({}, state), action.data), {}, {
        currentPage: storageGet("Q".concat(action.data.id, ".currentPage")) || action.data.currentPage
      });

    case 'SUBMIT_QUIZ':
      return _objectSpread(_objectSpread({}, state), {}, {
        submitting: true
      });

    case 'START_QUIZ':
    case 'START_QUIZ_SUCCESS':
      return startQuizz(state, action);

    case 'SET_CURRENT_QUESTION':
      storageSet("Q".concat(state.id, ".currentQuestion"), action.questionId);
      return _objectSpread(_objectSpread({}, state), {}, {
        currentQuestion: action.questionId
      });

    case 'SET_CURRENT_PAGE':
      storageSet("Q".concat(state.id, ".currentPage"), action.currentPage);
      return _objectSpread(_objectSpread({}, state), {}, {
        currentPage: action.currentPage
      });

    case 'SUBMIT_QUIZ_SUCCESS':
      return submitQuiz(state, action);

    case 'UPDATE_USER_QUESTION_ANSWERS':
      return state.status === 'started' ? updateUserQuestionAnswer(state, action) : state;

    case 'MARK_QUESTION_RENDERED':
      return markQuestionRendered(state, action);

    case 'SET_QUIZ_MODE':
      if (action.mode == 'reviewing') {
        return resetCurrentPage(state, {
          mode: action.mode
        });
      }

      return _objectSpread(_objectSpread({}, state), {}, {
        mode: action.mode
      });

    case 'SET_QUESTION_HINT':
      return setQuestionHint(state, action);

    case 'CHECK_ANSWER':
      return checkAnswer(state, action);

    case 'SEND_KEY':
      return _objectSpread(_objectSpread({}, state), {}, {
        keyPressed: action.keyPressed
      });
  }

  return state;
};
var blocks = flow(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["combineReducers"], function (reducer) {
  return function (state, action) {
    return reducer(state, action);
  };
}, function (reducer) {
  return function (state, action) {
    return reducer(state, action);
  };
}, function (reducer) {
  return function (state, action) {
    return reducer(state, action);
  };
})({
  a: function a() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
      a: 1
    };
    var action = arguments.length > 1 ? arguments[1] : undefined;
    return state;
  },
  b: function b() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
      b: 2
    };
    var action = arguments.length > 1 ? arguments[1] : undefined;
    return state;
  }
});
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["combineReducers"])({
  blocks: blocks,
  userQuiz: userQuiz
}));

/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/selectors.js":
/*!**********************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/frontend/quiz/store/selectors.js ***!
  \**********************************************************************************************************************************************/
/*! exports provided: getQuestionOptions, getItemStatus, getProp, getQuizAttempts, getQuizAnswered, getQuestions, getData, getDefaultRestArgs, getQuestionAnswered, getQuestionMark, getCurrentQuestion, getQuestion, isCheckedAnswer, isCorrect, getQuestionsSelectedAnswers, getUserMark */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestionOptions", function() { return getQuestionOptions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getItemStatus", function() { return getItemStatus; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getProp", function() { return getProp; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuizAttempts", function() { return getQuizAttempts; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuizAnswered", function() { return getQuizAnswered; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestions", function() { return getQuestions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getData", function() { return getData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getDefaultRestArgs", function() { return getDefaultRestArgs; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestionAnswered", function() { return getQuestionAnswered; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestionMark", function() { return getQuestionMark; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getCurrentQuestion", function() { return getCurrentQuestion; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestion", function() { return getQuestion; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isCheckedAnswer", function() { return isCheckedAnswer; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isCorrect", function() { return isCorrect; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getQuestionsSelectedAnswers", function() { return getQuestionsSelectedAnswers; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getUserMark", function() { return getUserMark; });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);

var _lodash = lodash,
    get = _lodash.get,
    isArray = _lodash.isArray;

var getQuestionOptions = function getQuestionOptions(state, id) {
  console.time('parseOptions');
  var question = getQuestion(state, id);
  var options = question.options;
  options = !isArray(options) ? JSON.parse(CryptoJS.AES.decrypt(options.data, options.key, {
    format: CryptoJSAesJson
  }).toString(CryptoJS.enc.Utf8)) : options;
  options = !isArray(options) ? JSON.parse(options) : options;
  console.timeEnd('parseOptions');
  return options;
};


/**
 * Get current status of an item in course.
 *
 * @param state
 * @param itemId
 */

function getItemStatus(state, itemId) {
  var item = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('course-learner/user').getItemById(itemId);
  return item ? get(item, 'userSettings.status') : '';
}
function getProp(state, prop, defaultValue) {
  return state[prop] || defaultValue;
}
/**
 * Get quiz attempted.
 *
 * @param state
 * @param itemId
 * @return {Array}
 */

function getQuizAttempts(state, itemId) {
  var item = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('course-learner/user').getItemById(itemId);
  return item ? get(item, 'userSettings.attempts') : [];
}
/**
 * Get answers for a quiz user has did.
 *
 * @param state
 * @param itemId
 * @return {{}}
 */

function getQuizAnswered(state, itemId) {
  var item = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('course-learner/user').getItemById(itemId);
  return item ? get(item, 'userSettings.answered', {}) : {};
}
/**
 * Get all questions in quiz.
 *
 * @param state
 * @return {*}
 */

function getQuestions(state) {
  var userQuiz = state.userQuiz;
  var questions = get(userQuiz, 'questions');
  return questions ? Object.values(questions) : [];
}
/**
 * Get property of store data.
 *
 * @param state - Store data
 * @param prop - Optional. NULL will return all data.
 * @return {*}
 */

function getData(state, prop) {
  var userQuiz = state.userQuiz;

  if (prop) {
    return get(userQuiz, prop);
  }

  return userQuiz;
}
function getDefaultRestArgs(state) {
  var userQuiz = state.userQuiz;
  return {
    itemId: userQuiz.id,
    courseId: userQuiz.courseId
  };
}
function getQuestionAnswered(state, id) {
  var userQuiz = state.userQuiz;
  return get(userQuiz, "answered.".concat(id, ".answered")) || undefined;
}
function getQuestionMark(state, id) {
  var userQuiz = state.userQuiz;
  return get(userQuiz, "answered.".concat(id, ".mark")) || undefined;
}
/**
 * Get current question is doing.
 *
 * @param {Object} state
 * @param {string} ret
 * @return {*}
 */

function getCurrentQuestion(state) {
  var ret = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  var questionsPerPage = get(state, 'userQuiz.questionsPerPage') || 1;

  if (questionsPerPage > 1) {
    return false;
  }

  var currentPage = get(state, 'userQuiz.currentPage') || 1;
  return ret === 'object' ? get(state, "userQuiz.questions[".concat(currentPage - 1, "]")) : get(state, "userQuiz.questionIds[".concat(currentPage - 1, "]"));
}
/**
 * Return a question contains fully data with title, content, ...
 *
 * @param state
 * @param theId
 */

var getQuestion = function getQuestion(state, theId) {
  var userQuiz = state.userQuiz;
  var s = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["select"])('learnpress/quiz');
  var questions = s.getQuestions();
  return questions.find(function (q) {
    return q.id == theId;
  });
};


/**
 * If user has used 'Instant check' for a question.
 *
 * @param {Object} state - Global state for app.
 * @param {number} id
 * @return {boolean}
 */

function isCheckedAnswer(state, id) {
  var checkedQuestions = get(state, 'userQuiz.checkedQuestions') || [];
  return checkedQuestions.indexOf(id) !== -1;
}
function isCorrect(state, id) {}
/**
 * Get questions user has selected answers.
 *
 * @param {Object} state. Global app state
 * @param state
 * @param {number} questionId
 * @return {{}}
 */

var getQuestionsSelectedAnswers = function getQuestionsSelectedAnswers(state, questionId) {
  var data = get(state, 'userQuiz.answered');
  var returnData = {};

  for (var loopId in data) {
    if (!data.hasOwnProperty(loopId)) {
      continue;
    }

    if (lpQuizSettings.checknorequizenroll == '1') {
      // If specific a question then return it only.
      if (questionId && loopId === questionId) {
        return data[loopId].answered;
      }

      returnData[loopId] = data[loopId].answered;
    } else {
      // Answer filled by user
      if (data[loopId].temp || data[loopId].blanks) {
        // If specific a question then return it only.
        if (questionId && loopId === questionId) {
          return data[loopId].answered;
        }

        returnData[loopId] = data[loopId].answered;
      }
    }
  }

  return returnData;
};


/**
 * Get mark user earned.
 * Just for questions user has used 'Instant check' button.
 *
 * @param state
 * @return {number}
 */

function getUserMark(state) {
  var userQuiz = state.userQuiz || {};
  var answered = userQuiz.answered,
      negativeMarking = userQuiz.negativeMarking,
      questions = userQuiz.questions,
      checkedQuestions = userQuiz.checkedQuestions;
  var totalMark = 0;

  var _loop = function _loop(_id) {
    if (!answered.hasOwnProperty(_id)) {
      id = _id;
      return "continue";
    }

    _id = parseInt(_id);
    var data = answered[_id];
    var questionMark = data.questionMark ? data.questionMark : function () {
      var question = questions.find(function (q) {
        id = _id;
        return q.id === _id;
      });
      id = _id;
      return question ? question.point : 0;
    }();
    var isChecked = checkedQuestions.indexOf(_id) !== -1;

    if (data.temp) {
      id = _id;
      return "continue";
    }

    if (negativeMarking) {
      if (data.answered) {
        totalMark = data.correct ? totalMark + data.mark : totalMark - questionMark;
      }
    } else if (data.answered && data.correct) {
      totalMark += data.mark;
    }

    id = _id;
  };

  for (var id in answered) {
    var _ret = _loop(id);

    if (_ret === "continue") continue;
  }

  return totalMark > 0 ? totalMark : 0;
}

/***/ }),

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

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/classnames/index.js":
/*!***************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/classnames/index.js ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/refx/refx.js":
/*!********************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/node_modules/refx/refx.js ***!
  \********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function flattenIntoMap( map, effects ) {
	var i;
	if ( Array.isArray( effects ) ) {
		for ( i = 0; i < effects.length; i++ ) {
			flattenIntoMap( map, effects[ i ] );
		}
	} else {
		for ( i in effects ) {
			map[ i ] = ( map[ i ] || [] ).concat( effects[ i ] );
		}
	}
}

function refx( effects ) {
	var map = {},
		middleware;

	flattenIntoMap( map, effects );

	middleware = function( store ) {
		return function( next ) {
			return function( action ) {
				var handlers = map[ action.type ],
					result = next( action ),
					i, handlerAction;

				if ( handlers ) {
					for ( i = 0; i < handlers.length; i++ ) {
						handlerAction = handlers[ i ]( action, store );
						if ( handlerAction ) {
							store.dispatch( handlerAction );
						}
					}
				}

				return result;
			};
		};
	};

	middleware.effects = map;

	return middleware;
}

module.exports = refx;


/***/ }),

/***/ "@learnpress/data-controls":
/*!***********************************************!*\
  !*** external {"this":["LP","dataControls"]} ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["LP"]["dataControls"]; }());

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["compose"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["i18n"]; }());

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
//# sourceMappingURL=quiz.js.map