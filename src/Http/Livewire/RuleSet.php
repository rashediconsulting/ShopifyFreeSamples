<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;

class RuleSet extends Component
{
    public SFSSet $sfs_set;
    public SFSRule $rule;

    protected $listeners = ['refreshSampleSetRules'];

    public function boot()
    {
    }

    public function render()
    {
        return view('ShopifyFreeSamples::components.rule-set');
    }

    public function refreshSampleSetRules(){
        $this->render();
    }
}