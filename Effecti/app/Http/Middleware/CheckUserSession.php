<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verifica se a sessão 'user_session_id' existe
        if (!Session::has('user_session_id')) {
            // Redireciona para a página inicial se a sessão não existir
            return redirect('/');
        }

        return $next($request);
    }
}