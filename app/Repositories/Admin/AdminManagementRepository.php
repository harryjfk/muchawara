<?php

namespace App\Repositories\Admin;

use Hash;
use App\Models\Admin;
use Validator;
use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;

class AdminManagementRepository {

	public function __construct(Admin $admin) {
		$this->admin = $admin;
	}

	public function getAllAdmins() 
    { 
        return $this->admin->orderBy('created_at', "desc")->get(); 
    }

	public function insertAdminData ($data) {

		$admin = clone $this->admin;
		
		foreach ($data as $key => $value) { 
			$admin->$key = $value; 
		}
		
		$admin->save();
	}


	public function changePassword ($admin_id, $new_password) {

        if(!$new_password) {

            $msg = trans_choice('admin.change_password', 0);
            return ['status' => 'error', 'message' => $msg];

        } elseif (strlen($new_password) < 8 || strlen($new_password) > 200 ) {

            $msg = trans_choice('admin.change_password', 1);
            return ['status' => 'error', 'message' => $msg];
        
        }

        $this->admin->where('id', $admin_id)->update(['password' => Hash::make($new_password)]);
        return ['status' => 'success'];

	}


	public function deleteAdmin ($admin_id) {

		 if (UtilityRepository::session_get('admin_id') == $admin_id) {
               
            $msg = trans_choice('admin.error_delete', 0);
            return ['status' => 'error', 'message' => $msg ];

         } else {
         	
         	$this->admin->destroy($admin_id);
            return ['status' => 'success'];
        }
	}






    public function createAdmin($name, $username, $password, $passwordConfirmation, $role, $adminPurpose, $contactno, $ip, $timestamp) 
    {
      	$errors = [];
        $success = $this->validateAdminCreateCredentials([
            "name" => $name,
            "username" => $username,
            "password" => $password,
            "password_confirmation" => $passwordConfirmation,
            "role" => $role,
        ], $errors);
    

        if(!$success) {
            return [
                "status" => "error", 
                "error_type" => "VALIDATION_ERROR",
                "error_text" => $errors[0]
            ];
        }
        

        $admin = new $this->admin;
        $admin->name = $name;
        $admin->username = $username;
        $admin->password = Hash::make($password);
        $admin->role = $role;
        $admin->admin_purpose = $adminPurpose;
        $admin->contact_no = $contactno;
        $admin->last_ip = $ip;
        $admin->last_login = $timestamp;

        $admin->save();

        return [
            "status" => 'success',
            "success_type" => "ADMIN_CREATED_SUCCESSFULLY",
            "success_text" => trans('admin.admin_created_success_text'),
            "data_admin" => $admin
         ];
    }



    public function validateAdminCreateCredentials($data, &$errors) 
    {
        $validator = Validator::make($data, [
            'username'              => 'required|email|max:200|min:4|unique:admin,username',
            'name'                  => 'required|max:100',
            'password'              => 'required|min:8|max:200|confirmed',
            'password_confirmation' => 'required|min:8|max:200',
            'role' => 'required',
        ]);

        if($validator->fails()) {
            $errors = $validator->errors()->all();
            return false;
        } else {
            return true;
        }

    }



    public function checkAdminUserExistsForOthers($adminID, $username)
    {
        $admin = $this->admin->where("id", "!=", $adminID)->where('username', $username)->first();
        return $admin ? true : false;
    }



    public function updateAdmin($adminID, $name, $username, $password, $passwordConfirmation, $role, $adminPurpose, $contactno)
    {
        $admin = $this->admin->find($adminID);

        if(!$admin) return false;

        if($this->checkAdminUserExistsForOthers($adminID, $username)) {
            return [
                "status" => "error",
                "error_type" => "ADMIN_USERNAME_EXISTS_OTHERS",
                "error_text" => trans('admin.admin_username_exists_for_others')
            ];
        }

        $admin->name = $name;
        $admin->username = $username;
        $admin->role = $role;
        $admin->admin_purpose = $adminPurpose;
        $admin->contact_no = $contactno;


        if($password != '' && $passwordConfirmation != '') {


            if($password === $passwordConfirmation) {
                $admin->password = Hash::make($password);
            } else {
                return [
                    "status" => "error",
                    "error_type" => "VALIDATION_ERROR",
                    "error_text" => trans('admin.password_confirmation_not_matched')
                ];
            }


        }

        $admin->save();
        return [
            "status" => 'success',
            "success_type" => "ADMIN_UPDATED_SUCCESSFULLY",
            "success_text" => trans('admin.admin_updated_success_text'),
            "data_admin" => $admin
         ];
    }




