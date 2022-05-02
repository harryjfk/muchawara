<?php

namespace App\Repositories\Admin;

use DB;
//models
use App\Models\User;
use App\Models\BlockUsers;
use App\Models\CreditHistory;
use App\Models\Credit;
use App\Models\Encounter;
use App\Models\Match;
use App\Models\Photo;
use App\Models\SuperpowerHistory;
use App\Models\UserSuperPowers;
use App\Models\UserAbuseReport;
use App\Models\UserInterests;
use App\Models\UserSettings;
use App\Models\UserSocialLogin;
use App\Models\UserFields;
use App\Models\PhotoAbuseReport;
use App\Models\Profile;
use App\Models\Visitor;
use App\Models\RiseUp;
use App\Models\Spotlight;
use App\Models\Notifications;
use App\Models\NotificationSettings;
use App\Models\NotificationContent;
use App\Components\Plugin;
use App\Components\Theme;

class UserManagementRepository {

    public function __construct(
    User $user, BlockUsers $blockUsers, CreditHistory $creditHistory, Credit $credit, Encounter $encounter, Match $match, Photo $photo, SuperpowerHistory $superpowerHistory, UserSuperPowers $userSuperPowers, UserAbuseReport $userAbuseReport, UserInterests $userInterests, UserSettings $userSettings, UserSocialLogin $userSocialLogin, UserFields $userFields, PhotoAbuseReport $photoAbuseReport, Profile $profile, Visitor $visitor, RiseUp $riseUp, Spotlight $spotlight, Notifications $notifications, NotificationSettings $notificationSettings, NotificationContent $notificationContent
    ) {

        $this->user = $user;
        $this->blockUsers = $blockUsers;
        $this->creditHistory = $creditHistory;
        $this->credit = $credit;
        $this->encounter = $encounter;
        $this->match = $match;
        $this->photo = $photo;
        $this->superpowerHistory = $superpowerHistory;
        $this->userSuperPowers = $userSuperPowers;
        $this->userAbuseReport = $userAbuseReport;
        $this->userInterests = $userInterests;
        $this->userSettings = $userSettings;
        $this->userSocialLogin = $userSocialLogin;
        $this->userFields = $userFields;
        $this->photoAbuseReport = $photoAbuseReport;
        $this->profile = $profile;
        $this->visitor = $visitor;
        $this->riseUp = $riseUp;
        $this->spotlight = $spotlight;
        $this->notifications = $notifications;
        $this->notificationSettings = $notificationSettings;
        $this->notificationContent = $notificationContent;


        $this->initActivatedUserManagementColumns();
        $this->registerActivatedUserManagementColumnsThemeModifire();
        $this->registerActivateUserManagementTableRowHook();
        $this->registerActivatedUserManagementTableRowPluginHook();
    }

    public function registerActivatedUserManagementTableRowPluginHook() {
        Plugin::add_hook("admin_activated_user_management_table_row", function($column, $user) {

                    switch ($column['column_name']) {

                        case 'checkbox_input_field':
                            return '<input class="user-checkbox" type="checkbox" data-user-id="' . $user->id . '" value="">';
                            break;
                        case 'photo':
                            return '<a href = "' . url('/profile') . '/' . $user->id . '">
								<div class="col-md-2 user-img-custom" style="background: url(' . $user->profile_pic_url() . ');background-size:contain;">
								</div>
							</a>';
                            break;
                        case 'name':
                            return '<a href = "' . url('profile') . '/' . $user->id . '">' . e($user->name) . '</a>';
                            break;
                        case 'email_id':
                            return e($user->username);
                            break;
                        case 'gender':
                            return trans('custom_profile.' . $user->gender);
                            break;
                        case 'last_online':
                            //return "<span id=\"uid_".$user->id."\"><script>localize('".$user->last_request."', \"uid_".$user->id."\");</script></span>";
                            return $user->getLastRequest();
                            break;
                        case 'birth_date':
                            return $user->getFormatedDob();
                            break;
                        case 'location':
                            return e($user->city);
                            break;
                        case 'date_joined':
                            return $user->getJoining();
                            break;
                        case 'social_links':
                            $content = '';
                            foreach ($user->get_social_links() as $link) {
                                if ($link == 'facebook') {
                                    $content .= '<i class="fa fa-facebook-square ftg"></i>';
                                } else if ($link == 'google') {
                                    $content .= '<i class="fa fa-google ftg"></i>';
                                }
                            }
                            return $content;
                            break;

                        default:
                            return "";
                            break;
                    }
                });
    }

