<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberModel extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'memid',
        'pbno',
        'name',
        'branch',
        'updated_by',
        'registered_at'
    ];

    function memberTable($data){
        $query = $this->select(
            "id",
            "memid",
            "pbno",
            "branch",
            DB::raw("UPPER(name) as name"),
            "registered_at",
            "updated_by"
        );

        if(!empty($data->filterSearch)){
            $search = $data->filterSearch;
            $query->where(function($q) use($search){
                $q->orWhereRaw("UPPER(name) LIKE '%".strtoupper($search)."%'");
                $q->orWhereRaw("memid LIKE '%".$search."%'");
                $q->orWhereRaw("pbno LIKE '%".$search."%'");
            });
        }

        $query = !empty($data->filterBranch) ? $query->where("branch", $data->filterBranch) : $query;

        if(!empty($data->filterStatus)){
            if($data->filterStatus == "registered"){
                $query = $query->whereNotNull("updated_by");
            }else{
                $query = $query->whereNull("updated_by");
            }
        }
        $query = $query->orderBy("id", "ASC");

        return $query;
    }

    function createUpdateMember($data){
        $result = array();
        $result["status"] = "success";
        $rules = [
            'name' => ['required'],
            'branch' => ['required']
        ];

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            $result["error"] = $validator->errors();
            $result["status"] = "failed";
        }else{
            $this->updateOrCreate([
                "id" => !empty($data["id"]) ? $data["id"] : 0
            ],$data);
        }
    }

    function getMember($id){
        return $this->find($id);
    }

    function branchList(){
        $result = array();
        $branchList = $this->select("branch")->distinct()->get();
        if(!empty($branchList)){
            $result = $branchList;
        }
        return $result;
    }
}
