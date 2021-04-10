<?php


namespace ppil\controller;


use ppil\models\Mark;

class MarkController
{
    public static function addMarkTo($from, $for, $mark)
    {
        if (isset(Mark::where('mark_from', '=', $from)->where('mark_for', '=', $for)->first()->mark))
            return false;
        else {
            $markElement = new Mark();
            $markElement->mark_from = $from;
            $markElement->mark_for = $for;
            $markElement->mark = $mark;
            $markElement->save();
            return true;
        }
    }

    public static function getMarkAverage($user)
    {
        $marks = Mark::where('mark_for', '=', $user)->get();
        $numberOfMark = 0;
        $average = 0;
        foreach ($marks as $mark) {
            $numberOfMark++;
            $average += $mark->mark;
        }
        return $average / $numberOfMark;
    }
}