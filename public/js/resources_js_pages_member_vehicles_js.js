"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_pages_member_vehicles_js"],{

/***/ "./resources/js/pages/member/vehicles.js":
/*!***********************************************!*\
  !*** ./resources/js/pages/member/vehicles.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "vehicles": () => (/* binding */ vehicles)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _utils_formValidationError__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/utils/formValidationError */ "./resources/js/utils/formValidationError.js");



function vehicles(form, typeMember) {
  var initialVehicles = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
  var listVehicle = reactive([]);
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(function () {
    initialVehicles.forEach(function (val) {
      return listVehicle.push(val);
    });
  });

  var vehicleClear = function vehicleClear() {
    listVehicle.splice(0);
  };

  var vehicleOnDelete = function vehicleOnDelete(index) {
    listVehicle.splice(index, 1);
    (0,_inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.usePage)().props.value.errors = {};
  };

  var vehicleAddValidation = function vehicleAddValidation() {
    if (!form.plat_number) {
      throw new _utils_formValidationError__WEBPACK_IMPORTED_MODULE_2__["default"]('plat_number', 'Plat kendaraan tidak boleh kosong');
    }

    if (!form.max_vehicle_id) {
      throw new _utils_formValidationError__WEBPACK_IMPORTED_MODULE_2__["default"]('max_vehicle_id', 'Tidak boleh kosong');
    }

    var platNumberExist = listVehicle.filter(function (vehicle) {
      return vehicle.platNumber === form.plat_number.toUpperCase();
    });

    if (platNumberExist.length) {
      throw new _utils_formValidationError__WEBPACK_IMPORTED_MODULE_2__["default"]('plat_number', 'Nomor plat kendaraan tidak boleh sama');
    }

    var maxVehicles = listVehicle.filter(function (vehicle) {
      return vehicle.maxVehicleId === form.max_vehicle_id;
    });

    if (maxVehicles.length) {
      if (maxVehicles.length + 1 > maxVehicles[0].maxVehicle) {
        throw new _utils_formValidationError__WEBPACK_IMPORTED_MODULE_2__["default"]('plat_number', 'Melibihi batas maksimal kendaraan');
      }
    }
  };

  var vehicleOnAdd = function vehicleOnAdd() {
    try {
      form.clearErrors('plat_number', 'max_vehicle_id');
      vehicleAddValidation();
      var typeVehicle = typeMember.maxVehicles.filter(function (maxVehicle) {
        return maxVehicle.value === form.max_vehicle_id;
      })[0];
      listVehicle.push({
        platNumber: form.plat_number.toUpperCase(),
        typeVehicle: typeVehicle.label,
        typeVehicleId: typeVehicle.typeVehicleId,
        maxVehicleId: typeVehicle.value,
        maxVehicle: typeVehicle.maxVehicle
      });
      form.reset('plat_number', 'max_vehicle_id');
    } catch (err) {
      form.setError(err.field, err.message);
    }
  };

  return {
    listVehicle: listVehicle,
    vehicleClear: vehicleClear,
    vehicleOnDelete: vehicleOnDelete,
    vehicleOnAdd: vehicleOnAdd
  };
}

/***/ }),

/***/ "./resources/js/utils/formValidationError.js":
/*!***************************************************!*\
  !*** ./resources/js/utils/formValidationError.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ FormValidationError)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function _construct(Parent, args, Class) { if (_isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var FormValidationError = /*#__PURE__*/function (_Error) {
  _inherits(FormValidationError, _Error);

  var _super = _createSuper(FormValidationError);

  function FormValidationError(field, message) {
    var _this;

    _classCallCheck(this, FormValidationError);

    _this = _super.call(this, message);
    _this.field = field;
    return _this;
  }

  return _createClass(FormValidationError);
}( /*#__PURE__*/_wrapNativeSuper(Error));



/***/ })

}]);