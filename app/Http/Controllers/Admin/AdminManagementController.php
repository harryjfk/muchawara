<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Admin\AdminManagementRepository;




class AdminManagementController extends Controller {

    protected $adminRepo;
    public function __construct (AdminManagementRepository $adminRepo) {
        $this->adminRepo = $adminRepo;
    }


    
    //this function shows the admin management view..
    //where admin can create and delete , update admin password
    //Route:: admin/users/adminmanagement
    public function showAdminManagement () {
        return view('admin.admin_management', [
            'admins' => $this->adminRepo->getAllAdmins(),
            'admin_two_factor_authentication' => $this->adminRepo->adminTwoFactorAuth()
        ]);
    }




    //this function does all admin managent tasks
    //where admin can create and delete , update admin password
    //Route:: admin/users/adminmanagement
    public function updateAdmin (Request $request) {

        switch ($request->_task) {
            
            case 'createAdmin':
                
                $response = $this->adminRepo->createAdmin(
                    $request->name,
                    $request->username,
                    $request->password,
                    $request->password_confirmation,
                    $request->role,
                    $request->role_purpose,
                    $request->contact_no,
                    $request->ip(),
                    date('Y-m-d H:i:s')
                );

                return response()->json($response);
                break;

            
            case 'delete_admin':
                $status = $this->adminRepo->deleteAdmin($request->id);
                return response()->json($status);
                break;

            
            case 'update_admin':
                $response = $this->adminRepo->updateAdmin(
                    $request->admin_id,
                    $request->name,
                    $request->username,
                    $request->password,
                    $request->password_confirmation,
                    $request->role,
                    $request->admin_purpose,
                    $request->contact_no
                );

                return response()->json($response);
                break;
            
            default:
                return redirect('admin/users/adminmanagement');
                break;
        }
            
         
    }





    public function showAdminAccessibleRoutes(Request $request)
    {
        $admin = $this->adminRepo->adminByID($request->admin_id);

        if($admin->role != 'guest') {
            return trans('admin.not_a_guest_admin');
        }



        $adminRoutesList = $this->adminRepo->accessibleRoutesByAdminID($admin->id);
        return view('admin.admin_accessible_routes_management', [
            'admin' => $admin,
            "routes_list" => $adminRoutesList
        ]);
    }


    public function saveAdminAccessibleRoutes(Request $request)
    {
        $routes = is_array($request->routes) ? $request->routes : [];
        $response = $this->adminRepo->saveAdminAccessibleRoutes($request->admin_id, $routes);
        return response()->json($response);
    }


    


    public function saveAdminTwoFactorAuth(Request $request)
    {
        $this->adminRepo->setAdminTwoFactorAuth($request->admin_two_factor_authentication);
        return response()->json([
            "status" => "success"
        ]);
    }

}

