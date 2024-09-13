<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//Classes
use App\Classes\DataTableClass;
use App\Classes\ReportClass;

//Model
use App\Models\User;
use App\Models\MemberModel;

class AdminController extends Controller
{
    protected $data, $datatable, $userModel, $memberModel, $reportClass;

    public function __construct()
    {
        $this->middleware('auth');
        $this->data = array();
        $this->userModel = new User();
        $this->datatable = new DataTableClass();
        $this->memberModel = new MemberModel();
        $this->reportClass = new ReportClass();
    }

    function Users(){
        $this->data["titlePage"] = "SENIOR FORUM | Users";
        $this->data["tab"] = "users"; 
        return view('Components.Users',$this->data);
    }

    function Maintenance(){
        $this->data["titlePage"] = "SENIOR FORUM | Maintenance";
        $this->data["tab"] = "maintenance";

        $tableArray = $this->datatable->getAllDatabaseTable();
        $tableList = array();
        foreach($tableArray as $table){
            foreach($table as $tablename){
                $tableList[] = trim($tablename);
            }
        }
        $this->data["tables"] = $tableList;

        $this->data['reportList'] = [
        ];

        $userList = $this->userModel->getUser();
        foreach($userList as $user){
            $this->data['userList'][$user->id] = $user->name;
        }

        return view('Components.Maintenance',$this->data);
    }

    function Members(){
        $this->data["titlePage"] = "SENIOR FORUM | Members";
        $this->data["tab"] = "members";
        $this->data["branchList"] = $this->memberModel->branchList();
        return view('Components.Members',$this->data);
    }

    function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response('logout',200); 
    }

    function UserTable(Request $request){
        return $this->datatable->userTable($request->all());
    }

    function createUpdateUser(Request $request){
        return $this->userModel->createUpdateUser($request->all());
    }

    function getUser(Request $request){
        return $this->userModel->getUser($request->id);
    }

    function deactivateUser(Request $request){
        if(!empty($request->status)){
            return $this->userModel->deactivateUser($request->id, $request->status);
        }else{
            return $this->userModel->deactivateUser($request->id);
        }
    }

    function batchInsertData(Request $request){
        $table = $request->table;
        $data = $request->insert;
        $result = array();
    
        if(!empty($data)){
            foreach($data as $rowData){
                foreach($rowData as $key => $row){
                    $dbData[trim($key)] = !empty($row) ? trim($row) : NULL;
                }
                $insertData[] = $dbData;
            }
            $dbInsert = DB::table(trim($table))->insert($insertData);
            if($dbInsert){
                $result["status"] = "success";
            }else{
                $result["status"] = "failed";
                $result["error"] = $insertData;
            }
        }else{
            $result["status"] = "failed";
            $result["error"] = $data;
        }

        return $result;
    }

    function memberTable(Request $request){
        return $this->datatable->memberTable($request->all());
    }

    function createUpdateMember(Request $request){
        return $this->memberModel->createUpdateMember($request->all());
    }

    function deleteMember(Request $request){
        return $this->memberModel->find($request->id)->delete();
    }

    function getMember(Request $request){
        $result = array();
        $result["member"] = $this->memberModel->getMember($request->id);
        $result["updated_by"] = Auth::user()->id;
        $result["registered_at"] = date("Y-m-d");  
        return $result;
    }

    function generateReport(Request $request){
        return $this->reportClass->generateReport($request->all());
    }
}
