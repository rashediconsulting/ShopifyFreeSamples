<?php
namespace RashediConsulting\ShopifyFreeSamples\Http\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use RashediConsulting\ShopifyFreeSamples\Models\SFSRule;

class RuleDetail extends Component
{
    public SFSRule $rule;
    public $sfs_set_id;

    public $rule_data = [
        "sfs_set_id" => "",
        "type" => "date",
        "lower_range" => "",
        "upper_range" => "",
    ];

    protected $rules = [
        'rule.type' => 'required|string',
        'rule.lower_range' => 'required|string',
        'rule.upper_range' => 'required|string',
    ];

    public function boot()
    {
        $this->rule->type = $this->rule->type ?: "date";

        $this->rule_data = [
            "sfs_set_id" => $this->sfs_set_id,
            "type" => $this->rule->type,
            "lower_range" => $this->rule->lower_range ?: "",
            "upper_range" => $this->rule->upper_range ?: "",
        ];


    }

    public function render()
    {
        return view('ShopifyFreeSamples::components.rule-detail');
    }

    public function saveChanges(){
        $this->rule->fill($this->rule_data)->save();
        //$this->dispatch('refreshSampleSetRules');
    }

    public function storeRule(){
        if(!empty($this->rule->id)){
            $this->deleteRule();
        }else{
            $this->createRule();
        }
    }

    public function createRule(){
        SFSRule::create($this->rule_data);
        $this->dispatch('refreshSampleSetRules');
    }

    public function deleteRule(){
        $this->rule->delete();
        $this->dispatch('refreshSampleSetRules');
    }

}