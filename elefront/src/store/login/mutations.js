export function auth_request(state){
  state.status = 'loading'
}
export function auth_success(state, data){
  // console.log(data)
  state.status = 'success'
  state.token = data.token
  state.user = data.user
  // console.log(state.user)
  // state.boolmercadocambioactividad=data.user.permisos.find(p=>p.id===1)!=undefined
  // state.boolbuscararchivos=data.user.permisos.find(p=>p.id===2)!=undefined
}
export function   auth_error(state){
  state.status = 'error'
}
export function salir(state){
  state.status = ''
  state.token = ''

  // state.boolmercadocambioactividad=false
  // state.boolbuscararchivos=false
}
