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
/******/ 	return __webpack_require__(__webpack_require__.s = "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/admin/pages/sync-data.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../../../Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/admin/pages/sync-data.js":
/*!**************************************************************************************************************************************!*\
  !*** E:/Work/Webs/WP/Clouds/Thimpress/Plugins/github.com/learnpress_v4_doing/learnpress/assets/src/apps/js/admin/pages/sync-data.js ***!
  \**************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($) {
  var Sync_Base = {
    id: 'sync-base',
    syncing: false,
    items: false,
    completed: false,
    callback: null,
    methodGetItems: '',
    itemsKey: '',
    chunkSize: 50,
    sync: function sync(callback) {
      if (this.syncing) {
        return;
      }

      this.callback = callback;

      if (this.items === false) {
        this.get_items();
      } else if (!this.dispatch()) {
        this.completed = true;
        this.callToCallback();
        return;
      }

      this.syncing = true;
    },
    init: function init() {
      this.syncing = false;
      this.items = false;
      this.completed = false;
    },
    is_completed: function is_completed() {
      return this.completed;
    },
    dispatch: function dispatch() {
      var that = this,
          items = this.items ? this.items.splice(0, this.chunkSize) : false;

      if (!items || items.length === 0) {
        return false;
      }

      $.ajax({
        url: '',
        data: {
          'lp-ajax': this.id,
          sync: items
        },
        method: 'post',
        success: function success(response) {
          response = LP.parseJSON(response);
          that.syncing = false;

          if (response.result !== 'success') {
            that.completed = true;
          }

          that.callToCallback();

          if (that.is_completed()) {
            return;
          }

          that.sync(that.callback);
        }
      });
      return true;
    },
    callToCallback: function callToCallback() {
      this.callback && this.callback.call(this);
    },
    get_items: function get_items() {
      var that = this;
      $.ajax({
        url: '',
        data: {
          'lp-ajax': this.id,
          sync: this.methodGetItems
        },
        success: function success(response) {
          that.syncing = false;
          response = LP.parseJSON(response);

          if (response[that.itemsKey]) {
            that.items = response[that.itemsKey];
            that.sync(that.callback);
          } else {
            that.completed = true;
            that.items = [];
            that.callToCallback();
          }
        }
      });
    }
  };
  var Sync_Course_Orders = $.extend({}, Sync_Base, {
    id: 'sync-course-orders',
    methodGetItems: 'get-courses',
    itemsKey: 'courses'
  });
  var Sync_User_Courses = $.extend({}, Sync_Base, {
    id: 'sync-user-courses',
    methodGetItems: 'get-users',
    itemsKey: 'users',
    chunkSize: 500
  });
  var Sync_User_Orders = $.extend({}, Sync_Base, {
    id: 'sync-user-orders',
    methodGetItems: 'get-users',
    itemsKey: 'users',
    chunkSize: 500
  });
  var Sync_Course_Final_Quiz = $.extend({}, Sync_Base, {
    id: 'sync-course-final-quiz',
    methodGetItems: 'get-courses',
    itemsKey: 'courses',
    chunkSize: 500
  });
  var Sync_Remove_Older_Data = $.extend({}, Sync_Base, {
    id: 'sync-remove-older-data',
    methodGetItems: 'remove-older-data',
    itemsKey: '_nothing_here',
    chunkSize: 500
  });
  var Sync_Calculate_Course_Results = $.extend({}, Sync_Base, {
    id: 'sync-calculate-course-results',
    methodGetItems: 'get-users',
    itemsKey: 'users',
    chunkSize: 1
  });
  window.LP_Sync_Data = {
    syncs: [],
    syncing: 0,
    options: {},
    start: function start(options) {
      this.syncs = [];
      this.options = $.extend({
        onInit: function onInit() {},
        onStart: function onStart() {},
        onCompleted: function onCompleted() {},
        onCompletedAll: function onCompletedAll() {}
      }, options || {});

      if (!this.get_syncs()) {
        return;
      }

      this.reset();
      this.options.onInit.call(this);

      var that = this,
          syncing = 0,
          totalSyncs = this.syncs.length,
          syncCallback = function syncCallback($sync) {
        if ($sync.is_completed()) {
          syncing++;
          that.options.onCompleted.call(that, $sync);

          if (syncing >= totalSyncs) {
            that.options.onCompletedAll.call(that);
            return;
          }

          that.sync(syncing, syncCallback);
        }
      };

      this.sync(syncing, syncCallback);
    },
    reset: function reset() {
      for (var sync in this.syncs) {
        try {
          this[this.syncs[sync]].init();
        } catch (e) {}
      }
    },
    sync: function sync(_sync, callback) {
      var that = this,
          $sync = this[this.syncs[_sync]];
      that.options.onStart.call(that, $sync);
      $sync.sync(function () {
        callback.call(that, $sync);
      });
    },
    get_syncs: function get_syncs() {
      var syncs = $('input[name^="lp-repair"]:checked').serializeJSON()['lp-repair'];

      if (!syncs) {
        return false;
      }

      for (var sync in syncs) {
        if (syncs[sync] !== 'yes') {
          continue;
        }

        sync = sync.replace(/[-]+/g, '_');

        if (!this[sync]) {
          continue;
        }

        this.syncs.push(sync);
      }

      return this.syncs;
    },
    get_sync: function get_sync(id) {
      id = id.replace(/[-]+/g, '_');
      return this[id];
    },
    sync_course_orders: Sync_Course_Orders,
    sync_user_orders: Sync_User_Orders,
    sync_user_courses: Sync_User_Courses,
    sync_course_final_quiz: Sync_Course_Final_Quiz,
    sync_remove_older_data: Sync_Remove_Older_Data,
    sync_calculate_course_results: Sync_Calculate_Course_Results
  };
  $(document).ready(function () {
    function initSyncs() {
      var $chkAll = $('#learn-press-check-all-syncs'),
          $chks = $('#learn-press-syncs').find('[name^="lp-repair"]');
      $chkAll.on('click', function () {
        $chks.prop('checked', this.checked);
      });
      $chks.on('click', function () {
        $chkAll.prop('checked', $chks.filter(':checked').length === $chks.length);
      });
    }

    initSyncs();
  }).on('click', '.lp-button-repair', function () {
    function getInput(sync) {
      return $('ul#learn-press-syncs').find('input[name*="' + sync + '"]');
    }

    LP_Sync_Data.start({
      onInit: function onInit() {
        $('ul#learn-press-syncs').children().removeClass('syncing synced');
        $('.lp-button-repair').prop('disabled', true);
      },
      onStart: function onStart($sync) {
        getInput($sync.id).closest('li').addClass('syncing');
      },
      onCompleted: function onCompleted($sync) {
        getInput($sync.id).closest('li').removeClass('syncing').addClass('synced');
      },
      onCompletedAll: function onCompletedAll() {
        $('ul#learn-press-syncs').children().removeClass('syncing synced');
        $('.lp-button-repair').prop('disabled', false);
      }
    });
  });
})(jQuery);

/***/ })

/******/ });
//# sourceMappingURL=sync-data.js.map