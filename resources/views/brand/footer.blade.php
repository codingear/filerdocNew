@if(Route::has('login.auth')){
    <p class="n-m font-thin v-center text-footer">Copyright &copy; {{@date('Y')}} - Todos los derechos reservados.</p>
@else
    <p class="n-m font-thin v-center text-footer black">Copyright &copy; {{@date('Y')}} - Todos los derechos reservados.</p>
@endif
   