    protected function registerActivateUserManagementTableRowHook() {
        Theme::hook("admin_activated_user_management_table_row", function($user) {

                    $columns = $this->getActivatedUserManagementTableColumns();

                    $content = "";

                    foreach ($columns as $column) {
                        $returnArray = Plugin::fire("admin_activated_user_management_table_row", [$column, $user]);

                        $tempContent = "";
                        foreach ($returnArray as $eachContent) {
                            $tempContent .= $eachContent;
                        }

                        $content .= "<td>" . $tempContent . "</td>";
                    }

                    return $content;
                });
    }

    protected function registerActivatedUserManagementColumnsThemeModifire() {

        Theme::render_modifier("admin_activated_user_management_table_columns", function($data) {

                    usort($data, function($a, $b) {
                                return ($a['priority'] == $b['priority']) ? 0 : ( ($a['priority'] < $b['priority']) ? -1 : 1 );
                            });

                    $this->activated_users_management_table_columns = $data;

                    $columnHtmlContent = "";
                    foreach ($data as $column) {
                        $columnHtmlContent .= "<th>{$column['column_text']}</th>";
                    }

                    return $columnHtmlContent;
                });
    }

    public function getActivatedUserManagementTableColumns() {
        return $this->activated_users_management_table_columns;
    }

    public function initActivatedUserManagementColumns() {
        //activated users management table columns list
        $this->activated_users_management_table_columns = [
            [
                "priority" => "1",
                "column_name" => 'checkbox_input_field',
                "column_text" => '<input type="checkbox" id="select_all_users" value=""',
            ],
            [
                "priority" => "2",
                "column_name" => "photo",
                "column_text" => trans_choice('admin.photo', 1),
            ],
            [
                "priority" => "3",
                "column_name" => "name",
                "column_text" => trans_choice('admin.name', 1),
            ],
            [
                "priority" => "4",
                "column_name" => "email_id",
                "column_text" => trans_choice('admin.email_id', 1),
            ],
            [
                "priority" => "4.1",
                "column_name" => "last_online",
                "column_text" => trans('admin.last_online'),
            ],
            [
                "priority" => "5",
                "column_name" => "gender",
                "column_text" => trans_choice('admin.sex', 1),
            ],
            [
                "priority" => "6",
                "column_name" => "birth_date",
                "column_text" => trans_choice('admin.birth_date', 1),
            ],
            [
                "priority" => "7",
                "column_name" => "location",
                "column_text" => trans_choice('admin.location', 1),
            ],
            [
                "priority" => "8",
                "column_name" => "date_joined",
                "column_text" => trans_choice('admin.date_joined', 1),
            ],
            [
                "priority" => "9",
                "column_name" => "social_links",
                "column_text" => trans_choice('admin.social_links', 1),
            ],
        ];

        Theme::hook("admin_activated_user_management_table_columns", function() {
                    return $this->activated_users_management_table_columns;
                });
    }

    public function activateUsersSuperpower($user_ids, $duration_days) {
        if ($duration_days < 1) {
            return ["status" => "error", "error_type" => "DURATION_ERROR"];
        }

        foreach ($user_ids as $user_id) {
            $user_superpower = $this->userSuperPowers->where('user_id', $user_id)->first();

            if ($user_superpower) {
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$duration_days} days", strtotime(date('Y-m-d'))));
            } else {
                $user_superpower = clone $this->userSuperPowers;
                $user_superpower->user_id = $user_id;
                $user_superpower->invisible_mode = 0;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$duration_days} days", strtotime(date('Y-m-d'))));
            }

