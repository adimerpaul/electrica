export default function () {
  return {
    status: '',
    token: localStorage.getItem('tokenelec') || '',
    user : {},
    pedido:[],
    boolusuario:false,
    booledituser:false,
    boolpuntos:false,
    boolgeneral:false,
    boolmtto:false,
    booldistrito:false,
    booldenuncia:false,
    boolreporte:false,
    boolcronograma:false,
    // boolmercadocambioactividad:false,
    // boolbuscararchivos:false,
    // boolusuarios:false,
    // booltramite:false,
    // booljuridico:false,
    // boolnatural:false,
    // booldemandado:false,
    // boolcotizacion:false,
    // boolmicotizacion:false,
    // inventario:false,
    // ventadetalle:false,
    // historialventadetalle:false,
    // ventalocal:false,
    // historialventalocal:false,
    // empleados:false,
    // reportes:false,
    // gastos:false,
    // historialprestamo:false,
  }
}
