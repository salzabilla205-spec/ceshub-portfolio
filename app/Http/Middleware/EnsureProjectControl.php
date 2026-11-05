<?php
namespace App\Http\Middleware;
use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EnsureProjectControl {
 public function handle(Request $request,Closure $next,string $module='route') {
  $slug=$module==='route'?($request->route('scale')??'living-lab'):$module;
  $project=Project::where('slug',$slug)->first();
  if(!$project && $module==='lab-scale')$project=Project::where('dashboard_url','like','%/dashboard/lab-scale')->first();
  abort_unless($project,404,'Project kontrol belum dikonfigurasi.');
  $credential=Auth::guard('dashboard')->user();
  if(!$credential || (int)$credential->project_id!==(int)$project->id){
   if($request->expectsJson())return response()->json(['message'=>'Masuk dengan sandi project sebelum menggunakan kontrol.'],403);
   return redirect()->route('dashboard.pengaturan',$project->slug);
  }
  return $next($request);
 }
}
