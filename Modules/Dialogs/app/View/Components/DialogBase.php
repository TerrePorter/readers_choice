<?php
namespace Modules\Dialogs\Providers\View\Components;

use Illuminate\View\Component;

class DialogBase extends Component
{
    /**
     * get dialog base template
     *
     * @return \Closure|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|string
     */
    public function render()
    {
        return view('dialogs::components.dialog-base');
    }
}
