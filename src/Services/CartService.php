<?php
	
namespace RashediConsulting\ShopifyFreeSamples\Services;

class CartService{



	public function addSamplesToCart(){
		$samples = $this->getSampleList();
	}


	public function getSampleList(){
        $sample_list = $this->getEligibleSamples();

	}

	public function getEligibleSamples(){
        return SFSProduct::where("sfs_set_id", "=", 1)->get();
	}
}