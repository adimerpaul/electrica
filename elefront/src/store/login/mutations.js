export function auth_request(state){
  state.status = 'loading'
}
export function auth_success(state, data){
  // console.log(data)
  state.status = 'success'
  state.token = data.token
  state.user = data.user
  // console.log(state.user)
   state.boolusuario=data.user.permisos.find(p=>p.id===1)!=undefined
   state.booledituser=data.user.permisos.find(p=>p.id===2)!=undefined
   state.boolpuntos=data.user.permisos.find(p=>p.id===3)!=undefined
   state.boolgeneral=data.user.permisos.find(p=>p.id===4)!=undefined
   state.boolmtto=data.user.permisos.find(p=>p.id===5)!=undefined
   state.booldistrito=data.user.permisos.find(p=>p.id===6)!=undefined
   state.booldenuncia=data.user.permisos.find(p=>p.id===7)!=undefined
   state.boolreporte=data.user.permisos.find(p=>p.id===8)!=undefined
   state.boolcronograma=data.user.permisos.find(p=>p.id===9)!=undefined
  // state.boolbuscararchivos=data.user.permisos.find(p=>p.id===2)!=undefined
}
export function   auth_error(state){
  state.status = 'error'
}
export function salir(state){
  state.status = ''
  state.token = ''
  state.boolusuario=false
  state.booledituser=false
  state.boolpuntos=false
  state.boolgeneral=false
  state.boolmtto=false
  state.booldistrito=false
  state.booldenuncia=false
  state.boolreporte=false
  state.boolcronograma=false
  // state.boolmercadocambioactividad=false
  // state.boolbuscararchivos=false
}
