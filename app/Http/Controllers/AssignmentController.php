<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{

    public function propertyTax($property_uid)
	{
	  $property_uid = $property_uid;
	  if($property_uid) {
		  
		  
		  $propertyDetails = DB::table('properties')->
								where('property_uid',$property_uid)->
								select('property_category','road_width','plot_area','colony_id')
								->get();
								
		 // print_r($propertyDetails[0]->property_category);
								
			 // we can handle the exception above for the query if no results we can send the response as false
								
		  //category id of the property
		  $category_id = $propertyDetails[0]->property_category;
								
		  // road width of the property
		  
		$rateWidth = '';
		if($propertyDetails[0]->road_width > 60)
			{
				$rateWidth = 'Super Exterior';
			} else if($propertyDetails[0]->road_width < 60 && $propertyDetails[0]->road_width > 30) {
				$rateWidth = 'Exterior';
			} else if($propertyDetails[0]->road_width < 30 ) {
				$rateWidth = 'Interior';
			}
		   // plot area of the property
		$plotArea = $propertyDetails[0]->plot_area;
			   
		// colony of the property
		$propertyColony =  $propertyDetails[0]->colony_id;
		
		// Rate calculation
		
		$rateDetails  = DB::table('rates')
					->where(['category_id'=> $category_id,
								'colony_id'=>$propertyColony,
								'road_location'=> $rateWidth])
					->select('rate')->get();
					
		// we can handle the exception above for the query if no results we can send the response as false
								
		$rate = $rateDetails[0]->rate;
		
		// tax calculations
		
		$result['property_details']['property_uid'] = $property_uid;
		$result['tax_due_summary']['total_tax'] = ($rate/2000) * $plotArea;
		$result['success'] = "true";
		return Response::json($result );
		
	  } else {
		  
		  $result['success'] = "false";
		 return Response::json($result );
	  }

}
}
