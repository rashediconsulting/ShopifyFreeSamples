<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSSet;

class SampleSetList extends Component
{
    public $sample_set_list;

    public $messages=[];

    public function boot()
    {
        $this->sample_set_list = SFSSet::all();
    }

    public function render()
    {
        return view('ShopifyFreeSamples::components.sample-set-list');
    }

    public function createSampleSet(){
        dd($this);

        $this->messages[]= 'Changes successfully saved.';
    }
}