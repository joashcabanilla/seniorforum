<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;

//Model
use App\Models\User;
use App\Models\MemberModel;

class ReportClass
{

    protected $userModel, $memberModel;

    function __construct()
    {
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
    }

    function generateReport($data){
        $data = (object) $data;
        switch($data->report){
            case "ListOfRegisteredMembers":
                return $this->ListOfRegisteredMembers($data);
            break;
        }
    }

    private function ListOfRegisteredMembers($data){
        $userList = $this->userModel->get();
        $memberList = $this->memberModel->whereNotNull("updated_by")->orderBy("branch","ASC")->orderBy("registered_at","ASC")->get();
        $users = $members = array();
        
        foreach($userList as $user){
            $users[$user->id] = strtoupper($user->name); 
        }
        
        foreach($memberList as $member){
            $members[$member->id] = [
                "memid" => $member->memid,
                "pbno" => $member->pbno,
                "name" => ucwords(strtolower($member->name)),
                "branch" => $member->branch,
                "dateRegistered" => date("d/m/Y",strtotime($member->registered_at)),
                "updatedBy" => $users[$member->updated_by],
            ]; 
        }
        
        $var = (array) $data;
        $var["title"] = "List Of Registered Members";
        $var["memberList"] = $members; 
        return response()->make(view("Report.ListOfRegisteredMembers",$var), '200'); 
    }
}
