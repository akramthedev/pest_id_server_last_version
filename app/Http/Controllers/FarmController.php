<?php




namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Serre;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmController extends Controller
{


    public function createFarm(Request $request)
    {
        

        $farm = Farm::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'location' => $request->location,
            'size' => $request->size,
        ]);

        return response()->json(['message' => 'Farm created successfully', 'farm' => $farm], 201);
    }





    public function getNames($idFarm, $idSerre)
    {
        
        $farm = Farm::find($idFarm);
        $serre = Serre::find($idSerre);
        $farmName = $farm ? $farm->name : "---";
        $serreName = $serre ? $serre->name : "---";

        
        return response()->json([
            'farm_name' => $farmName,
            'serre_name' => $serreName
        ], 200);

    }




    public function getAllFarmsPerAdmin($id)
    {
        $farms = Farm::where('user_id', $id)->get();
        return response()->json($farms, 200);
    }
    
    public function getSingleFarm($id)
    {
        $SingleFarm = Farm::where('id', $id)->get();
        return response()->json($SingleFarm, 200);
    }

    public function updateFarm(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'location' => 'string|max:255|nullable',
            'size' => 'numeric|nullable',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $farm = Farm::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$farm) {
            return response()->json(['message' => 'Farm not found or you do not have permission to update this farm.'], 404);
        }

        $farm->update($request->only(['name', 'location', 'size']));

        return response()->json(['message' => 'Farm updated successfully', 'farm' => $farm], 200);
    }




    public function deleteFarm($id)
    {
        $farm = Farm::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$farm) {
            return response()->json(['message' => 'Farm not found or you do not have permission to delete this farm.'], 404);
        }

        $farm->delete();
        return response()->json(['message' => 'Farm deleted successfully.'], 200);
    }




    public function getAllFarmsDashboard()
    {
        $farms = Farm::all();
         

        return response()->json($farms, 200);
    }




    public function farmANDserre($idFarm, $idSerre, $idUser)
    {
        // Fetch the farm for the given user
        $farm = Farm::where('id', $idFarm)->where('user_id', $idUser)->first();
    
        // Check if the farm exists
        if (!$farm) {
            return response()->json(['message' => 'Farm not found or you do not have permission to access this farm.'], 233);
        }
    
        // Fetch the serre (greenhouse) for the given farm
        $serre = Serre::where('id', $idSerre)->where('farm_id', $idFarm)->first();
    
        // Check if the serre exists
        if (!$serre) {
            return response()->json(['farm' => $farm], 234);
        }
    
        // Return both farm and serre information
        return response()->json(['farm' => $farm, 'serre' => $serre], 200);
    }

    public function getAllFarmsWithTheirSerres($idUser)
    {
        $farms = Farm::with('serres') 
            ->where('user_id', $idUser)  
            ->get();

        return response()->json($farms, 200);
    }
        


    public function farmANDserreModified($idFarm, $idSerre, $idUser, $role)
    {

        if($role === "staff"){

            $staff = Staff::where('user_id', $idUser)->first();

            if($staff){

                $farm = Farm::where('id', $idFarm)->where('user_id', $staff->admin_id)->first();

                if (!$farm) {
                    return response()->json(['message' => 'Farm not found or you do not have permission to access this farm.'], 233);
                }
        
                $serre = Serre::where('id', $idSerre)->where('farm_id', $idFarm)->first();
            
                if (!$serre) {
                    return response()->json(['farm' => $farm], 234);
                }
                return response()->json(['farm' => $farm, 'serre' => $serre], 200);

            }
            
        }
        else{
            $farm = Farm::where('id', $idFarm)->where('user_id', $idUser)->first();

            if (!$farm) {
                return response()->json(['message' => 'Farm not found or you do not have permission to access this farm.'], 233);
            }
    
            $serre = Serre::where('id', $idSerre)->where('farm_id', $idFarm)->first();
        
            if (!$serre) {
                return response()->json(['farm' => $farm], 234);
            }
            return response()->json(['farm' => $farm, 'serre' => $serre], 200);
        }

        
        
    }
    


    public function getFarmsWithGreenhouses($id)
    {
        $farms = Farm::where('user_id', $id)->get();
        $farms->load('serres');
        return response()->json($farms, 200);
        
    }


    public function getFarmsWithGreenhousesWithPlaques($id, $type)
    {

        if($type === "staff"){

            $staff = Staff::where('user_id', $idUser)->first();

            if($staff){

                $farm = Farm::where('id', $idFarm)->where('user_id', $staff->admin_id)->first();
                $farms = Farm::where('user_id', $staff->admin_id)
                ->with(['serres.plaques']) 
                ->get();
                return response()->json($farms, 200);

            }
            else{
                return response()->json("No Access", 500);
            }
            
        }
        $farms = Farm::where('user_id', $id)
            ->with(['serres.plaques']) 
            ->get();

        return response()->json($farms, 200);
    }



}