<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Campaigns\Models\Campaign;
use Modules\Campaigns\Models\CategoryTypes;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin::campaigns.index');
    }

    /**
     * Display the management form for a campaign category's
     */
    public function edit_categories($campaign_id)
    {

        // Get the campaign record
        $campaign_details = Campaign::with('categories')->find($campaign_id);

        // if no record, bounce back to main campaign index
        if (!$campaign_details) {
            return to_route('admin.campaigns.index');
        }
        $cd_array = $campaign_details->toArray();

        // get category types
        $c = CategoryTypes::all();
        $keep = [];

        foreach ($c as $index => $item) {
            // set category type as base array
            $tmp = $item->toArray();

            // if category type as categories
            if ($item->categories) {
                // get campagin categories (will be a list of all types)
                $filterArray = $cd_array['categories'];

                // filter out the campaign categories
                $tmp['categories'] = array_filter($item->categories->toArray(), function ( $v ) use ($filterArray) {
                    foreach ($filterArray as $index => $item) {
                        if ($item['id'] == $v['id']) {
                            return false;
                        }
                    }
                    return true;
                });
            }
            // save resulting category type
            $keep[] = $tmp;
        }

        // organize category details categories to be sorted by their type
        $catGrouped = [];
        foreach ($cd_array['categories'] as $index => $item) {
            $catGrouped[$item['type']][] = $item;
        }
        $cd_array['categories'] = $catGrouped;

        // return view
        return view('admin::campaigns.edit_categories', ['campaign_details' => $cd_array, 'categories' => $keep]);
    }



}
