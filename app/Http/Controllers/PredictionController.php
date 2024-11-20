<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PredictionController extends Controller
{
    // Create a new prediction
   
    




    public function createPrediction(Request $request)
{
    // Process each image
    $imagePaths = [];
    $responses = [];  // Tableau pour stocker les réponses de l'API pour chaque image

    foreach ($request->file('images') as $image) {

        // Store the image and get the path
        $imagePath = $image->store('images', 'public');
        $imagePaths[] = $imagePath;

        // Call the external API for each image to process it
        $response = $this->uploadImageToExternalAPI($imagePath);

        // Store the response for each image
        $responses[] = $response;

        // If the API response is invalid, return an error
        if (!$response || !isset($response['classA'], $response['classB'], $response['classC'])) {
            return response()->json(['message' => 'Image processing failed'], 400);
        }
    }

    // Create a new prediction record in the database
    $prediction = Prediction::create([
        'user_id' => $request->user_id,
        'serre_id' => $request->serre_id,
        'farm_id' => $request->farm_id,
        'plaque_id' => $request->plaque_id,
        'created_at' => $request->created_at,
        'result' => rand(30, 70), // Random result for demo, replace with actual logic
    ]);

    // Store each image and associate it with the prediction
    foreach ($imagePaths as $index => $imagePath) {
        $response = $responses[$index];  // Get the corresponding API response for this image

        Image::create([
            'prediction_id' => $prediction->id,
            'name' => $imagePath, // Store the image path in the database
            'size' => filesize(storage_path('app/public/' . $imagePath)),  // Store the image size
            'class_A' => $response['classA'],  // Store the class_A value for this image
            'class_B' => $response['classB'],  // Store the class_B value for this image
            'class_C' => $response['classC'],  // Store the class_C value for this image
        ]);
    }

    // Return success response
    return response()->json([
        'message' => 'Prediction created successfully',
        'prediction' => $prediction
    ], 201);
}





    
    private function uploadImageToExternalAPI($imagePath)
    {
        // Simulated API response for testing
        return [
            'classA' => rand(1, 30),   
            'classB' => rand(1, 30),   
            'classC' => rand(1, 30),   
        ];
    }
    

    public function getAllPredictions()
    {
        $predictions = Prediction::all();
        return response()->json($predictions, 200);
    }

    public function getUserPredictionsWithImages($userId){
        $predictions = Prediction::where('user_id', $userId)
        ->with('images') 
        ->get();        
        
        return response()->json($predictions, 200);
    }


    public function getUserPredictionsWithImages2($userId)
    {
        $predictions = Prediction::where('user_id', $userId)
            ->with(['images', 'farm', 'serre', 'plaque'])   
            ->get();

         foreach ($predictions as $prediction) {
            foreach ($prediction->images as $image) {
                $image->name = asset('storage/' . $image->name); 
            }
        }

        return response()->json($predictions, 200);
    }



    

    public function getUserPredictions($userId)
    {
        $predictions = Prediction::where('user_id', $userId)->get();
        return response()->json($predictions, 200);
    }

    public function getSinglePredictions ($predId){
        $prediction = Prediction::where('id', $predId)->get();
        return response()->json($prediction, 200);
    }


    // Update a prediction
    public function updatePrediction(Request $request, $id)
    { 
        $prediction = Prediction::find($id);
        if (!$prediction) {
            return response()->json(['message' => 'Prediction not found.'], 404);
        }

        $prediction->plaque_id = $request->input('plaque_id');
        $prediction->farm_id = $request->input('farm_id');
        $prediction->serre_id = $request->input('serre_id');
        $prediction->created_at = $request->input('created_at');
        $prediction->save();

        return response()->json(['message' => 'Prediction updated successfully', 'prediction' => $prediction], 200);
    }


    

    // Delete a prediction
    public function deletePrediction($id)
    {
        $prediction = Prediction::find($id);
        if (!$prediction) {
            return response()->json(['message' => 'Prediction not found.'], 404);
        }

        $prediction->delete();
        return response()->json(['message' => 'Prediction deleted successfully.'], 200);
    }

    // Delete all predictions for a specific user
    public function deleteAllPredictionPerUser($id)
    {
        $predictions = Prediction::where('user_id', $id)->get();

        if ($predictions->isEmpty()) {
            return response()->json(['message' => 'No predictions found for this user.'], 200);
        }

        foreach ($predictions as $prediction) {
            $prediction->images()->delete(); 
            $prediction->delete();
        }

        return response()->json(['message' => 'All predictions deleted successfully for this user.'], 200);
    }


    
}



