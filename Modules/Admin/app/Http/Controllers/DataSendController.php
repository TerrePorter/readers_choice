<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Campaigns\Models\Campaign;

class DataSendController extends Controller
{
    use Dispatchable;

    public function handler( Request $request )
    {

        // set up expected vars
        $adsCommand = $request->post('command');
        $adsData = $request->post('data');
        $ret = ['status' => 1, 'message' => "Command Failed.", 'message_type' => "error"];

        switch ($adsCommand) {

            // get a paginated list of users
            case 'fetchUserRecordsList':
                $page = $adsData['page'];
                $perPage = $adsData['per_page'];
                $searchText = ($adsData['search_text'] ?? '');
                $ret = $this->fetchUserRecordsList($page, $searchText, $perPage);
                break;

            // get a paginated list of campaigns
            case 'fetchCampaignsRecordsList':
                $page = $adsData['page'];
                $perPage = $adsData['per_page'];
                $searchText = ($adsData['search_text'] ?? '');
                $ret = $this->fetchCampaignsRecordsList($page, $searchText, $perPage);
                break;

            // delete a campaign
            case 'deleteCampaignsRecordById':
                $campaign_id = $adsData['campaign_id'];
                $ret = $this->deleteCampaignsRecordById($campaign_id);
                break;
        }

        // return json result
        return $ret;
    }

    /**
     * Deletes the campaign by record id
     *
     * @param $campaign_id
     *
     * @return array
     */
    private function deleteCampaignsRecordById($campaign_id) {

        // check if user has permission to delete a campaign
        if (Auth::user()->hasPermissionTo('delete campaign')) {
            // delete the campaign (this will also delete the campaign categories based on the relationship)
            Campaign::find($campaign_id)->delete();
            // return a success status
            return ['status' => 1, 'message' => "Command Successful."];
        }

        return ['status' => 0, 'message' => "Insufficient permissions."];
    }

    /**
     * get a paginated list of the campaigns
     *
     * @param $page
     * @param $searchText
     * @param $perPage
     *
     * @return array
     */
    private function fetchCampaignsRecordsList($page, $searchText, $perPage): array
    {
        $keep = [];
        if (isset($searchText)) {
            $campaigns = Campaign::where('name', 'like', "%$searchText%")
                                ->orWhere('title', 'like', "%$searchText%")
                                ->paginate($perPage, ['*'], 'page', $page);
        } else {
            $campaigns = Campaign::paginate($perPage, ['*'], 'page', $page);
        }


        foreach ($campaigns as $index => $campaign) {
            $tmp = $campaign->toArray();
            $tmp['start_datetime'] = $campaign->start_datetime->toDayDateTimeString();
            $tmp['end_datetime'] = $campaign->end_datetime->toDayDateTimeString();
            $keep['data'][] = $tmp;
        }

        $keep['links'] = $campaigns->links('admin::campaigns.pagination')->render();

        return $keep;
    }

    /**
     * Get a list of users
     *
     * @param $page
     * @param $searchText
     * @param $perPage
     *
     * @return array
     */
    private function fetchUserRecordsList($page, $searchText, $perPage): array
    {
        $keep = [];
        if (isset($searchText)) {
            $users = User::where('nickname', 'like', "%$searchText%")
                                ->orWhere('email', 'like', "%$searchText%")
                                ->paginate($perPage, ['*'], 'page', $page);
        } else {
            $users = User::paginate($perPage, ['*'], 'page', $page);
        }


        foreach ($users as $index => $user) {
            $tmp = $user->toArray();
            //$tmp['birthdate'] = $user->birthdate->format('m-d-Y');
            $tmp['email_verified_at'] = (!is_null($user->email_verified_at) ? $user->email_verified_at->toDayDateTimeString(): 'N/A');
            $tmp['created_at'] = $user->created_at->toDayDateTimeString();
            $tmp['last_login'] = (!is_null($user->last_login) ? $user->last_login->toDayDateTimeString(): 'N/A');
            $keep['data'][] = $tmp;
        }

        $keep['links'] = $users->links('admin::users.pagination')->render();

        return $keep;
    }

}
