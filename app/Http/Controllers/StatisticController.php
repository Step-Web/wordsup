<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Session;

class StatisticController extends Controller
{
    public function statistic(Request $req, int $user_id)
    {   $act = (!empty($req->query('act'))?$req->query('act'):'');
        $year = (!empty($req->query('year'))?$req->query('year'):date('Y'));
        $period = (!empty($req->query('period'))?$req->query('period'):'');

        if(!empty($act)){
            $score = 0;
            $data = array();
             $now = Carbon::now();

            if(!empty($period)) {

                $period = Carbon::now()->subDays($period);
                $q = DB::table('statistics')->select('created_at','score')->where('user_id',$user_id)->whereBetween('created_at', [$period, $now])->get();

            } else {
                $q = DB::table('statistics')->select('created_at','score')->where('user_id',$user_id)->whereYear('created_at', $year)->get();
            }

            foreach ($q as $v){
                $row['added'] = rusDate($v->created_at);
                $row['score'] = $v->score;
                $data['chart'][] = $row;
                $score += $v->score;
            }
            $data['score'] = $score;
         //   echo '<pre>'; print_r($year);echo '</pre>';
         // echo '<pre>'; print_r($data);echo '</pre>'; exit();
            return response()->json($data);
        }
        //$statistic = Statistic::where('user_id',$user_id)->get();
        $years = Statistic::select(DB::raw('YEAR(created_at) AS year'))->where('user_id',$user_id)->distinct()->pluck('year');
        return view('user.statistic', ['user_id' => $user_id, 'years' => $years,'year' => $year]);
       // dump($statistic);

    }

    public function setStatistic(Request $req)
    {
        $user_id = ($req->query('user_id')) ? $req->query('user_id') : 0;
        $score = ($req->query('score')) ? $req->query('score') : 0;
        $type = ($req->query('type')) ? $req->query('type') : 'word';
        return Statistic::setStatistic($user_id, $score, $type);
    }


    public function topuser(string $period)
    {
        $users = Statistic::getTopUserPeriod($period);
        $aName = array('all' => 'всё время', 'month' => 'месяц', 'week' => 'неделю', 'day' => 'сегодня');

        $pos = '';
        if (session()->get('user.id') > 0){
            $user_id = session()->get('user.id');
            $pos = $users->keys()->search($user_id); //  Позиция пользователя
        }

        return view('user.topuser', ['users' => $users, 'period' => $period, 'aName' => $aName, 'pos' => $pos]);
    }





}
