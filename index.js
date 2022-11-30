(function() {
  "use strict";
  var render$4 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", [_c("k-header", [_vm._v(" " + _vm._s(_vm.$t("view.lendmanagement.dashboard")) + " "), _c("k-button-group", { attrs: { "slot": "left" }, slot: "left" }, [_c("k-button", { attrs: { "icon": "add", "text": _vm.$t("lendmanagement.loan.add") }, on: { "click": function($event) {
      return _vm.$dialog("lendmanagement/loan/create");
    } } }), _c("k-button", { attrs: { "icon": "undo", "text": _vm.$t("lendmanagement.loan.notify") }, on: { "click": function($event) {
      return _vm.$dialog("lendmanagement/loan/notifyexpired");
    } } })], 1), _c("k-button-group", { attrs: { "slot": "right" }, slot: "right" }, [_c("k-button-link", { attrs: { "icon": "users", "link": "/lendmanagement/borrowers/" } }, [_vm._v(" " + _vm._s(_vm.$t("lendmanagement.borrowers.view")) + " ")]), _c("k-button-link", { attrs: { "icon": "table", "link": "/lendmanagement/inventory/" } }, [_vm._v(" " + _vm._s(_vm.$t("lendmanagement.dashboard.inventory.view")) + " ")])], 1)], 1), _c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(_vm._s(_vm.$t("lendmanagement.dashboard.status")))])], 1), _c("k-stats", { attrs: { "reports": _vm.stats, "size": "small" } }), _c("k-grid", { attrs: { "gutter": "large" } }, [_c("k-column", { attrs: { "width": "1" } }, [_c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(_vm._s(_vm.$t("lendmanagement.loan")))])], 1), _c("k-collection", { attrs: { "layout": "list", "items": _vm.loans } })], 1)], 1)], 1)], 1);
  };
  var staticRenderFns$4 = [];
  render$4._withStripped = true;
  var Dashboard_vue_vue_type_style_index_0_lang = "";
  function normalizeComponent(scriptExports, render2, staticRenderFns2, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render2) {
      options.render = render2;
      options.staticRenderFns = staticRenderFns2;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(this, (options.functional ? this.parent : this).$root.$options.shadowRoot);
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const __vue2_script$4 = {
    props: {
      items: Object,
      stats: Array,
      loans: Array,
      categories: Array
    },
    methods: {
      price(price) {
        return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(price);
      }
    }
  };
  const __cssModules$4 = {};
  var __component__$4 = /* @__PURE__ */ normalizeComponent(__vue2_script$4, render$4, staticRenderFns$4, false, __vue2_injectStyles$4, null, null, null);
  function __vue2_injectStyles$4(context) {
    for (let o in __cssModules$4) {
      this[o] = __cssModules$4[o];
    }
  }
  __component__$4.options.__file = "src/components/Dashboard.vue";
  var Dashboard = /* @__PURE__ */ function() {
    return __component__$4.exports;
  }();
  var render$3 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", [_c("k-header", [_vm._v(" " + _vm._s(_vm.$t("view.lendmanagement.inventory")) + " "), _c("k-button-group", { attrs: { "slot": "left" }, slot: "left" }, [_c("k-button", { attrs: { "icon": "add", "text": _vm.$t("lendmanagement.item.add") }, on: { "click": function($event) {
      return _vm.$dialog("inventory/item/create");
    } } })], 1), _c("k-button-group", { attrs: { "slot": "right" }, slot: "right" }, [_c("k-button-link", { attrs: { "icon": "cart", "link": "/lendmanagement/" } }, [_vm._v(" " + _vm._s(_vm.$t("lendmanagement.dashboard.view")) + " ")])], 1)], 1), _c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(_vm._s(_vm.$t("lendmanagement.dashboard.status")))])], 1), _c("k-stats", { attrs: { "reports": _vm.stats, "size": "small" } }), _c("k-grid", { attrs: { "gutter": "large" } }, [_c("k-column", { attrs: { "width": "1/2" } }, [_c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(_vm._s(_vm.$t("lendmanagement.items")))]), _c("k-button", { attrs: { "icon": "add", "text": _vm.$t("lendmanagement.item.add") }, on: { "click": function($event) {
      return _vm.$dialog("inventory/item/create");
    } } })], 1), _c("k-collection", { attrs: { "layout": "list", "items": _vm.items } })], 1), _c("k-column", { attrs: { "width": "1/2" } }, [_c("header", { staticClass: "k-section-header" }, [_c("k-headline", [_vm._v(_vm._s(_vm.$t("lendmanagement.categories")))]), _c("k-button", { attrs: { "icon": "add", "text": _vm.$t("lendmanagement.category.add") }, on: { "click": function($event) {
      return _vm.$dialog("stock/category/create");
    } } })], 1), _c("k-collection", { attrs: { "layout": "list", "items": _vm.categories } })], 1)], 1)], 1)], 1);
  };
  var staticRenderFns$3 = [];
  render$3._withStripped = true;
  var Inventory_vue_vue_type_style_index_0_lang = "";
  const __vue2_script$3 = {
    props: {
      items: Object,
      stats: Array,
      loans: Array,
      categories: Array
    },
    methods: {
      price(price) {
        return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(price);
      }
    }
  };
  const __cssModules$3 = {};
  var __component__$3 = /* @__PURE__ */ normalizeComponent(__vue2_script$3, render$3, staticRenderFns$3, false, __vue2_injectStyles$3, null, null, null);
  function __vue2_injectStyles$3(context) {
    for (let o in __cssModules$3) {
      this[o] = __cssModules$3[o];
    }
  }
  __component__$3.options.__file = "src/components/Inventory.vue";
  var Inventory = /* @__PURE__ */ function() {
    return __component__$3.exports;
  }();
  var render$2 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", [_c("k-header", [_vm._v(" " + _vm._s(_vm.category.title) + " "), _c("k-button-group", { attrs: { "slot": "left" }, slot: "left" }, [_c("k-button", { attrs: { "text": "Edit", "icon": "edit" }, on: { "click": function($event) {
      return _vm.$dialog("/inventory/category/" + _vm.category.id + "/update");
    } } }), _c("k-button", { attrs: { "text": "Delete", "icon": "trash" }, on: { "click": function($event) {
      return _vm.$dialog("/inventory/category/" + _vm.category.id + "/delete");
    } } })], 1), _c("k-button-group", { attrs: { "slot": "right" }, slot: "right" }, [_c("k-button", { attrs: { "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("/inventory/item/create");
    } } }, [_vm._v(" " + _vm._s(_vm.$t("lendmanagement.item.add")) + " ")])], 1)], 1), _c("k-collection", { attrs: { "layout": "list", "items": _vm.items } })], 1)], 1);
  };
  var staticRenderFns$2 = [];
  render$2._withStripped = true;
  const __vue2_script$2 = {
    props: {
      category: Object,
      items: Object
    },
    methods: {
      price(price) {
        return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(price);
      }
    }
  };
  const __cssModules$2 = {};
  var __component__$2 = /* @__PURE__ */ normalizeComponent(__vue2_script$2, render$2, staticRenderFns$2, false, __vue2_injectStyles$2, null, null, null);
  function __vue2_injectStyles$2(context) {
    for (let o in __cssModules$2) {
      this[o] = __cssModules$2[o];
    }
  }
  __component__$2.options.__file = "src/components/Category.vue";
  var Category = /* @__PURE__ */ function() {
    return __component__$2.exports;
  }();
  var render$1 = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", [_c("k-header", [_vm._v(" " + _vm._s(_vm.$t("lendmanagement.borrowers")) + " "), _c("k-button-group", { attrs: { "slot": "left" }, slot: "left" }, [_c("k-button", { attrs: { "text": "New Borrower", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("lendmanagement/borrowers/borrower/create");
    } } })], 1)], 1), _c("table", { staticClass: "k-products" }, [_c("tr", [_c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.borrowers.table.firstname")))]), _c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.borrowers.table.lastname")))]), _c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.borrowers.table.email")))]), _c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.borrowers.table.phone")))]), _c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.borrowers.table.lastlend")))])]), _vm._l(_vm.borrowers, function(borrower, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(borrower.firstname))]), _c("td", [_vm._v(_vm._s(borrower.lastname))]), _c("td", [_vm._v(_vm._s(borrower.email))]), _c("td", [_vm._v(_vm._s(borrower.phone))]), _c("td", [_vm._v(_vm._s(borrower.lastlend))])]);
    })], 2)], 1)], 1);
  };
  var staticRenderFns$1 = [];
  render$1._withStripped = true;
  var Borrowers_vue_vue_type_style_index_0_lang = "";
  const __vue2_script$1 = {
    props: {
      borrowers: Object
    },
    methods: {
      price(price) {
        return new Intl.NumberFormat("de-DE", { style: "currency", currency: "EUR" }).format(price);
      }
    }
  };
  const __cssModules$1 = {};
  var __component__$1 = /* @__PURE__ */ normalizeComponent(__vue2_script$1, render$1, staticRenderFns$1, false, __vue2_injectStyles$1, null, null, null);
  function __vue2_injectStyles$1(context) {
    for (let o in __cssModules$1) {
      this[o] = __cssModules$1[o];
    }
  }
  __component__$1.options.__file = "src/components/Borrowers.vue";
  var Borrowers = /* @__PURE__ */ function() {
    return __component__$1.exports;
  }();
  var render = function() {
    var _vm = this;
    var _h = _vm.$createElement;
    var _c = _vm._self._c || _h;
    return _c("k-inside", [_c("k-view", [_c("k-header", [_vm._v(" " + _vm._s(_vm.borrower.firstname) + " " + _vm._s(_vm.borrower.lastname) + " - " + _vm._s(_vm.loan.startDate) + " "), _c("k-button-group", { attrs: { "slot": "left" }, slot: "left" }, [_c("k-button", { attrs: { "icon": "add", "text": _vm.$t("lendmanagement.loan.add") }, on: { "click": function($event) {
      return _vm.$dialog("lendmanagement/loan/create");
    } } }), _c("k-button", { attrs: { "icon": "undo", "text": _vm.$t("lendmanagement.loan.notify") }, on: { "click": function($event) {
      return _vm.$dialog("lendmanagement/loan/notifyexpired");
    } } })], 1)], 1), _c("k-grid", { attrs: { "gutter": "large" } }, [_c("k-column", { attrs: { "width": "1/2" } }, [_c("k-form", { attrs: { "fields": {
      startDate: {
        label: "D\xE9but du pr\xEAt",
        type: "date",
        time: false,
        required: true,
        width: "1/2"
      },
      endDate: {
        label: "Fin du pr\xEAt",
        type: "date",
        time: false,
        required: true,
        width: "1/2"
      },
      line: {
        type: "line"
      }
    } }, on: { "input": _vm.input, "submit": _vm.submit }, model: { value: _vm.loan, callback: function($$v) {
      _vm.loan = $$v;
    }, expression: "loan" } }), _c("k-form", { attrs: { "fields": {
      firstname: {
        label: _vm.$t("lendmanagement.borrowers.table.firstname"),
        type: "text",
        required: true,
        width: "1/2"
      },
      lastname: {
        label: _vm.$t("lendmanagement.borrowers.table.lastname"),
        type: "text",
        required: true,
        width: "1/2"
      },
      email: {
        label: _vm.$t("lendmanagement.borrowers.table.email"),
        required: true,
        type: "text"
      },
      phone: {
        label: _vm.$t("lendmanagement.borrowers.table.phone"),
        type: "text"
      }
    } }, on: { "input": _vm.input, "submit": _vm.submit }, model: { value: _vm.borrower, callback: function($$v) {
      _vm.borrower = $$v;
    }, expression: "borrower" } })], 1), _c("k-column", { attrs: { "width": "1/2" } }, [_c("table", { staticClass: "k-products" }, [_c("thead", [_c("tr", [_c("th", { attrs: { "colspan": "3" } }, [_vm._v(_vm._s(_vm.$t("lendmanagement.items")))])])]), _c("tr", [_c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.loan.table.name")))]), _c("th", [_vm._v(_vm._s(_vm.$t("lendmanagement.loan.table.quantity")))]), _c("th")]), _vm._l(_vm.items, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.title))]), _c("td", [_vm._v(_vm._s(item.quantity))]), _c("td", { staticClass: "k-product-options" }, [_c("k-options-dropdown", { attrs: { "options": "products/" + id } })], 1)]);
    })], 2)])], 1)], 1)], 1);
  };
  var staticRenderFns = [];
  render._withStripped = true;
  var Loan_vue_vue_type_style_index_0_lang = "";
  const __vue2_script = {
    props: {
      items: Array,
      borrower: Object,
      loan: Object
    }
  };
  const __cssModules = {};
  var __component__ = /* @__PURE__ */ normalizeComponent(__vue2_script, render, staticRenderFns, false, __vue2_injectStyles, null, null, null);
  function __vue2_injectStyles(context) {
    for (let o in __cssModules) {
      this[o] = __cssModules[o];
    }
  }
  __component__.options.__file = "src/components/Loan.vue";
  var Loan = /* @__PURE__ */ function() {
    return __component__.exports;
  }();
  panel.plugin("scardoso/lendmanagement", {
    components: {
      "k-dashboard-view": Dashboard,
      "k-inventory-view": Inventory,
      "k-category-view": Category,
      "k-borrowers-view": Borrowers,
      "k-loan-view": Loan
    }
  });
})();
