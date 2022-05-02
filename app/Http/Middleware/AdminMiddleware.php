<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Admin\AdminManagementRepository;
use Route;

class AdminMiddleware
{
    public function __construct(AdminManagementRepository $adminManageRepo)
    {
        $this->adminManageRepo = $adminManageRepo;
    }
   


    public function handle($request, Closure $next)
    {

        if($this->isLoggedin()) {

            if($this->isLoginRoute($request)) {
                return $this->redirectToDashboard();
            }

            if($this->isGuestAdmin() && !$this->shouldPassThrough($request)) {
                return $this->redirectToDefaultGuest();
            }

            return $this->nocache($next($request));
        }

        return redirect()->guest('admin/login');
        
    }


    protected function redirectToDefaultGuest()
    {
        return redirect(
            $this->adminManageRepo->getGuestAdminDefaultRoot(
                session('admin_id')
            )
        );
    }


    protected function shouldPassThrough($request)
    {
        if($this->isLogoutRoute($request)) return true;
        return $this->adminManageRepo->isRouteAccessibleForAdmin(session('admin_id'), Route::current()->getURI());
    }


    protected function isLoggedin()
    {
        return session()->has('role');
    }


    protected function isGuestAdmin()
    {
        return session('role') === 'guest';
    }


    protected function isRootAdmin()
    {
        return session('role') === 'root';
    }


    protected function isLoginRoute($request)
    {
        return url('admin/login') === $request->url();
    }


    protected function isLogoutRoute($request)
    {
        return url('admin/logout') === $request->url();
    }


    protected function redirectToDashboard()
    {
        return redirect('/admin/dashboard');
    }


    protected function nocache($response)
    {
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        $response->headers->set('Pragma','no-cache');

        return $response;
    }
}
