(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[4],{"8b24":function(t,e,o){"use strict";o.r(e);var n=function(){var t=this,e=t._self._c;return e("q-page",[e("l-map",{staticStyle:{height:"50vh"},attrs:{zoom:t.zoom,center:t.center}},[e("l-tile-layer",{attrs:{url:t.url,attribution:t.attribution}}),t._l(t.puntos,(function(o){return e("l-marker",{key:o.id,attrs:{"lat-lng":[o.lat,o.lng]},on:{click:function(e){return t.frmmodalpunto(o)}}},[e("l-icon",[e("q-badge",{class:"AZUL CLARO"==o.color?"bg-light-blue-6":"AZUL OSCURO"==o.color?"bg-indigo-8":"AZUL OSCURO"==o.color?"bg-green-9":"bg-red",attrs:{label:""}}),e("div",{staticStyle:{color:"white","font-size":"15px","font-family":"'Arial Black'",opacity:"0.5","text-shadow":"2px 2px 0 #000"}},[t._v(t._s(o.codigo))])],1)],1)}))],2),e("q-table",{attrs:{data:t.puntos,"hide-header":"",columns:t.colums,filter:t.filter},scopedSlots:t._u([{key:"body-cell-descripcion",fn:function(o){return[e("q-td",{attrs:{props:o},on:{click:function(e){return t.frmmodalpunto(o.row)}}},[e("div",{staticClass:"text-subtitle2",staticStyle:{"font-size":"0.8em","white-space":"initial"}},[t._v(t._s(o.row.descripcion))])])]}},{key:"body-cell-potencia",fn:function(o){return[e("q-td",{attrs:{props:o},on:{click:function(e){return t.frmmodalpunto(o.row)}}},[t._v("\n          "+t._s(o.row.potencia)+" \n        ")])]}},{key:"body-cell-nroposte",fn:function(o){return[e("q-td",{attrs:{props:o},on:{click:function(e){return t.frmmodalpunto(o.row)}}},[t._v("\n          "+t._s(o.row.nroposte)+"\n        ")])]}},{key:"body-cell-ubicacion",fn:function(o){return[e("q-td",{attrs:{props:o}},[e("q-btn",{attrs:{color:"ACTIVO"==o.row.estado?"primary":"negative",icon:"place",size:"xs"},on:{click:function(e){return t.clickclientes(o.row)}}})],1)]}},{key:"top-right",fn:function(){return[e("q-input",{attrs:{outlined:"",dense:"",debounce:"300",placeholder:"Buscar"},scopedSlots:t._u([{key:"append",fn:function(){return[e("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.filter,callback:function(e){t.filter=e},expression:"filter"}})]},proxy:!0}])}),e("q-dialog",{attrs:{"full-width":""},model:{value:t.modalpunto,callback:function(e){t.modalpunto=e},expression:"modalpunto"}},[e("q-card",[e("q-card-section",[e("div",{staticClass:"text-h6"},[t._v("Nro poste "+t._s(t.punto.nroposte))])]),e("q-card-section",{staticClass:"q-pt-none"},[e("q-form",[e("div",{staticClass:"row"},[e("div",{staticClass:"col-12"},[e("q-select",{attrs:{dense:"",outlined:"",label:"estado",options:t.estados},on:{input:function(e){return t.cambioestado()}},model:{value:t.punto.estado,callback:function(e){t.$set(t.punto,"estado",e)},expression:"punto.estado"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"codigo"},model:{value:t.punto.codigo,callback:function(e){t.$set(t.punto,"codigo",e)},expression:"punto.codigo"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"nroposte"},model:{value:t.punto.nroposte,callback:function(e){t.$set(t.punto,"nroposte",e)},expression:"punto.nroposte"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"potencia"},model:{value:t.punto.potencia,callback:function(e){t.$set(t.punto,"potencia",e)},expression:"punto.potencia"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"material"},model:{value:t.punto.material,callback:function(e){t.$set(t.punto,"material",e)},expression:"punto.material"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"lat"},model:{value:t.punto.lat,callback:function(e){t.$set(t.punto,"lat",e)},expression:"punto.lat"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"lng"},model:{value:t.punto.lng,callback:function(e){t.$set(t.punto,"lng",e)},expression:"punto.lng"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"fecha"},model:{value:t.punto.fecha,callback:function(e){t.$set(t.punto,"fecha",e)},expression:"punto.fecha"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"cantidad"},model:{value:t.punto.cantidad,callback:function(e){t.$set(t.punto,"cantidad",e)},expression:"punto.cantidad"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"destino"},model:{value:t.punto.destino,callback:function(e){t.$set(t.punto,"destino",e)},expression:"punto.destino"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"dirigente"},model:{value:t.punto.dirigente,callback:function(e){t.$set(t.punto,"dirigente",e)},expression:"punto.dirigente"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"celular"},model:{value:t.punto.celular,callback:function(e){t.$set(t.punto,"celular",e)},expression:"punto.celular"}})],1),e("div",{staticClass:"col-6"},[e("q-input",{attrs:{dense:"",outlined:"",label:"distrito"},model:{value:t.punto.distrito,callback:function(e){t.$set(t.punto,"distrito",e)},expression:"punto.distrito"}})],1),e("div",{staticClass:"col-12"},[e("q-input",{attrs:{dense:"",outlined:"",label:"tecnico"},model:{value:t.punto.tecnico,callback:function(e){t.$set(t.punto,"tecnico",e)},expression:"punto.tecnico"}})],1),e("div",{staticClass:"col-12"},[e("q-input",{attrs:{dense:"",outlined:"",label:"descripcion"},model:{value:t.punto.descripcion,callback:function(e){t.$set(t.punto,"descripcion",e)},expression:"punto.descripcion"}})],1)])])],1),e("q-card-actions",{staticClass:"bg-white text-teal",attrs:{align:"right"}},[e("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{flat:"",label:"OK"}})],1)],1)],1)],1)},a=[],i={data(){return{modalpunto:!1,estados:["ACTIVO","MANTENIMIENTO"],estado:"ACTIVO",filter:"",colums:[{name:"nroposte",label:"nroposte",field:"nroposte"},{name:"descripcion",label:"descripcion",field:"descripcion"},{name:"potencia",label:"potencia",field:"potencia"},{name:"ubicacion",label:"ubicacion",field:"ubicacion"}],puntos:[],punto:{},url:"https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",attribution:'&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',zoom:16,center:[-17.97031,-67.11178],markerLatLng:[-17.97031,-67.11178]}},created(){this.mispuntos()},methods:{frmmodalpunto(t){this.modalpunto=!0,this.punto=t},clickclientes(t){this.center=[t.lat,t.lng],this.zoom=18},cambioestado(){console.log(this.punto),this.$axios.put("punto/"+this.punto.id,this.punto)},mispuntos(){this.$q.loading.show(),this.$axios.get("punto").then((t=>{this.puntos=t.data,this.$q.loading.hide()}))}}},l=i,s=o("2877"),c=o("9989"),u=o("58a8"),r=o("eaac"),p=o("db86"),d=o("9c40"),b=o("27f9"),f=o("0016"),m=o("24e8"),v=o("f09f"),g=o("a370"),h=o("0378"),k=o("ddd8"),q=o("4b7e"),x=o("7f67"),C=o("eebe"),y=o.n(C),w=Object(s["a"])(l,n,a,!1,null,null,null);e["default"]=w.exports;y()(w,"components",{QPage:c["a"],QBadge:u["a"],QTable:r["a"],QTd:p["a"],QBtn:d["a"],QInput:b["a"],QIcon:f["a"],QDialog:m["a"],QCard:v["a"],QCardSection:g["a"],QForm:h["a"],QSelect:k["a"],QCardActions:q["a"]}),y()(w,"directives",{ClosePopup:x["a"]})}}]);