    public function accessibleRoutesByAdminID($adminID)
    {       
        $routeLists = $this->accessibleRoutesList();
        
        foreach($routeLists as $key => $routeInfo) {

            $adminAccessibleRoutesCount = 0;
            $routeLists[$key]['accessible_for_admin'] = false;

            foreach($routeInfo['routes'] as $routeKey => $route) {

                $accessible = $this->isRouteAccessibleForAdmin($adminID, $route['name']); 
                $routeLists[$key]['routes'][$routeKey]["accessible_for_admin"] = $accessible;

                if($accessible) {
                    $adminAccessibleRoutesCount++;
                }
            }

            if(count($routeInfo['routes']) == $adminAccessibleRoutesCount) {
                $routeLists[$key]['accessible_for_admin'] = true;
            }
            
        }

        return $routeLists;
    }



    /*protected function adminAccibleRoutesCountByID($adminID)
    {
        $adminRoutes = $this->adminAccessibleRoutesFromConfig($adminID);
        return count($adminRoutes);
    }*/


    protected function adminAccessibleRoutesFromConfig($adminID) 
    {
        return config("admin_accessible_routes.admin_{$adminID}");
    }


    protected function getAdminAccessibleArray()
    {
        return config("admin_accessible_routes");
    }


    public function isRouteAccessibleForAdmin($adminID, $routeName)
    {
        $adminRoutes = $this->adminAccessibleRoutesFromConfig($adminID);
        $adminRoutes = is_array($adminRoutes) ? $adminRoutes : []; 
        return in_array($routeName, $adminRoutes);
    }


    public function getGuestAdminDefaultRoot($adminID)
    {
        $adminRoutes = $this->adminAccessibleRoutesFromConfig($adminID);
        $adminRoutes = is_array($adminRoutes) ? $adminRoutes : []; 
        return isset($adminRoutes[0]) ? $adminRoutes[0] : "";
    }



    public function accessibleRoutesList()
    {
        $listArray = Plugin::fire("admin_accessible_routes_list");
        $coreLists = $this->coreAdminAccessibleRoutesList();
        $mergedLists = $this->mergeLists($listArray, $coreLists);
        return $mergedLists;
    }


    protected function mergeLists($listArray, $coreLists)
    {
        return array_merge($listArray, $coreLists);
    }




    protected function coreAdminAccessibleRoutesList()
    {
        return [
            [
                "group_name" => trans('admin.menu_abuse_user'),
                "group_keyword" => "user_abuse_reports",
                "routes" => [
                    [
                        "name" => "admin/misc/userabuse",
                        "text" => trans('admin.user_reports'),
                        "visible" => true
                    ],
                    [
                        "name" => "admin/abusemanagement/userabuse/doaction",
                        "visible" => false
                    ],
                ],
            ],
            [
                "group_name" => trans('admin.menu_abuse_photo'),
                "group_keyword" => "photo_abuse_reports",
                "routes" => [
                    [
                        "name" => "admin/misc/photoabuse",
                        "text" => trans('admin.photo_reports'),
                        "visible" => true
                    ],
                    [
                        "name" => "admin/misc/photoabuse",
                        "visible" => false
                    ],
                ],
            ],
        ];

    }




    public function adminByID($adminID)
    {
        return $this->admin->find($adminID);
    }



    public function saveAdminAccessibleRoutes($adminID, $routes) 
    {
        try {

            $allRoutes = $this->getAdminAccessibleArray();
            $allRoutes["admin_{$adminID}"] = $routes;
            $string = $this->makeArray($allRoutes);
            $this->saveRoutesList($string);

            return [
                "status" => "success",
                "success_type" => 'ADMIN_ROUTES_SAVED',
                "success_text" => trans('admin.routes_save_success')
            ];


        } catch(\Exception $e) {
            return [
                "status" => "error",
                "error_type" => 'ADMIN_ROUTES_SAVED_ERROR',
                "error_text" => $e->getMessage()
            ];
        }

    }


    protected function accessibleRoutesListPath()
    {
        return config_path('admin_accessible_routes.php');
    }


    protected function saveRoutesList($string)
    {
        file_put_contents($this->accessibleRoutesListPath(), $string);
    }

    protected function makeArray($array)
    {
        $out = '<?php '."\nreturn\n";
        $out .= $this->buildArray($array);
        return $out . ";";
    }


    public function buildArray($array)
    {
        return is_array($array) ? var_export($array, true) : var_export([], true);
    }




    public function adminTwoFactorAuth()
    {
        return UtilityRepository::get_setting('admin_two_factor_authentication') == "true" ? true : false;
    }


    public function setAdminTwoFactorAuth($admin_two_factor_authentication)
    {
        UtilityRepository::set_setting('admin_two_factor_authentication', $admin_two_factor_authentication);
    }


}