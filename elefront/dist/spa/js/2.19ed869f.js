(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[2],{"713b":function(t,e,a){"use strict";a.r(e);var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-layout",{attrs:{view:"lHh Lpr lFf"}},[a("q-header",{attrs:{elevated:""}},[a("q-toolbar",[a("q-btn",{attrs:{flat:"",dense:"",round:"",icon:"menu","aria-label":"Menu"},on:{click:function(e){t.leftDrawerOpen=!t.leftDrawerOpen}}}),a("q-toolbar-title",[a("q-img",{staticStyle:{width:"30px"},attrs:{src:"logo.png"}}),t._v("\n          "+t._s(t.$store.getters["login/user"].name)+"\n        ")],1),a("div",[a("q-btn",{attrs:{color:"negative",icon:"logout",label:"Salir"},on:{click:t.logout}})],1)],1)],1),a("q-drawer",{attrs:{"show-if-above":"",bordered:"","content-class":"bg-grey-1"},model:{value:t.leftDrawerOpen,callback:function(e){t.leftDrawerOpen=e},expression:"leftDrawerOpen"}},[a("q-list",[a("q-item-label",{staticClass:"text-grey-8",attrs:{header:""}},[t._v("\n          Opciones de menu\n        ")]),t.$store.state.login.boolusuario?a("q-item",{attrs:{clickable:"",to:"usuarios",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"people"}})],1),a("q-item-section",[a("q-item-label",[t._v("Usuarios")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion User\n            ")])],1)],1):t._e(),t.$store.state.login.boolusuario?a("q-item",{attrs:{clickable:"",to:"usuarios",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"engineering"}})],1),a("q-item-section",[a("q-item-label",[t._v("Tecnicos")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Gestion Tecnicos\n            ")])],1)],1):t._e(),a("q-item",{attrs:{clickable:"",to:"/",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"list"}})],1),a("q-item-section",[a("q-item-label",[t._v("Puntos")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Detalles de todos los puntos\n            ")])],1)],1),t.$store.state.login.boolgeneral?a("q-item",{attrs:{clickable:"",to:"/informacion",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"map"}})],1),a("q-item-section",[a("q-item-label",[t._v("Datos del Alumbrado")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Puntos en el mapa\n            ")])],1)],1):t._e(),t.$store.state.login.boolmtto?a("q-item",{attrs:{clickable:"",to:"/mantenimiento",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"my_location"}})],1),a("q-item-section",[a("q-item-label",[t._v("Mantenimiento")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Puntos en mantenimiento\n            ")])],1)],1):t._e(),t.$store.state.login.booldistrito?a("q-item",{attrs:{clickable:"",to:"/alumbrado",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"wb_incandescent"}})],1),a("q-item-section",[a("q-item-label",[t._v("Alumbrado Publico")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n                    Ubicacion\n                  ")])],1)],1):t._e(),t.$store.state.login.booldenuncia||t.$store.state.login.boolreporte?a("q-expansion-item",{attrs:{"expand-separator":"",icon:"perm_identity",label:"Denuncias",caption:"Denuncias de contribuyente"}},[a("q-card",[a("q-card-section",[t.$store.state.login.booldenuncia?a("q-item",{attrs:{clickable:"",to:"/denuncia",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"cell_tower"}})],1),a("q-item-section",[a("q-item-label",[t._v("Denuncias")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n                    Denuncias realizadas\n                  ")])],1)],1):t._e(),t.$store.state.login.boolreporte?a("q-item",{attrs:{clickable:"",to:"/reportedenuncia",exact:""}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"print"}})],1),a("q-item-section",[a("q-item-label",[t._v("Reporte denuncias")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n                    Reporte denuncias\n                  ")])],1)],1):t._e()],1)],1)],1):t._e(),t.$store.getters["login/isLoggedIn"]?a("q-item",{attrs:{clickable:""},on:{click:t.logout}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:"logout"}})],1),a("q-item-section",[a("q-item-label",[t._v("Salir")]),a("q-item-label",{attrs:{caption:""}},[t._v("\n              Salir del sistema\n            ")])],1)],1):t._e()],1)],1),a("q-page-container",[a("router-view")],1)],1)},i=[],n={data(){return{leftDrawerOpen:!1}},created(){const t=localStorage.getItem("tokenelec");console.log(t),t&&(this.$axios.defaults.headers.common["Authorization"]="Bearer "+t,this.$axios.post("https://electricab.gamo.cf/api/me").then((e=>{console.log(e.data),this.$store.state.user=e.data,this.$store.commit("login/auth_success",{token:t,user:e.data})})).catch((t=>{this.$store.commit("login/salir"),localStorage.removeItem("tokenelec")})))},methods:{logout(){this.$q.loading.show(),this.$store.dispatch("login/logout").then((()=>{this.$q.loading.hide(),this.$router.push("/login")}))}}},s=n,l=a("2877"),r=a("4d5a"),c=a("e359"),m=a("65c6"),q=a("9c40"),b=a("6ac5"),u=a("068f"),d=a("9404"),p=a("1c1c"),g=a("0170"),v=a("66e5"),_=a("4074"),h=a("0016"),f=a("3b73"),w=a("f09f"),$=a("a370"),k=a("09e3"),x=a("eebe"),Q=a.n(x),D=Object(l["a"])(s,o,i,!1,null,null,null);e["default"]=D.exports;Q()(D,"components",{QLayout:r["a"],QHeader:c["a"],QToolbar:m["a"],QBtn:q["a"],QToolbarTitle:b["a"],QImg:u["a"],QDrawer:d["a"],QList:p["a"],QItemLabel:g["a"],QItem:v["a"],QItemSection:_["a"],QIcon:h["a"],QExpansionItem:f["a"],QCard:w["a"],QCardSection:$["a"],QPageContainer:k["a"]})}}]);