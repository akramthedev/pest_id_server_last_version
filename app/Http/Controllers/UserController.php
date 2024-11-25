<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Prediction;
use App\Models\Image;
use App\Models\Farm;
use App\Models\Serre;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;


class UserController extends Controller
{


    
    public function register(Request $request)
    {
        
        
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Un compte avec cette adresse e-mail existe déjà.'], 406);
        }
        else{

            $user = User::create([
                'fullName' => $request->fullName,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'image' => "https://res.cloudinary.com/dqprleeyt/image/upload/v1731407662/istockphoto-1397556857-612x612_1_vxnuqq.jpg",
                'password' => Hash::make($request->password), 
                'type' => "admin",
                "canAccess" => 0, 
                "isEmailVerified" => 0,
                'is_np' => 1, 
                'is_maj' => 1, 
                'is_an' => 1,
                'is_ja' => 1,
                'isSeen' =>0
            ]);
    
            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);

        }
        
        
    }





    

    public function register2(Request $request)
    {
        
        
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Un compte avec cette adresse e-mail existe déjà.'], 406);
        }
        else{

            $user = User::create([
                'fullName' => $request->fullName,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'image' => "https://res.cloudinary.com/dqprleeyt/image/upload/v1731407662/istockphoto-1397556857-612x612_1_vxnuqq.jpg",
                'password' => Hash::make($request->password), 
                'type' => "admin",
                "canAccess" => 1, 
                "isEmailVerified" => 1,
                'is_np' => 1, 
                'is_maj' => 1, 
                'is_an' => 1,
                'is_ja' => 1,
                'isSeen' => 0
            ]);

            if($user){
                $admin = Admin::create([
                    'user_id' => $user->id,
                ]);
            }
    
            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);

        }
        
        
    }




    

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 202);
        }

        
        
        if (!$user->canAccess && $user->isEmailVerified) {  
            return response()->json(['message' => "Votre accès à l'application est restreint."], 206);
        }

        
        if (!$user->canAccess) {  
            return response()->json(['message' => "Veuillez attendre l'approuvation d'un admin."], 203);
        }

    
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 202);
        }
    
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user], 200);
    }
    
        
    
    
    public function refuserUser($id)
    {
        // Validate that the user ID exists
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        // Delete the user
        $user->delete();
    
        return response()->json(['message' => 'User successfully deleted.'], 200);
    }
    
    

    
    public function getOtherDataOfUserInDashboard ($id)
    {
        // Find the user by ID
        $user = User::find($id);
    
        // Return 404 if the user is not found
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        // Retrieve predictions with related images
        $predictions = Prediction::with('images')
            ->where('user_id', $id)
            ->get();
    
        // Retrieve the admin associated with the user
        $admin = Admin::where('user_id', $id)->first();
    
        // Initialize staff variable
        $staffsCount = 0;
    
        // If admin exists, retrieve the number of associated staffs
        if ($admin) {
            $staffsCount = Staff::where('admin_id', $admin->id)->count(); // Use count instead of get() for performance
        }
    
        // Retrieve the number of farms associated with the user
        $farmsCount = Farm::where('user_id', $id)->count(); // Use count instead of get() for performance
    
        // Prepare the response data
        $data = [
            'farmsNumber' => $farmsCount,
            'predictionsNumber' => $predictions->count(), // Use count on the collection
            'staffsNumber' => $staffsCount,
        ];
    
        // Return the response with a 200 status
        return response()->json($data, 200);
    }


    public function accepterUser($id)
    {
        // Validate that the user ID exists
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Update canAccess and isEmailVerified attributes
        $user->canAccess = 1; // Set canAccess to true (1)
        $user->isEmailVerified = 1; // Set isEmailVerified to true (1)
        $user->save(); // Save the changes

        return response()->json(['message' => 'User access granted and email verification set to true.'], 200);
    }





    public function getUserById($id)
    {
        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

         return response()->json($user, 200);
    }


    public function getUserByIdAndHisStaffData($id)
    {
        // Find the user by ID
        $user = User::with('staffs')->find($id);


        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

         return response()->json($user, 200);
    }

    
    public function getAdminIdFromUserId($idUser)
    {
        $admin = Admin::where('user_id', $idUser)->first();

         if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 299);
        }
    
         return response()->json($admin, 200);
    }

 



    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function getAllUsers()
    {
        $users = User::all();
        $data =  [
            'status'=>200,
            'users'=>$users
        ];

        return response()->json($data, 200);
    }

    

    public function updatePassword(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'ancien' => 'required|string',
            'nouveau' => 'required|string|min:5',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 399);
        }

        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 301);
        }

        // Verify the old password
        if (!Hash::check($request->ancien, $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe ne matche pas'], 287);
        }

        

        // Update the password
        $user->password = Hash::make($request->nouveau);
        $user->save();

        return response()->json(['message' => 'Mot de passe changé avec succès!'], 200);
    }


    public function updatePasswordByAdmin(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'nouveau' => 'required|string|min:4',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 399);
        }

        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 301);
        }
  

        // Update the password
        $user->password = Hash::make($request->nouveau);
        $user->save();

        return response()->json(['message' => 'Mot de passe changé avec succès!'], 200);
    }








    public function updatePassword2(Request $request)
    {
         
 

        // Find the user by ID
        $user = User::where('email', $request->email)->first(); 

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 202);
        }
 

        // Update the password
        $user->password = Hash::make($request->nouveau);
        $user->save();

        return response()->json(['message' => 'Mot de passe changé avec succès!'], 200);
    }







    public function updateUserRestriction($id, $access)
    {
        // Validate that the user ID exists
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if($access === "canNotAccess"){
            //we give him access = 1
            $user->canAccess = 1;  
        }
        else{
            // we give him access = 0
            $user->canAccess = 0;  
        }
        $user->save();  

        return response()->json(['message' => 'User access updated successfully.', 'user' => $user], 200);
    }




    public function getAllUsersNonAccepted()
    {
        $users = User::where('canAccess', 0)->where('isEmailVerified', 0)->get();
        $data =  [
            'status' => 200,
            'users' => $users
        ];

        return response()->json($data, 200);
    }


    
    public function UserIsWelcomedDone($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user->update(['is_first_time_connected' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function notice1($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 202);
        }
    
        $user->update(['historique_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }



    public function notice2($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 202);
        }
    
        $user->update(['mesfermes_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }


    public function notice3($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['mespersonels_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }


    public function notice4($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['dashboard_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function notice5($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['liste_users_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function notice6($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['nouvelle_demande_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }



    public function notice7($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['profile_notice' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }



    public function notice8($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['isJournalActivitySeen' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }


    public function notice9($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['isBroadcastSeen' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    




    public function refreshAllParams($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_np' => 1, 'is_an' => 1, 'is_maj' => 1, 'is_ja' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function activateNP($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_np' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function activateAN($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_an' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function activateMAJ($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_maj' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function activateJA($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_ja' => 1]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }




    public function desactivateNP($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_np' => 0]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function desactivateAN($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_an' => 0]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function desactivateMAJ($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_maj' => 0]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    public function desactivateJA($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['is_ja' => 0]);
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }









    public function userHaveSeenBroadCast($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json($user, 202);
        }
    
        $user->update(['isNoticeOfBroadCastSeen' => 1]);
        
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }




    public function updateUser(Request $request, $idUser)
    {

        // Find the user by ID
        $user = User::find($idUser);
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if($request->type === "admin"){
            //we create a admin in table of admins 
            $admin = Admin::create([
                'user_id' => $idUser,
            ]);
        }

        // Update the user's attributes
        $user->update($request->only('fullName', 'email', 'mobile', 'image', 'type'));
    
        // Return a success response
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    



    public function updateUserType(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();

        $user->type = $request->type;
        $user->save();

        return response()->json(['message' => 'User type updated successfully', 'user' => $user], 200);
    }




    
    public function deleteUserWhoIsAdmin(Request $request, $id)
    {
        // Find the user by ID (which can be an admin user)
        $user = User::findOrFail($id); // This will throw an exception if the user is not found
    
        // Step 1: Delete all predictions associated with the user
        $predictions = Prediction::where('user_id', $user->id)->get();
        
        if (!$predictions->isEmpty()) { // Check if there are predictions
            foreach ($predictions as $prediction) {
                // Step 1.1: Delete associated images (if not set up to cascade)
                $images = Image::where('prediction_id', $prediction->id)->get();
                if (!$images->isEmpty()) { // Check if there are images
                    foreach ($images as $image) {
                        $image->delete();
                    }
                }
    
                // Step 1.2: Delete the prediction itself
                $prediction->delete();
            }
        }
    
        // Step 2: Delete all staff members associated with the user
        $staffMembers = Staff::where('admin_id', $user->id)->get();
        
        if(!$staffMembers->isEmpty()){
            foreach ($staffMembers as $staff) {
                $user = User::where('id',$staff->user_id)->first();
                $user->delete();
            }
        }
    
        
    
        // Step 3: Delete all farms and their serres associated with the user
        $farms = Farm::where('user_id', $user->id)->get();
    
        if (!$farms->isEmpty()) { // Check if there are farms
            foreach ($farms as $farm) {
                // Step 3.1: Delete associated serres
                $serres = Serre::where('farm_id', $farm->id)->get();
                if (!$serres->isEmpty()) { // Check if there are serres
                    foreach ($serres as $serre) {
                        $serre->delete(); // Delete each serre
                    }
                }
                
                // Delete the farm itself
                $farm->delete();
            }
        }
    
        // Step 4: Finally, delete the user
        $user->delete();
    
        return response()->json(['message' => 'User and associated records deleted successfully.']);
    }
    

    





    public function deleteUserStaffNotAdmin(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        // Step 1: Delete predictions associated with the user
        $predictions = $user->predictions;  // Assuming you have a relation defined in User model
    
        // Check if there are predictions
        if (!$predictions->isEmpty()) {
            foreach ($predictions as $prediction) {
                // Step 1.1: Delete associated images
                $images = $prediction->images;  // Assuming you have a relation defined in Prediction model
                
                if (!$images->isEmpty()) { // Check if there are images
                    foreach ($images as $image) {
                        $image->delete();  // Delete each image
                    }
                }
                $prediction->delete();  // Delete the prediction itself
            }
        }
    
        // Step 2: Delete all staff roles associated with the user
        $staffs = $user->staffs;  // Assuming you have a relation defined in User model
    
        // Check if there are staff members
        if (!$staffs->isEmpty()) {
            foreach ($staffs as $staff) {
                $staff->delete();  // Delete each staff record
            }
        }
    
        // Step 3: Finally, delete the user
        $user->delete();
    
        return response()->json(['message' => 'User, staff, predictions, and images deleted successfully.']);
    }



    public function markDemandesAsSeen()
    {
        User::query()->update(['isSeen' => 1]);
        return response()->json("Done", 200);
    }



}

