<?php

namespace App\Http\Controllers;

use App\Models\office_record;
use App\Models\User;
use App\Models\remote_record;
use App\Models\static_ip;
use App\Models\total_record;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
class UserController extends Controller
{
    private $comeinTime;
    public $add_comein_time;

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $token = $user->createToken($request->email)->plainTextToken;

        return response([
            'message' => 'User Created',
            'token' => $token,
        ]);
    }



    public function signin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
            $ip_address = $request->ip();
            // static ip for testing purpose
            // $ip_address='192.168.1.1';
            $user_ip_parts = explode('.', $ip_address);
            $user_ip = implode('.', array_slice($user_ip_parts, 0, 3));
            $static_ips = Static_ip::all();
            $user_location = '';

            foreach ($static_ips as $static_ip) {
                $static_ip_parts = explode('.', $static_ip->ip_address);
                $static_ip_prefix = implode('.', array_slice($static_ip_parts, 0, 3));

                if ($user_ip == $static_ip_prefix) {
                    $user_location = $static_ip->location;
                    $this->physical($token, $ip_address, $user, $user_location);
                    break;
                }
            }

            if (empty($user_location)) {
                $user_location = 'Remote Work';
                $this->remote($token, $ip_address, $user, $user_location);
            }

            return response([
                'token' => $token,
                'message' => 'User logged in',
            ]);
        }

        return response([
            'message' => 'Invalid credentials',
        ], 401);
    }



    private function remote($token, $ip_address, $user, $userlocation)
    {
        $currentDateTime = now();
        $visit = $currentDateTime->format('H:i:s');
        $add_comein_time = new remote_record();
        $add_comein_time->user_id = $user->id;
        $add_comein_time->ip_address = $ip_address;
        $add_comein_time->workplace = $userlocation;
        $add_comein_time->visit_time = $visit;
        $add_comein_time->updated_at = $currentDateTime;
        $add_comein_time->created_at = $currentDateTime;
        $add_comein_time->save();

        session(['visit_time' => $add_comein_time->visit_time]);

        return response([
            'token' => $token,
            'message' => 'User logged in',
        ]);
    }

    private function physical($token, $ip_address, $user, $userlocation)
    {
        // Perform physical location specific actions
        $currentDateTime = now();
        $visit = $currentDateTime->format('H:i:s');
        session(['user_id' => $user->id]);
        session(['user_location' => $userlocation]);
        session()->put('expires_at', now()->addHours(24));
        $add_comein_time = new office_record();
        $add_comein_time->user_id = $user->id;
        $add_comein_time->ip_address = $ip_address;
        $add_comein_time->workplace = $userlocation;
        $add_comein_time->visit_time = $visit;
        $add_comein_time->updated_at = $currentDateTime;
        $add_comein_time->created_at = $currentDateTime;
        $add_comein_time->save();

        session(['visit_time' => $add_comein_time->visit_time]);

        return response([
            'token' => $token,
            'message' => 'User logged in',
        ]);
    }



    // Logout method
    public function signout(Request $request)
    {
            $userId = auth()->user()->id;
            auth()->user()->tokens()->delete();

            $ip_address = $request->ip();
            // static ip for testing purpose
            // $ip_address='192.168.1.1';
            $departure = Carbon::now();
            $user_ip_parts = explode('.', $ip_address);
            $user_ip = implode('.', array_slice($user_ip_parts, 0, 3));
            $staticips = static_ip::all();
            $location = '';
            foreach ($staticips as $staticip) {
                $static_ip_parts = explode('.', $staticip->ip_address);
                $static_ip = implode('.', array_slice($static_ip_parts, 0, 3));
                if ($user_ip == $static_ip) {
                    $location = $staticip->location;
                    $this->physical_depart($ip_address, $userId, $departure, $location);
                }
                else{
                    $this->remote_depart($ip_address, $userId, $departure, $location);
                }
            }
            $this->total_duration($userId);
            return response([
                'message'=>'Process complete| Deleted Successfully',
            ]);

    }
         private function remote_depart( $ip_address, $userId, $departure,$userlocation)
        {
            $departure=Carbon::now();
            $visit=remote_record::where('user_id',$userId)
            ->whereNOtNull('visit_time')->whereNull('departure_time')
            ->first();
            if($visit){
                $visit->departure_time = $departure;
                $visit->save();
            $visitTimestamp =Carbon::parse($visit->visit_time);
            $departureTimestamp = Carbon::parse($visit->departure_time);
            $remote_hours = $visitTimestamp->diffInMinutes($departureTimestamp);
            $visit->total_remote_duration= $remote_hours;
            $visit->save();
            response([
                'message'=>'Remote Record Inserted'
            ]);
            }

        }
           private function physical_depart($ip_address, $userId, $departure,$userlocation)
        {

              $departure_office=Carbon::now();
              $visit_depart = office_record::where('user_id', $userId)
               ->whereNotNull('visit_time')
               ->whereNull('departure_time')
               ->first();
              if($visit_depart){
                  $visit_depart->departure_time = $departure_office;
                  $visit_depart->save();
                }
             $visitTimestamp =Carbon::parse($visit_depart->visit_time);
             $departureTimestamp = Carbon::parse($visit_depart->departure_time);
             $office_hours = $visitTimestamp->diffInMinutes($departureTimestamp);

             $visit_depart->total_physical_duration= $office_hours;
             $visit_depart->save();

        }

        private function total_duration($userId){
            $date = date('Y.m.d');
            $user = User::find($userId);
            if ($user) {
                $startDate = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

                $remote = $user->remote_record()->whereBetween('created_at', [$startDate, $endDate])->sum('total_remote_duration');
                $office = $user->office_record()->whereBetween('created_at', [$startDate, $endDate])->sum('total_physical_duration');
                $total_sum = $remote + $office;

                $userExists = total_record::where("user_id", $user->id)->first();
                if ($userExists) {
                    $userExists->remote_hours = $remote;
                    $userExists->physical_hours = $office;
                    $userExists->total_duration = $total_sum;
                    if($userExists->total_duration<=3){
                        $userExists->Attendence="ABSENT";
                    }elseif($userExists->total_duration<=5){
                        $userExists->Attendence="HALF DAY";
                    }else{
                        $userExists->Attendence="FULL DAY";
                    }
                    $userExists->save();
                } else {
                    $total_record = new total_record();
                    $total_record->user_id = $user->id;
                    $total_record->remote_hours = $remote;
                    $total_record->physical_hours = $office;
                    $total_record->total_duration = $total_sum;
                    if($total_record->total_duration<=3){
                        $total_record->Attendence="ABSENT";
                    }elseif($total_record->total_duration<=5){
                        $total_record->Attendence="HALF DAY";
                    }else{
                        $total_record->Attendence="FULL DAY";
                    }
                    $total_record->save();
                }

                return response(['message' => 'Total record created or updated successfully']);
            }

            return response(['message' => 'User not found'], 404);
        }

        //Check the Specific user data
        public function specific_record_user(Request $request, $id)
        {
            $user = User::where('email', $request->name)->first();
            if($user){
               $user=$user->total_record()->get();
               return $user;
            }
        }

        // All user record
        public function all_user_record(Request $request)
        {
            $user_record=User::with('total_record')->get();
            return $user_record;

        }
}
