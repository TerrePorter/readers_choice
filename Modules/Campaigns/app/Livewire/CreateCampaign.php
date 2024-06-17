<?php

namespace Modules\Campaigns\Livewire;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Modules\Campaigns\Models\Campaign;

class CreateCampaign extends Component
{
    public $name;
    public $title = "Reader's Choice";
    public $start_datetime;
    public $end_datetime;
    public $enabled;
    public $campaign_id = 0;

    /**
     * Set defaults
     *
     * @return void
     */
    public function mount() {
        $this->start_datetime = date("M j, Y h:i A");
        $this->end_datetime = date("M j, Y h:i A");
        $this->enabled = false;
    }

    /**
     * Find the selected campaign by id
     *
     * @param $id
     *
     * @return void
     */
    public function loadCampaign($id) {

        // set this instances campaign id
        $this->campaign_id = $id;

        // get the db record
        $c = Campaign::find($id);

        // if record found, set vars
        if ($c) {
            $this->name = $c->name;
            $this->title = $c->title;

            if (is_null($c->start_datetime)) {
                $this->start_datetime = date("M j, Y h:i A");
            } else {
                $this->start_datetime = $c->start_datetime->format('M j, Y h:i A');
            }
            if (is_null($c->end_datetime)) {
                $this->end_datetime = date("M j, Y h:i A");
            } else {
                $this->end_datetime = $c->end_datetime->format('M j, Y h:i A');
            }

            $this->enabled = $c->enabled;
        }

    }

    /**
     * Save changed to a campaign
     *
     * @throws ValidationException
     */
    public function save() {

        // different validation for edit vs new
        if (!isset($this->campaign_id)) {
            $this->validate([
                                'name' => 'required|min:5|unique:campaigns',
                                'title' => 'required',
                                'start_datetime' => 'required',
                                'end_datetime' => 'required',
                            ]);
        } else {
            $this->validate([
                                'name' => 'required|min:5',
                                'title' => 'required',
                                'start_datetime' => 'required',
                                'end_datetime' => 'required',
                            ]);
        }

        // convert the start and end date to carbon objects
        $start = Carbon::createFromFormat('M j, Y h:i A', $this->start_datetime);
        $end = Carbon::createFromFormat('M j, Y h:i A', $this->end_datetime);

        // validate the start is before the end
        if (!$start->lessThan($end)) {
            throw ValidationException::withMessages(['start_datetime' => 'Start Date has to be before End Date.']);
        }

        // make array of data to insert/update
        $d = [
            'name' => $this->name,
            'title' => $this->title,
            'start_datetime' => $start->format('Y-m-d H:i:s'),
            'end_datetime' => $end->format('Y-m-d H:i:s'),
            'enabled' => $this->enabled
        ];

        // reset any active campaigns, if this one is set to be active
        if ($this->enabled) {
            Campaign::where('enabled', '=', 1)->update(['enabled' => 0]);
        }

        // if update
        if ($this->campaign_id != 0) {
            // update a campaign
            $user = Campaign::find($this->campaign_id);
            $user->name = $d['name'];
            $user->title = $d['title'];
            $user->start_datetime = $d['start_datetime'];
            $user->end_datetime = $d['end_datetime'];
            $user->enabled = $d['enabled'];
            $user->save();
        } else {
            // create new campaign
            Campaign::create(
                $d
            );
        }

        // this was called from a modal, send the close modal event
        $this->dispatch('my-dialog-modal-button-handler', handler:'handleDefaultModalCloseAction', data: ['dialog_key' => 'CreateCampaign']);
    }

    /**
     * get the livewire template view
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function render()
    {
        return view('campaigns::livewire.create-campaign');
    }
}
