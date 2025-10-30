
import 'bootstrap'                               
import '../scss/app.scss'                         
import 'bootstrap-icons/font/bootstrap-icons.css' 

// Habilita tooltips si los necesitas
document.addEventListener('DOMContentLoaded', () => {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el))
})
