(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _slidespecials_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../slidespecials.vue */ "./resources/js/components/clientside/slidespecials.vue");
/* harmony import */ var _slidefeatured_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../slidefeatured.vue */ "./resources/js/components/clientside/slidefeatured.vue");
/* harmony import */ var _productshome_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../productshome.vue */ "./resources/js/components/clientside/productshome.vue");
/* harmony import */ var _bannerhome_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../bannerhome.vue */ "./resources/js/components/clientside/bannerhome.vue");
/* harmony import */ var _abouthome_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../abouthome.vue */ "./resources/js/components/clientside/abouthome.vue");
/* harmony import */ var _pages_loaddots_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../pages/loaddots.vue */ "./resources/js/components/pages/loaddots.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//







/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    'slide-specials': _slidespecials_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    'slide-featured': _slidefeatured_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    'products-home': _productshome_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    'banner-one': _bannerhome_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    'banner-two': _bannerhome_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    'about-home': _abouthome_vue__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  data: function data() {
    return {
      slideSpecialsUse: false,
      slideFeaturedUse: false,
      loadProducts: false,
      bannerUse: false,
      loadAbout: false
    };
  },
  created: function created() {
    var _this = this;

    // once featuredLoad is done, update loader to true and show rest of home components.
    this.$root.$on('featuredLoad', function (value) {
      _this.loader = true;
    });
  },
  computed: {
    templateName: function templateName() {
      if (this.$store.state.templateView) {
        return this.$store.state.templateView;
      }
    }
  },
  mounted: function mounted() {
    this.getFeaturedUse();
    this.getSpecialsUse(); // delay the load of Products in home component
    // so featured slide show loads first

    var self = this;
    setTimeout(function () {
      self.loadProducts = true;
      self.getBannerUse();
      self.getAboutUse();
    }, 3000);
  },
  methods: {
    getAboutUse: function getAboutUse() {
      // for scope of this within axios
      var self = this;
      axios.post('/aboutuseclient').then(function (res) {
        if (res.data.using != undefined && res.data.using.length) {
          if (res.data.using[0].using == 0) {
            self.loadAbout = false;
          } else {
            self.loadAbout = true;
          }
        }
      }).catch(function (error) {
        // error handling function with error, name axios is used for, and this.
        errorHandle(error, 'About show load', self);
      });
    },
    getFeaturedUse: function getFeaturedUse() {
      // for scope of this within axios
      var self = this;
      axios.post('/featureduseclient').then(function (res) {
        if (res.data.using != undefined && res.data.using.length) {
          if (res.data.using[0].using == 0) {
            self.slideFeaturedUse = false;
          } else {
            self.slideFeaturedUse = true;
          }
        }
      }).catch(function (error) {
        // error handling function with error, name axios is used for, and this.
        errorHandle(error, 'Featured slide show load', self);
      });
    },
    getSpecialsUse: function getSpecialsUse() {
      // for scope of this within axios
      var self = this;
      axios.post('/specialsuseclient').then(function (res) {
        if (res.data.using != undefined && res.data.using.length) {
          if (res.data.using[0].using == 0) {
            self.slideSpecialsUse = false;
          } else {
            self.slideSpecialsUse = true;
          }
        }
      }).catch(function (error) {
        // error handling function with error, name axios is used for, and this.
        errorHandle(error, 'Specials slide show load', self);
      });
    },
    getBannerUse: function getBannerUse() {
      // for scope of this within axios
      var self = this;
      axios.post('/banneruseclient').then(function (res) {
        if (res.data.using != undefined && res.data.using.length) {
          if (res.data.using[0].using == 0) {
            self.bannerUse = false;
          } else {
            self.bannerUse = true;
          }
        }
      }).catch(function (error) {
        // error handling function with error, name axios is used for, and this.
        errorHandle(error, 'Banner use load', self);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c& ***!
  \*********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { class: _vm.templateName + "-home-page" },
    [
      _vm.slideFeaturedUse ? _c("slide-featured") : _vm._e(),
      _vm._v(" "),
      _vm.bannerUse ? _c("banner-one", { attrs: { "ban-num": 1 } }) : _vm._e(),
      _vm._v(" "),
      _vm.bannerUse ? _c("banner-two", { attrs: { "ban-num": 0 } }) : _vm._e(),
      _vm._v(" "),
      _vm.loadProducts ? _c("products-home") : _vm._e(),
      _vm._v(" "),
      _vm.slideSpecialsUse ? _c("slide-specials") : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/clientside/templates/dawn/home.vue":
/*!********************************************************************!*\
  !*** ./resources/js/components/clientside/templates/dawn/home.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./home.vue?vue&type=template&id=7b0b937c& */ "./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c&");
/* harmony import */ var _home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./home.vue?vue&type=script&lang=js& */ "./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/clientside/templates/dawn/home.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=template&id=7b0b937c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/clientside/templates/dawn/home.vue?vue&type=template&id=7b0b937c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_7b0b937c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);