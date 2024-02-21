<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;
use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;

class RuleSet extends Component
{
    public $sfs_set_id;
    public SFSSet $sfs_set;
    public SFSRule $rule;

    protected $listeners = ['refreshSampleSetRules'];

    public function render()
    {
        $this->sfs_set=SFSSet::find($this->sfs_set_id);
        return view('ShopifyFreeSamples::components.rule-set');
    }

    public function refreshSampleSetRules(){
        $this->render();
    }

}