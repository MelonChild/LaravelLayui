<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class UserAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $user = Auth::guard()->user();
        View::share('userInfo', $user);
        if (!session('navs')) {
            $id = $user['id'];
            if ($id == 1) {
                $navs = \App\Models\Nav::orderBy('sort')->get()->toArray();
                session(['isAdmin' => true]);
            } else {
                $navs = \App\Models\Nav::whereHas('roles', function($query) use ($id) {
                            $ids = \App\Models\User::find($id)->roles()->where('active', 1)->pluck('id')->toArray();
                            $query->whereIn('id', $ids);
                        })->orderBy('sort')->get()->toArray();
                session(['isAdmin' => false]);
            }
            $newNavs = [];
            foreach ($navs as $key => $nav) {
                if ($nav['pid'] == 0) {
                    $childtemp = [];
                    $temp = $nav;
                    foreach ($navs as $key2 => $childnav) {
                        if ($childnav['pid'] == $nav['id']) {
                            $childtemp[] = $childnav;
                        }
                    }
                    count($childtemp) > 0 && $temp['children'] = $childtemp;
                    $newNavs[] = $temp;
                    unset($temp);
                }
            }
            session(['navs' => $navs]);
        }
        View::share('isAdmin', true);

        return $next($request);
    }

}
