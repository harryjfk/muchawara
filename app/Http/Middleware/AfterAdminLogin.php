<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Admin\AdminManagementRepository;
use Route;

class AfterAdminLogin
{
    public function __construct(AdminManagementRepository $adminManageRepo)
    {
        $this->adminManageRepo = $adminManageRepo;
    }

    public function handle($request, Closure $next)
    {
        if($this->isLoggedin() && $this->isLoginRoute($request)) {  

            if($this->isGuestAdmin()) {
                return $this->redirectToDefaultGuest();
                            
            } else if($this->isRootAdmin()){
                return redirect()->guest('/admin/dashboard'); 
            }
        }

        return $this->nocache($next($request));
        
    }


    protected function redirectToDefaultGuest()
    {
        return redirect(
            $this->adminManageRepo->getGuestAdminDefaultRoot(
                session('admin_id')
            )
        );
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


    protected function isLoggedin()
    {
        return session()->has('role');
    }


    protected function nocache($response)
    {
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        $response->headers->set('Pragma','no-cache');

        return $response;
    }

}
