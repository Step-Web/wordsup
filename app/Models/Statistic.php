<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Statistic extends Model
{

    public static function setStatistic(int $user_id=0, int $score=0, string $type = 'word')
    {
        if($user_id == 0) return false;
        $statistic = new Statistic();
        $todayRecord = $statistic->where('user_id',$user_id)->whereDate('created_at', Carbon::today())->first();
        if ($todayRecord) {
            // Обновляем Запись за сегодня
            $res = $statistic->where('id', $todayRecord->id)->update([
                'task' => $todayRecord->task+1,
                'score' => $todayRecord->score+$score,
                'type' => $type,
            ]);
        } else {
            // Вставляем записи за сегодня
            $statistic->user_id = $user_id;
            $statistic->task = 1;
            $statistic->type = $type;
            $statistic->score = $score;
            $res = $statistic->save();
        }
        return $res;

    }


    public static function getTopUserPeriod(string $period = 'all'){
        if($period == 'month'){
            $users = DB::table('statistics')
                ->join('users', 'users.id', '=', 'statistics.user_id')
                ->select('users.id', 'username','userpic', DB::raw('SUM(statistics.score) as score'))
                ->groupBy('users.id')
                ->where('statistics.created_at', '>=', now()->subDays(30))
                ->limit(100)
                ->get();


        } elseif($period == 'week'){
            $users = DB::table('statistics')
                ->join('users', 'users.id', '=', 'statistics.user_id')
                ->select('users.id', 'username','userpic', DB::raw('SUM(statistics.score) as score'))
                ->groupBy('users.id')
                ->where('statistics.created_at', '>=', now()->subDays(7))
                ->limit(100)
                ->get();
        } elseif($period == 'day'){
            $users = DB::table('statistics')
                ->join('users', 'users.id', '=', 'statistics.user_id')
                ->select('users.id', 'username','userpic', DB::raw('SUM(statistics.score) as score'))
                ->groupBy('users.id')
                ->whereDate('statistics.created_at', date('Y-m-d'))
                ->limit(100)
                ->get();
        } else{
            $users = User::select('id','username','userpic','score')->where('score', '>', 0)->limit(100)->get();
        }
        $users = $users->keyBy('id');
        $users = $users->sortByDesc('score');
        return $users;
    }

    public static function userPosition(int $score=0)
    {
        $pos =  User::select(DB::raw('COUNT(*) AS pos'))->where('score', '>=',$score)->first();
        return $pos->pos;
    }


}
