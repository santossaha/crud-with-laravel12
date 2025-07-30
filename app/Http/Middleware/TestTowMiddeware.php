<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class TestTowMiddeware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        echo "Middleware TestTowMiddeware executed.<br>";
        return $next($request);
    }

    public function chunkingResults(){
        DB::table('users')->orderBy('id')->chunk(100, function(Collection $users) {
            foreach($users as $user){
                return $user;
            }
        });
    }

    //chuncksById

    public function chunckById(){
        DB::table('users')->where('active', false)
        ->chunckById(100, function(Collection $users) {
            foreach($users as $user){
                DB::table('users')
                    ->where('id', $user->id)
                        ->update(['active' => true]);
            }
        });

        //user where condition
        DB::table('users')->where(function ($query) {
            $query->where('credits', 1)->orWhere('credits', 2);

        })->chunkById(100, function(Collection $users) {
            foreach($users as $user){
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['credits' => 3]);
            }
        });
    }

    //Lazy Collaction

    public function LazyCollaction(){
        DB::table('users')->orderBy('id')->lazy()->each(function(Object $users){

            foreach($users as $user){
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['devide', 3]);
            }
        });

        //LazyById

        DB::table('users')->where('active', true)
    }




}
