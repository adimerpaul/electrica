(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[2],{"713b":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t._self._c;return e("q-layout",{attrs:{view:"lHh Lpr lFf"}},[e("q-header",{attrs:{elevated:""}},[e("q-toolbar",[e("q-btn",{attrs:{flat:"",dense:"",round:"",icon:"menu","aria-label":"Menu"},on:{click:function(e){t.leftDrawerOpen=!t.leftDrawerOpen}}}),e("q-toolbar-title",[e("q-img",{staticStyle:{width:"30px"},attrs:{src:"logo.png"}}),t._v("\n          "+t._s(t.$store.getters["login/user"].name)+"\n        ")],1),e("div",[e("q-btn",{attrs:{color:"negative",icon:"logout",label:"Salir"},on:{click:t.logout}})],1)],1)],1),e("q-drawer",{attrs:{"show-if-above":"",bordered:"","content-class":"bg-grey-1"},model:{value:t.leftDrawerOpen,callback:function(e){t.leftDrawerOpen=e},expression:"leftDrawerOpen"}},[e("q-list",[e("q-item-label",{staticClass:"text-grey-8",attrs:{header:""}},[t._v("\n          Opciones de menu\n        ")]),e("q-item",{attrs:{clickable:"",to:"home",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"home"}})],1),e("q-item-section",[e("q-item-label",[t._v("Principal")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Home\n            ")])],1)],1),t.$store.state.login.boolusuario?e("q-item",{attrs:{clickable:"",to:"usuarios",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"people"}})],1),e("q-item-section",[e("q-item-label",[t._v("Usuarios")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion User\n            ")])],1)],1):t._e(),t.$store.state.login.booltecnico?e("q-item",{attrs:{clickable:"",to:"tecnicos",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"engineering"}})],1),e("q-item-section",[e("q-item-label",[t._v("Listado Tecnicos")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion tecnicos\n            ")])],1)],1):t._e(),t.$store.state.login.booljunta?e("q-item",{attrs:{clickable:"",to:"juntavecinal",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"real_estate_agent"}})],1),e("q-item-section",[e("q-item-label",[t._v("Juntas Vecinales")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion JV\n            ")])],1)],1):t._e(),t.$store.state.login.boolgestionar?e("q-item",{attrs:{clickable:"",to:"apublico",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"engineering"}})],1),e("q-item-section",[e("q-item-label",[t._v("Gestion AP")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion Postes\n            ")])],1)],1):t._e(),e("q-item",{attrs:{clickable:"",to:"/",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"list"}})],1),e("q-item-section",[e("q-item-label",[t._v("Puntos")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Detalles de todos los puntos\n            ")])],1)],1),t.$store.state.login.boolgeneral?e("q-item",{attrs:{clickable:"",to:"/informacion",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"map"}})],1),e("q-item-section",[e("q-item-label",[t._v("Datos del Alumbrado")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Puntos en el mapa\n            ")])],1)],1):t._e(),t.$store.state.login.boolmtto?e("q-item",{attrs:{clickable:"",to:"/mantenimiento",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"my_location"}})],1),e("q-item-section",[e("q-item-label",[t._v("Mantenimiento")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Puntos en mantenimiento\n            ")])],1)],1):t._e(),t.$store.state.login.booldistrito?e("q-item",{attrs:{clickable:"",to:"/alumbrado",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"wb_incandescent"}})],1),e("q-item-section",[e("q-item-label",[t._v("Alumbrado Publico")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Ubicacion\n                  ")])],1)],1):t._e(),t.$store.state.login.boolreclamo?e("q-item",{attrs:{clickable:"",to:"/reclamos",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"handyman"}})],1),e("q-item-section",[e("q-item-label",[t._v("Reclamos Mapa")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Ubicacion Reclamo\n                  ")])],1)],1):t._e(),t.$store.state.login.boolreporte?e("q-item",{attrs:{clickable:"",to:"/reportereclamo",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"print"}})],1),e("q-item-section",[e("q-item-label",[t._v("Reporte Reclamos")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Reporte atencion\n                  ")])],1)],1):t._e(),t.$store.state.login.boolcronograma?e("q-item",{attrs:{clickable:"",to:"/cronograma",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"date_range"}})],1),e("q-item-section",[e("q-item-label",[t._v("Planificacion ")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Actividad\n                  ")])],1)],1):t._e(),t.$store.state.login.booldenuncia||t.$store.state.login.boolreporte?e("q-expansion-item",{attrs:{"expand-separator":"",icon:"perm_identity",label:"Denuncias",caption:"Denuncias de contribuyente"}},[e("q-card",[e("q-card-section",[t.$store.state.login.booldenuncia?e("q-item",{attrs:{clickable:"",to:"/denuncia",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"cell_tower"}})],1),e("q-item-section",[e("q-item-label",[t._v("Denuncias")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Denuncias realizadas\n                  ")])],1)],1):t._e(),t.$store.state.login.boolreporte?e("q-item",{attrs:{clickable:"",to:"/reportedenuncia",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"print"}})],1),e("q-item-section",[e("q-item-label",[t._v("Reporte denuncias")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Reporte denuncias\n                  ")])],1)],1):t._e()],1)],1)],1):t._e(),t.$store.state.login.boolalmacen?e("q-expansion-item",{attrs:{"expand-separator":"",icon:"warehouse",label:"Almacen",caption:"Inventario"}},[e("q-card",[e("q-card-section",[e("q-item",{attrs:{clickable:"",to:"/materiales",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"list_alt"}})],1),e("q-item-section",[e("q-item-label",[t._v("Listado Materiales")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Gestion Material\n                  ")])],1)],1),e("q-item",{attrs:{clickable:"",to:"/tiendas",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"storefront"}})],1),e("q-item-section",[e("q-item-label",[t._v("Listado Tienda/Proveedor")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Proveedores\n                  ")])],1)],1),e("q-item",{attrs:{clickable:"",to:"/compras",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"shopping_cart"}})],1),e("q-item-section",[e("q-item-label",[t._v("Registro Compra")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Compras\n                  ")])],1)],1),e("q-item",{attrs:{clickable:"",to:"/inventarios",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"category"}})],1),e("q-item-section",[e("q-item-label",[t._v("Listado de Inventario")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Inventario\n                  ")])],1)],1),e("q-item",{attrs:{clickable:"",to:"/salidas",exact:""}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"category"}})],1),e("q-item-section",[e("q-item-label",[t._v("Entrega Material")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n                    Inventario\n                  ")])],1)],1)],1)],1)],1):t._e(),t.$store.getters["login/isLoggedIn"]?e("q-item",{attrs:{clickable:""},on:{click:t.logout}},[e("q-item-section",{attrs:{avatar:""}},[e("q-icon",{attrs:{name:"logout"}})],1),e("q-item-section",[e("q-item-label",[t._v("Salir")]),e("q-item-label",{attrs:{caption:""}},[t._v("\n              Salir del sistema\n            ")])],1)],1):t._e()],1)],1),e("q-page-container",[e("router-view")],1)],1)},o=[],n=(a("14d9"),{data(){return{leftDrawerOpen:!1}},created(){const t=localStorage.getItem("tokenelec");console.log(t),t&&(this.$axios.defaults.headers.common["Authorization"]="Bearer "+t,this.$axios.post("https://electricab.gamo.cf/api/me").then((e=>{console.log(e.data),this.$store.state.user=e.data,this.$store.commit("login/auth_success",{token:t,user:e.data})})).catch((t=>{this.$store.commit("login/salir"),localStorage.removeItem("tokenelec")})))},methods:{logout(){this.$q.loading.show(),this.$store.dispatch("login/logout").then((()=>{this.$q.loading.hide(),this.$router.push("/login")}))}}}),s=n,r=a("2877"),l=a("4d5a"),c=a("e359"),m=a("65c6"),q=a("9c40"),b=a("6ac5"),v=a("068f"),p=a("9404"),d=a("1c1c"),_=a("0170"),g=a("66e5"),u=a("4074"),h=a("0016"),x=a("3b73"),k=a("f09f"),$=a("a370"),f=a("09e3"),w=a("eebe"),Q=a.n(w),D=Object(r["a"])(s,i,o,!1,null,null,null);e["default"]=D.exports;Q()(D,"components",{QLayout:l["a"],QHeader:c["a"],QToolbar:m["a"],QBtn:q["a"],QToolbarTitle:b["a"],QImg:v["a"],QDrawer:p["a"],QList:d["a"],QItemLabel:_["a"],QItem:g["a"],QItemSection:u["a"],QIcon:h["a"],QExpansionItem:x["a"],QCard:k["a"],QCardSection:$["a"],QPageContainer:f["a"]})}}]);