            $user_superpower->save();
        }

        return ['status' => 'success'];
    }

    public function doAction($action, $user_ids) {

        switch ($action) {
            case 'verify':
                $this->verifyUsers($user_ids);
                break;

            case 'unverify':
                $this->unvefiryUsers($user_ids);
                break;

            case 'activate':
                $this->activateUsers($user_ids);
                break;

            case 'deactivate':
                $this->deactivateUsers($user_ids);
                break;

            default:
                return ['status' => 0];
                break;
        }

        return ['status' => 1];
    }

    /* This function returns all users 
      and also can access profiles of users by $user->profile
     */

    public function getAllActivatedUsers($username = '', $name = '') {

        $cols = ['user.*'];

        Plugin::fire('admin_activated_user_management_users_list_query_select_cols', ['cols' => &$cols]);

        $users = $this->user->withTrashed()
                ->where('user.activate_user', 'activated')
                ->where(function($query) {
                            $query->Where('user.username', 'not like', '%@bot.bot')
                            ->orWhere('user.username', NULL);
                        })
                ->select($cols);

        $orderBy = request()->sortBy;
        if ($orderBy == "") {
            $users = $users->orderBy('user.created_at', 'desc');
        } else {
            Plugin::fire("admin_activated_users_list_order_by_" . $orderBy, ['users' => &$users]);
        }



        Plugin::fire('admin_activated_user_management_uses_list_query', ['users' => &$users]);

        $users = $users->paginate(100);
        $users->setPath('usermanagement');

        Plugin::fire(
                "admin_activated_user_management_users_list", ["users" => &$users]
        );

        return $users;
    }

    public function getAllDeactivatedUsers() {

        $users = $this->user->withTrashed()
                ->where('activate_user', 'deactivated')
                ->Where('username', 'not like', '%@bot.bot')
                ->orWhere('username', NULL)
                ->orderBy('created_at', 'desc')
                ->paginate(100);

        $users->setPath('deactivate_usermanagement');

        return $users;
    }

    public function verifyUsers($userIds) {

        if (is_array($userIds)) {

            $this->user->whereIn('id', $userIds)
                    ->update(['verified' => 'verified']);
        }
    }

    public function unvefiryUsers($userIds) {

        if (is_array($userIds)) {

            $this->user->whereIn('id', $userIds)
                    ->update(['verified' => 'unverified']);
        }
    }

    public function activateUsers($userIds) {

        if (is_array($userIds)) {

            $this->user->whereIn('id', $userIds)
                    ->update(['activate_user' => 'activated']);
        }
    }

    public function deactivateUsers($userIds) {

        if (is_array($userIds)) {

            $this->user->whereIn('id', $userIds)
                    ->update([
                        'activate_user' => 'deactivated',
                        'activate_token' => "ban"
            ]);
        }
    }

    public function delete_users_permenently($user_ids) {

        DB::transaction(function () use($user_ids) {

                    $this->deleteFromBlockUsersTable($user_ids);

                    $this->deleteFromCreditHistoryTable($user_ids);
                    $this->deleteFromCreditTable($user_ids);
                    $this->deleteFromEncounterTable($user_ids);
                    $this->deleteFromMatchTable($user_ids);
                    $this->deleteFromNotificationsTable($user_ids);
                    $this->deleteFromNotificationSettingsTable($user_ids);
                    $this->deleteFromPhotoTable($user_ids);
                    $this->deleteFromProfileTable($user_ids);
                    $this->deleteFromUserTable($user_ids);
                    $this->deleteFromRiseUpTable($user_ids);
                    $this->deleteFromSpotlightTable($user_ids);
                    $this->deleteFromSuperpowerHistoryTable($user_ids);
                    $this->deleteFromUserAbuseReportTable($user_ids);
                    $this->deleteFromUserInterestsTable($user_ids);
                    $this->deleteFromUserFieldsTable($user_ids);
                    $this->deleteFromPhotoAbuseReportTable($user_ids);
                    $this->deleteFromUserSettingsTable($user_ids);
                    $this->deleteFromUserSocialLoginTable($user_ids);
                    $this->deleteFromUserSuperPowersTable($user_ids);
                    $this->deleteFromVisitorTable($user_ids);
                });
    }

    public function deleteFromBlockUsersTable($user_ids) {
        $this->blockUsers->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
    }

    public function deleteFromCreditHistoryTable($user_ids) {
        $this->creditHistory->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromCreditTable($user_ids) {
        $this->credit->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromEncounterTable($user_ids) {
        $this->encounter->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
    }

    public function deleteFromMatchTable($user_ids) {
        $this->match->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
    }

    public function deleteFromNotificationsTable($user_ids) {
        $this->notifications->withTrashed()->whereIn('from_user', $user_ids)->orWhereIn('to_user', $user_ids)->forceDelete();
    }

    public function deleteFromNotificationSettingsTable($user_ids) {
        $this->notificationSettings->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromPhotoTable($user_ids) {
        $this->photo->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromProfileTable($user_ids) {
        $this->profile->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromUserTable($user_ids) {
        $this->user->withTrashed()->whereIn('id', $user_ids)->forceDelete();
    }

    public function deleteFromRiseUpTable($user_ids) {
        $this->riseUp->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromSpotlightTable($user_ids) {
        $this->spotlight->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromSuperpowerHistoryTable($user_ids) {
        $this->superpowerHistory->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
    }

    public function deleteFromVisitorTable($user_ids) {
        $this->visitor->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
    }

    public function deleteFromUserAbuseReportTable($user_ids) {
        $this->userAbuseReport->withTrashed()->whereIn('reporting_user', $user_ids)->orWhereIn('reported_user', $user_ids)->forceDelete();
    }

    public function deleteFromPhotoAbuseReportTable($user_ids) {
        $this->photoAbuseReport->withTrashed()->whereIn('reporting_user', $user_ids)->orWhereIn('reported_user', $user_ids)->forceDelete();
    }

    public function deleteFromUserInterestsTable($user_ids) {
        $this->userInterests->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromUserFieldsTable($user_ids) {
        $this->userFields->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
    }

    public function deleteFromUserSettingsTable($user_ids) {
        $this->userSettings->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromUserSocialLoginTable($user_ids) {
        $this->userSocialLogin->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
    }

    public function deleteFromUserSuperPowersTable($user_ids) {
        $this->userSuperPowers->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
    }

    public function sendUserNotifications($user_ids, $content) {
        if (!$content) {
            return ["status" => "error", "error_type" => "DURATION_ERROR"];
        }

        $notificationContent = clone $this->notificationContent;
        $notificationContent->content = $content;
        $notificationContent->save();

        foreach ($user_ids as $user_id) {

            Plugin::fire('insert_notification', [
                'from_user' => -111,
                'to_user' => $user_id,
                'notification_type' => 'admin_notification',
                'entity_id' => $notificationContent->id,
                'notification_hook_type' => 'central'
            ]);
        }

        return ['status' => 'success'];
    }

    public function sendUserEmail($user_ids, $content, $subject) {
        if (!$content) {
            return ["status" => "error", "error_type" => "DURATION_ERROR"];
        }

        $data = new \stdClass;
        $data->subject = $subject;
        $data->body = $content;


        foreach ($user_ids as $user_id) {

            $data->user = $this->user->find($user_id);
            Plugin::Fire('send_email_raw', $data);
        }

        return ['status' => 'success'];
    }

    public function getStatics() {
        $users = User::all();

        $hoy = date('Y-m-d');
        //$unaSemanaAtras = date("d-m-Y",strtotime($hoy."- 1 week"));
        $hoyMenos1 = date("Y-m-d",strtotime($hoy."- 1 day"));
        $hoyMenos2 = date("Y-m-d",strtotime($hoy."- 2 day"));
        $hoyMenos3 = date("Y-m-d",strtotime($hoy."- 3 day"));
        $hoyMenos4 = date("Y-m-d",strtotime($hoy."- 4 day"));
        $hoyMenos5 = date("Y-m-d",strtotime($hoy."- 5 day"));
        $hoyMenos6 = date("Y-m-d",strtotime($hoy."- 6 day"));

        $usuariosConectadosHoy = array();
        $usuariosConectadosEstaSemana = array();
        $usuariosConectadosEstaSemana['0'] = array();
        $usuariosConectadosEstaSemana['1'] = array();
        $usuariosConectadosEstaSemana['2'] = array();
        $usuariosConectadosEstaSemana['3'] = array();
        $usuariosConectadosEstaSemana['4'] = array();
        $usuariosConectadosEstaSemana['5'] = array();
        $usuariosConectadosEstaSemana['6'] = array();

        foreach ($users as $user) {
            //var_dump((new \DateTime($user->last_request))->format('Y-m-d') == $hoy);die;
            if (!is_null($user->last_request)) {
                $fechaFormateada = (new \DateTime($user->last_request))->format('Y-m-d');
                if ($fechaFormateada == $hoy)
                    $usuariosConectadosEstaSemana['0'][] = $user;
                else if($fechaFormateada == $hoyMenos1)
                    $usuariosConectadosEstaSemana['1'][] = $user;
                else if($fechaFormateada == $hoyMenos2)
                    $usuariosConectadosEstaSemana['2'][] = $user;
                else if($fechaFormateada == $hoyMenos3)
                    $usuariosConectadosEstaSemana['3'][] = $user;
                else if($fechaFormateada == $hoyMenos4)
                    $usuariosConectadosEstaSemana['4'][] = $user;
                else if($fechaFormateada == $hoyMenos5)
                    $usuariosConectadosEstaSemana['5'][] = $user;
                else if($fechaFormateada == $hoyMenos6)
                    $usuariosConectadosEstaSemana['6'][] = $user;
                
            }
        }
        return $usuariosConectadosEstaSemana;
    }

}