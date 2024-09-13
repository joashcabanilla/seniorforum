<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//Model
use App\Models\User;
use App\Models\MemberModel;

class GuestController extends Controller
{
    protected $data, $userModel, $memberModel;

    public function __construct()
    {
        $this->middleware('guest');
        $this->data = array();
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
    }

    function Index(){
        $this->data["titlePage"] = "SENIOR FORUM | Login";
        return view('Components.Login',$this->data);
    }

    function Login(Request $request){
        return $this->userModel->login($request);
    }
}
