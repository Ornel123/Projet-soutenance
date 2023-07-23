<?php

namespace App\Http\Controllers;

use App\Models\UE;
use App\Models\Etudiant;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class AdminController extends Controller
{
    public function newAdmin(){
        return view('pages.admin.admincreate');
    }
    public function newProf(){
        $allUes = Ue::all();
        return view('pages.admin.profcreate')->with('ues',$allUes);
    }
    public function newAdminForm(Request $req){

        $userExist = User::where('email',$req->adminemail)->get();
        if(count($userExist) > 0){
            return Redirect::back()->withErrors(["Un Utilisateur avec ce email existe déjà !"]);
        }

        $user = new User();
        $user->email = $req->adminemail;
        $user->name = $req->adminnom;
        $user->password = Hash::make($req->adminpassword);
        $user->role = "Admin";
        $user->email_verified_at = new DateTime();
        $user->ue_id = 0;
        $user->save();

        return Redirect::back()->withSuccess("Administrateur creer avec success !");
    }

    public function newProfForm(Request $req){

        $userExist = User::where('email',$req->profemail)->get();
        $theUe = Ue::where('id',$req->ueId)->first();

        if(count($userExist) > 0){
            return Redirect::back()->withErrors(["Un Utilisateur avec ce email existe déjà !"]);
        }
        if($theUe->prof_id != 0){
            return Redirect::back()->withErrors(["Ce UE a deja un enseignants attribue !"]);
        }

        $user = new User();
        $user->email = $req->profemail;
        $user->name = $req->profnom;
        $user->password = Hash::make($req->profpassword);
        $user->role = "Enseignant";
        $user->email_verified_at = new DateTime();
        $user->ue_id = $req->ueId;
        $user->save();

        $theUe->prof_id = $user->id;
        $theUe->save();

        return Redirect::back()->withSuccess("Enseignant creer avec success !");
    }
}
