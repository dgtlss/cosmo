<?php

namespace Dgtlss\Cosmo\Controllers;

use Dgtlss\Cosmo\Models\CosmoError;

class CosmoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    private function checkGateAccess()
    {
        $allowedEmails = config('cosmo.guarding.users');
        $allowedRoles = config('cosmo.guarding.roles');

        // If user is allowed by email, allow access
        if (!empty($allowedEmails)) {
            $allowedEmails = explode(',', $allowedEmails);
            if (!in_array(auth()->user()->email, $allowedEmails)) {
                abort(403);
            }
        }

        // If user is not allowed by email, check if user is allowed by role
        if (!empty($allowedRoles)) {
            $allowedRoles = explode(',', $allowedRoles);
            foreach ($allowedRoles as $role) {
                if (auth()->user()->$role) {
                    return;
                }
            }
            abort(403);
        }

        // If no guarding is set, deny access to all users
        if(empty($allowedEmails) && empty($allowedRoles)) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkGateAccess(); // Check if user is allowed to view Cosmo
        $errors = CosmoError::orderBy('created_at','desc')->paginate(30);
        return view('cosmo::dashboard.index',compact('errors'));
    }

    public function show($uid)
    {
        $this->checkGateAccess(); // Check if user is allowed to view Cosmo
        $error = CosmoError::where('uid',$uid)->firstOrFail();
        return view('cosmo::dashboard.show',compact('error'));
    }
}