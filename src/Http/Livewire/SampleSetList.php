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
        $new_set = SFSSet::create(
            [
                'name' => 'New set',
                'active' => false,
                'quantity' => '2',
                'display_in_checkout' => false,
                'repeatable' => false,
            ]
        );

        return redirect()->to(route('free-sample-set-detail', $new_set));
    }

    public function editSampleSet($sample_set_id){
        return redirect()->to(route('free-sample-set-detail', $sample_set_id));
    }   

    public function deleteSampleSet($sample_set_id){
        SFSSet::find($sample_set_id)->delete();

        $this->messages[]= 'Sample set deleted.';
    }

    
}